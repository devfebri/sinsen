<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_h1_stok_apparel extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h1_stok_apparel";
  var $title  = "Stok Apparel";

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
    $this->load->model('M_sc_sp_stock', 'm_stock');
    $this->load->model('M_h2_api', 'm_h2_api');



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
    $id_dealer = $this->m_admin->cari_dealer();

    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $filter_d = [
        'id_part' => $rs->id_part,
        'id_dealer' => $id_dealer,
        'select' => 'summary_stok',
      ];
      $rs->stok = $this->m_h2_api->fetch_partWithAllStock($filter_d)->row()->summary_stok;
      $sub_array[] = $rs->id_part;
      $sub_array[] = $rs->nama_part;
      $sub_array[] = $rs->stok;
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
      'order_column' => 'view',
      'deleted' => false,
    ];
    if ($recordsFiltered == true) {
      return $this->m_stock->getApparel($filter)->num_rows();
    } else {
      return $this->m_stock->getApparel($filter)->result();
    }
  }
}
