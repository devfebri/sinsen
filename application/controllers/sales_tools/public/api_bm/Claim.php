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
    $post = $this->input->post();
    $mdt = [
      'claim_id' => 'required'
    ];
    cek_mandatory($mdt, $post);
    $this->db->trans_begin();

    // Update Claim
    $explode = explode(';', $post['claim_id']);
    foreach ($explode as $exp) {
      $f_claim = [
        'id_claim_int' => $exp,
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
        'updated_at'            => waktu_full(),
        'updated_by'            => $this->login->id_user,
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
      $msg = [count($explode) . ' Claim has been approved'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function list_approval()
  {
    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'status_proposal' => "draft",
      'order' => ['field' => 'cld.created_at', 'sort' => 'DESC'],
      'page' => $this->input->get('page'),
      'periode' => true,
    ];

    $result = $this->m_claim->getOnlyClaim($f_claim);
    // send_json($result->result());
    $new_res = [];
    foreach ($result->result() as $rs) {
      $new_res[] = [
        'id'            => (int)$rs->id_claim_int,
        'code_spk'      => $rs->no_spk,
        'date'          => $rs->tanggal,
        'sales_program' => $rs->judul_kegiatan,
        'nominal'       => $rs->voucher,
        'expired_start' => $rs->periode_awal,
        'expired_end'   => $rs->periode_akhir,
        'customer_name' => $rs->nama_konsumen,
        'product'       => $rs->tipe_motor,
        'status'        => $rs->status_claim
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
  }

  function list_tracking()
  {
    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'page' => $this->input->get('page'),
      'order' => ['field' => 'tgl_approve_reject_md', 'sort' => 'DESC'],
      'status_in' => "'rejected','approved','ajukan'"
    ];

    $result = $this->m_claim->getOnlyClaim($f_claim);
    $new_res = [];
    foreach ($result->result() as $rs) {
      $new_res[] = [
        'id'            => (int)$rs->id_claim_int,
        'code_spk'      => $rs->no_spk,
        'date'          => $rs->tanggal,
        'sales_program' => $rs->judul_kegiatan,
        'nominal'       => $rs->voucher,
        'expired_start' => $rs->periode_awal,
        'expired_end'   => $rs->periode_akhir,
        'customer_name' => $rs->nama_konsumen,
        'product'       => $rs->tipe_motor,
        'status'        => $rs->status_claim
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
  }

  function index()
  {
    $id_dealer = $this->login->id_dealer;

    $in_progress_claim = (int)$this->m_claim->getInprogressClaim($id_dealer);

    $time_avg = (int)$this->m_claim->getClaimTimeAverage($this->login->id_dealer) . ' Hari';

    // $f_claim = [
    //   'id_dealer' => $this->login->id_dealer,
    //   'status_proposal' => 'draft',
    // ];

    // $re_claim = $this->m_claim->getClaim($f_claim);
    $f_claim = [
      'id_dealer'       => $this->login->id_dealer,
      'status_proposal' => "draft",
      'order' => ['field' => 'cld.created_at', 'sort' => 'DESC'],
      'LIMIT' => "LIMIT 5",
      'periode' => true,
    ];

    $re_claim = $this->m_claim->getOnlyClaim($f_claim);
    $item = [];
    foreach ($re_claim->result() as $rs) {
      $item[] = [
        'id'            => (int)$rs->id_claim_int,
        'code_spk'      => $rs->no_spk,
        'date'          => $rs->tanggal,
        'sales_program' => $rs->judul_kegiatan,
        'nominal'       => $rs->voucher,
        'expired_start' => $rs->periode_awal,
        'expired_end'   => $rs->periode_akhir,
        'customer_name' => $rs->nama_konsumen,
        'product'       => $rs->tipe_motor,
        'status'        => $rs->status_claim
      ];
    }

    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'status_in' => "'approved'",
      'order' => ['field' => 'cld.created_at', 'sort' => 'DESC'],
      'select' => 'sum',
      'periode' => true,

    ];

    // $payment_avg = $this->m_claim->getOnlyClaim($f_claim)->row()->jml;

    $f_claim = [
      'id_dealer' => $this->login->id_dealer,
      'status_in' => "'ajukan'",
      'order' => ['field' => 'cld.created_at', 'sort' => 'DESC'],
      'select' => 'count',
      'periode' => true,
    ];

    $waiting_approval = $this->m_claim->getOnlyClaim($f_claim)->row()->c;
    $result = [
      'persentase_pendapatan_penjualan' => 0,
      'in_progress_claim'               => $in_progress_claim,
      'payment_avg'                     => 0,
      'time_avg'                        => $time_avg,
      'waiting_approval'                => $waiting_approval,
      'item'                            => $item
    ];
    send_json(msg_sc_success($result));
  }

  function reject()
  {
    $post = $this->input->post();
    $mdt = [
      'claim_id' => 'required'
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
        'tgl_approve_reject_md' => waktu_full(),
        'status_proposal'       => 'completed_by_md',
        'status'                => 'rejected',
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
      $msg = [count($explode) . ' Claim has been rejected'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }
}
