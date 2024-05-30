<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terima_srut extends CI_Controller {

    var $tables =   "tr_terima_srut";	
		var $folder =   "dealer";
		var $page		=		"terima_srut";
    var $pk     =   "no_serah_terima";
    var $title  =   "Terima SRUT";

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
		$data['set']		= "view";				
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_penyerahan_srut'] = $this->db->query("SELECT * FROM tr_penyerahan_srut INNER JOIN ms_dealer ON tr_penyerahan_srut.id_dealer = ms_dealer.id_dealer
			WHERE tr_penyerahan_srut.id_dealer = '$id_dealer'
			AND tr_penyerahan_srut.status='close'
			");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function t_penyerahan_srut(){
		$data['tgl_terima'] = $this->input->post('tgl_terima');
		$data['id_dealer'] 	= $this->input->post('id_dealer');		
		$this->load->view('h1/t_penyerahan_srut',$data);
	}
	public function cari_id(){				
		$th 						= date("Y");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_penyerahan_srut ORDER BY no_serah_terima DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_serah_terima)-11;
			$id 	= substr($row->no_serah_terima,$pan,5)+1;					
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th."/".$isi."/SRSRUT";
		}else{
		 	$kode = $th."/00001/SRSRUT";
		} 			
		return $kode;
	}
	public function save()
	{		
		$tgl 			= gmdate("y-m-d", time()+60*60*7);
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel		= $this->tables;				
		$no_serah_terima 						= $this->input->post('no_serah_terima');		
		$data['no_serah_terima'] 		= $no_serah_terima;
		$data['tgl_terima'] 				= $this->input->post('tgl_terima');		
		$data['id_dealer'] 					= $this->m_admin->cari_dealer();
		$jum = $this->input->post("jum");
		for ($i=1; $i <= $jum ; $i++) { 
			if(isset($_POST["cek_nosin_".$i])){
				$data2['no_serah_terima'] = $no_serah_terima;
				$data2['no_mesin'] 				= $this->input->post("no_mesin_".$i);					
				$data2['tgl_cek'] 				= $tgl;
				$this->m_admin->insert("tr_terima_srut_detail",$data2);	
			}
		}
		$cek = $this->m_admin->getByID("tr_terima_srut","no_serah_terima",$no_serah_terima);
		if($cek->num_rows() > 0){
			$data['updated_at']					= $waktu;		
			$data['updated_by']					= $login_id;
			$this->m_admin->update($tabel,$data,"no_serah_terima",$no_serah_terima);
		}else{
			$data['created_at']					= $waktu;		
			$data['created_by']					= $login_id;
			$this->m_admin->insert($tabel,$data);
		}		
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/terima_srut'>";		
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$id = $this->input->get('id');		
		$data['dt_penyerahan_srut'] = $this->m_admin->getByID("tr_penyerahan_srut",$this->pk,$id);
		$this->template($data);			
	}
	public function terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "terima";				
		$id = $this->input->get('id');		
		$data['dt_penyerahan_srut'] = $this->m_admin->getByID("tr_penyerahan_srut",$this->pk,$id);
		$this->template($data);			
	}

}