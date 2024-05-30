<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_prsl extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "dgi_prsl";
  var $title  = "Textfile .PRSL (Manage Parts Sales)";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_dgi_api');
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
      $start = $_GET['start'];
      $end   = $_GET['end'];

      set_time_limit(0);
      ini_set('memory_limit', '5000M');
      ini_set('max_execution_time', 1000000000000);

      $filename = dealer()->kode_dealer_md . '-E20-' . waktu_dgi_file() . '.PRSL';

      header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
      header("Content-Type: application/force-download");
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header("Content-Type: text/plain");
      $filters = [
        'start' => date_ymd($start),
        'end' => date_ymd($end),
      ];

      if ($_GET['po_id'] != '') {
        $filters['po_id'] = $_GET['po_id'];
      }
      $filters['join_part'] = true;
      $data = $this->m_dgi_api->getPartSales($filters)->result();
      // send_json($data);
      $content = '';
      $no = 1;
      foreach ($data as $dt) {
        $content .= $dt->nomor_so . ';';
        $content .= date_dmy($dt->tanggal_so) . ';';
        $content .= $dt->id_customer . ';';
        $content .= $dt->nama_pembeli . ';';
        $content .= $dt->diskon_so . ';';
        $content .= $dt->total_so . ';';
        $content .= $dt->kode_dealer_md . ';';
        $content .= date_time_dmyhis($dt->created_at) . ';';
        $content .= date_time_dmyhis($dt->updated_at) . ';';
        $content .= $dt->id_part . ';';
        $content .= $dt->kuantitas . ';';
        $content .= $dt->satuan . ';';
        $content .= $dt->harga_saat_dibeli . ';';
        $content .= $dt->diskon_value . ';';
        $content .= $dt->diskon_persen . ';';
        $content .= $dt->ppn . ';';
        $content .= $dt->total_so . ';';
        $content .= $dt->uang_muka . ';';
        $content .= $dt->booking_id_reference . ';';
        $content .= date_time_dmyhis($dt->created_at) . ';';
        $content .= date_time_dmyhis($dt->updated_at);
        $no++;
        if ($no <= count($data)) {
          $content .= "\r\n";
        }
      }
      echo $content;
      // fclose($f);
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
