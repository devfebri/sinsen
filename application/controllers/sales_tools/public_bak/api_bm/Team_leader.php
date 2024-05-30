<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Team_leader extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_sc_notes', 'm_notes');
    $this->load->model('m_master_user', 'm_user');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_dms');
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_sc_alert', 'm_alert');
    $this->load->model('m_sc_auth', 'm_auth');
    $this->load->model('M_sc_sp_home', 'm_home');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function submit_message()
  {
    $post = $this->input->post();
    $mandatory = [
      'master_subject_id' => 'required',
      'message'        => 'required',
      'employee_id'        => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_sbj = [
      'master_subject_id' => $post['master_subject_id'],
      'cek_referensi' => true
    ];
    $sbj = $this->m_master->getSubject($f_sbj);

    $f_user = [
      'id_karyawan_dealer_int' => $post['employee_id'],
      'cek_referensi' => true
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

    $ins_detail = [
      'iid'        => $id_message,
      'vusername' => $user_penerima->username,
      'bissent' => 1
    ];

    // $tes = ['ins_msg' => $ins_msg, 'ins_detail' => $ins_detail];
    // send_json($tes);

    $this->db->trans_begin();
    $this->db->insert('dms_detail_message', $ins_msg);
    $this->db->insert('dms_master_message', $ins_detail);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $params = [
        'judul' => $sbj->name,
        'pesan' => $post['message'],
        'command' => '',
        'for' => 'h1',
        'is_mobile' => false,
        'regid' => [$user_penerima->regid]
      ];
      send_fcm($params);
      $msg = ['Message has been sent'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function all_activity()
  {
    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $karyawan = $this->m_master->getKaryawan($f_k);
    // send_json($karyawan);
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      'return' => 'multi_id_karyawan_dealer_int'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

    $f_act = [
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer_int_in' => arr_in_sql($res_kry),
      'select' => 'count_activity',
      'check_date_null' => true,
      'bulan' => get_ym(),
      'group_by_id_kry_id_kategori' => true
    ];
    $data = $this->m_activity->getActivity($f_act)->result();

    $result = [];
    foreach ($data as $rs) {
      $result[] = [
        'id' => (int)$rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'total' => (int)$rs->total,
        'categories' => $rs->nama_kategori
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function detail()
  {
    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $karyawan = $this->m_master->getKaryawan($f_k);
    // send_json($karyawan);
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
    ];
    $sc = $this->m_dms->getTeamStructureManagement($f_kry)->row();
    $rank = 1;
    $alert = 0;
    $stats = 0;
    $activity = 0;
    $notes = 0;
    $result = [
      'name' => $sc->nama_lengkap,
      'image' => (string)$sc->image,
      'team' => (string)$sc->nama_team,
      'phone' => (string)$sc->no_hp,
      'rank' => $rank,
      'alert' => $alert,
      'stats' => $stats,
      'activity' => $activity,
      'notes' => $notes,
    ];
    send_json(msg_sc_success($result, NULL));
  }
  function alert()
  {
    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $karyawan = $this->m_master->getKaryawan($f_k);
    // send_json($karyawan);
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
    ];
    $sc = $this->m_dms->getTeamStructureManagement($f_kry)->row();

    $Lost_sales = 0;
    $not_followed = 0;
    $cancelled_spk = 0;
    $result = [
      'info' => '',
      'lost_sales' => $Lost_sales,
      'not_followed' => $not_followed,
      'cancelled_spk' => $cancelled_spk,
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function stats()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $kry = $this->m_master->getKaryawan($f_k);

    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
      'return' => 'multi_id_karyawan_dealer'
    ];
    $res_kry_team = $this->m_dms->getTeamSalesPeople($f_kry);

    $f_target = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'group_by_kategori_kendaraan' => true,
      'join_kategori_kendaraan' => true,
      'select' => 'kategori_kendaraan'
    ];
    $kategori = $this->m_dms->getH1TargetManagement($f_target)->result();
    foreach ($kategori as $ktg) {
      $filter_actual_sales = [
        'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
        'id_dealer' => $this->login->id_dealer,
        'id_kategori_kendaraan' => $ktg->id_kategori,
        'bulan_so' => get_ym(),
        'select' => 'count'
      ];
      $actual_sales = (int)$this->m_so->getSalesOrderIndividu($filter_actual_sales)->row()->count;

      $f_target = [
        'id_karyawan_dealer' => $kry->id_karyawan_dealer,
        'id_kategori_kendaraan' => $ktg->id_kategori,
        'id_dealer' => $this->login->id_dealer,
        'tahun' => get_y(),
        'bulan' => get_m(),
        'select' => 'sum_sales'
      ];
      $target = (int)$this->m_dms->getH1TargetManagement($f_target)->row()->sum_sales;

      $info[] = [
        'id' => (int)$ktg->id_kategori_int,
        'name' => $ktg->kategori,
        'actual' => (int)$actual_sales,
        'target' => (int)$target
      ];
    }

    //Prospek
    //Bulan Ini
    $f_act = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'id_dealer' => $this->login->id_dealer,
      'bulan_prospek' => get_ym(),
      'select' => 'count'
    ];
    $actual = (int)$this->m_prospek->getProspek($f_act)->row()->count;

    $f_target = [
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_prospek'
    ];
    $target = (int)$this->m_dms->getH1TargetManagement($f_target)->row()->sum_prospek;
    $this_month = [
      'actual' => $actual,
      'target' => $target
    ];

    //Bulan Kemarin
    $f_act = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'id_dealer' => $this->login->id_dealer,
      'bulan_prospek' => bulan_kemarin(get_ymd()),
      'select' => 'count'
    ];
    $actual = (int)$this->m_prospek->getProspek($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => substr(bulan_kemarin(get_ymd()), -2),
      'select' => 'sum_prospek'
    ];
    // send_json($f_target);
    $target = (int)$this->m_dms->getH1TargetManagement($f_target)->row()->sum_prospek;
    $last_month = [
      'actual' => $actual,
      'target' => $target
    ];

    $avg = [
      'actual' => ROUND(($this_month['actual'] + $last_month['actual']) / 2),
      'target' => ROUND(($this_month['target'] + $last_month['target']) / 2),
    ];
    $prospek = [
      'this_month' => $this_month,
      'last_month' => $last_month,
      'avg' => $avg
    ];

    //Unit Sales
    //Bulan Ini
    $f_act = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count'
    ];
    $actual = (int)$this->m_so->getSalesOrderIndividu($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_sales'
    ];
    $target = (int)$this->m_dms->getH1TargetManagement($f_target)->row()->sum_sales;
    $this_month = [
      'actual' => $actual,
      'target' => $target
    ];

    //Bulan Kemarin
    $f_act = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => bulan_kemarin(get_ymd()),
      'select' => 'count'
    ];
    $actual = (int)$this->m_so->getSalesOrderIndividu($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => substr(bulan_kemarin(get_ymd()), -2),
      'select' => 'sum_prospek'
    ];
    // send_json($f_target);
    $target = (int)$this->m_dms->getH1TargetManagement($f_target)->row()->sum_prospek;
    $last_month = [
      'actual' => $actual,
      'target' => $target
    ];

    $avg = [
      'actual' => ROUND(($this_month['actual'] + $last_month['actual']) / 2),
      'target' => ROUND(($this_month['target'] + $last_month['target']) / 2),
    ];
    $unit = [
      'this_month' => $this_month,
      'last_month' => $last_month,
      'avg' => $avg
    ];

    //Testing
    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
      'bulan_so' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];
    $res_month = $this->m_so->getRankSalesPeopleOnTeam($f_kry);
    $r_month = isset($res_month[$kry->id_karyawan_dealer]) ? $res_month[$kry->id_karyawan_dealer]['rank'] : 0;

    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
      'bulan_so' => bulan_kemarin(get_ym() . '-01'),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];

    $res_month_1 = $this->m_so->getRankSalesPeopleOnTeam($f_kry);
    $r_month_1 = isset($res_month_1[$kry->id_karyawan_dealer]) ? $res_month_1[$kry->id_karyawan_dealer]['rank'] : 0;
    $rank = [
      'this_month' => [
        'rank' => $r_month,
        'total' => count($res_month),
      ],
      'last_month' => [
        'rank' => $r_month_1,
        'total' => count($res_month_1),
      ]
    ];

    $f_d = [
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
    ];
    $d_tm_t = $this->m_dms->getTargetDiskon($f_d);

    $f_d_a = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'bulan_so' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
    ];
    $d_tm_a = $this->m_so->getPenjualanDenganDiskon($f_d_a);

    $bulan_kemarin = bulan_kemarin(get_ym() . '-01');
    $f_d = [
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'tahun' => substr($bulan_kemarin, 0, 4),
      'bulan' => substr($bulan_kemarin, -2),
    ];
    $d_lm_t = $this->m_dms->getTargetDiskon($f_d);

    $f_d_a = [
      'id_karyawan_dealer_in' => arr_in_sql($res_kry_team),
      'bulan_so' => $bulan_kemarin,
      'id_dealer' => $this->login->id_dealer,
    ];
    $d_lm_a = $this->m_so->getPenjualanDenganDiskon($f_d_a);

    $discount = [
      'this_month' => [
        'actual' => (int)$d_tm_a,
        'target' => (int)$d_tm_t,
      ],
      'last_month' => [
        'actual' => (int)$d_lm_a,
        'target' => (int)$d_lm_t,
      ],
    ];

    $data = [
      'info' => isset($info) ? $info : [],
      'discount' => isset($discount) ? $discount : [],
      'rank' => $rank,
      'prospek' => isset($prospek) ? $prospek : [],
      'unit' => isset($unit) ? $unit : [],
    ];
    $response = msg_sc_success($data, NULL);
    send_json($response);
  }

  function activity()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $kry = $this->m_master->getKaryawan($f_k);

    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
      'return' => 'multi_id_karyawan_dealer_int'
    ];
    $res_kry_team = $this->m_dms->getTeamSalesPeople($f_kry);

    $f_act = [
      'id_karyawan_dealer_int_in' => arr_in_sql($res_kry_team),
      'page' => $get['page'],
      'group_by_tanggal' => true,
      'order' => 'new'
    ];
    $tanggal = $this->m_activity->getActivity($f_act)->result();
    $data = [];
    foreach ($tanggal as $tgl) {
      $f_act_tgl = [
        'id_karyawan_dealer_int_in' => arr_in_sql($res_kry_team),
        'tanggal' => $tgl->tanggal,
        'select' => 'show_activity_sc_sales_person'
      ];
      $item = $this->m_activity->getActivity($f_act_tgl)->result();

      $data[] = [
        'badge' => false,
        'date' => tgl_indo($tgl->tanggal),
        'list' => $item
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function team()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $kry = $this->m_master->getKaryawan($f_k);

    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
    ];
    $res_kry_team = $this->m_dms->getTeamSalesPeople($f_kry)->result();
    $data = [];
    foreach ($res_kry_team as $sc) {
      $rank = 1;
      $position_info = '';
      $position_number = 0;
      $notes = '';
      $notes_datetime = '';
      $prospect_actual = 0;
      $prospect_target = 0;
      $spk_actual = 0;
      $spk_target = 0;
      $sales_actual = 0;
      $sales_target = 0;
      $data[] = [
        'id' => (int)$sc->id_karyawan_dealer_int,
        'name' => $sc->nama_lengkap,
        'image' => (string)$sc->image,
        'rank' => $rank,
        'position_info' => $position_info,
        'position_number' => (int)$position_number,
        'notes_datetime' => $notes_datetime,
        'notes' => $notes,
        'prospect_actual' => $prospect_actual,
        'prospect_target' => $prospect_target,
        'spk_actual' => $spk_actual,
        'spk_target' => $spk_target,
        'sales_actual' => $sales_actual,
        'sales_target' => $sales_target,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  function index()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_k = [
      'id_karyawan_dealer_int' => $this->input->get('employee_id'),
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $kry = $this->m_master->getKaryawan($f_k);

    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
      'return' => 'multi_honda_id_id_karyawan_dealer_result_all'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

    $f_kry = [
      'id_sales_coordinator' => $kry->id_karyawan_dealer,
    ];
    $kry = $this->m_dms->getTeamStructureManagement($f_kry)->row();

    $prospek = $this->m_prospek->getProspekActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($prospek['hot']);
    unset($prospek['medium']);
    unset($prospek['low']);


    $spk = $this->m_spk->getSPKActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($spk['spk_dengan_program']);


    $sales = $this->m_so->getSalesOrderActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));


    // send_json($res_kry);

    $rank = 1;
    $total_activity = 0;
    $activity = [];
    foreach ($res_kry['multi_id_karyawan_dealer_int'] as $id_kry) {
      //Activity
      $f_act = [
        'id_karyawan_dealer_int' => $id_kry,
        // 'tanggal' => isset($get['date_activity']) == '' ? tanggal() : $get['date_activity'],
        'check_date_null' => true,
        'bulan' => get_ym(),
        'group_by_id_kry_id_kategori' => true,
        'select' => 'count_activity'
      ];
      $get_activity = $this->m_activity->getActivity($f_act)->result();
      foreach ($get_activity as $ga) {
        $act = [
          'id' => (int)$id_kry,
          'name' => $ga->nama_lengkap,
          'image' => image_karyawan($ga->image, 'laki-laki'),
          'total' => $ga->total,
          'categories' => $ga->nama_kategori
        ];
        $activity[] = $act;
        $total_activity += $ga->total;
      }
    }

    $result = [
      'name' => $kry->nama_lengkap,
      'image' => (string)$kry->image,
      'team' => (string)$kry->nama_team,
      'rank' => $rank,
      'phone' => (string)$kry->no_hp,
      'prospek' => $prospek,
      'spk' => $spk,
      'sales' => $sales,
      'sales' => $sales,
      'total_activity' => $total_activity,
      'activity' => $activity,
    ];
    send_json(msg_sc_success($result));
  }
}
