<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_list_ap extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h2_list_ap";
  var $title  = "List AP";

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
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->id_po;
      $sub_array[] = $rs->tgl_po;
      $sub_array[] = $rs->due_date;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->dpp);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->ppn);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->pph);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->total_hutang);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->pembayaran);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->sisa);
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
      'status'  => isset($_POST['status']) ? $_POST['status'] : '',
      'id_tagihan_not_null'  => isset($_POST['id_tagihan_not_null']) ? $_POST['id_tagihan_not_null'] : '',
      'status_tagihan' => 'approved',
      'search' => $this->input->post('search')['value'],
      'sisa' => " > 0"
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getListAP($filter)->num_rows();
    } else {
      return $this->m_fin->getListAP($filter)->result();
    }
  }
}
