<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_pembelian_part extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "laporan_pembelian_part";
  var $title  =   "Laporan Pembelian Part";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
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
        $where = "and part.kelompok_part !='FED OIL'";
      }

      $data['details'] = $this->db->query("select gr.tanggal_receipt,gr.id_reference ,grp.id_part,part.nama_part,grp.qty, grp.harga_setelah_diskon as harga ,(grp.qty * grp.harga_setelah_diskon) as total from tr_h3_dealer_good_receipt_parts grp
          join tr_h3_dealer_good_receipt gr on gr.id_good_receipt =grp.id_good_receipt 
 		  join ms_part part on grp.id_part =part.id_part where gr.id_dealer='{$filter['id_dealer']}' and left(gr.tanggal_receipt,10) between '{$filter['start_date']}' and '{$filter['end_date']}' $where order by gr.tanggal_receipt,gr.id_reference ASC")->result();
      
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
