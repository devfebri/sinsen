<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_ahass_network extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getSupervisi($filter = null)
  {
    $where = "WHERE 1=1 ";
    $limit = '';
    $tot_dealer = "(SELECT COUNT(id_dealer) FROM tr_h2_md_supervisi_detail WHERE id_supervisi=sp.id_supervisi)";
    if ($filter != null) {
      if (isset($filter['id_supervisi'])) {
        $where .= " AND sp.id_supervisi='{$filter['id_supervisi']}' ";
      }
      if (isset($filter['tgl_supervisi'])) {
        $where .= " AND sp.tgl_supervisi='{$filter['tgl_supervisi']}' ";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order_column = ['id_supervisi', 'agenda', 'tgl_supervisi', $tot_dealer, 'status', null];
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = "ORDER BY sp.created_at DESC ";
        }
      } else {
        $order = "ORDER BY sp.created_at DESC ";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT id_supervisi,tgl_supervisi,agenda,status,$tot_dealer AS tot_dealer
    FROM tr_h2_md_supervisi sp
    $where $order $limit
    ");
  }

  function getSupervisiDetail($filter = null)
  {
    $where = "WHERE 1=1 ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_supervisi'])) {
        $where .= " AND spv.id_supervisi='{$filter['id_supervisi']}' ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND spv.id_dealer='{$filter['id_dealer']}' ";
      }
    }
    // $nama_pic_dealer = "(SELECT nama_lengkap FROM ms_karyawan_dealer WHERE id_dealer=dl.id_dealer AND id_jabatan='JBT-053' LIMIT 1)";
    $hasil = "(SELECT count(id_supervisi) FROM tr_h2_md_supervisi_hasil WHERE id_supervisi=spv.id_supervisi AND id_dealer=spv.id_dealer)";

    return $this->db->query("SELECT spv.id_supervisi,spv.id_dealer,kode_dealer_md,nama_dealer,'' AS id_karyawan,se, nama_pic_dealer,kunjungan, $hasil AS hasil,tgl_supervisi,spv.id_kabupaten,kabupaten,
    CASE 
      WHEN status_perbaikan IS NULL THEN 'NOT OK'
      WHEN status_perbaikan='ok' THEN 'OK'
      WHEN status_perbaikan='not_ok' THEN 'NOT OK'
    END AS status_perbaikan
    FROM tr_h2_md_supervisi_detail spv
    JOIN tr_h2_md_supervisi sp ON sp.id_supervisi=spv.id_supervisi
    JOIN ms_dealer dl ON dl.id_dealer=spv.id_dealer
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=spv.id_kabupaten
    -- JOIN ms_karyawan kr ON kr.id_karyawan=spv.id_karyawan
    $where");
  }

  function getSupervisiQuartal($filter = null)
  {
    $where = "WHERE 1=1 ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_supervisi'])) {
        $where .= " AND spv.id_supervisi='{$filter['id_supervisi']}' ";
      }
    }
    return $this->db->query("SELECT id_supervisi,quartal,start_date,end_date
    FROM tr_h2_md_supervisi_tgl_quartal spv
    $where ORDER BY quartal ASC");
  }
  function getSupervisiHasil($filter = null)
  {
    $where = "WHERE 1=1 ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_supervisi'])) {
        $where .= " AND spvh.id_supervisi='{$filter['id_supervisi']}' ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND spvh.id_dealer='{$filter['id_dealer']}' ";
      }
    }
    return $this->db->query("SELECT temuan_masalah,penyebab,perbaikan,deadline,id_supervisi,id_dealer,pic,foto_temuan,foto_perbaikan,
    CASE 
      WHEN foto_perbaikan IS NULL THEN 'NOT OK'
      ELSE 'OK'
    END AS status_perbaikan
    FROM tr_h2_md_supervisi_hasil spvh
    $where");
  }

  function getSupervisiHasilDokumen($filter = null)
  {
    $where = "WHERE 1=1 ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['id_supervisi'])) {
        $where .= " AND spvd.id_supervisi='{$filter['id_supervisi']}' ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND spvd.id_dealer='{$filter['id_dealer']}' ";
      }
    }
    return $this->db->query("SELECT file_dokumen,keterangan_dokumen
    FROM tr_h2_md_supervisi_hasil_dokumen spvd
    $where");
  }

  function get_id_supervisi()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $ymd     = date('Y-m-d');
    $ymd2     = date('ymd');
    $get_data  = $this->db->query("SELECT id_supervisi FROM tr_h2_md_supervisi
			WHERE LEFT(created_at,7)='$th_bln' 
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row          = $get_data->row();
      $id_supervisi = substr($row->id_supervisi, -4);
      $new_kode     = 'SPV/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $id_supervisi + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_md_supervisi', ['id_supervisi' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = 'SPV/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'SPV/' . $th . '/' . $bln . '/0001';
    }
    return strtoupper($new_kode);
  }
}
