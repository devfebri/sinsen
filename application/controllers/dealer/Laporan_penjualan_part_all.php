<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan_part_all extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "laporan_penjualan_part_all";
  var $title  =   "Laporan Penjualan Part All";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
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
    
      $filter = [
        'start_date' => $params->start_date,
        'end_date' => $params->end_date,
        'id_dealer'=>$this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer,
      ];
      
      $where = '';
      if($this->config->item('ahm_d_only')){
        $where = "and c.kelompok_part !='FED OIL'";
      }

      $data['details'] = $this->db->query("select a.no_nsc,a.tgl_nsc,b.id_part,c.nama_part,b.harga_beli,
          b.tipe_diskon,
          case when b.tipe_diskon ='Percentage' then (b.diskon_value * b.harga_beli) / 100 else IFNULL(b.diskon_value,0) end as diskon_rupiah,
          case when b.tipe_diskon !='Percentage' then (b.diskon_value / b.harga_beli) * 100 else ifnull(b.diskon_value,0) end as diskon_persen
          ,b.qty,c.kelompok_part,a.referensi,a.id_referensi,
          a.no_inv_jaminan
          from tr_h23_nsc a join tr_h23_nsc_parts b on a.no_nsc =b.no_nsc 
          join ms_part c on b.id_part_int = c.id_part_int
          where a.id_dealer='{$filter['id_dealer']}' and a.status is null and left(a.created_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}' $where order by a.tgl_nsc,a.no_nsc ASC")->result();
      
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
}
