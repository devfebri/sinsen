<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_overview_customer extends CI_Controller
{

  var $folder =   "dealer";
  var $page   =   "h2_dealer_overview_customer";
  var $title  =   "Overview Customer";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_dealer_customer_list_model');
    //===== Load Library =====		

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
    }
    ini_set('display_errors', 0);
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
      $data['isi']       = $this->page;
      $data['title']     = $this->title;
      $data['set']       = "view";
      $tgl1 = $this->input->get('tgl1');
      $tgl2 = $this->input->get('tgl2');
      $tgl3 = $this->input->get('tgl3');
      $tgl4 = $this->input->get('tgl4');
      $data['kendaraan'] = $this->H2_dealer_customer_list_model->getDataKendaraan();
      $data['pekerjaan'] = $this->H2_dealer_customer_list_model->getDataPekerjaan();
      
      if ($tgl1 != NULL & $tgl2 != NULL) {
        $data['chart'] = true;
        $data['grafikActivePassive'] = $this->H2_dealer_customer_list_model->grafikActivePassive($tgl1,$tgl2);
        $data['frequencyOfVisit'] = $this->H2_dealer_customer_list_model->frequencyOfVisit($tgl1,$tgl2);
        $data['salesAbility'] = $this->H2_dealer_customer_list_model->salesAbility($tgl1,$tgl2);
      } else {
        $data['chart'] = false;
        $data['grafikActivePassive'] = [];
      }

      if ($tgl3 != NULL & $tgl4 != NULL) {
        $data['chart2'] = true;
        $data['grafikActivePassiveAfter'] = $this->H2_dealer_customer_list_model->grafikActivePassiveAfter($tgl3,$tgl4);
        $data['frequencyOfVisitAfter'] = $this->H2_dealer_customer_list_model->frequencyOfVisitAfter($tgl3,$tgl4);
        $data['salesAbilityAfter'] = $this->H2_dealer_customer_list_model->salesAbilityAfter($tgl3,$tgl4);
      } else {
        $data['chart2'] = false;
        $data['grafikActivePassiveAfter'] = [];
      }

      // var_dump()
      $this->template($data);
  }
}
