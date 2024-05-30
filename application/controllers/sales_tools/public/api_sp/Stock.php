<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Stock extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_sc_sp_stock', 'm_stock');
    $this->load->model('M_h2_api', 'm_h2_api');
    $this->load->model('M_h1_dealer_stok', 'm_stok');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function accessories()
  {
    $get = $this->input->get();
    // send_json($get);
    $get['select'] = 'select_tipe_kendaraan_tot_acc';
    $get['id_kategori_int'] = $this->input->get('type_id');
    $get['active'] = 1;
    $get['join'] = 'ptm';
    $get['group_by'] = 'id_tipe_kendaraan';
    $get['accessories'] = "'ACCEC','Helm'";
    $get_res = $this->m_stock->getTipeKendaraan($get);
    $res_ = [];
    foreach ($get_res->result() as $rs) {
      $res_[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
        'code' => $rs->code,
        'image' => (string)$rs->image,
        'total_accessories' => (int)$rs->total_accessories,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }

  function accessories_detail()
  {
    $get = $this->input->get();
    // send_json($this->login);
    // send_json($get);
    $get['select'] = 'select_detail';
    $get['group_by_id_part'] = true;
    $res_ = $this->m_stock->getDetailAksesoris($get)->result();
    $new_res = [];
    foreach ($res_ as $rs) {
      $filter_d = [
        'id_part' => $rs->id,
        'id_dealer' => $this->login->id_dealer,
        'select' => 'summary_stok',
      ];

      $filter_d = [
        'id_part' => $rs->code,
        'id_dealer' => $this->login->id_dealer,
        'select' => 'summary_stok',
      ];
      $stok = $this->m_h2_api->fetch_partWithAllStock($filter_d)->row()->summary_stok;
      // $stok = 0;
      // if ($stok > 0) {
      $new_res[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
        'code' => $rs->code,
        'price' => (int)$rs->price,
        'stock' => (int)$stok
      ];
      // }
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }

  function unit()
  {
    $get = $this->input->get();
    // send_json($this->login);
    // send_json($get);
    $get['select']            = 'select_tipe_kendaraan';
    $get['id_kategori_int']   = $this->input->get('type_id');
    $get['active']            = 1;
    $get['id_dealer']         = $this->login->id_dealer;
    $get['ready_stok']        = false;
    $res_ = $this->m_stock->getTipeKendaraanWithStokUnit($get)->result();
    $new_res = [];
    foreach ($res_ as $rs) {

      $filter = [
        'id_dealer' => $this->login->id_dealer,
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'select' => 'select_detail',
      ];
      $model = $this->m_stock->getDetailUnitStok($filter);


      $filter_spk = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'id_dealer' => $this->login->id_dealer,
        'tahun_bulan' => get_ym()
      ];
      $prospek_spk = $this->m_stock->prospek_spk($filter_spk);
      // send_json($prospek_spk);
      // $wrn = $this->m_stock->getDetailUnit(['id_tipe_kendaraan' => $rs->id_tipe_kendaraan]);
      // send_json($wrn);
      // $id_warna = $wrn->num_rows() > 0 ? $wrn->row()->id_warna : '';
      $filter_bbn = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        // 'id_warna' => $id_warna,
      ];
      $price       = $this->m_prospek->cek_bbn($filter_bbn);

      // $available   = $this->m_stok->GetReadyStock($filter_spk);
      $is_indent = $this->db->query("SELECT COUNT(kode_type_actual) c FROM ms_utd WHERE kode_type_actual='$rs->id_tipe_kendaraan'")->row()->c;
      // if ($available > 0) {
      $new_res[] = [
        'id'                => (int)$rs->id_tipe_kendaraan_int,
        'name'              => $rs->tipe_ahm,
        'code'              => $rs->code,
        'image'             => (string)$rs->image,
        'price'             => (int)$price,
        'is_indent' => $is_indent > 0 ? true : false,
        'prospek_spk'       => $prospek_spk,
        'available'         => (int)$rs->ready_stok,
        'total_accessories' => (int)$rs->total_accessories,
        'model'             => $model
      ];
      // }
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }

  function unit_detail()
  {
    $get = $this->input->get();
    if (!isset($get['id_unit'])) {
      $result = [
        'status' => 0,
        'message' => ['Filter ID Unit tidak ada'],
        'data' => NULL
      ];
      send_json($result);
    } elseif ($get['id_unit'] == '') {
      $result = [
        'status' => 0,
        'message' => ['Tentukan ID Unit'],
        'data' => NULL
      ];
      send_json($result);
    }

    $get['select'] = 'select_detail_tipe_kendaraan';
    $get['id_tipe_kendaraan_int'] = $get['id_unit'];
    $res_ = $this->m_stock->getTipeKendaraan($get)->row();
    // send_json($res_);
    $wrn = $this->m_stock->getDetailUnit(['id_tipe_kendaraan' => $res_->id_tipe_kendaraan]);
    // send_json($wrn);
    $id_warna = $wrn->num_rows() > 0 ? $wrn->row()->id_warna : '';
    $filter_bbn = [
      'id_tipe_kendaraan' => $res_->id_tipe_kendaraan,
      'id_warna' => $id_warna,
    ];
    $price       = $this->m_prospek->cek_bbn($filter_bbn);

    $filter = [
      'id_dealer' => $this->login->id_dealer,
      'id_tipe_kendaraan' => $res_->id_tipe_kendaraan,
      'select' => 'select_detail',
    ];
    $model = $this->m_stock->getDetailUnitStok($filter);


    $new_res = [
      'id' => (int)$res_->id_tipe_kendaraan_int,
      'name' => $res_->tipe_ahm,
      'code' => $res_->code,
      'image' => $res_->image,
      'price' => (int)$price,
      'model' => $model
    ];

    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }
  function filter()
  {
    // send_json($this->login);
    $res_ = $this->m_stock->getFilter();
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }

  function apparel()
  {
    $get = $this->input->get();

    $get['select'] = 'select_apparel';
    $get['group_by_id_part'] = true;
    $res_ = $this->m_stock->getApparel($get)->result();
    $new_res = [];
    foreach ($res_ as $rs) {
      $filter_d = [
        'id_part' => $rs->code,
        'id_dealer' => $this->login->id_dealer,
        'select' => 'summary_stok',
      ];
      $stok = $this->m_h2_api->fetch_partWithAllStock($filter_d)->row()->summary_stok;
      $new_res[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
        'image' => (string)$rs->image,
        'code' => $rs->code,
        'price' => (int)$rs->price,
        'stock' => (int)$stok,
        'ukuran' => $rs->ukuran,
        'material' => $rs->material,
        'spesifikasi' => $rs->spesifikasi,
        'warna' => $rs->warna,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }
}
