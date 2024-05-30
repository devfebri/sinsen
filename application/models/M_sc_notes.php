<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_notes extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getNotes($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_karyawan_dealer_notes'])) {
      if ($filter['id_karyawan_dealer_notes'] != '') {
        $where .= " AND nt.id_karyawan_dealer_notes='{$filter['id_karyawan_dealer_notes']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_created'])) {
      if ($filter['id_karyawan_dealer_created'] != '') {
        $where .= " AND nt.id_karyawan_dealer_created='{$filter['id_karyawan_dealer_created']}'";
      }
    }

    if (isset($filter['perminggu'])) {
      $where .= " AND (nt.created_at + INTERVAL 7 DAY)>=NOW() ";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (nt.id_karyawan_dealer_int LIKE '%$search%'
              OR nt.message LIKE '%$search%'
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

    $order = '';
    if (isset($filter['order'])) {
      $order = "ORDER BY {$filter['order']['field']} {$filter['order']['sort']}";
    }

    $select = "nt.*";

    return $this->db->query("SELECT $select
    FROM tr_sc_notes nt
    JOIN ms_karyawan_dealer kd_create ON kd_create.id_karyawan_dealer_int=nt.id_karyawan_dealer_created
    JOIN ms_karyawan_dealer kd_notes ON kd_notes.id_karyawan_dealer_int=nt.id_karyawan_dealer_notes
    $where $order $limit
    ");
  }
}
