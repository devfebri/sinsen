<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Follow_up_service extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "follow_up_service";
  var $title  = "Follow Up Service";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_crm', 'm_crm');
    //===== Load Library ====='
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
      $status    = '';
      $respon = '';
      $btn_history = "<a data-toggle='tooltip' href='dealer/follow_up_service/service_history?id=$rs->id_follow_up'><button class='btn btn-flat btn-xs btn-info'>Service History</button></a>";
      if ($rs->status == 'open') {
        $status = '<label class="label label-primary">Open</label>';
        $button = $btn_history;
      } elseif ($rs->status == 'confirmed') {
        $status = '<label class="label label-success">Confirmed</label>';
      } elseif ($rs->status == 'closed') {
        $status = '<label class="label label-warning">Closed</label>';
      }
      if ($rs->respon_service == 1) {
        $respon = '<label class="label label-success">OK</label>';
      } elseif ($rs->respon_service == 0) {
        $respon = '<label class="label label-warning">Not OK</label>';
      }

      $sub_array[] = "<a href='dealer/follow_up_service/detail?id=$rs->id_follow_up'>$rs->id_follow_up</a>";
      // $sub_array[] = $rs->tgl_servis;
      // $sub_array[] = $rs->id_work_order;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->nama_lengkap;
      $sub_array[] = $respon;
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
  public function make_query($no_limit = null)
  {
    $start     = $this->input->post('start');
    $length    = $this->input->post('length');
    $limit     = "LIMIT $start,$length";
    $search    = $this->input->post('search')['value'];

    if (isset($_POST["order"])) $order     = $_POST["order"];
    if ($no_limit == 'y') $limit = '';

    $tambah = ['tanggal' => tanggal(), 'days' => 3];

    $filter = [
      'limit'         => $limit,
      'search'        => $search,
      'order'         => isset($_POST['order']) ? $_POST["order"]                : '',
      'start_date' => tanggal(),
      'end_date' => tambah_hari($tambah),
      // 'tgl_follow_up' => isset($_POST['tgl_follow_up']) ? $_POST['tgl_follow_up'] : '',
      // 'id_customer'   => isset($_POST['id_customer']) ? $_POST['id_customer']    : '',
      // 'nama_customer' => isset($_POST['nama_customer']) ? $_POST['nama_customer'] : '',
      // 'tgl_servis'    => isset($_POST['tgl_servis']) ? $_POST['tgl_servis'] : ''
    ];

    return $this->m_crm->fetch_follow_up($filter);
  }

  function get_filtered_data()
  {
    return $this->make_query('y')->num_rows();
  }


  public function service_history()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Service History';
    $data['set']   = "service_history";
    $data['mode']   = "insert_folup";
    $id_follow_up   = $this->input->get('id');
    $filter        = ['id_follow_up' => $id_follow_up];

    $data['row']        = $this->m_crm->getFollowUp($filter)->row();
    $data['reshcedule'] = $this->m_crm->rescheduleFU($id_follow_up);
    // send_json($data);
    $this->template($data);
  }
  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail';
    $data['set']   = "service_history";
    $data['mode']   = "detail";
    $id_follow_up   = $this->input->get('id');
    $filter        = ['id_follow_up' => $id_follow_up];

    $data['row']        = $this->m_crm->getFollowUp($filter)->row();
    $data['reshcedule'] = $this->m_crm->rescheduleFU($id_follow_up);
    // echo json_encode($data);
    // die();
    $this->template($data);
  }

  public function fetch_service_history()
  {
    $fetch_data = $this->make_query_service_history();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $button    = '';
      $btn_history = '<button type="button" onClick = \'return detailWO(' . json_encode($rs) . ')\' class = "btn btn-success btn-xs">Detail WO</button>';;
      $button = $btn_history;

      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->id_work_order;
      $sub_array[] = $rs->keluhan_konsumen;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_service_history(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }
  public function make_query_service_history($no_limit = null)
  {
    $start     = $this->input->post('start');
    $length    = $this->input->post('length');
    $limit     = "LIMIT $start,$length";
    $search    = $this->input->post('search')['value'];

    if (isset($_POST["order"])) $order     = $_POST["order"];
    if ($no_limit == 'y') $limit = '';

    $filter = [
      'limit'       => $limit,
      // 'search'      => $search,
      'order'       => isset($_POST['order']) ? $_POST["order"]            : '',
      'id_customer' => isset($_POST['id_customer']) ? $_POST['id_customer'] : '',
    ];

    return $this->m_crm->fetch_service_history($filter);
  }

  function get_filtered_data_service_history()
  {
    return $this->make_query_service_history('y')->num_rows();
  }

  public function save_result()
  {
    $waktu          = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl            = gmdate("Y-m-d", time() + 60 * 60 * 7);
    // $tgl = '2020-03-03';
    $login_id       = $this->session->userdata('id_user');
    $id_follow_up   = $this->input->post('id_follow_up');
    $respon_service = $this->input->post('respon_service');
    $status         = 'confirmed';

    $filter = ['id_user' => $login_id];
    $kry = $this->m_crm->getKry($filter);
    if ($kry->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data Karyawan tidak ditemukan !'];
      echo json_encode($result);
      die();
    } else {
      $kry = $kry->row();
    }
    $upd   = [
      'id_follow_up'             => $id_follow_up,
      'respon_service'           => $this->input->post('respon_service'),
      'id_karyawan_dealer_folup' => $kry->id_karyawan_dealer,
      'status'                   => $status,
      'updated_at'               => $waktu,
      'updated_by'               => $login_id
    ];
    $ins_history   = [
      'id_follow_up'             => $id_follow_up,
      'id_karyawan_dealer_folup' => $kry->id_karyawan_dealer,
      'status'                   => $status,
      'tgl_follow_up'            => $tgl
    ];

    // $tes = ['upd' => $upd, 'ins_history' => $ins_history];
    // echo json_encode($tes);
    // exit;
    $this->db->trans_begin();
    $this->db->update('tr_h2_follow_up_after_service', $upd, ['id_follow_up' => $id_follow_up]);
    $this->db->insert('tr_h2_follow_up_after_service_history', $ins_history);
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
        'link' => base_url('dealer/follow_up_service')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
  public function save_reminder()
  {
    $waktu          = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl            = gmdate("Y-m-d", time() + 60 * 60 * 7);
    // $tgl = '2020-03-03';
    $login_id       = $this->session->userdata('id_user');
    $id_follow_up   = $this->input->post('id_follow_up');
    $tgl_follow_up = $this->input->post('tgl_follow_up');
    $keterangan = $this->input->post('keterangan');
    $status         = 'not confirmed';

    $filter = ['id_user' => $login_id];
    $kry = $this->m_crm->getKry($filter);
    if ($kry->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data Karyawan tidak ditemukan !'];
      echo json_encode($result);
      die();
    } else {
      $kry = $kry->row();
    }
    $upd   = [
      'id_follow_up'  => $id_follow_up,
      'tgl_follow_up' => $tgl_follow_up,
      'updated_at'    => $waktu,
      'updated_by'    => $login_id
    ];
    $ins_history   = [
      'id_follow_up'             => $id_follow_up,
      'tgl_follow_up'            => $tgl,
      'id_karyawan_dealer_folup' => $kry->id_karyawan_dealer,
      'keterangan'                   => $keterangan,
      'status'                   => $status,
    ];

    // $tes = ['upd' => $upd, 'ins_history' => $ins_history];

    // echo json_encode($tes);
    // exit;
    $this->db->trans_begin();
    $this->db->update('tr_h2_follow_up_after_service', $upd, ['id_follow_up' => $id_follow_up]);
    $this->db->insert('tr_h2_follow_up_after_service_history', $ins_history);
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
        'link' => base_url('dealer/follow_up_service')
      ];
      $_SESSION['pesan']   = "Data has been processed successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
  public function save_close()
  {
    $waktu          = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl            = gmdate("Y-m-d", time() + 60 * 60 * 7);
    // $tgl = '2020-03-03';
    $login_id       = $this->session->userdata('id_user');
    $id_follow_up   = $this->input->post('id_follow_up');
    $status         = 'not confirmed';

    $filter = ['id_user' => $login_id];
    $kry = $this->m_crm->getKry($filter);
    if ($kry->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data Karyawan tidak ditemukan !'];
      echo json_encode($result);
      die();
    } else {
      $kry = $kry->row();
    }
    $upd   = [
      'id_follow_up' => $id_follow_up,
      'status'       => 'closed',
      'updated_at'   => $waktu,
      'updated_by'   => $login_id
    ];
    $ins_history   = [
      'id_follow_up'             => $id_follow_up,
      'id_karyawan_dealer_folup' => $kry->id_karyawan_dealer,
      'status'                   => $status,
      'tgl_follow_up'            => $tgl
    ];


    // $tes = ['upd' => $upd, 'ins_history' => $ins_history];

    // echo json_encode($tes);
    // exit;
    $this->db->trans_begin();
    $this->db->update('tr_h2_follow_up_after_service', $upd, ['id_follow_up' => $id_follow_up]);
    $this->db->insert('tr_h2_follow_up_after_service_history', $ins_history);

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
        'link' => base_url('dealer/follow_up_service')
      ];
      $_SESSION['pesan']   = "Data has been processed successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
