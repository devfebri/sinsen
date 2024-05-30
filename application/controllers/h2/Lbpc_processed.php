<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lbpc_processed extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"lbpc_processed";
	var $title  =   "LBPC Processed";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_md_claim', 'm_claim');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('mpdf_l');


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
			$data['folder'] = $this->folder;
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
		$data['set']	= "view";
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query_fetch();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			// $btn_approval = "<a class='btn btn-success btn-xs btn-flat' href=\"" . base_url('h2/' . $this->page . '/approved?id=' . $rs->id_po_kpb) . "\">Approval</a>";

			// if ($rs->status == 'input') {
			// 	$status = '<label class="label label-primary">Input</label>';
			// 	// if (can_access($this->page, 'can_update'))  
			// 	$button .= $btn_approval;
			// } elseif ($rs->status == 'approved') {
			// 	$status = '<label class="label label-success">Approved</label>';
			// } elseif ($rs->status == 'rejected') {
			// 	$status = '<label class="label label-danger">Rejected</label>';
			// }

			$sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->no_lbpc . '">' . $rs->no_lbpc . '</a>';
			$sub_array[] = $rs->tgl_lbpc;
			$sub_array[] = $rs->kelompok_pengajuan;
			$sub_array[] = $rs->start_date;
			$sub_array[] = $rs->end_date;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_fetch(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_fetch($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'view',
			'search' => $this->input->post('search')['value']
		];

		if (isset($_POST['ptca_not_null'])) {
			$filter['ptca_not_null'] = $_POST['ptca_not_null'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getLBPC($filter)->num_rows();
		} else {
			return $this->m_claim->getLBPC($filter)->result();
		}
	}
}
