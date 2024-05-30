<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_paket_jasa_service_regular extends CI_Controller
{

  var $folder =   "master";
  var $page    =    "h2_paket_jasa_service_regular";

  var $title  =   "Paket Jasa Service Regular";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('Number_model');
    //===== Load Library =====
    // $this->load->library('upload');
    $this->load->helper('tgl_indo');
    $this->load->model('m_h2_jasa', 'm_jasa');
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
    $data['isi']        = $this->page;
    $data['title']      = $this->title;
    $data['set']        = "view";
    $this->template($data);
  }
  public function loadData()
  {

    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $button    = '';
      $btn_edit = "<a data-toggle='tooltip' href='master/$this->page/edit?id=$rs->kode_paket'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      $button = $btn_edit;
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = "<a href='master/$this->page/detail?id=$rs->kode_paket'>$rs->kode_paket</a>";
      $sub_array[] = $rs->nama_paket;
      $sub_array[] = $rs->mileage;
      $sub_array[] = $rs->total_list_service;
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

  function make_query($no_limit = null)
  {
    $total_list_service = "(SELECT COUNT(kode_paket) FROM ms_h2_paket_jasa_regular_detail WHERE kode_paket=pr.kode_paket)";
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $order_column = array('kode_paket', 'nama_paket', 'deskripsi_paket', 'mileage', $total_list_service, 'active', null);
    $limit        = "LIMIT $start,$length";
    $order        = 'ORDER BY pr.created_at DESC';
    $search       = $this->input->post('search')['value'];
    $searchs      = "WHERE 1=1 ";

    if ($search != '') {
      $searchs .= "AND (pr.kode_paket LIKE '%$search%' 
	          OR pr.nama_paket LIKE '%$search%'
	          OR pr.deskripsi_paket LIKE '%$search%'
	          OR pr.mileage LIKE '%$search%'
	          OR pr.created_at LIKE '%$search%'
	          )
	      ";
    }

    if (isset($_POST["order"])) {
      $order_clm = $order_column[$_POST['order']['0']['column']];
      $order_by  = $_POST['order']['0']['dir'];
      $order     = "ORDER BY $order_clm $order_by";
    }

    if ($no_limit == 'y') $limit = '';

    return $this->db->query("SELECT pr.*,($total_list_service) total_list_service
   		 FROM ms_h2_paket_jasa_regular pr
   		 $searchs $order $limit ");
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

  public function upload()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "up";
    $data['mode']    = "up";
    $this->template($data);
  }

  public function upload_dealer()
  {
    $data['isi']    = $this->page;
    $data['title']  = "Upload Master Jasa Dealer";
    $data['set']    = "up_dealer";
    $data['mode']    = "up_dealer";
    $this->template($data);
  }

  public function jasa_dealer()
  {
    $data['isi']    = $this->page;
    $data['title']  = "Master Jasa Dealer";
    $data['set']    = "jsd";
    $data['mode']    = "jsd";
    $this->template($data);
  }

  public function save()
  {
    $login_id = $this->session->userdata('id_user');
    $kode_paket = $this->m_jasa->get_kode_paket_jasa_regular();

    $data   = [
      'kode_paket'          => $kode_paket,
      'nama_paket'        => $this->input->post('nama_paket'),
      'deskripsi_paket'   => $this->input->post('deskripsi_paket'),
      'mileage'           => $this->input->post('mileage'),
      'active'            => isset($_POST['active']) ? 1 : 0,
      'created_at'        => waktu_full(),
      'created_by'        => $login_id
    ];
    foreach ($this->input->post('list_service') as $key => $ls) {
      $ins_list_service[] = [
        'kode_paket'       => $kode_paket,
        'id_jasa'   => $ls['id_jasa']
      ];
    }

    $this->db->trans_begin();
    $this->db->insert('ms_h2_paket_jasa_regular', $data);
    if (isset($ins_list_service)) {
      $this->db->insert_batch('ms_h2_paket_jasa_regular_detail', $ins_list_service);
    }

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
        'link' => base_url('master/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    echo json_encode($rsp);
  }

  public function edit()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $kode_paket = $this->input->get('id');
    $row = $this->db->query("SELECT * FROM ms_h2_paket_jasa_regular WHERE kode_paket='$kode_paket'");
    if ($row->num_rows() > 0) {
      $data['row']            = $row->row();
      $data['set']            = "form";
      $data['mode']           = "edit";
      $data['list_service']   = $this->m_jasa->get_detail_paket_jasa_regular_all_details($kode_paket);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2'>";
    }
  }

  public function save_edit()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');

    $kode_paket  = $this->input->post('kode_paket');

    $data   = [
      'nama_paket'        => $this->input->post('nama_paket'),
      'deskripsi_paket'   => $this->input->post('deskripsi_paket'),
      'mileage'           => $this->input->post('mileage'),
      'active'            => isset($_POST['active']) ? 1 : 0,
      'updated_at'        => $waktu,
      'updated_by'        => $login_id
    ];

    foreach ($this->input->post('list_service') as $key => $ls) {
      $ins_list_service[] = [
        'kode_paket'    => $kode_paket,
        'id_jasa'       => $ls['id_jasa']
      ];
    }
    // send_json([$ins_list_service, $data]);

    $this->db->trans_begin();
    $this->db->update('ms_h2_paket_jasa_regular', $data, ['kode_paket' => $kode_paket]);

    $this->db->delete('ms_h2_paket_jasa_regular_detail', ['kode_paket' => $kode_paket, 'id_dealer' => null]);
    if (isset($ins_list_service)) {
      $this->db->insert_batch('ms_h2_paket_jasa_regular_detail', $ins_list_service);
    }

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
        'link' => base_url('master/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
    }
    echo json_encode($rsp);
  }

  public function detail()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $kode_paket = $this->input->get('id');
    $row = $this->db->query("SELECT * FROM ms_h2_paket_jasa_regular WHERE kode_paket='$kode_paket'");
    if ($row->num_rows() > 0) {
      $data['row']            = $row->row();
      $data['set']            = "form";
      $data['mode']           = "detail";
      $data['list_service']   = $this->m_jasa->get_detail_paket_jasa_regular_all_details($kode_paket);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/jasa_h2'>";
    }
  }
}
