<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Juklak_ahm extends CI_Controller {
    var $tables =   "ms_juklak_ahm";	
    var $folder =   "h1";
    var $page	=   "juklak_ahm";
    var $pk     =   "id_juklak";
    var $title  =   "Master Juklak AHM";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_juklak_ahm');		
		//===== Load Library =====
		$this->load->library('upload');
		//---- cek session -------//		
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
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$this->template($data);	
	}
	
	public function history()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['get_data'] = $this->m_admin->kondisi($tabel,$d)->row();	
		$juklakNo= $data['get_data']->juklakNo;
		$date = $data['get_data']->modifiedDate;
		$data['get_type'] = $this->db->query("select * from ms_juklak_ahm_type where juklakNo='$juklakNo' and isDelete=0");
		$data['get_target'] = $this->db->query("select * from ms_juklak_ahm_target where juklakNo='$juklakNo' and isDelete=0");
		$data['get_attachment'] = $this->db->query("select * from ms_juklak_ahm_file where juklakNo='$juklakNo' and isDelete=0");

		// get data detail lainnya	
		$get = $this->db->query("select json_ahm from ms_juklak_ahm_log where juklakNo='$juklakNo' and modifiedDate > '$date' order by createdDate desc limit 1")->row();

		if(count($get) > 0){
			$data['get_juklak_baru'] = json_decode($get->json_ahm);
		}else{
			$data['get_juklak_baru'] = false;
		}
	
		$data['isi']    = $this->page;		
		$data['title']	= "Perbandingan Juklak AHM";		
		$data['set']	= "view_log";					
		$this->template($data);	
	}

	public function update_status(){
		$juklakNo = $this->input->post('id');
		$modifiedDate = $this->input->post('date');
		$msg = '';

		$get_data = $this->db->query("select juklakNo, modifiedDate, json_ahm from ms_juklak_ahm_log where juklakNo = '$juklakNo' and modifiedDate > '$modifiedDate' order by modifiedDate desc limit 1")->row();

		$get_type = $this->db->query("select * from ms_juklak_ahm_type where juklakNo='$juklakNo' and isDelete=0");
		$get_target = $this->db->query("select * from ms_juklak_ahm_target where juklakNo='$juklakNo' and isDelete=0");
		$get_attachment = $this->db->query("select * from ms_juklak_ahm_file where juklakNo='$juklakNo' and isDelete=0");

		if(count($get_data)>0){
			$json = json_decode($get_data->json_ahm);
			// mesti pasang trans begin?
			// tambahi view perbandingan utk start dan end date tipe

			$this->db->query("Update ms_juklak_ahm_type set isDelete = 1 where juklakNo='$juklakNo' and isDelete = 0");
			$this->db->query("Update ms_juklak_ahm_target set isDelete = 1 where juklakNo='$juklakNo' and isDelete = 0");
			$this->db->query("Update ms_juklak_ahm_file set isDelete = 1 where juklakNo='$juklakNo' and isDelete = 0");

			foreach($json[array_search($juklakNo,$json)]->unit as $row){
				// print_r($row);
				$data_insert = array();
				$data_insert['juklakNo'] = $juklakNo;
				$data_insert['startPeriod'] = $row->startPeriod;
				$data_insert['endPeriod'] = $row->endPeriod;
				$data_insert['type'] =$row->type;
				$data_insert['typeDesc'] = $row->typeDesc;
				$data_insert['ahmContribution'] = $row->ahmContribution;
				$data_insert['mdContribution'] = $row->mdContribution;
				$data_insert['dContribution'] = $row->dContribution;
				$data_insert['isDelete'] = 0;
				$this->m_admin->insert('ms_juklak_ahm_type',$data_insert);	
			} 

			foreach($json[array_search($juklakNo,$json)]->targets as $row){
				// print_r($row);
				$data_insert = array();
				$data_insert['juklakNo'] = $juklakNo;
				$data_insert['year'] = $row->year;
				$data_insert['month'] = $row->month;
				$data_insert['target'] = $row->target;
				$data_insert['isDelete'] = 0;
				$this->m_admin->insert('ms_juklak_ahm_target',$data_insert);	
			}	

			foreach($json[array_search($juklakNo,$json)]->attachments as $row){
				// print_r($row);
				$data_insert = array();
				$data_insert['juklakNo'] = $juklakNo;
				$data_insert['version'] = $row->version;
				$data_insert['fileName'] = $row->fileName;
				$data_insert['fileExtension'] = explode('.',$row->fileName)[1];
				$data_insert['file'] = $row->file;
				$data_insert['uploadDate'] = date('Y-m-d');
				$data_insert['fileType'] = 'document';
				$data_insert['isDelete'] = 0;
				// print_r($data_insert);
				$this->m_admin->insert('ms_juklak_ahm_file',$data_insert);	
			}

			/* update data sesuai dengan ketentuan:
				1. penambahan tipe motor
				2. update informasi syarat kelengkapan dokumen
				3. perubahaan periode program

				status 1 = program aktif, status 2 = ada revisi juklak sehingga perlu di update ke menu master program md
			*/

			// update status juklak menjadi 2 = ada revisi juklak sehingga perlu di update ke menu master program md
			$modifiedDate = $json[array_search($juklakNo,$json)]->modifiedDate;
			$startPeriod = $json[array_search($juklakNo,$json)]->startPeriod;
			$endPeriod= $json[array_search($juklakNo,$json)]->endPeriod;
			$this->db->query("Update ms_juklak_ahm set statusJuklak = 2, createdDate = '$modifiedDate', modifiedDate = '$modifiedDate', startPeriod = '$startPeriod', endPeriod= '$endPeriod' where juklakNo='$juklakNo'");
			$msg = 'Update data juklak berhasil.';
		}else{
			$msg = 'Tidak ada proses update data.';

			$get_aktif = $this->db->query("select count(1) as total from ms_juklak_ahm where juklakNo = '$juklakNo' and statusJuklak = 0 limit 1")->row()->total;
			if($get_aktif > 0){
				// update status juklak menjadi 1 = program aktif
				$this->db->query("Update ms_juklak_ahm set statusJuklak = 1 where juklakNo='$juklakNo'");
				$msg = 'Berhasil dan juklak siap diproses.';
			}
		}

		// redirect ke halaman utama jika berhasil di update
		$data['msg'] = $msg;
		echo json_encode($data);
	}
	
	public function ajax_list()
	{
		$list = $this->m_juklak_ahm->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$id_menu = $this->m_admin->getMenu($this->page);
			$group 	= $this->session->userdata("group");
			$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
			// $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            

			$unik='Tidak';
			if($isi->uniqueCustomer==1){
				$unik='Ya';
			}

			$button ='';
			$get_log = $this->db->query("select juklakNo, modifiedDate from ms_juklak_ahm_log where juklakNo = '$isi->juklakNo' and modifiedDate > '$isi->modifiedDate' order by modifiedDate desc limit 1")->row();
			$get_aktif = $this->db->query("select count(1) as total from tr_sales_program where id_program_ahm = '$isi->juklakNo' limit 1")->row()->total;

			if(count($get_log) >0 && $get_aktif > 0){
				$button = "<i class=\"fa fa-check\" data-toggle=\"tooltip\" title=\"Program Aktif\"></i> <i class=\"fa fa-exclamation-triangle\" data-toggle=\"tooltip\" title=\"Ada Juklak Baru\"></i>";
			}else if(count($get_log) > 0 && $get_aktif == 0){
				$button = "<i class=\"fa fa-exclamation-triangle\" data-toggle=\"tooltip\" title=\"Ada Juklak Baru\"></i>";
			}else if((count($get_log) == 0 && $get_aktif >0) || $isi->statusJuklak == 1){
				$button = "<i class=\"fa fa-check\" data-toggle=\"tooltip\" title=\"Program Aktif\"></i>";
			}

			if(count($get_log)> 0){
				$tgl = $get_log->modifiedDate;
			}else{
				$tgl = '';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->juklakNo;
			$row[] = $isi->descJuklak;
			$row[] = $isi->segment;
			$row[] = $isi->programCategory;
			$row[] = $isi->subProgram;
			$row[] = $isi->startPeriod;
			$row[] = $isi->endPeriod;
			$row[] = $unik;
			$row[] = $isi->quota;
			$row[] = $isi->modifiedDate;
			$row[] = $tgl;
			$row[] = $button;
			$row[] = "<a data-toggle=\"tooltip\" $edit title=\"View Data\" class=\"btn btn-info btn-sm btn-flat\" href=\"h1/juklak_ahm/history?id=$isi->id_juklak\"><i class=\"fa fa-eye\"></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_juklak_ahm->count_all(),
			"recordsFiltered" => $this->m_juklak_ahm->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function download_file(){
		$name = $this->input->get('name');
		$juklakNo = $this->input->get('juklakNo');
		$version = $this->input->get('ver');
		$log = $this->input->get('islog');
		
		if($log!=1){
			$get_data = $this->db->query("select file, fileName, fileExtension from ms_juklak_ahm_file where juklakNo='$juklakNo' and version = '$version' and fileName = '$name' and isDelete=0")->row();

			if(count($get_data) > 0){
				$b64 = $get_data->file;
				$ext = $get_data->fileExtension;
				$filename = $get_data->fileName;
			}
		}else{
			$get_data = $this->db->query("select json_ahm from ms_juklak_ahm_log where juklakNo='$juklakNo' order by createdDate desc limit 1")->row();

			if(count($get_data) > 0){
				$json = json_decode($get_data->json_ahm);
				$filename = $name;
				
				foreach($json[array_search($juklakNo,$json)]->attachments as $att){
					if($att->fileName == $filename){
						$ext = explode(".",$filename)[1];
						$b64 = $att->file;
					}
				} 
			}else{
				echo 'File tidak ditemukan atau corrupt. Silahkan hubungi tim IT.';die;
			}
		}

		$data['b64'] = $b64;
		$data['ext'] = $ext;
		$data['filename'] = $filename;
		$this->load->view('h1/t_download_juklak', $data);
	}


	/*
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['set']		= "insert";									
		$this->template($data);	
	}

	public function save()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		// $cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();

		if(1){
			$data['perihal'] 	= $this->input->post('perihal');		
			$data['untuk'] 	= $this->input->post('untuk');		
			$data['isi'] 			= $this->input->post('isi');				
			$data['tgl_aktif'] 			= $this->input->post('start_date');				
			$data['tgl_expired']		= $this->input->post('end_date');
			$active = 1;
			if($this->input->post('active') == null) { 
				$active = 0;
			}			
			$data['active']		= $active;		
			$data['created_at']		= date('Y-m-d H:i:s');		
			$data['created_by']		= $this->session->userdata('id_user');
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/juklak_ahm'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function update()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 1){
			$data['perihal'] 	= $this->input->post('perihal');		
			$data['untuk'] 	= $this->input->post('untuk');		
			$data['isi'] 			= $this->input->post('isi');				
			$data['tgl_aktif'] 			= $this->input->post('start_date');				
			$data['tgl_expired']		= $this->input->post('end_date');
			$active = 1;
			if($this->input->post('active') == null) { 
				$active = 0;
			}			
			$data['active']		= $active;		
			$data['updated_at']		= date('Y-m-d H:i:s');		
			$data['updated_by']		= $this->session->userdata('id_user');
			
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/juklak_ahm'>";
		}else{
			$_SESSION['pesan'] 	= "Something Wrong!";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	*/
}