<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_h23_pit_management extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h23_pit_management";
  var $title  = "PIT Management";

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
    $this->load->model('m_dms');


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
      // $sub_array[] = $rs->kode_md;
      // $sub_array[] = $rs->kode_dealer_md;
      $sub_array[] = $rs->tgl_transaksi;
      $sub_array[] = $rs->honda_id;
      $sub_array[] = $rs->id_pit;
      $sub_array[] = $rs->jenis_pit;
      $cp = explode('|', $rs->nama_customer_no_polisi);
      $sub_array[] = isset($cp[1]) ? $cp[1] : '';
      $sub_array[] = $rs->nama_mekanik;
      $sub_array[] = $cp[0];
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
      'pit_not_null' => true,
      'join_mekanik' => true,
      'join_pit' => true,
      'id_dealer' => dealer()->id_dealer
    ];
    if ($recordsFiltered == true) {
      return $this->m_dms->getH23PitMekanikManagement($filter)->num_rows();
    } else {
      return $this->m_dms->getH23PitMekanikManagement($filter)->result();
    }
  }
}
