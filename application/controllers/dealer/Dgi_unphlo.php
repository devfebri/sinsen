<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_unphlo extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "dgi_unphlo";
  var $title  = "Textfile .UNPHLO (Unpaid HLO)";

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

      $filename = dealer()->kode_dealer_md . '-E20-' . waktu_dgi_file() . '.UNPHLO';

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

      if ($_GET['id_work_order'] != '') {
        $filters['id_work_order'] = $_GET['id_work_order'];
      }
      $filters['join_part'] = true;
      $filters['inv_jaminan_null'] = true;
      $data = $this->m_dgi_api->getPOPart($filters)->result();
      // send_json($data);
      $content = '';
      $no = 1;
      foreach ($data as $dt) {
        $content .= $dt->po_id . ';';
        $content .= date_dmy($dt->tanggal_order) . ';';
        $content .= $dt->id_work_order . ';';
        $content .= $dt->no_buku_khusus_claim_c2 . ';';
        $content .= $dt->no_identitas . ';';
        $content .= $dt->nama_customer . ';';
        $content .= $dt->alamat . ';';
        $content .= $dt->id_provinsi . ';';
        $content .= $dt->id_kabupaten . ';';
        $content .= $dt->id_kecamatan . ';';
        $content .= $dt->id_kelurahan . ';';
        $content .= $dt->kode_pos . ';';
        $content .= $dt->no_hp . ';';
        $content .= $dt->id_tipe_kendaraan . ';';
        $content .= $dt->tahun_produksi . ';';
        $content .= $dt->no_mesin . ';';
        $content .= $dt->no_rangka . ';';
        $content .= $dt->flag_numbering . ';';
        $content .= $dt->vor . ';';
        $content .= $dt->job_return_flag . ';';
        $content .= $dt->kode_dealer_md . ';';
        $content .= date_time_dmyhis($dt->created_at) . ';';
        $content .= date_time_dmyhis($dt->updated_at) . ';';
        $content .= $dt->id_part . ';';
        $content .= $dt->kuantitas . ';';
        $content .= $dt->harga_saat_dibeli . ';';
        $content .= $dt->subtotal . ';';
        $content .= $dt->uang_muka . ';';
        $content .= $dt->sisa_bayar . ';';
        $content .= date_time_dmyhis($dt->created_at) . ';';
        $content .= date_time_dmyhis($dt->updated_at) . '';
        $content .= ';';
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
