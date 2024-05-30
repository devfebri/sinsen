<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Claim extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_h1_dealer_claim', 'm_claim');
    $this->load->model('M_h1_md_sales_program', 'm_sp');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }


  function approve()
  {
    $post = $this->input->get();
    $mdt = [
      'claim_id' => 'requided'
    ];
    cek_mandatory($mdt, $post);
    $this->db->trans_begin();

    // Update Claim
    $explode = explode(';', $post['claim_id']);
    foreach ($explode as $exp) {
      $f_claim = [
        'id_claim_int' => $exp,
        'status_proposal' => 'draft',
        'id_dealer' => $this->login->id_dealer,
      ];
      $get_claim = $this->m_claim->getClaim($f_claim);
      if ($get_claim->num_rows() == 0) {
        $msg = ['Claim ID tidak ditemukan atau selesai di proses'];
        send_json(msg_sc_error($msg));
      } else {
        $cl = $get_claim->row();
      }
      $update = [
        'tgl_approve_reject_md' => waktu_full(),
        'status_proposal'       => 'submitted',
        'tgl_ajukan_claim'      => tanggal(),
        'status'                => 'ajukan',
      ];
      $cond = [
        'id_sales_order' => $cl->id_sales_order,
        'id_program_md' => $cl->id_program_md,
      ];
      $this->db->update('tr_claim_dealer', $update, $cond);

      //Buat Notifikasi
      $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['kode_notif' => 'approve_klaim_prop'])->row();
      $notif = [
        'id_notif_kat' => $ktg_notif->id_notif_kat,
        'id_referensi' => $cl->id_sales_order,
        'judul'        => "Approved Klaim Proposal",
        'pesan'        => "Telah dilakukan approve untuk klaim proposal (ID Sales Order =$cl->id_sales_order)",
        'link'         => $ktg_notif->link . '/?id=' . $cl->id_sales_order . '&ip_md=' . $cl->id_program_md,
        'status'       => 'baru',
        'id_dealer'    => $this->login->id_dealer,
        'created_at'   => waktu_full(),
        'created_by'   => $this->login->id_user
      ];
      $this->db->insert('tr_notifikasi', $notif);
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = [count($exp) . ' Claim has been approved'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }
  function list_approval()
  {
    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'status_proposal' => 'draft',
      'page' => $this->input->get('page')
    ];

    $result = $this->m_claim->getClaim($f_claim);
    // send_json($result->result());
    $new_res = [];
    foreach ($result->result() as $rs) {
      // send_json($rs);
      $f_sp = [
        'id_program_md' => $rs->id_program_md
      ];
      $sp = $this->m_sp->getSalesProgram($f_sp)->row();

      $f_tk = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan
      ];
      $tk = $this->m_unit->getTipeKendaraan($f_tk)->row();

      $new_res[] = [
        'id' => (int)$rs->id_claim_int,
        'code_spk' => $rs->no_spk,
        'date' => $rs->tgl_so,
        'sales_program' => $sp->judul_kegiatan,
        'nominal' => $rs->voucher,
        'expired_start' => $sp->periode_awal,
        'expired_end' => $sp->periode_akhir,
        'customer_name' => $rs->nama_konsumen,
        'product' => $tk->tipe_ahm . ' (' . $tk->id_tipe_kendaraan . ' - ' . $tk->deskripsi_ahm . ' ' . $tk->kategori . ')'
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
  }
  function list_tracking()
  {
    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'page' => $this->input->get('page')
    ];

    $result = $this->m_claim->getClaim($f_claim);
    // send_json($result->result());
    $new_res = [];
    foreach ($result->result() as $rs) {
      // send_json($rs);
      $f_sp = [
        'id_program_md' => $rs->id_program_md
      ];
      $sp = $this->m_sp->getSalesProgram($f_sp)->row();

      $f_tk = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan
      ];
      $tk = $this->m_unit->getTipeKendaraan($f_tk)->row();

      $new_res[] = [
        'id' => (int)$rs->id_claim_int,
        'code_spk' => $rs->no_spk,
        'date' => $rs->tgl_so,
        'sales_program' => $sp->judul_kegiatan,
        'nominal' => $rs->voucher,
        'expired_start' => $sp->periode_awal,
        'expired_end' => $sp->periode_akhir,
        'customer_name' => $rs->nama_konsumen,
        'product' => $tk->tipe_ahm . ' (' . $tk->id_tipe_kendaraan . ' - ' . $tk->deskripsi_ahm . ' ' . $tk->kategori . ')',
        'status' => $rs->status_proposal
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
  }

  function index()
  {
    $f_spk = [
      'id_dealer' => $this->login->id_dealer,
      'have_program' => true,
      'join_claim_program' => true,
      'status_proposal_not' => 'completed_by_md',
      'select' => 'sum_voucher',
      'tahun_bulan' => get_ym()
    ];
    $in_progress_claim = (int)$this->m_spk->getSPKIndividu($f_spk)->row()->tot_voucher;

    $f_spk = [
      'id_dealer' => $this->login->id_dealer,
      'have_program' => true,
      'join_claim_program' => true,
      'status_proposal' => 'completed_by_md',
      'select' => 'average_payment_claim'
    ];
    $payment_avg = (int)$this->m_spk->getSPKIndividu($f_spk)->row()->total;
    $time_avg = (int)$this->m_claim->getClaimTimeAverage($this->login->id_dealer) . ' Hari';

    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'status_proposal' => 'draft',
    ];

    $re_claim = $this->m_claim->getClaim($f_claim);
    $item = [];
    foreach ($re_claim->result() as $rs) {
      // send_json($rs);
      $f_sp = [
        'id_program_md' => $rs->id_program_md
      ];
      $sp = $this->m_sp->getSalesProgram($f_sp)->row();

      $f_tk = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan
      ];
      $tk = $this->m_unit->getTipeKendaraan($f_tk)->row();

      $item[] = [
        'id' => (int)$rs->id_claim_int,
        'code_spk' => $rs->no_spk,
        'date' => $rs->tgl_so,
        'sales_program' => $sp->judul_kegiatan,
        'nominal' => (int)$rs->voucher,
        'expired_start' => $sp->periode_awal,
        'expired_end' => $sp->periode_akhir,
        'customer_name' => $rs->nama_konsumen,
        'product' => $tk->tipe_ahm . ' (' . $tk->id_tipe_kendaraan . ' - ' . $tk->deskripsi_ahm . ' ' . $tk->kategori . ')'
      ];
    }

    $waiting_approval = count($item);
    $result = [
      'in_progress_claim' => $in_progress_claim,
      'payment_avg' => $payment_avg,
      'time_avg' => $time_avg,
      'waiting_approval' => $waiting_approval,
      'item' => $item
    ];
    send_json(msg_sc_success($result));
  }

  function reject()
  {
    $post = $this->input->post();
    $mdt = [
      'claim_id' => 'requided'
    ];
    cek_mandatory($mdt, $post);
    $this->db->trans_begin();

    // Update Claim
    $explode = explode(';', $post['claim_id']);
    foreach ($explode as $exp) {
      $f_claim = [
        'id_claim_int' => $exp,
        'status_proposal' => 'draft',
        'id_dealer' => $this->login->id_dealer,
      ];
      $get_claim = $this->m_claim->getClaim($f_claim);
      if ($get_claim->num_rows() == 0) {
        $msg = ['Claim ID tidak ditemukan atau selesai di proses'];
        send_json(msg_sc_error($msg));
      } else {
        $cl = $get_claim->row();
      }
      $alasan_reject = 'reject';
      $update = [
        'tgl_approve_reject_md' => NULL,
        'status_proposal'       => NULL,
        'tgl_ajukan_claim'      => NULL,
        'status'                => '',
        'alasan_reject'         => $alasan_reject,
      ];
      $cond = [
        'id_sales_order' => $cl->id_sales_order,
        'id_program_md' => $cl->id_program_md,
      ];
      $this->db->update('tr_claim_dealer', $update, $cond);

      //Buat Notifikasi
      $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['kode_notif' => 'rjct_klaim_prop'])->row();
      $notif = [
        'id_notif_kat' => $ktg_notif->id_notif_kat,
        'id_referensi' => $cl->id_sales_order,
        'judul'        => "Reject Klaim Proposal",
        'pesan'        => "Telah dilakukan reject untuk klaim proposal (ID Sales Order=$cl->id_sales_order) dengan alasan $alasan_reject ",
        'link'         => $ktg_notif->link . '?id=' . $cl->id_sales_order . '&ip_md=' . $cl->id_program_md,
        'status'       => 'baru',
        'id_dealer'    => $this->login->id_dealer,
        'created_at'   => waktu_full(),
        'created_by'   => $this->login->id_user
      ];
      $this->db->insert('tr_notifikasi', $notif);
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = [count($exp) . ' Claim has been rejected'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }
}
