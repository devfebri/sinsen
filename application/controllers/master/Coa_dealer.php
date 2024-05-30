<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Coa_dealer extends CI_Controller
{

  var $folder = "master";
  var $page   = "coa_dealer";
  var $title  = "COA Dealer";

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
    $this->load->model('m_master_finance', 'm_fin');


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
      $btn_print = '<a style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->kode_coa . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $button = $btn_print;
      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->kode_coa . '">' . $rs->kode_coa . '</a>';
      $sub_array[] = $rs->coa;
      $sub_array[] = $rs->tipe_transaksi;
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->saldo_awal);
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
      return $this->m_fin->getCOADealer($filter)->num_rows();
    } else {
      return $this->m_fin->getCOADealer($filter)->result();
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
    $kode_coa    = $this->input->get('id');

    $filter['kode_coa'] = $kode_coa;
    $result = $this->m_fin->getCOADealer($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
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

    //Cek Kode
    $filter['kode_coa'] = $post['kode_coa'];
    $cek = $this->m_fin->getCOADealer($filter);
    if ($cek->num_rows() > 0) {
      $result = ['status' => 'error', 'pesan' => 'Kode COA sudah ada !'];
      send_json($result);
    }

    $insert = [
      'kode_coa' => $post['kode_coa'],
      'coa' => $post['coa'],
      'tipe_transaksi' => $post['tipe_transaksi'],
      'saldo_awal' => $post['saldo_awal'],
      'created_at' => $waktu,
      'created_by' => $login_id,
    ];
    // $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_coa_dealer', $insert);
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
    $kode_coa    = $this->input->get('id');

    $filter['kode_coa'] = $kode_coa;
    $result = $this->m_fin->getCOADealer($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
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
    $waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $kode_coa = $post['kode_coa'];

    $update = [
      'coa' => $post['coa'],
      'tipe_transaksi' => $post['tipe_transaksi'],
      'saldo_awal' => $post['saldo_awal'],
      'updated_at' => $waktu,
      'updated_by' => $login_id,
    ];
    // $tes = ['update' => $update,];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_coa_dealer', $update, ['kode_coa' => $kode_coa]);
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
}
