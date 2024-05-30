<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ustk extends CI_Controller {

	var $tables =   "tr_ustk";	
	var $folder =   "h1";
	var $page		=		"ustk";
	var $pk     =   "id_ustk";
	var $title  =   "USTK";

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
		$data['set']		= "generate";
		$data['dt_ustk'] = $this->m_admin->getAll($this->tables);			
		$this->template($data);		
	}
	public function generate()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "generate";		
		$this->template($data);		
	}	
	public function t_detail(){
		$start_date 	= $this->input->post('start_date');
		$end_date 		= $this->input->post('end_date');
		
		$dq = "SELECT * FROM tr_sales_order INNER JOIN tr_faktur_stnk_detail ON tr_sales_order.no_mesin = tr_faktur_stnk_detail.no_mesin
				INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
				WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.create_ustk_by IS NULL OR tr_sales_order.create_ustk_by = 0 OR tr_sales_order.create_ssu_by IS NULL)
				AND (tr_sales_order.status_so = 'so_invoice' OR tr_sales_order.tgl_cetak_invoice IS NOT NULL OR tr_sales_order.tgl_cetak_invoice2 IS NOT NULL)";
		$data['dt_detail'] = $this->db->query($dq);
		$this->load->view('h1/t_ustk',$data);
	}
	public function cari_id(){				
		$th 						= date("y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_ustk ORDER BY id_ustk DESC LIMIT 0,1");						       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_ustk,2,5); 
	    $kode = $th.sprintf("%05d", $id+1);
		}else{
			$kode = $th."00001";
		}
		return $kode;
	}
	function create(){					
		$tgl 		= gmdate("dmY", time()+60*60*7);				
		$tgl2 		= gmdate("Ymd", time()+60*60*7);				
		$id_ustk								= $this->cari_id();
		$nama_file						= "AHM-E20-".$tgl."-".$tgl2;
		$start_date						= $this->input->post('start_date');
		$end_date							= $this->input->post('end_date');					
		$tanggal							= gmdate("Y-m-d", time()+60*60*7);    
		$login_id							= $this->session->userdata('id_user');
		$sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_faktur_stnk_detail ON tr_sales_order.no_mesin = tr_faktur_stnk_detail.no_mesin
			WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' AND (tr_sales_order.create_ustk_by IS NULL OR tr_sales_order.create_ustk_by = 0)
			AND tr_sales_order.status_so = 'so_invoice'");
		foreach ($sql->result() as $isi) {						
			$da['no_mesin']		= $isi->no_mesin;
			$da['id_ustk']		= $id_ustk;
			$cek1 = $this->m_admin->insert("tr_ustk_detail",$da);											

			$dat['create_ustk_by']	= $end_date;
			$dat['tgl_create_ustk'] = $login_id;
			$cek3 = $this->m_admin->update("tr_sales_order",$dat,"no_mesin",$isi->no_mesin);											
		}

		$data['id_ustk']			= $id_ustk;
		$data['start_date']		= $start_date;
		$data['end_date']			= $end_date;
		$data['nama_file']		= $nama_file.".USTK";
		$cek2 = $this->m_admin->insert("tr_ustk",$data);											

		$dt['no'] 		= $nama_file;
		$dt['id_ustk'] = $id_ustk;				
		$this->load->view("h1/file_ustk",$dt);
	}
	public function download()
	{					
		$id_ustk				= $this->input->get('id');
		$tr = $this->m_admin->getByID("tr_ustk","id_ustk",$id_ustk)->row();
		$dt['no'] 		= $tr->nama_file;
		$dt['id_ustk'] = $id_ustk;				
		$this->load->view("h1/file_ustk",$dt);
		
	}
}