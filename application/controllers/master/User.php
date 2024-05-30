<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	var $tables =   "ms_user";
	var $folder =   "master";
	var $page		=		"user";
	var $pk     =   "id_user";
	var $title  =   "Master Data User";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_master_user', 'm_user');
		//===== Load Library =====
		$this->load->library('upload');

		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
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

	public function index_new()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view";
		$data['dt_user'] = $this->db->query("SELECT ms_user.*,ms_user_group.user_group 
						FROM ms_user 
						LEFT JOIN ms_user_group ON ms_user.id_user_group = ms_user_group.id_user_group
						WHERE ms_user.jenis_user != 'Super Admin' AND ms_user.jenis_user != 'Admin'");
		$this->template($data);
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view_new";
		$this->template($data);
	}

	public function fetch_data_prospek_datatables()
	{
		$this->load->model('m_users', 'm_user_new');
		$list = $this->m_user_new->get_datatables();
		
		$data = array();
		$no = $_POST['start'];

        foreach($list as $row) {       
			$tombol_edit    = '<a href="master/user/edit?id='.$row->id_user.'"><button type="button" class="btn btn-info btn-sm btn-flat" title="Edit"><i class="fa fa-pencil"></i></button></a>';
			$tombol_view 	= '<a href="master/user/view?id='.$row->id_user.'"><button type="button" class="btn btn-primary btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>';
			$tombol  = $tombol_edit .' '. $tombol_view;

			if ($row->jenis_user == 'Dealer') {
				$user = $this->db->query("SELECT ms_karyawan_dealer.id_karyawan_dealer,ms_divisi.divisi,ms_jabatan.jabatan,(SELECT nama_dealer FROM ms_dealer WHERE id_dealer=ms_karyawan_dealer.id_dealer) AS dealer,
				(SELECT kode_dealer_md FROM ms_dealer WHERE id_dealer=ms_karyawan_dealer.id_dealer) AS kode_dealer FROM ms_karyawan_dealer                     
				LEFT JOIN ms_divisi ON ms_karyawan_dealer.id_divisi = ms_divisi.id_divisi
				LEFT JOIN ms_jabatan ON ms_karyawan_dealer.id_jabatan = ms_jabatan.id_jabatan 
				WHERE ms_karyawan_dealer.id_karyawan_dealer = '$row->id_karyawan_dealer'");
			} else {
				$user = $this->db->query("SELECT ms_karyawan.id_karyawan,ms_divisi.divisi,ms_jabatan.jabatan,'MAIN DEALER' AS dealer,'' AS kode_dealer FROM ms_karyawan                     
				LEFT JOIN ms_divisi ON ms_karyawan.id_divisi = ms_divisi.id_divisi
				LEFT JOIN ms_jabatan ON ms_karyawan.id_jabatan = ms_jabatan.id_jabatan 
				WHERE ms_karyawan.id_karyawan = '$row->id_karyawan_dealer'");
			}

			if ($user->num_rows() > 0) {
				$u            = $user->row();
				$nama_lengkap = $row->nama_lengkap;
				$divisi       = $u->divisi;
				$jabatan      = $u->jabatan;
				$dealer       = $u->dealer;
				$kode_dealer  = $u->kode_dealer;
			} else {
				$nama_lengkap = "";
				$divisi       = "";
				$jabatan      = "";
				$dealer       = "";
				$kode_dealer = '';
			}
				
			if ($row->status == 'online') {
				$status = "<span class='label label-success'>online</span>";
			} else {
				$status = "<span class='label label-danger'>offline</span>";
			}
			if ($row->username == NULL) {
				$status = '';
			}

			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->username .'<br>'.$status;
			$rows[] = $row->username_sc;
			$rows[] = $nama_lengkap;
			$rows[] = $jabatan;
			$rows[] = $row->user_group;
			$rows[] = $kode_dealer;
			$rows[] = $dealer;
			$rows[] = $tombol;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_user_new->count_all(),
			"recordsFiltered" => $this->m_user_new->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan", "kelurahan", "ASC");
		$data['dt_karyawan'] = $this->m_admin->getSortCond("ms_karyawan_dealer", "nama_lengkap", "ASC");
		$data['set']		= "form";
		$data['mode']		= "insert";
		// send_json($data);
		$this->template($data);
	}
	public function get_user_group()
	{
		$id_user_group		= $this->input->post('id_user_group');
		$dt_user_level		= $this->m_admin->getByID("ms_user_level", "id_user_group", $id_user_group);
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_user_level->result() as $row) {
			$data .= "<option value='$row->id_user_level'>$row->user_level</option>\n";
		}
		echo $data;
	}
	public function get_slot()
	{
		$jenis_user	= $this->input->post('jenis_user');
		$id_dealer	= $this->input->post('id_dealer');
		$data = '';
		if ($jenis_user == 'Main Dealer') {
			$rt = $this->db->query("SELECT * FROM ms_karyawan WHERE active = '1' ORDER BY nama_lengkap ASC");
			$data .= "<option value=''>- choose -</option>";
			foreach ($rt->result() as $val) {
				$data .= "<option value='$val->id_karyawan'>$val->id_karyawan | $val->npk | $val->nama_lengkap</option>\n";
			}
		} else {
			$rt = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND active = '1' ORDER BY nama_lengkap ASC");
			$data .= "<option value=''>- choose -</option>";
			foreach ($rt->result() as $val) {
				$data .= "<option value='$val->id_karyawan_dealer'>$val->id_karyawan_dealer | $val->id_flp_md | $val->nama_lengkap</option>\n";
			}
		}
		echo $data;
	}
	public function save()
	{
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id					= $this->session->userdata('id_user');
		$post = $this->input->post();
		// send_json($post);

		$insert['id_karyawan_dealer'] = $this->input->post('id_karyawan');
		$insert['jenis_user']         = $this->input->post('jenis_user');
		$insert['jenis_user_bagian']  = $this->input->post('jenis_user_bagian');
		$insert['input_date']         = $waktu;
		$insert['input_username']     = $login_id;

		$f_cek = ['id_karyawan_dealer' => $post['id_karyawan'],
				  'jenis_user' => $post['jenis_user']];
		$this->m_user->cekRegistrasiKaryawan($f_cek);

		if (isset($post['akses_sc'])) {
			$filter = ['username_sc' => $post['username_sc']];
			$cek_user = $this->m_user->getUser($filter);
			if ($cek_user->num_rows() > 0) {
				$rsp = [
					'status' => 'error',
					'pesan' => 'This username service concept has been registered'
				];
				send_json($rsp);
			}
			$insert['akses_sc']    = 1;
			$insert['username_sc'] = $post['username_sc'];
			$insert['password_sc'] = md5($post['password_sc']);
			$insert['role_sc']     = $post['role_sc'];
			$insert['active_sc']         = $post['active_sc'];
		} else {
			$insert['akses_sc'] = 0;
		}

		if (isset($post['akses_dms'])) {
			$filter = ['username' => $post['username']];
			$cek_user = $this->m_user->getUser($filter);
			if ($cek_user->num_rows() > 0) {
				$rsp = [
					'status' => 'error',
					'pesan' => 'This username DMS has been registered'
				];
				send_json($rsp);
			}
			$config['upload_path'] 		= './assets/panel/images/user/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
			$config['max_size']				= '2000';
			$config['max_width']  		= '2000';
			$config['max_height']  		= '1024';

			$this->upload->initialize($config);
			if (!$this->upload->do_upload('avatar')) {
				$avatar	= "";
			} else {
				$avatar	= $this->upload->file_name;
			}
			$insert['akses_dms']      = 1;
			$insert['username']       = $post['username'];
			$insert['password']       = md5($post['password']);
			$insert['admin_password'] = $this->input->post('admin_password');
			$insert['id_user_group']  = $this->input->post('id_user_group');
			$insert['avatar']         = $avatar;
			$insert['active']         = $post['active'];
		} else {
			$insert['akses_dms'] = 0;
		}
		// send_json($insert);
		$this->m_admin->insert($tabel, $insert);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		$rsp = [
			'status' => 'sukses',
			'link' => base_url('master/' . $this->page)
		];
		send_json($rsp);
	}
	public function delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel, $pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->m_admin->delete($tabel, $pk, $id);
			$_SESSION['pesan'] 	= "Data has been deleted successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/user'>";
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
		$f_user = ['id_user' => $id];
		$data['row'] = $this->m_user->getUser($f_user)->row();
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan", "kelurahan", "ASC");
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE active = 1 ORDER BY nama_lengkap ASC");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "form";
		$data['mode']	= "edit";
		// send_json($data);
		$this->template($data);
	}
	public function updatez()
	{
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');

		$config['upload_path'] 		= './assets/panel/images/user/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';

		$this->upload->initialize($config);
		if ($this->upload->do_upload('avatar')) {
			$data['avatar'] = $this->upload->file_name;

			$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
			if ($one->avatar != "") {
				unlink("assets/panel/images/user/" . $one->avatar); //Hapus Gambar
			}
		}

		//$data['id_kelurahan'] 	= $this->input->post('id_kelurahan');		
		$data['id_karyawan_dealer'] = $this->input->post('id_karyawan');
		$data['id_user_group']      = $this->input->post('id_user_group');
		$data['jenis_user']         = $this->input->post('jenis_user');
		$data['username']           = $this->input->post('username');
		$data['jenis_user_bagian']  = $this->input->post('jenis_user_bagian');
		$data['akses_sc']           = $this->input->post('akses_sc') == 'on' ? 1 : 0;
		$data['akses_dms']           = $this->input->post('akses_dms') == 'on' ? 1 : 0;
		$data['role_sc']           = $data['akses_sc'] == 1 ? $this->input->post('role_sc') : NULL;
		if ($this->input->post('active') == '1') {
			$data['active']	= $this->input->post('active');
		} else {
			$data['active'] 				= "";
		}
		if ($this->input->post('password') <> '') {
			$data['password'] 			= md5($this->input->post('password'));
		}
		if ($this->input->post('admin_password') <> '') {
			$data['admin_password']	= $this->input->post('admin_password');
		}

		// send_json($data);
		$this->m_admin->update($tabel, $data, $pk, $id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		$rsp = [
			'status' => 'sukses',
			'link' => base_url('master/' . $this->page)
		];
		send_json($rsp);
	}
	public function update()
	{
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id					= $this->session->userdata('id_user');
		$post = $this->input->post();
		// send_json($post);

		$update['id_karyawan_dealer'] = $this->input->post('id_karyawan');
		// $update['active']             = $this->input->post('active');
		$update['jenis_user']         = $this->input->post('jenis_user');
		$update['jenis_user_bagian']  = $this->input->post('jenis_user_bagian');
		$update['update_date']        = $waktu;
		$update['input_date']         = $waktu;
		$update['input_username']     = $login_id;

		//Get Data User & Cek Registrasi Karyawan
		$f_user = ['id_user' => $post['id']];
		$user = $this->m_user->getUser($f_user)->row();
		if ($user->id_karyawan_dealer != $post['id_karyawan']) {
			$f_cek = ['id_karyawan_dealer' => $post['id_karyawan']];
			$this->m_user->cekRegistrasiKaryawan($f_cek);
		}

		if (isset($post['akses_sc'])) {
			$filter = ['username_sc' => $post['username_sc']];
			$cek_user = $this->m_user->getUser($filter);
			if ($cek_user->num_rows() > 0) {
				$filter = ['id_user' => $post['id']];
				$cek_user_by_id = $this->m_user->getUser($filter)->row();
				if ($cek_user_by_id->username_sc != $post['username_sc']) {
					$rsp = [
						'status' => 'error',
						'pesan' => 'This username service concept has been registered'
					];
					send_json($rsp);
				} else {
					$user = $cek_user->row();
				}
			}
			$update['akses_sc']     = 1;
			$update['username_sc'] = $post['username_sc'];
			if ($post['password_sc'] != '') {
				$update['password_sc'] = md5($post['password_sc']);
			}
			$update['role_sc']     = $post['role_sc'];
			$update['active_sc']   = $post['active_sc'];
		} else {
			$update['akses_sc'] = 0;
			$update['active_sc'] = 0;
			$update['username_sc'] = NULL;
			$update['password_sc'] = NULL;
			$update['role_sc']     = NULL;
		}

		$filter = [
			'id_user' => $post['id']
		];
		$cek_user = $this->m_user->getUser($filter);
		if (isset($post['akses_dms'])) {
			if ($cek_user->num_rows() > 0) {
				$usr_old = $cek_user->row();
				$filter = [
					'username' => $post['username']
				];
				$cek_user = $this->m_user->getUser($filter);
				if ($cek_user->num_rows() > 0) {
					if ($usr_old->username != $post['username']) {
						$rsp = [
							'status' => 'error',
							'pesan' => 'This username DMS has been registered'
						];
						send_json($rsp);
					}
				}
			} else {
				$rsp = [
					'status' => 'error',
					'pesan' => 'ID User not found'
				];
				send_json($rsp);
			}
			$config['upload_path'] 		= './assets/panel/images/user/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
			$config['max_size']				= '2000';
			$config['max_width']  		= '2000';
			$config['max_height']  		= '1024';

			$this->upload->initialize($config);
			if (!$this->upload->do_upload('avatar')) {
				$avatar	= $usr_old->avatar;
			} else {
				$avatar	= $this->upload->file_name;
			}
			$update['akses_dms']      = 1;
			$update['username']       = $post['username'];
			if ($post['password'] != '') {
				$update['password']       = md5($post['password']);
			}
			if ($post['admin_password'] != '') {
				$update['admin_password'] = $this->input->post('admin_password');
			}
			$update['id_user_group']  = $this->input->post('id_user_group');
			$update['avatar']         = $avatar;
			$update['active']   = $post['active'];
		} else {
			if ($cek_user->num_rows() > 0) {
				$usr_old = $cek_user->row();
				$file_path = "assets/panel/images/user/" . $usr_old->avatar;
				if (!($usr_old->avatar == NULL or $usr_old->avatar == '')) {
					if (file_exists(FCPATH . $file_path)) {
						unlink($file_path); //Hapus Gambar
					}
				}
			}
			$update['akses_dms']      = 0;
			$update['active']         = 0;
			$update['username']       = NULL;
			$update['password']       = NULL;
			$update['admin_password'] = NULL;
			$update['id_user_group']  = NULL;
			$update['avatar']         = NULL;
		}
		// send_json($update);
		$this->db->update($tabel, $update, ['id_user' => $post['id']]);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		$rsp = [
			'status' => 'sukses',
			'link' => base_url('master/' . $this->page)
		];
		send_json($rsp);
	}
	public function view()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$data['row'] = $this->db->query("SELECT ms_user.*,ms_user_group.user_group,kd.id_dealer 
						FROM ms_user 
						LEFT JOIN ms_user_group ON ms_user.id_user_group = ms_user_group.id_user_group
						LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=ms_user.id_karyawan_dealer
						WHERE ms_user.id_user = '$id'")->row();
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan", "kelurahan", "ASC");
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE active = 1 ORDER BY nama_lengkap ASC");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "form";
		$data['mode']	= "detail";
		$this->template($data);
	}
}
