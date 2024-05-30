<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Sales extends CI_Controller
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

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $get = $this->input->get();
    $tot_actual = 0;
    $tot_target = 0;
    $tot_tunai = 0;
    $tot_kredit = 0;
    $user = $this->login;

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'group_by_sales_coordinator' => true,
      'search' => $get['search'],
      // 'tahun' => get_y(),
      // 'bulan' => get_m(),
    ];
    $re_sc = $this->m_dms->getTeamStructureManagement($f_sc);


    $f_tot_sales = [
      'id_dealer' => $user->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count'
    ];
    $total_sales = (int)$this->m_so->getSalesOrderIndividu($f_tot_sales)->row()->count;

    $item = [];

    foreach ($re_sc->result() as $rs) {

      $f_kry = ['id_team' => $rs->id_team, 'return' => 'multi_id_karyawan_dealer'];
      $id_karyawan_dealer_multi = $this->m_dms->getTeamSalesPeople($f_kry);

      $f_kry = ['id_team' => $rs->id_team, 'return' => 'multi_honda_id'];
      $honda_id_multi = $this->m_dms->getTeamSalesPeople($f_kry);

      $so = $this->m_so->getSalesOrderActivity(arr_in_sql($id_karyawan_dealer_multi), $user, arr_in_sql($honda_id_multi));

      $f_sales = [
        'id_karyawan_dealer_in' => arr_in_sql($id_karyawan_dealer_multi),
        'id_dealer' => $user->id_dealer,
        'bulan_so' => get_ym(),
        'select' => 'count'
      ];
      $sales = (int)$this->m_so->getSalesOrderIndividu($f_sales)->row()->count;

      $f_sales = [
        'id_karyawan_dealer_in' => arr_in_sql($id_karyawan_dealer_multi),
        'id_dealer' => $user->id_dealer,
        'bulan_so' => get_ym(),
        'status_delivery' => 'delivered',
        'select' => 'count'
      ];
      $delivered = (int)$this->m_so->getSalesOrderIndividu($f_sales)->row()->count;

      $f_sales = [
        'id_karyawan_dealer_in' => arr_in_sql($id_karyawan_dealer_multi),
        'id_dealer' => $user->id_dealer,
        'bulan_so' => get_ym(),
        'status_delivery' => 'in_progress',
        'select' => 'count'
      ];
      $in_progress = (int)$this->m_so->getSalesOrderIndividu($f_sales)->row()->count;

      $item[] = [
        'id' => $rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'phone' => $rs->no_hp,
        'team' => $rs->nama_team,
        'team_sales' => $sales,
        'total_sales' => $total_sales,
        'delivered' => $delivered,
        'in_progres' => $in_progress
      ];

      $tot_actual += $so['actual'];
      $tot_target += $so['target'];
      $tot_tunai += $so['tunai'];
      $tot_kredit += $so['kredit'];
    }

    //Order By
    if (count($item) > 0) {
      if (strtolower($this->input->get('sort')) == 'tertinggi') {
        array_sort_by_column($item, "actual", SORT_DESC);
      } elseif ($this->input->get('sort') == 'terendah') {
        array_sort_by_column($item, "actual", SORT_ASC);
      } elseif ($this->input->get('sort') == 'a-z') {
        array_sort_by_column($item, "name", SORT_ASC);
      } elseif ($this->input->get('sort') == 'z-a') {
        array_sort_by_column($item, "name", SORT_DESC);
      }
    }

    $result = [
      'actual' => $tot_actual,
      'target' => $tot_target,
      'tunai' => $tot_tunai,
      'kredit' => $tot_kredit,
      'item' => $item,
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
