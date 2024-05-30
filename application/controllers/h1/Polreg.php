<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Polreg extends CI_Controller {
    var $tables =   "tr_polreg";	
		var $folder =   "h1";
		var $page		=		"polreg";
    var $pk     =   "id_polreg";
    var $title  =   "Polreg";
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
		$data['dt_polreg'] = $this->db->query("SELECT * FROM tr_polreg INNER JOIN ms_kategori ON tr_polreg.id_kategori=ms_kategori.id_kategori
							INNER JOIN ms_segment ON tr_polreg.id_segment = ms_segment.id_segment");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function t_polreg(){
		$data['id_segment'] 	= $this->input->post('id_segment');
		$data['id_kategori'] 	= $this->input->post('id_kategori');		
		$this->load->view('h1/t_polreg',$data);
	}
	public function save()
	{		
		$waktu                      = gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id                   = $this->session->userdata('id_user');		
		$tabel                      = $this->tables;		
		$id_polreg                  = $this->m_admin->cari_id("tr_polreg","id_polreg");		
		$data['id_polreg']          = $id_polreg;
		$data['bulan']              = $this->input->post('bulan');		
		$data['id_kategori']        = $this->input->post('id_kategori');		
		$data['id_segment']         = $this->input->post('id_segment');		
		$data['active']             = "1";
		$data['created_at']         = $waktu;		
		$data['created_by']         = $login_id;
		// $data2['id_polreg']         = $id_polreg;
		
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');		
		$qty_honda         = $this->input->post('qty_honda');		
		$tipe_yamaha       = $this->input->post('tipe_yamaha');
		$qty_yamaha        = $this->input->post('qty_yamaha');
		$tipe_suzuki       = $this->input->post('tipe_suzuki');
		$qty_suzuki        = $this->input->post('qty_suzuki');
		$tipe_kawasaki     = $this->input->post('tipe_kawasaki');
		$qty_kawasaki      = $this->input->post('qty_kawasaki');		
		
		foreach ($id_tipe_kendaraan as $key => $val_tipe) {
			$data2[] = ['id_tipe_kendaraan'=>$id_tipe_kendaraan[$key],
						'qty_honda'     => $qty_honda[$key],
						'tipe_yamaha'   => $tipe_yamaha[$key],
						'qty_yamaha'    => $qty_yamaha[$key],
						'tipe_suzuki'   => $tipe_suzuki[$key],
						'qty_suzuki'    => $qty_suzuki[$key],
						'tipe_kawasaki' => $tipe_kawasaki[$key],
						'qty_kawasaki'  => $qty_kawasaki[$key],
						'id_polreg'		=> $id_polreg
					   ];
		}
		$this->m_admin->insert($tabel,$data);
		$this->db->insert_batch("tr_polreg_detail",$data2);
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/polreg/add'>";		
	}
	public function cetak_tenda_terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "cetak_terima";				
		$this->template($data);			
	}
	public function cari_tipe(){		
		$id_tipe_kendaraan 	= $this->input->post('id_tipe_kendaraan');		
		$cek 								= $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");						
		if($cek->num_rows()>0){
			$io = $cek->row();
			$tipe = $io->tipe_ahm;
		}		
		echo $tipe;
	}	
	public function view()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$id = $this->input->get('id');
		$data['dt_polreg'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$data['dt_polreg2'] = $this->m_admin->getByID("tr_polreg_detail",$this->pk,$id);
		$this->template($data);			
	}
	public function edit()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "edit";				
		$id = $this->input->get('id');
		$data['dt_polreg'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$data['dt_polreg2'] = $this->m_admin->getByID("tr_polreg_detail",$this->pk,$id);
		$this->template($data);			
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		$id_polreg 									= $this->input->post("id");		
		$data['id_polreg'] 					= $id_polreg;
		$data['bulan'] 							= $this->input->post('bulan');		
		$data['id_kategori'] 				= $this->input->post('id_kategori');		
		$data['id_segment'] 				= $this->input->post('id_segment');				
		$data['updated_at']					= $waktu;		
		$data['updated_by']					= $login_id;
		// $data2['id_polreg'] 				= $id_polreg;
		// $data2['id_tipe_kendaraan'] = $this->input->post('id_tipe_kendaraan');		
		// $data2['qty_honda'] 				= $this->input->post('qty_honda');		
		// $data2['tipe_yamaha'] 			= $this->input->post('tipe_yamaha');
		// $data2['qty_yamaha'] 				= $this->input->post('qty_yamaha');
		// $data2['tipe_suzuki'] 			= $this->input->post('tipe_suzuki');
		// $data2['qty_suzuki'] 				= $this->input->post('qty_suzuki');
		// $data2['tipe_kawasaki'] 		= $this->input->post('tipe_kawasaki');
		// $data2['qty_kawasaki'] 			= $this->input->post('qty_kawasaki');		


		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');		
		$qty_honda         = $this->input->post('qty_honda');		
		$tipe_yamaha       = $this->input->post('tipe_yamaha');
		$qty_yamaha        = $this->input->post('qty_yamaha');
		$tipe_suzuki       = $this->input->post('tipe_suzuki');
		$qty_suzuki        = $this->input->post('qty_suzuki');
		$tipe_kawasaki     = $this->input->post('tipe_kawasaki');
		$qty_kawasaki      = $this->input->post('qty_kawasaki');		
		
		foreach ($id_tipe_kendaraan as $key => $val_tipe) {
			$data2[] = ['id_tipe_kendaraan'=>$id_tipe_kendaraan[$key],
						'qty_honda'     => $qty_honda[$key],
						'tipe_yamaha'   => $tipe_yamaha[$key],
						'qty_yamaha'    => $qty_yamaha[$key],
						'tipe_suzuki'   => $tipe_suzuki[$key],
						'qty_suzuki'    => $qty_suzuki[$key],
						'tipe_kawasaki' => $tipe_kawasaki[$key],
						'qty_kawasaki'  => $qty_kawasaki[$key],
						'id_polreg'		=> $id_polreg
					   ];
		}
		$this->m_admin->update($tabel,$data,$this->pk,$id_polreg);
		// $this->m_admin->update("tr_polreg_detail",$data2,$this->pk,$id_polreg);
		$this->db->delete('tr_polreg_detail',['id_polreg'=>$id_polreg]);
		$this->db->insert_batch("tr_polreg_detail",$data2);
		$_SESSION['pesan'] 		= "Data has been updated successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/polreg'>";		
	}
}