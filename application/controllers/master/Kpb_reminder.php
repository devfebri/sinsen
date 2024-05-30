<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpb_reminder extends CI_Controller
{

  var $table = 'ms_h2_kpb_reminder';
  var $folder = "master";
  var $page   = "kpb_reminder";
  var $title  = "Master KPB Reminder";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_kpb_reminder', 'm_kpb');
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
      $button    = '';
      $btn_edit = "<a data-toggle='tooltip' href='master/kpb_reminder/edit?id=$rs->id_kpb'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      $button = $btn_edit;
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = "<a href='master/kpb_reminder/detail?id=$rs->id_kpb'>$rs->sms_kpb1</a>";
      $sub_array[] = $rs->call_kpb1;
      $sub_array[] = $rs->sms_kpb2;
      $sub_array[] = $rs->call_kpb3;
      $sub_array[] = $rs->sms_kpb3;
      $sub_array[] = $rs->call_kpb3;
      $sub_array[] = $rs->sms_kpb4;
      $sub_array[] = $rs->call_kpb4;
      $sub_array[] = $active;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data(),
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

    return $this->m_kpb->fetchData($filter);
  }

  function get_filtered_data()
  {
    return $this->make_query('y')->num_rows();
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
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');

    $data   = [
      'sms_kpb1'     => $this->input->post('sms_kpb1'),
      'call_kpb1'     => $this->input->post('call_kpb1'),
      'sms_kpb2'     => $this->input->post('sms_kpb2'),
      'call_kpb2'     => $this->input->post('call_kpb2'),
      'sms_kpb3'     => $this->input->post('sms_kpb3'),
      'call_kpb3'     => $this->input->post('call_kpb3'),
      'sms_kpb4'     => $this->input->post('sms_kpb4'),
      'call_kpb4'     => $this->input->post('call_kpb4'),
      'active'      => isset($_POST['active']) ? 1 : 0,
      'created_at'  => $waktu,
      'created_by'  => $login_id
    ];

    // echo json_encode($data);
    // exit;
    $this->db->trans_begin();
    if ($data['active'] == 1) {
      $this->db->query("UPDATE ms_h2_kpb_reminder SET active=0");
    }
    $this->db->insert($this->table, $data);
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
        'link' => base_url('master/kpb_reminder')
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
    $id_jasa = $this->input->get('id');
    $row = $this->db->query("SELECT * FROM ms_h2_jasa WHERE id_jasa='$id_jasa'");
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']    = "detail";
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/kpb_reminder'>";
    }
  }

  public function edit()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $id_kpb = $this->input->get('id');
    $filter = ['id_kpb' => $id_kpb];
    $row = $this->m_kpb->getKPBReminder($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']    = "edit";
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/kpb_reminder'>";
    }
  }

  public function save_edit()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');

    $id_kpb  = $this->input->post('id_kpb');
    // die;
    $data   = [
      'sms_kpb1'     => $this->input->post('sms_kpb1'),
      'call_kpb1'     => $this->input->post('call_kpb1'),
      'sms_kpb2'     => $this->input->post('sms_kpb2'),
      'call_kpb2'     => $this->input->post('call_kpb2'),
      'sms_kpb3'     => $this->input->post('sms_kpb3'),
      'call_kpb3'     => $this->input->post('call_kpb3'),
      'sms_kpb4'     => $this->input->post('sms_kpb4'),
      'call_kpb4'     => $this->input->post('call_kpb4'),
      'active'      => isset($_POST['active']) ? 1 : 0,
      'updated_at'  => $waktu,
      'updated_by'  => $login_id
    ];

    // echo json_encode($dt_detail);
    // echo json_encode($upd_claim);
    // echo json_encode($data);
    // exit;
    $this->db->trans_begin();
    if ($data['active'] == 1) {
      $this->db->query("UPDATE $this->table SET active=0");
    }
    $this->db->update($this->table, $data, ['id_kpb' => $id_kpb]);
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
        'link' => base_url('master/kpb_reminder')
      ];
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
    }
    echo json_encode($rsp);
  }
}
