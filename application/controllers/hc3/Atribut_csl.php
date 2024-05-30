<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Atribut_csl extends CI_Controller
{
  var $page  = "atribut_csl";
  var $folder  = "hc3";
  var $title = "Atribut CSL";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_md_claim', 'm_claim');
    $this->load->model('m_md_csl', 'm_csl');
    //===== Load Library =====
    $this->load->library('upload');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false' or $sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $data['folder']   = $this->folder;
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
    $data['set']   = "view";
    $this->template($data);
  }
  public function fetch()
  {
    $fetch_data = $this->make_query_fetch();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $active = '';
      $button = '';
      if ($rs->active == '1') {
        $active = '<i class="fa fa-check"></i>';
      }
      $btn_edit = "<a data-toggle='tooltip' href='hc3/atribut_csl/edit?id=$rs->id'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      $button .= $btn_edit;
      $sub_array[] = $rs->id;
      $sub_array[] = $rs->kategori;
      $sub_array[] = $rs->code;
      $sub_array[] = $rs->nama_atribut;
      $sub_array[] = $active;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_fetch(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_fetch($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'view',
      'search' => $this->input->post('search')['value']
    ];
    if (isset($_POST['kategori'])) {
      $filter['kategori'] = $_POST['kategori'];
    }
    if (isset($_POST['active'])) {
      $filter['active'] = $_POST['active'];
    }
    if ($recordsFiltered == true) {
      return $this->m_csl->getAtributCSL($filter)->num_rows();
    } else {
      return $this->m_csl->getAtributCSL($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Insert ' . $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    $this->template($data);
  }
  function save()
  {
    $post       = $this->input->post();
    $insert = [
      'kategori'     => $post['kategori'],
      'nama_atribut' => $post['nama_atribut'],
      'code'         => $post['code'],
      'active'       => $post['active'],
      'created_at'   => waktu_full(),
      'created_by'   => user()->id_user,
    ];

    // $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_csl_atribut', $insert);
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


  public function edit()
  {
    $id = $this->input->get('id');
    $filter = ['id' => $id];
    $row = $this->m_csl->getAtributCSL($filter);
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Edit ' . $this->title;
      $data['mode']  = 'edit';
      $data['set']   = "form";
      $data['row'] = $row->row();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }
  function save_edit()
  {
    $post       = $this->input->post();
    $update = [
      'kategori'     => $post['kategori'],
      'nama_atribut' => $post['nama_atribut'],
      'code'         => $post['code'],
      'active'       => $post['active'],
      'updated_at'   => waktu_full(),
      'updated_by'   => user()->id_user,
    ];

    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $cond = ['id' => $post['id']];
    $this->db->update('ms_csl_atribut', $update, $cond);
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
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function detail()
  {
    $id = $this->input->get('id');
    $filter = ['id_po_kpb' => $id];
    $row = $this->m_claim->getPOKPB($filter);
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Detail ' . $this->title;
      $data['mode']  = 'detail';
      $data['set']   = "form";
      $data['row'] = $row->row();
      $data['details'] = $this->m_claim->getPOKPBDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }
}
