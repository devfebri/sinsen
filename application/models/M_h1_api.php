<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_api extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getDriverDeliveryUnit($filter)
  {

    if (isset($filter['id_dealer'])) {
      $where   = "WHERE pd.id_dealer='{$filter['id_dealer']}' ";
    } else {
      $where = 'WHERE 1=1 ';
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (pd.id_team LIKE '%$search%'
                            OR pd.nama_team LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_karyawan_dealer', 'kd.honda_id', 'id_flp_md', 'driver', 'no_plat', 'no_hp', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY pd.driver ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_master_plat,no_plat,driver,kd.honda_id,status,pd.no_hp,pd.id_karyawan_dealer,kd.id_flp_md
    FROM ms_plat_dealer pd
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=pd.id_karyawan_dealer
    $where and pd.active = 1
    $order
    $limit
    ");
  }
}
