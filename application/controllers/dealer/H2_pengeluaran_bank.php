<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_pengeluaran_bank extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h2_pengeluaran_bank";
  var $title  = "Pengeluaran Bank";

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
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h2_finance', 'm_fin');


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
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="dealer/' . $this->page . '/edit?id=' . $rs->no_voucher . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $btn_ubah_stat = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/ubah_status?id=' . $rs->no_voucher . '" class="btn btn-primary btn-xs btn-flat">Ubah Status</a>';
      $btn_cetak = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/cetak?id=' . $rs->no_voucher . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';

      $status = '';
      if ($rs->status == 'draft' || $rs->status == NULL) {
        $status .= "<label class='label label-info'>Draft</label>";
        if (can_access($this->page, 'can_update')) $button .= $btn_edit;
        if (can_access($this->page, 'can_approval')) $button .= $btn_ubah_stat;
      } elseif ($rs->status == 'batal') {
        $status .= "<label class='label label-danger'>Batal</label>";
      } elseif ($rs->status == 'approved') {
        $button .= $btn_cetak;
        $status .= "<label class='label label-success'>Approved</label>";
      }
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail?id=' . $rs->no_voucher . '">' . $rs->no_voucher . '</a>';;
      $sub_array[] = date_dmy($rs->tgl_entry);
      $sub_array[] = $rs->kode_coa;
      $sub_array[] = $rs->coa;
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->tot_dibayar);
      $sub_array[] = $status;
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
      'search' => $this->input->post('search')['value'],
      'jenis_pengeluaran' => $this->input->post('jenis_pengeluaran'),
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getPengeluaranFinance($filter)->num_rows();
    } else {
      return $this->m_fin->getPengeluaranFinance($filter)->result();
    }
  }

  public function add()
  {

    // testing
    // page : 

    var_dump($data['isi']   = $this->page);
    die();
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    // send_json($data);
    $this->template($data);
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail ' . $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $no_voucher    = $this->input->get('id');

    $filter['no_voucher'] = $no_voucher;
    $result = $this->m_fin->getPengeluaranFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      $data['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }

  function save()
  {
    $post       = $this->input->post();
    $no_voucher = $this->m_fin->get_no_voucher();

    $insert = [
      'no_voucher'    => $no_voucher,
      'id_dealer'         => dealer()->id_dealer,
      'tgl_entry'         => tanggal(),
      'kode_coa'          => $post['kode_coa'],
      'tipe_customer'     => $post['tipe_customer'],
      'dibayar_kepada'    => $post['dibayar_kepada'] != '' ? $post['dibayar_kepada'] : 'dealer',
      'id_vendor'         => isset($post['id_vendor']) ? $post['id_vendor'] : null,
      'deskripsi'         => $post['deskripsi'],
      'rekening_tujuan'   => $post['rekening_tujuan'],
      'via_bayar'         => $post['via_bayar'],
      'jenis_pengeluaran' => 'bank',
      'created_at'        => waktu_full(),
      'created_by'        => user()->id_user,
    ];
    $tot_jml_dibayar = 0;
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'no_voucher' => $no_voucher,
        'kode_coa' => $pr['kode_coa'],
        'id_referensi' => $pr['id_referensi'],
        'sisa_hutang' => $pr['sisa_hutang'],
        'jml_dibayar' => $pr['dibayar'],
        'keterangan' => $pr['keterangan'],
        'from' => $pr['from'],
      ];
      $tot_jml_dibayar += $pr['dibayar'];
    }
    foreach ($post['pembayarans'] as $pr) {
      $ins_bayar[] = [
        'no_voucher' => $no_voucher,
        'no_bg_cek' => $pr['no_bg_cek'] != '' ? $pr['no_bg_cek'] : NULL,
        'tgl_jatuh_tempo_bg_cek' => $pr['tgl_jatuh_tempo_bg_cek'] != '' ? $pr['tgl_jatuh_tempo_bg_cek'] : NULL,
        'tgl_transfer' => $pr['tgl_transfer'] != '' ? $pr['tgl_transfer'] : NULL,
        'nominal' => $pr['nominal']
      ];
    }
    $insert['tot_jml_dibayar'] = $tot_jml_dibayar;
    // $tes = ['insert' => $insert, 'ins_detail' => $ins_detail, 'ins_bayar' => $ins_bayar];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h23_pengeluaran_finance', $insert);
    $this->db->insert_batch('tr_h23_pengeluaran_finance_detail', $ins_detail);
    $this->db->insert_batch('tr_h23_pengeluaran_finance_pembayaran', $ins_bayar);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $no_voucher    = $this->input->get('id');

    $filter['no_voucher'] = $no_voucher;
    $result = $this->m_fin->getPengeluaranFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $filter['status'] = null;
      $data['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      $data['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $post       = $this->input->post();
    $no_voucher = $post['no_voucher'];

    $update = [
      'kode_coa'          => $post['kode_coa'],
      'dibayar_kepada'    => $post['dibayar_kepada'],
      'deskripsi'         => $post['deskripsi'],
      'updated_at'        => waktu_full(),
      'updated_by'        => user()->id_user,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'no_voucher' => $no_voucher,
        'kode_coa' => $pr['kode_coa'],
        'id_referensi' => $pr['id_referensi'],
        'sisa_hutang' => $pr['sisa_hutang'],
        'jml_dibayar' => $pr['dibayar'],
        'keterangan' => $pr['keterangan'],
        'from' => $pr['from'],
      ];
    }
    // $tes = ['update' => $update, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h23_pengeluaran_finance', $update, ['no_voucher' => $no_voucher]);
    $this->db->delete('tr_h23_pengeluaran_finance_detail', ['no_voucher' => $no_voucher]);
    $this->db->insert_batch('tr_h23_pengeluaran_finance_detail', $ins_detail);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }
  public function ubah_status()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Ubah Status ' . $this->title;
    $data['mode']  = 'ubah_status';
    $data['set']   = "form";
    $no_voucher    = $this->input->get('id');

    $filter['no_voucher'] = $no_voucher;
    $result = $this->m_fin->getPengeluaranFinance($filter);

    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      $data['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_status()
  {
    $waktu     = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $id_dealer = $this->m_admin->cari_dealer();

    $no_voucher = $post['no_voucher'];

    $update = [
      'status' => $post['status'],
      'updated_status_at'  => $waktu,
      'updated_status_by'  => $login_id,
    ];
    // $tes = ['update' => $update, 'no_voucher' => $no_voucher];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h23_pengeluaran_finance', $update, ['no_voucher' => $no_voucher, 'id_dealer' => $id_dealer]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . '/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been processed successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }
  public function cetak()
  {
    $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $no_voucher = $this->input->get('id');

    $filter = ['no_voucher' => $no_voucher];
    $get_wo = $this->m_fin->getPengeluaranFinance($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set'] = 'cetak';
      $data['row']    = $row;
      $data['detail'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      if (isset($_GET['cek'])) {
        send_json($data);
      }
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_nsc.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }
}
