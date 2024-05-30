<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_db_customer_h23_md extends CI_Controller
{

  var $folder =   "h2/laporan";
  var $page    =    "laporan_db_customer_h23_md";
  var $title  =   "Laporan Database Customer AHASS";

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
      $data['dealer'] = $this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where id_dealer in('94','2',	'103',	'105',	'46',	'47',	'4',	'1',	'51',	'22',	'107',	'101',	'40',	'80',	'71',	'18',	'97',	'13',	'84',	'43',	'25',	'83',	'41',	'39',	'104',	'106',	'102',	'96',	'85',	'8',	'81',	'44',	'38',	'76',	'30',	'82',	'70',	'77',	'86',	'91',	'58',	'19',	'64',	'54',	'23',	'29',	'11',	'9',	'10',	'74',	'98',	'7',	'6',	'56',	'28',	'69',	'5',	'87',	'89',	'88',	'66',	'90',	'78',	'65')")->result();
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
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
      $data['title'] = "Database Customer AHASS";
      $data['params'] = $params;
      // send_json($data);
      $filter = [
        'start_date' => $params->start_date,
        'end_date' => $params->end_date,
        'id_dealer'=>$params->id_dealer,
      ];
        if($filter['id_dealer']=="All"){
            $data['details'] = $this->db->query("select dealer.kode_dealer_ahm,dealer.nama_dealer,a.no_mesin,a.no_rangka,a.tahun_produksi,left(b.tgl_servis,10) as tanggal_service,
            a.tgl_pembelian,a.no_polisi,jasa.id_type,a.nama_customer,a.alamat,
            d.deskripsi_ahm,d.tipe_ahm,a.no_hp 
            from ms_customer_h23 
            a join tr_h2_sa_form b on b.id_customer =a.id_customer 
            join tr_h2_wo_dealer c on c.id_sa_form =b.id_sa_form 
            join ms_tipe_kendaraan d on a.id_tipe_kendaraan =d.id_tipe_kendaraan 
            join tr_h2_wo_dealer_pekerjaan kerja on c.id_work_order =kerja.id_work_order 
            join ms_h2_jasa jasa on kerja.id_jasa =jasa.id_jasa
            join ms_dealer dealer on dealer.id_dealer=b.id_dealer
            where b.id_dealer in('94','2',	'103',	'105',	'46',	'47',	'4',	'1',	
            '51',	'22',	'107',	'101',	'40',	'80',	'71',	'18',	'97',	'13',	
            '84',	'43',	'25',	'83',	'41',	'39',	'104',	'106',	'102',	'96',	
            '85',	'8',	'81',	'44',	'38',	'76',	'30',	'82',	'70',	'77',	
            '86',	'91',	'58',	'19',	'64',	'54',	'23',	'29',	'11',	'9',	
            '10',	'74',	'98',	'7',	'6',	'56',	'28',	'69',	'5',	'87',	
            '89',	'88',	'66',	'90',	'78',	'65') and LEFT(c.created_at ,10) 
            between '{$filter['start_date']}' and '{$filter['end_date']}' and c.status ='closed'")->result();
        }else{
            $data['details'] = $this->db->query("select dealer.kode_dealer_ahm,dealer.nama_dealer,a.no_mesin,a.no_rangka,a.tahun_produksi,left(b.tgl_servis,10) as tanggal_service,
            a.tgl_pembelian,a.no_polisi,jasa.id_type,a.nama_customer,a.alamat,
            d.deskripsi_ahm,d.tipe_ahm,a.no_hp 
            from ms_customer_h23 
            a join tr_h2_sa_form b on b.id_customer =a.id_customer 
            join tr_h2_wo_dealer c on c.id_sa_form =b.id_sa_form 
            join ms_tipe_kendaraan d on a.id_tipe_kendaraan =d.id_tipe_kendaraan 
            join tr_h2_wo_dealer_pekerjaan kerja on c.id_work_order =kerja.id_work_order 
            join ms_h2_jasa jasa on kerja.id_jasa =jasa.id_jasa
            join ms_dealer dealer on dealer.id_dealer=b.id_dealer
            where b.id_dealer ='{$filter['id_dealer']}' and LEFT(c.created_at ,10) 
            between '{$filter['start_date']}' and '{$filter['end_date']}' and c.status ='closed'")->result();
          
            
        }
         
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
      $data['title']  = "Laporan Database Customer AHASS";
      $data['set']    = "view";
      $this->template($data);
    }
  }
}
