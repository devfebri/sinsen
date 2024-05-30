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
    $this->load->model('m_dms');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');

    $this->load->helper('tgl_indo');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function activity()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
      'return' => 'multi_id_karyawan_dealer_int'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);
    $get      = $this->input->get();

    $f_act = [
      'id_karyawan_dealer_int_in' => arr_in_sql($res_kry),
      'tanggal' => $this->input->get('date') == '' ? tanggal() : $this->input->get('date'),
      'sort' => $this->input->get('sort'),
      'kategori' => $this->input->get('categories'),
      'order' => $this->input->get('order'),
      // 'select' => 'show_activity_sp'
    ];
    $get = $this->m_activity->getActivity($f_act);
    $data = [];
    if ($get->num_rows() == 0) {
      // $data[] = [
      //   'id' => 0,
      //   'parent_id' => 0,
      //   'name' => '',
      //   'info' => '',
      //   'time' => '',
      //   'categories' => '',
      // ];
    } else {
      foreach ($get->result() as $dt) {
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
    }
    $result = [
      'total' => (int)$get->num_rows(),
      'item' => $data
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function index()
  {

    $f_badge = [
      'username' => $this->login->username,
      'select' => 'count',
      'bisread' => 0,
      'sent' => 1
    ];
    $badge = $this->m_inbox->getInbox($f_badge)->row()->count > 0 ? true : false;

    $karyawan = sc_user(['username' => $this->login->username])->row();

    //Testing
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      'bulan_so' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];
    $rank_kry = $this->m_so->getRankSalesPeopleOnTeam($f_kry);

    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
      'return' => 'multi_honda_id_id_karyawan_dealer_result_all'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);
    // send_json($res_kry);
    $prospek = $this->m_prospek->getProspekActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($prospek['hot']);
    unset($prospek['medium']);
    unset($prospek['low']);

    $spk = $this->m_spk->getSPKActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($spk['spk_dengan_program']);

    $sales = $this->m_so->getSalesOrderActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));

    $leaderboard = [];
    foreach ($res_kry['result'] as $rs) {
      $leaderboard[] = [
        'id' => $rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'rank' => $rank_kry[$rs->id_karyawan_dealer]['rank'],
        'position_info' => $rank_kry[$rs->id_karyawan_dealer]['position_info'],
        'position_number' => $rank_kry[$rs->id_karyawan_dealer]['position_number'],
        'penjualan' => $rank_kry[$rs->id_karyawan_dealer]['penjualan'],
      ];
    }

    array_sort_by_column($leaderboard, 'rank', SORT_ASC);

    $data = [
      'badge' => $badge,
      'prospek' => $prospek,
      'spk' => $spk,
      'sales' => $sales,
      'leaderboard' => $leaderboard
    ];
    send_json(msg_sc_success($data, NULL));
  }
  function leaderboard()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();

    //Testing
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      'bulan_so' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];
    $rank_kry = $this->m_so->getRankSalesPeopleOnTeam($f_kry);

    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
      'return' => 'multi_honda_id_id_karyawan_dealer_result_all'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

    $data = [];
    foreach ($res_kry['result'] as $rs) {
      // send_json($rs);
      $prospek = $this->m_prospek->getProspekActivity($rs->id_karyawan_dealer, $this->login, $rs->honda_id);
      $spk = $this->m_spk->getSPKActivity($rs->id_karyawan_dealer, $this->login, $rs->honda_id);
      $so = $this->m_so->getSalesOrderActivity($rs->id_karyawan_dealer, $this->login, $rs->honda_id);

      $data[] = [
        'id' => $rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'rank' => $rank_kry[$rs->id_karyawan_dealer]['rank'],
        'position_info' => $rank_kry[$rs->id_karyawan_dealer]['position_info'],
        'position_number' => $rank_kry[$rs->id_karyawan_dealer]['position_number'],
        'penjualan' => $rank_kry[$rs->id_karyawan_dealer]['penjualan'],
        'actual' => $so['actual'],
        'target' => $so['target'],
        'prospek_actual' => $prospek['actual'],
        'prospek_target' => $prospek['target'],
        'spk_actual' => $spk['actual'],
        'spk_target' => $spk['target'],
      ];
    }
    array_sort_by_column($data, 'rank', SORT_ASC);

    send_json(msg_sc_success($data, NULL));
  }
}
