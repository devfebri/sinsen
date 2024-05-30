<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_pkb extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "dgi_pkb";
  var $title  = "Textfile .PKB (Work Order)";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_work_order', 'm_wo');
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
        'filter_created_wo' => true
      ];
      if ($_GET['id_work_order'] != '') {
        $filters['id_work_order'] = $_GET['id_work_order'];
      }
      $params = $filters;
      $files[] = $filename_pkb1 = dealer()->kode_dealer_md . '-E20-' . $time . '-PKB.PKB1';
      $files[] = $filename_pkb2 = dealer()->kode_dealer_md . '-E20-' . $time . '-PKB.PKB2';
      $files[] = $filename_pkb3 = dealer()->kode_dealer_md . '-E20-' . $time . '-PKB.PKB3';

      $params['filename'] = $filename_pkb1;
      $params['id_dealer'] = dealer()->id_dealer;
      $params['id_work_order_not_null'] = true;
      $this->pkb1($params);
      $params['filename'] = $filename_pkb2;
      $this->pkb2($params);
      $params['select'] = 'wo_parts';
      $params['filename'] = $filename_pkb3;
      $this->pkb3($params);
      // die();

      //Load Libary ZIP
      $this->load->library('zip');

      //Get File To Create ZIP
      foreach ($files as $fl) {
        $get_files = FCPATH . "./temp_textfile/pkb/" . $fl;
        $this->zip->read_file($get_files);
      }

      //Remove File Temp
      foreach ($files as $fl) {
        if (file_exists(FCPATH . "temp_textfile/pkb/" . $fl)) {
          unlink("temp_textfile/pkb/" . $fl); //Delete File
        }
      }

      //Download ZIP
      $this->zip->download(dealer()->kode_dealer_md . '-E20-' . $time . '-PKB');
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }

  function pkb1($filters)
  {
    $data = $this->m_wo->get_sa_form($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/pkb/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->id_work_order . ';';
      $content .= $dt->id_sa_form . ';';
      $content .= date_dmy($dt->tgl_servis) . ';';
      $content .= date_time_dmyhis($dt->waktu_pkb) . ';';
      $content .= $dt->no_polisi . ';';
      $content .= $dt->no_rangka . ';';
      $content .= $dt->no_mesin . ';';
      $content .= $dt->id_tipe_kendaraan . ';';
      $content .= $dt->tahun_produksi . ';';
      $content .= $dt->informasi_bensin . ';';
      $content .= $dt->km_terakhir . ';';
      $content .= $dt->tipe_coming . ';';
      $content .= $dt->nama_customer . ';';
      $content .= $dt->alamat . ';';
      $content .= $dt->id_provinsi . ';';
      $content .= $dt->id_kabupaten . ';';
      $content .= $dt->id_kecamatan . ';';
      $content .= $dt->id_kelurahan . ';';
      $content .= $dt->kode_pos . ';';
      $content .= $dt->alamat_pembawa . ';';
      $content .= $dt->id_prov_pembawa . ';';
      $content .= $dt->id_kab_pembawa . ';';
      $content .= $dt->id_kec_pembawa . ';';
      $content .= $dt->id_kel_pembawa . ';';
      $content .= $dt->kodepos_pembawa . ';';
      $content .= $dt->nama_pembawa . ';';
      $content .= $dt->no_hp_pembawa . ';';
      $content .= $dt->hubungan_dengan_pemilik . ';';
      $content .= $dt->kelurahan . ';';
      $content .= $dt->rekomendasi_sa . ';';
      $content .= kry_login($dt->created_by)->id_flp_md . ';'; //Honda ID SA
      $filter_mk['id_karyawan_dealer'] = $dt->id_mekanik;
      $content .= kry_login($filter_mk)->id_flp_md . ';'; //Honda ID SA
      $content .= ';'; // Saran Mekanik
      $content .= $dt->asal_unit_entry . ';';
      $content .= $dt->id_pit . ';';
      $content .= $dt->jenis_pit . ';';
      $content .= date_time_dmyhis($dt->estimasi_waktu_daftar) . ';';
      $content .= konversi_detik_ke_jam_menit($dt->total_waktu) . ';'; //Waktu Selesai
      $content .= konversi_detik_ke_jam_menit($dt->etr) . ';'; //FRT
      $content .= $dt->tipe_pembayaran . ';';
      $content .= $dt->catatan_tambahan . ';';
      $content .= $dt->konfirmasi_pekerjaan_tambahan . ';';
      $content .= $dt->no_buku_claim_c2 . ';';
      $content .= $dt->id_wo_job_return . ';';
      $content .= $dt->grand_total . ';';
      $content .= konversi_detik_ke_jam_menit($dt->etr) . ';'; //FRT
      $content .= $dt->status_wo . ';'; // Status WO
      $content .= $dt->kode_dealer_md . ';'; // Status WO
      $content .= date_time_dmyhis($dt->waktu_pkb) . ';';
      $content .= date_time_dmyhis($dt->updated_at_wo);
      $no++;
      if ($no <= count($data)) {
        $content .= "\r\n";
      }
    }
    // echo $content;
    fwrite($file, $content);
    fclose($file);
  }

  function pkb2($filters)
  {
    $data = $this->m_wo->getWOPekerjaan($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/pkb/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->id_work_order . ';';
      $content .= $dt->id_jasa . ';';
      $content .= $dt->deskripsi . ';';
      $content .= $dt->desk_type   . ';';
      $content .= $dt->id_promo . ';';
      $content .= $dt->diskon_rp . ';';
      $content .= $dt->diskon_persen . ';';
      $content .= $dt->biaya_servis . ';';
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
  function pkb3($filters)
  {
    $data = $this->m_wo->getWOParts($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/pkb/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->id_work_order . ';';
      $content .= $dt->id_jasa . ';';
      $content .= $dt->id_part . ';';
      $content .= $dt->qty . ';';
      $content .= $dt->harga . ';';
      $content .= date_time_dmyhis($dt->created_at);
      $no++;
      if ($no <= count($data)) {
        $content .= "\r\n";
      }
    }
    fwrite($file, $content);
    fclose($file);
  }
}
