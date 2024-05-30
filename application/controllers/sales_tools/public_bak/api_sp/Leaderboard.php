<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Leaderboard extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sc_auth', 'm_auth');
    $this->load->model('M_sc_sp_home', 'm_home');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');

    $this->load->helper('tgl_indo');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $usr = sc_user(['username' => $this->login->username])->row();

    $f_kry = ['id_karyawan_dealer' => $usr->id_karyawan_dealer, 'select' => 'all'];
    $kry = $this->m_master->getKaryawan($f_kry);
    cek_referensi($kry, 'Employee ID');
    $kry = $kry->row();

    $f_leader = ['id_karyawan_dealer' => $kry->id_karyawan_dealer];
    $get_leader = $this->m_dms->getOneLeader($f_leader);
    cek_referensi($get_leader, 'Leader ID');
    $get_leader = $get_leader->row();
    // send_json($get_leader);
    $f_kry = [
      'id_sales_coordinator' => $get_leader->id_sales_coordinator,
      'bulan_so' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];
    // send_json($f_kry);
    $rank_all = $this->m_so->getRankSalesPeopleOnTeam($f_kry);
    // send_json($rank_all);
    $rank = $rank_all[$kry->id_karyawan_dealer];

    $data['info'] = [
      'id' => $kry->id_karyawan_dealer,
      'rank' => $rank['rank'],
      'name' => $kry->nama_lengkap,
      'image' => image_karyawan($kry->image, $kry->jk),
      'position_info' => $rank['position_info'],
      'position_number' => $rank['position_number'],
      'point' => $rank['penjualan'],
      'is_your' => $usr->username_sc == $this->login->username ? true : false,
    ];
    $list = [];
    unset($rank_all[$kry->id_karyawan_dealer]);
    foreach ($rank_all as $ls) {
      $f_kry = ['id_karyawan_dealer' => $ls['id_karyawan_dealer'], 'select' => 'all'];
      $kry = $this->m_master->getKaryawan($f_kry)->row();
      $list[] = [
        'id' => $ls['id_karyawan_dealer'],
        'rank' => $ls['rank'],
        'name' => $kry->nama_lengkap,
        'image' => image_karyawan($kry->image, $kry->jk),
        'position_info' => $ls['position_info'],
        'position_number' => $ls['position_number'],
        'point' => $ls['penjualan'],
        'is_your' => false,
      ];
    }
    $data['list'] = $list;

    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
}
