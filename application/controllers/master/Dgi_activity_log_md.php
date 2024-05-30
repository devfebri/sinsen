<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_activity_log_md extends CI_Controller
{

  var $folder = "master";
  var $page   = "dgi_activity_log_md";
  var $title  = "Activity Log";
  protected $key;


  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_dgi_api', 'm_dgi');
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
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $button    = '';
      // $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      $sub_array[] = $rs->id_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->endpoint;
      $sub_array[] = $rs->request_time;
      $sub_array[] = $rs->ip_address;
      $sub_array[] = $rs->http_response_code;
      $sub_array[] = $rs->data_count;
      $sub_array[] = $rs->status;
      $sub_array[] = $rs->message;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data
    );
    send_json($output);
  }

  public function make_query($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'viewMD',
      'active' => 1,
      'search' => $this->input->post('search')['value'],
    ];

    if ($recordsFiltered == true) {
      return $this->m_dgi->getActivityLog($filter)->num_rows();
    } else {
      return $this->m_dgi->getActivityLog($filter)->result();
    }
  }

  function generateKey()
  {
    $post = $this->input->post();
    $result = $this->m_dgi->generate_key($post);
    if ($result) {
      $response = ['status' => 'sukses', 'data' => $result];
    } else {
      $response = ['status' => 'gagal', 'pesan' => 'Generate API Key & Secret Key gagal. Silahkan coba lagi'];
    }
    send_json($response);
  }


  // public function add()
  // {
  //   $data['isi']    = $this->page;
  //   $data['title']  = $this->title;
  //   $data['set']    = "form";
  //   $data['mode']    = "insert";
  //   $this->template($data);
  // }

  // public function save()
  // {
  //   $post = $this->input->post();
  //   $data   = [
  //     'id_dealer'     => $post['id_dealer'],
  //     'api_key'     => $post['api_key'],
  //     'secret_key'     => $post['secret_key'],
  //     'active' => 1,
  //     'created_at'  => waktu_full(),
  //     'created_by'  => user()->id_user
  //   ];
  //   $this->db->trans_begin();
  //   $this->db->update('ms_dgi_api_key', ['active' => 0], ['id_dealer' => $post['id_dealer']]);
  //   $this->db->insert('ms_dgi_api_key', $data);
  //   if ($this->db->trans_status() === FALSE) {
  //     $this->db->trans_rollback();
  //     $rsp = [
  //       'status' => 'error',
  //       'pesan' => ' Something went wrong'
  //     ];
  //   } else {
  //     $this->db->trans_commit();
  //     $rsp = [
  //       'status' => 'sukses',
  //       'link' => base_url('master/api_dgi')
  //     ];
  //     $_SESSION['pesan']   = "Data has been saved successfully";
  //     $_SESSION['tipe']   = "success";
  //   }
  //   echo json_encode($rsp);
  // }

  // public function detail()
  // {
  //   $data['isi']    = $this->page;
  //   $data['title']  = $this->title;
  //   $id_jasa = $this->input->get('id');
  //   $row = $this->db->query("SELECT * FROM ms_h2_jasa WHERE id_jasa='$id_jasa'");
  //   if ($row->num_rows() > 0) {
  //     $row = $data['row'] = $row->row();
  //     $data['set']    = "form";
  //     $data['mode']    = "detail";
  //     $this->template($data);
  //   } else {
  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/api_dgi'>";
  //   }
  // }

  // public function edit()
  // {
  //   $data['isi']    = $this->page;
  //   $data['title']  = $this->title;
  //   $id_jasa = $this->input->get('id');
  //   $row = $this->db->query("SELECT * FROM ms_h2_jasa WHERE id_jasa='$id_jasa'");
  //   if ($row->num_rows() > 0) {
  //     $row = $data['row'] = $row->row();
  //     $data['set']    = "form";
  //     $data['mode']    = "edit";
  //     $this->template($data);
  //   } else {
  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/api_dgi'>";
  //   }
  // }

  // public function save_edit()
  // {
  //   $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
  //   $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
  //   $login_id = $this->session->userdata('id_user');

  //   $id_jasa  = $this->input->post('id_jasa');

  //   $data   = [
  //     'id_jasa' => $id_jasa,
  //     'deskripsi'   => $this->input->post('deskripsi'),
  //     'id_type'     => $this->input->post('id_type'),
  //     'tipe_motor'  => $this->input->post('tipe_motor'),
  //     'harga'       => $this->input->post('harga'),
  //     'waktu'       => $this->input->post('waktu'),
  //     'kategori'    => $this->input->post('kategori'),
  //     'batas_atas'  => $this->input->post('batas_atas'),
  //     'batas_bawah' => $this->input->post('batas_bawah'),
  //     'active'      => isset($_POST['active']) ? 1 : 0,
  //     'updated_at'  => $waktu,
  //     'updated_by'  => $login_id
  //   ];

  //   // echo json_encode($dt_detail);
  //   // echo json_encode($upd_claim);
  //   // echo json_encode($data);
  //   // exit;
  //   $this->db->trans_begin();
  //   $this->db->update('ms_h2_jasa', $data, ['id_jasa' => $id_jasa]);
  //   if ($this->db->trans_status() === FALSE) {
  //     $this->db->trans_rollback();
  //     $rsp = [
  //       'status' => 'error',
  //       'pesan' => ' Something went wrong'
  //     ];
  //   } else {
  //     $this->db->trans_commit();
  //     $rsp = [
  //       'status' => 'sukses',
  //       'link' => base_url('master/api_dgi')
  //     ];
  //     $_SESSION['pesan']   = "Data has been updated successfully";
  //     $_SESSION['tipe']   = "success";
  //   }
  //   echo json_encode($rsp);
  // }
}
