<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_integrate_finance_dealer extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "h2_integrate_finance_dealer";
  var $title  = "Integrate with Dealer Finance System";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_billing', 'm_bil');
    //===== Load Library =====		
    $this->load->library('pdf');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
    }
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
    if (isset($_GET['cetak'])) {
      // set_time_limit(500);
      $start_date = $_GET['start_date'];
      $end_date   = $_GET['end_date'];
      $filter     = $_GET['filter'];
      set_time_limit(0);
      ini_set('memory_limit', '5000M');
      ini_set('max_execution_time', 1000000000000);
      $delimiter = ";";
      $filename = "Integrate_with_dealer_finance_system" . date('Y-m-d') . ".csv";
      header("Content-type: text/x-csv");
      header('Content-Disposition: attachment; filename=' . $filename);

      $f = fopen("php://output", "w");

      $filters = ['start_tgl_njb' => $start_date, 'end_tgl_njb' => $end_date, 'group_type' => 1];

      if ($filter == 'nsc') {
        $data = [];
      } else {
        $data = $this->m_bil->getNJB($filters)->result();
      }
      foreach ($data as $dt) {
        $lineData = array(
          $dt->no_njb,
          $dt->tgl_njb,
          '',
          $dt->desk_type,
          (int) $dt->harga_net,
          1,
          (int) $dt->diskon,
          (int) $dt->harga_net
        );
        fputcsv($f, $lineData, $delimiter);
      }
      // send_json($lineData);
      $filters = ['start_tgl_nsc' => $start_date, 'end_tgl_nsc' => $end_date];
      if ($filter == 'njb') {
        $data = [];
      } else {
        $data = $this->m_bil->getNSCPartsHeader($filters)->result();
      }
      foreach ($data as $dt) {
        $diskon = 0;
        $tot = ($dt->harga_beli - $diskon) * $dt->qty;
        $lineData = array(
          $dt->no_nsc,
          $dt->tgl_nsc,
          $dt->id_part,
          $dt->nama_part,
          $dt->harga_beli,
          $dt->qty,
          $diskon,
          $tot,
        );
        fputcsv($f, $lineData, $delimiter);
      }
      fclose($f);
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
