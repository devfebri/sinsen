<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lkh extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head    =   "id_";
	var $table_det  =   "tr_";
	var $pk_det     =   "id_";
	var $folder     =   "h2";
	var $page       =		"lkh";
	var $title      =   "LKH";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_md_claim', 'm_claim');
		$this->load->model('m_h2_work_order', 'm_wo');
		//===== Load Library =====
		// $this->load->library('upload');

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
// 		$data['dt_result'] = $this->db->query("SELECT tr_lkh.*,nama_dealer,kode_dealer_md FROM tr_lkh 
// 			JOIN ms_dealer ON tr_lkh.id_dealer=ms_dealer.id_dealer
// 			ORDER BY tr_lkh.created_at DESC");
	    $data['dt_result'] = $this->db->query("SELECT lkh.*, dealer.nama_dealer,dealer.kode_dealer_md from tr_lkh lkh join ms_dealer dealer on lkh.id_dealer=dealer.id_dealer order by lkh.tgl_lkh DESC ");
		$this->template($data);
	}


	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		//$data['dt_lokasi'] = $this->db->query("SELECT * FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang=ms_gudang.id_gudang WHERE ms_lokasi_unit.qty > ms_lokasi_unit.isi AND ms_lokasi_unit.active = '1' ORDER BY ms_lokasi_unit.id_lokasi_unit,ms_gudang.gudang ASC");		
		$data['set']		= "form";
		$data['mode']		= "insert";
		$params['bulan_awal'] = gmdate("Y-m", time() + 60 * 60 * 7);
		$data['statistik'] = $this->m_claim->get_statistik_6_terakhir();
		// send_json($data);
		$this->template($data);
	}

	function refresh_statistik()
	{
		$post = $this->input->post();
		$params = [
			'bulan_awal' => gmdate("Y-m", time() + 60 * 60 * 7),
			'id_part_5' => substr($post['id_part'], 0, 5)
		];
		// send_json($params);
		$statistik = $this->m_claim->get_statistik_6_terakhir($params);
		$response = ['status' => 'sukses', 'data' => $statistik];
		send_json($response);
	}

	public function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_lkh    = $this->m_claim->get_id_lkh();
		$id_dealer = $this->input->post('id_dealer');

		$config['upload_path']   = './assets/panel/lkh_file';
		$config['allowed_types'] = 'jpg|png|jpeg|bmp';
		$config['max_size']      = '2048';
		$config['max_width']     = '3000';
		$config['max_height']    = '3000';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name']  = TRUE;
		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_ilustrasi')) {
			$file_ilustrasi = "gagal";
		} else {
			$file_ilustrasi = $this->upload->file_name;
		}

		$data 	= [
			'id_lkh'             => $id_lkh,
			'tgl_lkh'            => date_ymd($this->input->post('tgl_lkh')),
			'id_dealer'          => $this->input->post('id_dealer'),
			'tech_serv_ahm'      => $this->input->post('tech_serv_ahm'),
			'cc_tech_serv_ahm'   => $this->input->post('cc_tech_serv_ahm'),
			'kode_model'         => $this->input->post('kode_model'),
			'tema'               => $this->input->post('tema'),
			'part_utama'         => $this->input->post('part_utama'),
			'ongkos_kerja'       => $this->input->post('ongkos_kerja'),
			'symptom_code'       => $this->input->post('symptom_code'),
			'grade'              => $this->input->post('grade'),
			'pelapor'            => $this->input->post('pelapor'),
			'kepala_bengkel'     => $this->input->post('kepala_bengkel'),
			'gejala'             => $this->input->post('gejala'),
			'diagnosis'          => $this->input->post('diagnosis'),
			'penyebab_utama'     => $this->input->post('penyebab_utama'),
			'tindakan_sementara' => $this->input->post('tindakan_sementara'),
			'tgl_pembelian'      => date_ymd($this->input->post('tgl_pembelian')),
			'tgl_kejadian'       => date_ymd($this->input->post('tgl_kejadian')),
			'jam'                => $this->input->post('jam'),
			'km'                 => $this->input->post('km'),
			'no_mesin'           => $this->input->post('no_mesin'),
			'no_rangka'          => $this->input->post('no_rangka'),
			'kategori_claim'     => $this->input->post('kategori_claim'),
			'id_work_order'      => isset($_POST['id_work_order']) ? $_POST['id_work_order'] : null,
			'file_ilustrasi'     => $file_ilustrasi,
			'status'             => 'input',
			'created_at'         => $waktu,
			'created_by'         => $login_id
		];
		$periode = $this->input->post('periode');
		$bulan = $this->input->post('bulan');
		$jml_kejadian = $this->input->post('jml_kejadian');
		foreach ($periode as $key => $val) {
			$statistik[] = [
				'id_lkh' => $id_lkh,
				'periode'      => $val,
				'bulan'        => $bulan[$key],
				'jml_kejadian' => $jml_kejadian[$key],
			];
		}
		$id_part = $this->input->post('id_part');
		if (isset($is_part)) {
			foreach ($id_part as $key => $prt) {
				$ins_part[] = [
					'id_lkh' => $id_lkh,
					'id_part'        => $prt
				];
			}
		}
		// $tes = ['data' => $data, 'statistik' => $statistik, 'ins_part' => $ins_part];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_lkh', $data);
		if (isset($statistik)) {
			$this->db->insert_batch('tr_lkh_statistik', $statistik);
		}
		if (isset($ins_part)) {
			$this->db->insert_batch('tr_lkh_part_terkait', $ins_part);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('h2/lkh')
			//   ];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lkh'>";
		}
		// echo json_encode($rsp);
	}

	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_lkh = $this->input->get('id');
		$filter = ['id_lkh' => $id_lkh];
		$row = $this->m_claim->getLKH($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['dt_statistik'] = $this->db->query("SELECT tr_lkh_statistik.* FROM tr_lkh_statistik
				WHERE id_lkh='$id_lkh'
				")->result();
			$data['part_terkait']		= $this->m_claim->getLKHPartTerkait($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "detail";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lkh'>";
		}
	}

	public function edit()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$id_lkh = $this->db->escape_str($this->input->get('id'));
		$filter = ['id_lkh' => $id_lkh];
		$row = $this->m_claim->getLKH($filter);
		if ($row->num_rows() > 0) {
			$row = $data['row'] = $row->row();
			$data['dt_statistik'] = $this->db->query("SELECT tr_lkh_statistik.* FROM tr_lkh_statistik
				WHERE id_lkh='$id_lkh'
				")->result();
			$data['part_terkait']		= $this->m_claim->getLKHPartTerkait($filter)->result();
			$data['set']		= "form";
			$data['mode']		= "edit";
			// send_json($data);
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lkh'>";
		}
	}

	public function save_edit()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');

		$id_lkh    = $this->input->post('id_lkh');
		$id_dealer = $this->input->post('id_dealer');
		$lkh = $this->db->get_where('tr_lkh', ['id_lkh' => $id_lkh])->row();

		$config['upload_path']   = './assets/panel/lkh_file';
		$config['allowed_types'] = 'jpg|png|jpeg|bmp';
		$config['max_size']      = '2048';
		$config['max_width']     = '3000';
		$config['max_height']    = '3000';
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name']  = TRUE;
		$this->load->library('upload');
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_ilustrasi')) {
			$file_ilustrasi = $lkh->file_ilustrasi;
		} else {
			if (file_exists(FCPATH . "assets/panel/lkh_file/" . $lkh->file_ilustrasi)) {
				unlink("assets/panel/lkh_file/" . $lkh->file_ilustrasi); //Hapus Gambar
			}
			$file_ilustrasi = $this->upload->file_name;
		}

		$data 	= [
			'id_dealer'          => $this->input->post('id_dealer'),
			'tech_serv_ahm'      => $this->input->post('tech_serv_ahm'),
			'cc_tech_serv_ahm'   => $this->input->post('cc_tech_serv_ahm'),
			'kode_model'         => $this->input->post('kode_model'),
			'tema'               => $this->input->post('tema'),
			'part_utama'         => $this->input->post('part_utama'),
			'ongkos_kerja'         => $this->input->post('ongkos_kerja'),
			'symptom_code'       => $this->input->post('symptom_code'),
			'grade'              => $this->input->post('grade'),
			'pelapor'            => $this->input->post('pelapor'),
			'kepala_bengkel'     => $this->input->post('kepala_bengkel'),
			'gejala'             => $this->input->post('gejala'),
			'diagnosis'          => $this->input->post('diagnosis'),
			'penyebab_utama'     => $this->input->post('penyebab_utama'),
			'tindakan_sementara' => $this->input->post('tindakan_sementara'),
			'tgl_pembelian'      => date_ymd($this->input->post('tgl_pembelian')),
			'tgl_kejadian'      => date_ymd($this->input->post('tgl_kejadian')),
			'jam'                => $this->input->post('jam'),
			'km'                 => $this->input->post('km'),
			'no_mesin'           => $this->input->post('no_mesin'),
			'no_rangka'          => $this->input->post('no_rangka'),
			'kategori_claim'              => $this->input->post('kategori_claim'),
			'id_work_order'      => isset($_POST['id_work_order']) ? $_POST['id_work_order'] : null,
			'file_ilustrasi'     => $file_ilustrasi,
			'status'             => 'input',
			'updated_at'         => $waktu,
			'updated_by'         => $login_id
		];
		$periode      = $this->input->post('periode');
		$bulan        = $this->input->post('bulan');
		$jml_kejadian = $this->input->post('jml_kejadian');
		foreach ($periode as $key => $val) {
			$statistik[] = [
				'id_lkh' => $id_lkh,
				'periode'      => $val,
				'bulan'        => $bulan[$key],
				'jml_kejadian' => $jml_kejadian[$key],
			];
		}
		// echo json_encode($data);
		// exit;			 
		$this->db->trans_begin();
		$this->db->update('tr_lkh', $data, ['id_lkh' => $id_lkh]);
		$this->db->delete('tr_lkh_statistik', ['id_lkh' => $id_lkh]);
		if (isset($statistik)) {
			$this->db->insert_batch('tr_lkh_statistik', $statistik);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong !";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			//    	$rsp = ['status'=> 'sukses',
			// 'link'=>base_url('h2/lkh')
			//   ];
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h2/lkh'>";
		}
		// echo json_encode($rsp);
	}
}
