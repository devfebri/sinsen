<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_spk extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "dgi_spk";
  var $title  = " Textfile .SPK (Dealing Process – Create SPK)";

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
      $time = waktu_dgi_file();

      set_time_limit(0);
      ini_set('memory_limit', '5000M');
      ini_set('max_execution_time', 1000000000000);

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
      $params = $filters;
      $files[] = $filename_spk1 = dealer()->kode_dealer_md . '-E20-' . kry_login(user()->id_user)->id_flp_md . '-' . $time . '-SPK.SPK1';
      $files[] = $filename_spk2 = dealer()->kode_dealer_md . '-E20-' . kry_login(user()->id_user)->id_flp_md . '-' . $time . '-SPK.SPK2';
      $files[] = $filename_spk3 = dealer()->kode_dealer_md . '-E20-' . kry_login(user()->id_user)->id_flp_md . '-' . $time . '-SPK.SPK3';

      $params['filename'] = $filename_spk1;
      $this->spk1($params);
      $params['filename'] = $filename_spk2;
      $this->spk2($params);
      $params['filename'] = $filename_spk3;
      $this->spk3($params);
      // die();

      //Load Libary ZIP
      $this->load->library('zip');

      //Get File To Create ZIP
      foreach ($files as $fl) {
        $get_files = FCPATH . "./temp_textfile/spk/" . $fl;
        $this->zip->read_file($get_files);
      }

      //Remove File Temp
      foreach ($files as $fl) {
        if (file_exists(FCPATH . "temp_textfile/spk/" . $fl)) {
          unlink("temp_textfile/spk/" . $fl); //Delete File
        }
      }

      //Download ZIP
      $this->zip->download(dealer()->kode_dealer_md . '-E20-' . kry_login(user()->id_user)->id_flp_md . '-' . $time . '-SPK');
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }

  function spk1($filters)
  {
    $data = $this->m_dgi_api->getSPK($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/spk/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->no_spk . ';';
      $content .= $dt->id_prospek . ';';
      $content .= $dt->nama_konsumen . ';';
      $content .= $dt->no_ktp . ';';
      $content .= $dt->alamat . ';';
      $content .= $dt->id_provinsi . ';';
      $content .= $dt->id_kabupaten . ';';
      $content .= $dt->id_kecamatan . ';';
      $content .= $dt->id_kelurahan . ';';
      $content .= $dt->kodepos . ';';
      $content .= $dt->no_hp . ';';
      $content .= $dt->nama_bpkb_stnk . ';';
      $content .= $dt->no_ktp_bpkb . ';';
      $content .= $dt->alamat_ktp_bpkb . ';';
      $content .= $dt->id_prov_ktp . ';';
      $content .= $dt->id_kab_ktp . ';';
      $content .= $dt->id_kec_ktp . ';';
      $content .= $dt->id_kel_ktp . ';';
      $content .= $dt->kodepos_ktp . ';';
      $content .= $dt->latitude . ';';
      $content .= $dt->longitude . ';';
      $content .= $dt->npwp . ';';
      $content .= $dt->no_kk . ';';
      $content .= $dt->alamat_kk . ';';
      $content .= $dt->id_prov_kk . ';';
      $content .= $dt->id_kab_kk . ';';
      $content .= $dt->id_kec_kk . ';';
      $content .= $dt->id_kel_kk . ';';
      $content .= $dt->kodepos_kk . ';';
      $content .= $dt->fax . ';';
      $content .= $dt->email . ';';
      $content .= $dt->id_flp_md . ';';
      $content .= date_dmy($dt->tgl_spk) . ';';
      $content .= $dt->status_spk . ';';
      $content .= $dt->kode_dealer_md . ';';
      $content .= date_time_dmyhis($dt->created_at) . ';';
      $content .= date_time_dmyhis($dt->updated_at);
      $no++;
      if ($no <= count($data)) {
        $content .= "\r\n";
      }
    }
    fwrite($file, $content);
    fclose($file);
  }

  function spk2($filters)
  {
    $data = $this->m_dgi_api->getSPK($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/spk/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->no_spk . ';';
      $content .= $dt->id_prospek . ';';
      $content .= $dt->id_tipe_kendaraan . ';';
      $content .= $dt->id_warna . ';';
      $content .= $dt->qty . ';';
      $content .= $dt->harga . ';';
      $content .= $dt->diskon . ';';
      $content .= $dt->amount_ppn . ';';
      $content .= $dt->faktur_pajak . ';';
      $content .= $dt->jenis_beli . ';';
      $content .= $dt->tanda_jadi . ';';
      $content .= date_dmy($dt->tgl_pengiriman) . ';';
      $content .= $dt->program_umum . ';';
      $content .= $dt->id_flp_md . ';';
      $content .= $dt->id_apparel . ';';
      $content .= date_time_dmyhis($dt->created_at) . ';';
      $content .= date_time_dmyhis($dt->updated_at);
      $no++;
      if ($no <= count($data)) {
        $content .= "\r\n";
      }
    }
    fwrite($file, $content);
    fclose($file);
  }
  function spk3($filters)
  {
    $data = $this->m_dgi_api->getSPKAnggotaKK($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/spk/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->id_prospek . ';';
      $content .= $dt->anggota . ';';
      $content .= date_time_dmyhis($dt->created_at) . ';';
      $content .= date_time_dmyhis($dt->updated_at);
      $no++;
      if ($no <= count($data)) {
        $content .= "\r\n";
      }
    }
    fwrite($file, $content);
    fclose($file);
  }
}
