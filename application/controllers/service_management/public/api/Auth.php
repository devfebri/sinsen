<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sc_auth', 'm_auth');
    $this->load->helper('sc');
  }
  public function login()
  {
    $f = ['skip_user' => 1];
    // $res = [
    //   'post' => $this->input->post(),
    //   'get' => $this->input->get(),
    //   'server' => $_SERVER,
    //   'post_var' => $_POST,
    //   'get_var' => $_GET
    // ];
    // send_json($res);

    middleWareAPI($f);
    $header = $this->input->request_headers();
    $token   = token_bearer($header);
    $filter = [
      'username' => $this->input->post('username'),
      'password' => $this->input->post('password'),
      'regid'    => $this->input->post('fcm_token'),
      'token'    => $token['token']
    ];
    
    if(date('Y-m-d H:i') >='2024-01-01 01:00' && date('Y-m-d H:i') <='2024-01-01 11:00'){
      $result = [
        'status' => 0,
        'message' => ["Maintenance"]
      ];
    }else{
      $cek_login = $this->m_auth->cekLogin($filter);
      if ($cek_login) {
        $cek_login->menu_available = [
          ['name' => 'Monitor AHASS', 'status' => 1],
          ['name' => 'Queue Management', 'status' => 1],
          ['name' => 'Booking Management', 'status' => 1],
          ['name' => 'Service Consultation', 'status' => 1],
          ['name' => 'PKB', 'status' => 1],
          ['name' => 'Parts', 'status' => 1],
          ['name' => 'Mechanic Scheduling', 'status' => 1],
        ];
        $result =
          [
            'status' => 1,
            'message' => ['Signed in successful'],
            'data' => $cek_login
          ];
      } else {
        $result = [
          'status' => 0,
          'message' => ["Username/Password is invalid"]
        ];
      }
    }
    send_json($result);
  }
  public function logout()
  {
    middleWareAPI(['id_user_not_null' => true]);
    $header    = $this->input->request_headers();
    $filter    = token_bearer($header);
    $res_ = $this->m_auth->logout($filter);
    if ($res_ == true) {
      $result =
        [
          'status' => 1,
          'message' => ['You have been logout!']
        ];
    } else {
      $result = res_token_not_found();
    }
    send_json($result);
  }
  public function rank()
  {
    $this->load->model('m_dms');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $user = middleWareAPI();
    $karyawan = sc_user(['username' => $user->username])->row();

    $f_leader = ['id_karyawan_dealer' => $karyawan->id_karyawan_dealer];
    $get_leader = $this->m_dms->getOneLeader($f_leader);
    cek_referensi($get_leader, 'Leader ID');
    $ldr = $get_leader->row();
    $f_kry = [
      'id_sales_coordinator' => $ldr->id_sales_coordinator,
      'bulan_so' => get_ym(),
      'id_dealer' => $user->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];
    // send_json($f_kry);
    $rank_kry = $this->m_so->getRankSalesPeopleOnTeam($f_kry)[$karyawan->id_karyawan_dealer];

    $result = [
      'rank' => ordinal($rank_kry['rank']),
      'info' => info_rank($rank_kry)
    ];
    send_json(msg_sc_success($result, NULL));
  }
  public function test_notification()
  {

    $header    = $this->input->request_headers();
    $filter    = token_bearer($header);
    $res_ = $this->m_auth->validasiToken($filter);
    if ($res_) {
      $get = $this->input->get();
      // if (isset($get['email'])) {
      //   if ($get['email']!='') {
      //     $ins_notif[]=[

      //     ];
      //   }
      // }
      // $get_receiver = $this->;
      $result =
        [
          'status' => 1,
          'message' => ['success']
        ];
    } else {
      $result = res_token_not_found();
    }
    send_json($result);
  }

  public function forgot_password()
  {
    middleWareAPI();
    $filter    = $this->input->post();
    // send_json($filter);
    $res_ = $this->m_auth->cekUser($filter);
    if ($res_) {
      $res_ = $res_->row();
      // Generate a random salt

      $gen_id = base_convert(bin2hex($this->security->get_random_bytes(64) . $res_->email), 16, 36);
      if ($gen_id === FALSE) {
        $gen_id = hash('sha256', time() . mt_rand() . $res_->email);
      }

      $params = [
        'to_email' => $res_->email,
        'gen_id' => $gen_id
      ];
      // send_json($params);
      $forgot = $this->m_auth->setSendEmailForgotPassword($params);
      if ($forgot) {
        //Update Reset Password ID
        $upd_user = [
          'sc_reset_pass_id' => $gen_id,
          'sc_reset_pass_ke' => $res_->sc_reset_pass_ke + 1,
          'sc_reset_pass_at' => waktu_full()
        ];
        $this->db->update('ms_user', $upd_user, ['id_user' => $res_->id_user]);
        $result =
          [
            'status' => 1,
            'message' => ["We've sent an email. Click the link in the email to reset your password."]
          ];
      } else {
        $result = res_failed_send_email();
      }
    } else {
      $result = res_invalid_email();
    }
    send_json($result);
  }
  // function tes()
  // {
  //   $data = [
  //     'url' => base_url('dealer/dad/sadsa'),
  //     'tgl' => get_ymd(),
  //     'logo' => 'fewfe',
  //   ];
  //   $this->load->view('dealer/sales_tools/email_forgot_password', $data);
  // }
}
