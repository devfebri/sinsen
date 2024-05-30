<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Part extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2_booking', 'm_booking');
    $this->load->model('m_sm_master', 'm_sm');
    $this->load->model('m_h2_api');
    $this->load->model('h3_dealer_stock_model');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function nearby()
  {
    $get = $this->input->get();
    $mandatory = [
      'code' => 'required',
      'latitude' => 'required',
      'longitude' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_d = [
      'id_dealer' => $get['code']
    ];

    $result = $this->m_sm->getNearByDealer($f_d)->result();
    send_json(msg_sc_success($result, NULL));
  }

  function get_list()
  {
    $get = $this->input->get();
    $mandatory = [
      'limit' => 'required',
      'offset' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_d = [
      'search' => $this->input->get('search'),
      'sort' => $this->input->get('sort'),
      'id_kategori_int' => $this->input->get('product_category_id') == 0 ? '' : $this->input->get('product_category_id'),
      'id_tipe_kendaraan_int' => $this->input->get('product_id') == 0 ? '' : $this->input->get('product_id'),
      'offset' => $get['offset'],
      'length' => $get['limit'],
    ];

    $get_data = $this->m_sm->getParts($f_d)->result();
    $result = [];
    $id_dealer = $this->login->id_dealer;
    if ($f_d['search'] != '') {
      foreach ($get_data as $rs) {
        // $stok = $this->h3_dealer_stock_model->qty_avs($id_dealer, $rs->id_part);
        // if ((int)$rs->id_part_int==30199) { 
          // $stok = $this->h3_dealer_stock_model->qty_book_hotline($this->login->id_dealer,$rs->id_part);
          $rs_stok = $this->db->query("SELECT * FROM ms_h3_dealer_stock ds WHERE ds.id_dealer='$id_dealer' AND id_part_int='$rs->id_part_int'");
          $stok = 0;
          foreach ($rs_stok->result() as $rst) {
            $stok += $this->h3_dealer_stock_model->qty_avs($id_dealer, $rst->id_part,$rst->id_gudang,$rst->id_rak);
          }
        // }
        $result[] = [
          'id' => (int)$rs->id_part_int,
          'name' => $rs->nama_part,
          'code' => $rs->id_part,
          'unit_price' => (int)$rs->harga_dealer_user,
          'stock_remaining' => (int)$stok,
        ];
      }
    }

    send_json(msg_sc_success($result, NULL));
  }
}
