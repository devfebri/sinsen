<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_do_sim_part extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"create_do_sim_part";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Create DO SIM Part";

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
		$data['dt_pb_sim_part'] = $this->db->query("SELECT * FROM tr_pb_sim_part_detail INNER JOIN tr_pb_sim_part ON tr_pb_sim_part_detail.no_pb_sim_part = tr_pb_sim_part.no_pb_sim_part 
				INNER JOIN tr_so_part ON tr_pb_sim_part_detail.no_so_part = tr_so_part.no_so_part
				INNER JOIN ms_dealer ON tr_so_part.id_dealer = ms_dealer.id_dealer WHERE tr_pb_sim_part.status_pb = 'input'");			
		$this->template($data);			
	}
	public function delete(){
		$id = $this->input->get('id');		
		$d 	= $this->input->get('d');		
		$this->db->query("DELETE FROM tr_pb_sim_part_detail WHERE no_so_part = '$id'");					
		$cek = $this->m_admin->getByID("tr_pb_sim_part_detail","no_pb_sim_part",$d);
		if($cek->num_rows() == 0){
			$this->db->query("DELETE FROM tr_pb_sim_part WHERE no_pb_sim_part = '$d'");						
		}
		$_SESSION['pesan'] 	= "Data has been deleted successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/create_do_sim_part'>";
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}	
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";					
		$id = $this->input->get("id");
		$data['dt_sql'] 	= $this->db->query("SELECT * FROM tr_so_part INNER JOIN ms_dealer ON tr_so_part.id_dealer = ms_dealer.id_dealer WHERE 
		 tr_so_part.no_so_part = '$id'");			
		$this->template($data);	
	}	
	public function t_detail(){				
		$no_so_part = $this->input->post('no_so_part');
		$where = "";		
		$id_part = $this->input->post('id_part');
		$kelompok_part = $this->input->post('kelompok_part');
		if($id_part != ""){
			$where .= "AND id_part = '$id_part'";
		}
		if($kelompok_part != ""){
			$where .= "AND kelompok_part = '$kelompok_part'";
		}
		$data['sql'] = $this->db->query("SELECT * FROM tr_so_part_detail LEFT JOIn tr_so_part ON tr_so_part_detail.no_so_part = tr_so_part.no_so_part 
			LEFT JOIN ms_part ON tr_so_part_detail.id_part = ms_part.id_part
			WHERE tr_so_part_detail.no_so_part = '$no_so_part' $where");
		$this->load->view('h3/t_create_sim_part',$data);
	}	
	public function detail_popup()
	{				
		$no_so_part = $data['no_so_part'] = $this->input->post("no_so_part");		
		$data['isi']    = $this->page;	
		$data['dt_sql']	= $this->db->query("SELECT * FROM tr_so_part INNER JOIN ms_dealer ON tr_so_part.id_dealer = ms_dealer.id_dealer 
			WHERE tr_so_part.no_so_part = '$no_so_part'");
		$data['title']	= $this->title;								
		$this->load->view("h3/t_pb_sim_detail_popup",$data);		
	}	
	public function cari_id(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_create_do_sim ORDER BY no_do_sim_part DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_do_sim_part)-3;
			$id 	= substr($row->no_do_sim_part,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "DOS/".$kode1;
		}else{
			$kode = "DOS/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		return $kode;
	}	
	
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$no_do_sim_part 				= $this->cari_id();
		$da['no_do_sim_part'] 	= $no_do_sim_part;
		$da['no_so_part'] 			= $this->input->post("no_so_part");				
		$da['plafon'] 				= $this->input->post("plafon");				
		$da['plafon_booking']							= $this->input->post("plafon_booking");				
		$da['sisa_plafon']							= $this->input->post("sisa_plafon");				
		$da['status_do']								= "input";				
		$da['tgl_do']							= $tgl;		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 						
			$data['no_do_sim_part'] = $no_do_sim_part;
			$data['id_part'] = $id_part		= $_POST["id_part_".$i];			
			$data['amount'] 				= $_POST["amount_".$i];			
			$data['disc_satuan'] 				= $_POST["disc_satuan_".$i];			
			$data['disc_campaign'] 				= $_POST["disc_campaign_".$i];			
			$data['qty_pb'] 				= $_POST["qty_suggest_".$i];			
			$data['qty_supply'] 				= $_POST["qty_supply_".$i];			
				
			$cek = $this->db->query("SELECT * FROM tr_create_do_sim_detail WHERE id_part = '$id_part' AND no_do_sim_part = '$no_do_sim_part'");
			if($cek->num_rows() > 0){						
				$t = $cek->row();
				$this->m_admin->update("tr_create_do_sim_detail",$data,"id_detail",$t->id_detail);								
			}else{
				$this->m_admin->insert("tr_create_do_sim_detail",$data);								
			}											
		}
		
		$ce = $this->db->query("SELECT * FROM tr_create_do_sim WHERE no_do_sim_part = '$no_do_sim_part'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_create_do_sim",$da,"no_do_sim_part",$no_do_sim_part);								
		}else{
			$this->m_admin->insert("tr_create_do_sim",$da);								
		}				

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/create_do_sim_part'>";
	}	

}