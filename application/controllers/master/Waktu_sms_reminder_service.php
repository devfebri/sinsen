<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Waktu_sms_reminder_service extends CI_Controller
{

  var $folder = "master";
  var $page   = "waktu_sms_reminder_service";
  var $title  = "Waktu SMS Reminder Service";

  public function __construct()
  {
    parent::__construct();
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    //===== Load Library =====
    $this->load->library('upload');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
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
    $data['set']   = "setting";
    $data['bg'] = $this->db->query("SELECT * FROM ms_h23_waktu_reminder_auto_crm")->row();
    // send_json($data);
    $this->template($data);
  }

  public function save()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');

    $data   = [
      'reminder_service_via_sms'   => $this->input->post('reminder_service_via_sms'),
      'contact_customer_service_via_sms'   => $this->input->post('contact_customer_service_via_sms')
    ];

    // echo json_encode($data);
    // exit;
    $this->db->trans_begin();
    $this->db->update('ms_h23_waktu_reminder_auto_crm', $data);
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
      $_SESSION['pesan']   = "Data has been modified successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
