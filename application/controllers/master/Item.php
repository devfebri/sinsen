<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{

	var $tables =   "ms_item";
	var $folder =   "master";
	var $page		=		"item";
	var $pk     =   "id_item";
	var $title  =   "Master Data Item";

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
		$data['set']	= "view";
		// $data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan 
		// 																			ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna 
		// 																			ON ms_warna.id_warna = ms_item.id_warna WHERE ms_item.active = 1
		// 															        ORDER BY id_item ASC");
		$data['dt_item'] = $this->db->query("SELECT ms_item.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan	 
																			ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna 
																			ON ms_item.id_warna=ms_warna.id_warna
																			 -- WHERE ms_item.active = 1 
																			ORDER BY created_at DESC");
		$this->template($data);
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "insert";
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}
	public function save()
	{
		$waktu 		= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$config['upload_path']    = './assets/panel/item_video/';
			$config['allowed_types'] = 'mp4|3gp|flv|mkv|wmv';
			$config['max_size']      = '20480';
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name']  = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('video')) {
				$video = $this->upload->file_name;
			} else {
				$video = NULL;
			}

			$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
			$id_item = $this->input->post('id_item');
			$path = "./uploads/unit/$id_tipe_kendaraan";
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
			}
			$config_gbr['upload_path']   = $path;
			$config_gbr['allowed_types'] = 'jpg|png|jpeg|bmp';
			$config_gbr['max_size']      = '2048';
			$config_gbr['remove_spaces'] = TRUE;
			$config_gbr['overwrite']     = TRUE;
			$config_gbr['file_name']     = $id_item;
			$this->load->library('upload', $config_gbr);
			$this->upload->initialize($config_gbr);
			if ($this->upload->do_upload('gambar')) {
				$data['gambar'] = $this->upload->file_name;
			}

			$data['id_item']           = $id_item;
			$data['id_tipe_kendaraan'] = $id_tipe_kendaraan;
			$data['id_warna']          = $this->input->post('id_warna');
			$data['keterangan']        = $this->input->post('keterangan');
			$link_youtube              = $data['link_youtube'] = $this->input->post('link_youtube');
			$preview                   = $this->input->post('preview');
			if ($preview == '') {
				if ($link_youtube == '') {
					$preview = 'video';
				} else {
					$preview = 'link_youtube';
				}
			}
			$data['preview'] = $preview;
			$data['video']             = $video;

			if ($this->input->post('active') == '1') {
				$data['active'] 					= $this->input->post('active');
			} else {
				$data['active'] 					= "";
			}
			$data['created_at']				= $waktu;
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel, $data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/item/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
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
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/item'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel, $pk, $id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$data['dt_item'] = $this->m_admin->kondisi($tabel, $d);
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "edit";
		$this->template($data);
	}
	public function update()
	{
		$waktu 		= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');

		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id_);
		if ($cek->num_rows() == 0 or $id == $id_) {
			$dt = $cek->row();
			$config['upload_path']    = './assets/panel/item_video/';
			$config['allowed_types'] = 'mp4|3gp|flv|mkv|wmv';
			$config['max_size']      = '20480';
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name']  = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('video')) {
				$video           = $this->upload->file_name;
				$data['video'] = $video;
				if ($dt->video != '' or $dt->video != NULL) {
					if (file_exists(FCPATH . "assets/panel/item_video/" . $dt->video)) {
						unlink("assets/panel/item_video/" . $dt->video); //Hapus Gambar
					}
				}
			}

			$data['id_item']           = $this->input->post('id_item');
			$data['id_tipe_kendaraan'] = $this->input->post('id_tipe_kendaraan');
			$data['id_warna']          = $this->input->post('id_warna');
			$data['keterangan']        = $this->input->post('keterangan');
			$link_youtube              = $data['link_youtube']      = $this->input->post('link_youtube');
			$preview                   = $this->input->post('preview');

			$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
			$id_item = $this->input->post('id_item');
			$path = "./uploads/unit/$id_tipe_kendaraan";
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
			}

			$config_gbr['upload_path']   = $path;
			$config_gbr['allowed_types'] = 'jpg|png|jpeg|bmp';
			$config_gbr['max_size']      = '2048';
			$config_gbr['file_name']     = $id_item;
			$config_gbr['remove_spaces'] = TRUE;
			$config_gbr['overwrite']     = TRUE;
			$this->load->library('upload', $config_gbr);
			$this->upload->initialize($config_gbr);
			if ($this->upload->do_upload('gambar')) {
				$data['gambar'] = $this->upload->file_name;
			}

			if ($this->input->post('active') == '1') {
				$data['active'] 					= $this->input->post('active');
			} else {
				$data['active'] 					= "";
			}
			if ($preview == '') {
				if ($link_youtube == '') {
					$preview = 'video';
				} else {
					$preview = 'link_youtube';
				}
			}
			$data['preview'] = $preview;
			$data['updated_at']				= $waktu;
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel, $data, $pk, $id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/item'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}
