<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Prospek extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_dms');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $get = $this->input->get();
    $tot_actual = 0;
    $tot_target = 0;
    $user = $this->login;

    $f_prp = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'no_so_null' => true,
    ];
    $tot_prospek_to_spk = $this->m_prospek->totalProspekSPK($f_prp);

    $f_prp = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'status_no_deal' => true
    ];
    $tot_prospek_lost_sales = $this->m_prospek->totalProspekSPK($f_prp);

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'group_by_sales_coordinator' => true,
      'search' => $get['search'],
      'active' => 1,
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
    ];
    $re_sc = $this->m_dms->getTeamStructureManagement($f_sc);
    $item = [];
    foreach ($re_sc->result() as $rs) {
      $f_kry = ['id_team' => $rs->id_team, 'return' => 'multi_id_karyawan_dealer'];
      $id_karyawan_dealer_multi = $this->m_dms->getTeamSalesPeople($f_kry);
      $f_kry = ['id_team' => $rs->id_team, 'return' => 'multi_honda_id'];
      $honda_id_multi = $this->m_dms->getTeamSalesPeople($f_kry);
      // send_json($honda_id_multi);
      $prospek = $this->m_prospek->getProspekActivity(arr_in_sql($id_karyawan_dealer_multi), $user, arr_in_sql($honda_id_multi));

      $item[] = [
        'id' => $rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'phone' => $rs->no_hp,
        'team' => $rs->nama_team,
        'team_sales' => count($id_karyawan_dealer_multi),
        'actual' => $prospek['actual'],
        'target' => $prospek['target'],
        'low_prospek' => $prospek['low'],
        'hot_prospek' => $prospek['hot']
      ];

      $tot_actual += $prospek['actual'];
      $tot_target += $prospek['target'];
    }

    //Order By
    if (strtolower($this->input->get('sort')) == 'tertinggi') {
      array_sort_by_column($item, "actual", SORT_DESC);
    } elseif ($this->input->get('sort') == 'terendah') {
      array_sort_by_column($item, "actual", SORT_ASC);
    } elseif ($this->input->get('sort') == 'a-z') {
      array_sort_by_column($item, "name", SORT_ASC);
    } elseif ($this->input->get('sort') == 'z-a') {
      array_sort_by_column($item, "name", SORT_DESC);
    }
    // send_json($item);
    $result = [
      'actual' => $tot_actual,
      'target' => $tot_target,
      'prospek_to_spk' => $tot_prospek_to_spk,
      'prospek_lost_sales' => $tot_prospek_lost_sales,
      'item' => $item,
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
