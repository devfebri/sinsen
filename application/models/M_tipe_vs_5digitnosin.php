<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_tipe_vs_5digitnosin extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function fetchData($filter)
  {
    $set_filter   = "WHERE 1=1 ";
    $select = '';
    $tot_kendaraan = "SELECT COUNT(id_tipe_kendaraan) FROM ms_tipe_vs_5_digit_no_mesin_detail WHERE id=tp.id";

    if (isset($filter['id'])) {
      $set_filter .= " AND tp.id = '{$filter['id']}'";
    }

    if (isset($filter['aktif'])) {
      $set_filter .= " AND tp.aktif = '{$filter['aktif']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $set_filter .= " AND (id LIKE '%$search%'
                              OR nama_tipe LIKE '%$search%'
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
      $order_column = ['id', 'nama_tipe', $tot_kendaraan, null];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $set_filter .= " ORDER BY $order_clm $order_by ";
      }
    }

    if (isset($filter['limit'])) {
      $set_filter .= $filter['limit'];
    }

    if (isset($filter['select_concat_detail'])) {
      $select .= ",(SELECT GROUP_CONCAT(id_tipe_kendaraan,',') FROM ms_tipe_vs_5_digit_no_mesin_detail tpd WHERE tpd.id=tp.id) AS concat_detail ";
    }

    return $this->db->query("SELECT tp.*, ($tot_kendaraan) AS tot_kendaraan $select
    FROM ms_tipe_vs_5_digit_no_mesin tp
    $set_filter ");
  }

  function detailTipe($filter)
  {
    return $this->db->query("SELECT tpd.*,tk.tipe_ahm,tk.no_mesin
    FROM ms_tipe_vs_5_digit_no_mesin_detail tpd 
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=tpd.id_tipe_kendaraan
    WHERE tpd.id='{$filter['id']}'");
  }

  function get_id()
  {
    $get_data  = $this->db->query("SELECT id FROM ms_tipe_vs_5_digit_no_mesin ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $new = sprintf("%'.05d", $row->id + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_tipe_vs_5_digit_no_mesin', ['id' => $new])->num_rows();
        if ($cek > 0) {
          $new = sprintf("%'.05d", $new + 1);
          $i   = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new   = '00001';
    }
    return strtoupper($new);
  }
}
