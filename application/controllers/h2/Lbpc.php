<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lbpc extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"lbpc";
	var $title  =   "LBPC (Laporan Biaya Penggantian Claim)";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_md_claim', 'm_claim');
		$this->load->model('m_h2_md_laporan', 'm_lap');
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$data['dt_result'] = $this->db->query("SELECT * FROM tr_lbpc ORDER BY created_at DESC");
		$data['dt_result'] = $this->m_claim->getRekapClaimWarranty(['lbpc_not_null' => true]);
		$this->template($data);
	}


	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		//$data['dt_lokasi'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");							
		$data['set']		= "form";
		$data['mode']		= "insert";
		$this->template($data);
	}

	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$no_lbpc = $this->input->get('id');
		$filter = ['no_lbpc' => $no_lbpc];
		$row = $this->m_claim->getLBPC($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "detail";
			$data['details'] = $this->m_claim->getLBPCDetail($filter)->result();
			$data['parts'] = $this->m_lap->getCetakLBPC($filter);
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lkh'>";
		}
	}

	function get_no_lbpc()
	{
		$th_bln   = date('Y-m');
		$bln   = date('m');
		$th   = date('Y');
		return 'E20/' . $bln . '/' . $th;
		/*
		$get_data  = $this->db->query("SELECT no_lbpc FROM tr_lbpc
			WHERE LEFT(created_at,4)='$th' 
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			$no_lbpc  = substr($row->no_lbpc, 0, 5);
			$new_kode = sprintf("%'.05d", $no_lbpc + 1) . '/E20/' . $bln . '/' . $th;
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_lbpc', ['no_lbpc' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, 0, 5);
					$new_kode = sprintf("%'.05d", $neww + 1) . '/E20/' . $bln . '/' . $th;
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = '00001/E20/' . $bln . '/' . $th;
		}
		return $new_kode; */
	}
	function id()
	{
		$new_kode = gmdate("YmdHis", time() + 60 * 60 * 7);
		$i = 0;
		while ($i < 1) {
			$cek = $this->db->get_where('tr_lbpc', ['no_lbpc' => $new_kode])->num_rows();
			if ($cek > 0) {
				$new_kode++;
			} else {
				$i++;
			}
		}
		return strtoupper($new_kode);
	}

	public function save()
	{
		$post               = $this->input->post();
		$no_lbpc            = $post['no_lbpc_awal'] . '/' . $this->get_no_lbpc();
		$no_lbpc_ahass_to_md = $post['no_lbpc_ahass_to_md'];
		$kelompok_pengajuan = $post['kelompok_pengajuan'];
		$tgl_lbpc         = date_ymd($post['tgl_lbpc']);
		$start_date         = date_ymd($post['start_date']);
		$end_date           = date_ymd($post['end_date']);
		// send_json($post);
		$filter = [
			'start_date' => $start_date,
			'kelompok_pengajuan' => $kelompok_pengajuan,
			'end_date' => $end_date,
			'periode_pengajuan' => true,
			'lbpc_null' => true
		];
		$details = json_decode($post['details']);
		foreach ($details as $rs) {
			$upd_claim[] = ['id_rekap_claim' => $rs->id_rekap_claim, 'no_lbpc' => $no_lbpc];
		}

		$data 	= [
			'id' => $this->id(),
			'no_lbpc'            => $no_lbpc,
			'no_lbpc_ahass_to_md'=>$no_lbpc_ahass_to_md,
			'start_date'         => $start_date,
			'end_date'           => $end_date,
			'kelompok_pengajuan' => $kelompok_pengajuan,
			'tgl_lbpc'           => $tgl_lbpc,
			'status'             => 'input',
			'created_at'         => waktu_full(),
			'created_by'         => user()->id_user
		];

		// $res = ['upd_claim' => $upd_claim, 'data' => $data];
		// send_json($res);
		$this->db->trans_begin();
		$this->db->insert('tr_lbpc', $data);
		if (isset($upd_claim)) {
			$this->db->update_batch('tr_rekap_claim_waranty', $upd_claim, 'id_rekap_claim');
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
				'link' => base_url('h2/lbpc')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	function generate($filter = null)
	{
		$post = $this->input->post();
		if ($filter == null) {
			$json = true;
			$filter = [
				'kelompok_pengajuan' => $post['kelompok_pengajuan'],
				'start_date' => date_ymd($post['start_date']),
				'end_date' => date_ymd($post['end_date']),
				'lbpc_null' => true,
				'status_rekap_claim' => 'approve',
				'periode_pengajuan' => true,
				'lbpc_null' => true,
				'ceklist_lbpc' => true
			];
		}
		$details =  $this->m_claim->getRekapClaimWarranty($filter);
		
		$parts = $this->m_lap->getCetakLBPC($filter);
		if ($details->num_rows() == 0) {
			$result = ['status' => 'error', 'pesan' => 'Data tidak ditemukan !'];
			send_json($result);
		}
		$result = ['status' => 'sukses', 'details' => $details->result(), 'parts' => $parts];
		if (isset($json)) {
			send_json($result);
		} else {
			return $result;
		}
	}

	// public function cetak()
	// {
	// 	$no_lbpc  = $this->input->get('id');
	// 	$filter = ['no_lbpc' => $no_lbpc];
	// 	$get_data = $this->m_claim->getLBPC($filter);
	// 	if ($get_data->num_rows() > 0) {
	// 		$row = $data['row'] = $get_data->row();

	// 		// $upd = [
	// 		// 	'print_ke' => $row->print_ke + 1,
	// 		// 	'print_at' => $waktu,
	// 		// 	'print_by' => $login_id,
	// 		// ];
	// 		// $this->db->update('tr_lbpc', $upd, ['no_lbpc' => $no_lbpc]);
	// 		$mpdf                           = $this->mpdf_l->load();
	// 		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
	// 		$mpdf->charset_in               = 'UTF-8';
	// 		$mpdf->autoLangToFont           = true;

	// 		$data['set'] = 'print';
	// 		$data['row'] = $row;
	// 		$data['detail'] = $this->m_lap->getCetakLBPC($filter);
	// 		// send_json($data);

	// 		$html = $this->load->view('h2/lbpc_cetak', $data, true);
	// 		// render the view into HTML
	// 		$mpdf->WriteHTML($html);
	// 		// write the HTML into the mpdf
	// 		$output = 'cetak_.pdf';
	// 		$mpdf->Output("$output", 'I');
	// 	} else {
	// 		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lbpc'>";
	// 	}
	// }
	public function download_excel()
	{
		$data['set'] = 'download_excel';
		$data['result'] = $this->db->query("SELECT tr_rekap_claim_waranty.*,tr_lkh.no_mesin,tr_lkh.no_rangka,tr_lkh.tgl_pembelian,
			nama_dealer,kode_dealer_md 
			FROM tr_lbpc 
			JOIN tr_rekap_claim_waranty ON tr_lbpc.no_lbpc=tr_rekap_claim_waranty.no_lbpc
			JOIN tr_lkh ON tr_rekap_claim_waranty.no_lkh=tr_lkh.id_lkh
			JOIN ms_dealer ON ms_dealer.id_dealer=tr_rekap_claim_waranty.id_dealer
			ORDER BY created_at ASC");
		$this->load->view('h2/lbpc_cetak', $data);
	}

	public function edit()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$no_lbpc = $this->input->get('id');
		$filter = ['no_lbpc' => $no_lbpc];
		$row = $this->m_claim->getLBPC($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "edit";
			$data['details'] = $this->m_claim->getLBPCDetail($filter)->result();
			$data['parts'] = $this->m_lap->getCetakLBPC($filter);
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lkh'>";
		}
	}
	public function save_edit()
	{
		$post        = $this->input->post();
		$no_lbpc_old = $post['no_lbpc_old'];
		$no_lbpc_new = $post['no_lbpc_awal'] . '/' . $this->get_no_lbpc();
		// send_json($post);
		$details = json_decode($post['details']);
		foreach ($details as $rs) {
			$upd_claim[] = ['id_rekap_claim' => $rs->id_rekap_claim, 'no_lbpc' => $no_lbpc_new];
		}

		$upd 	= [
			'no_lbpc'    => $no_lbpc_new,
			'tgl_lbpc'   => date_ymd($post['tgl_lbpc']),
			'status'     => 'input',
			'updated_at' => waktu_full(),
			'updated_by' => user()->id_user
		];

		// $res = ['upd_claim' => $upd_claim, 'upd' => $upd];
		// send_json($res);
		$this->db->trans_begin();
		$this->db->update('tr_lbpc', $upd, ['no_lbpc' => $no_lbpc_old]);
		if (isset($upd_claim)) {
			$this->db->update_batch('tr_rekap_claim_waranty', $upd_claim, 'id_rekap_claim');
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
				'link' => base_url('h2/lbpc')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
}
