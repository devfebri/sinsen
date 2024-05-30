<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_labour_cost extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function fetchData($filter)
  {

    $order_column = array('sms_kpb1', 'call_kpb1', 'sms_kpb2', 'call_kpb2', 'sms_kpb2', 'call_kpb2', 'sms_kpb4', 'call_kpb4', 'active', null);
    $set_filter   = "WHERE 1=1 ";


    if (isset($filter['id'])) {
      $set_filter .= " AND lc.id = '{$filter['id']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $set_filter .= " AND (sms_kpb1 LIKE '%$search%'
                              OR call_kpb1 LIKE '%$search%'
                              OR sms_kpb2 LIKE '%$search%'
                              OR call_kpb2 LIKE '%$search%'
                              OR sms_kpb3 LIKE '%$search%'
                              OR call_kpb3 LIKE '%$search%'
                              OR sms_kpb3 LIKE '%$search%'
                              OR call_kpb3 LIKE '%$search%'
                              ) 
              ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $set_filter .= " ORDER BY $order_clm $order_by ";
      }
    }

    if (isset($filter['limit'])) {
      $set_filter .= $filter['limit'];
    }

    $tot_kendaraan = "SELECT COUNT(id_tipe_kendaraan) FROM ms_labour_cost_tipe WHERE id=lc.id";
    return $this->db->query("SELECT lc.*,($tot_kendaraan) AS tot_kendaraan
    FROM ms_labour_cost lc
    $set_filter ");
  }

  function detailLC($filter)
  {
    return $this->db->query("SELECT lct.*,tk.tipe_ahm, lct.nominal AS harga_terakhir
    FROM ms_labour_cost_tipe lct 
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=lct.id_tipe_kendaraan
    WHERE lct.id='{$filter['id']}'");
  }
}
