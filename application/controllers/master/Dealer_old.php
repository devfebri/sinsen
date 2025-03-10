<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dealer extends CI_Controller {
    var $tables =   "ms_dealer";	
		var $folder =   "master";
		var $page		=		"dealer";
    var $pk     =   "id_dealer";
    var $title  =   "Master Data Dealer";
    
	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
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
		$data['dt_dealer'] = $this->db->query("SELECT ms_dealer.*,ms_kelompok_harga.kelompok_harga,ms_kelurahan.kelurahan FROM ms_dealer LEFT JOIN ms_kelurahan ON
																					ms_dealer.id_kelurahan=ms_kelurahan.id_kelurahan LEFT JOIN ms_kelompok_harga ON
																					ms_dealer.id_kelompok_harga=ms_kelompok_harga.id_kelompok_harga");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$data['dt_kelompok_harga'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");
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
		echo $id_kelurahan."|".$kelurahan;
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel			= $this->tables;		
		$config['upload_path'] 		= './assets/panel/images/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';
				
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('logo')){
			$logo	= "";
		}else{
			$logo	= $this->upload->file_name;
		}
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('favicon')){
			$favicon	= "";
		}else{
			$favicon	= $this->upload->file_name;
		}
		$data['nama_dealer'] 					= $this->input->post('nama_dealer');		
		$data['kode_dealer_md'] 			= $this->input->post('kode_dealer_md');		
		$data['kode_dealer_ahm'] 			= $this->input->post('kode_dealer_ahm');		
		$data['kode_dealer_ahm_link'] = $this->input->post('kode_dealer_ahm_link');		
		$data['alamat'] 							= $this->input->post('alamat');		
		$data['no_telp'] 							= $this->input->post('no_telp');		
		$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
		if($this->input->post('h1') == '1') $data['h1'] = $this->input->post('h1');		
			else $data['h1'] = "";							
		if($this->input->post('h2') == '1') $data['h2'] = $this->input->post('h2');		
			else $data['h2'] = "";							
		if($this->input->post('h3') == '1') $data['h3'] = $this->input->post('h3');		
			else $data['h3'] = "";							
		$data['npwp'] 								= $this->input->post('npwp');
		$data['pkp'] 									= $this->input->post('pkp');
		$data['email'] 								= $this->input->post('email');
		$data['id_kelompok_harga'] 		= $this->input->post('id_kelompok_harga');
		$data['top_unit'] 						= $this->input->post('top_unit');
		$data['top_part'] 						= $this->input->post('top_part');
		$data['pemilik'] 							= $this->input->post('pemilik');
		$data['pic'] 									= $this->input->post('pic');
		$data['tipe_diskon'] 					= $this->input->post('tipe_diskon');
		$data['diskon_fixed_order'] 	= $this->input->post('diskon_fixed_order');
		$data['diskon_reguler'] 			= $this->input->post('diskon_reguler');
		$data['diskon_hotline'] 			= $this->input->post('diskon_hotline');
		$data['diskon_urgent'] 				= $this->input->post('diskon_urgent');
		$data['dealer_cb_ssp']				= $this->input->post('dealer_cb_ssp');
		$data['dealer_group_ssp']			= $this->input->post('dealer_group_ssp');
		$data['bisa_pilih_unit_do']		= $this->input->post('bisa_pilih_unit_do');
		$data['maks_penitipan_unit']	= $this->input->post('maks_penitipan_unit');
		$data['maks_hari_penitipan']	= $this->input->post('maks_hari_penitipan');
		$data['hrs_pdi']							= $this->input->post('hrs_pdi');
		$data['biaya_pdi']						= $this->input->post('biaya_pdi');
		$data['kirim_samsat']					= $this->input->post('kirim_samsat');
		$data['dealer_financing']			= $this->input->post('dealer_financing');
		$data['gudang_unit']					= $this->input->post('gudang_unit');
		$data['gudang_part']					= $this->input->post('gudang_part');
		$data['gudang_sendiri']				= $this->input->post('gudang_sendiri');
		$data['limit_po']							= $this->input->post('limit_po');		
		$data['pimpinan']							= $this->input->post('pimpinan');		
		$data['nama_kecil']						= $this->input->post('nama_kecil');		
		$data['pos']									= $this->input->post('pos');		
		$data['id_dealer_induk']			= $this->input->post('id_dealer_induk');		
		$data['logo']									= $logo;		
		$data['favicon']							= $favicon;		
		if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
			else $data['active'] = "";
		$data['created_at']				= $waktu;		
		$data['created_by']				= $login_id;
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/dealer/add'>";		
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/dealer'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_dealer'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_dealer_ms'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");			
		$data['dt_area_penjualan'] = $this->db->query("SELECT * FROM ms_area_penjualan WHERE active = 1 ORDER BY area_penjualan ASC");		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');
		$tabel					= $this->tables;
		$pk 						= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$config['upload_path'] 		= './assets/panel/images/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
			$config['max_size']				= '2000';
			$config['max_width']  		= '2000';
			$config['max_height']  		= '1024';
			$this->upload->initialize($config);
			if($this->upload->do_upload('logo')){
				$data['logo']=$this->upload->file_name;								
			}
			$this->upload->initialize($config);			
			if($this->upload->do_upload('favicon')){
				$data['favicon']=$this->upload->file_name;							
			}

			$data['nama_dealer'] 					= $this->input->post('nama_dealer');		
			$data['kode_dealer_md'] 			= $this->input->post('kode_dealer_md');		
			$data['kode_dealer_ahm'] 			= $this->input->post('kode_dealer_ahm');		
			$data['kode_dealer_ahm_link'] = $this->input->post('kode_dealer_ahm_link');		
			$data['alamat'] 							= $this->input->post('alamat');		
			$data['no_telp'] 							= $this->input->post('no_telp');		
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			if($this->input->post('h1') == '1') $data['h1'] = $this->input->post('h1');		
				else $data['h1'] = "";							
			if($this->input->post('h2') == '1') $data['h2'] = $this->input->post('h2');		
				else $data['h2'] = "";							
			if($this->input->post('h3') == '1') $data['h3'] = $this->input->post('h3');		
				else $data['h3'] = "";							
			$data['npwp'] 								= $this->input->post('npwp');
			$data['pkp'] 									= $this->input->post('pkp');
			$data['email'] 								= $this->input->post('email');
			$data['id_kelompok_harga'] 		= $this->input->post('id_kelompok_harga');
			$data['top_unit'] 						= $this->input->post('top_unit');
			$data['top_part'] 						= $this->input->post('top_part');
			$data['pemilik'] 							= $this->input->post('pemilik');
			$data['pic'] 									= $this->input->post('pic');
			$data['tipe_diskon'] 					= $this->input->post('tipe_diskon');
			$data['diskon_fixed_order'] 	= $this->input->post('diskon_fixed_order');
			$data['diskon_reguler'] 			= $this->input->post('diskon_reguler');
			$data['diskon_hotline'] 			= $this->input->post('diskon_hotline');
			$data['diskon_urgent'] 				= $this->input->post('diskon_urgent');
			$data['dealer_cb_ssp']				= $this->input->post('dealer_cb_ssp');
			$data['dealer_group_ssp']			= $this->input->post('dealer_group_ssp');
			$data['bisa_pilih_unit_do']		= $this->input->post('bisa_pilih_unit_do');
			$data['maks_penitipan_unit']	= $this->input->post('maks_penitipan_unit');
			$data['maks_hari_penitipan']	= $this->input->post('maks_hari_penitipan');
			$data['hrs_pdi']							= $this->input->post('hrs_pdi');
			$data['biaya_pdi']						= $this->input->post('biaya_pdi');
			$data['kirim_samsat']					= $this->input->post('kirim_samsat');
			$data['dealer_financing']			= $this->input->post('dealer_financing');
			$data['gudang_unit']					= $this->input->post('gudang_unit');
			$data['gudang_part']					= $this->input->post('gudang_part');
			$data['gudang_sendiri']				= $this->input->post('gudang_sendiri');
			$data['limit_po']							= $this->input->post('limit_po');
			$data['pimpinan']							= $this->input->post('pimpinan');		
			$data['nama_kecil']						= $this->input->post('nama_kecil');		
			$data['pos']									= $this->input->post('pos');		
			$data['id_dealer_induk']			= $this->input->post('id_dealer_induk');		
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] = "";		
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
			$this->m_admin->update($tabel,$data,$pk,$id);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/dealer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function view()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$page				= $this->page;
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);			
		
		$data['dt_area_penjualan'] = $this->db->query("SELECT * FROM ms_area_penjualan WHERE active = 1 ORDER BY area_penjualan ASC");		
		$data['dt_dealer'] = $this->m_admin->getByID($tabel,$pk,$id);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['set']		= "detail";									
		$this->template($data);
		
	}
}