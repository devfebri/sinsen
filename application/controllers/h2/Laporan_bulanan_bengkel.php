<?php
class Laporan_bulanan_bengkel extends CI_Controller{
  var $folder =   "h2/laporan";
  var $page    =    "laporan_bulanan_bengkel";
  var $title  =   "Laporan Bulanan Bengkel";
  
  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->library('upload');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false' or $sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
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
        'id_dealer'=>$params->id_dealer,
      ];
      
      if($filter['id_dealer']=="all"){
            $data['detail'] = $this->db->query("select kabupaten.kabupaten, dealer.nama_dealer,dealer.kode_dealer_ahm,dealer.id_dealer  from ms_dealer dealer 
            join ms_kelurahan kelurahan on dealer.id_kelurahan =kelurahan.id_kelurahan 
            join ms_kabupaten kabupaten on kabupaten.id_kabupaten = left(kelurahan.id_kecamatan,4)
            where dealer.id_dealer in('94','2',	'103',	'105',	'46',	'47',	'4',	'1',	
            '51',	'22',	'107',	'101',	'40',	'80',	'71',	'18',	'97',	'13',	
            '84',	'43',	'25',	'83',	'41',	'39',	'104',	'106',	'102',	'96',	
            '85',	'8',	'81',	'44',	'38',	'76',	'30',	'82',	'70',	'77',	
            '86',	'91',	'58',	'19',	'64',	'54',	'23',	'29',	'11',	'9',	
            '10',	'74',	'98',	'7',	'6',	'56',	'28',	'69',	'5',	'87',	
            '89',	'88',	'66',	'90',	'78',	'65')
            order by kabupaten.id_kabupaten desc, dealer.nama_dealer asc");
      }else{
        $data['detail'] = $this->db->query("select kabupaten.kabupaten, dealer.nama_dealer,dealer.kode_dealer_ahm,dealer.id_dealer  from ms_dealer dealer 
            join ms_kelurahan kelurahan on dealer.id_kelurahan =kelurahan.id_kelurahan 
            join ms_kabupaten kabupaten on kabupaten.id_kabupaten = left(kelurahan.id_kecamatan,4)
            where dealer.id_dealer ='{$filter['id_dealer']}'
            order by kabupaten.id_kabupaten desc, dealer.nama_dealer asc");
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
      $data['title']  = "Laporan Bulanan Bengkel";
      $data['set']    = "view";
      $this->template($data);
    }
  }
  
  
  
}

?>