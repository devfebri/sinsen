<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H1_d_penerimaan_kas_harian extends CI_Controller
{

  var $folder = "dealer/laporan";
  var $page   = "h1_d_penerimaan_kas_harian";
  var $title  = "Laporan Penerimaan Kas Harian";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h1_dealer_lap_finance', 'm_fin');
    $this->load->helper('romawi');
    $this->load->helper('tgl_indo');
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
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 900);

      $params = json_decode($_GET['params']);

      $data['set']   = 'cetak';
      $data['title'] = $this->title;
      $data['params'] = $params;
      // send_json($data);
      $filter = [
        'tgl_pembayaran' => date_ymd($params->tgl_pembayaran),
        'id_dealer' => dealer()->id_dealer,
      ];
      $result = $this->m_fin->getLaporanPenerimaanKasHarian($filter);
      $data['dp_pelunasan'] = $result['dp_pelunasan'];
      $data['tjs'] = $result['tjs'];
      // send_json($data);
      if ($params->tipe == 'preview') {
        $this->load->library('pdf');
        $mpdf                           = $this->pdf->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        // $mpdf->AddPage('L');
        $html = $this->load->view($this->folder . '/' . $this->page, $data, true);
        // $html = $this->load->view($this->folder . '/' . $this->page, $data);
        $mpdf->WriteHTML($html);
        $output = $this->page . '.pdf';
        $mpdf->Output("$output", 'I');
      } else {
        $this->load->view($this->folder . '/' . $this->page, $data);
      }
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }

  function tes()
  {
    $tgl = $this->input->get('tgl');
    $filter = [
      'tgl_pembayaran' => $tgl,
      'id_dealer' => dealer()->id_dealer,
    ];
    $result = $this->m_fin->getLaporanPenerimaanKasHarian($filter);
    send_json($result);
  }
}
