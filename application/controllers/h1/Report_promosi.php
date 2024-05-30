<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_promosi extends CI_Controller {
    var $tables =   "tr_report_promosi";	
		var $folder =   "h1";
		var $page		=		"report_promosi";
    var $pk     =   "id_report_promosi";
    var $title  =   "Report Promosi";
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
		$data['dt_report'] = $this->db->query("SELECT * FROM tr_report_promosi INNER JOIN tr_promosi ON tr_report_promosi.no_reg=tr_promosi.no_reg
						INNER JOIN ms_jenis_promosi ON tr_promosi.id_jenis_promosi=ms_jenis_promosi.id_jenis_promosi");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}			
	public function cari_id(){				
		$kode = $this->m_admin->cari_id($this->tables,"id_report_promosi");		 
		echo $kode;
	}
	public function ambil_noreg(){				
		$no_reg = $this->input->post("no_reg");		 
		$no_reg = $this->m_admin->getByID("tr_promosi","no_reg",$no_reg)->row();		 
		echo $no_reg->lokasi."|".$no_reg->tgl_mulai."|".$no_reg->tgl_selesai;
	}	
	public function save()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_report_promosi	=	$this->input->post("id_report_promosi");
		$ket	=	$this->input->post("ket");
		foreach($ket AS $key => $val){
			$uploaddir = 'assets/panel/files/';
			$fileName = $_FILES['nama_file']['name'][$key];     
			$tmpName  = $_FILES['nama_file']['tmp_name'][$key]; 
			$uploadfile = $uploaddir.$fileName;				
			move_uploaded_file($tmpName,$uploadfile);			
			$resultc2[] = array(
				"id_report_promosi"  	=> $id_report_promosi,
				"ket"  								=> $_POST['ket'][$key],
				"filename"  					=> $uploadfile				
			);
		}
		$testb= $this->db->insert_batch('tr_report_promosi_detail', $resultc2);
		
		
		$data['id_report_promosi'] 	=	$id_report_promosi;		
		$data['id_cuaca']				=	$this->input->post("id_cuaca");
		$data['jum_orang']			=	$this->input->post("jum_orang");
		$data['deskripsi']			=	$this->input->post("deskripsi");
		$data['total']					=	$this->input->post("total");
		$data['no_reg']					=	$this->input->post("no_reg");
		$data['created_at']			=	$waktu;
		$data['created_by']			=	$login_id;
		$r = $this->m_admin->getById($tabel,$pk,$id_report_promosi);
		if($r->num_rows() > 0){
			$this->m_admin->update($tabel,$data,$pk,$id_report_promosi);
		}else{
			$this->m_admin->insert($tabel,$data);
		}		
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/report_promosi/add'>";		
	}
}