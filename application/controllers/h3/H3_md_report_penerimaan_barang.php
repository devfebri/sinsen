<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_report_penerimaan_barang extends CI_Controller{
	
	var $folder ="h3";
	var $page   ="h3_md_report_penerimaan_barang";	
	var $isi    ="Report Penerimaan Barang H3 MD";	
	var $title  ="Report Penerimaan Barang H3 MD";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
        $this->load->model('h3_md_report_penerimaan_barang_model','report_penerimaan');	
		//===== Load Library =====		
		// $this->load->library('pdf');		
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

	protected function template($data){
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
	
	public function index(){	
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$this->template($data);		    	    
	}	

	public function downloadExcel(){
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['type']      = $type	    = $this->input->post('type');

		if($type=='status_closed'){
			$data['status_closed'] = $this->report_penerimaan->penerimaan_status_closed($start_date,$end_date);
			if($_POST['process']=='excel'){
				$this->load->view("h3/laporan/h3_md_report_penerimaan_h3_status_closed",$data);
			}
		}
    }
}
?>