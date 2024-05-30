<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Srut extends CI_Controller {
	private $filename = "import_data"; // Kita tentukan nama filenya

	var $tables =   "tr_srut";	
	var $folder =   "h1";
	var $page		=		"srut";
	var $pk     =   "no_srut";
	var $title  =   "SRUT";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_excel');				
		//===== Load Library =====
		$this->load->library('upload');		
		$this->load->library('csvimport');

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
		$data['dt_srut'] = $this->m_admin->getAll($this->tables);			
		$this->template($data);		
	}
	public function gagal()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Data SRUT Gagal";															
		$data['set']		= "gagal";
		$data['dt_srut'] = $this->m_admin->getAll("tr_srut_gagal");			
		$this->template($data);		
	}
	public function upload()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "upload";		
		$this->template($data);		
	}	
	public function import_db(){
		// // Load plugin PHPExcel nya
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$tgl_faktur = $this->input->post("tgl_faktur");

		$config['upload_path'] 		= './excel/';
		$config['allowed_types'] 	= 'xlsx';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';
				
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('userfile')){
			$filename	= "";
		}else{
			$filename	= $this->upload->file_name;
		}

		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load("excel/".$filename.""); // Load file yang telah diupload ke folder excel
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);			
		$data = array();
		$tgl 	= gmdate("Y-m-d", time()+60*60*7);		
		$numrow = 1;
		foreach($sheet as $i){			
			if($numrow > 2){						
				$no_mesin = $i['D'];
				$cek = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin);
				if($cek->num_rows() > 0){
					$data = array(
						'no_srut'=>$i['B'], 
						'no_rangka'=>$i['C'], 
						'no_mesin'=>$i['D'], 
						'no_srut_pemohon'=>$i['E'], 
						'tahun_pembuatan'=>$i['F'], 
						'tgl_upload'=>$tgl,
						'tgl_faktur'=>$tgl_faktur 
					);							
					$this->m_admin->insert('tr_srut', $data);
				}else{
					$data2 = array(
						'no_srut'=>$i['B'], 
						'no_rangka'=>$i['C'], 
						'no_mesin'=>$i['D'], 
						'no_srut_pemohon'=>$i['E'], 
						'tahun_pembuatan'=>$i['F'],
						'tgl_upload'=>$tgl,
						'tgl_faktur'=>$tgl_faktur
					);							
					$this->m_admin->insert('tr_srut_gagal', $data2);
				}				
			}			
			$numrow++; // Tambah 1 setiap kali looping
		}

		// $this->db->insert_batch('tr_srut', $data=null);
		// $this->db->insert_batch('tr_srut_gagal', $data2=null);
		//var_dump($data);
		
		$_SESSION['pesan'] 	= "Data berhasil diimport";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/srut'>";	
	}

}