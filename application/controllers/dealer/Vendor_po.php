<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendor_po extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "vendor_po";
  var $title  = "Vendor PO";

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
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id_vendor . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      if (can_access($this->page, 'can_update')) $button = $btn_edit;
      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_vendor . '">' . $rs->id_vendor . '</a>';
      $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->alamat;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $aktif;
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
      return $this->m_fin->getVendorPO($filter)->num_rows();
    } else {
      return $this->m_fin->getVendorPO($filter)->result();
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
    $id_vendor    = $this->input->get('id');

    $filter['id_vendor'] = $id_vendor;
    $result = $this->m_fin->getVendorPO($filter);
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
    $id_vendor = $this->m_fin->get_id_vendor_po();
    $id_dealer    = $this->m_admin->cari_dealer();

    $insert = [
      'id_vendor'   => $id_vendor,
      'id_dealer'   => $id_dealer,
      'nama_vendor' => $post['nama_vendor'],
      'alamat'      => $post['alamat'],
      'no_hp'       => $post['no_hp'],
      'kode_tipe_vendor'  => $post['kode_tipe_vendor'],
      'tipe_vendor'       => $post['tipe_vendor'],
      'kode_group_vendor' => $post['kode_group_vendor'],
      'group_vendor'      => $post['group_vendor'],
      'ppn'               => $post['ppn'],
      'no_rekening'       => $post['no_rekening'],
      'aktif'       => isset($_POST['aktif']) ? 1 : 0,
      'created_at'  => waktu_full(),
      'created_by'  => $login_id,
    ];
    // $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_h2_vendor_po_dealer', $insert);
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
    $id_vendor    = $this->input->get('id');

    $filter['id_vendor'] = $id_vendor;
    $result = $this->m_fin->getVendorPO($filter);
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
    $waktu     = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $id_dealer = $this->m_admin->cari_dealer();

    $id_vendor = $post['id_vendor'];

    $update = [
      'nama_vendor' => $post['nama_vendor'],
      'alamat'      => $post['alamat'],
      'no_hp'       => $post['no_hp'],
      'kode_tipe_vendor'  => $post['kode_tipe_vendor'],
      'tipe_vendor'       => $post['tipe_vendor'],
      'kode_group_vendor' => $post['kode_group_vendor'],
      'group_vendor'      => $post['group_vendor'],
      'ppn'               => $post['ppn'],
      'no_rekening'       => $post['no_rekening'],
      'aktif'       => isset($_POST['aktif']) ? 1 : 0,
      'updated_at'  => waktu_full(),
      'updated_by'  => $login_id,
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_h2_vendor_po_dealer', $update, ['id_vendor' => $id_vendor, 'id_dealer' => $id_dealer]);
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
