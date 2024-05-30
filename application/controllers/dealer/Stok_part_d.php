<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_part_d extends CI_Controller {

	var $tables = "tr_stok_part_dealer";	
	var $folder = "dealer";
	var $page   = "stok_part_d";
	var $title  = "Stok Part";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
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
		$data['set']	= "index";	
		$this->template($data);	
	}

	public function fetch()
   	{
		$fetch_data = $this->make_query();  
		$data = array();  
		foreach($fetch_data->result() as $rs)  
		{  
			$sub_array        = array();
			$button           = '';
			// $status='';
			// if ($rs->status=='open') {
			// 	$status = "<label class='label label-warning'>Open</label>";
			// }
			// if ($rs->status=='ready_to_repair') {
			// 	$status = "<label class='label label-info'>Ready To Repair</label>";
			// }
			// if ($rs->status=='resolved') {
			// 	$status = "<label class='label label-success'>Resolved</label>";
			// }

			$sub_array[] = $rs->id_part;
			$sub_array[] = $rs->nama_part;
			$sub_array[] = $rs->qty;
			$promo = 0;
			
			// $sub_array[] = $button;
			$data[]      = $sub_array;  
		}  
		$output = array(  
          "draw"            =>     intval($_POST["draw"]),  
          "recordsFiltered" =>     $this->get_filtered_data(),  
          "data"            =>     $data  
		);  
		echo json_encode($output);  
   	}

   function make_query($no_limit=null)  
   	{  
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_part','nama_part','qty'); 
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_stok_part_dealer.id_part DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_stok_part_dealer.id_dealer=$id_dealer";
		
		if ($search!='') {
	      $searchs .= "AND (id_part LIKE '%$search%' 
	          OR nama_part LIKE '%$search%'
	          OR qty LIKE '%$search%'
	          )
	      ";
	  	}
     	
     	if(isset($_POST["order"]))  
		{	
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
     	}
     	
     	if ($no_limit=='y')$limit='';

   		return $this->db->query("SELECT * FROM tr_stok_part_dealer
   			JOIN ms_part ON tr_stok_part_dealer.id_part=ms_part.id_part
   		 $searchs $order $limit ");
   	}  
   	function get_filtered_data(){  
		return $this->make_query('y')->num_rows();  
   	} 
}