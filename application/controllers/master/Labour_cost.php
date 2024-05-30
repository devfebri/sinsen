<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Labour_cost extends CI_Controller
{

  var $table = 'ms_h2_labour_cost';
  var $folder = "master";
  var $page   = "labour_cost";
  var $title  = "Master Labour Cost";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_labour_cost', 'm_lc');
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
      $btn_edit = "<a data-toggle='tooltip' href='master/labour_cost/edit?id=$rs->id'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      // $button = $btn_edit;
      $sub_array[] = "<a href='master/labour_cost/detail?id=$rs->id'>$rs->id</a>";
      $sub_array[] = $rs->tot_kendaraan;
      $sub_array[] = $rs->tgl_mulai_berlaku;
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
      return $this->m_lc->fetchData($filter);
    } else {
      return $this->m_lc->fetchData($filter)->num_rows();
    }
  }

  public function add()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "form";
    $data['mode']    = "insert";
    $harga_terakhir = "SELECT nominal 
    FROM ms_labour_cost_tipe lct
    JOIN ms_labour_cost lc ON lc.id=lc.id
    WHERE id_tipe_kendaraan=tk.id_tipe_kendaraan ORDER BY lc.id DESC LIMIT 1";
    $data['details'] = $this->db->query("SELECT id_tipe_kendaraan,tipe_ahm,IFNULL(($harga_terakhir),0) AS harga_terakhir,0 as ceklist FROM ms_tipe_kendaraan tk ORDER BY id_tipe_kendaraan ASC")->result();
    // send_json($data);
    $this->template($data);
  }

  public function save()
  {
    $post = $this->input->post();
    $id = $this->get_id();
    // send_json($post);
    $data   = [
      'id' => $id,
      'tgl_mulai_berlaku'     => date_ymd($post['tgl_mulai_berlaku']),
      'created_at'  => waktu_full(),
      'created_by'  => user()->id_user
    ];

    foreach ($post['details'] as $dt) {
      $ins_details[] = [
        'id' => $id,
        'id_tipe_kendaraan' => $dt['id_tipe_kendaraan'],
        'nominal' => $dt['nominal'],
      ];
    }
    // $tes = ['data' => $data, 'ins_detail' => $ins_details];
    // send_json($tes);
    $this->db->trans_begin();

    $this->db->insert('ms_labour_cost', $data);
    $this->db->insert_batch('ms_labour_cost_tipe', $ins_details);
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
        'link' => base_url('master/labour_cost')
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
    $row = $this->m_lc->fetchData($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']    = "form";
      $data['mode']    = "detail";
      $data['details'] = $this->m_lc->detailLC($filter)->result();
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
  function get_id()
  {
    $get_data  = $this->db->query("SELECT id FROM ms_labour_cost ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $new = sprintf("%'.05d", $row->id + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_labour_cost', ['id' => $new])->num_rows();
        if ($cek > 0) {
          $new = sprintf("%'.05d", $new + 1);
          $i   = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new   = '00001';
    }
    return strtoupper($new);
  }
}
