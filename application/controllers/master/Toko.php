<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toko extends CI_Controller {

    var $tables =   "ms_toko";	
		var $folder =   "master";
		var $page		=		"toko";
    var $pk     =   "id_toko";
    var $title  =   "Master Toko / Customer";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_kelurahan');		
		//===== Load Library =====
		$this->load->library('upload');
		
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
		$data['dt_toko'] = $this->db->query("SELECT * FROm ms_toko LEFT JOIN ms_kelurahan
																ON ms_toko.id_kelurahan = ms_kelurahan.id_kelurahan 
																ORDER BY nama_toko ASC");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;						
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");					
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function ajax_list()
	{				
		$list = $this->m_kelurahan->get_datatables();		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan","id_kecamatan",$isi->id_kecamatan);
			if($cek->num_rows() > 0){
				$t = $cek->row();
				$kecamatan = $t->kecamatan;
			}else{
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;			
			$row[] = $isi->kelurahan;			
			$row[] = $kecamatan;			
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_kelurahan->count_all(),
						"recordsFiltered" => $this->m_kelurahan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function take_kec()
	{		
		$id_kelurahan	= $this->input->post('id_kelurahan');	
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan 		= $dt_kec->kecamatan;
		$id_kabupaten = $dt_kec->id_kabupaten;
		$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= $dt_kab->kabupaten;
		$id_provinsi  = $dt_kab->id_provinsi;
		$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  		= $dt_pro->provinsi;

		
		echo $id_kecamatan."|".$kecamatan."|".$id_kabupaten."|".$kabupaten."|".$id_provinsi."|".$provinsi."|".$kelurahan;
	}
	
	public function save()
	{		
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id					= $this->session->userdata('id_user');		

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$config['upload_path'] 			= './assets/panel/files/';
			$config['allowed_types'] 		= 'jpg|jpeg|png';
			$config['max_size']					= '500';					
			$this->upload->initialize($config);
			if(!$this->upload->do_upload('foto_pemilik')){
				$foto_pemilik = "gagal";
			}else{
				$foto_pemilik = $this->upload->file_name;
			}
			$this->upload->initialize($config);
			if(!$this->upload->do_upload('foto_ruko')){
				$foto_ruko = "gagal";
			}else{
				$foto_ruko = $this->upload->file_name;
			}


			$data['id_toko']				= $this->input->post('id_toko');		
			$data['nama_toko'] 			= $this->input->post('nama_toko');		
			$data['no_telp']				= $this->input->post('no_telp');		
			$data['alamat']					= $this->input->post('alamat');		
			$data['id_kelurahan'] 	= $this->input->post('id_kelurahan');		
			$data['top_part'] 			= $this->input->post('top_part');		
			$data['top_oli'] 				= $this->input->post('top_oli');		
			$data['npwp'] 					= $this->input->post('npwp');				
			$data['nama_pemilik'] 	= $this->input->post('nama_pemilik');				
			$data['status_toko'] 		= $this->input->post('status_toko');				
			$data['tipe_diskon']		= $this->input->post('tipe_diskon');				
			$data['diskon_fix']			= $this->input->post('diskon_fix');				
			$data['diskon_reguler']	= $this->input->post('diskon_reguler');
			$data['diskon_hotline']	= $this->input->post('diskon_hotline');				
			$data['diskon_urgent']	= $this->input->post('diskon_urgent');				
			$data['kode_ahm']				= $this->input->post('kode_ahm');				
			$data['jumlah_ruko']		= $this->input->post('jumlah_ruko');							
			$data['foto_pemilik']		= $foto_pemilik;
			$data['foto_ruko']			= $foto_ruko;
			if($this->input->post('status') == '1'){
				$data['status']				= $this->input->post('status');		
			}else{
				$data['status'] 			= "";
			}
			$data['created_at']			= $waktu;
			$data['created_by']			= $login_id;				

			if($foto_ruko == 'gagal'){			
				$_SESSION['pesan'] 	= "Foto Ruko gagal upload!";
				$_SESSION['tipe'] 	= "danger";					
				echo "<script>history.go(-1)</script>";				
			}elseif($foto_pemilik == 'gagal'){			
				$_SESSION['pesan'] 	= "Foto Pemilik gagal upload!";
				$_SESSION['tipe'] 	= "danger";					
				echo "<script>history.go(-1)</script>";				
			}else{				
				$this->m_admin->insert($tabel,$data);
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/toko/add'>";
			}
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');		
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{				
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/toko'>";
		}
	}
	
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_toko'] = $this->m_admin->kondisi($tabel,$d);		
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");		
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']			= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$id					= $this->input->post("id_toko");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$config['upload_path'] 			= './assets/panel/files/';
			$config['allowed_types'] 		= 'jpg|jpeg|png';
			$config['max_size']					= '500';					
			$this->upload->initialize($config);
			if($this->upload->do_upload('foto_pemilik')){
				$data['foto_pemilik']=$this->upload->file_name;					
				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();							
			}else{
				$foto_pemilik = "besar";
			}
			if($this->upload->do_upload('foto_ruko')){
				$data['foto_ruko']=$this->upload->file_name;					
				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();							
			}else{
				$foto_ruko = "besar";
			}


			$data['id_toko']				= $this->input->post('id_toko');		
			$data['nama_toko'] 			= $this->input->post('nama_toko');		
			$data['no_telp']				= $this->input->post('no_telp');		
			$data['alamat']					= $this->input->post('alamat');		
			$data['id_kelurahan'] 	= $this->input->post('id_kelurahan');		
			$data['top_part'] 			= $this->input->post('top_part');		
			$data['top_oli'] 				= $this->input->post('top_oli');		
			$data['npwp'] 					= $this->input->post('npwp');				
			$data['nama_pemilik'] 	= $this->input->post('nama_pemilik');				
			$data['status_toko'] 		= $this->input->post('status_toko');				
			$data['tipe_diskon']		= $this->input->post('tipe_diskon');				
			$data['diskon_fix']			= $this->input->post('diskon_fix');				
			$data['diskon_reguler']	= $this->input->post('diskon_reguler');
			$data['diskon_hotline']	= $this->input->post('diskon_hotline');				
			$data['diskon_urgent']	= $this->input->post('diskon_urgent');				
			$data['kode_ahm']				= $this->input->post('kode_ahm');				
			$data['jumlah_ruko']		= $this->input->post('jumlah_ruko');							
			
			$data['updated_at']			= $waktu;
			$data['updated_by']			= $login_id;

			if($this->input->post('status') == '1'){
				$data['status']	= $this->input->post('status');		
			}else{
				$data['status'] 			= "";
			}

			if($foto_ruko == 'gagal'){			
				$_SESSION['pesan'] 	= "Foto Ruko gagal upload!";
				$_SESSION['tipe'] 	= "danger";					
				echo "<script>history.go(-1)</script>";				
			}elseif($foto_pemilik == 'gagal'){			
				$_SESSION['pesan'] 	= "Foto Pemilik gagal upload!";
				$_SESSION['tipe'] 	= "danger";					
				echo "<script>history.go(-1)</script>";				
			}else{				
				$this->m_admin->update($tabel,$data,$pk,$id);
				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/toko'>";
			}
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}	
}