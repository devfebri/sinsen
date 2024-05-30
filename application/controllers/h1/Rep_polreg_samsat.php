<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_polreg_samsat extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_polreg_samsat";	
	var $isi		=		"laporan_4";	
	var $title  =   "Polreg Samsat";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
		$this->load->library('pdf');		

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
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
		$id_user	= $this->session->userdata('id_user');			
		$this->m_admin->delete("tr_lap_polreg","created_by",$id_user);
		$this->template($data);		    	    
	}		
	public function getDetail()
	{
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');			
		$data['dt_polreg']=$this->db->query("SELECT * FROM tr_lap_polreg INNER JOIN ms_tipe_kendaraan ON tr_lap_polreg.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_lap_polreg.created_by = '$login_id'");	
		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
		$this->load->view('h1/report/t_polreg',$data);
	}
	public function save(){
		$id_tipe_kendaraan = $data['id_tipe_kendaraan'] 	= $this->input->post("id_tipe_kendaraan");
		$data['created_at'] 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$id_user =  $data['created_by']					= $this->session->userdata('id_user');			
		$cek = $this->db->query("SELECT * FROM tr_lap_polreg WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND created_by = '$id_user'");
		if($cek->num_rows() > 0){
			$this->m_admin->update("tr_lap_polreg",$data,"id_pol",$cek->row()->id_pol);
		}else{
			$this->m_admin->insert("tr_lap_polreg",$data);
		}
		echo "nihil";
	}
	public function delete(){
		$id_pol	= $this->input->post("id_pol");			
		$this->m_admin->delete("tr_lap_polreg","id_pol",$id_pol);
		echo "nihil";
	}
	public function download()
	{								
		$data['tgl1'] = $this->input->post('tgl1');
		$data['tgl2'] = $this->input->post('tgl2');				
		$this->load->view('h1/report/template/temp_polreg_samsat',$data);
	}
}