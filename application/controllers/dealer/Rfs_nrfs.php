<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfs_nrfs extends CI_Controller {

    var $tables =   "tr_scan_ubah";	
		var $folder =   "h1";
		var $page		=		"scan_ubah";
    var $pk     =   "id_scan_ubah";
    var $title  =   "Ubah Status RFS ke NRFS";

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
		$auth = $this->m_admin->user_auth('rfs_nrfs',"select");		
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
			$data['id_menu'] = $this->m_admin->getMenu('rfs_nrfs');
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = "rfs_nrfs";		
		$data['title']	= $this->title;															
		$data['set']		= "view";		
		$data['dt_scan_ubah'] = $this->db->query("SELECT DISTINCT(id_scan_ubah) FROM tr_scan_ubah WHERE jenis_ubah = 'RFS-NRFS' ORDER BY id_scan_ubah DESC");						
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = "rfs_nrfs";		
		$data['title']	= $this->title;															
		$data['set']		= "add_rfs_nrfs";		
		$this->template($data);	
	}
	
	public function cari_id(){
		$id = $this->m_admin->cari_kode("tr_scan_ubah","id_scan_ubah");
		echo $id;
	}
	public function t_rfs(){
		$id = $this->input->post('id_ubah');		
		$data['dt_rfs'] = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id'");
		$data['jenis']  = 'NRFS';		
		$this->load->view('h1/t_rfs_ubah',$data);
	}
	public function save_rfs(){
		$rfs_text		= $this->input->post('rfs_text');
		$id_ubah		= $this->input->post('id_ubah');
		$waktu 			= date("y-m-d");
		$cek 				= $this->db->query("SELECT * FROM tr_scan_barcode WHERE tr_scan_barcode.no_mesin = '$rfs_text'")->row();		
		if($cek->tipe == 'RFS'){
			$row 				= $this->db->query("SELECT * FROM tr_scan_barcode WHERE tr_scan_barcode.no_mesin = '$rfs_text'")->row();		
			$cek_gudang_dl = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_penerimaan_unit 
						ON tr_penerimaan_unit.id_penerimaan_unit=tr_penerimaan_unit_detail.id_penerimaan_unit 
						WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list'");
			if($cek_gudang_dl->num_rows() > 0){
				$cek_gudang = $cek_gudang_dl->row();				
				$gudang = $cek_gudang->gudang;
				$isi_gudang = 'ada';
			}else{
				$gudang = "";
				$isi_gudang = "";				
			}
			//cek status, gudang dan tipe dedicated
			$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.tipe_dedicated = '$row->tipe_motor' AND ms_lokasi_unit.status_unit = 'NRFS' 
							AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.id_gudang = '$gudang' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");
			
			//cek gudang, tipe kendaraan dan warna SAMA
			$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
							tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
							AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND tr_scan_barcode.warna = '$row->warna' AND ms_gudang.id_gudang = '$gudang' 
							ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
		
			//cek status, gudang dan tanpa dedicated
			$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						  AND ms_gudang.id_gudang = '$gudang' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");


			//cek gudang, tipe kendaraan SAMA dan warna BEDA
			$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
							tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang  
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty  AND ms_gudang.id_gudang = '$gudang' 
							AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			

			//cek gudang, tipe kendaraan BEDA dan warna BEDA
			$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
							tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang  
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.id_gudang = '$gudang' 
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

			if($isi_lokasi != ""){
				$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
				$cek_isi = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND (status = 1 OR status = 2)");				
				if($cek_isi->num_rows() > 0){
					$t = $cek_isi->row();
					$isi_scan = $t->jum;
				}else{
					$isi_scan = 0;
				}
				if($isi_scan < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 							
						$sl = $i;
						$cek_slot2 = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$isi_lokasi' AND slot = '$sl' AND (status = 1 OR status = 2) ORDER BY slot ASC");
						if($cek_slot2->num_rows() == 0){
							$isi_slot2 = $cek_slot2->row();
							$slot_baru = $sl;
							break;								
						}					

							
					}
					
				}else{
					$slot_baru = "";
				}			
			}else{
				$slot_baru = "";
			}
			
			
			$data['no_mesin']							= $rfs_text;
			$data['id_scan_ubah']					= $id_ubah;		
			$data['lokasi_tujuan']				= $isi_lokasi;			
			$data['slot_tujuan']					= $slot_baru;			
			$data['lokasi_awal']					= $row->lokasi;
			$data['slot_awal']						= $row->slot;

			$da['lokasi']					= $isi_lokasi;
			$da['slot']						= $slot_baru;
				
			
			$c = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id_ubah' AND no_mesin = '$rfs_text'");
			if($c->num_rows() > 0){			
				echo "no";
			}elseif($c->num_rows() == 0){
				if($cek2->num_rows() > 0 OR $cek3->num_rows() > 0 OR $cek1->num_rows() > 0 OR $cek4->num_rows() > 0 OR $cek5->num_rows() > 0){
					$cek2 = $this->m_admin->insert("tr_scan_ubah_detail",$data);						
					$this->m_admin->update_stock($row->tipe_motor,$row->warna,"NRFS",'+','1');
					$this->m_admin->update_stock($row->tipe_motor,$row->warna,"RFS",'-','1');
					$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$rfs_text);
					$this->m_admin->set_log($rfs_text,"NRFS",$isi_lokasi."-".$slot_baru);

					$this->db->query("UPDATE ms_lokasi_unit SET isi = '$jum' WHERE id_lokasi_unit = '$isi_lokasi'");				
					$this->db->query("UPDATE tr_scan_barcode SET tipe = 'NRFS' WHERE no_mesin = '$rfs_text'");				
					echo "ok";
				}else{
					echo "lokasi";
				}
			}									
		}else{
			echo "sudah";
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
		$da['tipe']						= "RFS";
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
			$data['jenis_ubah'] 			= "RFS-NRFS";			
			$data['status_ubah'] 					= "input";			
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	

			$this->m_admin->insert($tabel,$data);						

			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_nrfs'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function detail()
	{				
		$data['isi']    = "rfs_nrfs";		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$data['jenis']	= "RFS";		
		$id 						= $this->input->get('id');					
		$data['dt_scan_ubah'] = $this->db->query("SELECT * FROM tr_scan_ubah_detail WHERE id_scan_ubah = '$id' ORDER BY id_scan_ubah DESC");	
		//$data['dt_rfs'] = $this->db->query("SELECT * FROM tr_scan_ubah WHERE id_scan_ubah = '$id'");							
		$this->template($data);	
	}
	public function edit()
	{				
		$data['isi']    = "rfs_nrfs";		
		$data['title']	= $this->title;															
		$data['set']		= "edit";		
		$data['jenis']	= "RFS";		
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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rfs_nrfs'>";
		
	}
	public function cetak_stiker()
	{				
		$data['isi']    = "rfs_nrfs";		
		$data['title']	= "Cetak Stiker";	
		$id 						= $this->input->get("id");	
		$data['set']		= "cetak";
		$data['jenis']	= "RFS";				
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