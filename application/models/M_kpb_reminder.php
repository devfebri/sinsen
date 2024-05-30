<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_kpb_reminder extends CI_Model
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
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_kpb,sms_kpb1,call_kpb1,sms_kpb2,call_kpb2,sms_kpb3,call_kpb3,sms_kpb4,call_kpb4,active FROM ms_h2_kpb_reminder $set_filter ");
  }
  function getKPBReminder($filter)
  {
    $where = "WHERE 1=1";
    if (is_array($filter)) {
      if (isset($filter['id_kpb'])) {
        $where .= " AND kpb.id_kpb='{$filter['id_kpb']}'";
      }
    }
    return $this->db->query("SELECT id_kpb,sms_kpb1,call_kpb1,sms_kpb2,call_kpb2,sms_kpb3,call_kpb3,sms_kpb4,call_kpb4,active 
    FROM ms_h2_kpb_reminder kpb
      $where
      ");
  }

  function detailBatasKPB($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_tipe_kendaraan'])) {
        $where .= " AND id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}' ";
      }
    }
    $get_result = $this->db->query("SELECT id_tipe_kendaraan,batas_maks_kpb,km,toleransi,harga_jasa,kpb_ke
          FROM ms_kpb_detail 
          $where");
    foreach ($get_result->result() as $rs) {
      $filter =
        [
          'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
          'kpb_ke' => $rs->kpb_ke
        ];
      $rs->oli = $this->detailOliBatasKPB($filter);
      $result[] = $rs;
    }
    if (isset($result)) {
      return $result;
    }
  }

  function detailOliBatasKPB($filter)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_tipe_kendaraan'])) {
        $where .= " AND id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}' ";
      }
      if (isset($filter['kpb_ke'])) {
        $where .= " AND kpb_ke='{$filter['kpb_ke']}' ";
      }
    }
    $get_result = $this->db->query("SELECT kp.id_part,nama_part,harga_dealer_user
          FROM ms_kpb_oli AS kp
          JOIN ms_part mp ON mp.id_part=kp.id_part
          $where");
    if ($get_result->num_rows() > 0) {
      return $get_result->result();
    } else {
      return [];
    }
  }
}
