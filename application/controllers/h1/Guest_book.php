<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_book extends CI_Controller {

    var $tables =   "tr_guest_book";	
		var $folder =   "dealer";
		var $page	=	"guest_book";
    var $pk     =   "id_guest_book";
    var $title  =   "Guest Book";

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
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_guest_book'] = $this->db->query("SELECT * FROM tr_guest_book 						
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_dealer = '$id_dealer'
						ORDER BY tr_guest_book.id_list_appointment ASC");	
		
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";			
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer","jenis_customer","ASC");										
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['dt_status'] = $this->m_admin->getSortCond("ms_status","status","ASC");								
		$data['dt_permohonan'] = $this->m_admin->getSortCond("tr_prospek","id_list_appointment","ASC");								
		$this->template($data);										
	}	
	public function take_idlist()
	{		
		$id	= $this->input->post('id_list_appointment');	
			$id_dealer = $this->m_admin->cari_dealer();

	//	$dt_list = $this->m_admin->getByID("tr_prospek","id_list_appointment",$id)->row();											
		$dt_list = $this->db->query("SELECT * FROM tr_prospek WHERE id_list_appointment = '$id' AND id_dealer='$id_dealer' ");									
		echo $dt_list->no_hp."|".$dt_list->nama_konsumen."|".$dt_list->alamat;
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
			$id 			= $this->input->post('id_list_appointment');						
			$data['id_list_appointment'] 			= $this->input->post('id_list_appointment');						
			$data['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');	
			$data['id_warna'] 								= $this->input->post('id_warna');	
			$data['alamat2'] 									= $this->input->post('alamat');	
			$data['deskripsi_warna'] 					= $this->input->post('deskripsi_warna');	
			$data['deskripsi_mkt'] 						= $this->input->post('deskripsi_mkt');	
			$data['rencana_bayar'] 						= $this->input->post('rencana_bayar');	
			$data['id_jenis_customer'] 				= $this->input->post('id_jenis_customer');	
			$data['id_status'] 								= $this->input->post('id_status');	
			$data['tgl_fu_1'] 								= $this->input->post('tgl_fu_1');	
			$data['hasil_fu_1'] 							= $this->input->post('hasil_fu_1');	
			$data['tgl_fu_2'] 								= $this->input->post('tgl_fu_2');	
			$data['hasil_fu_2'] 							= $this->input->post('hasil_fu_2');
			$data['tgl_fu_3'] 								= $this->input->post('tgl_fu_3');	
			$data['hasil_fu_3'] 							= $this->input->post('hasil_fu_3');
			
			$id_dealer = $this->m_admin->cari_dealer();
			$data['id_dealer']				 				= $id_dealer;
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;	
			$this->m_admin->insert($tabel,$data);

			$da['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');	
			$da['id_warna'] 								= $this->input->post('id_warna');	
			$da['alamat'] 									= $this->input->post('alamat');	
			$this->m_admin->update("tr_prospek",$da,"id_list_appointment",$id);


			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/guest_book/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function detail()
	{				
		$id 			= $this->input->get("id");
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer","jenis_customer","ASC");										
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['dt_status'] = $this->m_admin->getSortCond("ms_status","status","ASC");								
		$data['dt_permohonan'] = $this->m_admin->getSortCond("tr_prospek","id_list_appointment","ASC");								
		$data['dt_guest_book'] = $this->db->query("SELECT * FROM tr_guest_book 
						INNER JOIN tr_prospek ON tr_guest_book.id_list_appointment=tr_prospek.id_list_appointment
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_guest_book = '$id'
						ORDER BY tr_prospek.id_list_appointment ASC");	
		$this->template($data);	
	}		
	public function edit()
	{		
		$id 			= $this->input->get("id");
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;															
		$data['set']		= "edit";
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer","jenis_customer","ASC");										
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$data['dt_status'] = $this->m_admin->getSortCond("ms_status","status","ASC");								
		$data['dt_permohonan'] = $this->m_admin->getSortCond("tr_prospek","id_list_appointment","ASC");								
		$data['dt_guest_book'] = $this->db->query("SELECT * FROM tr_guest_book 
						INNER JOIN tr_prospek ON tr_guest_book.id_list_appointment=tr_prospek.id_list_appointment
						INNER JOIN ms_tipe_kendaraan ON tr_guest_book.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_guest_book.id_warna = ms_warna.id_warna
						WHERE tr_guest_book.id_guest_book = '$id'
						ORDER BY tr_prospek.id_list_appointment ASC");	
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;		
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){		
			$id 															= $this->input->post('id_list_appointment');						
			$data['id_list_appointment'] 			= $this->input->post('id_list_appointment');						
			$data['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');	
			$data['id_warna'] 								= $this->input->post('id_warna');	
			$data['alamat2'] 									= $this->input->post('alamat');	
			$data['deskripsi_warna'] 					= $this->input->post('deskripsi_warna');	
			$data['deskripsi_mkt'] 						= $this->input->post('deskripsi_mkt');	
			$data['rencana_bayar'] 						= $this->input->post('rencana_bayar');	
			$data['id_jenis_customer'] 				= $this->input->post('id_jenis_customer');	
			$data['id_status'] 								= $this->input->post('id_status');	
			$data['tgl_fu_1'] 								= $this->input->post('tgl_fu_1');	
			$data['hasil_fu_1'] 							= $this->input->post('hasil_fu_1');	
			$data['tgl_fu_2'] 								= $this->input->post('tgl_fu_2');	
			$data['hasil_fu_2'] 							= $this->input->post('hasil_fu_2');
			$data['tgl_fu_3'] 								= $this->input->post('tgl_fu_3');	
			$data['hasil_fu_3'] 							= $this->input->post('hasil_fu_3');
					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;	
			$this->m_admin->update($tabel,$data,$pk,$id);

			
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/guest_book'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}		
}