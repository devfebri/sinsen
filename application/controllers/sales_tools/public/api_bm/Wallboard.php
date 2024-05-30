<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Wallboard extends CI_Controller
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
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_sc_sp_inbox', 'm_inbox');
    $this->load->model('m_md_csl_master', 'm_csl_m');
    $this->load->model('m_md_csl', 'm_csl');
    $this->load->model('m_h1_dealer_diskon', 'm_diskon');;


    $this->load->helper('sc');
    $this->load->helper('tgl_indo');
    $this->login = middleWareAPI();
  }

  function leaderboard()
  {
    $f_rank = [
      'tahun_bulan' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];

    $result = $this->m_so->getRankTeam($f_rank);
    $new_res = [];
    foreach ($result as $rs) {
      $f_kr = ['id_karyawan_dealer' => $rs['id_sales_coordinator'], 'select' => 'all'];
      $kry = $this->m_master->getKaryawan($f_kr)->row();
      $new_res[] = [
        'id' => (int)$kry->id_karyawan_dealer_int,
        'name' => $kry->nama_lengkap,
        'image' => image_karyawan($kry->image, $kry->jk),
        'team' => $rs['nama_team'],
        'rank' => $rs['rank'],
        'position_info' => $rs['position_info'],
        'position_number' => $rs['position_number'],
        'penjualan' => $rs['penjualan'],
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
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
    $info  = info_sisa_hari();

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'status' => 'Approved Disc',
      'select' => 'sum_nominal'
    ];

    $total_discount = (float)$this->m_diskon->getPengajuanDiskon($f_sc)->row()->sum_nominal;

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

    $spk = $this->m_spk->getSPKActivity(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login, arr_in_sql($res_kry['multi_honda_id']));
    unset($spk['spk_dengan_program']);

    $sales = $this->m_so->getAllSalesOrderTotalForHomeApk(arr_in_sql($res_kry['multi_id_karyawan_dealer']), $this->login,arr_in_sql($res_kry['multi_honda_id']));

    $f_rank = [
      'tahun_bulan' => get_ym(),
      'id_dealer' => $this->login->id_dealer,
      'cek_bulan_sebelumnya' => true
    ];

    $result = $this->m_so->getRankTeam($f_rank);
    $leaderboard = [];
    foreach ($result as $rs) {
      $f_kr = ['id_karyawan_dealer' => $rs['id_sales_coordinator'], 'select' => 'all'];
      $kry = $this->m_master->getKaryawan($f_kr)->row();
      $leaderboard[] = [
        'id'              => (int)$kry->id_karyawan_dealer_int,
        'name'            => $kry->nama_lengkap,
        'image'           => image_karyawan($kry->image, $kry->jk),
        'team'            => $rs['nama_team'],
        'rank'            => $rs['rank'],
        'position_info'   => $rs['position_info'],
        'position_number' => $rs['position_number'],
        'penjualan'       => $rs['penjualan'],
      ];
    }

    $result = [
      'badge' => $badge,
      'info' => $info,
      'total_discount' => $total_discount,
      'prospek' => $prospek,
      'spk' => $spk,
      'sales' => $sales,
      'leaderboard' => $leaderboard,
    ];
    send_json(msg_sc_success($result));
  }

  function performa_bulanan()
  {
    $get = $this->input->get();
    $mandatory = [
      'month' => 'required',
      'year' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    $tahun_bulan = $get['year'] . '-' . sprintf("%'.02d", $get['month']);
    $f_tot_sales = [
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => $tahun_bulan,
      'tgl_cetak_invoice_not_null' => true,
      'select' => 'count'
    ];
    $total_sales = (int)$this->m_so->getSalesOrderIndividu($f_tot_sales)->row()->count;
    $total_sales += (int)$this->m_so->getSalesOrderGCNoMesin($f_tot_sales)->row()->count;

    $filter_target_sales = [
      'id_dealer' => $this->login->id_dealer,
      'tahun' => $get['year'],
      'bulan' => sprintf("%'.02d", $get['month']),
      'select' => 'sum_sales'
    ];
    $target_sales =  (int)$this->m_dms->getH1TargetManagement($filter_target_sales)->row()->sum_sales;

    $result[] = [
      'id' => 1,
      'name' => 'H1',
      'description' => 'Target Penjualan Unit',
      'actual' => $total_sales,
      'actual_description' => 'Actual',
      'target' => $target_sales,
      'target_description' => 'Target',
      'info_title' => '',
      'info' => '',
      'type' => 1,
    ];
    $f_ahass = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => $tahun_bulan,
      'select' => 'target_ue',
    ];
    $trgt_h2 = $this->m_dms->getH23TargetManagementAHASS($f_ahass);
    $trgt_ue = 0;
    if ($trgt_h2->num_rows() > 0) {
      $trgt_ue = (int)$trgt_h2->row()->target_ue;
    }

    $f_wo = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan_wo' => $tahun_bulan,
      'select' => 'count',
    ];
    $actual_ue = (int)$this->m_wo->getWorkOrder($f_wo)->row()->count;
    $result[] = [
      'id' => 2,
      'name' => 'H23',
      'description' => 'Target Unit Masuk',
      'target' => $trgt_ue ?: 0,
      'actual_description' => 'Actual',
      'actual' => $actual_ue,
      'target_description' => 'Target',
      'info_title' => '',
      'info' => '',
      'type' => 1,
    ];


    $f_csl = [
      'id_dealer' => $this->login->id_dealer,
      'tahun' => $this->input->get('year'),
      'bulan' => $this->input->get('month'),
      // 'kategori' => $this->input->get('categories'),
      'select' => 'average'
    ];
    $actual_csl = $this->m_csl->getDetailActualUpladCSL($f_csl)->row()->average;
    $target_csl = $this->m_csl->getDetailTargetListUpladCSL($f_csl)->row()->average;
    $result[] = [
      'id' => 3,
      'name' => 'CSL',
      'description' => 'Target Pencapaian',
      'actual' => ROUND($actual_csl),
      'actual_description' => '',
      'target' => ROUND($target_csl),
      'target_description' => '',
      'info_title' => 'Hasil Dari',
      'info' => 'Januari - ' . bulan_pjg($get['month']) . ' ' . $get['year'],
      'type' => 2,
    ];
    send_json(msg_sc_success($result));
  }
}
