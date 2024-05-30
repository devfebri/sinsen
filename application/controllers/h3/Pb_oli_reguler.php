<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pb_oli_reguler extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"pb_oli_reguler";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Proses Barang Bagi Spare Part";

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
		$data['dt_pb_oli_reguler'] = $this->db->query("SELECT * FROM tr_pb_oli_reguler");			
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}	
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$data['sql'] = $this->db->query("SELECT * FROM tr_so_oil LEFT JOIN ms_dealer ON tr_so_oil.id_dealer = ms_dealer.id_dealer 
			WHERE tgl_so BETWEEN '$start_date' AND '$end_date' AND tr_so_oil.status_so = 'approved'");
		$this->load->view('h3/t_pb_oli_reguler',$data);
	}	
	public function detail_popup()
	{				
		$no_so_oil = $data['no_so_oil'] = $this->input->post("no_so_oil");		
		$data['isi']    = $this->page;	
		$data['dt_sql']	= $this->db->query("SELECT * FROM tr_so_oil INNER JOIN ms_dealer ON tr_so_oil.id_dealer = ms_dealer.id_dealer 
			WHERE tr_so_oil.no_so_oil = '$no_so_oil'");
		$data['title']	= $this->title;								
		$this->load->view("h3/t_pb_oli_detail_popup",$data);		
	}	
	public function cari_id(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_pb_oli_reguler ORDER BY no_pb_oli_reguler DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_pb_oli_reguler)-3;
			$id 	= substr($row->no_pb_oli_reguler,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "PBO/".$kode1;
		}else{
			$kode = "PBO/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}	
	
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		//$id_pb_oli_reguler 				= "PODU/190715/001";
		$no_pb_oli_reguler 				= $this->cari_id();
		$da['no_pb_oli_reguler'] 	= $no_pb_oli_reguler;
		$da['start_date'] 			= $this->input->post("tgl_1");				
		$da['end_date'] 				= $this->input->post("tgl_2");				
		$da['fix']							= $this->input->post("fix");				
		$da['reg']							= $this->input->post("reg");				
		$da['ho']								= $this->input->post("ho");				
		$da['umum']							= $this->input->post("umum");				
		$da['urgent']						= $this->input->post("urgent");				
		$da['status_pb'] 				= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 			
			$cek 						= $_POST["cek_".$i];			
			if(isset($cek)){
				$data['no_pb_oli_reguler'] = $no_pb_oli_reguler;
				$data['no_so_oil'] = $no_so_oil		= $_POST["no_so_oil_".$i];			
				$data['amount'] 				= $_POST["amount_".$i];			
				$data['status_pb_detail'] = "input";		
				
				$cek = $this->db->query("SELECT * FROM tr_pb_oli_reguler_detail WHERE no_so_oil = '$no_so_oil' AND no_pb_oli_reguler = '$no_pb_oli_reguler'");
				if($cek->num_rows() > 0){						
					$t = $cek->row();
					$this->m_admin->update("tr_pb_oli_reguler_detail",$data,"id_detail",$t->id_detail);								
				}else{
					$this->m_admin->insert("tr_pb_oli_reguler_detail",$data);								
				}						
			}			
		}
		
		$ce = $this->db->query("SELECT * FROM tr_pb_oli_reguler WHERE no_pb_oli_reguler = '$no_pb_oli_reguler'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_pb_oli_reguler",$da,"no_pb_oli_reguler",$no_pb_oli_reguler);								
		}else{
			$this->m_admin->insert("tr_pb_oli_reguler",$da);								
		}				

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/pb_oli_reguler'>";
	}	

}