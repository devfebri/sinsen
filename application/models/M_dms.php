<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dms extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
  

  function getH1TargetManagement($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['id_kategori_kendaraan'])) {
      if ($filter['id_kategori_kendaraan'] != '') {
        $where .= " AND tk.id_kategori='{$filter['id_kategori_kendaraan']}'";
      }
    }
    if (isset($filter['id'])) {
      $where .= " AND tg.id='{$filter['id']}'";
    }
    if (isset($filter['type_id'])) {
      if ($filter['type_id'] != '') {
        $where .= " AND tk.id_tipe_kendaraan_int='{$filter['type_id']}'";
      }
    }
    if (isset($filter['honda_id'])) {
      if ($filter['honda_id'] != '') {
        $where .= " AND tg.honda_id='{$filter['honda_id']}'";
      }
    }
    if (isset($filter['honda_id_in'])) {
      if ($filter['honda_id_in'] != '') {
        $where .= " AND tg.honda_id in({$filter['honda_id_in']})";
      }
    }
    if (isset($filter['id_karyawan_dealer_int_in'])) {
      if ($filter['id_karyawan_dealer_int_in'] != '') {
        $where .= " AND kd.id_karyawan_dealer_int in({$filter['id_karyawan_dealer_int_in']})";
      }
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where .= " AND kd.id_karyawan_dealer in({$filter['id_karyawan_dealer_in']})";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND kd.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND tg.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['tahun'])) {
      $where .= " AND tg.tahun='{$filter['tahun']}'";
    }
    if (isset($filter['bulan'])) {
      $where .= " AND tg.bulan='{$filter['bulan']}'";
    }
    if (isset($filter['bulan'])) {
      $where .= " AND tg.bulan='{$filter['bulan']}'";
    }
    if (isset($filter['active'])) {
      $where .= " AND tg.active={$filter['active']} ";
    }
    if (isset($filter['deleted'])) {
      if ($filter['deleted'] == true) {
        $where .= " AND tg.deleted =1";
      } else {
        $where .= " AND tg.deleted=0";
      }
    } else {
      $where .= " AND tg.deleted=0";
    }
    if (isset($filter['periode'])) {
      $where .= " AND CONCAT(tg.tahun,'-',RIGHT(CONCAT('00', IFNULL(tg.bulan,'')), 2))='{$filter['periode']}'";
    }
    // send_json($filter);
    if (isset($filter['periode_lebih_kecil'])) {
      $where .= " AND CONCAT(tg.tahun,'-',RIGHT(CONCAT('00', IFNULL(tg.bulan,'')), 2))<'{$filter['periode_lebih_kecil']}'";
    }
    if (isset($filter['periode_sama_lebih_besar'])) {
      $where .= " AND CONCAT(tg.tahun,'-',RIGHT(CONCAT('00', IFNULL(tg.bulan,'')), 2))>='{$filter['periode_sama_lebih_besar']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.tot_harga', 'po.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tg.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }


    $select = "tg.*,kode_dealer_md,tipe_ahm,nama_lengkap,CONCAT(tg.tahun,'-',RIGHT(CONCAT('00', IFNULL(tg.bulan,'')), 2)) tahun_bulan";

    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_prospek') {
        $select = "IFNULL(SUM(tg.target_prospek),0) AS sum_prospek";
      } elseif ($filter['select'] == 'sum_sales') {
        $select = "IFNULL(SUM(tg.target_sales),0) AS sum_sales";
      } elseif ($filter['select'] == 'sum_spk') {
        $select = "IFNULL(SUM(tg.target_spk),0) AS sum_spk";
      } elseif ($filter['select'] == 'kategori_kendaraan') {
        $select = "ktg_k.id_kategori_int,tk.id_kategori,ktg_k.kategori";
      }
    }

    $join = '';
    if (isset($filter['join_kategori_kendaraan'])) {
      $join .= "JOIN ms_kategori ktg_k ON ktg_k.id_kategori=tk.id_kategori";
    }

    $group = '';
    if (isset($filter['group_by_kategori_kendaraan'])) {
      $group = " GROUP BY tk.id_kategori";
    }

    return $this->db->query("SELECT $select
    FROM dms_h1_target_management tg
    JOIN ms_dealer dl ON dl.id_dealer=tg.id_dealer
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=tg.id_tipe_kendaraan
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_flp_md=tg.honda_id
    $join
    $where $group $order $limit
    ");
  }
  function getTeam($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_team'])) {
      if ($filter['id_team'] != '') {
        $where .= " AND tm.id_team='{$filter['id_team']}'";
      }
    }

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND tm.id_dealer='{$filter['id_dealer']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tm.id_team LIKE '%$search%'
              
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.tot_harga', 'po.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tm.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT tm.id_team,tm.nama_team,tm.active
    FROM dms_ms_team tm
    JOIN ms_dealer dl ON dl.id_dealer=tm.id_dealer
    $where $order $limit
    ");
  }
  function getTeamStructureManagement($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['cek_prospek_rendah'])) {
      $actual = "SELECT COUNT(id_prospek) FROM tr_prospek WHERE LEFT(tgl_prospek,7)='{$filter['tahun_bulan_prospek']}' AND  id_karyawan_dealer IN(SELECT id_karyawan_dealer FROM dms_team_structure_management_detail tsmd WHERE tsmd.id_team_structure=tm.id_team_structure)";

      $target = "SELECT IFNULL(SUM(target_prospek),0) 
                FROM dms_h1_target_management trg 
                WHERE trg.honda_id IN(SELECT CASE WHEN honda_id IS NULL THEN id_flp_md ELSE honda_id END 
                                      FROM dms_team_structure_management_detail tsmd 
                                      WHERE tsmd.id_team_structure=tm.id_team_structure 
                                      AND CASE WHEN honda_id IS NULL THEN id_flp_md ELSE honda_id END IS NOT NULL
                ) AND trg.tahun='{$filter['tahun_target']}' AND trg.bulan='{$filter['bulan_target']}' AND trg.active=1 and trg.deleted=0";
    }



    if (isset($filter['id_team_structure'])) {
      if ($filter['id_team_structure'] != '') {
        $where .= " AND tm.id_team_structure='{$filter['id_team_structure']}'";
      }
    }

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND tm.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_sales_coordinator'])) {
      if ($filter['id_sales_coordinator'] != '') {
        $where .= " AND tm.id_sales_coordinator='{$filter['id_sales_coordinator']}'";
      }
    }
    if (isset($filter['tahun'])) {
      if ($filter['tahun'] != '') {
        $where .= " AND tm.tahun='{$filter['tahun']}'";
      }
    }
    if (isset($filter['bulan'])) {
      if ($filter['bulan'] != '') {
        $where .= " AND tm.bulan='{$filter['bulan']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND tm.active='{$filter['active']}'";
      }
    }

    if (isset($filter['cek_prospek_rendah'])) {
      $where .= " AND ($actual)<($target)";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tm.id_team LIKE '%$search%'
              OR kd.nama_lengkap LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_team_structure', 'nama_team', 'nama_lengkap', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tm.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page = $page < 0 ? 0 : $page;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    $group = '';
    if (isset($filter['group_by_sales_coordinator'])) {
      $group = " GROUP BY tm.id_sales_coordinator";
    }
    if (isset($filter['select'])) {
      $select = "id_sales_coordinator,tm.id_team,id_team_structure, team.nama_team,nama_lengkap,kd.id_karyawan_dealer_int,kd.jk,kd.no_hp,kd.image,
      (SELECT COUNT(id_karyawan_dealer) FROM dms_team_structure_management_detail WHERE id_team_structure=tm.id_team_structure) AS tot_detail,($actual) actual,($target) target";
    } else {
      $select = "id_sales_coordinator,tm.id_team,id_team_structure, team.nama_team,nama_lengkap,kd.id_karyawan_dealer_int,kd.jk,kd.no_hp,kd.image,
  (SELECT COUNT(id_karyawan_dealer) FROM dms_team_structure_management_detail WHERE id_team_structure=tm.id_team_structure) AS tot_detail,tm.active";
    }
    return $this->db->query("SELECT $select
    FROM dms_team_structure_management tm
    JOIN dms_ms_team team ON team.id_team=tm.id_team AND team.id_dealer=tm.id_dealer
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=tm.id_sales_coordinator
    $where $group $order $limit
    ");
  }
  function getTeamStructureManagementDetail($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_team_structure'])) {
      if ($filter['id_team_structure'] != '') {
        $where .= " AND tmd.id_team_structure='{$filter['id_team_structure']}'";
      }
    }
    if (isset($filter['id_sales_coordinator'])) {
      if ($filter['id_sales_coordinator'] != '') {
        $where .= " AND sc.id_karyawan_dealer='{$filter['id_sales_coordinator']}'";
      }
    }

    if (isset($filter['search_sc'])) {
      $search = $filter['search_sc'];
      if ($search != '') {
        $where .= " AND (sc.id_karyawan_dealer LIKE '%$search%'
              OR sc.id_karyawan_dealer_int LIKE '%$search%'
              OR sc.nama_lengkap LIKE '%$search%'
              ) 
        ";
      }
    }

    return $this->db->query("SELECT tmd.id_team_structure,tmd.id_karyawan_dealer,kd.id_flp_md,kd.nama_lengkap,jabatan,kd.id_flp_md,kd.honda_id,kd.id_karyawan_dealer_int,kd.image,kd.jk,kd.jk jenis_kelamin 
    FROM dms_team_structure_management_detail tmd
    JOIN dms_team_structure_management tm ON tm.id_team_structure=tmd.id_team_structure
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=tmd.id_karyawan_dealer
    JOIN ms_karyawan_dealer sc ON sc.id_karyawan_dealer=tm.id_sales_coordinator
    JOIN ms_jabatan jbt ON jbt.id_jabatan=kd.id_jabatan
    $where
    ");
  }
  public function get_id_team()
  {
    $tgl       = date('Y-m-d');
    $thbln     = date('ymd');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_team FROM dms_ms_team
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->id_team, -3);
      $new_kode   = 'T-' . sprintf("%'.03d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('dms_ms_team', ['id_team' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -3);
          $new_kode   = 'T-' . sprintf("%'.03d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'T-001';
    }
    return strtoupper($new_kode);
  }
  public function get_id_team_structure()
  {
    $tgl       = date('Y-m-d');
    $thbln     = date('m/y');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_team_structure FROM dms_team_structure_management
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->id_team_structure, -3);
      $new_kode   = 'TS/' . sprintf("%'.03d", $last_number + 1);
      $new_kode   = 'TS/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.03d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('dms_team_structure_management', ['id_team_structure' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -3);
          $new_kode   = 'TS/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.03d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'TS/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . '001';
    }
    return strtoupper($new_kode);
  }

  function getH23TargetManagementAHASS($filter)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['id_dealer'])) {
      $where .= " AND tg.id_dealer='{$filter['id_dealer']}'";
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
      $where .= " AND tg.id_dealer='$id_dealer'";
    }
    if (isset($filter['deleted'])) {
      if ($filter['deleted'] == true) {
        $where .= " AND deleted =1";
      } else {
        $where .= " AND deleted=0";
      }
    }
    if (isset($filter['target_ue'])) {
      if ($filter['target_ue'] != '') {
        $where .= " AND tk.target_ue='{$filter['target_ue']}'";
      }
    }
    if (isset($filter['tahun_bulan'])) {
      if ($filter['tahun_bulan'] != '') {
        $where .= " AND LEFT(tg.created_at,7)='{$filter['tahun_bulan']}'";
      }
    }
    if (isset($filter['tanggal'])) {
      if ($filter['tanggal'] != '') {
        $where .= " AND tg.tanggal='{$filter['tanggal']}'";
      }
    }
    if (isset($filter['tanggal_lebih_kecil'])) {
      if ($filter['tanggal_lebih_kecil'] != '') {
        $where .= " AND tg.tanggal<'{$filter['tanggal_lebih_kecil']}'";
      }
    }
    if (isset($filter['tanggal_lebih_besar_sama'])) {
      if ($filter['tanggal_lebih_besar_sama'] != '') {
        $where .= " AND tg.tanggal>='{$filter['tanggal_lebih_besar_sama']}'";
      }
    }
    if (isset($filter['id'])) {
      $where .= " AND tg.id='{$filter['id']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['tg.tanggal', 'kode_dealer_md', 'target_ue', 'target_revenue', 'target_oli', 'target_non_oli', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tg.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $select = "tg.*,kode_dealer_md";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'target_ue') {
        $select = "tg.target_ue";
      } elseif ($filter['select'] == 'summary') {
        $select = "SUM(tg.target_ue) target_ue,
            SUM(tg.target_revenue) target_revenue,
            SUM(tg.target_oli) target_oli,
            SUM(tg.target_non_oli) target_non_oli
            ";
      }
    }
    // send_json($filter);
    return $this->db->query("SELECT $select
    FROM dms_h23_target_management_ahass tg
    JOIN ms_dealer dl ON dl.id_dealer=tg.id_dealer
    $where $order $limit
    ");
  }
  function getH23TargetManagementMekanik($filter)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['deleted'])) {
      if ($filter['deleted'] == true) {
        $where .= " AND deleted =1";
      } else {
        $where .= " AND deleted=0";
      }
    }
    if (isset($filter['target_ue'])) {
      if ($filter['target_ue'] != '') {
        $where .= " AND tg.target_ue='{$filter['target_ue']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_int'])) {
      if ($filter['id_karyawan_dealer_int'] != '') {
        $where .= " AND kd.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND kd.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
    }
    if (isset($filter['id_flp_md'])) {
      if ($filter['id_flp_md'] != '') {
        $where .= " AND (kd.id_flp_md='{$filter['id_flp_md']}' OR kd.honda_id='{$filter['id_flp_md']}')";
      }
    }

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND tg.id_dealer='{$filter['id_dealer']}'";
      }
    }

    if (isset($filter['tahun_bulan'])) {
      if ($filter['tahun_bulan'] != '') {
        $where .= " AND LEFT(tg.tanggal,7)='{$filter['tahun_bulan']}'";
      }
    }
    if (isset($filter['tanggal'])) {
      if ($filter['tanggal'] != '') {
        $where .= " AND tg.tanggal='{$filter['tanggal']}'";
      }
    }

    if (isset($filter['tanggal_lebih_kecil'])) {
      if ($filter['tanggal_lebih_kecil'] != '') {
        $where .= " AND tg.tanggal<'{$filter['tanggal_lebih_kecil']}'";
      }
    }
    if (isset($filter['tanggal_lebih_besar_sama'])) {
      if ($filter['tanggal_lebih_besar_sama'] != '') {
        $where .= " AND tg.tanggal>='{$filter['tanggal_lebih_besar_sama']}'";
      }
    }

    if (isset($filter['id'])) {
      $where .= " AND tg.id='{$filter['id']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tg.id LIKE '%$search%'
              OR tg.id_flp_md LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['tg.tanggal', 'kode_dealer_md', 'id_flp_md', 'nama_lengkap', 'target_ue', 'target_revenue', 'target_oli', 'target_non_oli', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tg.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $select = "tg.*,kode_dealer_md,tg.id_flp_md,nama_lengkap";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum') {
        $select = "SUM(tg.target_ue) AS sum";
      }
    }

    return $this->db->query("SELECT $select
    FROM dms_h23_target_management_mekanik tg
    JOIN ms_dealer dl ON dl.id_dealer=tg.id_dealer
    JOIN ms_karyawan_dealer kd ON kd.id_flp_md=tg.id_flp_md
    $where $order $limit
    ");
  }

  public function get_id_message($id_dealer = NULL)
  {
    // send_json($id_dealer);
    $tgl       = date('Y-m');
    $thbln     = date('y-m-');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $get_data  = $this->db->query("SELECT iid FROM dms_detail_message
			-- WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = $row->iid;
      $new_kode   = $last_number + 1;
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('dms_detail_message', ['iid' => $new_kode])->num_rows();
        if ($cek > 0) {
          $new_kode   = $new_kode + 1;
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 1;
    }
    return strtoupper($new_kode);
  }
  function getH1BroadcastMessage($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['iid'])) {
      $where .= " AND bc.iid='{$filter['iid']}'";
    }
    if (isset($filter['sent'])) {
      $where .= " AND bc.sent='{$filter['sent']}'";
    }
    if (isset($filter['sender_id'])) {
      $where .= " AND bc.sender_id='{$filter['sender_id']}'";
    }
    if (isset($filter['periode'])) {
      $where .= " AND LEFT(bc.created_at,7)='{$filter['periode']}'";
    }
    if (isset($filter['periode_lebih_kecil'])) {
      $where .= " AND LEFT(bc.created_at,7)<'{$filter['periode_lebih_kecil']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['tg.tanggal', 'kode_dealer_md', 'id_flp_md', 'nama_lengkap', 'target_ue', 'target_revenue', 'target_oli', 'target_non_oli', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY bc.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT bc.*,id_flp_md,nama_lengkap,message_type
    FROM dms_detail_message bc
    JOIN ms_user usr ON usr.id_user=bc.sender_id
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    LEFT JOIN dms_message_type mt ON mt.imsgtype=bc.imsgtype
    $where $order $limit
    ");
  }
  function getH1BroadcastMessageTo($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['iid'])) {
      $where .= " AND bc.iid='{$filter['iid']}'";
    }
    return $this->db->query("SELECT 
      CASE WHEN kd.id_karyawan_dealer IS NULL THEN kd_sc.id_karyawan_dealer ELSE kd.id_karyawan_dealer END id_karyawan_dealer,
      CASE WHEN kd.id_karyawan_dealer IS NULL THEN kd_sc.nama_lengkap ELSE kd.nama_lengkap END nama_lengkap,
      bc.xpmmsg_iid,
      bisdelete,bismarked,bisread,bissent,
      usr.username,usr_sc.username_sc,bc.vusername username_bc
    FROM dms_master_message bc
    LEFT JOIN ms_user usr ON usr.username=bc.vusername
    LEFT JOIN ms_user usr_sc ON usr_sc.username_sc=bc.vusername
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_sc ON kd_sc.id_karyawan_dealer=usr_sc.id_karyawan_dealer
    $where
    ");
  }

  function getH1BroadcastMessageReceived($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_karyawan_dealer_receiver'])) {
      $where .= " AND kd_receiver.id_karyawan_dealer='{$filter['id_karyawan_dealer_receiver']}'";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['tg.tanggal', 'kode_dealer_md', 'id_flp_md', 'nama_lengkap', 'target_ue', 'target_revenue', 'target_oli', 'target_non_oli', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY bc.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT bc.iid,bc.created_at,bc.vtitle,bc.vcontents,kd_sender.nama_lengkap,dmt.message_type
    FROM dms_master_message bcd
    JOIN dms_detail_message bc ON bc.iid=bcd.iid
    JOIN dms_message_type dmt ON dmt.imsgtype=bc.imsgtype
    left JOIN ms_user usr ON usr.username=bcd.vusername
    left JOIN ms_karyawan_dealer kd_receiver ON kd_receiver.id_karyawan_dealer=usr.id_karyawan_dealer
    left JOIN ms_user usr_sender ON usr_sender.id_user=bc.sender_id
    left JOIN ms_karyawan_dealer kd_sender ON kd_sender.id_karyawan_dealer=usr_sender.id_karyawan_dealer
    $where $order $limit
    ");
  }

  function getMessageType()
  {
    return $this->db->query("SELECT imsgtype,message_type FROM dms_message_type ORDER BY imsgtype ASC");
  }
  function getH1FollowUpActivity($filter)
  {
    $where = 'WHERE 1=1 ';
    $join = '';
    $select = '';
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND dl.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['pit_not_null'])) {
      $where .= " AND pit.id_pit IS NOT NULL";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (act.no_spk LIKE '%$search%'
              OR spk.nama_konsumen LIKE '%$search%'
              OR spk_gc.nama_npwp LIKE '%$search%'
              OR kd.nama_lengkap LIKE '%$search%'
              OR kd_gc.nama_lengkap LIKE '%$search%'
              ) 
        ";
      }
    }

    $status = "CASE 
                WHEN sa.id_sa_form IS NULL THEN 'waiting'
                WHEN sa.id_sa_form IS NOT NULL THEN 'open sa'
                WHEN wo.id_work_order IS NOT NULL THEN 'open wo'
                WHEN wo.status='pause' THEN wo.status
                WHEN wo.status='close' THEN wo.status
              END
              ";

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['act,created_at', 'spk.nama_konsumen', 'act.kategori', 'act.detail_activity'];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY act.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT 
    act.created_at,
    CASE WHEN spk.no_spk IS NOT NULL THEN spk.nama_konsumen ELSE spk_gc.nama_npwp END AS nama_konsumen,
    act.kategori,
    CASE WHEN spk.no_spk IS NOT NULL THEN kd.nama_lengkap ELSE kd_gc.nama_lengkap END AS sales_people,
    act.detail_activity
    FROM tr_manage_activity_after_dealing act
    JOIN ms_dealer dl ON dl.id_dealer=act.id_dealer
    LEFT JOIN tr_spk spk ON spk.no_spk=act.no_spk
    LEFT JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
    LEFT jOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc=act.no_spk
    LEFT jOIN tr_prospek_gc prp_gc ON prp_gc.id_prospek_gc=spk_gc.id_prospek_gc
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_gc ON kd_gc.id_karyawan_dealer=prp_gc.id_karyawan_dealer
    $join
    $where $order $limit
    ");
  }
  function getH23QueuePitManagement($filter)
  {
    $where = "WHERE 1=1 AND wo.status!='close'";
    $join = '';
    $select = '';
    if (isset($filter['join_mekanik'])) {
      $join .= " JOIN ms_karyawan_dealer kd_m ON kd_m.id_karyawan_dealer=sa.id_karyawan_dealer";
      $select .= ",CASE WHEN kd_m.honda_id IS NULL OR kd_m.honda_id='' THEN kd_m.id_flp_md ELSE kd_m.honda_id END AS honda_id";
    }
    if (isset($filter['join_pit'])) {
      $join .= " JOIN ms_h2_pit pit ON pit.id_pit=sa.id_pit AND pit.id_dealer=sa.id_dealer";
      $select .= ",sa.id_pit, pit.id_pit,pit.jenis_pit";
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND dl.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['pit_not_null'])) {
      $where .= " AND pit.id_pit IS NOT NULL";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    $status = "CASE 
                WHEN sa.id_sa_form IS NULL THEN 'waiting'
                WHEN sa.id_sa_form IS NOT NULL THEN 'open sa'
                WHEN wo.id_work_order IS NOT NULL THEN 'open wo'
                WHEN wo.status='pause' THEN wo.status
                WHEN wo.status='close' THEN wo.status
              END
              ";

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = [null, 'kode_dealer_md', 'sa.created_at', 'id_antrian', 'bk.created_at', 'sa.created_at', 'ch23.no_polisi', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY sa.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT 'E20' AS kode_md,sa.id_antrian,LEFT(sa.created_at,10) AS tgl_transaksi,bk.created_at AS waktu_booking,sa.created_at,ch23.no_polisi,$status AS status,dl.kode_dealer_md $select
    FROM tr_h2_sa_form sa
    JOIN ms_dealer dl ON dl.id_dealer=sa.id_dealer
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=sa.id_sa_form
    LEFT JOIN tr_h2_manage_booking bk ON bk.id_booking=sa.id_booking
    $join
    $where $order $limit
    ");
  }

  function getKaryawanDealer($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND kry.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND kry.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
    }
    if (isset($filter['id_flp_md'])) {
      if ($filter['id_flp_md'] != '') {
        $where .= " AND kry.id_flp_md='{$filter['id_flp_md']}'";
      }
    }
    if (isset($filter['id_jabatan_in'])) {
      if ($filter['id_jabatan_in'] != '') {
        $where .= " AND kry.id_jabatan IN ({$filter['id_jabatan_in']})";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (kry.nama_lengkap LIKE '%$search%'
              OR kry.honda_id LIKE '%$search%'
              OR kry.id_flp_md LIKE '%$search%'
              OR jbt.jabatan LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view_mekanik') {
          $order_column = ['id_karyawan_dealer', 'id_flp_md', 'nama_lengkap', 'jabatan', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY kry.nama_lengkap ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_karyawan_dealer,nama_lengkap,id_flp_md,jabatan,kry.active
    FROM ms_karyawan_dealer kry
    JOIN ms_jabatan jbt ON jbt.id_jabatan=kry.id_jabatan
    $where $order $limit
    ");
  }

  function getMechanicProfile($filter)
  {
    $where = 'WHERE 1=1 ';
    $bulan = date_ym($filter['tanggal']);
    $tanggal = date_ymd($filter['tanggal']);
    // send_json($bulan);
    // if (isset($filter['id_dealer'])) {
    $where .= " AND kd.id_dealer='{$filter['id_dealer']}'";
    // }
    // if (isset($filter['id_karyawan_dealer'])) {
    $where .= " AND kd.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
    // }
    $target_ue = "SELECT SUM(target_ue) 
                  FROM dms_h23_target_management_mekanik 
                  WHERE (id_flp_md=kd.id_flp_md OR id_flp_md=kd.honda_id) 
                    AND id_dealer=kd.id_dealer 
                    AND LEFT(tanggal,7)='$bulan'";

    $capaian_ue = "SELECT COUNT(id_work_order) 
                  FROM tr_h2_wo_dealer wo 
                  WHERE id_karyawan_dealer=kd.id_karyawan_dealer 
                  AND status='closed'
                  AND LEFT(wo.waktu_njb,7)='$bulan'
                  ";
    $ue_selesai_today = "SELECT COUNT(id_work_order) 
                  FROM tr_h2_wo_dealer wo 
                  WHERE status='closed'
                  AND (id_flp_md=kd.id_flp_md OR id_flp_md=kd.honda_id)
                  AND LEFT(waktu_njb,10)='$tanggal' 
                  ";
    $tot_mekanik = "SELECT COUNT(id_karyawan_dealer) FROm ms_karyawan_dealer WHERE id_dealer=kd.id_dealer AND kd.id_jabatan IN(" . sql_jabatan_mekanik() . ")";
    $produktif_rata2 = "($capaian_ue) / ($tot_mekanik)";

    $pendapatan_ahass = "SELECT SUM(IFNULL(grand_total,0)+IFNULL(tot_nsc,0)) 
                         FROM tr_h2_wo_dealer wo_ahass
                         LEFT JOIN tr_h23_nsc nsc ON nsc.id_referensi=wo_ahass.id_work_order
                         WHERE wo_ahass.status='closed' 
                         AND no_njb IS NOT NULL
                         AND wo_ahass.id_dealer=kd.id_dealer
                         AND LEFT(waktu_njb,10)='$tanggal' 
                         ";
    $pendapatan_mekanik = "($pendapatan_ahass AND wo_ahass.id_karyawan_dealer=kd.id_karyawan_dealer)/($ue_selesai_today)";
    return $this->db->query("SELECT 
      id_flp_md,
      id_karyawan_dealer,
      nama_lengkap,
      jabatan,
      IFNULL(($target_ue),0) AS target_ue,
      IFNULL(($capaian_ue),0) AS capaian_ue,
      IFNULL(($ue_selesai_today),0) AS ue_selesai_today,
      IFNULL(($tot_mekanik),0) AS tot_mekanik,
      IFNULL(($produktif_rata2),0) AS produktif_rata2,
      IFNULL(($pendapatan_ahass),0) AS pendapatan_ahass,
      IFNULL(($pendapatan_mekanik),0) AS pendapatan_mekanik
    FROM ms_karyawan_dealer kd 
    JOIN ms_jabatan jbt ON jbt.id_jabatan=kd.id_jabatan
    $where");
  }

  function getTeamSalesPeople($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_team'])) {
      if ($filter['id_team'] != '') {
        $where .= " AND tm.id_team='{$filter['id_team']}'";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND tm.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND tmd.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
    }
    // if (isset($filter['tahun'])) {
    //   if ($filter['tahun'] != '') {
    //     $where .= " AND tm.tahun='{$filter['tahun']}'";
    //   }
    // }
    // if (isset($filter['bulan'])) {
    //   if ($filter['bulan'] != '') {
    //     $where .= " AND tm.bulan='{$filter['bulan']}'";
    //   }
    // }

    if (isset($filter['id_sales_coordinator'])) {
      if ($filter['id_sales_coordinator'] != '') {
        $where .= " AND tm.id_sales_coordinator='{$filter['id_sales_coordinator']}'";
      }
    }

    $group = '';
    if (isset($filter['group_by_id_sales_people'])) {
      $group .= " GROUP BY tmd.id_karyawan_dealer";
    }

    $result =  $this->db->query("SELECT tmd.id_team_structure,tmd.id_karyawan_dealer,kd.id_flp_md,kd.nama_lengkap,jabatan,kd.id_flp_md,
    CASE WHEN kd.honda_id IS NULL OR kd.honda_id='' THEN kd.id_flp_md ELSE kd.honda_id END AS honda_id,
    kd.id_karyawan_dealer_int,kd.image,kd.jk
    FROM dms_team_structure_management_detail tmd
    JOIN dms_team_structure_management tm ON tm.id_team_structure=tmd.id_team_structure
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=tmd.id_karyawan_dealer
    JOIN ms_karyawan_dealer sc ON sc.id_karyawan_dealer=tm.id_sales_coordinator
    JOIN ms_jabatan jbt ON jbt.id_jabatan=kd.id_jabatan
    $where
    $group
    ");
    if (isset($filter['return'])) {
      $multi_id = [];
      if ($filter['return'] == 'multi_id_karyawan_dealer') {
        foreach ($result->result() as $rs) {
          $multi_id[] = $rs->id_karyawan_dealer;
        }
      } elseif ($filter['return'] == 'multi_id_karyawan_dealer_int') {
        foreach ($result->result() as $rs) {
          $multi_id[] = $rs->id_karyawan_dealer_int;
        }
      } elseif ($filter['return'] == 'multi_honda_id') {
        foreach ($result->result() as $rs) {
          $multi_id[] = $rs->honda_id == NULL ? $rs->id_flp_md : $rs->honda_id;
        }
      } elseif ($filter['return'] == 'multi_honda_id_id_karyawan_dealer_result_all') {
        $multi_id = [
          'result' => $result->result(),
          'multi_honda_id' => [],
          'multi_id_karyawan_dealer' => [],
        ];
        foreach ($result->result() as $rs) {
          $multi_id['multi_honda_id'][] = $rs->honda_id == NULL ? $rs->id_flp_md : $rs->honda_id;
          $multi_id['multi_id_karyawan_dealer'][] = $rs->id_karyawan_dealer;
          $multi_id['multi_id_karyawan_dealer_int'][] = $rs->id_karyawan_dealer_int;
        }
      }
      return $multi_id;
    } else {
      return $result;
    }
  }

  function getOneLeader($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND tmd.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
    }
    return $this->db->query("SELECT id_sales_coordinator
    FROM dms_team_structure_management_detail tmd
    JOIN dms_team_structure_management tm ON tm.id_team_structure=tmd.id_team_structure
    $where
    ");
  }

  function getTargetDiskon($filter)
  {
    $where = "WHERE 1=1 AND active=1";
    if (isset($filter['id_karyawan_dealer'])) {
      $where .= " AND honda_id=(SELECT CASE WHEN honda_id IS NULL THEN id_flp_md ELSE id_flp_md END FROM ms_karyawan_dealer WHERE id_karyawan_dealer='{$filter['id_karyawan_dealer']}')";
    }
    if (isset($filter['tahun'])) {
      $where .= " AND tahun='{$filter['tahun']}'";
    }
    if (isset($filter['bulan'])) {
      $where .= " AND bulan='{$filter['bulan']}'";
    }
    return $this->db->query("SELECT SUM(IFNULL(kuota_unit_diskon,0)) AS tot 
    FROm dms_h1_target_management
    $where")->row()->tot;
  }
  function getTargetDiskonAmount($filter)
  {
    $where = "WHERE 1=1 AND active=1";
    if (isset($filter['id_dealer'])) {
      $where .= " AND id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['tahun'])) {
      $where .= " AND tahun='{$filter['tahun']}'";
    }
    if (isset($filter['bulan'])) {
      $where .= " AND bulan='{$filter['bulan']}'";
    }
    return $this->db->query("SELECT SUM(IFNULL(batas_approval_diskon,0)) AS tot 
    FROm dms_h1_target_management
    $where")->row()->tot;
  }

  function getH23PitMekanikManagement($filter)
  {
    $where = "WHERE 1=1 ";
    $join = '';
    $select = '';

    $tgl_transaksi = "SELECT LEFT(wo.created_at,10) 
      FROM tr_h2_wo_dealer wo 
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      WHERE sa.id_pit=pt.id_pit AND wo.id_dealer=pt.id_dealer AND wo.status!='closed' ORDER BY wo.created_at DESC LIMIT 1";

    $nama_customer_no_polisi = "SELECT CONCAT(nama_customer,'|',no_polisi)
      FROM tr_h2_wo_dealer wo 
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      WHERE sa.id_pit=pt.id_pit AND wo.id_dealer=pt.id_dealer AND wo.status!='closed' ORDER BY wo.created_at DESC LIMIT 1";

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND dl.id_dealer='{$filter['id_dealer']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (($tgl_transaksi) LIKE '%$search%'
              OR ($nama_customer_no_polisi) LIKE '%$search%'
              OR kd.id_flp_md LIKE '%$search%'
              OR kd.honda_id LIKE '%$search%'
              OR pt.id_pit LIKE '%$search%'
              OR pt.jenis_pit LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = [null, 'dl.kode_dealer_md', '', 'kd.honda_id', 'pt.id_pit', 'pt.jenis_pit'];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY pt.id_pit ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT 
    'E20' AS kode_md,dl.kode_dealer_md,($tgl_transaksi) tgl_transaksi,kd.honda_id,pt.id_pit,pt.jenis_pit,kd.nama_lengkap nama_mekanik,($nama_customer_no_polisi) nama_customer_no_polisi
    FROM ms_h2_pit_mekanik pt
    JOIN ms_dealer dl ON dl.id_dealer=pt.id_dealer
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=pt.id_karyawan_dealer
    $join
    $where $order $limit
    ");
  }
  function mechanic_diagram($get)
  {
    $type = [
      'kpb' => ['ASS1', 'ASS2', 'ASS3', 'ASS4'],
      'claim' => ['C1', 'C2'],
      'quick_service' => ['QS'],
      'heavy_repair' => ['HR'],
      'light_repair' => ['LR'],
      'light_service' => ['LS'],
      'complete_service' => ['CS'],
      'oil_replacement' => ['OR+'],
    ];
    $result = [];
    foreach ($type as $key => $value) {
      $f = [
        'id_karyawan_dealer' => $get['id_karyawan_dealer'],
        'id_type_in' => arr_in_sql($value),
        'tahun_bulan_wo' => $get['tahun_bulan_wo']
      ];
      $count = $this->m_wo->getMekanikHistoryServis($f);
      $result[$key] = (int)$count;
    }
    // send_json($result);
    return $result;
  }

  function getTargetSalesPeopleByTipe($id_karyawan_dealer,$id_tipe_kendaraan,$tahun,$bulan)
  {
    $get = $this->db->query("SELECT id_tipe_kendaraan,target_prospek,target_sales,target_spk,kuota_unit_diskon,batas_approval_diskon 
    FROM dms_h1_target_management tm
    JOIN ms_karyawan_dealer kr ON kr.id_flp_md=tm.honda_id OR kr.honda_id=tm.honda_id
    WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND tahun='$tahun' AND bulan='$bulan' 
    AND kr.id_karyawan_dealer='$id_karyawan_dealer' AND (kr.id_flp_md IS NOT NULL OR kr.honda_id IS NOT NULL) AND tm.active=1 AND tm.deleted=0")->row();
    if ($get!=null) {
      return $get;
    }
  }


  
  function getSalesPeopleActive($filter)
  {

    $where = "WHERE 1=1";

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $id_dealer = $filter['id_dealer'];
        $where .= " AND kd.id_dealer='{$filter['id_dealer']}'";
      }
    }
    
    if (isset($filter['flp'])) {
      if ($filter['flp'] != '') {
        $where .= " AND kd.id_karyawan_dealer ='{$filter['flp']}'";
      }
    }

    if (isset($filter['bulan'])) {
      if ($filter['bulan'] != '') {
        $month = $filter['bulan'];
      }
    }

    $monthMinus1 = date('Y-m', strtotime("-1 months"));
    $monthMinus2 = date('Y-m', strtotime("-2 months"));
    $monthMinus3 = date('Y-m', strtotime("-3 months"));

    $a_monthMinus1 = $monthMinus1 . '-01';
    $lastDay_monthMinus1 = date('t', strtotime($monthMinus1));
    $b_monthMinus1 = $monthMinus1 . '-' . $lastDay_monthMinus1;
    
    $a_monthMinus2 = $monthMinus2 . '-01';
    $lastDay_monthMinus2 = date('t', strtotime($monthMinus2));
    $b_monthMinus2 = $monthMinus2 . '-' . $lastDay_monthMinus2;
    
    $a_monthMinus3 = $monthMinus3 . '-01';
    $lastDay_monthMinus3 = date('t', strtotime($monthMinus3));
    $b_monthMinus3 = $monthMinus3 . '-' . $lastDay_monthMinus3;

    $currentMonth = date('m');


    if($filter['set'] =='detail'){
      $select ="
      (SELECT target_prospek  FROM tr_target_sales_force_md_assign WHERE honda_id = pro.id_flp_md AND bulan='$currentMonth' LIMIT 1) AS target_prospek,
      (SELECT target_deal  FROM tr_target_sales_force_md_assign WHERE honda_id = pro.id_flp_md AND bulan='$currentMonth' LIMIT 1) AS target_sales,
      (SELECT target_sales  FROM tr_target_sales_force_md_assign WHERE honda_id = pro.id_flp_md AND bulan='$currentMonth' LIMIT 1) AS target_spk";

    }else if ($filter['set'] =='approve') {

      $select ="
      (SELECT target_prospek  FROM dms_h1_target_management WHERE honda_id = pro.id_flp_md AND bulan='$currentMonth' LIMIT 1) AS target_prospek,
      (SELECT target_sales  FROM dms_h1_target_management WHERE honda_id = pro.id_flp_md AND bulan='$currentMonth' LIMIT 1) AS target_sales,
      (SELECT target_spk  FROM dms_h1_target_management WHERE honda_id = pro.id_flp_md AND bulan='$currentMonth' LIMIT 1) AS target_spk";
     }
    
    $get_in = "
    select pro.id_flp_md as flp, kd.nama_lengkap ,
    sum(case when so.id_sales_order is not null then 1 else null end) as tot_ssu,
    sum(case when so.id_sales_order is not null AND so.tgl_cetak_invoice BETWEEN '$a_monthMinus1' AND '$b_monthMinus1' then 1 else 0 end) as tot_ssu_m_1,
    sum(case when so.id_sales_order is not null AND so.tgl_cetak_invoice BETWEEN '$a_monthMinus2' AND '$b_monthMinus2' then 1 else 0 end) as tot_ssu_m_2,
    sum(case when so.id_sales_order is not null AND so.tgl_cetak_invoice BETWEEN '$a_monthMinus3' AND '$b_monthMinus3' then 1 else 0 end) as tot_ssu_m_3,
    ROUND(sum(case when so.id_sales_order is not null AND so.tgl_cetak_invoice BETWEEN '$a_monthMinus3' AND '$b_monthMinus1' then 1 else 0 end)/3,0) as avg_tot,
    $select
    from tr_sales_order so 
    join tr_spk spk on so.no_spk = spk.no_spk 
    join tr_prospek pro on pro.id_customer = spk.id_customer 
    join ms_karyawan_dealer kd on kd.id_karyawan_dealer = pro.id_karyawan_dealer
    $where
    and so.tgl_cetak_invoice BETWEEN '$a_monthMinus3' and '$b_monthMinus1 '
    AND kd.active ='1'
    and pro.id_flp_md <> ''
    group by pro.id_karyawan_dealer  
    ";

    $get_main = $this->db->query("SELECT 
    kd.id_flp_md ,kd.nama_lengkap,
    case when sub_query.tot_ssu_m_1 is null then 0 else sub_query.tot_ssu_m_1 end as tot_ssu_m_1,
    case when sub_query.tot_ssu_m_2 is null then 0 else sub_query.tot_ssu_m_2 end as tot_ssu_m_2,
    case when sub_query.tot_ssu_m_3 is null then 0 else sub_query.tot_ssu_m_3 end as tot_ssu_m_3,
    case when sub_query.avg_tot is null then 0 else sub_query.avg_tot end as avg_tot
    from ms_karyawan_dealer kd 
    left join ($get_in) as sub_query on kd.id_flp_md =  sub_query.flp
    WHERE kd.id_dealer  =' $id_dealer' AND kd.id_flp_md <> ''  
    AND kd.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND kd.active='1' 
    order by kd.nama_lengkap asc
    ");

    return $get_main;
  }

  function jumlah_target_from_md($filter)
  {
    $get = $this->db->query("SELECT sum(jumlah) as jumlah from tr_target_sales_force_md_detail WHERE  id_dealer ='00888'");
    return  $get;
  }
  
  
  function getSalesPeopleTipeKendaraan($filter)
  {

 
    $where = "WHERE 1=1";


    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND kd.id_dealer='{$filter['id_dealer']}'";
      }
    }

    
    if (isset($filter['flp'])) {
      if ($filter['flp'] != '') {
        $where .= " AND kd.id_karyawan_dealer ='{$filter['flp']}'";
      }
    }


    if (isset($filter['tipe_kendaraan_in'])) {
      if ($filter['tipe_kendaraan_in'] != '') {
        $where .= " AND spk.id_tipe_kendaraan in (".$filter['tipe_kendaraan_in'].") ";
      }
    }

    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND spk.id_tipe_kendaraan  ='{$filter['id_tipe_kendaraan']}'";
      }
    }


    $get = $this->db->query("SELECT pro.id_flp_md, kd.nama_lengkap 
    from tr_sales_order so 
    join tr_spk spk on so.no_spk = spk.no_spk 
    join tr_prospek pro on pro.id_customer = spk.id_customer 
    join ms_karyawan_dealer kd on kd.id_karyawan_dealer = pro.id_karyawan_dealer
    $where
    AND kd.active ='1'
    and pro.id_flp_md <> ''
    group by pro.id_karyawan_dealer  
    ");


    $monthMinus1 = date('Y-m', strtotime("-1 months"));
    $monthMinus2 = date('Y-m', strtotime("-2 months"));
    $monthMinus3 = date('Y-m', strtotime("-3 months"));

    $a_monthMinus1 = $monthMinus1 . '-01';
    $lastDay_monthMinus1 = date('t', strtotime($monthMinus1));
    $b_monthMinus1 = $monthMinus1 . '-' . $lastDay_monthMinus1;
    
    $a_monthMinus2 = $monthMinus2 . '-01';
    $lastDay_monthMinus2 = date('t', strtotime($monthMinus2));
    $b_monthMinus2 = $monthMinus2 . '-' . $lastDay_monthMinus2;
    
    $a_monthMinus3 = $monthMinus3 . '-01';
    $lastDay_monthMinus3 = date('t', strtotime($monthMinus3));
    $b_monthMinus3 = $monthMinus3 . '-' . $lastDay_monthMinus3;

    $currentMonth = date('m');
    
    $get = $this->db->query("SELECT pro.id_flp_md, kd.nama_lengkap ,
    ROUND(sum(case when so.id_sales_order is not null AND so.tgl_cetak_invoice BETWEEN '$a_monthMinus3' AND '$b_monthMinus1' then 1 else 0 end)/3,0) as avg_tot_percentage,
    (ROUND(SUM(CASE WHEN so.id_sales_order is not null AND so.tgl_cetak_invoice BETWEEN '$a_monthMinus3' AND '$b_monthMinus1' THEN 1 ELSE 0 END))) AS total_ssu
    from tr_sales_order so 
    join tr_spk spk on so.no_spk = spk.no_spk 
    join tr_prospek pro on pro.id_customer = spk.id_customer 
    join ms_karyawan_dealer kd on kd.id_karyawan_dealer = pro.id_karyawan_dealer
    $where
    AND kd.active ='1'
    and pro.id_flp_md <> ''
    group by pro.id_karyawan_dealer  
    ");
    return $get;
  }

  function getSalesPeople($id_dealer)
  {
    $query= $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer 
    FROM ms_karyawan_dealer
    LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
    WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' 
    AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN('JBT-099','JBT-035','JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103')  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");

    return $query;
  
  }




}
