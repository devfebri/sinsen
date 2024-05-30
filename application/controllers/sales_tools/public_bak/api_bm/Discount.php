<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Discount extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_diskon', 'm_diskon');;
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');;
    $this->load->model('m_h1_dealer_spk', 'm_spk');;
    $this->load->model('m_sc_master', 'm_master');;
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $target = 0;
    $discount_sebelumnya = 0;
    $discount_bulan_ini = 0;
    $discount_rata_rata = ROUND(($discount_bulan_ini + $discount_sebelumnya) / 2);

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      // 'tahun_bulan' => get_ym(),
      'status' => 'Waiting Approval Disc'
    ];

    $re_sc = $this->m_diskon->getPengajuanDiskon($f_sc);
    $discount = [];
    foreach ($re_sc->result() as $rs) {
      $discount[] = [
        'id' => (int)$rs->id_pengajuan,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'discount_name' => $rs->discount_name ?: '',
        'discount_nominal' => (int)$rs->nominal_diskon,
        'payment' => $rs->payment,
      ];
    }
    $result = [
      'target' => $target,
      'discount_sebelumnya' => $discount_sebelumnya,
      'discount_bulan_ini' => $discount_bulan_ini,
      'discount_rata_rata' => $discount_rata_rata,
      'discount' => $discount,
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function approve()
  {
    $post = $this->input->post();
    $mdt = [
      'discount_id' => 'requided'
    ];
    cek_mandatory($mdt, $post);
    // send_json($post);
    $explode = explode(';', $post['discount_id']);
    foreach ($explode as $id) {
      $f_pgj = [
        'id_pengajuan' => $id,
        'id_dealer' => $this->login->id_dealer,
        'status' => 'Waiting Approval Disc'
      ];
      $pgj = $this->m_diskon->getPengajuanDiskon($f_pgj);
      cek_referensi($pgj, 'Discount ID');
      $pgj = $pgj->row();

      $update[] = [
        'id_pengajuan'           => $id,
        'jatah_approve_terpakai' => $pgj->jatah_approve_terpakai + 1,
        'status'                 => 'Approved Disc',
        'keterangan'             => $this->input->get('catatan') ?: '',
        'approved_at'            => waktu_full(),
        'approved_by'            => $this->login->id_user,
      ];
    }

    $this->db->trans_begin();
    $this->db->update_batch('tr_pengajuan_diskon', $update, 'id_pengajuan');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = [count($update) . ' Discount has been approved'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }
  function reject()
  {
    $post = $this->input->post();
    $mdt = [
      'discount_id' => 'requided'
    ];
    cek_mandatory($mdt, $post);
    // send_json($post);
    $explode = explode(';', $post['discount_id']);
    foreach ($explode as $id) {
      $f_pgj = [
        'id_pengajuan' => $id,
        'id_dealer' => $this->login->id_dealer,
        'status' => 'Waiting Approval Disc'
      ];
      $pgj = $this->m_diskon->getPengajuanDiskon($f_pgj);
      cek_referensi($pgj, 'Discount ID');
      $pgj = $pgj->row();

      $update[] = [
        'id_pengajuan'           => $id,
        'jatah_approve_terpakai' => $pgj->jatah_approve_terpakai + 1,
        'status'                 => 'Reject Disc',
        'keterangan'             => $this->input->get('catatan') ?: '',
        'approved_at'            => waktu_full(),
        'approved_by'            => $this->login->id_user,
      ];
    }

    $this->db->trans_begin();
    $this->db->update_batch('tr_pengajuan_diskon', $update, 'id_pengajuan');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = [count($update) . ' Discount has been rejected'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function history()
  {
    $get  = $this->input->get();
    $mandatory = ['page' => 'required'];
    cek_mandatory($mandatory, $get);

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'page' => $get['page'],
      'status_in' => "'Approved Disc','Reject Disc'"
    ];

    $re_sc = $this->m_diskon->getPengajuanDiskon($f_sc);
    $result = [];
    foreach ($re_sc->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_karyawan_dealer_int,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'discount_name' => $rs->discount_name ?: '',
        'discount_nominal' => (int)$rs->nominal_diskon,
        'payment' => $rs->payment,
        'keterangan' => $rs->keterangan,
        'tgl_konfirmasi' => $rs->tgl_konfirmasi,
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function request()
  {
    $get  = $this->input->get();
    $mandatory = ['page' => 'required'];
    cek_mandatory($mandatory, $get);

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'page' => $get['page'],
      'periode_pengajuan' => true,
      'start_date' => $this->input->get('date_start'),
      'end_date' => $this->input->get('date_end'),
      'status' => 'Waiting Approval Disc'
    ];

    $re_sc = $this->m_diskon->getPengajuanDiskon($f_sc);
    $result = [];
    foreach ($re_sc->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_pengajuan,
        'name' => $rs->nama_lengkap,
        'image' => image_karyawan($rs->image, $rs->jk),
        'discount_name' => $rs->discount_name ?: '',
        'discount_nominal' => (int)$rs->nominal_diskon,
        'payment' => $rs->payment,
        'keterangan' => $rs->keterangan,
        'tgl_konfirmasi' => (string)$rs->tgl_konfirmasi,
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function detail()
  {
    $this->load->model('M_h1_dealer_stok', 'm_stok');
    $get  = $this->input->get();
    $mandatory = ['discount_id' => 'required'];
    cek_mandatory($mandatory, $get);

    $f_sc = [
      'id_pengajuan' => $get['discount_id'],
      'id_dealer' => $this->login->id_dealer,
      'status' => 'Waiting Approval Disc'
    ];

    $row = $this->m_diskon->getPengajuanDiskon($f_sc);
    cek_referensi($row, 'Discount ID');
    $disc = $row->row();

    $f_prp = [
      'id_prospek' => $disc->id_prospek,
      'id_dealer' => $this->login->id_dealer,
    ];
    $prp = $this->m_prospek->getProspek($f_prp)->row();

    $f_kry = ['id_karyawan_dealer' => $prp->id_karyawan_dealer, 'select' => 'all'];
    $kry = $this->m_master->getKaryawan($f_kry)->row();
    // send_json($kry);
    $f_spk = [
      'id_customer' => $prp->id_customer,
      'id_dealer' => $this->login->id_dealer,
    ];
    $spk = $this->m_spk->getSPKIndividu($f_spk)->row();

    $filter_spk = [
      'id_tipe_kendaraan' => $spk->id_tipe_kendaraan,
      'id_dealer' => $this->login->id_dealer
    ];
    $stok = $this->m_stok->GetReadyStock($filter_spk);
    $sebelum = (object)[
      'down_payment' => $spk->dp_stor - ($spk->voucher_2 - $spk->diskon),
      'tenor' => (int)$spk->tenor,
      'angsuran_perbulan' => (int)$spk->angsuran,
    ];
    $sesudah = (object)[
      'down_payment' => (int)$spk->dp_stor,
      'tenor' => (int)$spk->tenor,
      'angsuran_perbulan' => (int)$spk->angsuran,
    ];

    $result = [
      'name' => $kry->nama_lengkap,
      'image' => image_karyawan($kry->image, $kry->jk),
      'request_total' => (int)$disc->jatah_approve_terpakai,
      'request_max' => (int)$disc->byk_jatah,
      'promo_id' => $spk->program_umum,
      'disc_reguler' => strtolower($spk->jenis_beli) == 'kredit' ? (int)$spk->voucher_2 : (int)$spk->voucher_1,
      'disc_tambahan' => (int)$disc->nominal_diskon,
      'date' => $disc->tgl_pengajuan,
      'customer_name' => $prp->nama_konsumen,
      'unit_name' => $spk->tipe_ahm,
      'unit_stock' => (int)$stok,
      'metode_pembayaran' => $spk->jenis_beli . ' - ' . $spk->finance_company,
      'catatan' => $disc->keterangan,
      'sebelum' => $sebelum,
      'sesudah' => $sesudah,
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
