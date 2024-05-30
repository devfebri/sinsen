<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wo_bundling extends CI_Controller {

    var $tables =   "tr_wo_bundling";	
		var $folder =   "h1";
		var $page		=		"wo_bundling";
    var $pk     =   "no_wo_bundling";
    var $title  =   "Work Order (WO) Bundling";

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
		$data['dt_wo']	= $this->db->query("SELECT * FROM tr_wo_bundling ORDER BY no_wo_bundling DESC");
		$this->template($data);			
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$id = $this->input->get('id');
		$data['dt_wo']	= $this->db->query("SELECT * FROM tr_wo_bundling WHERE no_wo_bundling = '$id'");
		$this->template($data);			
	}
	public function cek_paket(){
		$id_paket_bundling = $this->input->post('id_paket_bundling');
		$dq = $this->db->query("SELECT * FROM ms_paket_bundling INNER JOIN ms_item ON ms_paket_bundling.id_item=ms_item.id_item 
						INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan						
						WHERE ms_paket_bundling.id_paket_bundling = '$id_paket_bundling'");
		if($dq->num_rows() > 0){
			$r = $dq->row();
			echo "nihil|".$r->id_item."|".$r->tipe_ahm."|".$r->warna;
		}else{
			echo "There is no data found";
		}		
	}
	public function t_part(){
		$id = $this->input->post('id_paket_bundling');
		$mode = $this->input->post('mode');
		$data['mode'] = $this->input->post('mode');
		$data['qty_paket'] = $this->input->post('qty_paket');
		if($mode == 'insert'){
			$dq = "SELECT ms_paket_bundling_detail.*,ms_part.nama_part FROM ms_paket_bundling_detail INNER JOIN ms_part						
						ON ms_paket_bundling_detail.id_part=ms_part.id_part
						WHERE ms_paket_bundling_detail.id_paket_bundling = '$id'";
		}else{
			$no_wo_bundling = $this->input->post('no_wo_bundling');
			$dq = "SELECT * FROM tr_wo_bundling_part INNER JOIN ms_part 
						ON tr_wo_bundling_part.id_part = ms_part.id_part
						WHERE tr_wo_bundling_part.no_wo_bundling = '$no_wo_bundling'";
		}
		$data['dt_data'] = $this->db->query($dq);		
		$this->load->view('h1/t_wo_part',$data);
	}

	public function t_apparel(){
		$id = $this->input->post('id_paket_bundling');
		$mode = $this->input->post('mode');
		$data['mode'] = $this->input->post('mode');
		$data['qty_paket'] = $this->input->post('qty_paket');
		if($mode == 'insert'){
			$dq = "SELECT ms_paket_bundling_app.*,ms_apparel.apparel FROM ms_paket_bundling_app INNER JOIN ms_apparel						
						ON ms_paket_bundling_app.id_apparel=ms_apparel.id_apparel
						WHERE ms_paket_bundling_app.id_paket_bundling = '$id'";
		}else{
			$no_wo_bundling = $this->input->post('no_wo_bundling');
			$dq = "SELECT * FROM tr_wo_bundling_apparel INNER JOIN ms_apparel 
						ON tr_wo_bundling_apparel.id_apparel = ms_apparel.id_apparel
						WHERE tr_wo_bundling_apparel.no_wo_bundling = '$no_wo_bundling'";
		}
		$data['dt_data'] = $this->db->query($dq);		
		$this->load->view('h1/t_wo_apparel',$data);
	}
	public function t_nosin(){
		$id = $this->input->post('id_paket_bundling');
		$qty = $this->input->post('qty_paket');
		$id_item = $this->input->post('kode_item');
		$mode = $this->input->post('mode');
		$data['mode'] = $this->input->post('mode');
		if($mode == 'insert'){
			$dq = "SELECT * FROM tr_scan_barcode WHERE id_item = '$id_item' AND tipe = 'RFS' AND status = '1' 
				ORDER BY fifo ASC LIMIT 0,".$qty."";
		}else{
			$no_wo_bundling = $this->input->post('no_wo_bundling');
			$dq = "SELECT * FROM tr_wo_bundling_nosin INNER JOIN tr_scan_barcode 
						ON tr_wo_bundling_nosin.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_wo_bundling_nosin.no_wo_bundling = '$no_wo_bundling'";
		}
		$data['dt_data'] = $this->db->query($dq);		
		$this->load->view('h1/t_wo_nosin',$data);
	}
	public function cari_id(){		
		$tgl 						= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_wo_bundling WHERE LEFT(created_at,7) = '$tgl' ORDER BY no_wo_bundling DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_wo_bundling)-5;
			$id 	= substr($row->no_wo_bundling,$pan,5)+1;					
			$isi 	= sprintf("%'.05d",$id);		
			$kode = $th."-".$bln."/WOB/".$isi;
		}else{
		 	$kode = $th."-".$bln."/WOB/00001";
		} 			
		return $kode;
	}
	public function save()
	{				
		// echo 'Maintenance';die;		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_wo_bundling 				= $this->cari_id();
		$da['no_wo_bundling'] 	= strip_tags($no_wo_bundling);
		$da['tgl_paket'] 				= $tgl;				
		$da['id_paket_bundling']= $this->input->post("id_paket_bundling");
		$da['qty_paket']				= $this->input->post("qty_paket");	
		$da['status_paket'] 		= "input";		
		$da['created_at'] 			= $waktu;		
		$da['created_by'] 			= $login_id;		
		
		$serentak_input_picking_list = 0;

		$jum_nosin 					= $this->input->post("jum_nosin");		
		for ($i=1; $i <= $jum_nosin; $i++) { 			
			$no_mesin 				= $_POST["no_mesin_".$i];			
			
			$t = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();	

			if($t->status!=1){
				$serentak_input_picking_list =1;

				$_SESSION['pesan'] 	= "Gagal! Status unit sudah berbeda. Silahkan periksa kembali.";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo_bundling'>";	
			}
		}
	
		for ($i=1; $i <= $jum_nosin; $i++) { 	
			$no_mesin 				= $_POST["no_mesin_".$i];			
			$ds['no_wo_bundling'] = $no_wo_bundling;
			$ds['no_mesin'] = $no_mesin;	

			if($serentak_input_picking_list ==0){
				$cek = $this->db->query("SELECT * FROM tr_wo_bundling_nosin WHERE no_mesin = '$no_mesin' AND no_wo_bundling = '$no_wo_bundling'");
				if($cek->num_rows() > 0){		
					$t = $cek->row();
					$this->m_admin->update("tr_wo_bundling_nosin",$ds,"id_wo_bundling_nosin",$t->id_wo_bundling_nosin);								
				}else{
					$this->m_admin->insert("tr_wo_bundling_nosin",$ds);								
				}					
				$this->db->query("UPDATE tr_scan_barcode SET tipe = 'BOOKING' WHERE no_mesin = '$no_mesin'");		
				$this->m_admin->set_log($no_mesin,"BOOKING",$t->lokasi."-".$t->slot);
			}
		}

		if($serentak_input_picking_list ==0){
			$jum_part 							= $this->input->post("jum_part");		
			for ($i=1; $i <= $jum_part; $i++) { 			
				$id_part 						= $_POST["id_part_".$i];			
				$data['no_wo_bundling'] 		= $no_wo_bundling;
				$data['id_part'] 		= $id_part;				
				$data['qty_part'] 	= $_POST["qty_part_".$i];								

				$cek = $this->db->query("SELECT * FROM tr_wo_bundling_part WHERE id_part = '$id_part' AND no_wo_bundling = '$no_wo_bundling'");
				if($cek->num_rows() > 0){				
					$t = $cek->row();
					$this->m_admin->update("tr_wo_bundling_part",$data,"id_wo_bundling_part",$t->id_wo_bundling_part);								
				}else{
					$this->m_admin->insert("tr_wo_bundling_part",$data);								
				}					
			}

			$jum_apparel 					= $this->input->post("jum_apparel");		
			for ($i=1; $i <= $jum_apparel; $i++) { 			
				$id_apparel 				= $_POST["id_apparel_".$i];			
				$dat['no_wo_bundling'] 		= $no_wo_bundling;
				$dat['id_apparel'] = $id_apparel;				
				$dat['qty_apparel']= $_POST["qty_apparel_".$i];								

				$cek = $this->db->query("SELECT * FROM tr_wo_bundling_apparel WHERE id_apparel = '$id_apparel' AND no_wo_bundling = '$no_wo_bundling'");
				if($cek->num_rows() > 0){				
					$t = $cek->row();
					$this->m_admin->update("tr_wo_bundling_apparel",$dat,"id_wo_bundling_apparel",$t->id_wo_bundling_apparel);								
				}else{
					$this->m_admin->insert("tr_wo_bundling_apparel",$dat);								
				}					
			}

			$ce = $this->db->query("SELECT * FROM tr_wo_bundling WHERE no_wo_bundling = '$no_wo_bundling'");
			if($ce->num_rows() > 0){						
				$this->m_admin->update("tr_wo_bundling",$da,"no_wo_bundling",$no_wo_bundling);								
			}else{
				$this->m_admin->insert("tr_wo_bundling",$da);								
			}
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";		
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo_bundling'>";
		}else{
			$_SESSION['pesan'] 	= "Status unit sudah berbeda. Silahkan periksa kembali.";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo_bundling'>";	
		}
	}
	public function close_wo()
	{		
		
 		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");
		//apparel
		$cek_stok_apparel=0;
		$dq = $this->db->query("SELECT * FROM tr_wo_bundling_apparel INNER JOIN ms_apparel 
						ON tr_wo_bundling_apparel.id_apparel = ms_apparel.id_apparel
						WHERE tr_wo_bundling_apparel.no_wo_bundling = '$id'");
		foreach ($dq->result() as $isi) {
			$stok_apparel = $this->db->query("SELECT * FROM tr_stok_apparel WHERE id_apparel = '$isi->id_apparel'");
			if ($stok_apparel->num_rows() > 0) {
				if ($isi->qty_apparel > $stok_apparel->row()->qty) {
				$cek_stok_apparel++;
			}
			}
		}

		//part
		$cek_stok_part=0;
		$dq = $this->db->query("SELECT * FROM tr_wo_bundling_part WHERE no_wo_bundling = '$id'");
		foreach ($dq->result() as $isi) {
			$stok_part = $this->db->query("SELECT * FROM tr_stok_part_h1 WHERE id_part = '$isi->id_part'");
			if ($stok_part->num_rows() > 0) {
				if ($isi->qty_part > $stok_part->row()->qty_h1) {
					$cek_stok_part++;
				}
			}
		}
		$cek_stok_all = $cek_stok_apparel+$cek_stok_part;
		if ($cek_stok_all==0) {
			
			$data['status_paket'] 		= "closed";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$cek2 = $this->db->query("SELECT * FROM tr_wo_bundling_part WHERE no_wo_bundling = '$id'");
			foreach ($cek2->result() as $isi) {
				$id_part 	= $isi->id_part;				
				$qty 			= $isi->qty_part;				
				$this->m_admin->update_part($id_part,$qty,"-");
			}

			$cek = $this->db->query("SELECT * FROM tr_wo_bundling_nosin WHERE no_wo_bundling = '$id'");
			foreach ($cek->result() as $isi) {			
				$t = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin)->row();
				
				$this->m_admin->set_log($isi->no_mesin,"RFS",$t->lokasi."-".$t->slot);
				$a = $this->db->query("SELECT * FROM tr_wo_bundling INNER JOIN ms_paket_bundling ON tr_wo_bundling.id_paket_bundling = ms_paket_bundling.id_paket_bundling
				 WHERE tr_wo_bundling.no_wo_bundling = '$id'")->row();
				$item = $a->id_item_baru;			
				
				$b = $this->m_admin->getByID("ms_item","id_item",$item)->row();			
				$this->db->query("UPDATE tr_scan_barcode SET tipe = 'RFS',id_item = '$item',warna='$b->id_warna' WHERE no_mesin = '$isi->no_mesin'");
				$this->m_admin->update_stock($item,"RFS",'+','1');
			}
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo_bundling'>";	
		}
		else
		{
			$_SESSION['pesan'] 	= "Stok Berbeda";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo_bundling'>";		
		
		}
	}
	public function batal()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;		
		$pk 				= $this->pk;
		$id					= $this->input->get("id");
		$id_				= $this->input->get($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){				

			$data['status_paket'] 		= "canceled";			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);

			$cek = $this->db->query("SELECT * FROM tr_wo_bundling_nosin WHERE no_wo_bundling = '$id'");
			foreach ($cek->result() as $isi) {			
				$t = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin)->row();			
				$this->m_admin->set_log($isi->no_mesin,"RFS",$t->lokasi."-".$t->slot);
				$a = $this->db->query("SELECT * FROM tr_wo_bundling INNER JOIN ms_paket_bundling ON tr_wo_bundling.id_paket_bundling = ms_paket_bundling.id_paket_bundling
				 WHERE tr_wo_bundling.no_wo_bundling = '$id'")->row();
				$item = $a->id_item;			
				$b = $this->m_admin->getByID("ms_item","id_item",$item)->row();			

				$this->db->query("UPDATE tr_scan_barcode SET tipe = 'RFS',id_item = '$item',warna='$b->id_warna' WHERE no_mesin = '$isi->no_mesin'");
			}
			
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/wo_bundling'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}		
}



/// 2018-08/WOB/00001 per bulan