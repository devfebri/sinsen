<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Good_issue extends CI_Controller
{
  var $folder = "dealer";
  var $page   = "good_issue";
  var $title  = "Good Issue";

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
    $this->load->model('m_h2_work_order', 'm_wo');
    //===== Load Library =====
    $this->load->library('upload');
    $this->load->library('form_validation');
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
        if ($data['mode'] == 'insert_wo') $page = 'sa_form';
        if ($data['mode'] == 'detail_wo') $page = 'sa_form';
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

    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $sub_array[] = $rs->good_issue_id;
      $sub_array[] = $rs->nomor_so;
      $sub_array[] = $rs->nomor_ps;
      $sub_array[] = $rs->id_work_order;
      $sub_array[] = date_dmy($rs->tgl_servis);
      $sub_array[] = $rs->jenis_customer;
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->warna;
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
      'good_issue_not_null' => 'ya',
      'status_wo_in' => "'pause', 'open'",
      'order_column' => ['good_issue_id', 'kkc.nomor_so', 'nomor_ps', 'kkc.id_work_order', 'tgl_servis', 'jenis_customer', 'no_polisi', 'nama_customer', 'ch23.no_mesin', 'ch23.no_rangka', 'tipe_ahm', 'warna']
    ];

    if ($recordsFiltered == true) {
      return $this->m_wo->get_kirim_wo($filter)->num_rows();
    } else {
      return $this->m_wo->get_kirim_wo($filter)->result();
    }
  }

  public function detail_wo()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail Work Order';
    $data['mode']  = 'detail_wo';
    $data['set']   = "form";
    $id_work_order    = $this->input->get('id');

    $filter['id_work_order'] = $this->input->post('id_work_order');
    $sa_form = $this->m_wo->get_sa_form($filter);
    if ($sa_form->num_rows() > 0) {
      $row                     = $data['row_wo'] = $sa_form->row();
      $data['tipe_coming']     = explode(',', $row->tipe_coming);
      $filter['id_work_order'] = $id_work_order;
      $data['details']         = $this->m_h2->wo_detail($filter);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
    }
  }
}
