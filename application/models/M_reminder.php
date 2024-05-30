<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_reminder extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_crm', 'm_crm');
  }

  function getWaktuReminder()
  {
    return $this->db->query("SELECT LEFT(contact_customer_service_via_sms,5) AS contact_customer_service_via_sms,LEFT(reminder_service_via_sms,5) AS reminder_service_via_sms,LEFT(service_concept_create_alert,5) service_concept_create_alert FROM ms_h23_waktu_reminder_auto_crm LIMIT 1")->row();
  }

  function getPesan($filter)
  {
    $ymd = tanggal();
    $where = "WHERE id_dealer='{$filter['id_dealer']}' ";
    if ($filter != null) {
      if ($filter['tipe_pesan'] != '') {
        $where .= " AND tipe_pesan = '{$filter['tipe_pesan']}'";
      }
      $where .= " AND '$ymd' BETWEEN start_date AND end_date";
    }
    return $this->db->query("SELECT id_pesan,id_dealer,tipe_pesan,konten,start_date,end_date FROM ms_pesan 
    $where
    ORDER BY created_at DESC LIMIT 1
    ");
  }
  function reminderServiceViaSMS()
  {
    $filter_reminder_sms['tgl_reminder_sms'] = tanggal();
    $reminder_sms = $this->m_crm->getServiceReminder($filter_reminder_sms)->result();

    foreach ($reminder_sms as $rm) {
      $filter = ['id_dealer' => $rm->id_dealer, 'tipe_pesan' => 'reminder_service'];
      $m_pesan = $this->m_rem->getPesan($filter);
      if ($m_pesan->num_rows() > 0) {
        $m_pesan = $m_pesan->row();
        $ref = [
          'NamaDealer'     => $rm->nama_dealer,
          'AlamatDealer'   => $rm->alamat_dealer,
          'NamaCustomer'   => $rm->nama_customer,
          'NextService'    => $rm->tipe_servis_berikutnya,
          'TglNextService' => $rm->tgl_servis_berikutnya,
          'ContactBooking' => $rm->contact_booking_service,
          'TipeUnit'       => $rm->tipe_ahm,
          'Warna'          => $rm->warna
        ];
        $params = ['ref' => $ref, 'konten' => $m_pesan->konten];
        $pesan = generate_pesan($params);
        $params = ['no_hp' => $rm->no_hp, 'pesan' => $pesan];
        $res_sms = zenziva_sms($params);
        // $res_sms['status'] = 1; //Sementara
        $status_reminder_sms = 'Tidak Terkirim';
        if ($res_sms['status'] == 1) $status_reminder_sms = 'Terkirim';
        $result_reminder[] = [
          'id_service_reminder' => $rm->id_service_reminder,
          'status_reminder_sms' => $status_reminder_sms,
          'updated_at' => waktu(),
        ];
      }
    }

    $this->db->trans_begin();
    if (isset($result_reminder)) {
      $this->db->update_batch('tr_h2_service_reminder', $result_reminder, 'id_service_reminder');
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan'  => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'reminder' => isset($result_reminder) ? $result_reminder : null
      ];
    }
    return $rsp;
  }

  function contactCustomerServiceViaSMS()
  {
    $filter_contact_sms['tgl_contact_sms'] = tanggal();

    $contact_sms = $this->m_crm->getServiceReminder($filter_contact_sms)->result();
    foreach ($contact_sms as $rm) {
      $filter = ['id_dealer' => $rm->id_dealer, 'tipe_pesan' => 'contact_customer_service'];
      $m_pesan = $this->m_rem->getPesan($filter);
      if ($m_pesan->num_rows() > 0) {
        $m_pesan = $m_pesan->row();

        $ref = [
          'NamaDealer'     => $rm->nama_dealer,
          'AlamatDealer'   => $rm->alamat_dealer,
          'NamaCustomer'   => $rm->nama_customer,
          'NextService'    => $rm->tipe_servis_berikutnya,
          'TglNextService' => $rm->tgl_servis_berikutnya,
          'ContactBooking' => $rm->contact_booking_service,
          'TipeUnit'       => $rm->tipe_ahm,
          'Warna'          => $rm->warna
        ];
        $params = ['ref' => $ref, 'konten' => $m_pesan->konten];
        $pesan = generate_pesan($params);
        $params = ['no_hp' => $rm->no_hp, 'pesan' => $pesan];
        $res_sms = zenziva_sms($params);
        // $res_sms['status'] = 1; //Sementara
        $status_contact_sms = 'Tidak Terkirim';
        if ($res_sms['status'] == 1) $status_contact_sms = 'Terkirim';
        $result_contact_sms[] = [
          'id_service_reminder' => $rm->id_service_reminder,
          'status_contact_sms' => $status_contact_sms,
          'updated_at' => waktu(),
        ];
      }
      $notif[] = [
        'id_dealer' => $rm->id_dealer,
        'id_customer' => $rm->id_customer,
        'tgl_contact_call' => $rm->tgl_contact_call
      ];
    }
    $this->db->trans_begin();
    if (isset($result_contact_sms)) {
      $this->db->update_batch('tr_h2_service_reminder', $result_contact_sms, 'id_service_reminder');
    }
    if (isset($notif)) {
      $this->load->model('notifikasi_model', 'notifikasi');
      foreach ($notif as $nt) {
        $id_notif_kat = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_service_reminder')->get()->row()->id_notif_kat;
        $this->notifikasi->insert([
          'id_notif_kat' => $id_notif_kat,
          'judul'        => 'Service Reminder Customer',
          'pesan'        => "Terdapat reminder service yang perlu dilakukan contact via call pada tanggal : {$nt['tgl_contact_call']} untuk ID Customer : {$nt['id_customer']}",
          'link'         => "dealer/service_reminder_schedule",
          'id_referensi' => $nt['id_customer'],
          'id_dealer'    => $nt['id_dealer'],
          'show_popup'   => 1,
        ]);
      }
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan'  => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'reminder' => isset($result_contact_sms) ? $result_contact_sms : null

      ];
    }
    return $rsp;
  }

  function createAlertServiceConceptServiceCoordinator()
  {
    $this->load->model('M_sc_activity', 'm_act');
    $f = [
      'group_by_id_kry_id_kategori' => true,
      'select' => 'count_activity',
      'commited_date_null' => true,
      'tanggal_lebih_kecil' => tanggal()
    ];
    $ins_alert = [];
    $cek_act = $this->m_act->getActivity($f);
    foreach ($cek_act->result() as $rs) {
      $ins_alert[] = [
        'id_karyawan_int_sales_person' => $rs->id_karyawan_dealer_int,
        'kategori' => $rs->nama_kategori,
        'id_dealer' => $rs->id_dealer,
        'title' => $rs->nama_kategori,
        'info' => $rs->total . ' ' . $rs->nama_kategori . ' belum ditindak lanjuti',
        'created_at' => waktu_full(),
        'created_by' => 0
      ];
    }
    $this->db->trans_begin();
    if (count($ins_alert) > 0) {
      $this->db->insert_batch('tr_sc_alert', $ins_alert);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => count($ins_alert) . ' alert sukses',
      ];
    }
    return $rsp;
  }
}
