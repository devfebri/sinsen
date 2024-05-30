<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_crm extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function fetch_follow_up($filter)
  {
    $id_dealer = $this->m_admin->cari_dealer();

    $order_column = array('id_follow_up', 'tgl_servis ', 'tgl_follow_up', 'id_work_order', 'ch23.id_customer', 'ch23.id_tipe_kendaraan', null, 'respon_service', null, null);
    if ($filter != null) {
      $where = "WHERE fu.id_dealer='$id_dealer' ";

      if ($filter['search'] != '') {
        $where .= " AND (ch23.nama_customer LIKE '%{$filter['search']}%'
                        OR ch23.no_hp LIKE '%{$filter['search']}%'
        ) ";
      }
      if (isset($filter['start_date']) && isset($filter['end_date'])) {
        $where .= " AND (fu.tgl_follow_up BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}')";
      }
      // if ($filter['id_booking'] != '') {
      //   $where .= " AND id_booking LIKE '%{$filter['id_booking']}%'";
      // }
      // if ($filter['no_mesin'] != '') {
      //   $where .= " AND ch23.no_mesin LIKE '%{$filter['no_mesin']}%'";
      // }
      // if ($filter['no_polisi'] != '') {
      //   $where .= " AND no_polisi LIKE '%{$filter['no_polisi']}%'";
      // }
    }
    $set_filter = '';
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }


    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT fu.*,nama_customer,tipe_ahm,tk.id_tipe_kendaraan,respon_service,sa.tgl_servis,sa.id_customer,kd.nama_lengkap 
      FROM tr_h2_follow_up_after_service AS fu
      JOIN tr_h2_wo_dealer AS wo ON wo.id_work_order=fu.id_work_order
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      JOIN ms_customer_h23 AS ch23 ON ch23.id_customer=sa.id_customer
      JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=fu.id_karyawan_dealer_folup
      $where
    $set_filter 
    ");
  }

  function fetch_service_history($filter)
  {
    $id_dealer = $this->m_admin->cari_dealer();

    $order_column = array('tgl_servis', 'id_work_order ', 'keluhan', null);
    if ($filter != null) {
      $where = "WHERE wo.id_dealer='$id_dealer' ";
      if ($filter['id_customer'] != '') {
        $where .= " AND id_customer ='{$filter['id_customer']}'";
      }
      // if ($filter['no_mesin'] != '') {
      //   $where .= " AND ch23.no_mesin LIKE '%{$filter['no_mesin']}%'";
      // }
      // if ($filter['no_polisi'] != '') {
      //   $where .= " AND no_polisi LIKE '%{$filter['no_polisi']}%'";
      // }
    }
    $set_filter = '';
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY sa.tgl_servis DESC ";
    }


    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT wo.id_work_order,tgl_servis,sa.keluhan_konsumen,sa.id_customer,wo.id_sa_form
      FROM tr_h2_wo_dealer AS wo
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      $where
    $set_filter
    ");
  }

  function getFollowUp($filter = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    // send_json($id_dealer);
    $where = "WHERE fu.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if ($filter['id_follow_up'] != '') {
        $where .= " AND fu.id_follow_up = '{$filter['id_follow_up']}'";
      }
    }
    return $this->db->query("SELECT sa.id_customer,nama_customer,tk.id_tipe_kendaraan,tk.tipe_ahm,fu.id_follow_up
    FROM tr_h2_follow_up_after_service AS fu
      JOIN tr_h2_wo_dealer AS wo ON wo.id_work_order=fu.id_work_order
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      JOIN ms_customer_h23 AS ch23 ON ch23.id_customer=sa.id_customer
      JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
    $where ");
  }
  function getHistoryFU($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if ($filter['id_follow_up'] != '') {
        $where .= " AND id_follow_up = '{$filter['id_follow_up']}'";
      }
    }
    return $this->db->query("SELECT *
    FROM tr_h2_follow_up_after_service_history AS fuh
    $where ");
  }

  function rescheduleFU($id_follow_up)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $batas = $this->db->get_where('ms_h2_batas_waktu', ['id_dealer' => $id_dealer]);
    if ($batas->num_rows() > 0) {
      $batas = $batas->row()->ulang_follow_up_after_service;
      $filter = ['id_follow_up' => $id_follow_up];
      $history_fu = $this->getHistoryFU($filter)->num_rows();
      if ($history_fu < ($batas - 1)) {
        return 1;
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  }

  function getKry($filter)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if ($filter['id_user'] != '') {
        $where .= " AND id_user = '{$filter['id_user']}'";
      }
    }
    return $this->db->query("SELECT kry.id_karyawan_dealer,nama_lengkap
    FROM ms_user AS usr
    JOIN ms_karyawan_dealer kry ON kry.id_karyawan_dealer=usr.id_karyawan_dealer
    $where ");
  }

  public function getBatas()
  {
    $id_dealer = $this->m_admin->cari_dealer();
    return $this->db->get_where('ms_h2_batas_waktu', ['id_dealer' => $id_dealer]);
  }

  function fetch_srv_reminder($filter)
  {

    $result = $this->getserviceReminder($filter);
    return $result;
  }

  function getServiceReminder($filter = null)
  {

    $where = "WHERE 1=1 ";
    if (dealer()) {
      $id_dealer = dealer()->id_dealer;
      $where .= " AND sr.id_dealer='$id_dealer' ";
    }
    if ($filter != null) {
      if (isset($filter['id_service_reminder'])) {
        if ($filter['id_service_reminder'] != '') {
          $where .= " AND sr.id_service_reminder = '{$filter['id_service_reminder']}'";
        }
      }
      if (isset($filter['set_periode'])) {
        $where .= " AND sr.tgl_contact_call BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}'";
      }
      if (isset($filter['status_null'])) {
        $where .= " AND sr.status IS NULL ";
      }
      if (isset($filter['tgl_reminder_sms'])) {
        $where .= " AND sr.tgl_reminder_sms = '{$filter['tgl_reminder_sms']}'";
      }
      if (isset($filter['tgl_contact_sms'])) {
        $where .= " AND sr.tgl_contact_sms = '{$filter['tgl_contact_sms']}'";
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (ch23.nama_customer LIKE '%{$filter['search']}%'
                          OR ch23.no_hp LIKE '%{$filter['search']}%'
                          OR ch23.id_customer LIKE '%{$filter['search']}%'
                          OR ch_sr.id_customer LIKE '%{$filter['search']}%'
                          OR ch_sr.nama_customer LIKE '%{$filter['search']}%'
                          OR sr.tipe_servis_berikutnya LIKE '%{$filter['search']}%'
                          OR CASE 
                            WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL 
                            THEN (SELECT tipe_ahm FROM tr_sales_order so
                                  JOIN tr_spk spk ON spk.no_spk=so.no_spk
                                  JOIN ms_tipe_kendaraan tk_h1 ON tk_h1.id_tipe_kendaraan=spk.id_tipe_kendaraan
                                  WHERE so.no_mesin=sr.no_mesin
                                )
                            WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL 
                            THEN(SELECT tipe_ahm FROM ms_tipe_kendaraan tk_h23
                                  WHERE id_tipe_kendaraan=ch23.id_tipe_kendaraan
                                )
                            ELSE (SELECT tipe_ahm FROM ms_tipe_kendaraan tk_h23
                                  WHERE id_tipe_kendaraan=ch_sr.id_tipe_kendaraan
                                )
                          END LIKE '%{$filter['search']}%'
                          OR sr.tgl_servis_berikutnya LIKE '%{$filter['search']}%'

          ) ";
        }
      }
      if (isset($filter['order'])) {
        $order_column = array('id_customer', 'nama_customer ', 'tipe_ahm', null, null, 'tgl_servis_berikutnya', 'tipe_servis_berikutnya', 'status_sms', 'status_call', null);

        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $where .= " ORDER BY $order_clm $order_by ";
        } else {
          $where .= " ORDER BY sr.created_at DESC ";
        }
      }
      if (isset($filter['limit'])) {
        $where .= " {$filter['limit']}";
      }
    }
    return $this->db->query("SELECT sr.*,
      CASE 
        WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL THEN sr.no_mesin
        WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL THEN sa_sebelumnya.id_customer
        ELSE ch_sr.id_customer
      END AS id_customer,
      CASE 
        WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL 
        THEN (SELECT nama_konsumen FROM tr_sales_order so
              JOIN tr_spk spk ON spk.no_spk=so.no_spk
              WHERE so.no_mesin=sr.no_mesin
            )
        WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL THEN ch23.nama_customer
        ELSE ch_sr.nama_customer
      END AS nama_customer,
      CASE 
        WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL 
        THEN (SELECT no_hp FROM tr_sales_order so
              JOIN tr_spk spk ON spk.no_spk=so.no_spk
              WHERE so.no_mesin=sr.no_mesin
            )
        WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL THEN ch23.no_hp
        ELSE ch_sr.no_hp
      END AS no_hp,
      CASE 
        WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL 
        THEN (SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail 
              WHERE no_mesin=sr.no_mesin ORDER BY id DESC LIMIT 1)
        WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL THEN ch23.no_hp
        ELSE ch_sr.no_polisi
      END AS no_polisi,
      CASE 
        WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL 
        THEN (SELECT tipe_ahm FROM tr_sales_order so
              JOIN tr_spk spk ON spk.no_spk=so.no_spk
              JOIN ms_tipe_kendaraan tk_h1 ON tk_h1.id_tipe_kendaraan=spk.id_tipe_kendaraan
              WHERE so.no_mesin=sr.no_mesin
            )
        WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL 
        THEN(SELECT tipe_ahm FROM ms_tipe_kendaraan tk_h23
              WHERE id_tipe_kendaraan=ch23.id_tipe_kendaraan
            )
        ELSE (SELECT tipe_ahm FROM ms_tipe_kendaraan tk_h23
              WHERE id_tipe_kendaraan=ch_sr.id_tipe_kendaraan
            )
      END AS tipe_ahm,
      CASE 
        WHEN sa_sebelumnya.id_customer IS NULL AND ch_sr.id_customer IS NULL 
        THEN (SELECT warna FROM tr_sales_order so
              JOIN tr_spk spk ON spk.no_spk=so.no_spk
              JOIN ms_warna  ON ms_warna.id_warna=spk.id_warna
              WHERE so.no_mesin=sr.no_mesin
            )
        WHEN ch_sr.id_customer IS NULL AND sr.no_mesin IS NULL 
        THEN(SELECT warna FROM ms_warna
              WHERE id_warna=ch23.id_warna
            )
        ELSE (SELECT warna FROM ms_warna
              WHERE id_warna=ch_sr.id_warna
            )
      END AS warna,
      CASE 
        WHEN sa_sebelumnya.id_sa_form IS NULL THEN ''
        ELSE sa_sebelumnya.tgl_servis
      END AS tgl_servis_sebelumnya,
      CASE 
        WHEN sa_sebelumnya.id_sa_form IS NULL THEN ''
        ELSE (SELECT GROUP_CONCAT(id_type)  FROM tr_h2_wo_dealer_pekerjaan wdp
              JOIN ms_h2_jasa mj ON mj.id_jasa = wdp.id_jasa
              WHERE wdp.id_work_order=wo_before.id_work_order GROUP BY id_work_order) 
      END AS tipe_servis_sebelumnya,nama_dealer,dl.alamat AS alamat_dealer,contact_booking_service
      FROM tr_h2_service_reminder AS sr
      LEFT JOIN ms_customer_h23 ch_sr ON ch_sr.id_customer=sr.id_customer
      LEFT JOIN tr_h2_wo_dealer AS wo_before ON wo_before.id_work_order=sr.id_work_order_sebelumnya
      LEFT JOIN tr_h2_sa_form sa_sebelumnya ON sa_sebelumnya.id_sa_form=wo_before.id_sa_form
      LEFT JOIN ms_customer_h23 AS ch23 ON ch23.id_customer=sa_sebelumnya.id_customer
      JOIN ms_dealer dl ON dl.id_dealer=sr.id_dealer
      $where
    ");
  }

  function getHistoryReminder($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if ($filter['id_service_reminder'] != '') {
        $where .= " AND id_service_reminder = '{$filter['id_service_reminder']}'";
      }
    }
    return $this->db->query("SELECT *
    FROM tr_h2_service_reminder_history AS srh
    $where ");
  }
  function rescheduleServiceReminder($id_service_reminder)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $batas = $this->db->get_where('ms_h2_batas_waktu', ['id_dealer' => $id_dealer]);
    if ($batas->num_rows() > 0) {
      $batas = $batas->row()->ulang_service_reminder;
      $filter = ['id_service_reminder' => $id_service_reminder];
      $history_reminder = $this->getHistoryReminder($filter)->num_rows();
      if ($history_reminder < ($batas - 1)) {
        return 1;
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  }
}
