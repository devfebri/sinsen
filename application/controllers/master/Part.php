<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Part extends CI_Controller {

	var $tables =   "ms_part";	
	var $folder =   "master";
	var $page		=		"part";
	var $pk     =   "id_part";
	var $title  =   "Master Data Part";

	public function __construct()
	{		
		parent::__construct();
		ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
        ini_set('max_execution_time', 0);
        ini_set('max_input_time', 0);
        ini_set('upload_max_filesize', '128M');

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_part');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
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
		$data['set']	= "view_fix";
		$data['dt_part'] = $this->db->query("SELECT ms_part.*,ms_satuan.satuan FROM ms_part 
							LEFT JOIN ms_satuan ON ms_part.id_satuan=ms_satuan.id_satuan");							
		$data['dt_ptm'] = $this->m_admin->getAll("ms_pvtm");
		$this->template($data);	
	}
	public function ajax_list_2()
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
	public function ajax_list()
	{				
		$list = $this->m_part->get_datatables();		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {

			$isi2 = $this->m_admin->getByID("ms_satuan","id_satuan",$isi->id_satuan);
			if($isi2->num_rows() > 0){
				$i = $isi2->row();
				$satuan = $i->satuan;
			}else{
				$satuan = "";
			}
      if($isi->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";

      $id_menu = $this->m_admin->getMenu($this->page);
			$group 	= $this->session->userdata("group");
      $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
			$delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            


			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->id_part;
			$row[] = $isi->nama_part;
			$row[] = $isi->kelompok_vendor;
			$row[] = $satuan;
			$row[] = $isi->min_stok;
			$row[] = $isi->maks_stok;
			$row[] = $isi->safety_stok;
			$row[] = $isi->kelompok_part;
			$row[] = $active;
			$row[] = "
								<a $delete data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"master/part/delete?id=$isi->id_part\"><i class=\"fa fa-trash-o\"></i></a>
                <a $edit data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"master/part/edit?id=$isi->id_part\"><i class=\"fa fa-edit\"></i></a>
							";			
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
		$data['dt_vendor'] = $this->m_admin->getSortCond("ms_kelompok_vendor","kelompok_vendor","ASC");
		$data['dt_part'] = $this->m_admin->getSortCond("ms_kelompok_part","kelompok_part","ASC");
		$data['dt_satuan'] = $this->m_admin->getSortCond("ms_satuan","satuan","ASC");
		$data['dt_ptm'] = $this->m_admin->getAll("ms_pvtm");
		$this->template($data);	
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$config['upload_path'] 		= './assets/panel/images/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '100';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){

			$this->upload->initialize($config);
			if(!$this->upload->do_upload('gambar')){
				$gambar	= "";
			}else{
				$gambar	= $this->upload->file_name;
			}

			$data['id_part'] 					= $this->input->post('id_part');
			$data['nama_part'] 				= $this->input->post('nama_part');		
			$data['kelompok_vendor']	= $this->input->post('kelompok_vendor');		
			$data['id_satuan'] 				= $this->input->post('id_satuan');		
			$data['min_stok'] 				= $this->input->post('min_stok');		
			$data['maks_stok'] 				= $this->input->post('maks_stok');		
			$data['safety_stok'] 			= $this->input->post('safety_stok');		
			$data['min_sales'] 				= $this->input->post('min_sales');		
			$data['kelompok_part'] = $this->input->post('kelompok_part');		
			$data['harga_md_dealer'] 	= $this->input->post('harga_md_dealer');		
			$data['harga_dealer_user']= $this->input->post('harga_dealer_user');		
			$data['gambar'] 					= $gambar;
			$data['pnt'] 							= $this->input->post('pnt');		
			$data['fast_slow'] 				= $this->input->post('fast_slow');		
			$data['import_lokal'] 		= $this->input->post('import_lokal');		
			$data['rank'] 						= $this->input->post('rank');		
			$data['current'] 					= $this->input->post('current');		
			$data['important'] 				= $this->input->post('important');		
			$data['long'] 						= $this->input->post('long');		
			$data['engine'] 					= $this->input->post('engine');		
			$data['recommend_part'] 	= $this->input->post('recommend_part');		
			$data['status'] 					= $this->input->post('status');		
			$data['superseed'] 				= $this->input->post('superseed');
			$data['part_oli'] 				= $this->input->post('part_oli');
			$data['qty_dus'] 					= $this->input->post('qty_dus');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/part/add'>";			
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/part'>";
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
		$data['dt_part'] = $this->m_admin->kondisi($tabel,$d);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_vendor'] = $this->m_admin->getSortCond("ms_kelompok_vendor","kelompok_vendor","ASC");
		$data['dt_k_part'] = $this->m_admin->getSortCond("ms_kelompok_part","kelompok_part","ASC");
		$data['dt_ptm'] = $this->m_admin->getAll("ms_pvtm");
		$data['set']	= "edit";									
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
		
		$config['upload_path'] 		= './assets/panel/images/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '100';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';

		if($cek == 0 or $id == $id_){

			$this->upload->initialize($config);
			if($this->upload->do_upload('gambar')){
				$data['gambar']=$this->upload->file_name;
				
				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();			
				unlink("assets/panel/images/".$one->gambar); //Hapus Gambar
			}

			$data['id_part'] 					= $this->input->post('id_part');
			$data['nama_part'] 				= $this->input->post('nama_part');		
			$data['kelompok_vendor']	= $this->input->post('kelompok_vendor');		
			$data['id_satuan'] 				= $this->input->post('id_satuan');		
			$data['min_stok'] 				= $this->input->post('min_stok');		
			$data['maks_stok'] 				= $this->input->post('maks_stok');		
			$data['safety_stok'] 			= $this->input->post('safety_stok');		
			$data['min_sales'] 				= $this->input->post('min_sales');		
			$data['kelompok_part'] = $this->input->post('kelompok_part');		
			$data['harga_md_dealer'] 	= $this->input->post('harga_md_dealer');		
			$data['harga_dealer_user']= $this->input->post('harga_dealer_user');		
			//$data['jenis'] 						= $this->input->post('jenis');		
			$data['pnt'] 							= $this->input->post('pnt');		
			$data['fast_slow'] 				= $this->input->post('fast_slow');		
			$data['import_lokal'] 		= $this->input->post('import_lokal');		
			$data['rank'] 						= $this->input->post('rank');		
			$data['current'] 					= $this->input->post('current');		
			$data['important'] 				= $this->input->post('important');		
			$data['long'] 						= $this->input->post('long');		
			$data['engine'] 					= $this->input->post('engine');		
			$data['recommend_part'] 	= $this->input->post('recommend_part');		
			$data['status'] 					= $this->input->post('status');		
			$data['superseed'] 				= $this->input->post('superseed');
			$data['part_oli'] 				= $this->input->post('part_oli');
			$data['qty_dus'] 					= $this->input->post('qty_dus');
			
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/part'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function t_part(){		
		$id_part		= $this->input->post('id_part');					
		$mode				= $this->input->post('mode');					

		$cek_mode 	= $this->db->query("SELECT * FROM ms_part WHERE id_part = '$id_part'");		
		if($cek_mode->num_rows() > 0){
			$r = $cek_mode->row();
			$mode = $r->import;
		}else{
			$mode = "";
		}
		if($mode == ''){
			$cek_pvtm 	= $this->db->query("SELECT * FROM ms_pvtm WHERE no_part = '$id_part'");
			if($cek_pvtm->num_rows() > 0){
				$pvtm = $cek_pvtm->row();
				//$tipe_marketing = $pvtm->tipe_marketing;
				//$cek_ptm 	= $this->db->query("SELECT * FROM ms_ptm WHERE tipe_motor = '$tipe_marketing'");
				///if($cek_ptm->num_rows() > 0){
					foreach ($cek_pvtm->result() as $isi) {
						$id_pvtm					= $isi->id_pvtm;
						$data['id_pvtm']	= $isi->id_pvtm;
						$data['id_part']	= $id_part;
						$c = $this->db->query("SELECT * FROM ms_part_detail WHERE id_part = '$id_part' AND id_pvtm = '$id_pvtm'");
						if($c->num_rows() > 0){
							$df = $c->row();
							$id = $df->id_part_detail;
							$cek2 = $this->m_admin->update("ms_part_detail",$data,"id_part_detail",$id);						

							$dt['import'] = 'done';
							$cek2 = $this->m_admin->update("ms_part",$dt,"id_part",$id_part);						
						}else{
							$cek2 = $this->m_admin->insert("ms_part_detail",$data);												

							$dt['import'] = 'done';
							$cek2 = $this->m_admin->update("ms_part",$dt,"id_part",$id_part);						
						}			
					}
				//}
			}																																																																						
		}
		$data['dt_ptm'] = $this->db->query("SELECT * FROM ms_part_detail INNER JOIN ms_pvtm ON ms_part_detail.id_pvtm=ms_pvtm.id_pvtm WHERE id_part = '$id_part'");		
		$this->load->view('master/t_ptm',$data);
	}
	public function cek_item()
	{		
		$id_pvtm = $this->input->post('id_pvtm');		
		$sql 		= $this->db->query("SELECT * FROM ms_pvtm WHERE id_pvtm = '$id_pvtm'");
		if($sql->num_rows() > 0){
			$dt_ve = $sql->row();
			$sql = $this->db->query("SELECT * FROM ms_ptm WHERE tipe_motor = '$dt_ve->tipe_marketing'");
	    if($sql->num_rows() > 0){
	      $isi = $sql->row();
	      $tipe = $isi->tipe_motor;
	      $desk = $isi->deskripsi;
	    }
						echo "ok|".$tipe."|".$desk;
		}else{
			$tipe_motor = "";
			$deskripsi = "";
			echo "Data tidak ditemukan";			
		}		
	}
	public function save_ptm(){
		$id_part		= $this->input->post('id_part');			
		$id_pvtm			= $this->input->post('id_pvtm');			
		$data['id_pvtm']		= $this->input->post('id_pvtm');			
		$data['id_part']			= $this->input->post('id_part');
		$c = $this->db->query("SELECT * FROM ms_part_detail WHERE id_part = '$id_part' AND id_pvtm = '$id_pvtm'");
		if($c->num_rows() > 0){
			$df = $c->row();
			$id = $df->id_part_detail;
			$cek2 = $this->m_admin->update("ms_part_detail",$data,"id_part_detail",$id);						
		}else{
			$cek2 = $this->m_admin->insert("ms_part_detail",$data);						
			echo "ok";
		}							
	}	
	public function delete_ptm(){
		$id = $this->input->post('id_part_detail');		
		$this->db->query("DELETE FROM ms_part_detail WHERE id_part_detail = '$id'");			
		echo "nihil";
	}	
	public function upload()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_ptm'] = $this->m_admin->getAll("ms_pvtm");													
		$data['set']		= "upload";		
		$this->template($data);		
	}

	function import_db(){
		ini_set('mysql.connect_timeout', 300);
		ini_set('default_socket_timeout', 300);
    ini_set('display_errors', 1);
    ini_set('memory_limit', -1);
    ini_set('error_reporting', E_ALL);
    ini_set('max_execution_time', 10000000);
    ini_set('max_input_time', 10000000);
    ini_set('upload_max_filesize', '128M');
		$filename = $_FILES["userfile"]["tmp_name"];
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		if($_FILES['userfile']['size'] > 0)
		{
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;
			$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum1 = 1;$isi=0;
			// $dt_ins = array();$dt_upd=array();
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				// if(!$is_header_removed){
				// 	$is_header_removed = TRUE;
				// 	continue;
				// }

				$row = array(
					'id_part'           =>  !empty($importdata[0])?$importdata[0]:'',
					'nama_part'         =>  !empty($importdata[1])?$importdata[1]:'',
					'harga_md_dealer'   =>  !empty($importdata[2])?$importdata[2]:'',
					'harga_dealer_user' =>  !empty($importdata[3])?$importdata[3]:'',
					'kelompok_vendor'   =>  !empty($importdata[4])?$importdata[4]:'',
					'kelompok_part'     =>  !empty($importdata[5])?$importdata[5]:'',					
					'status'            =>  !empty($importdata[7])?$importdata[7]:'',					
					'superseed'         =>  !empty($importdata[8])?$importdata[8]:'',					
					'min_stok'          =>  !empty($importdata[9])?$importdata[9]:'',					
					'maks_stok'         =>  !empty($importdata[10])?$importdata[10]:'',					
					'safety_stok'       =>  !empty($importdata[11])?$importdata[11]:'',					
					'pnt'               =>  !empty($importdata[12])?$importdata[12]:'',					
					'fast_slow'         =>  !empty($importdata[13])?$importdata[13]:'',
					'import_lokal'      =>  !empty($importdata[14])?$importdata[14]:'',
					'rank'              =>  !empty($importdata[15])?$importdata[15]:'',
					'current'           =>  !empty($importdata[16])?$importdata[16]:'',
					'important'         =>  !empty($importdata[17])?$importdata[17]:'',
					'long'              =>  !empty($importdata[18])?$importdata[18]:'',
					'engine'            =>  !empty($importdata[19])?$importdata[19]:'',
					'start_date'        =>  $waktu
				);
				$cek = $this->m_admin->getByID("ms_part","id_part",$importdata[0]);				
				if($cek->num_rows() == 0){					
					$dt_ins[] = $row;				
					$no2++;
				}else{
					$amb = $cek->row();
					$row2 = array(
						'id_part' => $amb->id_part,
						'nama_part' => $amb->nama_part,
						'harga_md_dealer' => $amb->harga_md_dealer,
						'harga_dealer_user' => $amb->harga_dealer_user,
						'end_date' => $waktu
					);
					$dt_ins2[] = $row2;
					//$this->m_admin->insert('ms_part_history', $row2);
					$this->db->query("SET FOREIGN_KEY_CHECKS = 0");
					$this->m_admin->delete('ms_part','id_part',$amb->id_part);
					$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
					//$this->m_admin->update('ms_part', $row,'id_part',$amb->id_part);
					//$dt_upd[] = $row;
					$no2++;
				}				
				$isi++;
			}
			// var_dump($dt_ins);
			$this->db->trans_begin();
			if(isset($dt_ins)){
				$this->db->insert_batch('ms_part', $dt_ins);
			}
			if(isset($dt_upd)){
				$this->db->insert_batch('ms_part_history', $dt_ins2);				
			} 
			if($this->db->trans_status() === FALSE){
	      $this->db->trans_rollback();
	    }else{
	      $this->db->trans_commit();
	    }
			fclose($file);

			$_SESSION['pesan'] 	= $isi." Data yang anda import. Berhasil = ".$no2." data.";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/part'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/part'>";	
		}				
  }
}