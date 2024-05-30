<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_picking_list extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"monitor_picking_list";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Monitor Picking List";

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
		$data['set']	= "view";		
		$data['dt_monitor_picking_list'] = $this->db->query("SELECT *,ms_dealer.alamat AS alamat2 FROM tr_pl_part LEFT JOIN ms_dealer ON tr_pl_part.id_dealer = ms_dealer.id_dealer
				LEFT JOIN ms_karyawan ON tr_pl_part.id_karyawan = ms_karyawan.id_karyawan");
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling WHERE active = 1");									
		$data['dt_karyawan'] 	= $this->m_admin->getSortCond("ms_karyawan","nama_lengkap","ASC");			
		$this->template($data);	
	}
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$this->load->view('h3/t_monitor_picking_list',$data);
	}
	public function cari_id(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_pl_part ORDER BY no_pl_part DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_pl_part)-3;
			$id 	= substr($row->no_pl_part,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "PLP/".$kode1;
		}else{
			$kode = "PLP/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}
	public function save(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$jum = $this->input->post("jum_pl");		
		for ($i=1; $i <= $jum; $i++) { 												
			if(isset($_POST["cek_".$i])){
				$data['no_pl_part'] = $no_pl_part	= $_POST["no_pl_".$i];							
				$data['status_pl'] = "open";
				$data['id_karyawan'] = $this->input->post('id_karyawan');
				$data['updated_by'] = $waktu;
				$data['updated_at'] = $login_id;
				$this->m_admin->update("tr_pl_part",$data,"no_pl_part",$no_pl_part);
			}
		}			
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/monitor_picking_list'>";
	}
	public function detail_popup()
	{				
		$no_pl_part = $data['no_pl_part'] = $this->input->post("no_pl_part");		
		$data['isi']    = $this->page;			
		$data['title']	= $this->title;								
		$this->load->view("h3/t_pl_detail_popup",$data);		
	}	

	

}