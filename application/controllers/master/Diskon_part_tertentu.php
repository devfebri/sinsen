<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon_part_tertentu extends CI_Controller {

    var $tables =   "ms_diskon_part";	
		var $folder =   "master";
		var $page		=		"diskon_part_tertentu";
    var $pk     =   "id_diskon_part";
    var $title  =   "Master Diskon Part Tertentu";

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
		$this->item_part   = new Udp_cart("item");
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
		$data['dt_diskon_part'] = $this->db->query("SELECT * FROM ms_diskon_part LEFT JOIN ms_part ON ms_diskon_part.id_part = ms_part.id_part");									
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
	
  public function take_part()
	{		
		$id_part	= $this->input->post('id_part');	
		$dt_kel						= $this->db->query("SELECT * FROM ms_part WHERE id_part = '$id_part'")->row();
		$nama_part 		= $dt_kel->nama_part;		
		echo $id_part."|".$nama_part;
	}
	public function t_detail(){
		$data['dt_dealer']= $this->m_admin->getAll("ms_dealer");
  	$this->load->view("master/t_diskon_part_tertentu",$data);
  }
  public function save_detail(){
  	$data['id'] = rand(1,9999);				
		$data['id_dealer'] = $id_dealer = $this->input->post('id_dealer');
		$data['nama_dealer'] = $this->input->post('nama_dealer');
		$data['tipe_diskon'] = $this->input->post('tipe_diskon');
		$data['qty'] = $this->input->post('diskon_fix');
		$data['price'] = $this->input->post('diskon_reguler');		
		$data['diskon_hotline'] = $this->input->post('diskon_hotline');		
		$data['diskon_urgent'] = $this->input->post('diskon_urgent');		
		$no=1;
		if($item_part = $this->item_part->get_content()) {
			foreach ($item_part as $res){
				if($id_dealer == $res['id_dealer']){
					$no++;
				}				
			}
		}

		if($no==1){
			$this->item_part->insert($data);			
			$data['dt_dealer']= $this->m_admin->getAll("ms_dealer");			
			$this->load->view("master/t_diskon_part_tertentu",$data);			
		}else{
			echo "failed";
		}
  }
  public function delete_detail(){
  	$rowid=$this->input->post('id');
		if($this->item_part->remove_item($rowid)){
			$data['dt_dealer']= $this->m_admin->getAll("ms_dealer");			
			$this->load->view("master/t_diskon_part_tertentu",$data);			
		}else{
			echo "failed";
		}
  }
  public function take_dealer()
	{		
		$id_dealer	= $this->input->post('id_dealer');	
		$dt_kel						= $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();
		$nama_dealer 		= $dt_kel->nama_dealer;		
		$kode_dealer_md 		= $dt_kel->kode_dealer_md;		
		echo $id_dealer."|".$kode_dealer_md."|".$nama_dealer;
	}

	public function detail()
	{				
		$id_diskon_part = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_diskon_part'] = $this->db->query("SELECT * FROM ms_diskon_part LEFT JOIN ms_karyawan_dealer ON ms_diskon_part.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE ms_diskon_part.id_diskon_part = '$id_diskon_part'");														
		$data['set']		= "detail";				
		$this->template($data);	
	}	
	
	
  
	
	public function save()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);		
		$login_id  = $this->session->userdata('id_user');				
		$data['id_part']   = $this->input->post('id_part');
		$data['tipe_diskon'] 	= $this->input->post('tipe_diskon');
		$data['diskon_fix'] 	= $this->input->post('diskon_fix');
		$data['diskon_reguler'] 	= $this->input->post('diskon_reguler');
		$data['diskon_hotline'] 	= $this->input->post('diskon_hotline');
		$data['diskon_urgent'] 	= $this->input->post('diskon_urgent');
		$data['diskon_other'] 	= $this->input->post('diskon_other');
		if($this->input->post('active') == '1'){
			$data['active']				= $this->input->post('active');		
		}else{
			$data['active'] 			= "";
		}
		$data['created_at']			= $waktu;
		$data['created_by']			= $login_id;

		$this->db->trans_begin();
		$this->db->insert('ms_diskon_part',$data);
		$id = $this->db->insert_id();

		if($item_part = $this->item_part->get_content())
		{
			foreach($item_part as $key => $val){				
				$detail[$key]['id_diskon_part']   = $id;
				$detail[$key]['id_dealer']   = $val['id_dealer'];
				$detail[$key]['tipe_diskon']   = $val['tipe_diskon'];
				$detail[$key]['diskon_fix']   = $val['qty'];				
				$detail[$key]['diskon_reguler']   = $val['price'];								
				$detail[$key]['diskon_hotline']   = $val['diskon_hotline'];								
				$detail[$key]['diskon_urgent']   = $val['diskon_urgent'];								
			}
		}else{
			$_SESSION['pesan'] 	= "Data item masih kosong";
			$_SESSION['tipe'] 	= "danger";
			redirect(site_url('master/diskon_part_tertentu'));
			echo "<script>history.go(-1)</script>";
		}

		$this->db->insert_batch('ms_diskon_part_detail',$detail);
		if ($this->db->trans_status() === FALSE){
    	$this->db->trans_rollback();
      $_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_part_tertentu'>";
    }else{
      $this->db->trans_commit();
      $this->item_part->destroy();
      $_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_part_tertentu'>";
    }
	}	
	


	public function hapus_detail()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->post('id');				
		$this->m_admin->delete("ms_diskon_part_detail","id_diskon_part_detail",$id);
		echo "ok";
	}
	
	public function edit()
	{				
		$id_diskon_part = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;				
		$data['dt_diskon_part'] = $this->db->query("SELECT * FROM ms_diskon_part LEFT JOIN ms_karyawan_dealer ON ms_diskon_part.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE ms_diskon_part.id_diskon_part = '$id_diskon_part'");														
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

		$id					= $this->input->post("id_diskon_part");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
						
			$data['periode_awal']			= $this->input->post('periode_awal');
			$data['periode_akhir']		= $this->input->post('periode_akhir');
				
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_part'>";
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