<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_bg extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitoring_bg";
		var $isi		=		"bank_kas";
    var $pk     =   "no_do";
    var $title  =   "Monitoring BG Beredar";

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
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['dt_mon'] = $this->m_admin->getAll("tr_voucher_bank");
		$data['set']		= "view";				
		$this->template($data);			
	}

	public function serverside()
	{				
		$data['isi']    = $this->isi;															
		$data['title']	= $this->title;															
		$data['page']   = $this->page;		
		$data['set']		= "serverside";				
		$this->template($data);			
	}


	public function add()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}


	public function view()
	{				
		$data['isi']    = $this->isi;		
		$data['page']   = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";				
		$this->template($data);			
	}


		
	public function fetch()
	{


		$fetch_data = $this->make_query();

		$data       = array();
		$id_menu    = $this->m_admin->getMenu($this->page);
		$group      = $this->session->userdata("group");
		$edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
		
		$no = 1;
		foreach ($fetch_data->result() as $rs) {

			  
            if ($rs->tipe_customer=='Dealer') {
				$customer = $this->db->get_where('ms_dealer',['id_dealer'=>$rs->dibayar]);
				$customer = $customer->num_rows()>0?$customer->row()->nama_dealer:'';
  
			  }     
			  
			  
			  if ($rs->tipe_customer=='Vendor') {
				$customer = $this->db->get_where('ms_vendor',['id_vendor'=>$rs->dibayar]);
				$customer = $customer->num_rows()>0?$customer->row()->vendor_name:'';
			  }  

			  $temp =array();
			  $dt_bg = $this->db->query("SELECT * FROM tr_voucher_bank_bg WHERE id_voucher_bank = '$rs->id_voucher_bank'");
              foreach($dt_bg->result() as $isi) {
				$temp[] = "$isi->no_bg | $isi->nominal_bg <br>";
              }

			$sub_array   = array();
			$sub_array[] = $no++;
			$sub_array[] = $rs->id_voucher_bank;
			$sub_array[] = $rs->tgl_entry;
			$sub_array[] =  $temp;
			$sub_array[] = $customer;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data(),
			"data"            => $data
		);
		echo json_encode($output);
	}

	public function make_query($no_limit = null)
	{
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";
		if ($no_limit == 'y') $limit = '';

		$search = $this->input->post('search')['value'];
		$where = "WHERE 1=1 ";

		if ($search != '') {
			$where .= " AND (tr_voucher_bank.account LIKE '%$search%'
					OR tr_voucher_bank.tgl_entry LIKE '%$search%'
					OR tr_voucher_bank.deskripsi LIKE '%$search%'
				) 
			";
		}

		$order_column = array('tr_voucher_bank.account', 'tr_voucher_bank.tgl_entry','tr_voucher_bank.deskripsi', 'tr_voucher_bank.total', null);
		$set_order = "ORDER BY tr_voucher_bank.id_voucher_bank DESC";

		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

		return $this->db->query("SELECT * FROM tr_voucher_bank
		$where 
		$set_order 
		$limit
		");

	}

	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}





}