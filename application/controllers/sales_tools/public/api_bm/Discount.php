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
    $this->load->model('m_dms');
    $this->login = middleWareAPI();
  }

  function index()
  {
    $f_d = [
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
    ];
    $tot_target = $this->m_dms->getTargetDiskonAmount($f_d);


    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => bulan_kemarin(get_ym() . '-01'),
      'status' => 'Approved Disc',
      'select' => 'sum_nominal'
    ];

    $discount_sebelumnya = (float)$this->m_diskon->getPengajuanDiskon($f_sc)->row()->sum_nominal;


    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'status' => 'Approved Disc',
      'select' => 'sum_nominal_count_data'
    ];

    $dbi  = $this->m_diskon->getPengajuanDiskon($f_sc)->row();
    $discount_bulan_ini = (float)$dbi->sum_nominal;
    $target = @($discount_bulan_ini / $tot_target) * 100;

    $discount_rata_rata = ROUND(@($discount_bulan_ini / $dbi->count));

    $f_sc = [
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'status' => 'Waiting Approval Disc'
    ];

    $re_sc = $this->m_diskon->getPengajuanDiskon($f_sc);
    $discount = [];
    foreach ($re_sc->result() as $rs) {
      $discount[] = [
        'id' => (int)$rs->id_pengajuan,
        'name' => (string)$rs->nama_konsumen,
        'image' => image_karyawan($rs->image, $rs->jk),
        'discount_name' => $rs->discount_name ?: 'Diskon Dealer',
        'discount_nominal' => (int)$rs->nominal_diskon,
        'payment' => $rs->payment,
      ];
    }
    $result = [
      'target' => (int)$target,
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

      if ($pgj->no_spk != NULL) {
        $upd_spk[] = [
          'no_spk'     => $pgj->no_spk,
          'diskon'     => $pgj->nominal_diskon,
          'updated_at' => waktu_full(),
          'updated_by' => $this->login->id_user,
        ];
      }

      $update[] = [
        'id_pengajuan'           => $id,
        'jatah_approve_terpakai' => $pgj->jatah_approve_terpakai + 1,
        'status'                 => 'Approved Disc',
        'catatan_approval'       => $this->input->post('catatan') ?: '',
        'approved_at'            => waktu_full(),
        'approved_by'            => $this->login->id_user,
      ];
    }

    $this->db->trans_begin();
    $this->db->update_batch('tr_pengajuan_diskon', $update, 'id_pengajuan');
    if (isset($upd_spk)) {
      $this->db->update_batch('tr_spk', $upd_spk, 'no_spk');
    }
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
      if ($pgj->no_spk != NULL) {
        $upd_spk[] = [
          'no_spk'     => $pgj->no_spk,
          'diskon'     => 0,
          'updated_at' => waktu_full(),
          'updated_by' => $this->login->id_user,
        ];
      }
      $update[] = [
        'id_pengajuan'           => $id,
        'jatah_approve_terpakai' => $pgj->jatah_approve_terpakai + 1,
        'status'                 => 'Reject Disc',
        'catatan_approval'             => $post['catatan'] ?: '',
        'approved_at'            => waktu_full(),
        'approved_by'            => $this->login->id_user,
      ];
    }

    $this->db->trans_begin();
    $this->db->update_batch('tr_pengajuan_diskon', $update, 'id_pengajuan');
    if (isset($upd_spk)) {
      $this->db->update_batch('tr_spk', $upd_spk, 'no_spk');
    }
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
        'date' => $rs->tgl_konfirmasi,
        'status' => $rs->status_disc
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
        'id_prospek' => $rs->id_prospek,
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
    // send_json($prp);
    $f_spk = [
      'id_customer' => $prp->id_customer,
      'id_dealer' => $this->login->id_dealer,
    ];
    $spk = $this->m_spk->getSPKIndividu($f_spk)->row();
    // send_json($spk);
    $filter_spk = [
      'id_tipe_kendaraan' => $spk->id_tipe_kendaraan,
      'id_dealer' => $this->login->id_dealer
    ];
    $stok = $this->m_stok->GetReadyStock($filter_spk);
    $sebelum = (object)[
      'down_payment' => (int)$spk->dp_stor,
      'tenor' => (int)$spk->tenor,
      'angsuran perbulan' => (int)$spk->angsuran,
    ];
    $sesudah = (object)[
      'down_payment' => $spk->dp_stor,
      'tenor' => (int)$spk->tenor,
      'angsuran perbulan' => (int)$spk->angsuran,
    ];

    $result = [
      'no_spk' => $spk->no_spk,
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
      'catatan' => $disc->catatan_approval,
      'sebelum' => $sebelum,
      'sesudah' => $sesudah,
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
