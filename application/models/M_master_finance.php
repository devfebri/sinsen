<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_master_finance extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_work_order', 'm_wo');
  }

  function getCOADealer($filter = null)
  {
    $order_column = ['kode_coa', 'coa', 'tipe_transaksi', "saldo_awal", null];
    // $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 ";
    $order = "ORDER BY kode_coa ASC ";
    $limit = '';

    if ($filter != null) {
      if (isset($filter['kode_coa'])) {
        $where .= " AND coa.kode_coa='{$filter['kode_coa']}' ";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT kode_coa,coa,tipe_transaksi,saldo_awal
    FROM ms_coa_dealer coa
    $where $order $limit
    ");
  }

  function fetch_getCOADealer($filter)
  {

    $order_column = array('kode_coa', 'coa', 'tipe_transaksi', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (kode_coa LIKE '%$search%'
                            OR coa LIKE '%$search%'
                            OR tipe_transaksi LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY kode_coa ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT kode_coa,coa,tipe_transaksi
    FROM ms_coa_dealer
    $set_filter
    ");
  }

  function getBarangLuar($filter = null)
  {
    $order_column = ['id_barang', 'nama_barang', 'harga_satuan', null];
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 AND id_dealer='$id_dealer'";
    $order = "ORDER BY created_at DESC";
    $limit = '';

    if ($filter != null) {
      if (isset($filter['id_barang'])) {
        $where .= " AND id_barang='{$filter['id_barang']}' ";
      }
      if (isset($filter['aktif'])) {
        $where .= " AND aktif='{$filter['aktif']}' ";
      }
      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
        }
        $where .= " AND (id_barang LIKE '%$search%'
        OR nama_barang LIKE '%$search%'
        OR harga_satuan LIKE '%$search%'
        )  ";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT id_barang,nama_barang,harga_satuan,aktif
    FROM ms_h2_barang_luar
    $where $order $limit
    ");
  }

  function getVendorPO($filter = null)
  {
    $order_column = ['id_vendor', 'nama_vendor', 'alamat', "no_hp", null];
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 AND id_dealer='$id_dealer'";
    $order = "ORDER BY created_at DESC";
    $limit = '';

    if ($filter != null) {
      if (isset($filter['id_vendor'])) {
        $where .= " AND vod.id_vendor='{$filter['id_vendor']}' ";
      }
      if (isset($filter['aktif'])) {
        $where .= " AND vod.aktif='{$filter['aktif']}' ";
      }
      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
        }
        $where .= " AND (id_vendor LIKE '%$search%'
        OR nama_vendor LIKE '%$search%'
        OR alamat LIKE '%$search%'
        OR no_hp LIKE '%$search%'
        )  ";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT id_vendor,nama_vendor,alamat,no_hp,aktif,kode_tipe_vendor,tipe_vendor,kode_group_vendor,group_vendor,ppn,no_rekening
    FROM ms_h2_vendor_po_dealer vod
    $where $order $limit
    ");
  }

  public function get_id_vendor_po()
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_vendor FROM ms_h2_vendor_po_dealer
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row       = $get_data->row();
      $id_vendor = substr($row->id_vendor, -4);
      $new_kode  = 'VPO/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $id_vendor + 1);
      $i         = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('ms_h2_vendor_po_dealer', ['id_vendor' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'VPO/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'VPO/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }
  public function get_id_barang_luar()
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_barang FROM ms_h2_barang_luar
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row       = $get_data->row();
      $id_barang = substr($row->id_barang, -4);
      $new_kode  = 'BRG/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $id_barang + 1);
      $i         = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('ms_h2_barang_luar', ['id_barang' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'BRG/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'BRG/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }
}
