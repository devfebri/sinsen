<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_md_reporting_follow_up extends CI_Controller
{

  var $folder =   "h2";
  var $page   =   "h2_md_reporting_follow_up";
  var $title  =   "Reporting Follow Up H23";

  
  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_md_customer_list_model');
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
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $data['dealer']=  $this->H2_md_customer_list_model->getDataDealer();
      $this->template($data);
  }

  public function downloadReport(){
    $data['start_date']= $start_date= $this->input->post('tgl1');
	  $data['end_date']  = $end_date	= $this->input->post('tgl2');
    $data['id_dealer']  = $id_dealer	= $this->input->post('dealer');
    $data['report'] = $report = $this->H2_md_customer_list_model->reportFollowUp($id_dealer, $start_date,$end_date);
    // var_dump($id_dealer);
    // die();
    
    if($_POST['process']=='excel'){
        $this->load->view("h2/laporan/temp_h2_md_reporting_follow_up",$data);
    }
  }
}
