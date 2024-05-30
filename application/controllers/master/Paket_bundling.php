<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_bundling extends CI_Controller {

    var $tables =   "ms_paket_bundling";	
		var $folder =   "master";
		var $page		=		"paket_bundling";
    var $pk     =   "id_paket_bundling";
    var $title  =   "Master Data Paket Bundling";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_part');		
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
		$data['dt_paket_bundling'] = $this->m_admin->getAll($this->tables);
		//$data['dt_part'] = $this->db->query("SELECT * FROM ms_part LEFT JOIN ms_satuan ON ms_part.id_satuan = ms_satuan.id_satuan WHERE ms_part.active = '1'");
		$data['dt_apparel'] = $this->db->query("SELECT * FROM ms_apparel WHERE ms_apparel.active = '1'");
		$this->template($data);	
	}
	public function browsePart()
	{				
		$data['dt_part'] = $this->db->query("SELECT * FROM ms_part LEFT JOIN ms_satuan ON ms_part.id_satuan = ms_satuan.id_satuan WHERE ms_part.active = '1' LIMIT 0,10");
		$this->load->view('master/t_part_browse',$data);
	}
	public function ajax_list()
	{				
		$list = $this->m_part->get_datatables();		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
		
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->id_part;
			$row[] = $isi->nama_part;			
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_part')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_part->count_all(),
						"recordsFiltered" => $this->m_part->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";			
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan	 
																			ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna 
																			ON ms_item.id_warna=ms_warna.id_warna");							
		$data['dt_part'] = $this->db->query("SELECT * FROM ms_part LEFT JOIN ms_satuan ON ms_part.id_satuan = ms_satuan.id_satuan WHERE ms_part.active = '1'");
		$data['dt_apparel'] = $this->db->query("SELECT * FROM ms_apparel WHERE ms_apparel.active = '1'");
		$this->template($data);	
	}
	public function t_part(){
		$id = $this->input->post('id_paket_bundling');
		$dq = "SELECT * FROM ms_paket_bundling_detail INNER JOIN ms_part ON ms_paket_bundling_detail.id_part=ms_part.id_part WHERE ms_paket_bundling_detail.id_paket_bundling = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$this->load->view('master/t_bundling_part',$data);
	}
	public function cek_part()
	{		
		$id_part = $this->input->post('id_part');		
		$sql = $this->db->query("SELECT * FROM ms_part WHERE ms_part.id_part = '$id_part'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();										
			$id_part = $dt_ve->id_part;
			$nama_part = $dt_ve->nama_part;
		}else{
			$id_part = "";
			$nama_part = "";
		}
			
			
		echo "ok"."|".$id_part."|".$nama_part;
	}
	public function save_part(){
		$id_paket_bundling		= $this->input->post('id_paket_bundling');			
		$id_part							= $this->input->post('id_part');
		$data['id_paket_bundling']				= $this->input->post('id_paket_bundling');			
		$data['id_part']			= $this->input->post('id_part');			
		$data['qty_part']			= $this->input->post('qty');							
		$cek = $this->db->get_where("ms_paket_bundling_detail",array("id_part"=>$id_part,"id_paket_bundling"=>$id_paket_bundling));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_paket_bundling_detail;
			$this->m_admin->update("ms_paket_bundling_detail",$data,"id_paket_bundling_detail",$id);			
		}else{
			$this->m_admin->insert("ms_paket_bundling_detail",$data);			
		}
		echo "nihil";
	}
	public function delete_part(){
		$id_paket_bundling_detail = $this->input->post('id_paket_bundling_detail');		
		$this->db->query("DELETE FROM ms_paket_bundling_detail WHERE id_paket_bundling_detail = '$id_paket_bundling_detail'");			
		echo "nihil";
	}
	public function cari_id(){						
		$pr_num	= $this->db->query("SELECT * FROM ms_paket_bundling ORDER BY id DESC LIMIT 0,1");						       
	  if($pr_num->num_rows()>0){
	   	$row 	= $pr_num->row();		
	   	$id 	= substr($row->id_paket_bundling,3,4); 
	    $kode = "PB-".sprintf("%04d", $id+1);
		}else{
			$kode = "PB-0001";
		}
		echo $kode;
	}
	public function t_apparel(){
		$id = $this->input->post('id_paket_bundling');
		$dq = "SELECT * FROM ms_paket_bundling_app LEFT JOIN ms_apparel ON ms_paket_bundling_app.id_apparel=ms_apparel.id_apparel WHERE ms_paket_bundling_app.id_paket_bundling = '$id'";
		$data['dt_data'] 	= $this->db->query($dq);
		$this->load->view('master/t_bundling_apparel',$data);
	}
	public function cek_apparel()
	{		
		$id_apparel = $this->input->post('id_apparel');		
		$sql = $this->db->query("SELECT * FROM ms_apparel WHERE id_apparel = '$id_apparel'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();										
			$id_apparel = $dt_ve->id_apparel;
			$apparel = $dt_ve->apparel;
		}else{
			$id_apparel = "";
			$apparel = "";
		}
			
			
		echo "ok"."|".$id_apparel."|".$apparel;
	}
	public function save_apparel(){
		$id_paket_bundling		= $this->input->post('id_paket_bundling');			
		$id_apparel						= $this->input->post('id_apparel');
		$data['id_paket_bundling']				= $this->input->post('id_paket_bundling');			
		$data['id_apparel']		= $this->input->post('id_apparel');			
		$data['qty_apparel']	= $this->input->post('qty_apparel');							
		$cek = $this->db->get_where("ms_paket_bundling_app",array("id_apparel"=>$id_apparel,"id_paket_bundling"=>$id_paket_bundling));
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_paket_bundling_detail;
			$this->m_admin->update("ms_paket_bundling_app",$data,"id_paket_bundling_detail",$id);			
		}else{
			$this->m_admin->insert("ms_paket_bundling_app",$data);			
		}
		echo "nihil";
	}
	public function delete_apparel(){
		$id_paket_bundling_app = $this->input->post('id_paket_bundling_app');		
		$this->db->query("DELETE FROM ms_paket_bundling_app WHERE id_paket_bundling_app = '$id_paket_bundling_app'");			
		echo "nihil";
	}


	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel			= $this->tables;		
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_paket_bundling'] 		= $this->input->post('id_paket_bundling');				
			$data['nama_paket_bundling'] 	= $this->input->post('nama_paket_bundling');				
			$data['id_item'] = $id_item 	= $this->input->post('id_item');
			$data['id_item_baru'] 				= $this->input->post('id_item_baru');
			$id_item_baru 								= $this->input->post('id_item_baru');
			$amb = explode("-", $id_item_baru);
			$amb2 = explode("-", $id_item);
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}				
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$cek1 = $this->m_admin->getByID("ms_warna","id_warna",$amb2[1])->row();
			$cek2 = $this->m_admin->getByID("ms_warna","id_warna",$amb[1]);
			if($cek2->num_rows() == 0){
				$update_warna = $this->db->query("INSERT INTO ms_warna VALUES ('$amb[1]','$cek1->warna','$cek1->warna_samsat','$waktu','$login_id','','','0')");
			}
			$this->db->query("UPDATE ms_item SET bundling='Ya',id_warna='$amb[1]',id_warna_lama = '$amb2[1]',id_item_lama='$id_item' WHERE id_item = '$id_item_baru'");
			
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/paket_bundling/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}	
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{		
			$this->db->trans_begin();			
			
			// $get = $this->m_admin->getByID("ms_paket_bundling","id_paket_bundling",$id)->row();			
			// $amb = explode("-", $get->id_item_baru);
			// $amb2 = explode("-", $get->id_item);

			// $cek1 = $this->m_admin->getByID("ms_warna","id_warna",$amb2[1])->row();
			// $cek2 = $this->m_admin->getByID("ms_warna","id_warna",$amb[1]);			
			// $this->db->query("UPDATE ms_item SET bundling='',id_warna='$amb2[1]',id_warna_lama = '',id_item_lama='' WHERE id_item = '$id_item_baru'");


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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/paket_bundling'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_paket'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan	 
																			ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna 
																			ON ms_item.id_warna=ms_warna.id_warna");							
		$data['dt_part'] = $this->db->query("SELECT * FROM ms_part LEFT JOIN ms_satuan ON ms_part.id_satuan = ms_satuan.id_satuan WHERE ms_part.active = '1'");
		$data['dt_apparel'] = $this->db->query("SELECT * FROM ms_apparel WHERE ms_apparel.active = '1'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['nama_paket_bundling'] 	= $this->input->post('nama_paket_bundling');				
			$data['id_item'] = $id_item							= $this->input->post('id_item');
			$data['id_item_baru'] = $id_item_baru				= $this->input->post('id_item_baru');
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);

			$amb = explode("-", $id_item_baru);
			$amb2 = explode("-", $id_item);
			$cek1 = $this->m_admin->getByID("ms_warna","id_warna",$amb2[1])->row();
			$cek2 = $this->m_admin->getByID("ms_warna","id_warna",$amb[1]);
			if($cek2->num_rows() == 0){
				$update_warna = $this->db->query("INSERT INTO ms_warna VALUES ('$amb[1]','$cek1->warna','$cek1->warna_samsat','$waktu','$login_id','','','0')");
			}
			$this->db->query("UPDATE ms_item SET bundling='Ya',id_warna='$amb[1]',id_warna_lama = '$amb2[1]',id_item_lama='$id_item' WHERE id_item = '$id_item_baru'");

			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/paket_bundling'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}