<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_api_key extends CI_Controller
{

  var $folder = "master";
  var $page   = "dgi_api_key";
  var $title  = "Key Management Dealer Group Integration";
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
      $btn_active = "<a data-toggle='tooltip' title='Set Aktif' class='btn btn-success btn-xs btn-flat' onclick=\"return confirm('Apakah anda yakin ?')\" href=\"" . base_url('master/' . $this->page . '/setActive?id=' . $rs->api_key) . "\"><i class='fa fa-check'></i></a>";
      if ($rs->active == 0) {
        $button = $btn_active;
      }
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      $sub_array[] = $rs->id_dealer;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->api_key;
      $sub_array[] = $rs->secret_key;
      $sub_array[] = $rs->created_at;
      $sub_array[] = $rs->updated_at;
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
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'viewMD',
      // 'active' => 1,
      'search' => $this->input->post('search')['value'],
    ];
    if (isset($_POST['po_type'])) {
      $filter['po_type'] = $_POST['po_type'];
    }
    if ($recordsFiltered == true) {
      return $this->m_dgi->getAPIKey($filter)->num_rows();
    } else {
      return $this->m_dgi->getAPIKey($filter)->result();
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
    $data   = [
      'id_dealer'     => $post['id_dealer'],
      'api_key'     => $post['api_key'],
      'secret_key'     => $post['secret_key'],
      'active' => 1,
      'created_at'  => waktu_full(),
      'created_by'  => user()->id_user
    ];
    $this->db->trans_begin();
    $this->db->update('ms_dgi_api_key', ['active' => 0], ['id_dealer' => $post['id_dealer']]);
    $this->db->insert('ms_dgi_api_key', $data);
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
        'link' => base_url('master/dgi_api_key')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    echo json_encode($rsp);
  }

  public function setActive()
  {
    $id = $this->input->get('id');
    $filter['api_key'] = $id;
    $cek = $this->m_dgi->getAPIKey($filter);
    if ($cek->num_rows() > 0) {
      $cek = $cek->row();
      $data   = [
        'active' => 1,
        'updated_at'  => waktu_full(),
        'updated_by'  => user()->id_user
      ];
      $this->db->trans_begin();
      $this->db->update('ms_dgi_api_key', ['active' => 0], ['id_dealer' => $cek->id_dealer]);
      $this->db->update('ms_dgi_api_key', $data, ['api_key' => $id]);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $this->db->trans_commit();
        $_SESSION['pesan']   = "Something went wrong";
        $_SESSION['tipe']   = "danger";
      } else {
        $this->db->trans_commit();
        $_SESSION['pesan']   = "Data has been actived successfully";
        $_SESSION['tipe']   = "success";
      }
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/dgi_api_key'>";
    } else {
      $_SESSION['pesan']   = "Data not found";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/dgi_api_key'>";
    }
  }

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
  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/dgi_api_key'>";
  //   }
  // }

  // public function edit()
  // {
  //   $data['isi']    = $this->page;
  //   $data['title']  = $this->title;
  //   $api_key = $this->input->get('id');
  //   $filter['api_key'] = $api_key;
  //   $row = $this->m_dgi->getAPIKey($filter);
  //   if ($row->num_rows() > 0) {
  //     $row = $data['row'] = $row->row();
  //     $data['set']  = "form";
  //     $data['mode'] = "edit";
  //     // send_json($data);
  //     $this->template($data);
  //   } else {
  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/dgi_api_key'>";
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
  //       'link' => base_url('master/dgi_api_key')
  //     ];
  //     $_SESSION['pesan']   = "Data has been updated successfully";
  //     $_SESSION['tipe']   = "success";
  //   }
  //   echo json_encode($rsp);
  // }
}
