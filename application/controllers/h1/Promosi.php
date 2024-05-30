<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Promosi extends CI_Controller {
    var $tables =   "tr_promosi";	
		var $folder =   "h1";
		var $page		=		"promosi";
    var $pk     =   "id_promosi";
    var $title  =   "Promosi";
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
		$data['dt_pl']	= $this->db->query("SELECT * FROM tr_promosi INNER JOIN ms_program_promosi ON tr_promosi.id_program_promosi=ms_program_promosi.id_program_promosi
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
		$kode = $this->m_admin->cari_id($this->tables,"id_promosi");		 
		echo $kode;
	}
	public function cari_harga(){				
		$id_item_promosi = $this->input->post("id_item_promosi");		 
		$item = $this->m_admin->getByID("ms_item_promosi","id_item_promosi",$id_item_promosi)->row();		 
		echo $item->harga_beli;
	}
	public function cari_total(){				
		$id_promosi = $this->input->post("id_promosi");		 
		$item 			= $this->db->query("SELECT SUM(ms_item_promosi.harga_beli) AS total FROM tr_promosi_biaya 
									INNER JOIN ms_item_promosi ON tr_promosi_biaya.id_item_promosi=ms_item_promosi.id_item_promosi WHERE tr_promosi_biaya.id_promosi = '$id_promosi'")->row();		 
		echo $item->total;
	}	
	public function t_biaya(){
		$id = $this->input->post('id_promosi');
		$dq = "SELECT tr_promosi_biaya.*,ms_vendor.id_vendor,ms_vendor.vendor_name,ms_item_promosi.item_promosi,ms_item_promosi.harga_beli FROM tr_promosi_biaya INNER JOIN ms_vendor ON tr_promosi_biaya.id_vendor=ms_vendor.id_vendor 
						INNER JOIN ms_item_promosi ON tr_promosi_biaya.id_item_promosi=ms_item_promosi.id_item_promosi 
						WHERE tr_promosi_biaya.id_promosi = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$this->load->view('h1/t_promosi_biaya',$data);
	}
	public function t_dealer(){
		$id = $this->input->post('id_promosi');
		$dq = "SELECT * FROM tr_promosi_dealer INNER JOIN ms_dealer ON tr_promosi_dealer.id_dealer=ms_dealer.id_dealer WHERE tr_promosi_dealer.id_promosi = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$this->load->view('h1/t_promosi_dealer',$data);
	}
	public function t_tipe(){
		$id = $this->input->post('id_promosi');
		$dq = "SELECT * FROM tr_promosi_tipe INNER JOIN ms_tipe_kendaraan ON tr_promosi_tipe.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan WHERE tr_promosi_tipe.id_promosi = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$this->load->view('h1/t_promosi_tipe',$data);
	}
	public function t_leasing(){
		$id = $this->input->post('id_promosi');
		$dq = "SELECT * FROM tr_promosi_leasing INNER JOIN ms_finance_company ON tr_promosi_leasing.id_finance_company=ms_finance_company.id_finance_company WHERE tr_promosi_leasing.id_promosi = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$this->load->view('h1/t_promosi_leasing',$data);
	}
	public function save_dealer(){
		$id_promosi						= $this->input->post('id_promosi');			
		$id_dealer						= $this->input->post('id_dealer');
		$data['id_promosi']		= $this->input->post('id_promosi');			
		$data['id_dealer']		= $this->input->post('id_dealer');					
		$cek = $this->db->get_where("tr_promosi_dealer",array("id_promosi"=>$id_promosi,"id_dealer"=>$id_dealer));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_promosi_dealer;
			$this->m_admin->update("tr_promosi_dealer",$data,"id_promosi_dealer",$id);			
		}else{
			$this->m_admin->insert("tr_promosi_dealer",$data);			
		}
		echo "nihil";
	}
	public function save_tipe(){
		$id_promosi						= $this->input->post('id_promosi');			
		$id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');
		$data['id_promosi']		= $this->input->post('id_promosi');			
		$data['id_tipe_kendaraan']		= $this->input->post('id_tipe_kendaraan');					
		$data['qty_target']		= $this->input->post('qty_target');					
		$cek = $this->db->get_where("tr_promosi_tipe",array("id_promosi"=>$id_promosi,"id_tipe_kendaraan"=>$id_tipe_kendaraan));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_promosi_tipe;
			$this->m_admin->update("tr_promosi_tipe",$data,"id_promosi_tipe",$id);			
		}else{
			$this->m_admin->insert("tr_promosi_tipe",$data);			
		}
		echo "nihil";
	}
	public function save_leasing(){
		$id_promosi						= $this->input->post('id_promosi');			
		$id_finance_company		= $this->input->post('id_finance_company');
		$data['id_promosi']		= $this->input->post('id_promosi');			
		$data['id_finance_company']		= $this->input->post('id_finance_company');							
		$cek = $this->db->get_where("tr_promosi_leasing",array("id_promosi"=>$id_promosi,"id_finance_company"=>$id_finance_company));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_promosi_leasing;
			$this->m_admin->update("tr_promosi_leasing",$data,"id_promosi_leasing",$id);			
		}else{
			$this->m_admin->insert("tr_promosi_leasing",$data);			
		}
		echo "nihil";
	}	
	public function save_biaya(){
		$id_promosi						= $this->input->post('id_promosi');			
		$id_vendor						= $this->input->post('id_vendor');
		$data['id_promosi']		= $this->input->post('id_promosi');			
		$data['id_vendor']		= $this->input->post('id_vendor');							
		$data['id_item_promosi']		= $this->input->post('id_item_promosi');							
		$data['qty']					= $this->input->post('qty');							
		$data['ppn']					= $this->input->post('ppn');							
		$data['keterangan']		= $this->input->post('keterangan');							
		$cek = $this->db->get_where("tr_promosi_biaya",array("id_promosi"=>$id_promosi,"id_vendor"=>$id_vendor));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_promosi_biaya;
			$this->m_admin->update("tr_promosi_biaya",$data,"id_promosi_biaya",$id);			
		}else{
			$this->m_admin->insert("tr_promosi_biaya",$data);			
		}
		echo "nihil";
	}
	public function reset_dealer(){
		$id_promosi = $this->input->post('id_promosi');		
		$this->db->query("DELETE FROM tr_promosi_dealer WHERE id_promosi = '$id_promosi'");			
		echo "nihil";
	}
	public function reset_tipe(){
		$id_promosi = $this->input->post('id_promosi');		
		$this->db->query("DELETE FROM tr_promosi_tipe WHERE id_promosi = '$id_promosi'");			
		echo "nihil";
	}
	public function reset_leasing(){
		$id_promosi = $this->input->post('id_promosi');		
		$this->db->query("DELETE FROM tr_promosi_leasing WHERE id_promosi = '$id_promosi'");			
		echo "nihil";
	}
	public function delete_dealer(){
		$id_promosi_dealer = $this->input->post('id_promosi_dealer');		
		$this->db->query("DELETE FROM tr_promosi_dealer WHERE id_promosi_dealer = '$id_promosi_dealer'");			
		echo "nihil";
	}
	public function delete_tipe(){
		$id_promosi_tipe = $this->input->post('id_promosi_tipe');		
		$this->db->query("DELETE FROM tr_promosi_tipe WHERE id_promosi_tipe = '$id_promosi_tipe'");			
		echo "nihil";
	}
	public function delete_leasing(){
		$id_promosi_leasing = $this->input->post('id_promosi_leasing');		
		$this->db->query("DELETE FROM tr_promosi_leasing WHERE id_promosi_leasing = '$id_promosi_leasing'");			
		echo "nihil";
	}
	public function delete_biaya(){
		$id_promosi_biaya = $this->input->post('id_promosi_biaya');		
		$this->db->query("DELETE FROM tr_promosi_biaya WHERE id_promosi_biaya = '$id_promosi_biaya'");			
		echo "nihil";
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$data['id_promosi'] 				= $this->input->post('no_penerimaan_promosi');		
		$data['id_program_promosi'] = $this->input->post('id_program_promosi');		
		$data['id_jenis_promosi'] 	= $this->input->post('id_jenis_promosi');						
		$data['no_reg'] 						= $this->input->post('no_reg');						
		$data['tema'] 							= $this->input->post('tema');						
		$data['tgl_reg'] 						= $this->input->post('tgl_reg');						
		$data['tgl_mulai'] 					= $this->input->post('tgl_mulai');						
		$data['tgl_selesai'] 				= $this->input->post('tgl_selesai');						
		$data['lokasi'] 						= $this->input->post('lokasi');						
		$data['ahm'] 								= $this->input->post('ahm');						
		$data['main_dealer'] 				= $this->input->post('main_dealer');						
		$data['dealer'] 						= $this->input->post('dealer');						
		$data['presentase_d'] 			= $this->input->post('presentase_d');						
		$data['rupiah_d'] 					= $this->input->post('rupiah_d');						
		$data['presentase_o'] 			= $this->input->post('presentase_o');						
		$data['rupiah_o'] 					= $this->input->post('rupiah_o');						
		$data['dealer_ikut'] 				= $this->input->post('dealer_ikut');						
		$data['tipe_ikut'] 					= $this->input->post('tipe_ikut');						
		$data['leasing_ikut'] 			= $this->input->post('leasing_ikut');						
		$data['total_harga'] 				= $this->input->post('total_harga');						
		$data['no_po'] 							= $this->input->post('no_po');						
		$data['id_cara_bayar'] 			= $this->input->post('id_cara_bayar');						
		$data['judul'] 							= $this->input->post('judul');						
		$data['latar_belakang'] 		= $this->input->post('latar_belakang');						
		$data['jenis_proposal'] 		= $this->input->post('jenis_proposal');						
		$data['isi'] 								= $this->input->post('isi');						
		$data['approval'] 					= $this->input->post('approval');						
		$data['penutup'] 						= $this->input->post('penutup');						
		$data['status'] 						= "input";
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/promosi/add'>";		
	}
	public function delete(){
		$id = $this->input->get('id');		
		$this->db->query("DELETE FROM tr_promosi_leasing WHERE id_promosi = '$id'");			
		$this->db->query("DELETE FROM tr_promosi_biaya WHERE id_promosi = '$id'");			
		$this->db->query("DELETE FROM tr_promosi_tipe WHERE id_promosi = '$id'");			
		$this->db->query("DELETE FROM tr_promosi_dealer WHERE id_promosi = '$id'");			
		$this->db->query("DELETE FROM tr_promosi WHERE id_promosi = '$id'");			
		$_SESSION['pesan'] 		= "Data has been deleted successfully";
		$_SESSION['tipe'] 		= "danger";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/promosi'>";		
	}
}