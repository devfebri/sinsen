<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_group extends CI_Controller
{
	var $tables =   "ms_user_group";
	var $folder =   "master";
	var $page		=		"user_group";
	var $pk     =   "id_user_group";
	var $title  =   "Master Data User group";
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
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
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
		$data['dt_user_group'] = $this->db->query("SELECT * FROM ms_user_group WHERE user_group != 'Super Admin'");
		$this->template($data);
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$this->template($data);
	}
	public function save()
	{
		$tabel							= $this->tables;
		$data['code'] 			= $this->input->post('code');
		$data['user_group']	= $this->input->post('user_group');
		$data['jenis_user']			= $this->input->post('jenis_user');
		$this->m_admin->insert($tabel, $data);
		$_SESSION['pesan'] = "Data has been saved successfully";
		$_SESSION['tipe'] = "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/user_group/add'>";
	}
	public function delete()
	{
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->get('id');
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
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/user_group'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel, $pk, $id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$d 					= array($pk => $id);
		$data['dt_user_group'] = $this->m_admin->kondisi($tabel, $d);
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "edit";
		$this->template($data);
	}
	public function update()
	{
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');
		$data['code'] 			= $this->input->post('code');
		$data['user_group'] 	= $this->input->post('user_group');
		$data['jenis_user']			= $this->input->post('jenis_user');
		$this->m_admin->update($tabel, $data, $pk, $id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/user_group'>";
	}
	public function access_level()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$d 					= array($pk => $id);
		$data['dt_user_group'] = $this->m_admin->kondisi($tabel, $d);
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "set_menu";
		$this->template($data);
	}
	public function cek_access()
	{
		$id_menu 					= $this->input->get('id_menu');
		$id_user_group 		= $this->input->get('id_user_group');
		$jum_data					= $this->input->get('jum_data');
		$fr = "";
		for ($i = 1; $i <= $jum_data; $i++) {
			if (isset($_GET["can_select_" . $i . ""])) {
				$id_menu 	= $_GET["id_menu_" . $i . ""];
				$fr = $fr . "," . $id_menu;
			}
		}
		$_SESSION['pesan'] = "Data has been saved successfully " . $fr;
		$_SESSION['tipe'] = "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/user_group'>";
	}
	public function save_access()
	{
		$waktu 						= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id					= $this->session->userdata('id_user');
		$tabel								= $this->tables;
		$id_menu 							= $this->input->post('id_menu');
		$id_user_group				= $this->input->post('id_user_group');
		$jum_data							= $this->input->post('jum_data');

		$cek = $this->db->query("SELECT * FROM ms_user_access_level WHERE id_user_group = '$id_user_group'");
		if (count($cek) == 0) {
			for ($i = 1; $i <= $jum_data; $i++) {
				if (isset($_POST["can_select_" . $i . ""])) $can_select = 1;
				else $can_select = 0;
				if (isset($_POST["can_insert_" . $i . ""])) $can_insert = 1;
				else $can_insert = 0;
				if (isset($_POST["can_update_" . $i . ""])) $can_update = 1;
				else $can_update = 0;
				if (isset($_POST["can_delete_" . $i . ""])) $can_delete = 1;
				else $can_delete = 0;
				if (isset($_POST["can_print_" . $i . ""])) $can_print = 1;
				else $can_print = 0;
				if (isset($_POST["can_download_" . $i . ""])) $can_download = 1;
				else $can_download = 0;
				if (isset($_POST["can_approval_" . $i . ""])) $can_approval = 1;
				else $can_approval = 0;
				if (isset($_POST["can_reject_" . $i . ""])) $can_reject = 1;
				else $can_reject = 0;
				if (isset($_POST["can_submit_" . $i . ""])) $can_submit = 1;
				else $can_submit = 0;
				if (isset($_POST["can_cancel_" . $i . ""])) $can_cancel = 1;
				else $can_cancel = 0;
				if (isset($_POST["can_close_" . $i . ""])) $can_close = 1;
				else $can_close = 0;
				if (isset($_POST["can_reopen_" . $i . ""])) $can_reopen = 1;
				else $can_reopen = 0;
				if (isset($_POST["can_transit_" . $i . ""])) $can_transit = 1;
				else $can_transit = 0;
				$id_menu 	= $_POST["id_menu_" . $i . ""];
				$data['id_user_group'] = $id_user_group;
				$data['id_menu']  			  = $id_menu;
				$data['can_select']  	 = $can_select;
				$data['can_delete']  	 = $can_insert;
				$data['can_update']  	 = $can_update;
				$data['can_delete']  	 = $can_delete;
				$data['can_print']  		 = $can_print;
				$data['can_download']	 = $can_download;
				$data['can_approval']  = $can_approval;
				$data['can_reject']  	 = $can_reject;
				$data['can_submit']  	 = $can_submit;
				$data['can_cancel']  	 = $can_cancel;
				$data['can_close']  	  = $can_close;
				$data['can_reopen']  	 = $can_reopen;
				$data['can_transit']   = $can_transit;
				$testb = $this->m_admin->insert('ms_user_access_level', $data);
			}
		} else {
			$del = $this->db->query("DELETE FROM ms_user_access_level WHERE id_user_group = '$id_user_group'");
			// echo $jum_data;
			for ($i = 1; $i <= $jum_data; $i++) {
				if (isset($_POST["can_select_" . $i . ""])) $can_select = 1;
				else $can_select = 0;
				if (isset($_POST["can_insert_" . $i . ""])) $can_insert = 1;
				else $can_insert = 0;
				if (isset($_POST["can_update_" . $i . ""])) $can_update = 1;
				else $can_update = 0;
				if (isset($_POST["can_delete_" . $i . ""])) $can_delete = 1;
				else $can_delete = 0;
				if (isset($_POST["can_print_" . $i . ""])) $can_print = 1;
				else $can_print = 0;
				if (isset($_POST["can_download_" . $i . ""])) $can_download = 1;
				else $can_download = 0;
				if (isset($_POST["can_approval_" . $i . ""])) $can_approval = 1;
				else $can_approval = 0;
				if (isset($_POST["can_reject_" . $i . ""])) $can_reject = 1;
				else $can_reject = 0;
				if (isset($_POST["can_submit_" . $i . ""])) $can_submit = 1;
				else $can_submit = 0;
				if (isset($_POST["can_cancel_" . $i . ""])) $can_cancel = 1;
				else $can_cancel = 0;
				if (isset($_POST["can_close_" . $i . ""])) $can_close = 1;
				else $can_close = 0;
				if (isset($_POST["can_reopen_" . $i . ""])) $can_reopen = 1;
				else $can_reopen = 0;
				if (isset($_POST["can_transit_" . $i . ""])) $can_transit = 1;
				else $can_transit = 0;
				$id_menu 	= $_POST["id_menu_" . $i . ""];
				$data['id_user_group']  = $id_user_group;
				$data['id_menu']  			= $id_menu;
				$data['can_select']  		= $can_select;
				$data['can_insert']  		= $can_insert;
				$data['can_update']  		= $can_update;
				$data['can_delete']  		= $can_delete;
				$data['can_print']  		= $can_print;
				$data['can_download']		= $can_download;
				$data['can_approval']  	= $can_approval;
				$data['can_reject']  	 = $can_reject;
				$data['can_submit']  	 = $can_submit;
				$data['can_cancel']  	 = $can_cancel;
				$data['can_close']  	  = $can_close;
				$data['can_reopen']  	 = $can_reopen;
				$data['can_transit']   = $can_transit;
				$testb = $this->m_admin->insert('ms_user_access_level', $data);
			}
		}
		$_SESSION['pesan'] = "Data has been saved successfully";
		$_SESSION['tipe'] = "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/user_group'>";
	}
}
