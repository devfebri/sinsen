<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek_giro extends CI_Controller {

		var $tables 	=   "ms_cek_giro";	
		var $folder 	=   "master";
		var $page		=   "cek_giro";
		var $pk     	=   "id_cek_giro";
		var $title  	=   "Master Data No Cek & Giro";

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
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}
		$this->load->library('form_validation');

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
		$data['set']	= "views";
		$data['dt_cek_giro'] = $this->m_admin->getAll($this->tables);							
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";									
		$this->template($data);	
	}

	
	public function fetch()
	{
	  $fetch_data = $this->make_query();
	  $no = $_POST['start'];

	  $data = array();
	  foreach ($fetch_data as $rs) {
		$button = "<a data-toggle='tooltip' title='Edit Data' class='btn btn-primary btn-sm btn-flat' href='master/cek_giro/edit?id=".$rs->id_cek_giro."'><i class='fa fa-edit'></i></a>";
		$button.= "<a data-toggle='tooltip' title='Delete Data' onclick=\"return confirm('Are you sure to delete this data?')\" class='btn btn-danger btn-sm btn-flat' href='master/cek_giro/delete?id=".$rs->id_cek_giro."'><i class='fa fa-trash-o'></i></a>";
		$no++;
		$sub_array = array();
		$sub_array[] =  $no;
		$sub_array[] = $rs->tgl_buat;
		$sub_array[] = $rs->kode_giro;
		$sub_array[] = $rs->nama_bank;
		$sub_array[] = $button;
		$data[]      = $sub_array;
	  }

	  $count= $this->make_query(true);
	  $output = array(
		"draw"            => intval($_POST["draw"]),
		"recordsFiltered" => $count,
		"recordsTotal"    => $count,
		"data"            => $data
	  );
	  echo json_encode($output);
	}

	
	public function make_query($recordsFiltered = null)
	{
	  $start        = $this->input->post('start');
	  $length       = $this->input->post('length');
	  $limit        = "LIMIT $start, $length";
	  $where        = "WHERE 1=1 ";

	  if ($recordsFiltered == true) $limit = '';
  
	  $filter = [
		'limit'  => $limit,
		'order'  => isset($_POST['order']) ? $_POST['order'] : '',
		'search' => $this->input->post('search')['value'],
		'order_column' => 'view',
		'deleted' => false,
	  ];

	  if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND  (cg.tgl_buat LIKE'%{$filter['search']}%'
                            OR cg.kode_giro LIKE'%{$filter['search']}%'
                            OR bk.bank LIKE'%{$filter['search']}%'
                            OR cg.id_cek_giro LIKE'%{$filter['search']}%'
          )";
        }
      }
	  
	  if (isset($filter['order'])) {
		$order = $filter['order'];
		if ($order != '') {
		  if ($filter['order_column'] == 'view') {
			$order_column = ['cg.tgl_buat', 'cg.kode_giro', 'bk.bank'];
		  }
		  $order_clm  = $order_column[$order['0']['column']];
		  $order_by   = $order['0']['dir'];
		  $order = " ORDER BY $order_clm $order_by ";
		} else {
		  $order = " ORDER BY  cg.tgl_buat DESC  ";
		}
	  } else {
		$order = '';
	  }
	  $group = '';

	  if ($recordsFiltered == true) {
		return $this->db->query("SELECT cg.tgl_buat,cg.kode_giro, bk.bank as nama_bank, cg.kode_giro, cg.id_cek_giro from ms_cek_giro cg left join ms_bank bk on cg.bank = bk.id_bank $where $group $order $limit")->num_rows();
	  } else {
		return $this->db->query("SELECT cg.tgl_buat,cg.kode_giro, bk.bank as nama_bank, cg.kode_giro, cg.id_cek_giro from ms_cek_giro cg left join ms_bank bk on cg.bank = bk.id_bank $where $group $order $limit")->result();
	  }
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
			$data['tgl_buat'] 				= $this->input->post('tgl_buat');					
			$data['bank'] = $bank 		= $this->input->post('bank');					
			$cek_bank = $this->m_admin->getByID("ms_bank","id_bank",$bank)->row()->bank;
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";							
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$dari 						= $this->input->post('dari');					
			$sampai 					= $this->input->post('sampai');					
			if($cek_bank == 'Bank Permata' OR $cek_bank == 'Permata'){
				$data['kode_giro'] = $kode_giro 				= $this->input->post('kode_giro');					
				$cek = $this->m_admin->getByID("ms_cek_giro","kode_giro",$kode_giro);
				if($cek->num_rows() > 0){
					$_SESSION['pesan'] 	= "Kode Giro sudah pernah diinput";
					$_SESSION['tipe'] 	= "danger";
					echo "<script>history.go(-1)</script>";
				}else{
					$this->m_admin->insert($tabel,$data);				
					$_SESSION['pesan'] 	= "Data has been saved successfully";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro/add'>";								
				}
			}elseif($dari < $sampai) {
				for ($i=$dari; $i <= $sampai ; $i++) { 
					$data['kode_giro'] = $i;
					$this->m_admin->insert($tabel,$data);				
				}
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro/add'>";			
			}else{
				$_SESSION['pesan'] 	= "Kode Giro Awal harus lebih kecil dari Kode Giro akhir.";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro'>";
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
		$data['dt_cek_giro'] = $this->m_admin->kondisi($tabel,$d);
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
			$data['tgl_buat'] 				= $this->input->post('tgl_buat');					
			$data['kode_giro'] 				= $this->input->post('kode_giro');					
			$data['bank'] 						= $this->input->post('bank');								
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";							
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cek_bank(){
		$id = $this->input->post("id");
		$cek_bank = $this->m_admin->getByID("ms_bank","id_bank",$id)->row()->bank;
		echo $cek_bank;
	}
	public function t_giro(){
		$id = $this->input->post('id_cek_giro');
		$dq = "SELECT * FROM ms_cek_giro_detail WHERE id_cek_giro = '$id'";
		$data['dt_giro'] = $this->db->query($dq);
		$this->load->view('master/t_giro',$data);
	}
	public function delete_giro(){
		$id 		= $this->input->post('id_cek_giro_detail');		
		$da 		= "DELETE FROM ms_cek_giro_detail WHERE id_cek_giro_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_giro(){
		$id_cek_giro	= $this->input->post('id_cek_giro');
		$no_cek					= $this->input->post('no_cek');		
		$c 			= $this->db->query("SELECT * FROM ms_cek_giro_detail WHERE id_cek_giro ='$id_cek_giro' AND no_cek = '$no_cek'");
		$data['id_cek_giro']		= $this->input->post('id_cek_giro');			
		$data['no_cek']			= $this->input->post('no_cek');			
		if($c->num_rows()==0){
			$this->m_admin->insert('ms_cek_giro_detail',$data);							
		}else{
			$op = $c->row();
			$this->m_admin->update('ms_cek_giro_detail',$data,"id_cek_giro_detail",$op->id_cek_giro_detail);							
		}
		echo "nihil";
	}
}