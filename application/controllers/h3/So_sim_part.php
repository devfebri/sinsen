<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class So_sim_part extends CI_Controller {

	var $tables =   "tr_pemenuhan_po";	
	var $folder =   "h3";
	var $page		=		"so_sim_part";
	var $pk     =   "no_pemenuhan_po";
	var $title  =   "SO SIM Part Dealer";

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
		$data['set']	= "view";
		$data['dt_so_part'] = $this->db->query("SELECT * FROM tr_so_part LEFT JOIN ms_dealer ON tr_so_part.id_dealer = ms_dealer.id_dealer");			
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}
	public function download()
	{				
		$this->load->view('h3/template_so_sim_part');
	}
	public function cari_dealer(){
		$id_dealer = $this->input->post('id_dealer');
		$cek = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer);
		if($cek->num_rows() > 0){
			$rt = $cek->row();
			$id_dealer = $rt->id_dealer;
			$kode_dealer_md = $rt->kode_dealer_md;
			$alamat = $rt->alamat;
		}else{			
			$id_dealer = "";
			$kode_dealer_md = "";
			$alamat = "";
		}
		echo $id_dealer."|".$kode_dealer_md."|".$alamat;
	}
	public function cari_no_sim(){		
		$th 						= date("y");
		$bln 						= date("m");		
		$tgl 						= date("d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_so_part ORDER BY no_so_part DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_so_part)-3;
			$id 	= substr($row->no_so_part,$pan,3)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."/00".$id;          
		  }elseif($id>9 && $id<=99){
				$kode1 = $th.$bln.$tgl."/0".$id;                   		  
		  }
			$kode = "SOSM/".$kode1;
		}else{
			$kode = "SOSM/".$th.$bln.$tgl."/001";
		} 	
		//$kode = $this->m_admin->cari_id("tr_pemenuhan_po","no_pemenuhan_po");
		echo $kode;
	}	
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";			
		$id 						= $this->input->get("id");
		$data['dt_sql'] = $this->db->query("SELECT * FROM tr_so_part LEFT JOIN ms_dealer ON tr_so_part.id_dealer = ms_dealer.id_dealer 
			WHERE no_so_part = '$id'");	
		$data['dt_dealer'] 	= $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$this->template($data);	
	}	
	public function delete_sim(){
		$id_so_part_detail = $this->input->post('id_so_part_detail');		
		$this->db->query("DELETE FROM tr_so_part_detail WHERE id_so_part_detail = '$id_so_part_detail'");			
		echo "nihil";
	}
	public function t_detail(){		
		$data['isi'] 		= "tes";
		$no_so_part = $this->input->post('no_so_part');
		$data['sql'] = $this->db->query("SELECT * FROM tr_so_part_detail LEFT JOIN ms_sim_part ON tr_so_part_detail.id_part = ms_sim_part.id_part
			LEFT JOIN ms_part ON ms_sim_part.id_part = ms_part.id_part
			WHERE tr_so_part_detail.no_so_part = '$no_so_part'");
		$this->load->view('h3/t_so_sim_part',$data);
	}
	public function cek_part(){
		$id 	= $this->input->post("id_part");
		$kode = $this->db->query("SELECT * FROM ms_part INNER JOIN ms_sim_part ON ms_part.id_part = ms_sim_part.id_part WHERE ms_part.id_part = '$id'")->row();		
		if(isset($kode->nama_part)){
			$nama_part = $kode->nama_part;
		}else{
			$nama_part = "";
		}
		if(isset($kode->harga_md_dealer)){
			$harga = $kode->harga_md_dealer;
		}else{	
			$harga = "0";
		}
		echo $id."|".$nama_part."|".$harga."|".$kode->qty_sim_part;
	}
	public function save_sim(){
		
		$id_part		= $this->input->post('id_part');					
		$no_so_part			= $this->input->post('no_so_part');					
		$data['id_part']			= $this->input->post('id_part');			
		$data['no_so_part']		= $this->input->post('no_so_part');		
		$data['het']		= $this->input->post('het');		
		$data['qty_order']				= $this->input->post('qty_order');		
		$data['qty_sim_part']			= $this->input->post('qty_sim_part');		
		$data['qty_avs_md']			= $this->input->post('qty_avs_md');				
		
		$cek = $this->db->get_where("tr_so_part_detail",array("id_part"=>$id_part,"no_so_part"=>$no_so_part));
		if($cek->num_rows() > 0){	
			$sq = $cek->row();
			$id = $sq->id_so_part_detail;
			$this->m_admin->update("tr_so_part_detail",$data,"id_so_part_detail",$id);			
		}else{
			$this->m_admin->insert("tr_so_part_detail",$data);			
		}
		echo "nihil";
	}	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$save					= $this->input->post("save");		
		if($save == 'approve'){
			$id					= $this->input->post("no_so_part");		
			$data['status_so'] 				= "approved";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update("tr_so_part",$data,"no_so_part",$id);		
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/so_sim_part'>";		
		}elseif($save=='reject'){
			$id					= $this->input->post("no_so_part");		
			$data['status_so'] 				= "rejected";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update("tr_so_part",$data,"no_so_part",$id);		
			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/so_sim_part'>";		
		}else{

		}
	}
	public function simpan()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$save				= $this->input->post("save");				
	  $no_so_part = $data['no_so_part']				= $this->input->post("no_so_part");		
		$data['masa_berlaku']			= $this->input->post("masa_berlaku");		
		$data['id_dealer']				= $this->input->post("id_dealer");		
		$data['tipe_po']					= $this->input->post("tipe_po");		
		$data['tgl_so']						= $tgl;		
		$data['status_so'] 				= "input";			
		$data['created_at']				= $waktu;		
		$data['created_by']				= $login_id;		
		$cek = $this->db->get_where("tr_so_part",array("no_so_part"=>$no_so_part));
		if($cek->num_rows() > 0){	
			$sq = $cek->row();
			$id = $sq->no_so_part;
			$this->m_admin->update("tr_so_part",$data,"no_so_part",$no_so_part);			
		}else{
			$this->m_admin->insert("tr_so_part",$data);			
		}
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/so_sim_part'>";				
	}	
}