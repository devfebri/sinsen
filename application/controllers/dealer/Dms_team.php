<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_team extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_team";
  var $title  = "Team Sales";

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
      $status = '';
      $button = '';
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      $btn_edit = '<a data-toggle="tooltip" title="Edit Data" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id_team . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      if (can_access($this->page, 'can_update')) $button = $btn_edit;
      // $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . '">' . $rs->id . '</a>';
      // $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';
      $sub_array[] = $rs->id_team;
      $sub_array[] = $rs->nama_team;
      $sub_array[] = $active;
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
      'id_dealer' => dealer()->id_dealer,
      'order_column' => 'view'
    ];
    if ($recordsFiltered == true) {
      return $this->m_dms->getTeam($filter)->num_rows();
    } else {
      return $this->m_dms->getTeam($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    $this->template($data);
  }

  function save()
  {
    $post       = $this->input->post();
    $insert = [
      'nama_team'   => $post['nama_team'],
      'id_team'     => $this->m_dms->get_id_team(),
      'id_dealer'   => dealer()->id_dealer,
      // 'aktif'          => isset($_POST['aktif']) ? 1: 0,
      'created_at'  => waktu_full(),
      'created_by'  => user()->id_user,
      'active'      => $this->input->post('active') == 'on' ? 1 : 0,
    ];
    // $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('dms_ms_team', $insert);
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
    $id    = $this->input->get('id');

    $filter['id_team'] = $id;
    $filter['id_dealer'] = dealer()->id_dealer;
    $result = $this->m_dms->getTeam($filter);
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
    $post      = $this->input->post();

    $id_team = $post['id_team'];

    $update = [
      'nama_team'             => $post['nama_team'],
      'updated_at'        => waktu_full(),
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'updated_by'        => user()->id_user,
    ];
    $id_dealer = dealer()->id_dealer;
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_ms_team', $update, ['id_team' => $id_team,'id_dealer'=>$id_dealer]);
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
