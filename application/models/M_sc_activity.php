<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_activity extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getActivity($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_karyawan_dealer_int'])) {
      if ($filter['id_karyawan_dealer_int'] != '') {
        $where .= " AND act.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_int_in'])) {
      if ($filter['id_karyawan_dealer_int_in'] != '') {
        $where .= " AND act.id_karyawan_dealer_int IN ({$filter['id_karyawan_dealer_int_in']})";
      }
    }
    if (isset($filter['tanggal_lebih_kecil'])) {
      if ($filter['tanggal_lebih_kecil'] != '') {
        $where .= " AND act.tanggal <'{$filter['tanggal_lebih_kecil']}'";
      }
    }
    if (isset($filter['tanggal'])) {
      if ($filter['tanggal'] != '') {
        $where .= " AND act.tanggal ='{$filter['tanggal']}'";
      }
    }
    if (isset($filter['bulan'])) {
      if ($filter['bulan'] != '') {
        $where .= " AND LEFT(act.tanggal,7) ='{$filter['bulan']}'";
      }
    }
    if (isset($filter['parent_id'])) {
      if ($filter['parent_id'] != '') {
        $where .= " AND act.parent_id ='{$filter['parent_id']}'";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND act.id_dealer ='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_kategori_activity'])) {
      if ($filter['id_kategori_activity'] != '') {
        $where .= " AND act.id_kategori_activity ='{$filter['id_kategori_activity']}'";
      }
    }
    if (isset($filter['status_not'])) {
      if ($filter['status_not'] != '') {
        $where .= " AND act.status != '{$filter['status_not']}'";
      }
    }
    if (isset($filter['check_date_null'])) {
      if ($filter['check_date_null'] != '') {
        $where .= " AND act.check_date IS NULL";
      }
    }
    if (isset($filter['kategori'])) {
      if ($filter['kategori'] != '') {
        $where .= " AND ktg.name ='{$filter['kategori']}'";
      }
    }

    if (isset($filter['status_prospek_not'])) {
      if ($filter['status_prospek_not'] != '') {
        $where .= " AND prp.status_prospek!='{$filter['status_prospek_not']}'";
      }
    }
    if (isset($filter['status_prospek_not_in'])) {
      if ($filter['status_prospek_not_in'] != '') {
        $where .= " AND prp.status_prospek NOT IN ({$filter['status_prospek_not_in']})";
      }
    }

    if (isset($filter['status_spk_not_in'])) {
      if ($filter['status_spk_not_in'] != '') {
        $where .= " AND spk.status_spk NOT IN({$filter['status_spk_not_in']})";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (act.id_karyawan_dealer_int LIKE '%$search%'
              OR act.name LIKE '%$search%'
              OR act.info LIKE '%$search%'
              OR act.parent_id LIKE '%$search%'
              OR act.parent_id LIKE '%$search%'
              OR ktg.name LIKE '%$search%'
              ) 
        ";
      }
    }

    $order = "";
    if (isset($filter['order'])) {
      if ($filter['order'] != '') {
        if ($filter['order'] == 'activity_az') {
          $order = "ORDER BY act.name ASC";
        } elseif ($filter['order'] == 'activity_za') {
          $order = "ORDER BY act.name DESC";
        } elseif ($filter['order'] == 'new') {
          $order = "ORDER BY act.tanggal DESC";
        } elseif ($filter['order'] == 'latest') {
          $order = "ORDER BY act.created_at ASC";
        }
      }
    }

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

    $group = '';
    if (isset($filter['group_by_tanggal'])) {
      $group = " GROUP BY act.tanggal";
    }
    if (isset($filter['group_by_id_kry_id_kategori'])) {
      $group = " GROUP BY act.id_karyawan_dealer_int, act.id_kategori_activity ";
    }


    $join = '';
    if (isset($filter['join'])) {
      if ($filter['join'] == 'join_prospek') {
        $join = "JOIN tr_prospek prp ON prp.id_prospek=act.parent_id";
      } elseif ($filter['join'] == 'join_spk') {
        $join = "JOIN tr_spk spk ON spk.no_spk=act.parent_id";
      }
    }

    $select = "act.*,kry.nama_lengkap,
    CASE WHEN kry.honda_id IS NULL THEN kry.id_flp_md ELSE honda_id END AS honda_id,'' AS image,
    ktg.name AS nama_kategori,dl.kode_dealer_md,kry.jk,LEFT(act.jam,5) AS time
    ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'show_activity_sp') {
        $select = "act.id,act.parent_id,act.name,act.info,LEFT(act.jam,5) AS time, ktg.name as categories";
      } elseif ($filter['select'] == 'all') {
        $select = "act.*,kry.nama_lengkap,kry.jk,kry.id_flp_md,kry.honda_id";
      } elseif ($filter['select'] == 'show_activity_sc_sales_person') {
        $select = "kry.id_karyawan_dealer_int,kry.nama_lengkap AS name,act.code,act.info,ktg.name as categories,LEFT(act.jam,5) AS time";
      } elseif ($filter['select'] == 'count_activity') {
        $select = "act.*,kry.nama_lengkap,
        CASE WHEN kry.honda_id IS NULL THEN kry.id_flp_md ELSE honda_id END AS honda_id,kry.image AS image,
        ktg.name AS nama_kategori,dl.kode_dealer_md,kry.jk,IFNULL(COUNT(act.id),0) AS total";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_sc_sales_activity act
    JOIN ms_karyawan_dealer kry ON kry.id_karyawan_dealer_int=act.id_karyawan_dealer_int
    JOIN sc_ms_kategori_activity ktg ON ktg.id=act.id_kategori_activity
    JOIN ms_dealer dl ON dl.id_dealer=act.id_dealer
    $join
    $where $group $order $limit
    ");
  }

  function insertActivity($params)
  {
    $params['code'] = $this->getIDActivity($params['id_dealer']);
    $this->db->insert('tr_sc_sales_activity', $params);
  }

  public function getIDActivity($id_dealer = NULL)
  {
    $thbln     = date('ym');
    $tahun_bulan     = date('Y-m');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT code FROM tr_sc_sales_activity
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$tahun_bulan'
      ORDER BY created_at DESC LIMIT 0,1");

    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->code, -5);
      $new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/ACT/' . sprintf("%'.05d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_sc_sales_activity', ['code' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -5);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/ACT/' . sprintf("%'.05d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/ACT/00001';
    }
    return strtoupper($new_kode);
  }

  function getActivityParent($code)
  {
    return $this->db->query("SELECT 
    CASE 
      WHEN prp.id_prospek_int IS NOT NULL THEN prp.id_prospek_int 
      WHEN spk.no_spk_int IS NOT NULL THEN spk.no_spk_int
      WHEN so.id_sales_order_int IS NOT NULL THEN so.id_sales_order_int 
      ELSE 0
    END AS parent_id_int
    FROM tr_sc_sales_activity act
    LEFT JOIN tr_prospek prp ON prp.id_prospek=act.parent_id
    LEFT JOIN tr_spk spk ON spk.no_spk=act.parent_id
    LEFT JOIN tr_sales_order so ON so.id_sales_order=act.parent_id
    WHERE parent_id='$code'")->row();
  }
}
