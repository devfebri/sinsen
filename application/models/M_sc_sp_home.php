<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_sp_home extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
  }

  function getSalesProgram($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    $unit = "SELECT GROUP_CONCAT(tipe_ahm SEPARATOR ', ') 
            FROM tr_sales_program_tipe spt 
            JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spt.id_tipe_kendaraan
            WHERE spt.id_program_md=sp.id_program_md";
    if ($filter != NULL) {
      if (isset($filter['id_program_md'])) {
        if ($filter['id_program_md'] != '') {
          $where .= " AND sp.id_program_md='{$filter['id_program_md']}'";
        }
      }
      if (isset($filter['id_sales_program'])) {
        if ($filter['id_sales_program'] != '') {
          $where .= " AND sp.id_sales_program={$filter['id_sales_program']}";
        }
      }
      if (isset($filter['type_unit_id'])) {
        if ($filter['type_unit_id'] != '') {
          $where .= " AND '{$filter['type_unit_id']}' IN (SELECT id_tipe_kendaraan FROM tr_sales_program_tipe WHERE id_program_md=sp.id_program_md)";
        }
      }
      if (isset($filter['cek_periode'])) {
        if ($filter['cek_periode'] != '') {
          $where .= " AND '{$filter['cek_periode']}' BETWEEN periode_awal AND periode_akhir";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (title LIKE '%{$filter['search']}%'
                        OR sp.id_program_ahm LIKE '%{$filter['search']}%'
                        OR sp.id_program_md LIKE '%{$filter['search']}%'
                        OR sp.judul_kegiatan LIKE '%{$filter['search']}%'
                        OR sp.periode_akhir LIKE '%{$filter['search']}%'
                        OR sp.periode_awal LIKE '%{$filter['search']}%'
                        OR ($unit) LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['page'])) {
        $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
        $length = 10;
        // $start = $page == 1 ? 0 : $length * ($page - 1);
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    $price = "SELECT 
    CASE WHEN (ahm_cash+md_cash+dealer_cash+other_cash)=0 THEN 
            (ahm_kredit+md_kredit+dealer_kredit+other_kredit)
         ELSE (ahm_cash+md_cash+dealer_cash+other_cash)
    END
    FROM tr_sales_program_tipe WHERE id_program_md=sp.id_program_md LIMIT 1";

    return $this->db->query("SELECT id_sales_program id,
    id_program_ahm juklak_id,
    id_program_md code,
    judul_kegiatan name,
    CASE WHEN id_jenis_sales_program='SP-002' THEN 0  ELSE ($price) END price,
    periode_awal date_start,
    otomatis,
    periode_akhir date_end,($unit) AS unit
    FROM tr_sales_program sp
    $where 
    ORDER BY id_jenis_sales_program ASC
    $limit
    ");
  }

  function getAllProspectActual($id_karyawan_dealer, $user, $honda_id = NULL)
  {
    $this->load->model('m_dms');
    $this->load->model('m_h1_dealer_prospek','prospek');
    $f_actual_prospek = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'select' => 'count'
    ];

    $filter_target_prospek = [
      'honda_id_in' => $honda_id == NULL ? '' : $honda_id,
      'id_dealer' => $user->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_prospek',
      'active' => 1,
    ];

    $prospek_target=$this->m_dms->getH1TargetManagement($filter_target_prospek)->row()->sum_prospek;

    $this->load->model('m_h1_prospek','m_prospek');
    $actual = $this->m_prospek->getProspek($f_actual_prospek)->row()->count;
    $actual_gc = $this->db->query("SELECT SUM(qty) tot FROM tr_prospek_gc_kendaraan pgk
                JOIN tr_prospek_gc pg ON pg.id_prospek_gc=pgk.id_prospek_gc
                WHERE id_karyawan_dealer='$id_karyawan_dealer' AND id_dealer='$user->id_dealer' AND LEFT(pg.tgl_prospek,7)='".get_ym()."'")->row()->tot;
    $actual = $actual+$actual_gc;

    $filter_sudah_fu_prospek = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'sudah_fu' => true,
      'select' => 'count',
      'join' => 'join_prospek',
      // 'status_prospek_not' => 'Deal'
    ];
    $filter_belum_fu_prospek = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek_tidak_sama' => 'Deal',
      'select' => 'count',
      'belum_fu' => true,
      'join' => 'join_prospek',
      'status_prospek_not' => 'Deal'
    ];

    $f_hot = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek' => 'hot',
      'select' => 'count',
    ];
    $hot = $this->prospek->getProspek($f_hot)->row()->count;

    $f_medium = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek' => 'medium',
      'select' => 'count',
    ];
    $medium = $this->prospek->getProspek($f_medium)->row()->count;

    $f_low = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek' => 'low',
      'select' => 'count',
    ];
    $low = $this->prospek->getProspek($f_low)->row()->count;

    return [
      'actual'   => (int)$actual,
      'target'   => (int)$prospek_target==0?0:(int)$prospek_target,
      'sudah_fu'   => (int)$this->prospek->getProspek($filter_sudah_fu_prospek)->row()->count,
      'belum_fu'   => (int)$this->prospek->getProspek($filter_belum_fu_prospek)->row()->count,
      'hot' => (int)$hot,
      'medium' => (int)$medium,
      'low' => (int)$low,
    ];
  }
}
