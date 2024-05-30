<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lap_pendapatan_harian_servis extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "lap_pendapatan_harian_servis";
  var $title  =   "Laporan Pendapatan Harian Servis";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    $this->load->model('m_h2_dealer_laporan2', 'm_lap2');
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

      $filter['tgl_transaksi'] = $params->tanggal;
      $data['details'] = $this->m_lap->getPendapatanHarianServis($filter)->result();
      $data['details_sales_part'] = $this->m_lap->getPendapatanHarianServisSalesParts($filter)->result();
      // send_json($data);

      if ($params->tipe == 'preview') {
        $this->load->library('pdf');
        $mpdf                           = $this->pdf->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $html = $this->load->view($this->folder . '/' . $this->page, $data, true);
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
  public function cetak2old()
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 900);

    $params = json_decode($_GET['params'], true);
    // $params = ['tanggal' => $this->input->get('tgl'), 'tipe' => 'preview'];

    $data['set']   = 'cetak';
    $data['title'] = $this->title;
    $data['params'] = $params;

    $filter['tgl_transaksi'] = $params['tanggal'];
    $details = $this->m_lap2->getPendapatanHarianServis($filter)->result();
    $res_details = [];
    foreach ($details as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $res_details[] = $dtl;
    }
    $data['details'] = $res_details;
    $details_sales_part = $this->m_lap2->getPendapatanHarianServisSalesPartsDirect($filter)->result();
    $res_details_sales_part = [];
    foreach ($details_sales_part as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $kelompok_oil = $this->m_lap2->kelompok_oil() . ",'FED OIL'";
      $dtl->parts_nsc = $this->m_bil->getNSCParts(['no_nsc' => $dtl->no_nsc, 'kelompok_part_not_in' => $kelompok_oil])->result();
      if (count($dtl->parts_nsc) > 0) {
        $res_details_sales_part[] = $dtl;
      }
    }
    $data['details_sales_part'] = $res_details_sales_part;

    $details_sales_oli = $this->m_lap2->getPendapatanHarianServisSalesOliDirect($filter)->result();
    $res_details_sales_oli = [];
    foreach ($details_sales_oli as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $kelompok_oli = $this->m_lap2->kelompok_oil();
      $dtl->parts_nsc = $this->m_bil->getNSCParts(['no_nsc' => $dtl->no_nsc, 'kelompok_part_in' => $kelompok_oli])->result();
      if (count($dtl->parts_nsc) > 0) {
        $res_details_sales_oli[] = $dtl;
      }
    }
    $data['details_sales_oli'] = $res_details_sales_oli;

    $filter['tanggal'] = $params['tanggal'];
    $uang_jaminan = $this->m_bil->get_uang_jaminan($filter)->result();
    $details_uj = [];
    foreach ($uang_jaminan as $uj) {
      $fl['no_inv_uang_jaminan'] = $uj->no_inv_uang_jaminan;
      $uj->metode_bayar = $this->m_bil->get_uang_jaminan_metode($fl)->result();
      $fl['id_booking'] = $uj->id_booking;
      $uj->parts = $this->m_bil->getRequestDocumentParts($fl)->result();
      $details_uj[] = $uj;
    }
    $data['details_uj'] = $details_uj;


    if (isset($_GET['cek'])) {
      send_json($data);
    }

    if ($params['tipe'] == 'preview') {
      $this->load->library('pdf');
      $mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $html = $this->load->view($this->folder . '/' . $this->page . '_cetak', $data, true);
      $mpdf->WriteHTML($html);
      $output = $this->page . '.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      $this->load->view($this->folder . '/' . $this->page . '_cetak', $data);
    }
  }
  public function cetak2()  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 900);

    $params = json_decode($_GET['params'], true);

    $data['set']   = 'cetak';
    $data['title'] = $this->title;
    $data['params'] = $params;

    // $filter['tgl_transaksi'] = $params['tanggal'];
    $filter['tgl_transaksi_awal'] = $params['tanggal'];
    $filter['tgl_transaksi_akhir'] = $params['tanggal_akhir'];
    $details = $this->m_lap2->getWOClosedByFilter($filter)->result();
    $res_details = [];
    foreach ($details as $dtl) {
      $freceipt['id_work_order'] = $dtl->id_work_order;
      $subdetails = $this->m_lap2->getPendapatanHarianServis($freceipt)->row();
      if ($subdetails != NULL) {
        $subdetails->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_referensi' => $dtl->id_work_order])->result();
        $res_details[] = $subdetails;
      } else {
        $fd = ['id_work_order' => $dtl->id_work_order];
        $res_details[] = $this->m_lap2->getDetailWOClosedByFilter($fd)->row();
      }
    }
    // send_json($res_details);
    $data['details'] = $res_details;
    $details_sales_part = $this->m_lap2->getPendapatanHarianServisSalesPartsDirect($filter)->result();
    $res_details_sales_part = [];
    foreach ($details_sales_part as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $kelompok_oil = $this->m_lap2->kelompok_oil() . ",'FED OIL'";
      $dtl->parts_nsc = $this->m_bil->getNSCParts(['no_nsc' => $dtl->no_nsc, 'kelompok_part_not_in' => $kelompok_oil])->result();
      if (count($dtl->parts_nsc) > 0) {
        $res_details_sales_part[] = $dtl;
      }
    }
    $data['details_sales_part'] = $res_details_sales_part;

    $details_sales_oli = $this->m_lap2->getPendapatanHarianServisSalesOliDirect($filter)->result();
    $res_details_sales_oli = [];
    foreach ($details_sales_oli as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $kelompok_oli = $this->m_lap2->kelompok_oil() . ",'FED OIL'";
      $dtl->parts_nsc = $this->m_bil->getNSCParts(['no_nsc' => $dtl->no_nsc, 'kelompok_part_in' => $kelompok_oli])->result();
      if (count($dtl->parts_nsc) > 0) {
        $res_details_sales_oli[] = $dtl;
      }
    }
    $data['details_sales_oli'] = $res_details_sales_oli;

    $filter['tanggal'] = $params['tanggal'];
    $uang_jaminan = $this->m_bil->get_uang_jaminan($filter)->result();
    $details_uj = [];
    foreach ($uang_jaminan as $uj) {
      $fl['no_inv_uang_jaminan'] = $uj->no_inv_uang_jaminan;
      $uj->metode_bayar = $this->m_bil->get_uang_jaminan_metode($fl)->result();
      $fl['id_booking'] = $uj->id_booking;
      $uj->parts = $this->m_bil->getRequestDocumentParts($fl)->result();
      $details_uj[] = $uj;
    }
    $data['details_uj'] = $details_uj;


    if (isset($_GET['cek'])) {
      send_json($data['details']);
    }

    if ($params['tipe'] == 'preview') {
      $this->load->library('pdf');
      $mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $html = $this->load->view($this->folder . '/' . $this->page . '_cetak', $data, true);
      $mpdf->WriteHTML($html);
      $output = $this->page . '.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      $this->load->view($this->folder . '/' . $this->page . '_cetak', $data);
    }
  }
  
  public function cetak_nonfed()  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 900);

    $params = json_decode($_GET['params'], true);

    $data['set']   = 'cetak';
    $data['title'] = $this->title;
    $data['params'] = $params;

    // $filter['tgl_transaksi'] = $params['tanggal'];
    $filter['tgl_transaksi_awal'] = $params['tanggal'];
    $filter['tgl_transaksi_akhir'] = $params['tanggal_akhir'];
    $details = $this->m_lap2->getWOClosedByFilter($filter)->result();
    $res_details = [];
    foreach ($details as $dtl) {
      $freceipt['id_work_order'] = $dtl->id_work_order;
      $subdetails = $this->m_lap2->getPendapatanHarianServis($freceipt)->row();
      if ($subdetails != NULL) {
        $subdetails->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_referensi' => $dtl->id_work_order])->result();
        $res_details[] = $subdetails;
      } else {
        $fd = ['id_work_order' => $dtl->id_work_order];
        $res_details[] = $this->m_lap2->getDetailWOClosedByFilter($fd)->row();
      }
    }
    // send_json($res_details);
    $data['details'] = $res_details;
    $details_sales_part = $this->m_lap2->getPendapatanHarianServisSalesPartsDirect_non_fed($filter)->result();
    $res_details_sales_part = [];
    foreach ($details_sales_part as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $kelompok_oil = $this->m_lap2->kelompok_oil();
      $dtl->parts_nsc = $this->m_bil->getNSCParts(['no_nsc' => $dtl->no_nsc, 'kelompok_part_not_in' => $kelompok_oil])->result();
      if (count($dtl->parts_nsc) > 0) {
        $res_details_sales_part[] = $dtl;
      }
    }
    $data['details_sales_part'] = $res_details_sales_part;

    $details_sales_oli = $this->m_lap2->getPendapatanHarianServisSalesOliDirect_non_fed($filter)->result();
    $res_details_sales_oli = [];
    foreach ($details_sales_oli as $dtl) {
      $dtl->metode_bayar = $this->m_bil->getKwitansiMetodeBayar(['id_receipt' => $dtl->id_receipt])->result();
      $kelompok_oli = $this->m_lap2->kelompok_oil();
      $dtl->parts_nsc = $this->m_bil->getNSCParts(['no_nsc' => $dtl->no_nsc, 'kelompok_part_in' => $kelompok_oli])->result();
      if (count($dtl->parts_nsc) > 0) {
        $res_details_sales_oli[] = $dtl;
      }
    }
    $data['details_sales_oli'] = $res_details_sales_oli;

    $filter['tanggal'] = $params['tanggal'];
    $uang_jaminan = $this->m_bil->get_uang_jaminan($filter)->result();
    $details_uj = [];
    foreach ($uang_jaminan as $uj) {
      $fl['no_inv_uang_jaminan'] = $uj->no_inv_uang_jaminan;
      $uj->metode_bayar = $this->m_bil->get_uang_jaminan_metode($fl)->result();
      $fl['id_booking'] = $uj->id_booking;
      $uj->parts = $this->m_bil->getRequestDocumentParts($fl)->result();
      $details_uj[] = $uj;
    }
    $data['details_uj'] = $details_uj;


    if (isset($_GET['cek'])) {
      send_json($data['details']);
    }

    if ($params['tipe'] == 'preview') {
      $this->load->library('pdf');
      $mpdf                           = $this->pdf->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $html = $this->load->view($this->folder . '/' . $this->page . '_cetak_non', $data, true);
      $mpdf->WriteHTML($html);
      $output = $this->page . '.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      $this->load->view($this->folder . '/' . $this->page . '_cetak_non', $data);
    }
  }
}
