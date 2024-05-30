<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemenuhan_hutang_ksu extends CI_Controller {

    var $tables =   "tr_pemenuhan_hutang_ksu";	
		var $folder =   "dealer";
		var $page		=		"pemenuhan_hutang_ksu";
    var $pk     =   "no_surat_pengantar";
    var $title  =   "Pemenuhan Hutang KSU";

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
		$this->load->library('cfpdf');
		$this->load->library('mpdf_l');


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
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_hutang']	= $this->db->query("SELECT * FROM tr_pemenuhan_hutang LEFT JOIN tr_sales_order ON tr_pemenuhan_hutang.id_sales_order = tr_sales_order.id_sales_order
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			WHERE tr_spk.id_dealer = '$id_dealer'");
		$this->template($data);			
	}
	public function list_hutang()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "list_hutang";						
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_hutang'] = $this->db->query("SELECT DISTINCT(id_sales_order) FROM tr_sales_order WHERE id_dealer = '$id_dealer'");	
		$this->template($data);			
	}
	public function terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "terima";				
		$id = $this->input->get('id');		
		$data['dt_retur']	= $this->db->query("SELECT * FROM tr_map_retur WHERE no_map_retur = '$id'");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
		$this->template($data);			
	}
	public function cek_item(){
		$id_sales_order = $this->input->post("id_sales_order");
		$sql = $this->db->query("SELECT tr_sales_order.*,tr_spk.id_warna,tr_spk.id_tipe_kendaraan,tr_spk.nama_konsumen FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			WHERE tr_sales_order.id_sales_order = '$id_sales_order'")->row();
		echo "ok|".$sql->tgl_cetak_invoice."|".$sql->nama_konsumen."|".$sql->no_invoice."|".$sql->no_mesin."|".$sql->no_rangka."|".$sql->id_tipe_kendaraan."|".$sql->id_warna;		
	}
	public function t_data(){
		$id = $this->input->post("id_sales_order");
		$sql = $this->db->query("SELECT tr_sales_order.*,tr_spk.id_warna,tr_spk.id_tipe_kendaraan,tr_spk.nama_konsumen FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			WHERE tr_sales_order.id_sales_order = '$id'")->row();
		$dq = "SELECT DISTINCT(ms_koneksi_ksu_detail.id_koneksi_ksu_detail), ms_koneksi_ksu_detail.id_koneksi_ksu,ms_koneksi_ksu_detail.id_ksu 
			FROM ms_koneksi_ksu 
			LEFT JOIN ms_koneksi_ksu_detail ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu
			LEFT JOIN tr_sales_order_ksu ON ms_koneksi_ksu.id_koneksi_ksu = tr_sales_order_ksu.id_koneksi_ksu			
			WHERE tr_sales_order_ksu.id_sales_order = '$id' AND  ms_koneksi_ksu_detail.id_ksu NOT IN (SELECT tr_sales_order_ksu.id_ksu FROM tr_sales_order_ksu WHERE tr_sales_order_ksu.id_sales_order = '$id' AND id_ksu IS NOT NULL)";
		$data['dt_data'] 	= $this->db->query($dq);					
		$this->load->view('dealer/t_pemenuhan_hutang_ksu',$data);					
	}
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_pemenuhan_hutang ORDER BY no_surat_pengantar DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_surat_pengantar)-8;
			$id 	= substr($row->no_surat_pengantar,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/PHK";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/PHK";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/RM";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/PHK";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/PHK";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_surat_pengantar 		= $this->cari_id();
		$da['no_surat_pengantar'] = $no_surat_pengantar;
		$da['tgl_cetak'] 				= $tgl;
		$id_sales_order 				= $this->input->post("id_sales_order");
		$da['id_sales_order'] 	= $id_sales_order;
		$da['status'] 					= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 	= $this->input->post("jum");				
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_".$i])){
				$id_ksu 								= $_POST["id_ksu_".$i];			
				$data['id_ksu'] 				= $id_ksu;
				$data['no_surat_pengantar'] = $no_surat_pengantar;
				$data["status_ksu"] 		= "input";				

				$ksu = $this->m_admin->getByID("tr_sales_order_ksu","id_sales_order",$id_sales_order)->row();
				$dt['id_sales_order'] = $id_sales_order;
				$dt['id_ksu'] = $id_ksu;
				$dt['id_koneksi_ksu'] = $ksu->id_koneksi_ksu;
				$dt['id_dealer'] = $ksu->id_dealer;
				$dt['created_at'] = $waktu;
				$dt['created_by'] = $login_id;
				$this->m_admin->insert("tr_sales_order_ksu",$dt);								

				$cek = $this->db->query("SELECT * FROM tr_pemenuhan_hutang_detail WHERE id_ksu = '$id_ksu' AND no_surat_pengantar = '$no_surat_pengantar'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_pemenuhan_hutang_detail",$data,"id_pemenuhan_hutang_detail",$r->id_pemenuhan_hutang_detail);													
				}else{
					$this->m_admin->insert("tr_pemenuhan_hutang_detail",$data);								
				}				
			}			
		}		
		$ce = $this->db->query("SELECT * FROM tr_pemenuhan_hutang WHERE no_surat_pengantar = '$no_surat_pengantar'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_pemenuhan_hutang",$da,"no_surat_pengantar",$no_surat_pengantar);								
		}else{
			$this->m_admin->insert("tr_pemenuhan_hutang",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/pemenuhan_hutang_ksu'>";
	}
	public function cetak()
	{
    $id  = $this->input->get('id');    
    $tgl = date("Y-m-d");    
    $mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion=true; 
    $mpdf->charset_in='UTF-8';
    $mpdf->autoLangToFont = true;  	
  	$sql = $this->db->query("SELECT * FROM tr_pemenuhan_hutang LEFT JOIN tr_pemenuhan_hutang_detail ON 
  				tr_pemenuhan_hutang.no_surat_pengantar	= tr_pemenuhan_hutang_detail.no_surat_pengantar	 
  				LEFT JOIN tr_sales_order ON tr_pemenuhan_hutang.id_sales_order = tr_sales_order.id_sales_order
  				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
  				WHERE tr_pemenuhan_hutang_detail.no_surat_pengantar = '$id'");
  	$data['row'] 		= $sql->row();    	    	
    $html = $this->load->view('dealer/cetak_pemenuhan', $data, true);   
    $mpdf->WriteHTML($html);    
    $output = 'pemenuhan_hutang_ksu.pdf';
    $mpdf->Output("$output", 'I');          
	}
}