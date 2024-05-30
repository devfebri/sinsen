<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Penerimaan_promosi extends CI_Controller {
    var $tables =   "tr_penerimaan_promosi";	
		var $folder =   "h1";
		var $page		=		"penerimaan_promosi";
    var $pk     =   "no_penerimaan_promosi";
    var $title  =   "Penerimaan Promosi";
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
		$data['isi']    = "penerimaan_promosi";		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$data['dt_promosi'] = $this->db->query("SELECT * FROM tr_penerimaan_promosi INNER JOIN ms_vendor ON tr_penerimaan_promosi.id_vendor=ms_vendor.id_vendor");
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
		$kode = $this->m_admin->cari_id($this->tables,"no_penerimaan_promosi");		 
		echo $kode;
	}
	public function cek_kategori(){				
		$id_item_promosi = $this->input->post("id_item_promosi");		 
		$ids = $this->db->query("SELECT * FROM ms_kategori_item INNER JOIN ms_item_promosi ON ms_item_promosi.id_kategori_item = ms_kategori_item.id_kategori_item
					WHERE ms_item_promosi.id_item_promosi = '$id_item_promosi'");
		if($ids->num_rows() > 0){
			$rt = $ids->row();
			$kategori_item = $rt->kategori_item;
		}else{
			$kategori_item = "";
		}
		echo $kategori_item;
	}
	public function t_data(){
		$id = $this->input->post('no_penerimaan_promosi');
		$mode = $this->input->post('mode');
		$dq = "SELECT * FROM tr_penerimaan_promosi_detail
						WHERE tr_penerimaan_promosi_detail.no_penerimaan_promosi = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$data['mode']			= $mode;
		$this->load->view('h1/t_penerimaan_promosi',$data);
	}
	public function save_data(){
		$no_penerimaan_promosi			= $this->input->post('no_penerimaan_promosi');			
		$id_item_promosi						= $this->input->post('id_item_promosi');
		$data['id_item_promosi']		= $this->input->post('id_item_promosi');			
		$data['id_kategori_item']		= $this->input->post('id_kategori_item');			
		$data['no_penerimaan_promosi']	= $this->input->post('no_penerimaan_promosi');					
		$data['qty_terima']					= $this->input->post('qty_terima');							
		$cek = $this->db->get_where("tr_penerimaan_promosi_detail",array("no_penerimaan_promosi"=>$no_penerimaan_promosi,"id_item_promosi"=>$id_item_promosi));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_penerimaan_promosi_detail;
			$this->m_admin->update("tr_penerimaan_promosi_detail",$data,"id_penerimaan_promosi_detail",$id);			
		}else{
			$this->m_admin->insert("tr_penerimaan_promosi_detail",$data);			
		}
		echo "nihil";
	}
	public function delete_data(){
		$id_penerimaan_promosi_detail = $this->input->post('id_penerimaan_promosi_detail');		
		$this->db->query("DELETE FROM tr_penerimaan_promosi_detail WHERE id_penerimaan_promosi_detail = '$id_penerimaan_promosi_detail'");			
		echo "nihil";
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$data['no_penerimaan_promosi'] 	= $this->input->post('no_penerimaan_promosi');		
		$data['id_vendor'] 					= $this->input->post('id_vendor');		
		$data['no_surat_jalan'] 		= $this->input->post('no_surat_jalan');						
		$data['tgl_sj'] 						= $this->input->post('tgl_sj');						
		$data['tgl_penerimaan'] 		= $this->input->post('tgl_penerimaan');						
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_promosi/add'>";		
	}
	public function edit()
	{				
		$id = $this->input->get('id');
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;															
		$data['set']		= "edit";
		$data['dt_promosi'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$this->template($data);			
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$id 	= $this->input->post('no_penerimaan_promosi');		
		$data['no_penerimaan_promosi'] 	= $this->input->post('no_penerimaan_promosi');		
		$data['id_vendor'] 					= $this->input->post('id_vendor');		
		$data['no_surat_jalan'] 		= $this->input->post('no_surat_jalan');						
		$data['tgl_sj'] 						= $this->input->post('tgl_sj');						
		$data['tgl_penerimaan'] 		= $this->input->post('tgl_penerimaan');						
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;
		$this->m_admin->update($tabel,$data,$this->pk,$id);
		$_SESSION['pesan'] 		= "Data has been updated successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penerimaan_promosi'>";		
	}
	public function view()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";				
		$id = $this->input->get('id');
		$data['dt_promosi'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$this->template($data);			
	}
}