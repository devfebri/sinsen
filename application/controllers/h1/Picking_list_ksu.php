<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Picking_list_ksu extends CI_Controller {

    var $tables =   "tr_surat_jalan_ksu_pl";	
		var $folder =   "h1";
		var $page		=		"picking_list_ksu";
    var $pk     =   "no_pl_ksu";
    var $title  =   "Picking List KSU";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_picking_list_ksu_datatables');	
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
		/*$data['dt_ksu'] = $this->db->query("SELECT * FROM tr_surat_jalan_ksu_pl 
				INNER JOIN tr_picking_list ON tr_surat_jalan_ksu_pl.no_do = tr_picking_list.no_do
				INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
				INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer ORDER BY tr_surat_jalan_ksu_pl.created_at DESC limit 100");*/
		$this->template($data);			
	}	

	public function fetch_data_picking_list_ksu_datatables()
	{
		$list = $this->m_picking_list_ksu_datatables->get_datatables();

		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       

			  if (!empty($row->no_pl_ksu)) {
				  $link_pl_ksu   ="<a href='h1/picking_list_ksu/detail?id=$row->no_pl_ksu'>$row->no_pl_ksu</a>";
			  	  } else{
					$link_pl_ksu="closed";
			  	  } 

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $link_pl_ksu;
			$rows[] = $row->tgl_pl_ksu;
			$rows[] = $row->no_picking_list;
			$rows[] = $row->tgl_pl;
			$rows[] = $row->no_do;
			$rows[] = $row->nama_dealer;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_picking_list_ksu_datatables->count_all(),
			"recordsFiltered" => $this->m_picking_list_ksu_datatables->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "ksu";				
		$id = $this->input->get("id");
		$data['id'] 		= $this->input->get("id");
		$data['dt_sj'] 	= $this->db->query("SELECT * FROM tr_surat_jalan_ksu_pl 
						INNER JOIN tr_surat_jalan_ksu ON tr_surat_jalan_ksu_pl.no_surat_jalan=tr_surat_jalan_ksu.no_surat_jalan
						INNER JOIN tr_picking_list ON tr_surat_jalan_ksu_pl.no_do = tr_picking_list.no_do
						INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
						INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
						INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu=ms_ksu.id_ksu						
						WHERE tr_surat_jalan_ksu_pl.no_pl_ksu = '$id'");				
		$this->template($data);			
	}			
}