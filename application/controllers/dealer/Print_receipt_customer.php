<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Print_receipt_customer extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "print_receipt_customer";
  var $title  = "Print Receipt Customer";

  public function __construct()
  {
    parent::__construct();
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_master', 'm_h2');
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    $this->load->model('m_h2_billing', 'm_bil');
    $this->load->model('m_h2_print_receipt', 'm_prc');
    $this->load->model('m_h2_work_order', 'm_wo');


    //===== Load Library =====
    $this->load->library('upload');
    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $page = $this->page;
      if (isset($data['mode'])) {
        if ($data['mode'] == 'detail_wo') {
          $page = 'sa_form';
        }
        if ($data['mode'] == 'detail_njb') {
          $page = 'njb';
        }
        if ($data['mode'] == 'detail_nsc') {
          $page = 'nsc';
        }
      }
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";

    // $data['result']    = $this->m_bil->get_njb_nsc_print()->result();
    // send_json($data);
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $button = '';
      $btn_kwitansi = '<a style="margin-bottom:1px" href="dealer/print_receipt_customer/create_kwitansi?id=' . $rs->id_referensi . '&ref=' . preg_replace('/\s/', '', $rs->referensi) . '" class="btn btn-info btn-xs btn-flat"><b>Create Kwitansi</b></a> ';
      $btn_detail = '<a style="margin-bottom:1px" href="dealer/print_receipt_customer/detail_kwitansi?id=' . $rs->id_referensi . '&ref=' . preg_replace('/\s/', '', $rs->referensi) . '" class="btn btn-success btn-xs btn-flat"><b>Detail Kwitansi</b></a> ';
      if ($rs->sisa > 0) {
        if (can_access($this->page, 'can_update'))  $button  .= $btn_kwitansi;
      }
      if (round($rs->sisa) != round($rs->total_bayar)) {
        $button .= $btn_detail;
      }
      $sub_array[] = $rs->referensi;
      $sub_array[] = $rs->id_referensi;
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail_njb?id=' . $rs->no_njb . '">' . $rs->no_njb . '</a>';
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail_nsc?id=' . $rs->no_nsc . '">' . $rs->no_nsc . '</a>';
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->total_bayar);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->dibayar);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->sisa);
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'order_column' => 'print_receipt',
      'search' => $this->input->post('search')['value'],
      'sisa_lebih_besar'  => isset($_POST['sisa_lebih_besar']) ? $_POST['sisa_lebih_besar'] : '',
      'sisa_0'  => isset($_POST['sisa_0']) ? $_POST['sisa_0'] : '',
      'select' => 'print_njb_nsc'
    ];
    if ($recordsFiltered == true) {
      return $this->m_bil->get_njb_nsc_print($filter)->num_rows();
    } else {
      return $this->m_bil->get_njb_nsc_print($filter)->result();
    }
  }

  public function history()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'History ' . $this->title;
    $data['set']   = "history_tes";
    $this->template($data);
  }

  public function create_kwitansi()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'create_kwitansi';
    $data['set']   = "form";
    $id            = $this->input->get('id');
    $ref           = $this->input->get('ref');
    //Cek WO
    $filter['id_work_order'] = $id;
    $wo = $this->m_wo->get_sa_form(($filter));
    if ($wo->num_rows() > 0) {
      $row = $wo->row();
      $filter_detail['id_work_order'] = $row->id_work_order;
      $filter_sudah_bayar['id_referensi'] = $row->id_work_order;
    } else {
      $filter_detail['nomor_so'] = $id;
      // send_json($filter_detail);
      $nsc = $this->m_bil->getNSC($filter_detail);
      if ($nsc->num_rows() > 0) {
        $row = $nsc->row();
        $filter_detail['no_nsc'] = $row->no_nsc;
        $filter_sudah_bayar['id_referensi'] = $row->id_referensi;
        // send_json($filter_detail);
      }
    }
    if (isset($row)) {
      $data['riwayat_bayar'] = $this->m_bil->getTransaksiSudahDibayar($filter_sudah_bayar);
      $data['detail_transaksi'] = $this->m_lap->getDetailTransaksiCustomer($filter_detail);
      $data['row'] = $row;
      if (isset($_GET['cek'])) {
        send_json($data);
      }
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt_customer'>";
    }
  }

  public function detail_wo()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail Work Order';
    $data['mode']  = 'detail_wo';
    $data['set']   = "form";
    $id_work_order    = $this->input->get('id');

    $filter['id_work_order'] = $id_work_order;
    $sa_form = $this->m_wo->get_sa_form($filter);
    if ($sa_form->num_rows() > 0) {
      $row                     = $data['row_wo'] = $sa_form->row();
      $data['tipe_coming']     = explode(',', $row->tipe_coming);
      $data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
      // $filter['id_work_order'] = $id_work_order;
      // $data['details']         = $this->m_h2->wo_detail($filter);
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt_customer'>";
    }
  }

  public function detail_njb()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail NJB';
    $data['mode']  = 'detail_njb';
    $data['set']   = "form";
    $no_njb = $this->input->get('id');

    $filter = ['no_njb' => $no_njb];
    $get_wo = $this->m_wo->get_sa_form($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      $data['pkp'] = $row->pkp_njb;
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt_customer'>";
    }
  }

  public function detail_nsc()
  {
    $this->load->model('m_h23_nsc', 'm_nsc');

    $data['isi']   = $this->page;
    $data['title'] = 'Detail NSC';
    $data['mode']  = 'detail_nsc';
    $data['set']   = "form";
    $no_nsc = $this->input->get('id');

    $filter = ['no_nsc' => $no_nsc];
    $get_nsc = $this->m_bil->getNSC($filter);

    if ($get_nsc->num_rows() > 0) {
      $nsc = $get_nsc->row();
      $filter = ['id_work_order' => $nsc->id_referensi];
      $wo = $this->m_wo->get_sa_form($filter);
      if ($wo->num_rows() > 0) {
        $wo = $wo->row();
        $nsc->tgl_servis         = $wo->tgl_servis;
        $nsc->id_karyawan_dealer = $wo->id_karyawan_dealer;
        $nsc->nama_lengkap       = $wo->nama_lengkap;
        $nsc->kd_dealer_so       = $wo->kode_dealer_md;
        $nsc->dealer_so          = $wo->nama_dealer;
        $nsc->tipe_ahm           = $wo->tipe_ahm;
        $nsc->no_polisi          = $wo->no_polisi;
      } else {
        $so = $this->m_nsc->getSOH3($nsc->id_referensi);
        $nsc->dealer_so       = $so->nama_dealer;
        $nsc->kd_dealer_so       = $so->kode_dealer_md;
        $nsc->nama_lengkap       = $so->nama_lengkap;
      }
      $filter = ['no_nsc' => $nsc->no_nsc];
      $nsc->parts = $this->m_bil->getNSCParts($filter)->result();
      $data['row'] = $nsc;
      $data['pkp'] = $nsc->pkp;
      $data['tampil_ppn'] = $nsc->tampil_ppn;

      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/nsc'>";
    }
  }

  public function detail_kwitansi()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $id            = $this->input->get('id');
    $row = $this->m_bil->getRefKwitansi($id);
    // send_json($row);
    if ($row['row'] != null) {
      $data['row'] = $row['row'];
      $data['riwayat_bayar'] = $this->m_bil->getTransaksiSudahDibayar($row['filter_sudah_bayar']);
      $data['kwitansi'] = $this->m_bil->getKwitansi($row['filter_sudah_bayar'])->result();
      $data['detail_transaksi'] = $this->m_lap->getDetailTransaksiCustomer($row['filter_detail']);
      if (isset($_GET['cek'])) {
        send_json($data);
      }
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt_customer'>";
    }
  }

  function one_kwitansi()
  {
    $id_receipt = $_GET['id'];
    $data['isi']   = $this->page;
    $data['title'] = 'Detail Kwitansi';
    $data['mode']   = "detail_kwitansi";
    $data['set']   = "form";
    $filter = ['id_receipt' => $id_receipt];
    $row = $this->m_bil->getKwitansi($filter);
    if ($row->num_rows() > 0) {
      $row = $row->row();
      $transaksi = $this->m_bil->getRefKwitansi($row->id_referensi);
      $transaksi['row']->id_receipt = $row->id_receipt;
      $transaksi['row']->tgl_receipt = $row->tgl_receipt;
      $transaksi['row']->kode_coa = $row->kode_coa;
      $transaksi['row']->coa = $row->coa;
      $data['row'] = $transaksi['row'];
      if ($row->referensi == 'wo') {
        $filter_sudah_bayar['id_work_order'] = $row->id_referensi;
      } else {
        $filter_sudah_bayar['nomor_so'] = $row->id_referensi;
      }
      $data['pembayarans'] = $this->m_bil->getKwitansiMetodeBayar($filter)->result();
      $data['riwayat_bayar'] = $this->m_bil->getTransaksiSudahDibayar($filter_sudah_bayar);
      $data['detail_transaksi']['details'] = $this->m_bil->getKwitansiTransaksi($filter)->result();
      if (isset($_GET['cek'])) {
        send_json($data);
      }
      $this->template($data);
    }
  }

  function save_kwitansi()
  {
    $waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_dealer  = $this->m_admin->cari_dealer();
    $id_receipt = $this->m_bil->get_id_receipt_customer();

    if (isset($post['id_work_order'])) {
      $referensi = 'wo';
      $id_referensi = $post['id_work_order'];
    } elseif (isset($post['nomor_so'])) {
      $referensi = 'part_sales';
      $id_referensi = $post['nomor_so'];
    }

    $nominal_lebih = isset($post['nominal_lebih']) ? $post['nominal_lebih'] : NULL;
    $nominal_lebih = $nominal_lebih > 0 ? $nominal_lebih : NULL;

    // tambahkan history pembayaran / akumulasi pembayaran dan jumlah pembayaran 1 kwitansi
    $total_bayar = 0;
    $insert = [
      'id_receipt' => $id_receipt,
      'id_dealer' => $id_dealer,
      'tgl_receipt' => $tanggal,
      'id_referensi' => $id_referensi,
      'referensi' => $referensi,
      'created_at' => $waktu,
      'created_by' => $login_id,
      'nominal_lebih'    => $nominal_lebih,
      'kode_coa'         => isset($post['kode_coa']) ? $post['kode_coa']                : NULL,
      'keterangan_lebih' => isset($post['keterangan_lebih']) ? $post['keterangan_lebih'] : NULL,
    ];

    $get_history_penerimaan = $this->db->query("
      select ifnull(sum(jumlah_pembayaran),0) as total from tr_h2_receipt_customer where id_referensi ='$id_referensi'
    ")->row()->total;

    $insert['history_pembayaran'] = $get_history_penerimaan;

    $no_nsc = '';
    foreach ($post['details']['details'] as $dt) {
      $ins_transaksi[] = [
        'id_receipt' => $id_receipt,
        'id_referensi' => $dt['id_referensi'],
        'tgl_transaksi' => date_ymd($dt['tgl_transaksi']),
        'nilai' => $dt['nilai'],
      ];

      if (strtolower(substr($dt['id_referensi'], 0, 3)) == 'nsc') {
        $no_nsc = $dt['id_referensi'];
      }
    }

    foreach ($post['pembayarans'] as $pr) {
      $ins_bayar[] = [
        'id_receipt' => $id_receipt,
        'no_rekening' => isset($pr['no_rekening']) ? $pr['no_rekening'] : null,
        'id_bank' => isset($pr['id_bank']) ? $pr['id_bank'] : null,
        'nominal' => $pr['nominal'],
        'tanggal' => $pr['tanggal'],
        'metode_bayar' => $pr['metode_bayar'],
        'uang_muka' => isset($pr['uang_muka']) ? $pr['uang_muka'] : 0,
        'no_inv_jaminan' => isset($pr['no_inv_jaminan']) ? $pr['no_inv_jaminan'] : NULL,
      ];

      $total_bayar+=$pr['nominal'];

      if ($pr['metode_bayar'] == 'uang_muka') {
        $cek_nsc = $this->db->query("SELECT SUM(uang_muka_terpakai) uang_muka_terpakai,total_bayar FROM tr_h2_uang_jaminan WHERE no_inv_uang_jaminan='{$pr['no_inv_jaminan']}' ");
        // if ($cek_nsc->num_rows() > 0) {
        $ns = $cek_nsc->row();
        $uang_muka_terpakai = (int)$ns->uang_muka_terpakai + (int)$pr['nominal'];
        $no_inv_jaminan = $pr['no_inv_jaminan'];
        if ($uang_muka_terpakai > $ns->total_bayar) {
          $rsp = [
            'status' => 'error',
            'pesan' => 'Nominal pembayaran melebihi Uang Jaminan. ID Uang Jaminan : ' . $pr['no_inv_jaminan']
          ];
          send_json($rsp);
        }
        $upd_uang_muka[] = [
          'uang_muka_terpakai' => $uang_muka_terpakai,
          'no_inv_uang_jaminan' => $no_inv_jaminan
        ];
        // } else {
        //   $rsp = [
        //     'status' => 'error',
        //     'pesan' => 'No. NSC untuk ID Uang Jaminan ' . $pr['no_inv_jaminan'], ' tidak ditemukan !'
        //   ];
        //   send_json($rsp);
        // }
      }
    }

    // Cek Booking Apakah Dari Customer App, Jika Iya Lakukan Update Status=3 (pemeriksaan akhir)
    $book = null;
    if (isset($post['id_work_order'])) {
      $book = $this->db->query("SELECT book.* 
    FROM tr_h2_manage_booking book 
    JOIN tr_h2_sa_form sa ON sa.id_booking=book.id_booking
    JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=sa.id_sa_form
    WHERE wo.id_work_order='{$post['id_work_order']}' AND IFNULL(customer_apps_booking_number,'')!=''
    ")->row();
    }

    $insert['jumlah_pembayaran'] = $total_bayar;

    $tes = [
      'insert' => $insert,
      'ins_bayar' => $ins_bayar,
      'ins_transaksi' => $ins_transaksi,
      'upd_uang_muka' => isset($upd_uang_muka) ? $upd_uang_muka : NULL
    ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_receipt_customer', $insert);
    $this->db->insert_batch('tr_h2_receipt_customer_metode', $ins_bayar);
    $this->db->insert_batch('tr_h2_receipt_customer_transaksi', $ins_transaksi);
    if (isset($upd_uang_muka)) {
      $this->db->update_batch('tr_h2_uang_jaminan', $upd_uang_muka, 'no_inv_uang_jaminan');
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      if ($book != null) {
        $this->load->library('mokita');
        foreach ($ins_bayar as $byr) {
          if ($byr['metode_bayar'] != 'uang_muka') {
            $array = [
              'AppsBookingNumber'   => $book->customer_apps_booking_number,
              'DmsBookingNumber'    => $book->id_booking,
              'Amount'              => $byr['nominal'],
              'Chanel'              => $byr['metode_bayar']
            ];
            $this->mokita->payment($array);
          }
        }
        $this->db->trans_commit();
      } else {
        $this->db->trans_commit();
      }
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/print_receipt_customer/cetak_kwitansi?id=' . $id_receipt)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }

  public function cetak_kwitansi()
  {
    $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $id_receipt = $this->input->get('id');

    $filter = ['id_receipt' => $id_receipt];
    $get = $this->m_bil->getKwitansi($filter);

    if ($get->num_rows() > 0) {
      $row = $get->row();
      // $upd = ['cetak_nsc_ke'=> $row->cetak_nsc_ke+1,
      //         'cetak_nsc_at'=> $waktu,
      //         'cetak_nsc_by'=> $login_id,
      //       ];
      // $this->db->update('tr_h2_wo_dealer',$upd,['no_njb'=>$no_njb]);

      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set'] = 'cetak_kwitansi';
      $transaksi = $this->m_bil->getRefKwitansi($row->id_referensi);
      $transaksi['row']->id_receipt = $row->id_receipt;
      $transaksi['row']->tgl_receipt = $row->tgl_receipt;
      $data['row'] = $transaksi['row'];

      $data['detail'] = $this->m_bil->getKwitansiTransaksi($filter)->result();
      $data['tot_trans'] = $this->m_bil->getKwitansiTransaksiTotal($filter);
      $data['metode'] = $this->m_bil->getKwitansiMetodeBayar($filter)->result();
      $filter['no_nsc'] = $data['row']->no_nsc;
      $data['tot_bayar'] = $this->m_bil->getTransaksiSudahDibayar($filter);
      $data['sisa'] = $data['tot_trans'] - $data['tot_bayar'];
      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_nsc.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/print_receipt_customer'>";
    }
  }


  public function fetch_history()
  {
    $fetch_data = $this->make_query_history();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $button = '';
      $btn_kwitansi = '<a style="margin-bottom:1px" href="dealer/print_receipt_customer/create_kwitansi?id=' . $rs->id_referensi . '&ref=' . preg_replace('/\s/', '', $rs->referensi) . '" class="btn btn-info btn-xs btn-flat"><b>Create Kwitansi</b></a> ';
      $btn_detail = '<a style="margin-bottom:1px" href="dealer/print_receipt_customer/detail_kwitansi?id=' . $rs->id_referensi . '&ref=' . preg_replace('/\s/', '', $rs->referensi) . '" class="btn btn-success btn-xs btn-flat"><b>Detail Kwitansi</b></a> ';
      if ($rs->sisa > 0) {
        if (can_access($this->page, 'can_update'))  $button  .= $btn_kwitansi;
      }
      if (round($rs->sisa) != round($rs->total_bayar)) {
        $button .= $btn_detail;
      }
      $sub_array[] = $rs->referensi;
      $sub_array[] = $rs->id_referensi;
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail_njb?id=' . $rs->no_njb . '">' . $rs->no_njb . '</a>';
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail_nsc?id=' . $rs->no_nsc . '">' . $rs->no_nsc . '</a>';
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->total_bayar);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->dibayar);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->sisa);
      $sub_array[] = $rs->last_tgl_kwitansi;
      $sub_array[] = $rs->last_id_kwitansi;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_history(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_history($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'order_column' => 'print_receipt',
      'search' => $this->input->post('search')['value'],
      'sisa_0'  => isset($_POST['sisa_0']) ? $_POST['sisa_0'] : '',
      'select' => 'print_njb_nsc',
      'select_add' => 'last_kwitansi'
    ];
    $tabs = $this->input->post('tabs');
    if ($recordsFiltered == true) {
      if ($tabs == 'wo_tab') {
        return $this->m_prc->getHistoryPrintReceiptWO($filter)->num_rows();
      } elseif ($tabs == 'part_sales') {
        return $this->m_prc->getHistoryPrintReceiptPartSales($filter)->num_rows();
      } else {
        return $this->m_bil->get_njb_nsc_print($filter)->num_rows();
      }
    } else {
      if ($tabs == 'wo_tab') {
        return $this->m_prc->getHistoryPrintReceiptWO($filter)->result();
      } elseif ($tabs == 'part_sales') {
        return $this->m_prc->getHistoryPrintReceiptPartSales($filter)->result();
      } else {
        return $this->m_bil->get_njb_nsc_print($filter)->result();
      }
    }
  }
}
