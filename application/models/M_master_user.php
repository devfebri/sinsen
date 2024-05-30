<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_master_user extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
  }
  function getUser($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['id_user'])) {
      $where .= " AND id_user='{$filter['id_user']}'";
    }
    if (isset($filter['id_dealer'])) {
      $where .= " AND kd.id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['id_karyawan_dealer'])) {
      $where .= " AND usr.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
    }
    if (isset($filter['id_karyawan_dealer_int'])) {
      $where .= " AND kd.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
    }
    if (isset($filter['username'])) {
      $where .= " AND username='{$filter['username']}'";
    }
    if (isset($filter['username_sc'])) {
      $where .= " AND username_sc='{$filter['username_sc']}'";
    }
    if (isset($filter['role_sc'])) {
      $where .= " AND role_sc='{$filter['role_sc']}'";
    }
    if (isset($filter['active_sc'])) {
      $where .= " AND active_sc='{$filter['active_sc']}'";
    }
    if (isset($filter['jenis_user'])) {
      $where .= " AND usr.jenis_user='{$filter['jenis_user']}'";
    }

    $group = '';
    if (isset($filter['group_by_username'])) {
      $group = " GROUP BY usr.username ";
    }
    if (isset($filter['group_by_username_sc'])) {
      $group = " GROUP BY usr.username_sc ";
    }

    $result = $this->db->query("SELECT usr.*,ms_user_group.user_group,kd.id_dealer,
    CASE 
      WHEN usr.active=0 THEN 0
      WHEN usr.active=1 THEN 1
      else 0
    END AS active
    FROM ms_user usr
    LEFT JOIN ms_user_group ON usr.id_user_group = ms_user_group.id_user_group
		LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=usr.id_karyawan_dealer
    $where $group
    ");
    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Employee ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getRoleServiceConcept($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['aktif'])) {
      $where .= " AND aktif='{$filter['aktif']}'";
    }
    return $this->db->query("SELECT *
      FROM sc_ms_role st
    $where
    ");
  }

  function cekRegistrasiKaryawan($params)
  {
    //Cek Registrasi Karyawan
    $f_kry = ['id_karyawan_dealer' => $params['id_karyawan_dealer'],'jenis_user' => $params['jenis_user']];
    $kry = $this->getUser($f_kry);
    if ($kry->num_rows() > 0) {
      $response = [
        'status' => 'error',
        'pesan' => 'ID Karyawan Dealer : ' . $params['id_karyawan_dealer'] . ' sudah diregistrasi'
      ];
      send_json($response);
    }
  }
}
