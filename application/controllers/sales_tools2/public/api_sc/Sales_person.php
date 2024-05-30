<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Sales_person extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_cdb', 'm_cdb');
    $this->load->model('m_master_user', 'm_user');
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_sc_notes', 'm_notes');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_dms');
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_sc_alert', 'm_alert');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $filter_inbox = [
      'username' => $this->login->username,
      'sent' => 1,
      'bisread' => 0,
      'select' => 'count'
    ];
    $inbox_badge = $this->m_inbox->getInbox($filter_inbox)->row()->count > 0 ? true : false;
    $data['badge'] = $inbox_badge;

    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_dt = ['id_sales_coordinator' => $karyawan->id_karyawan_dealer];
    $data_result = $this->m_dms->getTeamStructureManagementDetail($f_dt);
    foreach ($data_result->result() as $dt) {
      $kry = new stdClass();
      $kry->id_karyawan_dealer = $dt->id_karyawan_dealer;
      $kry->honda_id = $dt->honda_id;

      $user = new stdClass();
      $user->id_dealer = $this->login->id_dealer;

      $prospek = $this->m_prospek->getProspekActivity($kry->id_karyawan_dealer, $user, $kry->honda_id);
      $sales = $this->m_so->getSalesOrderActivity($kry->id_karyawan_dealer, $user, $kry->honda_id);

      $data['item'][] = [
        'id' => (int)$dt->id_karyawan_dealer_int,
        'name' => $dt->nama_lengkap,
        'image' => (string)$dt->image,
        'actual' => $sales['actual'],
        'target' => $sales['target'],
        'hot' => $prospek['hot'],
        'medium' => $prospek['medium'],
        'low' => $prospek['low'],
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function notes()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required',
      'page'        => 'required',
    ];
    cek_mandatory($mandatory, $get);


    $get['id_karyawan_dealer_notes'] = $get['employee_id'];
    $get['perminggu'] = true;
    $get['order'] = ['field' => 'created_at', 'sort' => 'DESC'];
    $res_ = $this->m_notes->getNotes($get);
    $data = [];
    foreach ($res_->result() as $rs) {
      $data[] = [
        'id' => $rs->id_notes,
        'message' => $rs->message,
        'datetime' => tgl_indojam($rs->created_at, ' '),
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function submit_notes()
  {
    $post = $this->input->post();
    $mandatory = [
      'employee_id' => 'required',
      'message'        => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_kry = [
      'id_karyawan_dealer_int' => $post['employee_id'],
      'id_dealer' => $this->login->id_dealer,
      'cek_referensi' => true,
      'select' => 'all'
    ];
    $kry = $this->m_master->getKaryawan($f_kry);

    $karyawan = sc_user(['username' => $this->login->username])->row();


    $insert = [
      'id_karyawan_dealer_created' => $karyawan->id_karyawan_dealer_int,
      'id_karyawan_dealer_notes' => $kry->id_karyawan_dealer_int,
      'message' => $post['message'],
      'created_at' => waktu_full(),
      'created_by'            => $this->login->id_user
    ];
    // send_json($insert);

    $this->db->trans_begin();
    $this->db->insert('tr_sc_notes', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Notes has been saved'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function alert()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    $get['id_karyawan_int_sales_person'] = $get['employee_id'];
    $get['id_dealer'] = $this->login->id_dealer;
    $get['tanggal']   = tanggal();
    $res_ = $this->m_alert->getAlert($get);
    $data = [];
    foreach ($res_->result() as $rs) {
      $data[] = [
        'id' => $rs->id_alert,
        'title' => $rs->title,
        'info' => $rs->info,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
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
      'iid'       => $id_message,
      'vusername' => $user_penerima->username_sc,
      'bissent'   => 1
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

  function detail()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $get['id_karyawan_dealer_int'] = $get['employee_id'];
    $get['select'] = "all";
    $res_ = $this->m_master->getKaryawan($get);
    cek_referensi($res_, 'Employee ID');
    $kry = $res_->row();

    $f_so = [
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count',
    ];
    $actual = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    $filter_target_sales = [
      'honda_id' => $kry->honda_id,
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_sales'
    ];
    $target = $this->m_dms->getH1TargetManagement($filter_target_sales)->row()->sum_sales;


    $f_alert = [
      'id_karyawan_int_sales_person' => $kry->id_karyawan_dealer_int,
      'tanggal' => tanggal()
    ];
    $alert = $this->m_alert->getAlert($f_alert)->num_rows();
    $stats    = 0;
    $activity = 0;
    $f_notes = ['id_karyawan_dealer_notes' => $kry->id_karyawan_dealer_int];
    $notes    = $this->m_notes->getNotes($f_notes)->num_rows();
    $data = [
      'name' => $kry->nama_lengkap,
      'image' => (string)$kry->image,
      'actual' => (int)$actual,
      'target' => (int)$target,
      'alert' => $alert,
      'stats' => $stats,
      'activity' => $activity,
      'notes' => $notes,
    ];
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function activity()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);
    $f_act = [
      'id_karyawan_dealer_int' => $get['employee_id'],
      'page' => $get['page'],
      'group_by_tanggal' => true,
      'order' => 'new'
    ];
    $tanggal = $this->m_activity->getActivity($f_act)->result();
    $data = [];
    foreach ($tanggal as $tgl) {
      $f_act_tgl = [
        'id_karyawan_dealer_int' => $get['employee_id'],
        'tanggal' => $tgl->tanggal,
        'select' => 'show_activity_sc_sales_person'
      ];
      $item = $this->m_activity->getActivity($f_act_tgl)->result();

      $data[] = [
        // 'badge' => false,
        'date' => tgl_indo($tgl->tanggal),
        'item' => $item
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function stats()
  {
    $get = $this->input->get();
    $mandatory = [
      'employee_id' => 'required',
      'date' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $get['id_karyawan_dealer_int'] = $get['employee_id'];
    $get['select'] = "all";
    $res_ = $this->m_master->getKaryawan($get);
    cek_referensi($res_, 'Employee ID');
    $kry = $res_->row();

    $f_target = [
      // 'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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
        'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_prospek' => get_ym(),
      'select' => 'count'
    ];
    $actual = (int)$this->m_prospek->getProspek($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,

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
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_prospek' => bulan_kemarin(get_ymd()),
      'select' => 'count'
    ];
    $actual = (int)$this->m_prospek->getProspek($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count'
    ];
    $actual = (int)$this->m_so->getSalesOrderIndividu($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => bulan_kemarin(get_ymd()),
      'select' => 'count'
    ];
    $actual = (int)$this->m_so->getSalesOrderIndividu($f_act)->row()->count;

    $f_target = [
      'honda_id' => $kry->honda_id,
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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

    $s_coor = sc_user(['username' => $this->login->username])->row();

    //Testing
    $f_kry = [
      'id_sales_coordinator' => $s_coor->id_karyawan_dealer,
      'bulan_so' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];
    $res_month = $this->m_so->getRankSalesPeopleOnTeam($f_kry);
    $r_month = isset($res_month[$kry->id_karyawan_dealer]) ? $res_month[$kry->id_karyawan_dealer]['rank'] : 0;

    $f_kry = [
      'id_sales_coordinator' => $s_coor->id_karyawan_dealer,
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
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
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
}
