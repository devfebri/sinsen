<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_alert extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getAlert($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_karyawan_int_sales_person'])) {
      if ($filter['id_karyawan_int_sales_person'] != '') {
        $where .= " AND alert.id_karyawan_int_sales_person='{$filter['id_karyawan_int_sales_person']}'";
      }
    }

    if (isset($filter['tanggal'])) {
      if ($filter['tanggal'] != '') {
        $where .= " AND LEFT(alert.created_at,10)='{$filter['tanggal']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (alert.id_karyawan_int_sales_person LIKE '%$search%'
              OR alert.info LIKE '%$search%'
              OR alert.title LIKE '%$search%'
              ) 
        ";
      }
    }

    // if (isset($filter['order'])) {
    //   $order = $filter['order'];
    //   if ($order != '') {
    //     if ($filter['order_column'] == 'view') {
    //       $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.tot_harga', 'po.status', NULL];
    //     }
    //     $order_clm  = $order_column[$order['0']['column']];
    //     $order_by   = $order['0']['dir'];
    //     $order = " ORDER BY $order_clm $order_by ";
    //   } else {
    //     $order = " ORDER BY tg.created_at DESC ";
    //   }
    // } else {
    //   $order = '';
    // }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $length = 10;
      $page   = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page   = $page < 0 ? 0   :  $page;
      $start  = $length * $page;
      $limit  = "LIMIT $start, $length";
    }

    $select = "alert.*";

    return $this->db->query("SELECT $select
    FROM tr_sc_alert alert
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer_int=alert.id_karyawan_int_sales_person
    $where $limit
    ");
  }
}
