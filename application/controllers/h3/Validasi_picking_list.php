<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi_picking_list extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"validasi_picking_list";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "Validasi Picking List";

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
		$data['dt_validasi_picking_list'] = $this->db->query("SELECT *,ms_dealer.alamat AS alamat2 FROM tr_pl_part LEFT JOIN ms_dealer ON tr_pl_part.id_dealer = ms_dealer.id_dealer
				LEFT JOIN ms_karyawan ON tr_pl_part.id_karyawan = ms_karyawan.id_karyawan WHERE status_pl <> 'input'");		
		$this->template($data);			
	}
	public function start()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "start";
		$id = $this->input->get("id");
		$data['sql'] = $this->db->query("SELECT *,ms_dealer.alamat AS alamat2 FROM tr_pl_part LEFT JOIN ms_dealer ON tr_pl_part.id_dealer = ms_dealer.id_dealer				
				LEFT JOIN ms_karyawan ON tr_pl_part.id_karyawan = ms_karyawan.id_karyawan WHERE tr_pl_part.no_pl_part = '$id'");		
		$this->template($data);	
	}
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$this->load->view('h3/t_monitor_picking_list',$data);
	}
	public function save(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$save	= $_POST["save"];							
		if($save == 'save'){
			$no_pl_part	= $_POST["no_pl_part"];							
			$jum = $this->input->post("jum");		
			for ($i=1; $i <= $jum; $i++) { 																		
				$qty_validasi = $_POST["qty_validasi_".$i];							
				$id_part = $_POST["id_part_".$i];							
				$this->db->query("UPDATE tr_pl_part_detail SET qty_validasi = '$qty_validasi' WHERE id_part = '$id_part' AND no_pl_part = '$no_pl_part'");
			}			
			$data['updated_by'] = $waktu;
			$data['updated_at'] = $login_id;
			$data['start_pick'] = $waktu;
			$this->m_admin->update("tr_pl_part",$data,"no_pl_part",$no_pl_part);
		}elseif($save=='close'){
			$no_pl_part	= $_POST["no_pl_part"];							
			$jum = $this->input->post("jum");		
			for ($i=1; $i <= $jum; $i++) { 																		
				$qty_validasi = $_POST["qty_validasi_".$i];							
				$id_part = $_POST["id_part_".$i];							
				$this->db->query("UPDATE tr_pl_part_detail SET qty_validasi = '$qty_validasi' WHERE id_part = '$id_part' AND no_pl_part = '$no_pl_part'");
			}			
			$rt = $this->m_admin->getByID("tr_pl_part","no_pl_part",$no_pl_part)->row();
			$data['updated_by'] = $waktu;
			$data['status_pl'] = "close";
			$data['updated_at'] = $login_id;
			if(is_null($rt->start_pick)) $data['start_pick'] = $waktu;					
			$data['end_pick'] = $waktu;
			$this->m_admin->update("tr_pl_part",$data,"no_pl_part",$no_pl_part);
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/validasi_picking_list'>";
	}


	

}