<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ptcd extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"ptcd";
	var $title  =   "PTCD (Perincian Tagihan Claim Main Dealer)";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_md_claim', 'm_claim');
		$this->load->model('m_h2');
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
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";
		$data['dt_result'] = $this->db->query("SELECT * FROM tr_ptcd ORDER BY created_at DESC");
		$this->template($data);
	}


	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "form";
		$data['mode']		= "insert";
		$this->template($data);
	}

	public function fetch_lbpc()
	{
		$fetch_data = $this->make_query_lbpc();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array     = array();
			$button        = '<button data-dismiss=\'modal\' onClick=\'return pilihLBPC(' . json_encode($rs) . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $rs->no_lbpc;
			$sub_array[] = $rs->tgl_lbpc;
			$sub_array[] = $rs->kelompok_pengajuan;
			$sub_array[] = $rs->start_date;
			$sub_array[] = $rs->end_date;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data_lbpc(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	function make_query_lbpc($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('no_lbpc', 'tgl_lbpc', 'kelompok_pengajuan', null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY tr_lbpc.created_at DESC';
		$search       = $this->input->post('search')['value'];
		// $id_dealer    = $this->m_admin->cari_dealer();
		$searchs      = "";

		if ($search != '') {
			$searchs .= "AND (no_lbpc LIKE '%$search%' 
	          OR kelompok_pengajuan LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';
		return $this->db->query("SELECT tr_lbpc.*
   			FROM tr_lbpc
   		 	$searchs $order $limit 
   		 ");
	}
	function get_filtered_data_lbpc()
	{
		return $this->make_query_lbpc('y')->num_rows();
	}

	function get_lbpc_part()
	{
		$no_lbpc = $this->input->post('no_lbpc');
		$get_dt = $this->db->query("SELECT tr_rekap_claim_waranty_detail.*,0 AS jml_accept,(SELECT nama_part FROM ms_part WHERE id_part=tr_rekap_claim_waranty_detail.id_part) AS nama_part,tr_lkh.no_rangka,tr_rekap_claim_waranty.*
   			FROM tr_rekap_claim_waranty_detail 
   			JOIN tr_rekap_claim_waranty ON tr_rekap_claim_waranty.id_rekap_claim=tr_rekap_claim_waranty_detail.id_rekap_claim
   			JOIN tr_lkh ON tr_lkh.id_lkh=tr_rekap_claim_waranty.no_lkh
   			WHERE no_lbpc='$no_lbpc'
				 ")->result();
		$get_dt = $this->m_claim->getRekapClaimWarrantyParts(['no_lbpc' => $no_lbpc]);
		echo json_encode($get_dt->result());
	}

	function get_no_ptcd()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$ymd 	  = date('Y-m-d');
		$ymd2 	  = date('ymd');
		$get_data  = $this->db->query("SELECT * FROM tr_ptcd
			WHERE LEFT(created_at,4)='$th' 
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			$no_ptcd  = substr($row->no_ptcd, 5, 5);
			$new_kode = 'PTCD/' . sprintf("%'.05d", $no_ptcd + 1) . '/' . $th;
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_ptcd', ['no_ptcd' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, 5, 5);
					$new_kode = 'PTCD/' . sprintf("%'.05d", $neww + 1) . '/' . $th;
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode   = 'PTCD/00001/' . $th;
		}
		return strtoupper($new_kode);
	}

	function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$no_ptcd  = $this->get_no_ptcd();
		$tgl_ptcd = $this->input->post('tgl_ptcd');
		$data = [
			'no_ptcd' => $no_ptcd,
			'tgl_ptcd' => $tgl_ptcd,
			'created_at' => $waktu,
			'grand_total' => $this->input->post('grand_total'),
			'created_by' => $login_id
		];
		// send_json($data);
		$details = $this->input->post('details');
		$this->db->trans_begin();
		$this->db->insert('tr_ptcd', $data);
		foreach ($details as $dt) {
			$upd   = ['jml_accept' => $dt['jml_accept'], 'no_ptcd' => $no_ptcd];
			$where = ['id_part'    => $dt['id_part'], 'id_rekap_claim' => $dt['id_rekap_claim']];
			$this->db->update('tr_rekap_claim_waranty_detail', $upd, $where);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('h2/ptcd')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h2/ptcd'>";
		}
		echo json_encode($rsp);
	}

	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$no_ptcd = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM tr_ptcd WHERE no_ptcd='$no_ptcd'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$filter['no_ptcd'] = $no_ptcd;
			$data['details'] = $this->m_claim->getRekapClaimWarrantyParts($filter)->result();
			$data['set']     = "form";
			$data['mode']    = "detail";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_claim_waranty'>";
		}
	}
}
