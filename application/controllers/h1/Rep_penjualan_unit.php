<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Rep_penjualan_unit extends CI_Controller {

	

	var $folder =   "h1/report";

	var $page		=		"rep_penjualan_unit";	

	var $isi		=		"laporan_1";	

	var $title  =   "Data Penjualan Unit";



	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');		

		//===== Load Library =====		

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

		$data['dt_vendor'] = $this->db->query("SELECT DISTINCT(ms_vendor.vendor_name),ms_vendor.id_vendor FROM ms_unit_transporter INNER JOIN ms_vendor ON ms_unit_transporter.id_vendor = ms_vendor.id_vendor ORDER BY ms_vendor.id_vendor ASC");

		$data['dt_tipe'] = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 ORDER BY ms_tipe_kendaraan.id_tipe_kendaraan ASC");

		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 AND h1=1 ORDER BY nama_dealer ASC");

		$data['dt_item'] = $this->db->query("SELECT * FROM ms_item WHERE active = 1 ORDER BY id_tipe_kendaraan ASC");

		$this->template($data);		    	    

	}		

	public function	download(){				

		$data['tgl1'] = $tgl2		= $this->input->post('tgl1');

		$data['tgl2'] = $tgl1		= $this->input->post('tgl2');				

		$data['id_dealer'] = $id_dealer		= $this->input->post('id_dealer');				

		$data['id_item'] = $id_item		= $this->input->post('id_item');				

		$data['id_tipe_kendaraan'] = $id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');	

					

		$this->load->view("h1/report/template/temp_penjualan_unit",$data);

	}	

}