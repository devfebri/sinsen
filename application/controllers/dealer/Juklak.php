<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Juklak extends CI_Controller {
    // var $tables =   "ms_juklak_ahm";	
    var $folder =   "dealer";
    var $page	=   "juklak";
    var $pk     =   "id_juklak";
    var $title  =   "Master Juklak";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_juklak');		
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
	protected function template($data){
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

	public function index(){				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$this->template($data);	
	}
		
	public function ajax_list()
	{
		$list = $this->m_juklak->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$id_menu = $this->m_admin->getMenu($this->page);
			$group 	= $this->session->userdata("group");
			$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
			// $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            

			// $unik="<i class=\"fa fa-close\"></i>";
			$unik="-";
			if($isi->uniqueCustomer==1){
				$unik="<i class=\"fa fa-check\"></i>";
			}

			$kk = "-";
			if($isi->kk_validation==1){
				$unik="<i class=\"fa fa-check\"></i>";
			}
			
			$download ="<button class=\"btn btn-info btn-sm btn-flat disabled\"><i class=\"fa fa-download\"></i></button>";
			if($isi->draft_jutlak!=''){
				$download = "<a data-toggle=\"tooltip\" title=\"Download Juklak\" class=\"btn btn-info btn-sm btn-flat\" href=\"dealer/juklak/download_file?id_program_md=$isi->id_program_md\"><i class=\"fa fa-download\"></i></a>";
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = "<a data-toggle=\"tooltip\" $edit title=\"View Data\" href=\"dealer/juklak/detail?id=$isi->id_program_md\">".$isi->id_program_md."</a>";
			$row[] = $isi->judul_kegiatan;
			$row[] = $isi->segment;
			$row[] = $isi->kategori_program;
			$row[] = $isi->sub_kategori_program;
			$row[] = $isi->startPeriod;
			$row[] = $isi->endPeriod;
			$row[] = $isi->tanggal_maks_po;
			$row[] = $isi->tanggal_maks_bastk;
			$row[] = $isi->kuota_program;
			$row[] = $unik;
			$row[] = $kk;
			$row[] = date_format(date_create($isi->created_at),"Y-m-d");
			$row[] = date_format(date_create($isi->updated_at),"Y-m-d");
			if($isi->endPeriod>date('Y-m-d')){
				$row[] = "<i class=\"fa fa-check\" data-toggle=\"tooltip\" title=\"Program Aktif\"></i>";
			}else{
				$row[] = "";
			}
			$row[] = $download;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_juklak->count_all(),
			"recordsFiltered" => $this->m_juklak->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function detail_old(){		
		$id 			= $this->input->get('id');
		
		$data['get_data'] = $this->db->query("
			select (CASE when a.target_penjualan !='' then a.target_penjualan else 0 end) target_penjualan, a.id_program_ahm , b.programCategory, a.file_name, a.kategori_program, a.judul_kegiatan, b.juklakNo, a.segment, b.descJuklak , a.id_program_md, b.uniqueCustomer, a.judul_kegiatan , b.subProgram , a.periode_awal as startPeriod , a.periode_akhir as endPeriod, a.jenis, a.kuota_program , a.unique_customer, a.tanggal_maks_po, a.tanggal_maks_bastk, a.kategori_program , c.jenis_sales_program as sub_kategori_program, a.kk_validation, b.statusJuklak , a.created_at , a.updated_at
			from tr_sales_program a
			join ms_jenis_sales_program c on a.id_jenis_sales_program = c.id_jenis_sales_program
			left join ms_juklak_ahm b on a.id_program_ahm = b.juklakNo
			where id_program_md ='$id'")->row();	

		$juklakNo= $data['get_data']->juklakNo;
		
		$data['get_type'] = $this->db->query("select * from tr_sales_program_tipe where id_program_md='$id'");
		$data['get_syarat'] = $this->db->query("select syarat_ketentuan from tr_sales_program_syarat where id_program_md='$id' order by syarat_ketentuan asc");

		$data['isi']    = $this->page;		
		$data['title']	= "Detail Juklak";		
		$data['set']	= "view_detail";					
		$this->template($data);	
	}

	public function detail(){		
		$id 			= $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();

		$data['get_data'] = $this->db->query("
			select (CASE when a.target_penjualan !='' then a.target_penjualan else 0 end) target_penjualan, a.id_program_ahm , b.programCategory, a.file_name, a.kategori_program, a.judul_kegiatan, b.juklakNo, a.segment, b.descJuklak , a.id_program_md, b.uniqueCustomer, a.judul_kegiatan , b.subProgram , a.periode_awal as startPeriod , a.periode_akhir as endPeriod, a.jenis, (case when a.kuota_program = '*' then d.kuota else a.kuota_program end) as kuota_program , a.unique_customer, a.tanggal_maks_po, a.tanggal_maks_bastk , c.jenis_sales_program as sub_kategori_program, a.kk_validation, b.statusJuklak , a.created_at , a.updated_at
			from tr_sales_program a
			left join ms_juklak_ahm b on a.id_program_ahm = b.juklakNo
			join ms_jenis_sales_program c on a.id_jenis_sales_program = c.id_jenis_sales_program
			left join tr_sales_program_dealer d on a.id_program_md = d.id_program_md and d.id_dealer = '$id_dealer'
			where a.id_program_md ='$id'")->row();	

		$juklakNo= $data['get_data']->juklakNo;
		
		$data['get_type'] = $this->db->query("select * from tr_sales_program_tipe where id_program_md='$id'");
		$data['get_syarat'] = $this->db->query("select syarat_ketentuan from tr_sales_program_syarat where id_program_md='$id' order by syarat_ketentuan asc");

		$data['isi']    = $this->page;		
		$data['title']	= "Detail Juklak";		
		$data['set']	= "view_detail";					
		$this->template($data);	
	}

	public function download_file(){
		$id_program_md = $this->input->get('id_program_md');
		$get_data = $this->db->query("select draft_jutlak, file_name from tr_sales_program where id_program_md='$id_program_md'")->row();

		$filename='no_file';
		if(count($get_data) > 0){
			$b64 = $get_data->draft_jutlak;
			$ext = explode('.',$get_data->file_name)[1];
			$filename = $get_data->file_name;
		}

		$data['b64'] = $b64;
		$data['ext'] = $ext;
		$data['filename'] = $filename;
		$this->load->view('dealer/t_download_juklak', $data);
	}

	/*
	public function history(){		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['get_data'] = $this->m_admin->kondisi($tabel,$d)->row();	
		$juklakNo= $data['get_data']->juklakNo;
		$data['get_type'] = $this->db->query("select * from ms_juklak_ahm_type where juklakNo='$juklakNo'");
		$data['get_target'] = $this->db->query("select * from ms_juklak_ahm_target where juklakNo='$juklakNo'");
		$data['get_attachment'] = $this->db->query("select * from ms_juklak_ahm_file where juklakNo='$juklakNo'");

		// get data detail lainnya
	
		$data['isi']    = $this->page;		
		$data['title']	= "Detail Juklak Main Dealer";		
		$data['set']	= "view_log";					
		$this->template($data);	
	}

	public function add(){				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['set']		= "insert";									
		$this->template($data);	
	}

	public function save(){		
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