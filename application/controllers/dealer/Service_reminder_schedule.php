<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Detail History Disimpan Setelah Proses

class Service_reminder_schedule extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "service_reminder_schedule";
  var $title  = "Service Reminder Schedule";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_crm', 'm_crm');
    $this->load->model('m_reminder', 'm_rem');
    //===== Load Library ====='
    // $this->load->library('upload');
    $this->load->helper('tgl_indo');
    // $this->load->model('notifikasi_model', 'notifikasi');

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
    $tgl       = tanggal();
    foreach ($fetch_data->result() as $rs) {
      $sub_array           = array();
      $button              = '';
      $status_reminder_sms = '';
      $status_contact_sms = '';
      $status_contact_call = '';
      $status_next_servis  = '';
      $btn_contact      = "<a data-toggle = 'tooltip' href = 'dealer/service_reminder_schedule/contact_customer?id=$rs->id_service_reminder'><button class = 'btn btn-flat btn-xs btn-info'>Contact Customer</button></a>";
      $btn_reminder      = "<button type='button' class = 'btn btn-flat btn-xs btn-primary' onclick=\"showModalReminderSms('$rs->id_service_reminder')\">Reminder SMS</button>";

      //Cek SMS Reminder
      if ($rs->status_reminder_sms == null) {
        if ($rs->id_work_order_berikutnya == null && $rs->tgl_reminder_sms == $tgl) {
          // $button .= $btn_reminder;
        }
      } else if ($rs->status_reminder_sms == 'Terkirim') {
        $status_reminder_sms = '<label class="label label-success">Terkirim</label>';
      } else if ($rs->status_reminder_sms == 'Tidak Terkirim') {
        $status_reminder_sms = '<label class="label label-danger">Tidak Terkirim</label>';
      }

      if ($rs->status_contact_sms == 'Terkirim') {
        $status_contact_sms = '<label class="label label-success">Terkirim</label>';
      } else if ($rs->status_contact_sms == 'Tidak Terkirim') {
        $status_contact_sms = '<label class="label label-danger">Tidak Terkirim</label>';
      }

      // Cek Contact Via Call
      if ($rs->status_contact_call == null) {
        if ($tgl == $rs->tgl_contact_call) {
          $button .= $btn_contact;
        }
      } else {
        if ($rs->status_contact_call == 'terhubung') {
          $status_contact_call = "<label class=\"label label-success\">$rs->status_contact_call</label>";
        } else {
          $status_contact_call = "<label class=\"label label-warning\">$rs->status_contact_call</label>";
        }
      }

      if ($rs->id_work_order_berikutnya == null) {
        $status_next_servis = '<label class="label label-warning">Belum</label>';
        //   $button = $btn_;
        // } elseif ($rs->status == 'confirmed') {
        //   $status = '<label class="label label-success">Confirmed</label>';
        // } elseif ($rs->status == 'closed') {
        //   $status = '<label class="label label-warning">Closed</label>';
        // }
        // if ($rs->respon_service == 1) {
        //   $respon = '<label class="label label-success">OK</label>';
        // } elseif ($rs->respon_service == 0) {
        //   $respon = '<label class="label label-warning">Not OK</label>';
      }

      // $sub_array[] = "<a href='dealer/follow_up_service/detail?id=$rs->id_service_reminder'>$rs->id_customer</a>";
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = date_dmy($rs->tgl_servis_sebelumnya);
      $sub_array[] = $rs->tipe_servis_sebelumnya;
      $sub_array[] = date_dmy($rs->tgl_servis_berikutnya);
      $sub_array[] = $rs->tipe_servis_berikutnya;
      $sub_array[] = $status_next_servis;
      $sub_array[] = $status_reminder_sms;
      $sub_array[] = $status_contact_sms;
      $sub_array[] = $status_contact_call;
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
      'order'         => isset($_POST['order']) ? $_POST["order"] : '',
      // 'start_date' => tanggal(),
      // 'end_date' => tambah_hari($tambah),
      // 'id_customer'   => isset($_POST['id_customer']) ? $_POST['id_customer']    : '',
      // 'nama_customer' => isset($_POST['nama_customer']) ? $_POST['nama_customer'] : '',
      // 'tgl_servis'    => isset($_POST['tgl_servis']) ? $_POST['tgl_servis'] : ''
    ];

    return $this->m_crm->fetch_srv_reminder($filter);
  }

  function get_filtered_data()
  {
    return $this->make_query('y')->num_rows();
  }


  public function contact_customer()
  {
    $data['isi']         = $this->page;
    $data['title']       = 'Contact Customer';
    $data['set']         = "contact_customer";
    $data['mode']        = "insert_contact";
    $id_service_reminder = $this->input->get('id');

    $filter        = ['id_service_reminder' => $id_service_reminder, 'status_null' => 1];

    $data['row']        = $this->m_crm->getServiceReminder($filter)->row();
    $data['reshcedule'] = $this->m_crm->rescheduleServiceReminder($id_service_reminder);
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

      $sub_array[] = date_dmy($rs->tgl_servis);
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
    $login_id            = $this->session->userdata('id_user');
    $id_service_reminder = $this->input->post('id_service_reminder');
    $status_contact_sms  = $this->input->post('status_contact_sms');

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
      'id_service_reminder'  => $id_service_reminder,
      'booking_status'       => $this->input->post('booking_status'),
      'alasan_tidak_booking' => isset($_POST['alasan_tidak_booking']) ? $_POST['alasan_tidak_booking'] : null,
      'id_karyawan_contact'  => $kry->id_karyawan_dealer,
      // 'status_contact_sms'   => $status_contact_sms,
      'status_contact_call'  => 'terhubung',
      'updated_at'           => $waktu,
      'status'               => 'closed',
      'updated_by'           => $login_id
    ];

    $filter       = ['id_service_reminder' => $id_service_reminder];
    $get_reminder = $this->m_crm->getServiceReminder($filter);
    if ($get_reminder->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data service reminder tidak ditemukan !'];
      echo json_encode($result);
      die();
    } else {
      $rem = $get_reminder->row();
    }
    $ins_history   = [
      'id_service_reminder' => $id_service_reminder,
      'status_reminder_sms' => $rem->status_reminder_sms,
      'tgl_reminder_sms'    => $rem->tgl_reminder_sms,
      'tgl_contact_call'    => $rem->tgl_contact_call,
      'tgl_contact_sms'     => $rem->tgl_contact_sms,
      'status_contact_call' => 'terhubung',
      'status_contact_sms'  => $status_contact_sms,
      'id_karyawan_contact' => $kry->id_karyawan_dealer
    ];

    // $tes = ['upd' => $upd, 'ins_history' => $ins_history];
    // echo json_encode($tes);
    // exit;

    $this->db->trans_begin();
    $this->db->update('tr_h2_service_reminder', $upd, ['id_service_reminder' => $id_service_reminder]);
    $this->db->insert('tr_h2_service_reminder_history', $ins_history);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan'  => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link'   => base_url('dealer/service_reminder_schedule')
      ];
      $_SESSION['pesan'] = "Data has been saved successfully";
      $_SESSION['tipe']  = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function save_status_call()
  {
    $waktu               = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $tgl                 = gmdate("Y-m-d", time() + 60 * 60 * 7);
    // $tgl              = '2020-03-03';
    $login_id            = $this->session->userdata('id_user');
    $id_service_reminder = $this->input->post('id_service_reminder');
    $status_contact_sms  = $this->input->post('status_contact_sms');
    $tgl_contact_call    = $this->input->post('tgl_contact_call');
    $status_call         = $this->input->post('status_call');
    $keterangan         = $this->input->post('keterangan');

    $filter = ['id_user' => $login_id];
    $kry = $this->m_crm->getKry($filter);
    if ($kry->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data Karyawan tidak ditemukan !'];
      echo json_encode($result);
      die();
    } else {
      $kry = $kry->row();
    }

    if (isset($_POST['tgl_contact_call'])) {
      $upd   = [
        'tgl_contact_call' => $tgl_contact_call,
        'updated_at'       => $waktu,
        'updated_by'       => $login_id
      ];
    } else {
      $upd   = [
        'status'              => 'closed',
        'updated_at'          => $waktu,
        'updated_by'          => $login_id,
        'status_contact_call' => $status_call,
        'status_contact_sms'  => $status_contact_sms,
        'id_karyawan_contact' => $kry->id_karyawan_dealer
      ];
    }

    $filter = ['id_service_reminder' => $id_service_reminder];
    $get_reminder = $this->m_crm->getServiceReminder($filter);
    if ($get_reminder->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data service reminder tidak ditemukan !'];
      echo json_encode($result);
      die();
    } else {
      $rem = $get_reminder->row();
    }

    $ins_history   = [
      'id_service_reminder' => $id_service_reminder,
      'status_reminder_sms' => $rem->status_reminder_sms,
      'tgl_reminder_sms'    => $rem->tgl_reminder_sms,
      'tgl_contact_call'    => $rem->tgl_contact_call,
      'tgl_contact_sms'     => $rem->tgl_contact_sms,
      'status_contact_call' => $status_call,
      'status_contact_sms'  => $status_contact_sms,
      'keterangan'          => $keterangan,
      'id_karyawan_contact' => $kry->id_karyawan_dealer
    ];

    // $tes = ['upd' => $upd, 'ins_history' => $ins_history];
    // echo json_encode($tes);
    // exit;

    $this->db->trans_begin();
    $this->db->update('tr_h2_service_reminder', $upd, ['id_service_reminder' => $id_service_reminder]);
    $this->db->insert('tr_h2_service_reminder_history', $ins_history);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan'  => 'Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/service_reminder_schedule')
      ];
      $_SESSION['pesan'] = "Data has been processed successfully";
      $_SESSION['tipe']  = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function export()
  {
    if (isset($_GET['cetak'])) {
      set_time_limit(500);
      ini_set('memory_limit', '5000M');
      ini_set('max_execution_time', 1000000000000);
      $data['set']       = 'export';
      $data['start_date']   = $this->input->get('start_date');
      $data['end_date']   = $this->input->get('end_date');
      $filter = ['set_periode' => 1, 'start_date' => $data['start_date'], 'end_date' => $data['end_date']];

      $data['details'] = $this->m_crm->getServiceReminder($filter)->result();
      // send_json($data);
      $this->load->view('dealer/' . $this->page . '_cetak', $data);
    } else {
      $data['isi']       = $this->page;
      $data['title']     = 'Export ' . $this->title;
      $data['set']       = "export";
      $this->template($data);
    }
  }

  public function insert()
  {
    $data['isi']       = $this->page;
    $data['title']     = $this->title;
    $data['mode']       = "insert";
    $data['set']       = "form";
    $this->template($data);
  }

  public function save()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $post     = $this->input->post();
    $id_dealer     = dealer()->id_dealer;
    $ins_reminder = [
      'id_dealer'              => $id_dealer,
      'id_customer'            => $post['id_customer'],
      'tgl_reminder_sms'       => $post['tgl_reminder_sms'],
      'tgl_contact_sms'        => $post['tgl_contact_sms'],
      'tgl_contact_call'       => $post['tgl_contact_call'],
      'tgl_servis_berikutnya'  => $post['tgl_servis'],
      'tipe_servis_berikutnya' => $post['id_type'],
      'created_at'             => $waktu,
      'created_by'             => $login_id,
      'reminder_from'          => 'create_new',
    ];
    // $tes = ['ins_reminder' => $ins_reminder];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_service_reminder', $ins_reminder);
    // $this->notifikasi->insert([
    //   'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_service_reminder')->get()->row()->id_notif_kat,
    //   'judul'        => 'Service Reminder Customer',
    //   'pesan'        => "Terdapat reminder service via SMS yang dilakukan tanggal : {$post['tgl_reminder_sms']}, dan perlu dilakukan contact via call pada tanggal : {$post['tgl_contact_call']} untuk ID Customer : {$post['id_customer']}",
    //   'link'         => "dealer/service_reminder_schedule",
    //   'id_referensi' => $post['id_customer'],
    //   'id_dealer'    => $id_dealer,
    //   'show_popup'   => false,
    // ]);
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
        'link' => base_url('dealer/service_reminder_schedule')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }
}
