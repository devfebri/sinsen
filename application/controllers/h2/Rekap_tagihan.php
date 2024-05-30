<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_tagihan extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"rekap_tagihan";
	var $title  =   "Rekap Tagihan";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2');
		$this->load->model('m_h2_md_claim', 'm_claim');

		//===== Load Library =====
		$this->load->library('upload');
		$this->load->helper('tgl_indo');

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
		$data['dt_result'] = $this->db->query("SELECT ptca.id_ptca,ptca.tgl_ptca,ptca.start_date,ptca.end_date,ptca.status,nama_dealer,kode_dealer_md FROm tr_rekap_tagihan_ptca ptca
			JOIN ms_dealer ON ms_dealer.id_dealer=ptca.id_dealer
		 ORDER BY ptca.tgl_ptca ASC");
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

	public function generate($filter = null)
	{
		if ($filter == null) {
			$post = $this->input->post();
			$filter = [
				'id_dealer' => $post['id_dealer'],
				'start_date' => date_ymd($post['start_date']),
				'end_date' => date_ymd($post['end_date']),
				'lbpc_not_null' => true,
				'get_summary' => true,
				'ceklist_ptca' => true,
				'ptca_null' => true,
				'group_by_dealer'=>true
			];
			$return_json = true;
		}
		$result = $this->m_claim->getRekapClaimWarranty($filter);
		if ($result->num_rows() > 0) {
			$response = ['status' => 'sukses', 'data' => $result->result()];
		} else {
			$response = ['status' => 'error', 'pesan' => 'Data tidak ditemukan !'];
		}
		if (isset($return_json)) {
			send_json($response);
		} else {
			return $result->result();
		}
	}

	function get_id_ptca()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$ymd 	  = date('Y-m-d');
		$ymd2 	  = date('ymd');
		$get_data  = $this->db->query("SELECT * FROM tr_rekap_tagihan_ptca
			WHERE LEFT(created_at,4)='$th' 
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			$id_ptca  = substr($row->id_ptca, 5, 5);
			$new_kode = $th . '/' . sprintf("%'.05d", $id_ptca + 1) . '/PTCA';
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_rekap_tagihan_ptca', ['id_ptca' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, 5, 5);
					$new_kode = $th . '/' . sprintf("%'.05d", $neww + 1) . '/PTCA';
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode   = $th . '/00001/PTCA';
		}
		return strtoupper($new_kode);
	}

	public function save()
	{
		$post = $this->input->post();
		$id_ptca    = $this->get_id_ptca();
		$details = json_decode($post['details']);
		// send_json($details);
		foreach ($details as $rs) {
			$upd_claim[] = ['id_rekap_claim' => $rs->id_rekap_claim, 'id_ptca' => $id_ptca];
			$upd_lbpc[] = [
				'no_lbpc' => $rs->no_lbpc,
				'id_ptca' => $id_ptca,
				'rekap_at' => waktu_full(),
				'rekap_by' => user()->id_user
			];
		}

		$data 	= [
			'id_ptca' => $id_ptca,
			'start_date'         => date_ymd($post['start_date']),
			'end_date'           => date_ymd($post['end_date']),
			'id_dealer' => $post['id_dealer'],
			'tgl_ptca'           => get_ymd(),
			'status'             => 'input',
			'created_at'         => waktu_full(),
			'created_by'         => user()->id_user
		];

		// $tes = ['upd_claim' => $upd_claim, 'data' => $data, 'upd_lbpc' => $upd_lbpc];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_rekap_tagihan_ptca', $data);
		if (isset($upd_claim)) {
			$this->db->update_batch('tr_rekap_claim_waranty', $upd_claim, 'id_rekap_claim');
		}
		if (isset($upd_lbpc)) {
			$this->db->update_batch('tr_lbpc', $upd_lbpc, 'no_lbpc');
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
				'link' => base_url('h2/rekap_tagihan')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_ptca = $this->input->get('id');
		$start_date = sql_date_dmy('ptca.start_date');
		$end_date = sql_date_dmy('ptca.end_date');
		$tgl_ptca = sql_date_dmy('ptca.tgl_ptca');

		$row = $this->db->query("SELECT *,($start_date) AS start_date,($end_date) AS end_date,($tgl_ptca) AS tgl_ptca,dl.pkp AS pkp_dealer FROM tr_rekap_tagihan_ptca ptca
		JOIN ms_dealer dl ON dl.id_dealer=ptca.id_dealer
		WHERE id_ptca='$id_ptca'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$filter = [
				'id_ptca' => $id_ptca,
				'get_summary' => true,
			];
			$data['details'] = $this->m_claim->getRekapClaimWarranty($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "detail";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_tagihan'>";
		}
	}

	public function approve()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_ptca = $this->input->get('id');
		$start_date = sql_date_dmy('ptca.start_date');
		$end_date = sql_date_dmy('ptca.end_date');
		$tgl_ptca = sql_date_dmy('ptca.tgl_ptca');

		$row = $this->db->query("SELECT *,($start_date) AS start_date,($end_date) AS end_date,($tgl_ptca) AS tgl_ptca,dl.pkp AS pkp_dealer FROM tr_rekap_tagihan_ptca ptca
		JOIN ms_dealer dl ON dl.id_dealer=ptca.id_dealer
		WHERE id_ptca='$id_ptca'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$filter = [
				'id_ptca' => $id_ptca,
				'get_summary' => true,
			];
			$data['details'] = $this->m_claim->getRekapClaimWarranty($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "approve";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_tagihan'>";
		}
	}

	public function save_approve()
	{
		$id_ptca    = $this->input->post('id_ptca');

		$data 	= [
			'status'             => 'approve',
			'approved_at'         => waktu_full(),
			'approved_by'         => user()->id_user
		];

		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
		$this->db->update('tr_rekap_tagihan_ptca', $data, ['id_ptca' => $id_ptca]);
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
				'link' => base_url('h2/rekap_tagihan')
			];
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function batal()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_ptca = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM tr_rekap_tagihan_ptca WHERE id_ptca='$id_ptca' AND status='input'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['details'] = $this->generate($row->start_date, $row->end_date, $row->id_dealer, $row->id_ptca);
			$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$row->id_dealer'")->row();
			$data['set']		= "form";
			$data['mode']		= "batal";
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/rekap_tagihan'>";
		}
	}

	public function save_batal()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_ptca    = $this->input->post('id_ptca');
		$id_dealer  = $this->input->post('id_dealer');

		$data 	= [
			'status'             => 'batal',
			'alasan_cancel' => $this->input->post('alasan_cancel'),
			'cancel_at'     => $waktu,
			'cancel_by'     => $login_id
		];

		// // echo json_encode($dt_detail);
		// echo json_encode($upd_claim);
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
		$this->db->update('tr_rekap_tagihan_ptca', $data, ['id_ptca' => $id_ptca]);
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
				'link' => base_url('h2/rekap_tagihan')
			];
			$_SESSION['pesan'] 	= "Data has been approved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
}
