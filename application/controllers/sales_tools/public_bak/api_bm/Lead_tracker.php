<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Lead_tracker extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_dms');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_master_user', 'm_user');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $f_kry = [
      'id_dealer' => $this->login->id_dealer,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
      'return' => 'multi_honda_id_id_karyawan_dealer_result_all'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

    $prospek = $this->m_prospek->getProspekActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));

    unset($prospek['hot']);
    unset($prospek['medium']);
    unset($prospek['low']);

    // $spk = $this->m_spk->getSPKActivity(NULL, $this->login, NULL);
    $spk = $this->m_spk->getSPKActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));

    unset($spk['spk_dengan_program']);

    // $so = $this->m_so->getSalesOrderActivity(NULL, $this->login, NULL);
    $so = $this->m_so->getSalesOrderActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));


    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'group_by_sales_coordinator' => true,
      'cek_prospek_rendah' => true,
      'select' => 'show_target_actual_coordinator',
      'tahun_bulan_prospek' => get_ym(),
      'tahun_target' => get_y(),
      'bulan_target' => get_m(),
      'active' => 1
    ];

    $re_sc = $this->m_dms->getTeamStructureManagement($f_sc);
    $item = [];
    foreach ($re_sc->result() as $rs) {
      $f_kry = ['id_team' => $rs->id_team, 'return' => 'multi_honda_id_id_karyawan_dealer_result_all'];
      $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

      $filter_actual_sales = [
        'id_karyawan_dealer_in' => arr_in_sql($res_kry['multi_id_karyawan_dealer']),
        'id_dealer' => $this->login->id_dealer,
        'bulan_so' => get_ym(),
        'select' => 'count'
      ];
      $tot_sales = $this->m_so->getSalesOrderIndividu($filter_actual_sales)->row()->count;

      $prospek_sc = $this->m_prospek->getProspekActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));

      $spk_sc = $this->m_spk->getSPKActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));


      $item[] = [
        'id' => (int)$rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'team' => $rs->nama_team,
        'total_sales' => (int)$tot_sales,
        'actual' => (int)$spk_sc['actual'],
        'target' => (int)$prospek_sc['target'],
      ];
    }

    $result = [
      'prospek' => $prospek,
      'spk' => $spk,
      'sales' => $so,
      'item' => $item,
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function submit_message_all_team()
  {
    $post = $this->input->post();
    $mdt = [
      'master_subject_id' => 'requided',
      'message' => 'requided'
    ];
    cek_mandatory($mdt, $post);
    // send_json($post);

    $f_sbj = [
      'master_subject_id' => $post['master_subject_id'],
      'cek_referensi' => true
    ];
    $sbj = $this->m_master->getSubject($f_sbj);

    $f_user = [
      // 'id_karyawan_dealer_int' => $post['employee_id'],
      'group_by_username_sc' => true,
      'id_dealer' => $this->login->id_dealer,
      'role_sc' => 2,
      'active_sc' => 1
    ];
    $user_penerima = $this->m_user->getUser($f_user);


    $id_message = $this->m_dms->get_id_message($this->login->id_dealer);
    $ins_msg = [
      'iid'        => $id_message,
      'vtitle'     => $sbj->name,
      'vcontents'  => $post['message'],
      'bisdelete'  => 0,
      'imsgtype'   => $post['master_subject_id'],
      'sender_id'  => $this->login->id_user,
      'created_at' => waktu_full(),
      'created_by' => $this->login->id_user,
      'sent' => 1,
      'sending_at' => waktu_full(),
      'sending_by' => $this->login->id_user,
    ];
    // send_json($user_penerima->result());
    foreach ($user_penerima->result() as $rs) {
      $ins_detail[] = [
        'iid'        => $id_message,
        'vusername' => $rs->username_sc,
        'bissent' => 1
      ];
      if (($rs->regid == NULL || $rs->regid == '') == false) {
        $regid[] = $rs->regid;
      }
    }

    // $tes = ['ins_msg' => $ins_msg, 'ins_detail' => $ins_detail];
    // send_json($tes);

    $this->db->trans_begin();
    $this->db->insert('dms_detail_message', $ins_msg);
    $this->db->insert_batch('dms_master_message', $ins_detail);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      if (isset($regid)) {
        $params = [
          'judul' => $sbj->name,
          'pesan' => $post['message'],
          'command' => '',
          'for' => 'h1',
          'is_mobile' => false,
          'regid' => $regid
        ];
        send_fcm($params);
      }
      $msg = ['Message has been sent'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function prospek_rendah()
  {
    $get  = $this->input->get();
    $mandatory = ['page' => 'required'];
    cek_mandatory($mandatory, $get);
    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'group_by_sales_coordinator' => true,
      'cek_prospek_rendah' => true,
      'page' => $get['page'],
      'select' => 'show_target_actual_coordinator',
      'tahun_bulan_prospek' => get_ym(),
      'tahun_target' => get_y(),
      'bulan_target' => get_m(),
    ];

    $re_sc = $this->m_dms->getTeamStructureManagement($f_sc);
    $result = [];
    foreach ($re_sc->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'team' => $rs->nama_team,
        'actual' => (int)$rs->actual,
        'target' => (int)$rs->target,
      ];
    }
    send_json(msg_sc_success($result));
  }
}
