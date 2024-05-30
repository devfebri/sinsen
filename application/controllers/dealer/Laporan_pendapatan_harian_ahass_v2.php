<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_pendapatan_harian_ahass_v2 extends CI_Controller
{

  var $folder =   "dealer/laporan";
  var $page    =    "laporan_pendapatan_harian_ahass_v2";
  var $title  =   "Laporan pendapatan harian ahass";

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
    ini_set('display_errors', 0);
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
      $data['title'] = "LAPORAN PENDAPATAN HARIAN SERVICE";
      $data['params'] = $params;
     
      $filter = [
        'start_date' => $params->start_date,
        'end_date' => $params->end_date,
        'id_dealer'=>$this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer,
      ];
        $data['details'] = $this->db->query("SELECT wo.id_work_order,wo.status,cus.no_polisi,kar.nama_lengkap,wo.tipe_pembayaran,
        CASE WHEN sa.id_type ='ASS1' then 1 else 0 end as kpb1,
        CASE WHEN sa.id_type ='ASS2' then 1 else 0 end as kpb2,
        CASE WHEN sa.id_type ='ASS3' then 1 else 0 end as kpb3,
        CASE WHEN sa.id_type ='ASS4' then 1 else 0 end as kpb4,
        sa.no_claim_c2
            from tr_h2_wo_dealer wo 
            join tr_h2_sa_form sa on wo.id_sa_form =sa.id_sa_form 
            join ms_customer_h23 cus on sa.id_customer =cus.id_customer 
            left join ms_karyawan_dealer kar on wo.id_karyawan_dealer =kar.id_karyawan_dealer             
            where wo.id_dealer ='{$filter['id_dealer']}'  and left(wo.closed_at,10) >= '$params->start_date' and left(wo.closed_at,10) <= '$params->end_date'
            GROUP by wo.id_work_order ")->result();
            
        $data['diskon_service']=$this->db->query("select wo.id_work_order,wop.id_jasa,SUM( CASE WHEN (wop.disc_percentage = 0 or wop.disc_percentage is NULL) then wop.diskon_value else wop.harga * wop.disc_percentage / 100 end)
            as diskon_jasa from tr_h2_wo_dealer wo join tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order = wop.id_work_order join ms_h2_jasa jasa on wop.id_jasa =jasa.id_jasa 
            where wo.id_dealer ='{$filter['id_dealer']}' and left(wo.closed_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}' and jasa.id_type not in('ASS1','ASS2','ASS3','ASS4','C1','C2') and wop.pekerjaan_batal ='0'")->row();
    
        
         $data['diskon_part'] = $this->db->query("   
            select nsc.no_nsc,nsc_part.harga_beli,nsc_part.tipe_diskon ,nsc_part.diskon_value ,sum(CASE WHEN nsc_part.tipe_diskon ='Percentage' then nsc_part.harga_beli * nsc_part.diskon_value / 100 else IFNULL(nsc_part.diskon_value,0) end) AS diskon
            from tr_h23_nsc_parts nsc_part join tr_h23_nsc nsc on nsc.no_nsc = nsc_part.no_nsc 
            join ms_part part on nsc_part.id_part_int =part.id_part_int
            join tr_h2_wo_dealer wo on nsc.id_referensi =wo.id_work_order 
            where 
            part.kelompok_part not in('GMO','OIL','FED OIL') 
            and nsc.id_dealer ='{$filter['id_dealer']}' and left(wo.closed_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}' ");
          
           $data['diskon_oil'] = $this->db->query("select nsc.no_nsc,nsc_part.harga_beli,nsc_part.tipe_diskon ,nsc_part.diskon_value ,sum(CASE WHEN nsc_part.tipe_diskon ='Percentage' then nsc_part.harga_beli * nsc_part.diskon_value / 100 else IFNULL(nsc_part.diskon_value,0) end) AS diskon
            from tr_h23_nsc_parts nsc_part join tr_h23_nsc nsc on nsc.no_nsc = nsc_part.no_nsc 
            join ms_part part on nsc_part.id_part_int =part.id_part_int
            join tr_h2_wo_dealer wo on nsc.id_referensi =wo.id_work_order 
            where 
            part.kelompok_part in('GMO','OIL','FED OIL') 
            and nsc.id_dealer ='{$filter['id_dealer']}' and left(wo.closed_at,10) between '{$filter['start_date']}' and '{$filter['end_date']}' ");
            
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
      $data['title']  = "LAPORAN PENDAPATAN HARIAN SERVICE";
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
