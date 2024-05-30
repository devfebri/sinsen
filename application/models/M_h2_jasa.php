<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_jasa extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    $this->load->model('m_h2_master', 'mh2');
  }

  public function get_kode_detail($id_dealer = NULL)
  {
    $tahun_bulan    = date('Y-m');
    $thbln          = date('ym');

    $get_data  = $this->db->query("SELECT * FROM ms_h2_detail_work_list
			WHERE LEFT(created_at,7)='$tahun_bulan'
      ORDER BY id_detail_int DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row            = $get_data->row();
      $last_number    = substr($row->kode_detail, -5);
      $new_kode       = 'WL/' . $thbln . '/' . sprintf("%'.05d", $last_number + 1);

      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_h2_detail_work_list', ['kode_detail' => $new_kode])->num_rows();
        if ($cek > 0) { // Jika sudah ada generate kembali
          $gen_number   = substr($new_kode, -5);
          $new_kode     = 'WL/' . $thbln . '/' . sprintf("%'.05d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'WL/' . $thbln . '/00001';
    }
    return strtoupper($new_kode);
  }

  public function get_kode_paket_jasa_regular($id_dealer = NULL)
  {
    $tahun_bulan    = date('Y-m');
    $thbln          = date('ym');

    $get_data  = $this->db->query("SELECT * FROM ms_h2_paket_jasa_regular
			WHERE LEFT(created_at,7)='$tahun_bulan'
      ORDER BY id_paket_int DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row            = $get_data->row();
      $last_number    = substr($row->kode_paket, -5);
      $new_kode       = 'PSR/' . $thbln . '/' . sprintf("%'.05d", $last_number + 1);

      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_h2_paket_jasa_regular', ['kode_paket' => $new_kode])->num_rows();
        if ($cek > 0) { // Jika sudah ada generate kembali
          $gen_number   = substr($new_kode, -5);
          $new_kode     = 'PSR/' . $thbln . '/' . sprintf("%'.05d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'PSR/' . $thbln . '/00001';
    }
    return strtoupper($new_kode);
  }

  function fetch_detail_work_list($filter)
  {

    $order_column = array('kode_detail', 'nama_detail', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_detail_int LIKE '%$search%'
                            OR kode_detail LIKE '%$search%'
                            OR nama_detail LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY kode_detail ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_detail_int,kode_detail,nama_detail
    FROM ms_h2_detail_work_list
    $set_filter
    ");
  }

  function fetch_spareparts($filter)
  {

    $order_column = array('id_part', 'nama_part', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_part LIKE '%$search%'
                            OR nama_part LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY id_part ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_part,nama_part
    FROM ms_part
    $set_filter
    ");
  }

  function get_detail_work_lists_jasa($id_jasa, $id_dealer = null)
  {
    $where = "";
    if ($id_dealer != null) {
      $where = "AND jdw.id_dealer='$id_dealer'";
    }
    return $this->db->query("SELECT jdw.*,w.nama_detail
      FROM ms_h2_jasa_detail_work_list jdw
      JOIN ms_h2_detail_work_list w ON w.kode_detail=jdw.kode_detail
      WHERE id_jasa='$id_jasa' $where
      ")->result();
  }

  function get_spareparts_jasa($id_jasa, $id_dealer = null)
  {
    $where = "";
    if ($id_dealer != null) {
      $where = "AND jdw.id_dealer='$id_dealer'";
    }

    return $this->db->query("SELECT spr.*,prt.nama_part
      FROM ms_h2_jasa_spareparts spr
      JOIN ms_part prt ON prt.id_part=spr.id_part
      WHERE id_jasa='$id_jasa' $where
      ")->result();
  }

  function fetch_jasa($filter)
  {

    $tot_detail_work_list = "(SELECT COUNT(id_jasa) FROM ms_h2_jasa_detail_work_list WHERE id_jasa=js.id_jasa)";
    $tot_spareparts = "(SELECT COUNT(id_jasa) FROM ms_h2_jasa_spareparts WHERE id_jasa=js.id_jasa)";

    $order_column = array('js.id_jasa', 'nama_jasa', 'deskripsi', $tot_detail_work_list, $tot_spareparts, null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (js.id_jasa LIKE '%$search%'
                            OR nama_jasa LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY js.id_jasa ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT js.*,($tot_detail_work_list) tot_detail_work_list,($tot_spareparts) tot_spareparts
    FROM ms_h2_jasa js
    $set_filter
    ");
  }

  function get_detail_paket_jasa_regular_all_details($kode_paket)
  {
    $res_detail = $this->db->query("SELECT jrd.*,js.nama_jasa,js.deskripsi FROM ms_h2_paket_jasa_regular_detail jrd
    JOIN ms_h2_jasa js ON js.id_jasa=jrd.id_jasa
    WHERE jrd.kode_paket='$kode_paket'
    ")->result();
    $return = [];
    foreach ($res_detail as $rs) {
      $rs->detail_work_lists = $this->m_jasa->get_detail_work_lists_jasa($rs->id_jasa);
      $rs->spareparts = $this->m_jasa->get_spareparts_jasa($rs->id_jasa);
      $return[] = $rs;
    }
    return $return;
  }
}
