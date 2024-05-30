<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_aksesoris extends CI_Controller {

	var $tables =   "tr_po_aksesoris";	
	var $folder =   "h1";
	var $page		=		"po_aksesoris";
	var $pk     =   "no_po_aksesoris";
	var $title  =   "PO Permintaan Aksesoris";

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
		$data['dt_po_aksesoris'] = $this->db->query("SELECT * FROM tr_po_aksesoris  ORDER BY tr_po_aksesoris.tgl_po DESC");	
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan
						ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
						ON ms_item.id_warna=ms_warna.id_warna
						WHERE ms_item.active = 1");	
		
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling WHERE active = 1");									
		$this->template($data);	
	}
	public function t_bundling(){
		$id = $this->input->post('id_paket_bundling');
		$qty = $this->input->post('qty_paket');
		$data['dt_paket'] = $this->db->query("SELECT * FROM ms_paket_bundling_detail INNER JOIN ms_part ON ms_paket_bundling_detail.id_part = ms_part.id_part 
					WHERE ms_paket_bundling_detail.id_paket_bundling = '$id'");		
		$data['qty'] 			= $qty;
		$data['id'] 			= $id;
		$this->load->view('h1/t_bundling',$data);
	}
	public function cari_id(){		
		$tgl 						= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_po_aksesoris WHERE LEFT(created_at,7) = '$tgl' ORDER BY no_po_aksesoris DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_po_aksesoris)-5;
			$id 	= substr($row->no_po_aksesoris,$pan,5)+1;					
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th."-".$bln."/POPP/".$isi;
		}else{
		 	$kode = $th."-".$bln."/POPP/00001";
		} 			
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_po_aksesoris 				= $this->cari_id();
		$da['no_po_aksesoris'] 	= strip_tags($no_po_aksesoris);
		$da['tgl_po'] 					= $tgl;				
		$da['id_paket_bundling']= $this->input->post("id_paket_bundling");
		$da['qty_paket']				= $this->input->post("qty_paket");
		$da['keterangan']				= $this->input->post("keterangan");
		$da['status_po'] 				= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 			
			$id_part 						= $_POST["id_part_".$i];			
			$data['no_po_aksesoris'] 		= $no_po_aksesoris;
			$data['id_part'] 		= $id_part;				
			$data['qty'] 				= $_POST["qty_".$i];			
			$data['harga'] 			= $_POST["harga_".$i];			
			//$this->db->query("UPDATE tr_terima_bj SET serah_bpkb = 'ya' WHERE no_mesin = '$nosin'");										

			$cek = $this->db->query("SELECT * FROM tr_po_aksesoris_detail WHERE id_part = '$id_part' AND no_po_aksesoris = '$no_po_aksesoris'");
			if($cek->num_rows() > 0){						
				$t = $cek->row();
				$this->m_admin->update("tr_po_aksesoris_detail",$data,"id_po_aksesoris",$t->id_po_aksesoris);								
			}else{
				$this->m_admin->insert("tr_po_aksesoris_detail",$data);								
			}					
		}
			
		$ce = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE no_po_aksesoris = '$no_po_aksesoris'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_po_aksesoris",$da,"no_po_aksesoris",$no_po_aksesoris);								
		}else{
			$this->m_admin->insert("tr_po_aksesoris",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_aksesoris'>";
	}
	public function approve()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");		
		$data['status_po'] 				= "approved";			
		$data['updated_at'] = $data['approved_at']				= $waktu;		
		$data['updated_by'] = $data['approved_by']				= $login_id;		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_aksesoris'>";		
	}
	public function reject()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
			
		$id					= $this->input->get("id");			
		$data['status_po'] 	= "rejected";			
		$data['updated_at'] = $data['rejected_at']				= $waktu;		
		$data['updated_by'] = $data['rejected_by']				= $login_id;		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_aksesoris'>";		
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "detail";			
		$id 						= $this->input->get('id');
		$data['dt_paket'] = $this->db->query("SELECT * FROM tr_po_aksesoris_detail INNER JOIN ms_part ON tr_po_aksesoris_detail.id_part=ms_part.id_part 
				WHERE no_po_aksesoris = '$id'");									
		$data['dt_pemenuhan_po'] = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE no_po_aksesoris = '$id'");									
		$this->template($data);	
	}
	public function penerimaan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "penerimaan";			
		$id 						= $this->input->get('id');
		$data['dt_paket'] = $this->db->query("SELECT * FROM tr_po_aksesoris_detail INNER JOIN ms_part ON tr_po_aksesoris_detail.id_part=ms_part.id_part 
				WHERE no_po_aksesoris = '$id'");									
		$data['dt_pemenuhan_po'] = $this->db->query("SELECT * FROM tr_po_aksesoris WHERE no_po_aksesoris = '$id'");									
		$this->template($data);	
	}
	public function save_penerimaan()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$id 										= $this->input->post("id");				
		$da['status_po'] 				= "diterima";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		$cek = 0;
		for ($i=1; $i <= $jum; $i++) { 			
			$id_part 										= $_POST["id_part_".$i];						
			$data['id_part'] 						= $id_part;				
			$data['terima'] 						= $_POST["terima_".$i];									
			$terima 										= $_POST["terima_".$i];									
			$qty_po 										= $_POST["qty_po_".$i];									

			// if($qty_po > $terima){
			if($qty_po < $terima){
				$cek++;
			}else{				
				$cek1 = $this->db->query("SELECT * FROM tr_po_aksesoris_detail WHERE id_part = '$id_part' AND no_po_aksesoris = '$id'");
				$t = $cek1->row();
				$this->m_admin->update("tr_po_aksesoris_detail",$data,"id_po_aksesoris",$t->id_po_aksesoris);											
				$this->m_admin->update_part($id_part,$terima,"+");
			}
		}
		if($cek == 0){
			$this->m_admin->update("tr_po_aksesoris",$da,"no_po_aksesoris",$id);										
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_aksesoris'>";
		}else{
			$_SESSION['pesan'] 	= "Qty Pemenuhan tidak boleh melebihi Qty PO";
			$_SESSION['tipe'] 	= "danger";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/po_aksesoris'>";
		}						
		
	}


}