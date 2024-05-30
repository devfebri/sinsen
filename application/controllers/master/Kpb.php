<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpb extends CI_Controller
{

	var $tables = "ms_kpb";
	var $folder = "master";
	var $page   = "kpb";
	var $pk     = "id_tipe_kendaraan";
	var $title  = "Master Data KPB";
	var $order_column_part = array("id_part", "nama_part", 'kelompok_vendor', null);

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
		$this->load->model('m_kpb_reminder', 'm_kpbm');
		$this->load->model('m_h2_md_claim', 'm_claim');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		$data['folder'] = $this->folder;
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
		// $data['kpb']    = $this->db->query("SELECT ms_kpb.*,ms_tipe_kendaraan.tipe_ahm FROM ms_kpb 
		// 									JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_kpb.id_tipe_kendaraan ORDER BY created_at DESC
		// 	");
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
			$btn_add = "<a data-toggle='tooltip' title='Tambah' class='btn btn-primary btn-xs btn-flat' href=\"" . base_url('master/' . $this->page . '/add?id=' . $rs->id_tipe_kendaraan) . "\"><i class='fa fa-plus'></i></a>";
			$btn_edit = "<a data-toggle='tooltip' title='Edit' class='btn btn-warning btn-xs btn-flat' href=\"" . base_url('master/' . $this->page . '/edit?id=' . $rs->id_tipe_kendaraan) . "\"><i class='fa fa-edit'></i></a>";
			$btn_history = "<a data-toggle='tooltip' title='History' class='btn btn-success btn-xs btn-flat' href=\"" . base_url('master/' . $this->page . '/history?id=' . $rs->id_tipe_kendaraan) . "\"><i class='fa fa-edit'></i></a>";
			if ($rs->set_kpb == NULL) {
				$button .= $btn_add;
			} else {
				$button .= $btn_edit;
			}
			// if ($rs->status == 'input') {
			// 	$status = '<label class="label label-primary">Input</label>';
			// 	// if (can_access($this->page, 'can_update'))  
			// 	$button .= $btn_approval;
			// } elseif ($rs->status == 'approved') {
			// 	$status = '<label class="label label-success">Approved</label>';
			// } elseif ($rs->status == 'rejected') {
			// 	$status = '<label class="label label-danger">Rejected</label>';
			// }

			$sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_tipe_kendaraan . '">' . $rs->id_tipe_kendaraan . '</a>';
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->n_kpb;
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
		if (isset($_POST['id_tipe_kendaraan'])) {
			$filter['id_tipe_kendaraan'] = $_POST['id_tipe_kendaraan'];
		}
		if ($recordsFiltered == true) {
			return $this->m_claim->getKPBTipeKendaraan($filter)->num_rows();
		} else {
			return $this->m_claim->getKPBTipeKendaraan($filter)->result();
		}
	}

	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert';
		$id = $this->input->get('id');
		$data['dt_tipe'] = $this->db->get_where("ms_tipe_kendaraan", ['id_tipe_kendaraan' => $id]);
		$data['set']     = "form";
		$this->template($data);
	}

	function save()
	{
		$waktu    = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
		$tgl      = date("Y-m-d");
		$login_id = $this->session->userdata('id_user');
		$post     = $this->input->post();


		
		$upd_data_ms_tipe 		= [
			'n_kpb' => $post['n_kpb'],
			'updated_at'   => $waktu,
			'updated_by'   => $login_id,
		];

		$ins_data 		= [
			'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
			'harga_oli' => $post['harga_oli'],
			'insentif_oli' => $post['insentif_oli'],
			'status'            => 1,
			'created_at'        => $waktu,
			'created_by'        => $login_id,
		];

		$details = $this->input->post('details');
		foreach ($details as $val) {
			$ins_details[] = [
				'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
				'batas_maks_kpb'                => isset($val['batas_maks_kpb']) ? $val['batas_maks_kpb'] : 0,
				'km'                => isset($val['km']) ? $val['km'] : 0,
				'toleransi'                => isset($val['toleransi']) ? $val['toleransi'] : 0,
				'harga_jasa'                => isset($val['harga_jasa']) ? $val['harga_jasa'] : 0,
				'kpb_ke'                => isset($val['kpb_ke']) ? $val['kpb_ke'] : 0,
			];
			if (isset($val['oli'])) {
				foreach ($val['oli'] as $ol) {
					$ins_oli[] =
						[
							'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
							'kpb_ke' => $val['kpb_ke'],
							'id_part' => $ol['id_part']
						];
				}
			}
		}
		if (empty($ins_details)) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'batas KPB belum ditentukan !'
			];
			send_json($rsp);
		}
		// $result = ['ins_data' => $ins_data, 'ins_details' => $ins_details, 'ins_oli' => isset($ins_oli) ? $ins_oli : ''];
		// send_json($result);
		$this->db->trans_begin();
		$this->db->update('ms_kpb', ['status' => 0], ['id_tipe_kendaraan' => $post['id_tipe_kendaraan']]);
		
		$this->db->update('ms_tipe_kendaraan', $upd_data_ms_tipe, ['id_tipe_kendaraan' => $post['id_tipe_kendaraan']]); 

		$this->db->insert('ms_kpb', $ins_data);
		if (isset($ins_details)) {
			$this->db->insert_batch('ms_kpb_detail', $ins_details);
		}
		if (isset($ins_oli)) {
			$this->db->insert_batch('ms_kpb_oli', $ins_oli);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong !'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('master/kpb')
			];
			$_SESSION['pesan'] 	= "Data berhasil disimpan";
			$_SESSION['tipe'] 	= "success";
		}
		echo json_encode($rsp);
	}

	// public function delete()
	// {
	// 	$tabel			= $this->tables;
	// 	$pk 			= $this->pk;
	// 	$id 			= $this->input->get('id');
	// 	$this->db->trans_begin();
	// 	$this->db->delete($tabel, array($pk => $id));
	// 	$this->db->delete('ms_kpb_detail', array($pk => $id));
	// 	$this->db->trans_commit();
	// 	$result = 'Success';

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$result = 'You can not delete this data because it already used by the other tables';
	// 		$_SESSION['tipe'] 	= "danger";
	// 	} else {
	// 		$result = 'Data has been deleted succesfully';
	// 		$_SESSION['tipe'] 	= "success";
	// 	}
	// 	$_SESSION['pesan'] 	= $result;
	// 	echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/kpb'>";
	// }
	// public function ajax_bulk_delete()
	// {
	// 	$tabel			= $this->tables;
	// 	$pk 			= $this->pk;
	// 	$list_id 		= $this->input->post('id');
	// 	foreach ($list_id as $id) {
	// 		$this->m_admin->delete($tabel, $pk, $id);
	// 	}
	// 	echo json_encode(array("status" => TRUE));
	// }

	public function edit()
	{
		$id              = $this->input->get('id');
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'edit';
		$data['dt_tipe'] = $this->db->get_where("ms_tipe_kendaraan", ['id_tipe_kendaraan' => $id]);
		$filter = ['id_tipe_kendaraan' => $id];
		$data['kpb']     = $this->m_claim->getMasterKPB($filter);
		$filter = ['id_tipe_kendaraan' => $id];
		$data['details'] = $this->m_kpbm->detailBatasKPB($filter);
		$data['set']     = "form";
		$this->template($data);
	}

	function save_edit()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = date("Y-m-d");
		$login_id = $this->session->userdata('id_user');
		$post     = $this->input->post();

		$upd_data_ms_tipe 		= [
			'no_mesin'    => $post['no_mesin'],
			'n_kpb' => $post['n_kpb'],
			'updated_at'   => $waktu,
			'updated_by'   => $login_id,
		];
		
		$upd_data 		= [
			'status'       => 1,
			'harga_oli'    => $post['harga_oli'],
			'insentif_oli' => $post['insentif_oli'],
			'updated_at'   => $waktu,
			'updated_by'   => $login_id,
		];

		$details = $this->input->post('details');
		foreach ($details as $val) {
			$ins_details[] = [
				'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
				'batas_maks_kpb'    => isset($val['batas_maks_kpb']) ? $val['batas_maks_kpb'] : 0,
				'km'                => isset($val['km']) ? $val['km']                        : 0,
				'toleransi'         => isset($val['toleransi']) ? $val['toleransi']          : 0,
				'harga_jasa'        => isset($val['harga_jasa']) ? $val['harga_jasa']        : 0,
				'kpb_ke'            => isset($val['kpb_ke']) ? $val['kpb_ke']                : 0,
			];
			if (isset($val['oli'])) {
				foreach ($val['oli'] as $ol) {
					$ins_oli[] =
						[
							'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
							'kpb_ke' => $val['kpb_ke'],
							'id_part' => $ol['id_part']
						];
				}
			}
		}
		if (empty($ins_details)) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'batas KPB belum ditentukan !'
			];
			send_json($rsp);
		}
		// $result = ['upd_data' => $upd_data, 'ins_details' => $ins_details, 'ins_oli' => isset($ins_oli) ? $ins_oli : ''];
		// send_json($result);
		$this->db->trans_begin();
		$this->db->update('ms_kpb', $upd_data, ['id_tipe_kendaraan' => $post['id_tipe_kendaraan'], 'status' => 1]);
		
		$this->db->update('ms_tipe_kendaraan', $upd_data_ms_tipe, ['id_tipe_kendaraan' => $post['id_tipe_kendaraan']]);

		$this->db->delete('ms_kpb_detail', ['id_tipe_kendaraan' => $post['id_tipe_kendaraan']]);
		$this->db->delete('ms_kpb_oli', ['id_tipe_kendaraan' => $post['id_tipe_kendaraan']]);

		if (isset($ins_details)) {
			$this->db->insert_batch('ms_kpb_detail', $ins_details);
		}
		if (isset($ins_oli)) {
			$this->db->insert_batch('ms_kpb_oli', $ins_oli);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong !'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('master/kpb')
			];
			$_SESSION['pesan'] 	= "Data berhasil disimpan";
			$_SESSION['tipe'] 	= "success";
		}
		echo json_encode($rsp);
	}

	public function detail()
	{
		$id              = $this->input->get('id');
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'detail';
		$data['dt_tipe'] = $this->db->get_where("ms_tipe_kendaraan", ['id_tipe_kendaraan' => $id]);
		$filter = ['id_tipe_kendaraan' => $id];
		$data['kpb']     = $this->m_claim->getMasterKPB($filter);
		$filter = ['id_tipe_kendaraan' => $id];
		$data['details'] = $this->m_kpbm->detailBatasKPB($filter);
		$data['set']     = "form";
		$this->template($data);
	}

	public function fetch_part()
	{
		$fetch_data = $this->make_datatables();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array   = array();
			$sub_array[] = $rs->id_part;
			$sub_array[] = $rs->nama_part;
			$sub_array[] = $rs->kelompok_vendor;
			$row         = json_encode($rs);
			$link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPart(' . $row . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
			$sub_array[] = $link;
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
		$this->db->select('*');
		$this->db->from('ms_part');
		// $this->db->join('ms_link', 'ms_link.kode_btn = ms_link.kode_btn');

		$search = $this->input->post('search')['value'];
		if ($search != '') {
			$searchs = "(id_part LIKE '%$search%' 
	          OR nama_part LIKE '%$search%'
	      )";
			$this->db->where("$searchs", NULL, false);
		}
		if (isset($_POST["order"])) {
			$this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
			$this->db->order_by('id_part', 'ASC');
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
}
