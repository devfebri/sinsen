<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_stok extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function GetReadyStock($filter)
  {
    $where = "WHERE tr_scan_barcode.status BETWEEN 1 AND 4 AND tr_penerimaan_unit_dealer.status = 'close'
    AND tr_penerimaan_unit_dealer_detail.retur=0 ";
    if (isset($filter['id_tipe_kendaraan'])) {
      $where .= "AND tr_scan_barcode.tipe_motor='{$filter['id_tipe_kendaraan']}'";
    }
    if (isset($filter['id_warna'])) {
      $where .= "AND tr_scan_barcode.warna='{$filter['id_warna']}'";
    }
    if (isset($filter['id_dealer'])) {
      $where .= "AND tr_penerimaan_unit_dealer.id_dealer='{$filter['id_dealer']}' ";
    }
    $result = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
    LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
    LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
    LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
    $where ")->row()->jum;

    // $where = "";
    // if (isset($filter['id_warna'])) {
    //   $where .= "AND id_warna='{$filter['id_warna']}'";
    // }
    // $book_spk = $this->db->query("SELECT COUNT(no_spk) c FROM tr_spk WHERE id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}' AND status_spk='booking' AND id_dealer ='{$filter['id_dealer']}' $where")->row()->c;
    // $result = $result - $book_spk;
    return $result;
  }
}
