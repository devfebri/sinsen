<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_prsp extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "dgi_prsp";
  var $title  = "Textfile .PRSP (Prospecting Activity - Create/Update Prospect Database)";

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
      $delimiter = ";";
      $filename = dealer()->kode_dealer_md . '-E20-' . kry_login(user()->id_user)->id_flp_md . '-' . waktu_dgi_file() . '.PRSP';

      // header("Content-type: text/x-csv;charset=UTF-8");
      // header('Content-Disposition: attachment; filename=' . $filename);

      // $f = fopen("php://output", "w");
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
      if ($_GET['id_prospek'] != '') {
        $filters['id_prospek'] = $_GET['id_prospek'];
      }
      if ($_GET['id_karyawan_dealer'] != '') {
        $filters['id_karyawan_dealer'] = $_GET['id_karyawan_dealer'];
      }
      $data = $this->m_dgi_api->fetch_getProspek($filters)->result();
      $content = '';
      $no = 1;
      foreach ($data as $dt) {
        $content .= $dt->id_prospek . ';'
          . $dt->sumber_prospek . ';'
          . date_dmy($dt->tgl_prospek) . ';'
          . $dt->prioritas_prospek . ';'
          . $dt->nama_konsumen . ';'
          . $dt->no_hp . ';'
          . $dt->no_ktp . ';'
          . $dt->alamat . ';'
          . $dt->id_provinsi . ';'
          . $dt->id_kabupaten . ';'
          . $dt->id_kecamatan . ';'
          . $dt->id_kelurahan . ';'
          . $dt->kode_pos . ';'
          . $dt->alamat_kantor . ';'
          . $dt->id_provinsi_kantor . ';'
          . $dt->id_kabupaten_kantor . ';'
          . $dt->id_kecamatan_kantor . ';'
          . $dt->id_kelurahan_kantor . ';'
          . $dt->kode_pos_kantor . ';'
          . $dt->latitude . ';'
          . $dt->longitude . ';'
          . $dt->pekerjaan . ';'
          . $dt->no_telp_kantor . ';'
          . date_dmy($dt->tgl_appointment) . ';'
          . $dt->waktu_appointment . ';'
          . $dt->metode_fol_up . ';'
          . $dt->test_ride_preference . ';'
          . $dt->status_prospek . ';'
          . $dt->status_prospek . ';'
          . $dt->honda_id . ';'
          . $dt->id_event . ';'
          . $dt->kode_dealer_md . ';'
          . date_time_dmyhis($dt->created_at) . ';'
          . date_time_dmyhis($dt->updated_at) . ';'
          . $dt->id_tipe_kendaraan . ';'
          . $dt->program_umum . ';'
          . date_time_dmyhis($dt->created_at) . ';'
          . date_time_dmyhis($dt->updated_at);
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
