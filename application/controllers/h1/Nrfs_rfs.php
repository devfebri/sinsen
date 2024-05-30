<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nrfs_rfs extends CI_Controller {

    var $tables =   "tr_scan_ubah";	
		var $folder =   "h1";
		var $page		=		"scan_ubah_n";
    var $pk     =   "id_scan_ubah";
    var $title  =   "Ubah Status NRFS ke RFS";

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
		$data['isi']    = "nrfs_rfs";		
		$data['title']	= $this->title;															
		$data['set']		= "view";		
		$data['dt_scan_ubah'] = $this->db->query("SELECT DISTINCT(id_scan_ubah) FROM tr_scan_ubah WHERE jenis_ubah = 'NRFS-RFS' ORDER BY id_scan_ubah DESC");						
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = "nrfs_rfs";		
		$data['title']	= $this->title;															
		$data['set']		= "add_nrfs_rfs";		
		$this->template($data);	
	}
	
	public function cari_id(){
		$id = $this->m_admin->cari_kode("tr_scan_ubah","id_scan_ubah");
		echo $id;
	}
	public function t_rfs(){
		$id = $this->input->post('id_ubah');		
		$data['dt_rfs'] = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id'");
		$data['jenis']  = 'RFS';		
		$this->load->view('h1/t_rfs_ubah',$data);
	}
	public function save_rfs(){
		$rfs_text		= $this->input->post('rfs_text');
		$id_ubah		= $this->input->post('id_ubah');
		$waktu 			= date("y-m-d");
		$row 				= $this->db->query("SELECT * FROM tr_scan_barcode WHERE tr_scan_barcode.no_mesin = '$rfs_text'")->row();		
		$cek_gudang = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_penerimaan_unit 
					ON tr_penerimaan_unit.id_penerimaan_unit=tr_penerimaan_unit_detail.id_penerimaan_unit 
					WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list'")->row();
		//cek status, gudang dan tipe dedicated
		$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.tipe_dedicated = '$row->tipe_motor' AND ms_lokasi_unit.status_unit = 'RFS' 
						AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");
		
		//cek gudang, tipe kendaraan dan warna SAMA
		$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
						tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND tr_scan_barcode.warna = '$row->warna'
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
	
		//cek status, gudang dan tanpa dedicated
		$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
					  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
					  AND ms_gudang.gudang = '$cek_gudang->gudang' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");


		//cek gudang, tipe kendaraan SAMA dan warna BEDA
		$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
						tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

		//cek gudang, tipe kendaraan BEDA dan warna BEDA
		$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
						tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

		if($cek1->num_rows() > 0){
			$amb = $cek1->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;				
		}elseif($cek2->num_rows() > 0){
			$amb = $cek2->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}elseif($cek3->num_rows() > 0){
			$amb = $cek3->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}elseif($cek4->num_rows() > 0){
			$amb = $cek4->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}elseif($cek5->num_rows() > 0){
			$amb = $cek5->row();				
			$isi_lokasi = $amb->id_lokasi_unit;
			$jum = $amb->isi + 1;			
		}else{
			$isi_lokasi = "";
		}			

		//cek slot
		$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();
		$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND status = 1 ORDER BY slot DESC LIMIT 0,1");
		if($cek_slot->num_rows() > 0){
			$isi_slot = $cek_slot->row();
			if($cek_maks->qty > $isi_slot->slot){
				$slot = $isi_slot->slot + 1;
				if($slot < 10){
					$slot_baru = "0".$slot;
				}else{
					$slot_baru= $slot;
				}	
			}else{
				$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND status = 1 ORDER BY slot ASC LIMIT 0,1");
				$isi_slot2 = $cek_slot2->row();
				if($isi_slot2->slot > 1){
					$slot2 = $isi_slot2->slot - 1;
					$slot_baru = "0".$slot2;							
				}	
			}
			
		}else{
			$slot_baru = "01";
		}

		$data['no_mesin']							= $rfs_text;
		$data['id_scan_ubah']					= $id_ubah;		
		$data['lokasi_tujuan']				= $isi_lokasi;			
		$data['slot_tujuan']					= $slot_baru;			
		$data['lokasi_awal']					= $row->lokasi;
		$data['slot_awal']						= $row->slot;

		$da['lokasi']					= $isi_lokasi;
		$da['slot']						= $slot_baru;
		$da['tipe']						= "RFS";

			
		$amb = $this->m_admin->getByID("tr_shipping_list","no_mesin",$rfs_text);
		if($amb->num_rows() > 0){					
			$c = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id_ubah' AND no_mesin = '$rfs_text'");
			if($c->num_rows() > 0){			
				echo "no";
			}elseif($c->num_rows() == 0){
				if($cek2->num_rows() > 0 OR $cek3->num_rows() > 0 OR $cek1->num_rows() > 0 OR $cek4->num_rows() > 0 OR $cek5->num_rows() > 0){
					$cek2 = $this->m_admin->insert("tr_scan_ubah_detail",$data);						
					$this->m_admin->update_stock($row->tipe_motor,$row->warna,"NRFS",'+','1');
					$this->m_admin->update_stock($row->tipe_motor,$row->warna,"RFS",'-','1');
					$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$rfs_text);
					$this->m_admin->set_log($rfs_text,"RFS",$isi_lokasi."-".$slot_baru);
					$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum' WHERE id_lokasi_unit = '$isi_lokasi'");				
					echo "ok";
				}else{
					echo "lokasi";
				}
			}						
		}else{
			echo "none";
		}									
	}
	public function delete_scan(){
		$id 				= $this->input->post('id_scan_ubah');		
		$jenis 			= $this->input->post('jenis');
		if($jenis == 'NRFS'){
			$u_jenis = "RFS";
		}else{
			$u_jenis = "NRFS";
		}				
		$no_mesin 	= $this->input->post('no_mesin');		

		$cek 				= $this->db->query("SELECT * FROM tr_scan_barcode WHERE tr_scan_barcode.no_mesin = '$no_mesin'")->row();		
		$this->m_admin->update_stock($cek->tipe_motor,$cek->warna,$jenis,'-','1');
		$this->m_admin->update_stock($cek->tipe_motor,$cek->warna,$u_jenis,'+','1');

		$c = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id' AND no_mesin = '$no_mesin'")->row();		
		$lokasi_awal 		= $c->lokasi_awal;				
		$lokasi_tujuan 	= $c->lokasi_tujuan;				
		$slot 					= $c->slot_awal;				

		$amb = $this->m_admin->getByID("ms_lokasi_unit","id_lokasi_unit",$lokasi_tujuan)->row();		
		$jum = $amb->isi - 1;
		$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum' WHERE id_lokasi_unit = '$lokasi_tujuan'");			

		$amb2 = $this->m_admin->getByID("ms_lokasi_unit","id_lokasi_unit",$lokasi_awal)->row();		
		$jum2 = $amb2->isi + 1;
		$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum2' WHERE id_lokasi_unit = '$lokasi_awal'");			
		
		$this->db->query("DELETE FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id' AND no_mesin  = '$no_mesin'");			
		
		$da['lokasi']					= $lokasi_awal;
		$da['slot']						= $slot;
		$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);
		echo "nihil";
	}
	
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){			
			$data['id_scan_ubah'] 		= $this->input->post('id_scan_ubah');
			$data['tgl_ubah'] 				= $this->input->post('tgl');	
			$data['jenis_ubah'] 			= "NRFS-RFS";			
			$data['status_ubah'] 			= "input";			
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	

			$this->m_admin->insert($tabel,$data);						

			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/nrfs_rfs'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function detail()
	{				
		$data['isi']    = "nrfs_rfs";		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$data['jenis']	= "NRFS";		
		$id 						= $this->input->get('id');					
		$data['dt_scan_ubah'] = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id' ORDER BY id_scan_ubah DESC");	
		//$data['dt_rfs'] = $this->db->query("SELECT * FROM tr_scan_ubah WHERE id_scan_ubah = '$id'");							
		$this->template($data);	
	}
	public function edit()
	{				
		$data['isi']    = "nrfs_rfs";		
		$data['title']	= $this->title;															
		$data['set']		= "edit";		
		$data['jenis']	= "NRFS";		
		$id 						= $this->input->get('id');					
		$data['dt_scan_ubah'] = $this->db->query("SELECT * FROM tr_scan_ubah INNER JOIN tr_scan_ubah_detail ON 
							tr_scan_ubah.id_scan_ubah=tr_scan_ubah_detail.id_scan_ubah WHERE tr_scan_ubah.id_scan_ubah = '$id' ORDER BY tr_scan_ubah.id_scan_ubah DESC");	
		//$data['dt_rfs'] = $this->db->query("SELECT * FROM tr_scan_ubah WHERE id_scan_ubah = '$id'");							
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();

		$save 				= $this->input->post('save');
		if($save == 'update'){	
			$data['tgl_ubah'] 				= $this->input->post('tgl');	
		}elseif($save == 'approve'){
			$data['status_ubah'] = "approved";
		}elseif($save == 'reject'){
			$data['status_ubah'] = "rejected";
		}		
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	

		$this->m_admin->update($tabel,$data,$pk,$id);						

		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/nrfs_rfs'>";
		
	}
	public function cetak_stiker()
	{				
		$data['isi']    = "nrfs_rfs";		
		$data['title']	= "Cetak Stiker";	
		$id 						= $this->input->get("id");	
		$data['set']		= "cetak";
		$data['jenis']	= "NRFS";				
		$data['dt_cetak'] = $this->db->query("SELECT tr_scan_barcode.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_scan_barcode 
					INNER JOIN tr_scan_ubah_detail ON tr_scan_barcode.no_mesin = tr_scan_ubah_detail.no_mesin
					INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					WHERE tr_scan_ubah_detail.id_scan_ubah = '$id' AND tr_scan_barcode.status  = '1'");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function cetak_s(){
		$id 				= $this->input->get('id');		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;		
		$data['dt_stiker'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$id'");
		$this->load->view('h1/cetak_stiker',$data);
		
	}
			
}