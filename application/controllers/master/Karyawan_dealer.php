<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan_dealer extends CI_Controller
{

	var $tables =   "ms_karyawan_dealer";
	var $folder =   "master";
	var $page		=		"karyawan_dealer";
	var $pk     =   "id_karyawan_dealer";
	var $title  =   "Master Data Karyawan Dealer";

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

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view";
		// $data['dt_karyawan_dealer'] = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.nama_dealer,ms_divisi.divisi,ms_jabatan.jabatan,ms_agama.agama
		// 														FROM ms_karyawan_dealer LEFT JOIN ms_dealer 
		// 														ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer LEFT JOIN ms_divisi 
		// 														ON ms_karyawan_dealer.id_divisi=ms_divisi.id_divisi LEFT JOIN ms_jabatan
		// 														ON ms_karyawan_dealer.id_jabatan=ms_jabatan.id_jabatan LEFT JOIN ms_agama
		// 														ON ms_karyawan_dealer.id_agama=ms_agama.id_agama ORDER BY id_karyawan_dealer,nama_lengkap ASC LIMIT 10");							
		$this->template($data);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data->result() as $rs) {
			$sub_array       = array();
			$button          = "";
			// $btn_ajukan = "<a data-toggle='tooltip' title='Ajukan' href='dealer/klaim_proposal/ajukan?id=$rs->id_sales_order&jp=$rs->jenis_program&ip_md=$rs->id_program_md'><button class='btn btn-flat btn-xs btn-info'>Ajukan</button></a>";
			// $button .= "<a href=\"master/karyawan_dealer/delete?id=$rs->id_karyawan_dealer\"><button type=\"button\" class=\"btn btn-danger btn-sm btn-flat btn-xs\" title=\"Delete\" onclick=\"return confirm('Are you sure want to delete this data?')\"><i class=\"fa fa-trash\"></i></button></a>";
			$button .= "<a href=\"master/karyawan_dealer/edit?id=$rs->id_karyawan_dealer\" class=\"btn btn-primary btn-sm btn-flat btn-xs\"><i class=\"fa fa-edit\"></i></a> ";
			$button .= " <a href=\"master/karyawan_dealer/view?id=$rs->id_karyawan_dealer\" class=\"btn btn-info btn-sm btn-flat btn-xs\"><i class=\"fa fa-eye\"></i></a>";
			$active = '';
			if ($rs->active == '1') {
				// $button = $btn_ajukan;
				$active = "<i class='glyphicon glyphicon-ok'></i>";
			}
			$sub_array[] = $rs->id_karyawan_dealer;
			$sub_array[] = $rs->id_flp_md;
			$sub_array[] = $rs->nik;
			$sub_array[] = $rs->nama_lengkap;
			$sub_array[] = $rs->nama_dealer;
			$sub_array[] = $rs->divisi;
			$sub_array[] = $rs->jabatan;
			$sub_array[] = $rs->no_telp;
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

	function make_query($no_limit = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$order_column = array('id_karyawan_dealer', 'id_flp_md', 'nik', 'nama_lengkap', 'nama_dealer', 'divisi', 'jabatan', 'no_telp', null, null);
		$limit        = "LIMIT $start,$length";
		$order        = 'ORDER BY id_karyawan_dealer,nama_lengkap ASC';
		$search       = $this->input->post('search')['value'];
		$searchs      = "WHERE 1=1 ";

		if ($search != '') {
			$searchs .= " AND (id_karyawan_dealer LIKE '%$search%' 
	          OR id_flp_md LIKE '%$search%'
	          OR nik LIKE '%$search%'
	          OR nama_lengkap LIKE '%$search%'
	          OR nama_dealer LIKE '%$search%'
	          OR divisi LIKE '%$search%'
	          OR jabatan LIKE '%$search%'
	          OR ms_karyawan_dealer.no_telp LIKE '%$search%'
	          )
	      ";
		}

		if (isset($_POST["order"])) {
			$order_clm = $order_column[$_POST['order']['0']['column']];
			$order_by  = $_POST['order']['0']['dir'];
			$order     = "ORDER BY $order_clm $order_by";
		}

		if ($no_limit == 'y') $limit = '';

		return $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.nama_dealer,ms_divisi.divisi,ms_jabatan.jabatan,ms_agama.agama
   			FROM ms_karyawan_dealer LEFT JOIN ms_dealer
   			ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer LEFT JOIN ms_divisi
   			ON ms_karyawan_dealer.id_divisi=ms_divisi.id_divisi LEFT JOIN ms_jabatan
   			ON ms_karyawan_dealer.id_jabatan=ms_jabatan.id_jabatan LEFT JOIN ms_agama
   			ON ms_karyawan_dealer.id_agama=ms_agama.id_agama 
   		 	$searchs $order $limit 
   		 ");
	}
	function get_filtered_data()
	{
		return $this->make_query('y')->num_rows();
	}

	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['dt_divisi'] = $this->m_admin->getSortCond("ms_divisi", "id_divisi", "ASC");
		$data['dt_jabatan'] = $this->m_admin->getSortCond("ms_jabatan", "id_jabatan", "ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		// $data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer", "nama_dealer", "ASC");
		$data['dt_dealer'] = $this->m_admin->getListDealer("id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer", '','nama_dealer','ASC');
		$data['dt_pos'] = $this->m_admin->getSortCond("ms_pos_dealer", "nama_pos", "ASC");
		$data['dt_training'] = $this->m_admin->getSortCond("ms_training", "training", "ASC");
		$data['set']		= "form";
		$data['mode']		= "insert";
		$this->template($data);
	}
	public function get_karyawan_dealer_group()
	{
		$id_karyawan_dealer_group		= $this->input->post('id_karyawan_dealer_group');
		$dt_karyawan_dealer_level		= $this->m_admin->getByID("ms_karyawan_dealer_level", "id_karyawan_dealer_group", $id_karyawan_dealer_group);
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_karyawan_dealer_level->result() as $row) {
			$data .= "<option value='$row->id_karyawan_dealer_level'>$row->karyawan_dealer_level</option>\n";
		}
		echo $data;
	}
	public function cari_dealer()
	{
		$id_dealer		= $this->input->post('id_dealer');
		$dt_pos				= $this->m_admin->getByID("ms_pos_dealer", "id_dealer", $id_dealer);
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_pos->result() as $row) {
			$data .= "<option value='$row->id_pos_dealer'>$row->nama_pos</option>\n";
		}
		echo $data;
	}
	public function t_kerja()
	{
		$id = $this->input->post('id_karyawan_dealer');
		$dq = "SELECT ms_karyawan_dealer_kerja.*,ms_dealer.nama_dealer FROM ms_karyawan_dealer_kerja INNER JOIN ms_dealer ON ms_karyawan_dealer_kerja.id_dealer=ms_dealer.id_dealer
						WHERE ms_karyawan_dealer_kerja.id_karyawan_dealer = '$id'";
		$data['dt_kerja'] = $this->db->query($dq);
		$this->load->view('master/t_kerja', $data);
	}
	public function delete_kerja()
	{
		$id 		= $this->input->post('id_karyawan_dealer_kerja');
		$da 		= "DELETE FROM ms_karyawan_dealer_kerja WHERE id_karyawan_dealer_kerja = '$id'";
		$this->db->query($da);
		echo "nihil";
	}
	public function save_kerja()
	{
		$id_karyawan_dealer	= $this->input->post('id_karyawan_dealer');
		$id_dealer					= $this->input->post('id_dealer');
		$c 			= $this->db->query("SELECT * FROM ms_karyawan_dealer_kerja WHERE id_karyawan_dealer ='$id_karyawan_dealer' AND id_dealer = '$id_dealer'");
		if ($c->num_rows() == 0) {
			$data['id_karyawan_dealer']		= $this->input->post('id_karyawan_dealer');
			$data['id_dealer']			= $this->input->post('id_dealer');
			$data['tgl_masuk']			= $this->input->post('tgl_masuk');
			$data['tgl_keluar']		= $this->input->post('tgl_keluar');
			$this->m_admin->insert('ms_karyawan_dealer_kerja', $data);
			echo "nihil";
		} else {
			echo "nothing";
		}
	}

	public function t_training()
	{
		$id = $this->input->post('id_karyawan_dealer');
		$dq = "SELECT * FROM ms_karyawan_dealer_training WHERE ms_karyawan_dealer_training.id_karyawan_dealer = '$id'";
		$data['dt_training'] = $this->db->query($dq);
		$this->load->view('master/t_training', $data);
	}
	public function delete_training()
	{
		$id 		= $this->input->post('id_karyawan_dealer_training');
		$da 		= "DELETE FROM ms_karyawan_dealer_training WHERE id_karyawan_dealer_training = '$id'";
		$this->db->query($da);
		echo "nihil";
	}
	public function save_training()
	{
		$id_karyawan_dealer	= $this->input->post('id_karyawan_dealer');
		$training					= $this->input->post('training');
		$c 			= $this->db->query("SELECT * FROM ms_karyawan_dealer_training WHERE id_karyawan_dealer ='$id_karyawan_dealer' AND training = '$training'");
		if ($c->num_rows() == 0) {
			$data['id_karyawan_dealer']		= $this->input->post('id_karyawan_dealer');
			$data['training']			= $this->input->post('training');
			$data['tgl_mulai']			= $this->input->post('tgl_mulai');
			$data['tgl_selesai']		= $this->input->post('tgl_selesai');
			$this->m_admin->insert('ms_karyawan_dealer_training', $data);
			echo "nihil";
		} else {
			echo "nothing";
		}
	}

	public function save()
	{
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id					= $this->session->userdata('id_user');

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$id_karyawan_dealer = $data['id_karyawan_dealer'] = $this->input->post('id_karyawan_dealer');
			$data['honda_id']           = $this->input->post('honda_id');
			$data['nik']                = $this->input->post('nik');
			$data['id_flp_md']          = $this->input->post('id_flp_md');
			$data['nama_lengkap']       = $this->input->post('nama_lengkap');
			$data['id_dealer']          = $this->input->post('id_dealer');
			$data['id_pos_dealer']      = $this->input->post('id_pos_dealer');
			$data['id_divisi']          = $this->input->post('id_divisi');
			$data['id_jabatan']         = $this->input->post('id_jabatan');
			$data['tempat_lahir']       = $this->input->post('tempat_lahir');
			$data['tgl_lahir']          = $this->input->post('tgl_lahir');
			$data['alamat']             = $this->input->post('alamat');
			$data['id_agama']           = $this->input->post('id_agama');
			$data['jk']                 = $this->input->post('jk');
			$data['no_telp']            = $this->input->post('no_telp');
			$data['no_hp']              = $this->input->post('no_hp');
			$data['email']              = $this->input->post('email');
			$data['tgl_masuk']          = $this->input->post('tgl_masuk');
			$data['tgl_keluar']         = $this->input->post('tgl_keluar');
			$data['alasan_keluar']      = $this->input->post('alasan_keluar');
			if ($this->input->post('active') == '1') {
				$data['active']				= $this->input->post('active');
			} else {
				$data['active'] 			= "";
			}
			if (isset($_POST['riwayats'])) {
				$riwayat = $this->input->post('riwayats');
				foreach ($riwayat as $key => $val) {
					$ins_riwayat[] = [
						'id_karyawan_dealer' => $id_karyawan_dealer,
						'id_dealer'  => $val['id_dealer'],
						'tgl_masuk'  => $val['tgl_masuk'],
						'tgl_keluar' => $val['tgl_keluar']
					];
				}
			}
			if (isset($_POST['trainings'])) {
				$training = $this->input->post('trainings');
				foreach ($training as $key => $val) {
					$ins_training[] = [
						'id_karyawan_dealer' => $id_karyawan_dealer,
						'id_training'  => $val['id_training'],
						'tgl_training'  => $val['tgl_training'],
						'no_sertifikat' => $val['no_sertifikat'],
						'nilai' => $val['nilai'],
						'keterangan' => $val['keterangan']
					];
				}
			}
			$data['created_at']			= $waktu;
			$data['created_by']			= $login_id;
			// echo json_encode($ins_training);
			// exit;

			$this->db->trans_begin();
			$this->db->insert('ms_karyawan_dealer', $data);
			if (isset($ins_riwayat)) {
				$this->db->insert_batch('ms_karyawan_dealer_kerja', $ins_riwayat);
			}
			if (isset($ins_training)) {
				$this->db->insert_batch('ms_karyawan_dealer_training', $ins_training);
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
					'link' => base_url('master/karyawan_dealer')
				];
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h2/rekap_claim_waranty'>";
			}
			echo json_encode($rsp);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_dealer/add'>";
		} else {
			$rsp = [
				'status' => 'duplikat',
				'pesan' => "ID Karyawan Dealer $id sudah ada !"
			];
			echo json_encode($rsp);

			// $_SESSION['pesan'] 	= "Duplicate entry for primary key";
			// $_SESSION['tipe'] 	= "danger";
			// echo "<script>history.go(-1)</script>";
		}
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
			$this->db->trans_begin();
			$this->db->delete($tabel, array($pk => $id));
			$this->db->trans_commit();
			$result = 'Success';

			if ($this->db->trans_status() === FALSE) {
				$result = 'You can not delete this data because it already used by the other tables';
				$_SESSION['tipe'] 	= "danger";
			} else {
				$this->m_admin->delete("ms_karyawan_dealer_kerja", $pk, $id);
				$this->m_admin->delete("ms_karyawan_dealer_training", $pk, $id);
				$this->m_admin->delete("ms_user", $pk, $id);
				$result = 'Data has been deleted succesfully';
				$_SESSION['tipe'] 	= "success";
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/karyawan_dealer'>";
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
		$tabel                      = $this->tables;
		$pk                         = $this->pk;
		$page                       = $this->page;
		$id                         = $this->input->get('id');
		$d                          = array($pk => $id);
		$data['dt_divisi']          = $this->m_admin->getSortCond("ms_divisi", "id_divisi", "ASC");
		$data['dt_jabatan']         = $this->m_admin->getSortCond("ms_jabatan", "id_jabatan", "ASC");
		$data['dt_agama']           = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		//$data['dt_dealer']          = $this->m_admin->getSortCond("ms_dealer", "nama_dealer", "ASC");
		$data['dt_dealer'] = $this->m_admin->getListDealer("id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer", '','nama_dealer','ASC');
		$data['row'] = $this->m_admin->getByID($tabel, $pk, $id)->row();
		$data['dt_training']        = $this->m_admin->getSortCond("ms_training", "training", "ASC");
		$data['isi']                = $this->page;
		$data['title']              = 'Edit ' . $this->title;
		$data['set']                = "form";
		$data['mode']               = "edit";
		$data['trainings'] = $this->db->query("SELECT ms_karyawan_dealer_training.*,
							 training
							 FROM ms_karyawan_dealer_training
							 JOIN ms_training ON ms_training.id_training=ms_karyawan_dealer_training.id_training
							 WHERE ms_karyawan_dealer_training.id_karyawan_dealer='$id'
							")->result();
		$data['riwayats'] = $this->db->query("SELECT ms_karyawan_dealer_kerja.*,nama_dealer,kode_dealer_md
							 FROM ms_karyawan_dealer_kerja
							 JOIN ms_dealer ON ms_dealer.id_dealer=ms_karyawan_dealer_kerja.id_dealer
							 WHERE ms_karyawan_dealer_kerja.id_karyawan_dealer='$id'
							")->result();
		$this->template($data);
	}

	public function save_edit()
	{
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id					= $this->session->userdata('id_user');

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$id_karyawan_dealer_old = $this->input->post('id_karyawan_dealer_old');
		$cek 				= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer='$pk' AND id_karyawan_dealer<>'$id_karyawan_dealer_old'")->num_rows();
		if ($cek == 0) {
			$id_karyawan_dealer = $data['id_karyawan_dealer'] = $this->input->post('id_karyawan_dealer');
			$data['honda_id']           = $this->input->post('honda_id');
			$data['nik']                = $this->input->post('nik');
			$data['id_flp_md']          = $this->input->post('id_flp_md');
			$data['nama_lengkap']       = $this->input->post('nama_lengkap');
			$data['id_dealer']          = $this->input->post('id_dealer');
			$data['id_pos_dealer']      = $this->input->post('id_pos_dealer');
			$data['id_divisi']          = $this->input->post('id_divisi');
			$data['id_jabatan']         = $this->input->post('id_jabatan');
			$data['tempat_lahir']       = $this->input->post('tempat_lahir');
			$data['tgl_lahir']          = $this->input->post('tgl_lahir');
			$data['alamat']             = $this->input->post('alamat');
			$data['id_agama']           = $this->input->post('id_agama');
			$data['jk']                 = $this->input->post('jk');
			$data['no_telp']            = $this->input->post('no_telp');
			$data['no_hp']              = $this->input->post('no_hp');
			$data['email']              = $this->input->post('email');
			$data['tgl_masuk']          = $this->input->post('tgl_masuk');
			$data['tgl_keluar']         = $this->input->post('tgl_keluar');
			$data['alasan_keluar']      = $this->input->post('alasan_keluar');
			if ($this->input->post('active') == '1') {
				$data['active']				= $this->input->post('active');
			} else {
				$data['active'] 			= "";
			}
			if (isset($_POST['riwayats'])) {
				$riwayat = $this->input->post('riwayats');
				foreach ($riwayat as $key => $val) {
					$ins_riwayat[] = [
						'id_karyawan_dealer' => $id_karyawan_dealer,
						'id_dealer'  => $val['id_dealer'],
						'tgl_masuk'  => $val['tgl_masuk'],
						'tgl_keluar' => $val['tgl_keluar']
					];
				}
			}
			if (isset($_POST['trainings'])) {
				$training = $this->input->post('trainings');
				foreach ($training as $key => $val) {
					$ins_training[] = [
						'id_karyawan_dealer' => $id_karyawan_dealer,
						'id_training'  => $val['id_training'],
						'tgl_training'  => $val['tgl_training'],
						'no_sertifikat' => $val['no_sertifikat'],
						'nilai' => $val['nilai'],
						'keterangan' => $val['keterangan']
					];
				}
			}
			$data['updated_at']			= $waktu;
			$data['updated_by']			= $login_id;
			// echo json_encode($ins_training);
			// exit;

			$this->db->trans_begin();
			$this->db->update('ms_karyawan_dealer', $data, ['id_karyawan_dealer' => $id_karyawan_dealer]);

			$this->db->delete('ms_karyawan_dealer_kerja', ['id_karyawan_dealer' => $id_karyawan_dealer_old]);
			$this->db->delete('ms_karyawan_dealer_training', ['id_karyawan_dealer' => $id_karyawan_dealer_old]);

			if (isset($ins_riwayat)) {
				$this->db->insert_batch('ms_karyawan_dealer_kerja', $ins_riwayat);
			}
			if (isset($ins_training)) {
				$this->db->insert_batch('ms_karyawan_dealer_training', $ins_training);
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
					'link' => base_url('master/karyawan_dealer')
				];
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h2/rekap_claim_waranty'>";
			}
			echo json_encode($rsp);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_dealer/add'>";
		} else {
			$rsp = [
				'status' => 'duplikat',
				'pesan' => "ID Karyawan Dealer $id sudah ada !"
			];
			echo json_encode($rsp);

			// $_SESSION['pesan'] 	= "Duplicate entry for primary key";
			// $_SESSION['tipe'] 	= "danger";
			// echo "<script>history.go(-1)</script>";
		}
	}

	public function view()
	{
		$tabel                      = $this->tables;
		$pk                         = $this->pk;
		$page                       = $this->page;
		$id                         = $this->input->get('id');
		$d                          = array($pk => $id);
		$data['dt_divisi']          = $this->m_admin->getSortCond("ms_divisi", "id_divisi", "ASC");
		$data['dt_jabatan']         = $this->m_admin->getSortCond("ms_jabatan", "id_jabatan", "ASC");
		$data['dt_agama']           = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		$data['dt_dealer']          = $this->m_admin->getSortCond("ms_dealer", "nama_dealer", "ASC");
		$data['row'] = $this->m_admin->getByID($tabel, $pk, $id)->row();
		$data['dt_training']        = $this->m_admin->getSortCond("ms_training", "training", "ASC");
		$data['isi']                = $this->page;
		$data['title']              = 'Detail ' . $this->title;
		$data['set']                = "form";
		$data['mode']               = "detail";
		$data['trainings'] = $this->db->query("SELECT ms_karyawan_dealer_training.*,
							 training
							 FROM ms_karyawan_dealer_training
							 JOIN ms_training ON ms_training.id_training=ms_karyawan_dealer_training.id_training
							 WHERE ms_karyawan_dealer_training.id_karyawan_dealer='$id'
							")->result();
		$data['riwayats'] = $this->db->query("SELECT ms_karyawan_dealer_kerja.*,nama_dealer,kode_dealer_md
							 FROM ms_karyawan_dealer_kerja
							 JOIN ms_dealer ON ms_dealer.id_dealer=ms_karyawan_dealer_kerja.id_dealer
							 WHERE ms_karyawan_dealer_kerja.id_karyawan_dealer='$id'
							")->result();
		$this->template($data);
	}

	public function proses_import()
	{
		$filenya = 'uploads/import_karyawan.xlsx';
        	include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        // Fungsi untuk melakukan proses upload file
        $return = array();
        $this->load->library('upload'); // Load librari upload

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = 'import_karyawan';

        $this->upload->initialize($config); // Load konfigurasi uploadnya
        if($this->upload->do_upload('import_file')){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            // return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            // return $return;
        }
        // print_r($return);exit();

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load($filenya); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = array();
        $error = '';

       

        $numrow = 1;
        foreach($sheet as $row){
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport

            if($numrow > 1){
                // Kita push (add) array data ke variabel data

            	if (!empty($row['B']) AND !empty($row['D']) ) {

            		//validasi array 
            		$id_flp_md = $row['B'];
        			$cek_id_flp_md = $this->db->get_where('ms_karyawan_dealer',array('id_flp_md'=> $id_flp_md));
        			if ($cek_id_flp_md->num_rows() > 0) {

        				// tambahkan list error
        				$error .= "<b>ID FLP MD : $id_flp_md </b> sudah ada di database <br> silahkan dicek kembali dan import ulang.";
        				$_SESSION['pesan'] 	= $error;
						$_SESSION['tipe'] 	= "error";
						echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_dealer'>";
						exit();
        				
        			} else {
        				$id_dealer = get_data('ms_dealer','kode_dealer_md',$row['D'],'id_dealer');

	            		array_push($data, array(

	            			'id_karyawan_dealer'=> $row['B'],
							'id_flp_md'			=> $row['B'],
							'nama_lengkap'		=> $row['C'],
							'id_dealer'			=> $id_dealer,
							'id_pos_dealer'		=> $row['E'],
							'id_divisi'			=> $row['F'],
							'id_jabatan'		=> $row['G'],
							'nik'				=> $row['H'],
							'tempat_lahir'		=> $row['I'],
							'tgl_lahir'			=> $row['L'].'-'.$row['K'].'-'.$row['J'],
							'alamat'			=> $row['M'],
							'id_agama'			=> $row['N'],
							'jk'				=> $row['O'],
							'no_hp'				=> $row['P'],
							'no_telp'			=> $row['Q'],
							'email'				=> $row['R'],
							'tgl_masuk'			=> $row['U'].'-'.$row['T'].'-'.$row['S'],
							'created_at'		=> get_waktu(),
							'created_by'		=> $this->session->userdata('id_user'),
							'active'			=> 1,
	            		));
        			}
            	}
            }

            $numrow++; // Tambah 1 setiap kali looping
        }

        $simpan = $this->db->insert_batch('ms_karyawan_dealer', $data);
        unlink($filenya);
        if ($simpan) {
        	$_SESSION['pesan'] 	= "Data has been import successfully <br>";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_dealer'>";
        } else {
        	$_SESSION['pesan'] 	= $error;
			$_SESSION['tipe'] 	= "error";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/karyawan_dealer'>";
        }

	}
}
