<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rubbing extends CI_Controller {

    var $tables =   "tr_rubbing";	
		var $folder =   "h1";
		var $page		=		"rubbing";
    var $pk     =   "no_rubbing";
    var $title  =   "Rubbing";

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
		$data['dt_rubbing'] = $this->m_admin->getAll("tr_rubbing");																						
		$data['set']		= "view";				
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
		$id    = $this->input->get('id');		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['dt_rubbing'] = $this->db->query("SELECT * FROM tr_rubbing LEFT JOIN tr_scan_barcode ON tr_rubbing.no_mesin_rusak=tr_scan_barcode.no_mesin 
				LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
				LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
				WHERE no_rubbing = '$id'");
		$data['set']		= "detail";				
		$this->template($data);			
	}
	public function approval()
	{				
		$id    = $this->input->get('id');		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['dt_lokasi'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.status_unit = 'RFS' AND ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");																					
		$data['dt_lokasi2'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang 
							WHERE ms_lokasi_unit.status_unit = 'NRFS' AND ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");																					
		$data['dt_rubbing'] = $this->db->query("SELECT * FROM tr_rubbing INNER JOIN tr_scan_barcode ON tr_rubbing.no_mesin_rusak=tr_scan_barcode.no_mesin 
				INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
				INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
				WHERE no_rubbing = '$id'");
		$data['set']		= "approval";				
		$this->template($data);			
	}
	public function cari_nosin()
	{		
		$no_mesin = $this->input->post('no_mesin');
		$sql = $this->db->query("SELECT * FROM tr_scan_barcode 
				INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
				INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();			
			echo "ok"."|".$dt_ve->id_item."|".$dt_ve->tipe_ahm."|".$dt_ve->warna;
		}else{
			echo "There is no data found!";
		}
	}
	public function t_data(){
		$no_mesin = $this->input->post('no_mesin');		
		$data['dt_nosin'] = $this->db->query("SELECT * FROM tr_checker_detail INNER JOIN tr_checker ON tr_checker_detail.id_checker = tr_checker.id_checker 
				INNER JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part 
				WHERE tr_checker.no_mesin = '$no_mesin'");				
		$data['no_mesin'] 			= $no_mesin;
		$this->load->view('h1/t_rubbing',$data);
	}
	public function cari_id(){		
		$tgl						= date("d");
		$bln 						= date("m");		
		$th 						= date("Y");
				
		$pr_num = $this->db->query("SELECT * FROM tr_rubbing ORDER BY no_rubbing DESC LIMIT 0,1");							
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->no_rubbing)-8;
			$id 	= substr($row->no_rubbing,$pan,5)+1;	
			if($id < 10){
					$kode1 = $th."/"."0000".$id."/RUB";          					
      }elseif($id>9 && $id<=99){
					$kode1 = $th."/"."000".$id."/RUB";          					
      }elseif($id>99 && $id<=999){
					$kode1 = $th."/"."00".$id."/RUB";          					
      }elseif($id>999){
					$kode1 = $th."/"."0".$id."/RUB";          					
      }
			$kode = $kode1;
		}else{
			$kode = $th."/00001/RUB";
		}						
		return $kode;
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');						
		$no_rubbing 					= $this->cari_id();
		$da['no_rubbing'] 		= $no_rubbing;
		$da['tgl_rubbing'] 		= $tgl;				
		$da['no_mesin_rusak'] = $this->input->post("no_mesin");
		$da['sumber_rubbing'] = $this->input->post("sumber_rubbing");
		$da['status_rubbing'] = "input";		
		$da['created_at'] 		= $waktu;		
		$da['created_by'] 		= $login_id;		
		
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			
				$nosin 								= $_POST["no_mesin_".$i];			
				$id_part 							= $_POST["id_part_".$i];			
				$data['no_mesin'] 		= $nosin;
				$data['id_part'] 			= $_POST["id_part_".$i];			
				$data['no_rubbing'] 	= $no_rubbing;			
				$data["cek"] 					= "ya";
				$this->db->query("UPDATE tr_checker_detail SET rubbing = 'ya' WHERE no_mesin = '$nosin' AND id_part = '$id_part'");										

				$cek = $this->db->query("SELECT * FROM tr_rubbing_detail WHERE no_mesin = '$nosin' AND id_part = '$id_part'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_rubbing_detail",$data,"no_rubbing",$no_rubbing);								
				}else{
					$this->m_admin->insert("tr_rubbing_detail",$data);								
				}
			
		}
			
		$ce = $this->db->query("SELECT * FROM tr_rubbing WHERE no_rubbing = '$no_rubbing'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_rubbing",$da,"no_rubbing",$no_rubbing);								
		}else{
			$this->m_admin->insert("tr_rubbing",$da);								
		}
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rubbing'>";
	}
	public function cek_nosin(){
		$no_mesin		= $this->input->post('no_mesin');							
		$tipe				= $this->input->post('tipe');							
		$c = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'")->row();				

		$cek 	= $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'");

		$row = $cek->row();
		$cek_gudang = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail INNER JOIN tr_penerimaan_unit 
						ON tr_penerimaan_unit.id_penerimaan_unit=tr_penerimaan_unit_detail.id_penerimaan_unit 
						WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list'")->row();

		//cek status, gudang dan tipe dedicated
		$cek1 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.tipe_dedicated = '$row->tipe_motor' AND ms_lokasi_unit.status_unit = '$tipe' 
						AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang' 
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");	

		
		//cek gudang, tipe kendaraan dan warna SAMA
		$cek2 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
						INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
						INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.status_unit = '$tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty 
						AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' AND tr_scan_barcode.warna = '$row->warna'
						AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' 
						ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");			
				
		//cek status, gudang dan tanpa dedicated
		$cek3 = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
					  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = '$tipe' AND ms_lokasi_unit.isi = 0
					  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						AND ms_lokasi_unit.isi = ''
					  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

		//cek status, gudang dan tanpa dedicated
		$cek3_a = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang
						  WHERE ms_lokasi_unit.tipe_dedicated = '' AND ms_lokasi_unit.status_unit = '$tipe' AND ms_lokasi_unit.isi = 0
						  AND ms_gudang.gudang = '$cek_gudang->gudang' AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1'  
						  ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");

	
		//cek gudang, tipe kendaraan SAMA dan warna BEDA
		$cek4 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode 
						INNER JOIN ms_lokasi_unit ON tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit 
						INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang 
						WHERE ms_lokasi_unit.status_unit = '$tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' AND tr_scan_barcode.tipe_motor = '$row->tipe_motor' ORDER BY ms_lokasi_unit.id_lokasi_unit ASC LIMIT 0,1");								

		//cek gudang, tipe kendaraan BEDA dan warna BEDA
		$cek5 = $this->db->query("SELECT DISTINCT(tr_scan_barcode.lokasi),ms_lokasi_unit.id_lokasi_unit,ms_lokasi_unit.isi FROM tr_scan_barcode INNER JOIN ms_lokasi_unit ON
						tr_scan_barcode.lokasi = ms_lokasi_unit.id_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang   
						WHERE ms_lokasi_unit.status_unit = '$tipe' AND ms_lokasi_unit.isi < ms_lokasi_unit.qty AND ms_gudang.gudang = '$cek_gudang->gudang'
						AND ms_gudang.active = '1' AND ms_lokasi_unit.active = '1' and ms_lokasi_unit.tipe_dedicated = ''
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
		}elseif($cek3_a->num_rows() > 0){
			$amb = $cek3_a->row();				
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
		}
		else{
			$isi_lokasi = "";
		}			

		//cek slot

		if($isi_lokasi != ""){
			$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$isi_lokasi'")->row();				
			if($cek_maks->isi < $cek_maks->qty){					
					for($i=1; $i <= $cek_maks->qty; $i++) { 
						if($i < 10){
							$sl = "0".$i;
						}else{
							$sl = $i;
						}
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

		$lokasi_baru = $isi_lokasi."-".$slot_baru;


		echo $c->no_mesin."|".$c->id_item."|".$c->tipe_motor."|".$c->warna."|".$c->lokasi."-".$c->slot."|".$lokasi_baru;								
	}

	public function get_slot(){
		$id_lokasi	= $this->input->post('lokasi_baru');

		$cek_maks = $this->db->query("SELECT * FROM ms_lokasi_unit WHERE id_lokasi_unit = '$id_lokasi'")->row();		
		$data .= "<option value=''>- choose -</option>";
		for ($i=1; $i <= $cek_maks->qty; $i++) { 
			if($i < 10){
				$isi = "0".$i;				
			}else{
				$isi= $i;
			}
			$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$id_lokasi' AND slot = '$isi' AND status = '1'");
			if($cek_slot->num_rows() == 0){
				$data .= "<option value='$isi'>$isi</option>\n";	
			}			
		}			
		echo $data;
	}
	public function save_approval()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		
		$data['no_mesin_rusak'] 	= $this->input->post('no_mesin');
		$data['sumber_rubbing'] 	= $this->input->post('no_mesin2');
		$no_mesin 								= $this->input->post('no_mesin');			
		$no_mesin2 								= $this->input->post('no_mesin2');			
		$data['lokasi_baru1'] 		= $this->input->post('lokasi_baru')."-".$this->input->post('slot');				
		$data['lokasi_baru2'] 		= $this->input->post('lokasi_baru2')."-".$this->input->post('slot2');				
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;	
		$data['status_rubbing']		= "closed";	


		$t = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin)->row();			
		$s = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$no_mesin2)->row();			
		$this->db->query("UPDATE ms_lokasi_unit SET isi = isi-1 WHERE id_lokasi_unit = '$t->lokasi'");				
		$this->db->query("UPDATE ms_lokasi_unit SET isi = isi+1 WHERE id_lokasi_unit = '$t->lokasi'");				

		$da['lokasi']							= $this->input->post('lokasi_baru');	
		$da['slot']								= $this->input->post('slot');	
		$da['tipe']								= "RFS";		
		$this->m_admin->update("tr_scan_barcode",$da,"no_mesin",$no_mesin);

		$da2['lokasi']							= $this->input->post('lokasi_baru2');	
		$da2['slot']								= $this->input->post('slot2');	
		$da2['tipe']								= "NRFS";		
		$this->m_admin->update("tr_scan_barcode",$da2,"no_mesin",$no_mesin2);
		
		$this->m_admin->update("tr_rubbing",$data,"no_mesin_rusak",$no_mesin);
		$a = $this->m_admin->getByID("tr_checker","no_mesin",$no_mesin)->row();			
		$this->db->query("UPDATE tr_wo SET status_wo = 'closed' WHERE id_checker = '$a->id_checker'");				
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/rubbing'>";		
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