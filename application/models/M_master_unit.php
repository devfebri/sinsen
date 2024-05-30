<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_master_unit extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getTipeKendaraan($filter = NULL)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_tipe_kendaraan'])) {
      $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }
    if (isset($filter['id_tipe_kendaraan_int'])) {
      $where .= " AND tk.id_tipe_kendaraan_int='{$filter['id_tipe_kendaraan_int']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.deskripsi_ahm LIKE '%$search%'
              ) 
        ";
      }
    }
    return $this->db->query("SELECT id_tipe_kendaraan_int,id_tipe_kendaraan,tipe_ahm,deskripsi_ahm,tipe_customer,tipe_part,id_segment,tk.id_kategori,id_series,cc_motor,tgl_awal,tgl_akhir,kode_ptm,tk.no_mesin AS no_mesin_5_digit,ktg.kategori 
    FROM ms_tipe_kendaraan tk 
    LEFT JOIN ms_kategori ktg ON ktg.id_kategori=tk.id_kategori
    $where ORDER BY tk.id_tipe_kendaraan DESC");
  }
  function getWarna($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_warna'])) {
      $where .= " AND wr.id_warna='{$filter['id_warna']}'";
    }
    if (isset($filter['id_warna_int'])) {
      $where .= " AND wr.id_warna_int='{$filter['id_warna_int']}'";
    }
    return $this->db->query("SELECT id_warna,id_warna_int,warna FROM ms_warna wr $where");
  }
  function getItem($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_item'])) {
      $where .= " AND wr.id_item='{$filter['id_item']}'";
    }
    if (isset($filter['id_item_int'])) {
      $where .= " AND itm.id_item_int='{$filter['id_item_int']}'";
    }
    if (isset($filter['id_warna'])) {
      $where .= " AND wr.id_warna='{$filter['id_warna']}'";
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }
    return $this->db->query("SELECT id_item,tk.id_tipe_kendaraan,wr.id_warna,tk.id_tipe_kendaraan_int,wr.id_warna_int,tk.tipe_ahm,wr.warna 
    FROM ms_item itm
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=itm.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=itm.id_warna
    $where");
  }
}
