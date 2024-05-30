<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jasa_h2_d extends CI_Controller
{

	var $folder =   "dealer";
	var $page		=		"jasa_h2_d";
	var $title  =   "Master Jasa H2";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_master');
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->helper('tgl_indo');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
			error_reporting(-1);
		ini_set('display_errors', 1);
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
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['set']       = "view";
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
		    $deskripsi = $this->db->query("SELECT tipe_marketing,deskripsi from ms_ptm where tipe_produksi ='$rs->tipe_motor'")->row();
			$sub_array = array();
			$button    = '';
			$btn_edit = "<a data-toggle='tooltip' href='dealer/jasa_h2_d/edit?id=$rs->id_jasa'><button class='btn btn-flat btn-xs btn-warning'>Atur Harga</button></a>";
			if (can_access($this->page, 'can_update') && ($rs->id_type == 'ASS1' || $rs->id_type == 'ASS2' || $rs->id_type == 'ASS3' || $rs->id_type == 'ASS4' || $rs->id_type=='CS') == false) $button = $btn_edit;
			$active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';

			$sub_array[] = "<a href='dealer/jasa_h2_d/detail?id=$rs->id_jasa'>$rs->id_jasa</a>";
			$sub_array[] = $rs->deskripsi;
			$sub_array[] = $rs->desk_tipe;
			$sub_array[] = $rs->kategori;
			$sub_array[] = $rs->tipe_motor." - ".$deskripsi->tipe_marketing;
			$sub_array[] = $deskripsi->deskripsi;
			$sub_array[] = mata_uang_rp($rs->harga);
			$sub_array[] = mata_uang_rp($rs->batas_bawah) . ' - ' . mata_uang_rp($rs->batas_atas);
			$sub_array[] = mata_uang_rp($rs->harga_dealer);
			$sub_array[] = $rs->waktu . ' menit';
			$sub_array[] = $active;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->get_filtered_data(),
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function make_query($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start,$length";
		$order        = '';
		$search       = $this->input->post('search')['value'];
		$id_dealer    = $this->m_admin->cari_dealer();

		if (isset($_POST["order"])) $order     = $_POST["order"];
		if ($no_limit == 'y') $limit = '';
		$filter = [
			'id_dealer' => $id_dealer,
			'limit' => $limit,
			'search' => $search,
			'order' => $order
		];
		// send_json($filter);
		return $this->m_h2_master->fetch_jasa_h2_dealer($filter);
	}

	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}


	// public function add()
	// {				
	// 	$data['isi']    = $this->page;		
	// 	$data['title']	= $this->title;		
	// 	$data['set']		= "form";					
	// 	$data['mode']		= "insert";					
	// 	$this->template($data);										
	// }

	public function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_jasa  = $this->input->post('id_jasa');
		$cek_id_jasa = $this->db->get_where('ms_h2_jasa', ['id_jasa' => $id_jasa]);
		if ($cek_id_jasa->num_rows() > 0) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'ID Jasa ' . $id_jasa . ' sudah ada !'
			];
			echo json_encode($rsp);
			exit;
		}

		$data 	= [
			'id_jasa' => $id_jasa,
			'id_jasa2'     => $this->input->post('id_jasa2'),
			'deskripsi'   => $this->input->post('deskripsi'),
			'id_type'     => $this->input->post('id_type'),
			'tipe_motor'  => $this->input->post('tipe_motor'),
			'harga'       => $this->input->post('harga'),
			'batas_atas'  => $this->input->post('batas_atas'),
			'batas_bawah' => $this->input->post('batas_bawah'),
			'waktu'       => $this->input->post('waktu'),
			'kategori'    => $this->input->post('kategori'),
			'active'      => isset($_POST['active']) ? 1 : 0,
			'created_at'  => $waktu,
			'created_by'  => $login_id
		];

		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
		$this->db->insert('ms_h2_jasa', $data);
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
				'link' => base_url('dealer/jasa_h2_d')
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
		$id_jasa = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM ms_h2_jasa WHERE id_jasa='$id_jasa'");
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "detail";
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/jasa_h2_d'>";
		}
	}

	public function edit()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$id_jasa       = $this->input->get('id');
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_type_not_in = "'ASS1','ASS2','ASS3','ASS4'";
		$row           = $this->m_h2_master->get_jasa_h2($id_dealer, $id_jasa, NULL, $id_type_not_in);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['set']		= "form";
			$data['mode']		= "edit";
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/jasa_h2_d'>";
		}
	}

	public function save_edit()
	{
		$waktu     = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$id_jasa   = $this->input->post('id_jasa');
		$cek_jasa_on_dealer = $this->m_h2_master->get_jasa_h2($id_dealer, $id_jasa);
		if ($cek_jasa_on_dealer->row()->id_dealer == null) {
			$ins_data 	= [
				'id_jasa' => $id_jasa,
				'id_dealer'	   => $id_dealer,
				'harga_dealer' => $this->input->post('harga_dealer'),
				'active'       => 1,
				'created_at'   => $waktu,
				'created_by'   => $login_id
			];
		} else {
			$upd_data 	= [
				'harga_dealer' => $this->input->post('harga_dealer'),
				'updated_at'   => $waktu,
				'updated_by'   => $login_id
			];
		}
		// echo json_encode($data);
		// exit;
		$this->db->trans_begin();
		if (isset($ins_data)) {
			$this->db->insert('ms_h2_jasa_dealer', $ins_data);
		}
		if (isset($upd_data)) {
			$this->db->update('ms_h2_jasa_dealer', $upd_data, ['id_jasa' => $id_jasa, 'id_dealer' => $id_dealer]);
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
				'link' => base_url('dealer/jasa_h2_d')
			];
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
		}
		echo json_encode($rsp);
	}
	public function delete()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_jasa  = $this->input->get('id');
		$this->db->delete('ms_h2_jasa', ['id_jasa' => $id_jasa]);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/jasa_h2_d'>";
	}
}
