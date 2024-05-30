<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_detail_work_list extends CI_Controller
{

  var $folder   = "master";
  var $page     = "h2_detail_work_list";
  var $title    = "Master Detail Work List";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('Number_model');
    $this->load->model('M_h2_jasa', 'm_jasa');
    //===== Load Library =====
    // $this->load->library('upload');
    $this->load->helper('tgl_indo');
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
      $btn_edit = "<a data-toggle='tooltip' href='master/$this->page/edit?id=$rs->kode_detail'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      // $btn_delete = "<a onclick=\"return confirm('Apakah Anda yakin ?')\" data-toggle='tooltip' href='master/jasa_h2/delete?id=$rs->kode_detail'><button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-trash'></i></button></a>";
      $button = $btn_edit;
      $status = $rs->status == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = "<a href='master/$this->page/detail?id=$rs->kode_detail'>$rs->kode_detail</a>";
      $sub_array[] = $rs->nama_detail;
      $sub_array[] = $status;
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
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $order_column = array('kode_detail', 'nama_detail', 'status', null);
    $limit        = "LIMIT $start,$length";
    $order        = 'ORDER BY dwl.created_at DESC';
    $search       = $this->input->post('search')['value'];
    $searchs      = "WHERE 1=1 ";

    if ($search != '') {
      $searchs .= "AND (dwl.kode_detail LIKE '%$search%' 
	          OR dwl.created_at LIKE '%$search%'
	          OR dwl.nama_detail LIKE '%$search%'
	          )
	      ";
    }

    if (isset($_POST["order"])) {
      $order_clm = $order_column[$_POST['order']['0']['column']];
      $order_by  = $_POST['order']['0']['dir'];
      $order     = "ORDER BY $order_clm $order_by";
    }

    if ($no_limit == 'y') $limit = '';

    return $this->db->query("SELECT dwl.*
   		 FROM ms_h2_detail_work_list dwl
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

  public function save()
  {
    $login_id = $this->session->userdata('id_user');

    $data   = [
      'kode_detail'   => $this->m_jasa->get_kode_detail(),
      'nama_detail'   => $this->input->post('nama_detail'),
      'status'        => isset($_POST['status']) ? 1 : 0,
      'created_at'    => waktu_full(),
      'created_by'    => $login_id
    ];

    // echo json_encode($data);
    // exit;
    $this->db->trans_begin();
    $this->db->insert('ms_h2_detail_work_list', $data);
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
    die;
  }

  public function edit()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $kode_detail = $this->input->get('id');
    $row = $this->db->query("SELECT * FROM ms_h2_detail_work_list WHERE kode_detail='$kode_detail'");
    if ($row->num_rows() > 0) {
      $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']   = "edit";
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/$this->page'>";
    }
  }

  public function save_edit()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    extract($this->input->post());
    $row = $this->db->get_where('ms_h2_detail_work_list', ['kode_detail' => $kode_detail_old])->row();
    if ($row == null) {
      $rsp = [
        'status' => 'error',
        'pesan' => 'Kode Detail ' . $kode_detail_old . ' tidak ditemukan !'
      ];
      echo json_encode($rsp);
      exit;
    }

    $data   = [
      'nama_detail'   => $this->input->post('nama_detail'),
      'status'        => isset($_POST['status']) ? 1 : 0,
      'updated_at'    => $waktu,
      'updated_by'    => $login_id
    ];

    // echo json_encode($data);
    // exit;
    $this->db->trans_begin();
    $this->db->update('ms_h2_detail_work_list', $data, ['kode_detail' => $kode_detail_old]);
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
    $kode_detail = $this->input->get('id');
    $row = $this->db->query("SELECT * FROM ms_h2_detail_work_list WHERE kode_detail='$kode_detail'");
    if ($row->num_rows() > 0) {
      $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']   = "detail";
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/$this->page'>";
    }
  }
}
