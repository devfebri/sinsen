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
    // send_json($karyawan);
    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
      'return' => 'multi_id_karyawan_dealer_int'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

    $f_act = [
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer_int_in' => arr_in_sql($res_kry),
      'status' => 'new',
      'select' => 'count_activity',
      'group_by_id_kry_id_kategori' => true,
      'bulan' => get_ym(),
    ];
    // send_json($f_act);
    $data = $this->m_activity->getActivity($f_act)->result();
    $result = [];
    foreach ($data as $rs) {
      $result[] = [
        'id' => $rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'total' => (int)$rs->total,
        'categories' => $rs->nama_kategori
      ];
    }
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

    $f_kry = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
      'return' => 'multi_honda_id_id_karyawan_dealer_result_all'
    ];
    $res_kry = $this->m_dms->getTeamSalesPeople($f_kry);

    $f_act = [
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer_int_in' => arr_in_sql($res_kry['multi_id_karyawan_dealer_int']),
      'status' => 'new',
      'select' => 'count_activity',
      'group_by_id_kry_id_kategori' => true,
      'bulan' => get_ym(),
    ];

    $result_data = $this->m_activity->getActivity($f_act)->result();
    $res_activity = [];
    foreach ($result_data as $rs) {
      $res_activity[] = [
        'id'         => $rs->id_karyawan_dealer_int,
        'name'       => $rs->nama_lengkap,
        'image'      => image_karyawan($rs->image, $rs->jk),
        'total'      => (int)$rs->total,
        'categories' => $rs->nama_kategori
      ];
    }

    $prospek = $this->m_prospek->getProspekActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($prospek['hot']);
    unset($prospek['medium']);
    unset($prospek['low']);

    $spk = $this->m_spk->getSPKActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($spk['spk_dengan_program']);

    $sales = $this->m_so->getSalesOrderActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));

    $result = [
      'badge' => $badge,
      'prospek' => $prospek,
      'spk' => $spk,
      'sales' => $sales,
      'activity' => $res_activity
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
