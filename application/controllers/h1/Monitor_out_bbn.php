<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor_out_bbn extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"monitor_out_bbn";
		var $isi		=		"invoice_keluar";
    var $pk     =   "no_do";
    var $title  =   "Monitor Outstanding Piutang BBN";

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
		$data['set']		= "serverside";				
		// $data['dt_rekap'] = $this->m_admin->getAll("tr_monout_piutang_bbn");
		// $data['dt_rekap'] = $this->db->query("SELECT tr_monout_piutang_bbn.*,(SELECT nama_dealer FROM ms_dealer WHERE id_dealer=tr_faktur_stnk.id_dealer) as dealer FROM tr_monout_piutang_bbn 
		// 	INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
		// 	JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
		// 	WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved')
		// 	AND tr_faktur_stnk.status_faktur='approved'
		// 	");
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

		
	public function fetch()
	{
		$fetch_data = $this->make_query();

		$data       = array();
		$id_menu    = $this->m_admin->getMenu($this->page);
		$group      = $this->session->userdata("group");
		$edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
		
		$no = 1;
		foreach ($fetch_data->result() as $rs) {
			
			$link = "<a href='h1/monitor_out_bbn/view?id={$rs->no_rekap}'>{$rs->no_rekap}</a>";

			$sub_array   = array();
			$sub_array[] = $no++;
			$sub_array[] = $link;
			$sub_array[] = $rs->tgl_rekap;
			$sub_array[] = $rs->dealer;
			$sub_array[] = $rs->no_bastd;
			$sub_array[] = $rs->total;
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
		$where .= " AND (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved')
		AND tr_faktur_stnk.status_faktur='approved'";


		if ($search != '') {
			$where .= " AND (tr_monout_piutang_bbn.no_rekap LIKE '%$search%'
					-- OR tr_monout_piutang_bbn.dealer LIKE '%$search%'
					OR tr_monout_piutang_bbn.no_bastd LIKE '%$search%'
					OR tr_monout_piutang_bbn.total LIKE '%$search%'
				) 
			";

			// $where_in = " AND tr_monout_piutang_bbn.dealer LIKE '%$search%'";

		}

		$order_column = array('tr_monout_piutang_bbn.tgl_rekap', 'tr_monout_piutang_bbn.dealer', 'tr_monout_piutang_bbn.no_bastd', 'tr_monout_piutang_bbn.total', null);
		$set_order = "ORDER BY tr_monout_piutang_bbn.tgl_rekap DESC";

		if (isset($_POST['order'])) {
			$order = $_POST['order'];
			$order_clm  = $order_column[$order['0']['column']];
			$order_by   = $order['0']['dir'];
			$set_order = " ORDER BY $order_clm $order_by ";
		}

	 	 $q =$this->db->query("SELECT tr_monout_piutang_bbn.*,(SELECT nama_dealer FROM ms_dealer WHERE id_dealer=tr_faktur_stnk.id_dealer ) as dealer FROM tr_monout_piutang_bbn 
		INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
		JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
		$where 
		-- $set_order 
		$limit
		");

	

	return  $q ;

	}

	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
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
		$id_rekap = $this->input->get('id');
		$data['dt_rekap'] = $this->db->query("SELECT * FROM tr_monout_piutang_bbn INNER JOIN tr_faktur_stnk ON tr_monout_piutang_bbn.no_bastd = tr_faktur_stnk.no_bastd								
				INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
				WHERE tr_monout_piutang_bbn.no_rekap = '$id_rekap'");					
		$this->template($data);			
	}	
}