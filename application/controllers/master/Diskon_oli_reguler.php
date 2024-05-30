<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon_oli_reguler extends CI_Controller {

    var $tables =   "ms_diskon_oli";	
		var $folder =   "master";
		var $page		=		"diskon_oli_reguler";
    var $pk     =   "id_diskon_oli";
    var $title  =   "Master Diskon Oli Reguler";

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
		$this->item   = new Udp_cart("item");
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
		$data['dt_diskon_oli_reguler'] = $this->db->query("SELECT * FROM ms_diskon_oli LEFT JOIN ms_part ON ms_diskon_oli.id_part = ms_part.id_part");							
		//$data['dt_part']= $this->m_admin->getSortCond("ms_part","nama_part","ASC");
		$data['dt_part']= $this->m_admin->getAll("ms_part");
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;								
		$data['set']		= "insert";		
		//$data['dt_part']= $this->m_admin->getSortCond("ms_part","nama_part","ASC");
		$data['dt_part']= $this->m_admin->getAll("ms_part");
		$this->template($data);	
	}	
	public function t_detail(){
		$data['dt_part']= $this->m_admin->getAll("ms_part");
  	$this->load->view("master/t_diskon_detail",$data);
  }
  public function save_detail(){
  	$data['id'] = rand(1,9999);				
		$data['id_part'] = $id_part = $this->input->post('id_part');
		$data['nama_part'] = $this->input->post('nama_part');
		$data['tipe_diskon'] = $this->input->post('tipe_diskon');
		$data['qty'] = $this->input->post('range1');
		$data['price'] = $this->input->post('range2');
		$data['range3'] = $this->input->post('range3');		
		$no=1;
		if($item = $this->item->get_content()) {
			foreach ($item as $res){
				if($id_part == $res['id_part']){
					$no++;
				}				
			}
		}

		if($no==1){
			$this->item->insert($data);
			$data['set']  = 'item';			
			$this->load->view("master/t_diskon_detail",$data);			
		}else{
			echo "failed";
		}
  }
  public function delete_detail(){
  	$rowid=$this->input->post('id');
		if($this->item->remove_item($rowid)){
			$this->load->view("master/t_diskon_detail",$data);			
		}else{
			echo "failed";
		}
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
	public function save()
	{		
		$waktu     = gmdate("y-m-d H:i:s", time()+60*60*7);		
		$login_id  = $this->session->userdata('id_user');				
		if($item = $this->item->get_content())
		{
			foreach($item as $key => $val){				
				$detail[$key]['id_part']   = $val['id_part'];
				$detail[$key]['tipe_diskon']   = $val['tipe_diskon'];
				$detail[$key]['range_1']   = $val['qty'];				
				$detail[$key]['range_2']   = $val['price'];				
				$detail[$key]['range_3']   = $val['range3'];				
			}
		}else{
			$_SESSION['pesan'] 	= "Data item masih kosong";
			$_SESSION['tipe'] 	= "danger";
			redirect(site_url('master/diskon_oli_reguler'));
			echo "<script>history.go(-1)</script>";
		}

		$this->db->trans_begin();
		$this->db->insert_batch('ms_diskon_oli',$detail);
		if ($this->db->trans_status() === FALSE){
    	$this->db->trans_rollback();
      $_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
      echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_reguler'>";
    }else{
      $this->db->trans_commit();
      $this->item->destroy();
      $_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_reguler'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_reguler/edit'>";
		}
	}
	
	public function edit()
	{				
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;				
		$data['set']			= "edit";									
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