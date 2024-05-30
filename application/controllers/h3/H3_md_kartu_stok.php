<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_kartu_stok extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_kartu_stok";
    protected $title  = "Kartu Stok";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====

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

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = 'form';

		$data['kartu_stok'] = $this->db
		->select('sp.id_stok_part')
        ->select('sp.id_part')
        ->select('p.nama_part')
        ->select('g.kode_gudang')
        ->select('lr.id as id_lokasi_rak')
        ->select('lr.kode_lokasi_rak')
        ->from('tr_stok_part as sp')
        ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak')
        ->join('ms_part as p', 'p.id_part = sp.id_part')
		->join('ms_h3_md_gudang as g', 'g.id = lr.id_gudang')
		->where('sp.id_stok_part', $this->input->get('id'))
		->get()->row_array();
		
		$this->template($data);
	}
}