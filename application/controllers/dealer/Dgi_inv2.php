<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dgi_inv2 extends CI_Controller
{


  var $folder = "dealer";
  var $page   = "dgi_inv2";
  var $title  = "Textfile .INV2 (Billing Process – Create Invoice)";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_work_order', 'm_wo');
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
      $files[] = $filename_inv1 = dealer()->kode_dealer_md . '-E20-' . $time . '.INV2';
      $files[] = $filename_njb = dealer()->kode_dealer_md . '-E20-' . $time . '.NJB';
      $files[] = $filename_nsc = dealer()->kode_dealer_md . '-E20-' . $time . '.NSC';

      $params['filename'] = $filename_inv1;
      $params['id_dealer'] = dealer()->id_dealer;
      $this->inv2($params);
      $params['filename'] = $filename_njb;
      $this->njb($params);
      $params['filename'] = $filename_nsc;
      $this->nsc($params);
      // die();

      //Load Libary ZIP
      $this->load->library('zip');

      //Get File To Create ZIP
      foreach ($files as $fl) {
        $get_files = FCPATH . "./temp_textfile/inv2/" . $fl;
        $this->zip->read_file($get_files);
      }

      //Remove File Temp
      foreach ($files as $fl) {
        if (file_exists(FCPATH . "temp_textfile/inv2/" . $fl)) {
          unlink("temp_textfile/inv2/" . $fl); //Delete File
        }
      }

      //Download ZIP
      $this->zip->download(dealer()->kode_dealer_md . '-E20-' . $time . '-INV2');
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['set']    = "view";
      $this->template($data);
    }
  }

  function inv2($filters)
  {
    $filters['referensi'] = 'Work Order';
    $data = $this->m_bil->get_njb_nsc_print($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/inv2/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->id_work_order . ';';
      $content .= $dt->no_njb . ';';
      $content .= date_dmy($dt->tgl_njb) . ';';
      $content .= round($dt->total_jasa) . ';';
      $content .= $dt->no_nsc . ';';
      $content .= date_dmy($dt->tgl_nsc) . ';';
      $content .= $dt->tot_nsc . ';';
      $wo = $this->m_wo->get_sa_form(['id_work_order' => $dt->id_work_order])->row();
      $content .= kry_login($wo->created_by)->id_flp_md . ';'; //Honda ID SA
      $filter_mk['id_karyawan_dealer'] = $wo->id_mekanik;
      $content .= kry_login($filter_mk)->id_flp_md . ';'; //Honda ID SA
      $content .= date_time_dmyhis($dt->created_at) . ';';
      $content .= date_time_dmyhis($dt->created_at);
      $no++;
      if ($no <= count($data)) {
        $content .= "\r\n";
      }
    }
    // echo $content;
    fwrite($file, $content);
    fclose($file);
  }

  function njb($filters)
  {
    $filters['no_njb_not_null'] = true;
    $data = $this->m_wo->getWOPekerjaan($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/inv2/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->no_njb . ';';
      $content .= $dt->id_jasa . ';';
      $content .= $dt->harga . ';';
      $content .= $dt->id_promo   . ';';
      $content .= ROUND($dt->diskon_rp)   . ';';
      $content .= $dt->diskon_persen   . ';';
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
  function nsc($filters)
  {
    $filters['id_work_order_not_null'] = true;
    $data = $this->m_bil->getNSCParts($filters)->result();
    $fileLocation = getenv("DOCUMENT_ROOT") . "/temp_textfile/inv2/" . $filters['filename'];
    $file = fopen($fileLocation, "w");

    $content = '';
    $no = 1;
    foreach ($data as $dt) {
      $content .= $dt->no_nsc . ';';
      $content .= $dt->id_jasa . ';';
      $content .= $dt->id_part . ';';
      $content .= $dt->qty . ';';
      $content .= $dt->harga_beli . ';';
      $content .= $dt->id_promo . ';';
      $content .= $dt->promo_rp . ';';
      $content .= $dt->promo_persen . ';';
      $content .= $dt->ppn . ';';
      $content .= $dt->subtotal . ';';
      $content .= round($dt->uang_muka) . ';';
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
