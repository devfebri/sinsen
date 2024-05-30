<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Home extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sc_auth', 'm_auth');
    $this->load->model('M_sc_sp_home', 'm_home');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');

    $this->load->helper('tgl_indo');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }
  public function sales_program()
  {
    $get = $this->input->get();
    $get['cek_periode'] = get_ymd();
    $res_ = $this->m_home->getSalesProgram($get);
    $new_res = [];
    foreach ($res_->result() as $val) {
      $res = [
        'id'         => (int)$val->id,
        'juklak_id'  => $val->juklak_id,
        'code'       => $val->code,
        'name'       => $val->name,
        'price'      => (int)$val->price,
        'date_start' => $val->date_start,
        'date_end'   => $val->date_end,
        'default'    => $val->otomatis == 1 ? true : false,
        'unit'       => $val->unit,
      ];
      $new_res[] = $res;
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }

  function activity()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get      = $this->input->get();

    $f_act = [
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'tanggal' => $get['date_activity'] == '' ? tanggal() : $get['date_activity'],
      'status' => $this->input->get('status'),
      'order' => $this->input->get('sort'),
      'check_date_null' => true,
      'bulan' => get_ym()
      // 'select' => 'show_activity_sp'
    ];
    $get_data = $this->m_activity->getActivity($f_act)->result();
    $data = [];
    foreach ($get_data as $dt) {

      $parent_id_int = $this->m_activity->getActivityParent($dt->parent_id)->parent_id_int;
      $data[] = [
        'id' => (int)$dt->id,
        'parent_id' => (int)$parent_id_int,
        'name' => $dt->name,
        'info' => $dt->info,
        'time' => $dt->time,
        'categories' => $dt->nama_kategori,
      ];
    }
    $data = [
      'total_activity' => count($data),
      'items' => $data
    ];
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function index()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get      = $this->input->get();

    //Activity
    $f_act = [
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'tanggal' => isset($get['date_activity']) == '' ? tanggal() : $get['date_activity'],
      'check_date_null' => true,
      'bulan' => get_ym()
      // 'select' => 'show_activity_sp'
    ];
    $get_activity = $this->m_activity->getActivity($f_act)->result();
    $activity = [];
    foreach ($get_activity as $dt) {
      $parent_id_int = $this->m_activity->getActivityParent($dt->parent_id)->parent_id_int;


      $activity[] = [
        'id' => (int)$dt->id,
        'parent_id' => (int)$parent_id_int,
        'name' => $dt->name,
        'info' => $dt->info,
        'time' => $dt->time,
        'categories' => $dt->nama_kategori,
      ];
    }

    $rank              = 1;
    $inbox_badge = $this->m_inbox->getInboxBadge($this->login->username);

    $info  = info_sisa_hari();

    $prospek = $this->m_prospek->getProspekActivity($karyawan->id_karyawan_dealer, $this->login, $karyawan->honda_id);

    $spk = $this->m_spk->getSPKActivity($karyawan->id_karyawan_dealer, $this->login, $karyawan->honda_id);
    $spk               = [
      'actual' => $spk['actual'],
      'target' => $spk['target'] == 0 ? 0 : $spk['target'],
    ];

    $sales = $this->m_so->getSalesOrderActivity($karyawan->id_karyawan_dealer, $this->login, $karyawan->honda_id);

    $f_prp = [
      'id_dealer' => $this->login->id_dealer,
      'id_kategori_activity' => 1,
      'check_date_null' => true,
      'select' => 'count_activity',
      'tanggal_lebih_kecil' => isset($get['date_activity']) == '' ? tanggal() : $get['date_activity'],
      'bulan' => get_ym(),
      // 'tanggal_lebih_kecil' => get_ymd(),
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      // 'join' => 'join_prospek',
      // 'status_prospek_not' => 'Deal'
    ];
    $tt_prospek = (int)$this->m_activity->getActivity($f_prp)->row()->total;

    $f_spk = [
      'id_dealer' => $this->login->id_dealer,
      'id_kategori_activity' => 2,
      'check_date_null' => true,
      'select' => 'count_activity',
      'tanggal' => $this->input->get('date_activity'),
      'join' => 'join_spk',
      'status_spk_not_in' => "'close','canceled'",
      'tanggal_lebih_kecil' => get_ymd(),
      'bulan' => get_ym(),
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int
    ];
    $tt_spk = (int)$this->m_activity->getActivity($f_spk)->row()->total;

    $f_sales = [
      'id_dealer' => $this->login->id_dealer,
      'id_kategori_activity' => 3,
      'check_date_null' => true,
      'select' => 'count_activity',
      'tanggal' => $this->input->get('date_activity'),
      'id_karyawan_dealer_int' => $karyawan->id_karyawan_dealer_int,
      'bulan' => get_ym(),
    ];
    $tt_sales = (int)$this->m_activity->getActivity($f_sales)->row()->total;

    $tugas_tertunda    = [
      'prospek' => $tt_prospek,
      'spk' => $tt_spk,
      'sales' => $tt_sales,
    ];

    $filter = [
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count'
    ];
    $tot_sales_program = (int)$this->m_prospek->getSalesProgram($filter)->row()->count;

    $data = [
      'rank'           => $rank,
      'inbox_badge'    => $inbox_badge,
      'info'           => $info,
      'prospek'        => $prospek,
      'spk'            => $spk,
      'sales'          => $sales,
      'tugas_terunda'  => $tugas_tertunda,
      'sales_program'  => $tot_sales_program,
      'total_activity' => count($activity),
      'activity'       => $activity
    ];
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
}
