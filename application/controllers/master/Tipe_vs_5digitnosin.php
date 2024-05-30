<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tipe_vs_5digitnosin extends CI_Controller
{

  var $folder = "master";
  var $page   = "tipe_vs_5digitnosin";
  var $title  = "Master Tipe vs 5 Digit No. Mesin";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_tipe_vs_5digitnosin', 'm_tipe');
    //===== Load Library =====
    // $this->load->library('upload');
    $this->load->helper('tgl_indo');

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
      $data['folder'] = $this->folder;
      $data['page'] = $this->page;
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']       = $this->page;
    $data['title']     = $this->title;
    $data['set']       = "view";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      // $button    = '';
      $button = "<a data-toggle='tooltip' href='" . base_url($this->folder . '/' . $this->page) . "/edit?id=$rs->id'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";

      $sub_array[] = "<a href='" . base_url($this->folder . '/' . $this->page) . "/detail?id=$rs->id'>$rs->id</a>";
      $sub_array[] = $rs->nama_tipe;
      $sub_array[] = $rs->tot_kendaraan;
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

  public function make_query($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'limit' => $limit,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
    ];
    if ($no_limit == null) {
      return $this->m_tipe->fetchData($filter);
    } else {
      return $this->m_tipe->fetchData($filter)->num_rows();
    }
  }

  public function add()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "form";
    $data['mode']    = "insert";
    $this->template($data);
  }

  public function save()
  {
    $post = $this->input->post();
    $id = $this->m_tipe->get_id();
    // send_json($post);
    $data   = [
      'id' => $id,
      'nama_tipe' => $post['nama_tipe'],
      'harga' => $post['harga'],
      'aktif' => isset($post['aktif']) ? 1 : 0,
      'created_at'  => waktu_full(),
      'created_by'  => user()->id_user
    ];

    foreach ($post['details'] as $dt) {
      $ins_details[] = [
        'id' => $id,
        'id_tipe_kendaraan' => $dt['id_tipe_kendaraan']
      ];
    }
    // $tes = ['data' => $data, 'ins_detail' => $ins_details];
    // send_json($tes);
    $this->db->trans_begin();

    $this->db->insert('ms_tipe_vs_5_digit_no_mesin', $data);
    $this->db->insert_batch('ms_tipe_vs_5_digit_no_mesin_detail', $ins_details);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . '/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function detail()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $id = $this->input->get('id');
    $filter = ['id' => $id];
    $row = $this->m_tipe->fetchData($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']    = "detail";
      $data['details'] = $this->m_tipe->detailTipe($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/kpb_reminder'>";
    }
  }

  public function edit()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $id = $this->input->get('id');
    $filter = ['id' => $id];
    $row = $this->m_tipe->fetchData($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']    = "edit";
      $data['details'] = $this->m_tipe->detailTipe($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/kpb_reminder'>";
    }
  }

  public function save_edit()
  {
    $post = $this->input->post();
    $id = $this->input->post('id');
    // send_json($post);
    $data   = [
      'id' => $id,
      'nama_tipe' => $post['nama_tipe'],
      'harga' => $post['harga'],
      'aktif' => isset($post['aktif']) ? 1 : 0,
      'updated_at'  => waktu_full(),
      'updated_by'  => user()->id_user
    ];

    foreach ($post['details'] as $dt) {
      $ins_details[] = [
        'id' => $id,
        'id_tipe_kendaraan' => $dt['id_tipe_kendaraan']
      ];
    }
    $tes = ['data' => $data, 'ins_detail' => $ins_details];
    // send_json($tes);
    $this->db->trans_begin();

    $cond = ['id'=>$id];
    $this->db->update('ms_tipe_vs_5_digit_no_mesin', $data,$cond);
    $this->db->delete('ms_tipe_vs_5_digit_no_mesin_detail',$cond);
    $this->db->insert_batch('ms_tipe_vs_5_digit_no_mesin_detail', $ins_details);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . '/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
