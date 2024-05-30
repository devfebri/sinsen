<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen_mekanik extends CI_Controller
{

	var $folder = "dealer";
	var $page   = "absen_mekanik";
	var $title  = "Absen Mekanik";

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
		$this->load->library('form_validation');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
		$this->load->model('m_h2_master', 'mh2');
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
		$data['folder']    = $this->folder;
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index";
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';

			$btn_edit = '<a data-toggle="tooltip" title="Edit" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id_absen . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
			if (can_access($this->page, 'can_update')) $button .= $btn_edit;
			$sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_absen . '">' . $rs->tanggal . '</a>';
			// $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';
			$sub_array[] = $rs->tot;
			$sub_array[] = $rs->tot_hadir;
			$sub_array[] = $rs->tot_tidak_hadir;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST['order'] : '',
			'search' => $this->input->post('search')['value'],
			'order_column' => 'view',
		];

		if ($recordsFiltered == true) {
			return $this->mh2->get_absen_mekanik($filter)->num_rows();
		} else {
			return $this->mh2->get_absen_mekanik($filter)->result();
		}
	}

	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$id_dealer       = $this->m_admin->cari_dealer();
		$this->template($data);
	}

	function save()
	{
		$waktu     = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = date("Y-m-d");
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$id_absen  = $this->get_id_absen();
		$tanggal   = $this->input->post('tanggal');
		$cek = $this->db->get_where('tr_h2_absen_mekanik', ['tanggal' => $tanggal, 'id_dealer' => $id_dealer]);
		if ($cek->num_rows() > 0) {
			$result = ['status' => 'error', 'pesan' => 'Data absensi tanggal ' . $tanggal . ' sudah diisi !'];
			send_json($result);
		}
		$ins_data 		= [
			'id_absen' => $id_absen,
			'tanggal'    => $tanggal,
			'id_dealer'  => $id_dealer,
			'created_at' => $waktu,
			'created_by' => $login_id,
		];

		$absen = $this->input->post('absen');
		foreach ($absen as $keys => $val) {
			$ins_details[] = [
				'id_absen' => $id_absen,
				'id_karyawan_dealer' => $val['id_karyawan_dealer'],
				'aktif' => $val['aktif'] == 'true' ? 1 : 0,
			];
		}
		// $result = ['ins_data'=>$ins_data,'ins_details'=>$ins_details];
		// echo json_encode($result);
		// exit();
		$this->db->trans_begin();
		$this->db->insert('tr_h2_absen_mekanik', $ins_data);
		if (isset($ins_details)) {
			$this->db->insert_batch('tr_h2_absen_mekanik_detail', $ins_details);
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
				'link' => base_url('dealer/absen_mekanik')
			];
			$_SESSION['pesan'] 	= "Data berhasil disimpan.";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function get_id_absen()
	{
		$th        = date('y');
		$bln       = date('m');
		$tgl       = date('Y-m');
		$thbln     = date('ymd');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$get_data  = $this->db->query("SELECT id_absen FROM tr_h2_absen_mekanik
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$tgl'
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$last_number = substr($row->id_absen, -4);
			$new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/ABS/' . sprintf("%'.04d", $last_number + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_h2_absen_mekanik', ['id_absen' => $new_kode])->num_rows();
				if ($cek > 0) {
					$gen_number    = substr($new_kode, -4);
					$new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/ABS/' . sprintf("%'.04d", $gen_number + 1);
					$i = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/ABS/0001';
		}
		return strtoupper($new_kode);
	}

	function showDetailAbsensi()
	{
		$tanggal = $this->input->post('tanggal');
		$filter[]  = ['tanggal' => $tanggal];
		if (isset($_POST['cek_tanggal'])) {
			if ($_POST['cek_tanggal'] == 'y') {
				$cek_tanggal = $this->mh2->get_absen_mekanik($filter);
				if ($cek_tanggal->num_rows() > 0) {
					$result = ['status' => 'error', 'pesan' => 'Absensi untuk tanggal ' . $tanggal . ' sudah ada !'];
					echo json_encode($result);
					exit();
				}
			}
		}
		$get_abs = $this->mh2->get_detail_absen_mekanik(null, $tanggal);
		if ($get_abs->num_rows() == 0) {
			$result = ['status' => 'error', 'pesan' => 'Data karyawan tidak ditemukan !'];
		} else {
			$result = ['status' => 'sukses', 'data' => $get_abs->result()];
		}
		echo json_encode($result);
	}

	public function edit()
	{
		$id_absen      = $this->input->get('id');
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'edit';
		$data['set']   = "form";
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM tr_h2_absen_mekanik WHERE id_absen='$id_absen' AND id_dealer=$id_dealer");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pit'>";
		}
	}

	function save_edit()
	{
		$waktu    = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$id_absen = $this->input->post('id_absen');

		$upd_data 		= [
			'id_absen' => $id_absen,
			'updated_at' => $waktu,
			'updated_by' => $login_id,
		];

		$absen = $this->input->post('absen');
		foreach ($absen as $val) {
			$ins_details[] = [
				'id_absen' => $id_absen,
				'id_karyawan_dealer' => $val['id_karyawan_dealer'],
				'aktif' => $val['aktif'] == 'true' ? 1 : 0,
			];
		}
		// $result = ['upd_data' => $upd_data, 'ins_details' => $ins_details];
		// echo json_encode($result);
		// exit();
		$this->db->trans_begin();
		$this->db->update('tr_h2_absen_mekanik', $upd_data, ['id_absen' => $id_absen]);
		$this->db->delete('tr_h2_absen_mekanik_detail', ['id_absen' => $id_absen]);
		if (isset($ins_details)) {
			$this->db->insert_batch('tr_h2_absen_mekanik_detail', $ins_details);
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
				'link' => base_url('dealer/absen_mekanik')
			];
			$_SESSION['pesan'] 	= "Data berhasil disimpan.";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function detail()
	{
		$id_absen      = $this->input->get('id');
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_dealer = $this->m_admin->cari_dealer();
		$row    = $this->db->query("SELECT * FROM tr_h2_absen_mekanik WHERE id_absen='$id_absen' AND id_dealer=$id_dealer");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/pit'>";
		}
	}
}
