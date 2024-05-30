<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_dealer_monitoring_dashboard_follow_up extends CI_Controller
{

  var $folder =   "dealer";
  var $page   =   "h2_dealer_monitoring_dashboard_follow_up";
  var $title  =   "Monitoring Dashboard Follow Up";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    $this->db_crm       = $this->load->database('db_crm', true);
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
      $data['customer_db'] = $this->H2_dealer_customer_list_model->card_overview_customer_db();
      $data['belum_fu'] = $this->H2_dealer_customer_list_model->card_overview_belum_fu();
      $data['customer_reminder']    = $this->H2_dealer_customer_list_model->card_overview_customer_reminder();
      $data['contacted'] = $this->H2_dealer_customer_list_model->card_overview_contacted(); 
      $data['booking_service'] = $this->H2_dealer_customer_list_model->card_overview_booking_service(); 
      $data['actual_service'] = $this->H2_dealer_customer_list_model->card_overview_actual_service(); 
      $data['channel_effectiveness'] = $this->H2_dealer_customer_list_model->channel_effectiveness();
      $data['channelGroupById1'] = $this->H2_dealer_customer_list_model->channelGroupById1();
      $data['channelGroupById2'] = $this->H2_dealer_customer_list_model->channelGroupById2();
      $data['channelGroupById3'] = $this->H2_dealer_customer_list_model->channelGroupById3();
      $data['channelGroupById4'] = $this->H2_dealer_customer_list_model->channelGroupById4();
      $data['channelGroupById5'] = $this->H2_dealer_customer_list_model->channelGroupById5();
      $data['channelGroupById6'] = $this->H2_dealer_customer_list_model->channelGroupById6();
      $data['channelGroupById7'] = $this->H2_dealer_customer_list_model->channelGroupById7();
      $data['channelGroupById8'] = $this->H2_dealer_customer_list_model->channelGroupById8();
      $data['channelGroupById9'] = $this->H2_dealer_customer_list_model->channelGroupById9();
      $data['channelGroupById10'] = $this->H2_dealer_customer_list_model->channelGroupById10();
      $data['custFuneling'] = $this->H2_dealer_customer_list_model->custFuneling();
      $data['custFunelingBooking'] = $this->H2_dealer_customer_list_model->custFunelingBooking();
      $data['custFunelingActual'] = $this->H2_dealer_customer_list_model->custFunelingActual();
      $data['grafikLeaderboard'] = $this->H2_dealer_customer_list_model->grafikLeaderboard();
      $data['grafikToJWa'] = $this->H2_dealer_customer_list_model->grafikToJWa();
      $data['grafikToJSMS'] = $this->H2_dealer_customer_list_model->grafikToJSMS();
      $data['grafikToJTelepon'] = $this->H2_dealer_customer_list_model->grafikToJTelepon();
      $data['grafikToJEmail'] = $this->H2_dealer_customer_list_model->grafikToJEmail();
      $this->template($data);
  }
}
