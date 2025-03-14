<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tagihan_lain extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "tagihan_lain";
  var $title  = "Tagihan Lain-lain";

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
      $data['folder'] = $this->folder;
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
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
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id_po . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      // $btn_print = '<a style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/cetak?id=' . $rs->id_po . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
      $button = $btn_edit;
      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_po . '">' . $rs->id_po . '</a>';
      $sub_array[] = $rs->tgl_po;
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->keterangan;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->total);
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
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getPOFinance($filter)->num_rows();
    } else {
      return $this->m_fin->getPOFinance($filter)->result();
    }
  }

  public function add()
  {
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
    $id_po    = $this->input->get('id');

    $filter['id_po'] = $id_po;
    $result = $this->m_fin->getPOFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPOFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save()
  {
    $waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_po = $this->m_fin->get_id_po();
    $id_dealer    = $this->m_admin->cari_dealer();

    $insert = [
      'id_po'   => $id_po,
      'id_dealer'   => $id_dealer,
      'tgl_po'   => $tanggal,
      'id_vendor' => $post['id_vendor'],
      'keterangan'      => $post['keterangan'],
      'created_at'  => $waktu,
      'created_by'  => $login_id,
    ];
    $total = 0;
    foreach ($post['details'] as $pr) {
      $total += ($pr['qty'] * $pr['harga_satuan']);
      $ins_detail[] = [
        'id_po' => $id_po,
        'id_barang' => $pr['id_barang'],
        'qty' => $pr['qty'],
        'harga_satuan' => $pr['harga_satuan'],
      ];
    }
    $insert['total'] = $total;
    // $tes = ['insert' => $insert, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_dealer_po_finance', $insert);
    $this->db->insert_batch('tr_h2_dealer_po_finance_detail', $ins_detail);
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
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $id_po    = $this->input->get('id');

    $filter['id_po'] = $id_po;
    $result = $this->m_fin->getPOFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPOFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $waktu     = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $id_dealer = $this->m_admin->cari_dealer();

    $id_po = $post['id_po'];

    $update = [
      'id_vendor' => $post['id_vendor'],
      'keterangan'      => $post['keterangan'],
      'updated_at'  => $waktu,
      'updated_by'  => $login_id,
    ];

    $total = 0;
    foreach ($post['details'] as $pr) {
      $total += ($pr['qty'] * $pr['harga_satuan']);
      $ins_detail[] = [
        'id_po' => $id_po,
        'id_barang' => $pr['id_barang'],
        'qty' => $pr['qty'],
        'harga_satuan' => $pr['harga_satuan'],
      ];
    }
    $update['total'] = $total;
    // $tes = ['update' => $update, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h2_dealer_po_finance', $update, ['id_po' => $id_po, 'id_dealer' => $id_dealer]);
    $this->db->delete('tr_h2_dealer_po_finance_detail', ['id_po' => $id_po]);
    $this->db->insert_batch('tr_h2_dealer_po_finance_detail', $ins_detail);

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
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  // public function cetak()
  // {
  //   $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
  //   $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
  //   $login_id   = $this->session->userdata('id_user');
  //   $id_po = $this->input->get('id');

  //   $filter = ['id_po' => $id_po];
  //   $get_wo = $this->m_fin->getPOFinance($filter);

  //   if ($get_wo->num_rows() > 0) {
  //     $row = $data['row'] = $get_wo->row();
  //     $this->load->library('mpdf_l');
  //     $mpdf                           = $this->mpdf_l->load();
  //     $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
  //     $mpdf->charset_in               = 'UTF-8';
  //     $mpdf->autoLangToFont           = true;

  //     $data['set'] = 'cetak';
  //     $data['row']    = $row;
  //     $data['detail'] = $this->m_fin->getPOFinanceDetail($filter)->result();
  //     // send_json($data);
  //     $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
  //     // render the view into HTML
  //     $mpdf->WriteHTML($html);
  //     // write the HTML into the mpdf
  //     $output = 'cetak_nsc.pdf';
  //     $mpdf->Output("$output", 'I');
  //   } else {
  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
  //   }
  // }
}
