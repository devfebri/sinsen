<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_sp_inbox extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
  }

  function getInbox($filter = NULL)
  {
    $where  = 'WHERE sent=1 ';
    $limit  = '';
    $select = '';

    if (isset($filter['username'])) {
      $where .= " AND vusername='{$filter['username']}'";
    }
    if (isset($filter['sent'])) {
      $where .= " AND dm.sent='{$filter['sent']}'";
    }
    if (isset($filter['bisread'])) {
      $where .= " AND mm.bisread='{$filter['bisread']}'";
    }
    if (isset($filter['tanggal'])) {
      $where .= " AND LEFT(dm.created_at,10)='{$filter['tanggal']}'";
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (title LIKE '%{$filter['search']}%'
                        OR sp.id_program_ahm LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }
    if (isset($filter['select_tanggal'])) {
      $select .= "LEFT(dm.created_at,10) AS tanggal";
    }
    if (isset($filter['select_detail'])) {
      $name = "SELECT nama_lengkap FROM ms_karyawan_dealer kry
               JOIN ms_user usr ON usr.id_karyawan_dealer=kry.id_karyawan_dealer WHERE usr.id_user=dm.sender_id";

      $info = "SELECT 
               CASE 
                WHEN rl.code='sales_coordinator' THEN 'Message From SC'
                WHEN rl.code='branch_manager' THEN 'Message From BM'
                WHEN rl.code IS NULL THEN CONCAT('Message From ',kry.nama_lengkap)
                ELSE CONCAT('Message From ',kry.nama_lengkap)
               END
               FROM ms_karyawan_dealer kry
               JOIN ms_user usr ON usr.id_karyawan_dealer=kry.id_karyawan_dealer 
               LEFT JOIN sc_ms_role rl ON rl.id=usr.role_sc
               WHERE usr.id_user=dm.sender_id";

      $select .= "mm.iid,xpmmsg_iid code,RIGHT(dm.created_at,8) time,dm.vcontents AS content,mt.message_type,bisread,'' AS expired, 
      CASE 
        WHEN xpmmsg_iid IS NULL OR xpmmsg_iid='' THEN ($info)
        ELSE CONCAT('Message From ', (SELECT nama_lengkap FROM ms_karyawan_dealer kd JOIN ms_user usr ON usr.id_karyawan_dealer=kd.id_karyawan_dealer WHERE usr.id_user=dm.created_by))
      END AS info,
      CASE 
        WHEN xpmmsg_iid IS NULL OR xpmmsg_iid='' THEN ($name)
        ELSE ''
      END AS name
      ";
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(mm.iid) AS count";
      }
    }

    if (isset($filter['group_by_tanggal'])) {
      $where .= " GROUP BY tanggal";
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    $order = "ORDER BY mm.iid ASC";
    if (isset($filter['order'])) {
      $order = "ORDER BY {$filter['order']}";
    }
    // $unit = '';
    return $this->db->query("SELECT $select
    FROM dms_master_message mm
    JOIN dms_detail_message dm ON dm.iid=mm.iid
    JOIN dms_message_type mt ON mt.imsgtype=dm.imsgtype
    $where 
    $order
    $limit
    ");
  }

  function getInboxBadge($username)
  {
    $filter_inbox = [
      'username' => $username,
      'sent' => 1,
      'bisread' => 0,
      'select' => 'count'
    ];
    return $this->m_inbox->getInbox($filter_inbox)->row()->count > 0 ? true : false;
  }
}
