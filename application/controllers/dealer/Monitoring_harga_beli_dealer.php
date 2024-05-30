<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_harga_beli_dealer extends CI_Controller
{

	var $tables =   "tr_penerimaan_ksu_dealer";
	var $folder =   "dealer/laporan";
	var $page	=		"monitoring_harga_beli_dealer";
	var $title  =   "Monitoring Harga Beli Dealer";
	var $order_column = array('ms_kelompok_md.id_item', 'tipe_ahm', 'warna', 'start_date', 'ms_kelompok_md.harga_jual');

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
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false' or $sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "view";
		$id_dealer     = $this->m_admin->cari_dealer();
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_datatables();
		$data       = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$sub_array[] = $rs->id_item;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->start_date;
			$sub_array[] = mata_uang_rp($rs->harga_jual);
			$data[] = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}



	function make_query()
	{
		$id_dealer     = $this->m_admin->cari_dealer();
		$dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$this->db->select('ms_kelompok_md.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna');
		$this->db->from('ms_kelompok_md');
		$this->db->join('ms_item', 'ms_kelompok_md.id_item = ms_item.id_item', 'left');
		$this->db->join('ms_tipe_kendaraan', 'ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan', 'left');
		$this->db->join('ms_warna', 'ms_item.id_warna = ms_warna.id_warna', 'left');

		// if($_POST["start_date"] != '' && $_POST["end_date"] != ''){
		//      $start_date = $this->input->post('start_date');
		//      $end_date   = $this->input->post('end_date');
		//      // $this->db->where('tr_purchase_request.purchase_date>=',$start_date);
		//      // $this->db->where('tr_purchase_request.purchase_date<=',$end_date);
		//      $searchs ='';

		//      if($this->input->post('search')['value'] !='')
		//      {   
		//          $search = $this->input->post('search')['value'];
		//          if ($search!='') {
		//              $searchs = "AND (sq_number LIKE '%$search%' 
		//                  OR ms_customer.name_cust LIKE '%$search%'
		//                  OR sr.tanggal LIKE '%$search%'
		//              )";
		//          }
		//          // $this->db->or_like("sq_number", $_POST["search"]["value"]);  
		//          // $this->db->or_like("name_cust", $_POST["search"]["value"]);   
		//       }

		//      $this->db->where("(sr.tanggal BETWEEN '$start_date' AND '$end_date') $searchs", NULL, false);
		//  }
		$this->db->where("ms_kelompok_md.id_kelompok_harga='$dealer->id_kelompok_harga' ");

		$search = $this->input->post('search')['value'];
		if ($search != '') {
			$searchs = "(ms_kelompok_md.id_item LIKE '%$search%' 
	          OR tipe_ahm LIKE '%$search%'
	          OR warna LIKE '%$search%'
	          OR start_date LIKE '%$search%'
	          OR harga_jual LIKE '%$search%'
	      )";
			$this->db->where("$searchs", NULL, false);
		}
		if (isset($_POST["order"])) {
			$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
			$this->db->order_by('start_date', 'DESC');
		}
	}
	function make_datatables()
	{
		$this->make_query();
		if ($_POST["length"] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_filtered_data()
	{
		$this->make_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function download_xls()
	{
		$this->make_query();
		$data['details'] = $this->db->get()->result();
		$data['title'] = $this->title;
		$this->load->view('dealer/laporan/monitoring_harga_beli_dealer_download', $data);
	}
}
