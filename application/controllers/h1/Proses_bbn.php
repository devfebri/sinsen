<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_bbn extends CI_Controller {

    var $tables =   "tr_proses_bbn";  
    var $folder =   "h1";
    var $page   =   "proses_bbn";
    var $pk     =   "no_invoice";
    var $title  =   "Entry Notice Pajak";

  public function __construct()
  {   
    parent::__construct();
    
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');    
    //===== Load Library =====
    $this->load->library('upload');

    //---- cek session -------//    
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page,"select");    
    $sess = $this->m_admin->sess_auth();    
    if($name=="" OR $auth=='false')
    {
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
    }elseif($sess=='false'){
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
    }


  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if($name=="")
    {
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
    }else{
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']  = $this->session->userdata("group");
      $this->load->view('template/header',$data);
      $this->load->view('template/aside');      
      $this->load->view($this->folder."/".$this->page);   
      $this->load->view('template/footer');
    }
  }

  public function index()
  {       
    $data['isi']    = $this->page;    
    $data['title']  = $this->title;                             
    $data['set']    = "view";
    $data['dt_bbn'] = $this->m_admin->getAll($this->tables);
    $this->template($data);     
  } 
  public function add()
  {       
    $data['isi']    = $this->page;    
    $data['title']  = $this->title;                             
    $data['set']    = "insert";       
    $this->template($data);     
  }   
  public function t_bbn(){
    $start_date = $this->input->post('start_date');
    $end_date   = $this->input->post('end_date');
    //$id_dealer  = $this->m_admin->cari_dealer(); 
    $data['dt_bbn'] = $this->db->query("SELECT tr_konfirmasi_map_detail.*,tr_pengajuan_bbn_detail.*,
        tr_pengajuan_bbn_detail.tgl_mohon_samsat AS tgl_samsat, ms_tipe_kendaraan.deskripsi_ahm as tipe_ahm,ms_warna.warna FROM tr_konfirmasi_map_detail         
        INNER JOIN tr_pengajuan_bbn_detail ON tr_konfirmasi_map_detail.no_mesin= tr_pengajuan_bbn_detail.no_mesin 
        INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna
        WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat BETWEEN '$start_date' AND '$end_date' 
        AND tr_konfirmasi_map_detail.konfirmasi = 'ya'
        AND (tr_pengajuan_bbn_detail.proses_bbn = '' OR tr_pengajuan_bbn_detail.proses_bbn IS NULL) AND tr_pengajuan_bbn_detail.status_bbn = 'generated'");
    if($data['dt_bbn']->num_rows()==0) {
      $data['dt_bbn'] = $this->db->query("SELECT *,tr_bantuan_bbn.tgl_samsat AS tgl_samsat, tgl_faktur as tgl_jual, tahun_produksi as tahun,tr_bantuan_bbn.no_mesin,ms_tipe_kendaraan.deskripsi_ahm as tipe_ahm FROM tr_bantuan_bbn 
          INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
          INNER JOIN ms_warna ON tr_bantuan_bbn.id_warna = ms_warna.id_warna
          WHERE tgl_samsat BETWEEN '$start_date' AND '$end_date'");
    }    
    $data['status'] = "input";
    $this->load->view('h1/t_bbn',$data);
  }
  public function save()
  {       
    $waktu      = gmdate("y-m-d h:i:s", time()+60*60*7);
    $tgl        = gmdate("y-m-d", time()+60*60*7);
    $login_id   = $this->session->userdata('id_user');        
    //$no_bastd   = $this->cari_id();
    $no_invoice_bbn         = $this->m_admin->cari_id("tr_proses_bbn","no_invoice_bbn");
    $da['no_invoice_bbn']   = $no_invoice_bbn;
    $da['tgl_invoice']      = $tgl;       
    $da['status_bbn']       = "input";    
    $da['created_at']       = $waktu;   
    $da['created_by']       = $login_id;    
    
    $jum                    = $this->input->post("jum");
    $am=0;$tot=0;
    for ($i=1; $i <= $jum; $i++) { 
      $nosin                = $_POST["no_mesin_".$i];     
      $data['no_mesin']     = $nosin;
      $data['no_invoice_bbn']   = $no_invoice_bbn;
      $notice_pajak         = $_POST["notice_pajak_".$i];     
      $data['notice_pajak'] = $notice_pajak;
      $data["check"] = "ya";
      if(isset($_POST["notice_pajak_".$i]) AND $_POST["notice_pajak_".$i] != '' AND isset($_POST["cek_notice_".$i])){
        $this->db->query("UPDATE tr_pengajuan_bbn_detail SET proses_bbn = 'ya' WHERE no_mesin = '$nosin'");                           
        $tot = $tot + 1;
        $am = $am + $notice_pajak;
        
        $cek = $this->db->query("SELECT * FROM tr_proses_bbn_detail WHERE no_mesin = '$nosin'");
        if($cek->num_rows() > 0){           
          $this->m_admin->update("tr_proses_bbn_detail",$data,"no_mesin",$nosin);               
        }else{
          $this->m_admin->insert("tr_proses_bbn_detail",$data);               
        } 
      }
    }

    $da['amount']       = $am;
    $da['jumlah_unit']  = $tot;
    
    
    $ce = $this->db->query("SELECT * FROM tr_proses_bbn WHERE no_invoice_bbn = '$no_invoice_bbn'");
    if($ce->num_rows() > 0){            
      $this->m_admin->update("tr_proses_bbn",$da,"no_invoice_bbn",$no_invoice_bbn);               
    }else{
      $this->m_admin->insert("tr_proses_bbn",$da);                
    }
    $_SESSION['pesan']  = "Data has been saved successfully";
    $_SESSION['tipe']   = "success";    
    echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/proses_bbn'>";
  }





  public function konfirmasi()
  {       
    $data['isi']    = $this->page;    
    $data['title']  = $this->title;       
    $id = $this->input->get('id');    
    $a  = $this->input->get('a');   
    $data['dt_biro']= $this->m_admin->getByID("tr_kirim_biro","no_tanda_terima",$a);
    $data['dt_map'] = $this->db->query("SELECT tr_pengajuan_bbn_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_pengajuan_bbn_detail INNER JOIN ms_tipe_kendaraan 
        ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna
        WHERE tr_pengajuan_bbn_detail.id_generate='$id'");                      
    $data['set']    = "konfirmasi";       
    $this->template($data);     
  }  
  public function detail()
  {       
    $data['isi']    = $this->page;    
    $data['title']  = $this->title;       
    $id = $this->input->get('id');        
    $data['dt_bbn'] = $this->db->query("SELECT * FROM tr_proses_bbn_detail INNER JOIN tr_proses_bbn ON tr_proses_bbn_detail.no_invoice_bbn = tr_proses_bbn.no_invoice_bbn
        WHERE tr_proses_bbn.no_invoice_bbn = '$id'");                      
    $data['set']    = "detail";       
    $this->template($data);     
  }     
}