<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_md_elearning extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
  }

  function getElearning($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['kategori'])) {
      if ($filter['kategori'] != '') {
        $where .= " AND el.kategori='{$filter['kategori']}'";
      }
    }
    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND el.id='{$filter['id']}'";
      }
    }
    if (isset($filter['code'])) {
      if ($filter['code'] != '') {
        $where .= " AND el.code='{$filter['code']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND el.active='{$filter['active']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (el.kategori LIKE '%$search%'
              OR el.code LIKE '%$search%'
              OR el.nama_atribut LIKE '%$search%'
              OR el.id LIKE '%$search%'
              OR el.active LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id', 'kategori', 'code', 'nama_atribut', 'active', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY el.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT el.id,el.id id_elearning,el.business_function,el.title,el.created_at,el.role,rl.role role_desc,el.active
    FROM sc_ms_elearning el
    JOIN sc_ms_role rl ON rl.id=el.role
    $where $order $limit
    ");
  }

  function get_id()
  {
    $get_data  = $this->db->query("SELECT id FROM sc_ms_elearning upl
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = $row->id;
      $new_kode   = $last_number + 1;
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('sc_ms_elearning', ['id' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode   = $last_number + 1;
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 1;
    }
    return strtoupper($new_kode);
  }

  function getElearningDetail($filter)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['parent_id'])) {
      if ($filter['parent_id'] != '') {
        $where .= " AND eld.parent_id='{$filter['parent_id']}'";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_upload', 'upl.created_at', 'tahun', 'bulan', 'tipe', 'kategori', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY upl.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT eld.id,eld.title,eld.content_type,eld.extension,eld.size,eld.url,eld.player
    FROM sc_ms_elearning_detail eld
    JOIN sc_ms_elearning el ON el.id=eld.parent_id
    $where $order $limit
    ");
  }
}
