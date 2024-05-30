<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Activity extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_dms');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }
  function index()
  {

    $inbox_badge = $this->m_inbox->getInboxBadge($this->login->username);

    $filter = [
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count'
    ];
    $sales_program = (int)$this->m_prospek->getSalesProgram($filter)->row()->count;

    $karyawan = sc_user(['username' => $this->login->username])->row();
    // send_json($karyawan);
    $prospek = $this->m_prospek->getProspekActivity($karyawan->id_karyawan_dealer, $this->login, $karyawan->honda_id);
    // send_json($prospek);

    $filter_spk_program = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_spk' => get_ym(),
      'ada_program' => true,
      'select' => 'count',
    ];

    $filter_actual_spk = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_spk' => get_ym(),
      'select' => 'count',
    ];
    $actual_spk = $this->m_spk->getSPK($filter_actual_spk)->row()->count;
    // $spk_dengan_program = $this->m_spk->getSPK($filter_spk_program)->row()->count;


    $filter_target_sales = [
      'honda_id' => $karyawan->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_spk'
    ];
    $target_spk = $this->m_dms->getH1TargetManagement($filter_target_sales)->row()->sum_spk;

    $spk = [
      'actual' => (int)$actual_spk,
      'target' => (int)$target_spk,
    ];

    $rank = 0;

    $sales = $this->m_so->getSalesOrderActivity($karyawan->id_karyawan_dealer, $this->login, $karyawan->honda_id);

    // $actual_prospek = 100;
    // $actual_spk = 30;
    // $sales['actual_sales'] = 15;

    $prospek_spk = @($actual_spk / $prospek['actual']) * 100;
    $spk_sales = @($sales['actual'] / $actual_spk) * 100;
    $prospek_sales = @($sales['actual'] / $prospek['actual']) * 100;
    $success_rate = [
      [
        'id' => 1,
        'before' => 'Prospek',
        'after' => 'SPK',
        'value' => (int)$prospek_spk,
      ],
      [
        'id' => 2,
        'before' => 'SPK',
        'after' => 'Sales',
        'value' => (int)$spk_sales,
      ],
      [
        'id' => 2,
        'before' => 'Prospek',
        'after' => 'Sales',
        'value' => (int)$prospek_sales,
      ],
    ];

    //Overdue
    $f_act = [
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'tanggal_lebih_kecil' => tanggal(),
      'check_date_null' => true,
      'bulan' => get_ym(),
    ];
    $get_act_overdue = $this->m_activity->getActivity($f_act)->result();
    $act_overdue = [];
    foreach ($get_act_overdue as $dt) {
      $parent_id_int = $this->m_activity->getActivityParent($dt->parent_id)->parent_id_int;
      $act_overdue[] = [
        'id' => (int)$dt->id,
        'parent_id' => (int)$parent_id_int,
        'name' => $dt->name,
        'info' => $dt->info,
        'time' => $dt->time,
        'categories' => $dt->nama_kategori,
      ];
    }

    //Latest
    $f_act = [
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'tanggal' => tanggal(),
      // 'check_date_null' => true
      // 'LIMIT' => 'LIMIT 5',
      // 'select' => 'show_activity_sp'
    ];
    $get_act_latest = $this->m_activity->getActivity($f_act)->result();
    $act_latest = [];
    foreach ($get_act_latest as $dt) {
      $parent_id_int = $this->m_activity->getActivityParent($dt->parent_id)->parent_id_int;
      $act_latest[] = [
        'id' => (int)$dt->id,
        'parent_id' => (int)$parent_id_int,
        'name' => $dt->name,
        'info' => $dt->info,
        'time' => $dt->time,
        'categories' => $dt->nama_kategori,
      ];
    }

    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => [
        'rank' => $rank,
        'inbox_badge' => $inbox_badge,
        'sales_program' => $sales_program,
        'prospek' => $prospek,
        'spk' => $spk,
        'sales' => $sales,
        'success_rate' => $success_rate,
        'latest' => $act_latest,
        'overdue' => $act_overdue
      ],
    ];
    send_json($result);
  }

  function read()
  {
    // send_json($this->login);
    $post = $this->input->post();
    if ($post['id'] == '') {
      $result = ['status' => 0, 'message' => ['ID Requided !']];
      send_json($result);
    }
    $where = [
      'iid' => $post['id'],
      'vusername' => $this->login->username
    ];

    $this->db->update('dms_master_message', ['bisread' => 1], $where);
    if ($this->db->affected_rows() > 0) {
      $result = [
        'status' => 1,
        'message' => ["Read data success"]
      ];
    } else {
      $result = [
        'status' => 0,
        'message' => ['Failed read data']
      ];
    }
    send_json($result);
  }

  function latest()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get      = $this->input->get();

    $f_act = [
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'tanggal' => tanggal(),
      // 'check_date_null' => true,
      'bulan' => get_ym(),
      // 'select' => 'show_activity_sp'
    ];
    if (isset($get['page'])) {
      $f_act['page'] = $get['page'];
    }
    $data = $this->m_activity->getActivity($f_act)->result();
    $act_latest = [];
    foreach ($data as $dt) {
      $parent_id_int = $this->m_activity->getActivityParent($dt->parent_id)->parent_id_int;
      $act_latest[] = [
        'id' => (int)$dt->id,
        'parent_id' => (int)$parent_id_int,
        'name' => $dt->name,
        'info' => $dt->info,
        'time' => $dt->time,
        'categories' => $dt->nama_kategori,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $act_latest
    ];
    send_json($result);
  }

  function overdue()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get      = $this->input->get();

    $f_act = [
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'tanggal_lebih_kecil' => tanggal(),
      'bulan' => get_ym(),
      'check_date_null' => true,
      // 'page' => $get['page'],
      // 'select' => 'show_activity_sp'
    ];
    if (isset($get['page'])) {
      $f_act['page'] = $get['page'];
    }
    $data = $this->m_activity->getActivity($f_act)->result();
    $act_overdue = [];
    foreach ($data as $dt) {
      $parent_id_int = $this->m_activity->getActivityParent($dt->parent_id)->parent_id_int;
      $act_overdue[] = [
        'id' => (int)$dt->id,
        'parent_id' => (int)$parent_id_int,
        'name' => $dt->name,
        'info' => $dt->info,
        'time' => $dt->time,
        'categories' => $dt->nama_kategori,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $act_overdue
    ];
    send_json($result);
  }
}
