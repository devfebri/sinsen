<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_ditahan extends CI_Controller {

    var $tables =   "ms_stok_ditahan";	
		var $folder =   "master";
		var $page		=		"stok_ditahan";
    var $pk     =   "id_stok_ditahan";
    var $title  =   "Master Stok Ditahan";

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
		
$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
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
		// $data['isi']    = $this->page;		
		// $data['title']	= $this->title;															
		// $data['set']	= "view";
		// // $data['dt_stok_ditahan'] = $this->db->query("SELECT ms_stok_ditahan.*,ms_kelompok_harga.kelompok_harga FROM ms_stok_ditahan LEFT JOIN ms_kelompok_harga 
		// 			// ON ms_stok_ditahan.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga");							
		// $data['dt_stok_ditahan']=$this->db->query("SELECT ms_stok_ditahan.*,ms_tipe_kendaraan.tipe_ahm FROM ms_stok_ditahan
		// 	INNER JOIN ms_tipe_kendaraan on ms_stok_ditahan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
		// 	WHERE ms_stok_ditahan.status<>'new'
		// 	ORDER BY ms_stok_ditahan.id_stok_ditahan DESC 
		// 	");
		// $this->template($data);		
		$this->add();
	}


	public function getFast()
	{
		$data['fast']		= $this->db->query("SELECT * FROM ms_stok_ditahan 
							  INNER JOIN ms_tipe_kendaraan on ms_stok_ditahan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
							  WHERE jenis_moving='fast' ");
		$this->load->view('master/t_stok_ditahan_fast',$data);
	}

	public function getSlow()
	{
		$data['slow']		= $this->db->query("SELECT * FROM ms_stok_ditahan 
							  INNER JOIN ms_tipe_kendaraan on ms_stok_ditahan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
							  WHERE jenis_moving='slow'");
		$this->load->view('master/t_stok_ditahan_slow',$data);
	}

	public function addStok(){
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');
		$data['id_tipe_kendaraan']			= $this->input->post('id_tipe_kendaraan');		
		//$data['persen_stok_ditahan']	= $this->input->post('persen_stok_ditahan');			
		$getHeader = $this->db->query("SELECT * FROM ms_stok_ditahan_header")->row();		
		$data['jenis_moving']				= $this->input->post('jenis_moving');	
		if ($data['jenis_moving']=='fast') {
			
			$data['persen_stok_ditahan']	= $getHeader->persen_fast_moving;			
		}elseif ($data['jenis_moving']=='slow') {
			
			$data['persen_stok_ditahan']	= $getHeader->persen_slow_moving;			
		}		
		$data['status']					= 'new';					
		$data['created_by']				= $login_id;					
		$data['created_at']				= $waktu;					
		$this->m_admin->insert("ms_stok_ditahan",$data);		
		echo "nihil";
	}

	public function delStok(){
		$id			= $this->input->post('id');			
		$this->m_admin->delete("ms_stok_ditahan",'id_stok_ditahan',$id);		
		echo "nihil";
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$submit		= $this->input->post('save');

		$persen_fast_moving=$data[0]['persen_fast_moving'] = $this->input->post('stok_ditahan_fast');

		$persen_slow_moving=$data[0]['persen_slow_moving'] = $this->input->post('stok_ditahan_slow');
		$data[0]['updated_at']		   = $waktu;
		$data[0]['updated_by']		   = $login_id;
		$data[0]['id_header']		   = 1;
			$this->db->trans_begin();
			$this->db->update_batch('ms_stok_ditahan_header',$data, 'id_header'); 
			$this->db->query("UPDATE ms_stok_ditahan set persen_stok_ditahan='$persen_fast_moving' WHERE jenis_moving='fast' ");
			$this->db->query("UPDATE ms_stok_ditahan set persen_stok_ditahan='$persen_slow_moving' WHERE jenis_moving='slow' ");
			if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/stok_ditahan'>";	
            }
            else
            {
                $this->db->trans_commit();
                $_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/stok_ditahan'>";	
            }
					
	}



	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
		$data['dt_item'] = $this->m_admin->getSortCond("ms_item","id_item","ASC");	
		$data['set']	= "insert";									
		$this->template($data);	
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/stok_ditahan'>";
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
		$data['dt_stok_ditahan'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
		$data['dt_item'] = $this->m_admin->getSortCond("ms_item","id_item","ASC");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
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
		if($cek == 0 or $id == $id_){
			$data['id_kelompok_harga'] 	= $this->input->post('id_kelompok_harga');		
			$data['harga_bbn'] 					= $this->input->post('harga_bbn');		
			$data['harga_jual'] 				= $this->input->post('harga_jual');		
			$data['id_item'] 						= $this->input->post('id_item');		
			$data['start_date']					= $this->input->post('start_date');		
			$data['end_date']						= $this->input->post('end_date');		
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/stok_ditahan'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}