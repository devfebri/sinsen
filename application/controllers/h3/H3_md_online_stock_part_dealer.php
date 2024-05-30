<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_online_stock_part_dealer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_online_stock_part_dealer";
    protected $title  = "Online Stock Part Dealer";

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
		$this->load->library('form_validation');

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

	public function cetak()
  	{
		$id_dealer = $this->input->post('id_dealer');

		$data['nama_dealer'] = $this->db->select('nama_dealer')
										->from('ms_dealer')
										->where('id_dealer',$id_dealer)
										->get()->row();

        $data['details'] = $this->db->query("select a.id_part,mp.nama_part,mp.kelompok_vendor,a.stock,a.id_rak,a.id_gudang,mp.harga_md_dealer,(a.stock * harga_md_dealer) as jumlah_beli, a.id_dealer,
			mp.harga_dealer_user,(a.stock * harga_dealer_user) as jumlah_jual,mp.kelompok_part,mp.rank,mp.status 
			from ms_part mp join ms_h3_dealer_stock a on a.id_part=mp.id_part where a.id_dealer ='$id_dealer' and mp.kelompok_part !='TL' order by a.id_rak ASC")->result();

		$this->load->view("h3/laporan/laporan_stock_dealer",$data);
  	}
}