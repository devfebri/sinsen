<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Target_salesman extends CI_Controller {

    var $tables =   "ms_target_salesman";	
		var $folder =   "master";
		var $page		=		"target_salesman";
    var $pk     =   "id_target_salesman";
    var $title  =   "Master Target Salesman";

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
		$this->load->library("udp_cart");//load library 
		$this->item_target   = new Udp_cart("item");
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
		$data['dt_target_salesman'] = $this->db->query("SELECT * FROM ms_target_salesman LEFT JOIN ms_karyawan_dealer ON ms_target_salesman.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer");									
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;								
		$data['set']		= "insert";		
		$data['dt_karyawan']= $this->m_admin->getAll("ms_karyawan_dealer");
		$this->template($data);	
	}
	public function detail()
	{				
		$id_target_salesman = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_target_salesman'] = $this->db->query("SELECT * FROM ms_target_salesman LEFT JOIN ms_karyawan_dealer ON ms_target_salesman.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE ms_target_salesman.id_target_salesman = '$id_target_salesman'");														
		$data['set']		= "detail";				
		$this->template($data);	
	}	
	public function take_karyawan()
	{		
		$id_karyawan_dealer	= $this->input->post('id_karyawan_dealer');	
		$dt_kel						= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer = '$id_karyawan_dealer'")->row();
		$nama_lengkap 		= $dt_kel->nama_lengkap;		
		echo $id_karyawan_dealer."|".$nama_lengkap;
	}
	public function t_detail(){
		$data['dt_toko']= $this->m_admin->getAll("ms_toko");
  	$this->load->view("master/t_target_salesman",$data);
  }
  public function take_toko()
	{		
		$id_toko	= $this->input->post('id_toko');	
		$dt_kel						= $this->db->query("SELECT * FROM ms_toko WHERE id_toko = '$id_toko'")->row();
		$nama_toko 		= $dt_kel->nama_toko;		
		echo $id_toko."|".$nama_toko;
	}
	public function save_detail(){
  	$data['id'] = rand(1,9999);				
		$data['id_toko'] = $id_toko = $this->input->post('id_toko');
		$data['nama_toko'] = $this->input->post('nama_toko');
		$data['target_part'] = $this->input->post('target_part');
		$data['qty'] = $this->input->post('target_oli');
		$data['price'] = $this->input->post('target_aksesoris');		
		$no=1;
		if($item_target = $this->item_target->get_content()) {
			foreach ($item_target as $res){
				if($id_toko == $res['id_toko']){
					$no++;
				}				
			}
		}

		if($no==1){
			$this->item_target->insert($data);			
			$data['dt_toko']= $this->m_admin->getAll("ms_toko");			
			$this->load->view("master/t_target_salesman",$data);			
		}else{
			echo "failed";
		}
  }
  public function delete_detail(){
  	$rowid=$this->input->post('id');
		if($this->item_target->remove_item($rowid)){
			$data['dt_toko']= $this->m_admin->getAll("ms_toko");			
			$this->load->view("master/t_target_salesman",$data);			
		}else{
			echo "failed";
		}
  }
	public function save()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);		
		$login_id  = $this->session->userdata('id_user');				
		$data['id_karyawan_dealer']   = $this->input->post('id_karyawan_dealer');
		$data['periode_awal'] 	= $this->input->post('periode_awal');
		$data['periode_akhir'] 	= $this->input->post('periode_akhir');

		$this->db->trans_begin();
		$this->db->insert('ms_target_salesman',$data);
		$id = $this->db->insert_id();

		if($item_target = $this->item_target->get_content())
		{
			foreach($item_target as $key => $val){				
				$detail[$key]['id_target_salesman']   = $id;
				$detail[$key]['id_toko']   = $val['id_toko'];
				$detail[$key]['target_part']   = $val['target_part'];
				$detail[$key]['target_oli']   = $val['qty'];				
				$detail[$key]['target_acc']   = $val['price'];								
			}
		}else{
			$_SESSION['pesan'] 	= "Data item masih kosong";
			$_SESSION['tipe'] 	= "danger";
			redirect(site_url('master/target_salesman'));
			echo "<script>history.go(-1)</script>";
		}

		$this->db->insert_batch('ms_target_salesman_detail',$detail);
		if ($this->db->trans_status() === FALSE){
    	$this->db->trans_rollback();
      $_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/target_salesman'>";
    }else{
      $this->db->trans_commit();
      $this->item_target->destroy();
      $_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/target_salesman'>";
    }
	}	
	


	public function hapus_detail()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->post('id');				
		$this->m_admin->delete("ms_target_salesman_detail","id_target_salesman_detail",$id);
		echo "ok";
	}
	
	public function edit()
	{				
		$id_target_salesman = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_target_salesman'] = $this->db->query("SELECT * FROM ms_target_salesman LEFT JOIN ms_karyawan_dealer ON ms_target_salesman.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE ms_target_salesman.id_target_salesman = '$id_target_salesman'");														
		$data['set']		= "edit";			
		$data['dt_karyawan']= $this->m_admin->getAll("ms_karyawan_dealer");	
		$this->template($data);	
	}
	public function update_detail()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$id					= $this->input->post("id_diskon_oli");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			
			$data['id_part']					= $this->input->post('id_part');		
			$data['tipe_diskon'] 			= $this->input->post('tipe_diskon');		
			$data['range_1']					= $this->input->post('range1');
			$data['range_2']					= $this->input->post('range2');
			$data['range_3']					= $this->input->post('range3');
				
			$this->m_admin->update($tabel,$data,$pk,"1");
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_reguler/edit'>";
		}else{
			echo "failed";
		}
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$id					= $this->input->post("id_target_salesman");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
						
			$data['periode_awal']			= $this->input->post('periode_awal');
			$data['periode_akhir']		= $this->input->post('periode_akhir');
				
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/target_salesman'>";
		}else{
			echo "failed";
		}
	}	
	public function edit_diskon(){
		$id_diskon_oli = $this->input->post('id_diskon_oli');		
		//$id_diskon_oli = $this->input->get('id');		
		$dq = "SELECT * FROM ms_diskon_oli INNER JOIN ms_part ON ms_diskon_oli.id_part = ms_part.id_part WHERE ms_diskon_oli.id_diskon_oli = '$id_diskon_oli'";
		$sql = $this->db->query($dq);		
		if($sql->num_rows() > 0) {
			$data['dt_sql'] = $sql->row();
		}
		$data['sql'] = "";
		$this->load->view('master/t_diskon_oli',$data);		
	}
}