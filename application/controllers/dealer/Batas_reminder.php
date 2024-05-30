<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Batas_reminder extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "batas_reminder";
  var $title  = "Batas Reminder CRM";

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
    $this->load->model('m_h2_crm', 'm_crm');
    $this->load->model('m_h2');
    //===== Load Library =====
    $this->load->library('upload');
    $this->load->library('form_validation');
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
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "form";
    $row    = $this->m_crm->getBatas();
    if ($row->num_rows() > 0) {
      $data['row'] = $row->row();
    }
    $this->template($data);
  }

  public function save()
  {
    $waktu     = gmdate("y-m-d H: i: s", time() + 60 * 60 * 7);
    $tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $id_dealer = $this->m_admin->cari_dealer();
    $cek_batas = $this->db->get_where('ms_h2_batas_waktu', ['id_dealer' => $id_dealer]);
    if ($cek_batas->num_rows() == 0) {
      $ins = [
        'id_dealer' => $id_dealer,
        'h_follow_up_after_service' => $this->input->post('h_follow_up_after_service'),
        'ulang_service_reminder' => $this->input->post('ulang_service_reminder'),
        'ulang_follow_up_after_service' => $this->input->post('ulang_follow_up_after_service'),
      ];
    } else {
      $upd = [
        'h_follow_up_after_service' => $this->input->post('h_follow_up_after_service'),
        'ulang_service_reminder' => $this->input->post('ulang_service_reminder'),
        'ulang_follow_up_after_service' => $this->input->post('ulang_follow_up_after_service'),
      ];
    }

    // echo json_encode($upd);
    // die;
    $this->db->trans_begin();
    if (isset($ins)) {
      $this->db->insert('ms_h2_batas_waktu', $ins);
    }
    if (isset($upd)) {
      $this->db->update('ms_h2_batas_waktu', $upd, ['id_dealer' => $id_dealer]);
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
        'link' => base_url('dealer/batas_reminder')
      ];
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
