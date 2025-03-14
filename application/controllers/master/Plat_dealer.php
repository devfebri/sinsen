<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Plat_dealer extends CI_Controller
{



	var $tables =   "ms_plat_dealer";

	var $folder =   "master";

	var $page		=		"plat_dealer";

	var $pk     =   "id_master_plat";

	var $title  =   "Data Mobil Dealer";



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
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
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

		$data['set']		= "view";
		$data['plat_dealer'] = $this->db->query("SELECT ms_plat_dealer.*,ms_dealer.nama_dealer FROM $this->tables
												inner join ms_dealer on $this->tables.id_dealer = ms_dealer.id_dealer 
											 ORDER BY id_master_plat DESC");
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
	public function t_detail()

	{

		$data['plat_dealer'] = $this->db->query("SELECT * FROM $this->tables
												inner join ms_dealer on $this->tables.id_dealer = ms_dealer.id_dealer 
											 ORDER BY id_master_plat DESC");
		$this->load->view("master/t_plat_dealer", $data);
	}

	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$id_dealer = $this->input->post('id_dealer');
		$no_plat 	= $this->input->post('no_plat');
		$driver = $this->input->post('driver');
		$no_hp = $this->input->post('no_hp');

		$data_insert = array(
			'id_dealer' => $id_dealer,
			'no_plat' => $no_plat,
			'driver' => $driver,
			'id_karyawan_dealer' => $this->input->post('id_karyawan_dealer'),
			'no_hp' => $no_hp,
			'active' => 1,
			//'ksu' => $ksu,
			'created_at' => $waktu,
			'created_by' => $login_id,
			'status' => '',
		);
		$this->m_admin->insert($this->tables, $data_insert);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/plat_dealer/add'>";
	}

	public function update()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$id_dealer = $this->input->post('id_dealer');
		$id = $this->input->post('id');
		$no_plat 	= $this->input->post('no_plat');
		$driver = $this->input->post('driver');
		$id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
		$no_hp = $this->input->post('no_hp');

		$data_insert = array(
			'id_dealer' => $id_dealer,
			'no_plat' => $no_plat,
			'driver' => $driver,
			'id_karyawan_dealer' => $id_karyawan_dealer,
			'no_hp' => $no_hp,
			'active' => 1,
			'updated_at' => $waktu,
			'updated_by' => $login_id,
		);
		$this->m_admin->update($this->tables, $data_insert, "id_master_plat", $id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/plat_dealer'>";
	}

	public function delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel, $pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_begin();
			$this->db->delete($tabel, array($pk => $id));
			$this->db->trans_commit();
			$result = 'Success';

			if ($this->db->trans_status() === FALSE) {
				$result = 'You can not delete this data because it already used by the other tables';
				$_SESSION['tipe'] 	= "danger";
			} else {
				$result = 'Data has been deleted succesfully';
				$_SESSION['tipe'] 	= "success";
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/plat_dealer'>";
		}
	}

	public function edit()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title;
		$data['set']		= "form";
		$data['mode']		= "edit";
		$id 						= $this->input->get('id');
		$row = $this->getPlatDealer($id);
		if ($row->num_rows() > 0) {
			$data['row'] = $row->row();
			// send_json($data);
			$this->template($data);
		}
	}

	function getPlatDealer($id)
	{
		return $this->db->query("SELECT mpd.*,honda_id FROM ms_plat_dealer mpd 
		LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=mpd.id_karyawan_dealer
		WHERE id_master_plat = '$id'");
	}
}
