<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plafon extends CI_Controller {

    var $tables =   "ms_plafon";	
		var $folder =   "h1";
		var $page		=		"plafon";
    var $pk     =   "id_plafon";
    var $title  =   "Pengaturan Plafon";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_plafon_datatables');		
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
		// $data['dt_plafon'] = $this->db->query("SELECT ms_plafon.*,ms_dealer.nama_dealer,ms_dealer.kode_dealer_md FROM ms_plafon INNER JOIN ms_dealer 
		//																	ON ms_plafon.id_dealer=ms_dealer.id_dealer ORDER BY ms_plafon.id_plafon DESC");							
		$this->template($data);	
	}
	public function fetch_data_plafon_datatables()
	{
		$list = $this->m_plafon_datatables->get_datatables();
		
		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {  
			
			if($row->status=='input'){
				$op = $row->op1;
				$plafon = $row->plafon;
				$status = "<span class='label label-danger'>$row->status</span>";
				$rd =  "<a data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/plafon/delete?id=$row->id_plafon\"><i class=\"fa fa-trash-o\"></i></a>
						<a data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/plafon/edit?id=$row->id_plafon\"><i class=\"fa fa-edit\"></i></a>";
				$rs =  "<a data-toggle=\"tooltip\" title=\"Appove Data\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/plafon/approve?id=$row->id_plafon\"><i class=\"fa fa-check\"></i></a>";              
			  }elseif($row->status=='waiting 2' OR $row->status=='waiting 1' ){
				$op = $row->op2;
				$plafon = $row->plafon1;
				$status = "<span class='label label-warning'>$row->status</span>";
				$rd = "";
				$rs = "<a data-toggle=\"tooltip\" title=\"Appove Data\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/plafon/approve?id=$row->id_plafon\"><i class=\"fa fa-check\"></i></a>";
			  }elseif($row->status=='approved'){
				$op = $row->op3;
				$plafon = $row->plafon2;
				$status = "<span class='label label-success'>$row->status</span>";
				$rd = "";
				$rs = "";
			  } 

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->kode_dealer_md;
			$rows[] = $row->nama_dealer;
			$rows[] = "(".$op.")" . number_format($plafon,0) ;
			$rows[] = $status;
			$rows[] = $row->tgl;
			$rows[] =  $rd .  $rs;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_plafon_datatables->count_all(),
			"recordsFiltered" => $this->m_plafon_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";	
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$this->template($data);	
	}
	public function save()
	{		
		$waktu 		= gmdate("Y-m-d h:i:s", time()+60*60*7);
		$tgl 			= gmdate("Y-m-d", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel			= $this->tables;		
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$config['upload_path'] 		= './assets/panel/files/';
			$config['allowed_types'] 	= 'jpeg|png|jpg|bmp';
			$config['max_size']				= '1000';		
					
			$this->upload->initialize($config);
			if (isset($_POST['foto'])) {
				if(!$this->upload->do_upload('foto')){
				$foto = "";
				}else{
					$foto = $this->upload->file_name;
				}
			}else{
				$foto='';
			}

			$id_dealer 					= $this->input->post('id_dealer');				
			$data['id_dealer'] 	= $this->input->post('id_dealer');				
			$data['op1'] 				= $this->input->post('op');				
			$data['no_surat_pengajuan'] = $this->input->post('no_surat_pengajuan');				
			$data['keterangan']	= $this->input->post('keterangan');				
			$data['foto']				= $foto;				
			$data['plafon'] 		= $this->m_admin->ubah_rupiah($this->input->post('plafon'));					
			$data['id_user'] 		= $login_id;
			$data['status'] 		= "input";			
			$data['tgl']				= $tgl;		
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$cek = $this->db->query("SELECT * FROM ms_plafon WHERE id_dealer = '$id_dealer' AND status <> 'approved'");
			if($cek->num_rows() > 0){
				$_SESSION['pesan'] 	= "Masih ada proses pengajuan perubahan plafon dealer ini yg belum di-approve";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/plafon'>";
			}else{
				$this->m_admin->insert($tabel,$data);
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/plafon/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/plafon'>";
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
		$data['dt_plafon'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
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
			$this->upload->initialize($config);
			if($this->upload->do_upload('foto')){
				$data['foto']=$this->upload->file_name;
				
				$one = $this->m_admin->getByID($tabel,$pk,$id)->row();			
				unlink("assets/panel/files/".$one->file); //Hapus Gambar
			}

			$data['op1'] 				= $this->input->post('op');				
			$data['plafon'] 	= $this->m_admin->ubah_rupiah($this->input->post('plafon'));					
			$data['no_surat_pengajuan'] = $this->input->post('no_surat_pengajuan');				
			$data['keterangan']	= $this->input->post('keterangan');							
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/plafon'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function approve()
	{		
		$tabel		= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$dt_plafon = $this->m_admin->kondisi($tabel,$d)->row();
		$data['dt_plafon'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		if($dt_plafon->status == 'input'){
			$data['set']		= "approve1";									
		}elseif($dt_plafon->status == 'waiting 2'){
			$data['set']		= "approve2";									
		}
		$this->template($data);	
	}
	public function update_approve1()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');			
		$mode 		= "new";
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_dealer	= $this->input->post("id_dealer");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		$status     = $this->input->post("status");
		if($cek == 0 or $id == $id_){			
			if($status == 'waiting 1'){
				$data['op2'] 						= $this->input->post('op');				
				$data['plafon1'] 				= $this->m_admin->ubah_rupiah($this->input->post('plafon1'));					
				$data['status']					= "waiting 2";
				$data['id_user1']				= $login_id;		
			}elseif($status == 'waiting 2'){
				$plafon 								= $this->m_admin->ubah_rupiah($this->input->post('plafon2'));					
				$op 										= $this->input->post('op');				
				$data['op3'] 						= $this->input->post('op');				
				$data['plafon2'] 				= $plafon;					
				$data['status']					= "approved";
				$data['id_user2']				= $login_id;		

				$r = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();				
				if($op == '+'){
					$plafon_akhir = $plafon + $r->plafon;
					$plafon_maks 	= $plafon + $r->plafon_maks;
				}else{
					$plafon_akhir = $r->plafon - $plafon;
					$plafon_maks 	= $r->plafon_maks - $plafon;
				}				
				if($plafon_maks >= 0 AND $plafon_akhir >= 0){
					$da['plafon'] 					= $plafon_akhir;
					$da['plafon_maks'] 			= $plafon_maks;
					$this->m_admin->update("ms_dealer",$da,"id_dealer",$id_dealer);
				}else{
					$mode = "over";
				}				
			}
			$data['updated_at']			= $waktu;		
			$data['updated_by']			= $login_id;
			if($mode == 'over'){
				$_SESSION['pesan'] 	= "Plafon tidak boleh minus!";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/plafon'>";
			}else{
				$this->m_admin->update($tabel,$data,$pk,$id);
				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/plafon'>";
			}			
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}		
	}
}