<?php
defined('BASEPATH') or exit('No direct script access allowed');

class List_of_purchase_history extends CI_Controller
{

	var $tables = "tr_reminder_follow_up";
	var $folder = "dealer/laporan";
	var $page   = "list_of_purchase_history";
	var $title  = "List Of Purchase History";

	public function __construct()
	{
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
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
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index";
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array        = array();
			$button           = '';
			$sub_array[] = $rs->id_sales_order;
			$sub_array[] = $rs->tgl_pengiriman;
			$sub_array[] = $rs->nama_konsumen;
			$sub_array[] = $rs->no_hp;
			$sub_array[] = $rs->desc_unit;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->sales;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_pesan', 'tipe_pesan', 'konten', 'start_date', 'end_date', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_sales_order.created_at DESC';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "WHERE tr_sales_order.id_dealer=$id_dealer";

		if ($search != '') {
			$searchs .= "AND (tgl_pengiriman LIKE '%$search%' 
	          OR tr_sales_order.created_at LIKE '%$search%'
	          OR tr_sales_order.id_sales_order LIKE '%$search%'
	          OR nama_konsumen LIKE '%$search%'
	          OR id_customer LIKE '%$search%'
	          OR no_hp LIKE '%$search%'
	          OR id_tipe_kendaraan LIKE '%$search%'
	          OR id_warna LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("SELECT *,
			(SELECT CONCAT(id_tipe_kendaraan,' | ',tipe_ahm) FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan) AS desc_unit,
			(SELECT CONCAT(id_warna,' | ',warna) FROM ms_warna WHERE id_warna=tr_spk.id_warna) AS warna,
			(SELECT CONCAT(tr_prospek.id_flp_md,' | ',nama_lengkap) FROM tr_prospek 
				JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
				WHERE id_customer=tr_spk.id_customer ORDER BY tr_prospek.created_at DESC LIMIT 1) AS sales
   			FROM tr_sales_order
   			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
   		 $searchs $order $limit ");
	}
	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}

	function download_xls()
	{
		$data['details'] = $this->make_query('y')->result();
		$data['title'] = $this->title;
		$this->load->view('dealer/laporan/list_of_purchase_history_download', $data);
	}
}
