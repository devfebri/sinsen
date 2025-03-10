<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Prospek extends CI_Controller
{
	var $tables =   "tr_prospek";
	var $folder =   "dealer";
	var $page		=		"prospek";
	var $pk     =   "id_prospek";
	var $title  =   "Data Prospek";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_kelurahan');
		$this->load->model('m_h1_dealer_prospek', 'm_prospek');
		//===== Load Library =====
		$this->load->library('upload');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
		if ($this->session->userdata('id_user')==106) {
			// echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek_crm'>";
			//redirect ('dealer/prospek_crm');
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
	public function index_old()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= 'view';
		$filter = ['id_customer_in_spk' => false];
		$data['dt_prospek'] = $this->m_prospek->getProspek($filter);
		$this->template($data);
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= 'view_new2';
		$this->template($data);
	}

	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= 'history';
		$filter = ['id_customer_in_spk' => true];
		$data['dt_prospek'] = $this->m_prospek->getProspek($filter);
		$this->template($data);
	}
	public function gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= 'view_gc';
		$id_dealer      = $this->m_admin->cari_dealer();
		$data['dt_prospek'] = $this->db->query("SELECT tr_prospek_gc.*,ms_karyawan_dealer.nama_lengkap FROM tr_prospek_gc LEFT JOIN ms_dealer ON tr_prospek_gc.id_dealer=ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
			LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan
			LEFT JOIN ms_kecamatan ON tr_prospek_gc.id_kecamatan=ms_kecamatan.id_kecamatan
			LEFT JOIN ms_kabupaten ON tr_prospek_gc.id_kabupaten=ms_kabupaten.id_kabupaten
			LEFT JOIN ms_provinsi ON tr_prospek_gc.id_provinsi=ms_provinsi.id_provinsi
			WHERE tr_prospek_gc.active = '1' AND tr_prospek_gc.id_dealer = '$id_dealer' ORDER BY tr_prospek_gc.atur_tgl DESC");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function getDetail()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id 		= $this->input->post('id');
		if ($id == null or $id == '') {
			$data['detail'] = $this->db->query("SELECT tr_prospek_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_prospek_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_prospek_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna
			 	WHERE tr_prospek_gc_kendaraan.status='new' AND tr_prospek_gc_kendaraan.created_by='$login_id'");
		} else {
			$data['detail'] = $this->db->query("SELECT tr_prospek_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_prospek_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_prospek_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna
				WHERE id_prospek_gc='$id'");
		}
		$data['id'] = $id == null ? 0 : $id;
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_prospek_gc', $data);
	}
	public function addDetail()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id_prospek_gc = $this->input->post('id_prospek_gc');
		if ($id_prospek_gc <> '') {
			$data['id_prospek_gc']			= $this->input->post('id_prospek_gc');
			$data['status']					= 'input';
		} else {
			$data['status']					= 'new';
		}
		$data['id_tipe_kendaraan']	= $id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');
		$data['id_warna'] = $id_warna			= $this->input->post('id_warna');
		$data['qty']				= $this->input->post('qty');
		$data['tahun']				= $this->input->post('tahun');
		$data['created_by']				= $login_id;
		$data['created_at']				= $waktu;
		$cek = $this->db->query("SELECT * From tr_prospek_gc_kendaraan WHERE created_by='$login_id' AND status='new' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
		if ($cek->num_rows() > 0) {
			echo "Tipe dan Warna ini sudah dipilih sebelumnya";
		} else {
			$this->m_admin->insert("tr_prospek_gc_kendaraan", $data);
			echo "nihil";
		}
	}
	public function saveEdit()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$data['id_tipe_kendaraan']	= $id_tipe_kendaraan		= $this->input->post('id_tipe_kendaraan');
		$data['id_warna'] = $id_warna			= $this->input->post('id_warna');
		$data['qty']				= $this->input->post('qty');
		$data['tahun']				= $this->input->post('tahun');
		$id				= $this->input->post('id_gc');
		$this->m_admin->update("tr_prospek_gc_kendaraan", $data, "id_prospek_gc_kendaraan", $id);
		echo "nihil";

		// $cek = $this->db->query("SELECT * FROM tr_prospek_gc_kendaraan WHERE created_by='$login_id' AND status='new' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
		// if($cek->num_rows() == 1){
		// 	echo "Tipe dan Warna ini sudah dipilih sebelumnya";
		// }else{
		// }
	}
	public function delDetail()
	{
		$id			= $this->input->post('id');
		$this->m_admin->delete("tr_prospek_gc_kendaraan", 'id_prospek_gc_kendaraan', $id);
		echo "nihil";
	}

	public function download_prospek()
	{
		$data['id_dealer'] = $this->m_admin->cari_dealer();
		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');
		$this->load->view('dealer/laporan/lap_prospek_all', $data);
	}

	public function edit_popup()
	{
		$id_gc = $this->input->post("id_gc");
		$data['isi']    = $this->page;
		$data['dt_gc']	= $this->db->query("SELECT * FROM tr_prospek_gc_kendaraan LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_prospek_gc_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna 
			WHERE tr_prospek_gc_kendaraan.id_prospek_gc_kendaraan = '$id_gc'")->row();
		$data['title']	= $this->title;
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view("dealer/t_prospek_gc_edit.php", $data);
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$data['mode']		= "insert";
		// $data['dt_prospek'] = $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.id_karyawan_dealer FROM tr_prospek LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		// $data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.nama_dealer FROM ms_karyawan_dealer 
		// 	LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
		// 	WHERE ms_karyawan_dealer.id_dealer = '$id_dealer'
		// 	 AND id_flp_md <> '' AND ms_karyawan_dealer.active='1' 
		// 	 AND ms_karyawan_dealer.id_jabatan IN ('JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099','JBT-109')
		// ORDER BY nama_lengkap ASC");


		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,ms_dealer.nama_dealer FROM ms_karyawan_dealer 
			LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
			WHERE ms_karyawan_dealer.id_dealer = '$id_dealer'
			 AND id_flp_md <> '' AND ms_karyawan_dealer.active='1' 
			 AND ms_karyawan_dealer.id_jabatan IN ('JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099','JBT-109')
		ORDER BY nama_lengkap ASC");

		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "id_pendidikan", "ASC");
		// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");
		
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		/* $data['dt_no_mesin'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
						INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND tr_scan_barcode.status = '4'"); 
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC");*/
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}
	public function add_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= "insert_gc";
		$data['mode']		= "insert";
		// $data['dt_prospek'] = $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.id_karyawan_dealer FROM tr_prospek LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND id_flp_md <> ''  AND ms_karyawan_dealer.active='1' AND (ms_karyawan_dealer.id_jabatan IN ('JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099','JBT-109') or id_flp_md ='127250') ORDER BY nama_lengkap ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "id_pendidikan", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		/* $data['dt_no_mesin'] = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
						INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND tr_scan_barcode.status = '4'"); 
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC"); */
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}
	public function cari_id()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT ms_dealer.id_dealer, ms_dealer.kode_dealer_md FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 	= $isi->kode_dealer_md;
		$id_dealer 		= $isi->id_dealer;
		$pr_num 			= $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4)='$tahun' ORDER BY id_prospek DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 5;
			$id 	= substr($row->id_prospek, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		$rt = rand(1111, 9999);
		$tgl						= $this->input->post('tgl');
		$th 						= date("y");
		$waktu 					= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$id_dealer 			= $this->m_admin->cari_dealer();
		$pr_num 				= $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4)='$tahun' ORDER BY id_list_appointment DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_list_appointment) - 5;
			$id 	= substr($row->id_list_appointment, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . "0" . $id;
			}
			$kode2 = "PR" . $kode1;
		} else {
			$kode2 = "PR" . $th . "00001";
		}

		echo $kode . "|" . $rt . "|" . $kode2;
	}
	public function ajax_list()
	{
		$list = $this->m_kelurahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan", "id_kecamatan", $isi->id_kecamatan);
			if ($cek->num_rows() > 0) {
				$t = $cek->row();
				$kecamatan = $t->kecamatan;
			} else {
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->kelurahan;
			$row[] = $kecamatan;
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_kelurahan->count_all(),
			"recordsFiltered" => $this->m_kelurahan->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	public function cari_id_real()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$id_dealer 		= $this->m_admin->cari_dealer();
		$isi 				= $this->db->query("SELECT ms_dealer.kode_dealer_md FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4) = '$tahun' ORDER BY id_prospek DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 5;
			$id 	= substr($row->id_prospek, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		//$rt = rand(1111,9999);
		$rt = $this->m_admin->get_customer();
		$id_dealer      = $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		} else {
			$get_dealer = '';
			$panjang = '';
		}
		$tgl						= $this->input->post('tgl');
		$th 						= date("y");
		$waktu 					= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$pr_num 				= $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE RIGHT(id_list_appointment,$panjang) = '$get_dealer' and left(created_at,4) = '$tahun' ORDER BY id_list_appointment DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_list_appointment) - ($panjang + 6);
			$id 	= substr($row->id_list_appointment, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . "0" . $id;
			}
			$kode2 = "PR" . $kode1 . "-" . $get_dealer;
		} else {
			$kode2 = "PR" . $th . "00001-" . $get_dealer;
		}

		return array('kode' => $kode, 'rt' => $rt, 'kode2' => $kode2);
		//echo $kode."|".$rt."|".$kode2;
	}
	public function cari_id_gc()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$tahun = date("Y");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$id_dealer 		= $this->m_admin->cari_dealer();
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md . "GC";
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek_gc WHERE id_dealer = '$id_dealer' and left(created_at,4) = '$tahun' ORDER BY id_prospek_gc DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek_gc) - 5;
			$id 	= substr($row->id_prospek_gc, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		return $kode;
	}
	public function take_sales()
	{
		$id_karyawan_dealer	= anti_injection($this->input->post('id_karyawan_dealer'));
		$dt_eks				= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer = '$id_karyawan_dealer'");
		if ($dt_eks->num_rows() > 0) {
			$da = $dt_eks->row();
			$kode = $da->id_flp_md;
			$nama = $da->nama_lengkap;
		} else {
			$kode = "";
			$nama = "";
		}
		echo $kode . "|" . $nama;
	}

	/*
	public function take_kec_OLD()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kelurahan 		= $dt_kel->kelurahan;
		$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan 		= $dt_kec->kecamatan;
		$id_kabupaten = $dt_kec->id_kabupaten;
		$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= $dt_kab->kabupaten;
		$id_provinsi  = $dt_kab->id_provinsi;
		$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  		= $dt_pro->provinsi;

		echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $dt_kel->kode_pos . "|" . $kelurahan;
	}
	*/
	
	public function take_kec()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');
		// $data = $this->db->query("SELECT kelurahan, id_kecamatan, kode_pos FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'");

		$sql = 'SELECT kelurahan, id_kecamatan, kode_pos FROM ms_kelurahan WHERE id_kelurahan = ?';
		$data = $this->db->query($sql, array(htmlentities($id_kelurahan)));
		if($data->num_rows()>0){
			$dt_kel			= $data->row();
			$kelurahan 		= $dt_kel->kelurahan;
			$kode_pos 		= $dt_kel->kode_pos;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$dt_kec				= $this->db->query("SELECT id_kabupaten, kecamatan FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
			$kecamatan 		= $dt_kec->kecamatan;
			$id_kabupaten = $dt_kec->id_kabupaten;
			$dt_kab				= $this->db->query("SELECT id_provinsi, kabupaten FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
			$kabupaten  	= $dt_kab->kabupaten;
			$id_provinsi  = $dt_kab->id_provinsi;
			$dt_pro				= $this->db->query("SELECT provinsi FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
			$provinsi  		= $dt_pro->provinsi;

			echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $kode_pos . "|" . $kelurahan;
		}else{
			echo  "|||||||";
		}
	}

	public function save()
	{
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek/add'>";
	}
	
	public function save_off()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$r = "";
			$isi_ktp = $this->input->post('no_ktp');
			$ktp = strlen($isi_ktp);
			if ($ktp < 16) {
				$jum = 16 - $ktp;
				for ($i = 1; $i <= $jum; $i++) {
					$r = $r . "0";
				}
				$ktp_f = $r . $isi_ktp;
			} else {
				$ktp_f = $isi_ktp;
			}
			$isi													= $this->cari_id_real();
			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			$data['id_customer'] 					= $isi['rt'];
			$data['nama_konsumen'] 				= $this->input->post('nama_konsumen');
			$data['no_ktp'] 							= $ktp_f;
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['no_kk'] 								= $this->input->post('no_kk');
			$data['jenis_wn'] 						= $this->input->post('jenis_wn');
			$data['jenis_kelamin'] 				= $this->input->post('jenis_kelamin');
			$data['tempat_lahir']					= $this->input->post('tempat_lahir');
			$data['tgl_lahir'] 						= $this->input->post('tgl_lahir');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$data['agama'] 								= $this->input->post('agama');
			//$data['penanggung_jawab'] 		= $this->input->post('penanggung_jawab');	
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['no_telp'] 							= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_telp'));
			$data['merk_sebelumnya'] 			= $this->input->post('merk_sebelumnya');
			$data['jenis_sebelumnya'] 		= $this->input->post('jenis_sebelumnya');
			$data['tipe_sebelumnya'] 		= $this->input->post('tipe_sebelumnya');
			$data['pemakai_motor'] 				= $this->input->post('pemakai_motor');
			$data['email'] 								= $this->input->post('email');
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$status_prospek = $data['status_prospek'] 			= $this->input->post('status_prospek');
			$data['id_tipe_kendaraan'] 		= $this->input->post('id_tipe_kendaraan');
			$data['id_warna'] 						= $this->input->post('id_warna');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['facebook'] 						= $this->input->post('facebook');
			$data['youtube'] 							= $this->input->post('youtube');
			$data['twitter'] 							= $this->input->post('twitter');
			$data['instagram'] 						= $this->input->post('instagram');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			$data['id_kelurahan_kantor'] 	= $this->input->post('id_kelurahan_kantor');
			$cek_req_instansi = $this->db->get_where('ms_sub_pekerjaan', array('id_sub_pekerjaan'=>$this->input->post('sub_pekerjaan')));
			if ($cek_req_instansi->num_rows() > 0) {
				$req_instansi = $cek_req_instansi->row()->required_instansi;
				if ($req_instansi == '1') {
					if ($this->input->post('id_kelurahan_kantor') == '') {
						$_SESSION['pesan'] 	= "kelurahan Kantor tidak boleh kosong";
						$_SESSION['tipe'] 	= "danger";
						echo "<script>history.go(-1)</script>";
						exit();
					}
				}
			}
			
			$data['prioritas_prospek'] 		= $this->input->post('prioritas_prospek');
			$data['program_umum'] 				= $this->input->post('program_utama') != '' ? $this->input->post('program_utama') : NULL;
			$data['program_gabungan'] 		= $this->input->post('program_gabungan') != '' ? $this->input->post('program_gabungan') : NULL;
			$data['id_list_appointment']  = $isi['kode2'];
			$data['created_at']						= $waktu;
			$data['created_by']						= $login_id;
			// $data['jenis_pembelian'] 			= $this->input->post('jenis_pembelian');	
			$data['pekerjaan'] 						= $this->input->post('pekerjaan');
			$data['sub_pekerjaan'] 						= $this->input->post('sub_pekerjaan');
			$data['pekerjaan_lain'] 						= $this->input->post('lain');
			$data['nama_tempat_usaha'] 						= $this->input->post('nama_usaha');
			// $data['pengeluaran_bulan'] 		= $this->input->post('pengeluaran_bulan');	
			// $data['pendidikan'] 					= $this->input->post('pendidikan');	
			// $data['digunakan'] 						= $this->input->post('digunakan');	
			// $data['sedia_hub'] 						= $this->input->post('sedia_hub');	
			// $data['status_rumah'] 				= $this->input->post('status_rumah');	
			// $data['no_mesin'] 						= $this->input->post('no_mesin');	
			// $data['no_rangka'] 						= $this->input->post('no_rangka');	
			// $data['tahun_rakit'] 					= $this->input->post('tahun_rakit');	

			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$sumber_prospek = $this->input->post('sumber_prospek');
			$id_prospek = $data['id_prospek'] 					= $this->m_prospek->getIDProspek($sumber_prospek);
			$data['tgl_prospek']          = $this->input->post('tgl_prospek');
			$data['longitude']            = $this->input->post('longitude');
			$data['latitude']             = $this->input->post('latitude');
			$data['alamat_kantor']        = $this->input->post('alamat_kantor');
			$data['no_telp_kantor']       = $this->input->post('no_telp_kantor');
			$data['jenis_customer']       = $this->input->post('jenis_customer');
			$data['sumber_prospek']       = $sumber_prospek;
			$data['test_ride_preference'] = $this->input->post('test_ride_preference');
			$data['rencana_pembayaran']   = $this->input->post('rencana_pembayaran');
			$data['catatan']              = $this->input->post('catatan');
			$data['keterangan_not_deal']  = $this->input->post('keterangan_not_deal');
			$data['id_event']  = $this->input->post('id_event');
			$tgl_fol_up    = $this->input->post('tgl_fol_up');
			$waktu_fol_up  = $this->input->post('waktu_fol_up');
			$metode_fol_up = $this->input->post('metode_fol_up');
			$keterangan    = $this->input->post('keterangan');
			if (count($tgl_fol_up)) {
				foreach ($tgl_fol_up as $key => $val) {
					$dt_fol_up[] = [
						'id_prospek' => $id_prospek,
						'tgl_fol_up' => $val,
						'waktu_fol_up'  => $waktu_fol_up[$key],
						'metode_fol_up' => $metode_fol_up[$key],
						'keterangan'    => $keterangan[$key],
					];
				}
			}
			// send_json($data);
			// $this->m_admin->insert($tabel, $data); // DIOFF KARNA PENGINPUTAN ADA DI SERVICE CONCEPT / MOBILE APPS
			if (isset($dt_fol_up)) {
				// $this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up); // DIOFF KARNA PENGINPUTAN ADA DI SERVICE CONCEPT / MOBILE APPS
			} 
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function save_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_prospek_gc";
		$pk					= "id_prospek_gc";
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {

			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			$data['jenis'] 								= $this->input->post('jenis');
			$data['id_kelompok_harga']		= $this->input->post('kelompok_harga');
			$data['nama_npwp']						= $this->input->post('nama_npwp');
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['tgl_berdiri']					= $this->input->post('tgl_berdiri');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$status_prospek = $data['status_prospek'] 			= $this->input->post('status_prospek');
			$data['nama_penanggung_jawab'] = $this->input->post('nama_penanggung_jawab');
			$data['email'] 								= $this->input->post('email');
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			$data['created_at']						= $waktu;
			$data['created_by']						= $login_id;
			$data['status']								= "input";
			$data['id_prospek_gc'] = $id_prospek_gc = $this->cari_id_gc();

			$data['tgl_prospek'] 			= $this->input->post('tgl_prospek');
			$data['id_pekerjaan'] 			= $this->input->post('id_pekerjaan');
			$data['longitude'] 			= $this->input->post('longitude');
			$data['latitude'] 			= $this->input->post('latitude');
			$data['test_ride_preference'] 			= $this->input->post('test_ride_preference');
			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$data['id_event'] 			 = $this->input->post('id_event');
			$data['program_umum'] = $this->input->post('program_umum');
			$data['id_kelurahan_kantor'] = $this->input->post('id_kelurahan_kantor');
			
			$data['alamat_kantor'] = $this->input->post('alamat_kantor');
			$data['no_telp_kantor'] = $this->input->post('no_telp_kantor');
			$data['prioritas_prospek'] = $this->input->post('prioritas_prospek');
			$data['sumber_prospek'] = $this->input->post('sumber_prospek');
			$tgl_fol_up    = $this->input->post('tgl_fol_up');
			$waktu_fol_up  = $this->input->post('waktu_fol_up');
			$metode_fol_up = $this->input->post('metode_fol_up');
			$keterangan    = $this->input->post('keterangan');
			if (count($tgl_fol_up)) {
				foreach ($tgl_fol_up as $key => $val) {
					$dt_fol_up[] = [
						'id_prospek' => $id_prospek_gc,
						'tgl_fol_up' => $val,
						'waktu_fol_up'  => $waktu_fol_up[$key],
						'metode_fol_up' => $metode_fol_up[$key],
						'keterangan'    => $keterangan[$key],
					];
				}
			}


			$lastHeader = $this->db->query("SELECT * From tr_prospek_gc_kendaraan WHERE created_by='$login_id' AND status='new'");
			if ($lastHeader->num_rows() > 0) {
				$this->m_admin->insert("tr_prospek_gc", $data);
				if (isset($dt_fol_up)) {
					$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
				}
				$this->db->query("UPDATE tr_prospek_gc_kendaraan set status='input', id_prospek_gc = '$id_prospek_gc', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek/add_gc'>";
			} else {
				$_SESSION['pesan'] 	= "Detail Tipe Kendaraan tidak boleh kosong";
				$_SESSION['id_kelurahan'] 	= $this->input->post('id_kelurahan');
				$_SESSION['atur_tgl'] 	= $this->input->post('atur_tgl');
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function update_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id_prospek_gc");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0 or $id == $id_) {

			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			$data['jenis'] 								= $this->input->post('jenis');
			$data['id_kelompok_harga']		= $this->input->post('kelompok_harga');
			$data['nama_npwp']						= $this->input->post('nama_npwp');
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['tgl_berdiri']					= $this->input->post('tgl_berdiri');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$status_prospek = $data['status_prospek'] 			= $this->input->post('status_prospek');
			$data['nama_penanggung_jawab'] = $this->input->post('nama_penanggung_jawab');
			$data['email'] 								= $this->input->post('email');
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			$data['updated_at']						= $waktu;
			$data['updated_by']						= $login_id;
			$data['tgl_prospek'] 			= $this->input->post('tgl_prospek');
			$data['id_pekerjaan'] 			= $this->input->post('id_pekerjaan');
			$data['longitude'] 			= $this->input->post('longitude');
			$data['latitude'] 			= $this->input->post('latitude');
			$data['test_ride_preference'] 			= $this->input->post('test_ride_preference');
			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$data['id_event'] 			 = $this->input->post('id_event');
			$data['program_umum'] = $this->input->post('program_umum');
			$data['id_kelurahan_kantor'] = $this->input->post('id_kelurahan_kantor');
			
			$data['alamat_kantor'] = $this->input->post('alamat_kantor');
			$data['no_telp_kantor'] = $this->input->post('no_telp_kantor');
			$data['prioritas_prospek'] = $this->input->post('prioritas_prospek');
			$data['sumber_prospek'] = $this->input->post('sumber_prospek');
			$tgl_fol_up    = $this->input->post('tgl_fol_up');
			$waktu_fol_up  = $this->input->post('waktu_fol_up');
			$metode_fol_up = $this->input->post('metode_fol_up');
			$keterangan    = $this->input->post('keterangan');
			if (count($tgl_fol_up)) {
				foreach ($tgl_fol_up as $key => $val) {
					$dt_fol_up[] = [
						'id_prospek' => $id,
						'tgl_fol_up' => $val,
						'waktu_fol_up'  => $waktu_fol_up[$key],
						'metode_fol_up' => $metode_fol_up[$key],
						'keterangan'    => $keterangan[$key],
					];
				}
			}

			$this->m_admin->update("tr_prospek_gc", $data, "id_prospek_gc", $id);

			$this->db->query("UPDATE tr_prospek_gc_kendaraan set status='input', created_at='$waktu',created_by='$login_id' WHERE id_prospek_gc='$id'");
			$this->db->delete('tr_prospek_fol_up', ['id_prospek' => $id]);

			if (isset($dt_fol_up)) {
				$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
			}
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek/gc'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}

	public function edit()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer FROM ms_karyawan_dealer
			LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
		 WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> '' AND ms_karyawan_dealer.id_jabatan IN ('JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099','JBT-109','JBT-113') AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
		$filter = ['id_prospek' => $id];
		$dt_prospek = $this->m_prospek->getProspek($filter);
		if ($dt_prospek->num_rows() > 0) {
			$row = $dt_prospek->row();
			$data['row']  = $row;
		}
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");
		
		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.warna from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title;
		$data['set']		= "insert";
		$data['mode']		= "edit";
		$data['details'] = $this->db->query("SELECT fup.*, CASE WHEN msfup.name IS NULL THEN metode_fol_up ELSE msfup.name END metode_fol_up FROM tr_prospek_fol_up fup  LEFT JOIN sc_ms_metode_follow_up msfup ON msfup.id=fup.metode_fol_up WHERE id_prospek='$id'")->result();
		// send_json($data['row']);
		$this->template($data);
	}
	public function detail()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= anti_injection($this->input->get('id'));
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT ms_karyawan_dealer.*,nama_dealer FROM ms_karyawan_dealer
			LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer=ms_dealer.id_dealer
		 WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND id_flp_md <> ''  AND ms_karyawan_dealer.active='1' ORDER BY nama_lengkap ASC");
		$filter = ['id_prospek' => $id];
		$dt_prospek = $this->m_prospek->getProspek($filter);
		if ($dt_prospek->num_rows() > 0) {
			$row = $dt_prospek->row();
		    $data['row']  = $row;
		}
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_subpekerjaan'] = $this->db->query("select required_instansi, id_sub_pekerjaan, sub_pekerjaan, active from ms_sub_pekerjaan where active = '1' order by sub_pekerjaan asc");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		// $data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_tipe'] = $this->db->query("select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active ='1' order by tipe_ahm asc");

		$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.warna from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "insert";
		$data['mode']		= "detail";
		$data['details'] = $this->db->query("SELECT fup.*, CASE WHEN msfup.name IS NULL THEN metode_fol_up ELSE msfup.name END metode_fol_up FROM tr_prospek_fol_up fup  LEFT JOIN sc_ms_metode_follow_up msfup ON msfup.id=fup.metode_fol_up WHERE id_prospek='$id'")->result();
		// send_json($data['row']);
		$this->template($data);
	}
	public function edit_gc()
	{
		$tabel		= "tr_prospek_gc";
		$pk 			= "id_prospek_gc";
		$id 	= $data['id']		= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND id_flp_md <> ''  AND ms_karyawan_dealer.active='1' AND ms_karyawan_dealer.id_jabatan IN ('JBT-071','JBT-072','JBT-073','JBT-074','JBT-063','JBT-064','JBT-065','JBT-103','JBT-099','JBT-109') ORDER BY nama_lengkap ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$filter['id_prospek_gc'] = $id;
		$data['dt_prospek_gc'] = $this->m_prospek->getProspekGC($filter);
		if ($data['dt_prospek_gc']->num_rows() > 0) {
			$row = $data['dt_prospek_gc']->row();
		}

		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['details'] = $this->db->query("SELECT fup.*, CASE WHEN msfup.name IS NULL THEN metode_fol_up ELSE msfup.name END metode_fol_up FROM tr_prospek_fol_up fup  LEFT JOIN sc_ms_metode_follow_up msfup ON msfup.id=fup.metode_fol_up WHERE id_prospek='$id'")->result();

		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title . " Group Customer";
		$data['set']		= "edit_gc";
		$data['mode']		= "edit";
		$this->template($data);
	}
	public function update()
	{
		$this->db_crm       = $this->load->database('db_crm', true);


		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		// $cek 				= $this->m_admin->getByID($tabel, $pk, $id_)->num_rows();
		// if ($cek == 0 or $id == $id_) {
		// send_json($this->input->post());
		
		// $is_leads = $this->db_crm->query("select leads_id from leads where idProspek='$id'");
		$is_leads = $this->db->query("select id_prospek, input_from from tr_prospek where id_prospek='$id' and input_from in ('crm','ldd')");
		if($is_leads->num_rows() > 0) {
			$_SESSION['pesan'] 	= "Data Leads ini hanya bisa diedit di Menu Prospek CRM!";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}else{

			$r = "";
			$isi_ktp = $this->input->post('no_ktp');
			$ktp = strlen($isi_ktp);
			if ($ktp < 16) {
				$jum = 16 - $ktp;
				for ($i = 1; $i <= $jum; $i++) {
					$r = $r . "0";
				}
				$ktp_f = $r . $isi_ktp;
			} else {
				$ktp_f = $isi_ktp;
			}
			$isi						= $this->cari_id_real();
			$data['no_ktp'] 							= $ktp_f;
			$data['no_kk'] 								= $this->input->post('no_kk');
			$data['jenis_wn'] 						= $this->input->post('jenis_wn');
			$data['jenis_pembelian'] 			= $this->input->post('jenis_pembelian');
			$data['id_dealer'] 						= $this->m_admin->cari_dealer();
			$data['id_karyawan_dealer'] 	= $this->input->post('id_karyawan_dealer');
			$data['id_flp_md'] 						= $this->input->post('id_flp_md');
			//$data['id_customer'] 					= $this->input->post('id_customer');	
			$data['nama_konsumen'] 				= $this->input->post('nama_konsumen');
			$data['no_npwp'] 							= $this->input->post('no_npwp');
			$data['jenis_kelamin'] 				= $this->input->post('jenis_kelamin');
			$data['tgl_lahir'] 						= $this->input->post('tgl_lahir');
			$data['tempat_lahir']					= $this->input->post('tempat_lahir');
			$data['id_kelurahan'] 				= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 				= $region[1];
			$data['id_kabupaten'] 				= $region[2];
			$data['id_provinsi'] 					= $region[3];
			$data['kodepos'] 							= $this->input->post('kodepos');
			$data['alamat'] 							= $this->input->post('alamat');
			$data['agama'] 								= $this->input->post('agama');
			$data['pekerjaan'] 						= $this->input->post('pekerjaan');
			$data['pengeluaran_bulan'] 		= $this->input->post('pengeluaran_bulan');
			$data['pendidikan'] 					= $this->input->post('pendidikan');
			//$data['penanggung_jawab'] 		= $this->input->post('penanggung_jawab');	
			$data['no_hp'] 								= preg_replace("![^a-z0-9]+!i", "", $this->input->post('no_hp'));
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['sedia_hub'] 						= $this->input->post('sedia_hub');
			$data['merk_sebelumnya'] 			= $this->input->post('merk_sebelumnya');
			$data['jenis_sebelumnya'] 		= $this->input->post('jenis_sebelumnya');
			$data['tipe_sebelumnya'] 		= $this->input->post('tipe_sebelumnya');
			$data['digunakan'] 						= $this->input->post('digunakan');
			$data['pemakai_motor'] 				= $this->input->post('pemakai_motor');
			$data['email'] 								= $this->input->post('email');
			$data['status_rumah'] 				= $this->input->post('status_rumah');
			$data['status_nohp'] 					= $this->input->post('status_nohp');
			$status_prospek = $data['status_prospek'] 			= $this->input->post('status_prospek');
			$data['id_tipe_kendaraan'] 		= $this->input->post('id_tipe_kendaraan');
			$data['no_mesin'] 						= $this->input->post('no_mesin');
			$data['no_rangka'] 						= $this->input->post('no_rangka');
			$data['id_warna'] 						= $this->input->post('id_warna');
			$data['tahun_rakit'] 					= $this->input->post('tahun_rakit');
			$data['atur_tgl'] 						= $this->input->post('atur_tgl');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['facebook'] 						= $this->input->post('facebook');
			$data['youtube'] 							= $this->input->post('youtube');
			$data['twitter'] 							= $this->input->post('twitter');
			$data['instagram'] 						= $this->input->post('instagram');
			$data['atur_jam'] 						= $this->input->post('atur_jam');
			$data['keterangan_fol'] 			= $this->input->post('keterangan_fol');
			//$data['id_list_appointment']  = $this->input->post('id_list_appointment');	
			$data['id_kelurahan_kantor'] 	= $this->input->post('id_kelurahan_kantor');
			$cek_req_instansi = $this->db->get_where('ms_sub_pekerjaan', array('id_sub_pekerjaan'=>$this->input->post('sub_pekerjaan')));
			if ($cek_req_instansi->num_rows() > 0) {
				$req_instansi = $cek_req_instansi->row()->required_instansi;
				if ($req_instansi == '1') {
					if ($this->input->post('id_kelurahan_kantor') == '') {
						$_SESSION['pesan'] 	= "kelurahan Kantor tidak boleh kosong";
						$_SESSION['tipe'] 	= "danger";
						echo "<script>history.go(-1)</script>";
						exit();
					}
				}
			}
			$data['prioritas_prospek'] 		= $this->input->post('prioritas_prospek');
			$data['program_umum'] 				= $this->input->post('program_utama') != '' ? $this->input->post('program_utama') : NULL;
			$data['program_gabungan'] 		= $this->input->post('program_gabungan') != '' ? $this->input->post('program_gabungan') : NULL;
			$data['updated_at']						= $waktu;
			$data['updated_by']						= $login_id;
			if (strtolower($status_prospek) == 'hot' || strtolower($status_prospek) == 'low') {
				$data['status_aktifitas'] = 'In Progress';
			} elseif (strtolower($status_prospek) == 'deal' || strtolower($status_prospek) == 'not deal') {
				$data['status_aktifitas'] = 'Completed';
			} else {
				$data['status_aktifitas'] = 'Not Started';
			}
			$data['tgl_prospek']          = $this->input->post('tgl_prospek');
			$data['longitude']            = $this->input->post('longitude');
			$data['latitude']             = $this->input->post('latitude');
			$data['alamat_kantor']        = $this->input->post('alamat_kantor');
			$data['no_telp_kantor']       = $this->input->post('no_telp_kantor');
			$data['jenis_customer']       = $this->input->post('jenis_customer');
			$data['sumber_prospek']       = $this->input->post('sumber_prospek');
			$data['test_ride_preference'] = $this->input->post('test_ride_preference');
			$data['rencana_pembayaran']   = $this->input->post('rencana_pembayaran');
			$data['catatan']              = $this->input->post('catatan');
			$data['keterangan_not_deal']  = $this->input->post('keterangan_not_deal');
			$data['sub_pekerjaan']              = $this->input->post('sub_pekerjaan');
			$data['pekerjaan_lain']  = $this->input->post('lain');
			$data['nama_tempat_usaha']              = $this->input->post('nama_usaha');
			$tgl_fol_up    = $this->input->post('tgl_fol_up');
			$data['id_event']  = $this->input->post('id_event');
			$waktu_fol_up  = $this->input->post('waktu_fol_up');
			$metode_fol_up = $this->input->post('metode_fol_up');
			$keterangan    = $this->input->post('keterangan');
			if (count($tgl_fol_up) > 0) {
				foreach ($tgl_fol_up as $key => $val) {
					$dt_fol_up[] = [
						'id_prospek' => $id,
						'tgl_fol_up' => $val,
						'waktu_fol_up'  => $waktu_fol_up[$key],
						'metode_fol_up' => $metode_fol_up[$key],
						'keterangan'    => $keterangan[$key],
					];
				}
			}
			// send_json($data);
			$this->m_admin->update($tabel, $data, $pk, $id);
			$this->db->delete('tr_prospek_fol_up', ['id_prospek' => $id]);
			if (isset($dt_fol_up)) {
				$this->db->insert_batch('tr_prospek_fol_up', $dt_fol_up);
			}
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek'>";
			// } else {
			// 	$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			// 	$_SESSION['tipe'] 	= "danger";
			// 	echo "<script>history.go(-1)</script>";
			// }
		}
	}
	public function view()
	{
		$tabel		= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');
		$d 				= array($pk => $id);
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_karyawan'] = $this->m_admin->getSortDealer("ms_karyawan_dealer", "nama_lengkap", "ASC", $id_dealer);
		$data['dt_prospek'] = $this->m_admin->kondisi($tabel, $d);
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan", "kelurahan", "ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		// $data['dt_no_mesin'] = $this->m_admin->getSort("tr_scan_barcode", "no_mesin", "ASC");
		// $data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "detail";
		$this->template($data);
	}
	public function getWarna()
	{
		$id_tipe_kendaraan = anti_injection($this->input->post('id_tipe_kendaraan'));
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC
				";
		$dt_warna = $this->db->query($dq);
		
		$check_ev = $this->m_admin->checkIsEv($id_tipe_kendaraan); 
		$dealer = $this->m_admin->cari_dealer();

		// if ($check_ev > 0 && $dealer =='103'){
		// 	var_dump($check_ev);
		// 	die();
		// 	$_SESSION['pesan'] = "Tipe kendaraan EV | Qty maximal 1 pada 1 SPK";
		// 	$_SESSION['tipe']  = "success";
		// }


		if ($dt_warna->num_rows() > 0) {
			echo "<option value=''>- choose -</option>";
			foreach ($dt_warna->result() as $res) {
				echo "<option value='$res->id_warna' >$res->id_warna | $res->warna</option>";
			}
		}
		
	}
	public function getWarnaEdit()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$dq = "SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC
				";
		$dt_warna = $this->db->query($dq);
		if ($dt_warna->num_rows() > 0) {
			echo "<option value=''>- choose -</option>";
			foreach ($dt_warna->result() as $res) {
				if ($res->id_warna == $id_warna) {
					$select = 'selected';
				} else {
					$select = '';
				}
				echo "<option value='$res->id_warna' $select >$res->id_warna | $res->warna</option>";
			}
		}
	}
	public function cancel_prospek()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "cancel_prospek";
		$data['mode'] = 'cancel';
		$id_dealer       = $this->m_admin->cari_dealer();
		$data['reasons'] = $this->db->get_where('ms_reasons', ['fungsi' => 'Cancel Prospek']);
		$data['prospek'] = $this->db->query("SELECT tr_prospek.*,harga_on_road,harga_off_road,biaya_bbn,tipe_ahm,warna FROM tr_prospek 
			LEFT JOIN tr_spk ON tr_prospek.id_customer=tr_spk.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_prospek.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_warna.id_warna=tr_prospek.id_warna
			WHERE tr_prospek.id_dealer=$id_dealer
			ORDER BY tr_prospek.created_at DESC");
		$this->template($data);
	}
	function save_cancel()
	{
		$waktu                       = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id                    = $this->session->userdata('id_user');
		$id_prospek                  = $this->input->post('id_prospek');

		$data['id_reasons']          = $this->input->post('id_reasons');
		$data['keterangan_not_deal'] = $this->input->post('keterangan_not_deal');
		$data['id_prospek']          = $this->input->post('id_prospek');
		$data['cancel_at']           = $waktu;
		$data['cancel_by']           = $login_id;
		$data['status_aktifitas']    = 'Completed';
		$data['status_prospek']    = 'Not Deal';
		$this->db->update('tr_prospek', $data, ['id_prospek' => $id_prospek]);
		$_SESSION['pesan'] = "Data has been canceled successfully";
		$_SESSION['tipe']  = "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/prospek'>";
	}
	public function notif_outstanding()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Notifikasi List Outstanding Prospek';
		$data['set']   = "notif_outstanding";
		$date          = $this->input->get('tgl');
		$id_dealer     = $this->m_admin->cari_dealer();
		$login_id      = $this->session->userdata('id_user');
		$data['reasons'] = $this->db->get_where('ms_reasons', ['fungsi' => 'Cancel Prospek']);

		$data['prospek'] = $this->db->query("SELECT * FROM tr_prospek 
			WHERE (status_prospek='Hot Prospect' OR status_prospek='Hot Prospek' OR status_prospek='hot' OR status_prospek='low') 
			AND (SELECT COUNT(id_prospek) FROM tr_prospek_fol_up 
			WHERE tr_prospek.id_prospek=id_prospek )>0 
			AND tr_prospek.id_dealer=$id_dealer AND id_karyawan_dealer=(SELECT id_karyawan_dealer FROM ms_user WHERE id_user=$login_id)
			ORDER BY created_at DESC ");
		$this->template($data);
	}
	function getProgramUmum()
	{
		$filter = [
			'id_tipe_kendaraan' => anti_injection($this->input->post('id_tipe_kendaraan')),
			'id_warna' => anti_injection($this->input->post('id_warna')),
			'jenis_beli' => anti_injection($this->input->post('jenis_beli')),
		];
		$result = $this->m_prospek->getProgramUmum($filter);
		if ($result == FALSE) {
			$result = [];
		}
		send_json($result);
	}

	public function getProgramGabungan()
	{
		$id_program_md = $this->input->post('program_utama');
		$jenis_beli = $this->input->post('jenis_beli');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$dt = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();
		// send_json($this->input->post());

		$cek_program = $this->db->get_where('tr_sales_program', ['id_program_md' => $id_program_md]);
		if ($cek_program->num_rows() == 0) {
			$result = [];
			send_json($result);
		} else {
			$cek_program = $cek_program->row();
		}
		//Semua Dealer
		if ($cek_program->kuota_program == 0 || $cek_program->kuota_program == NULL) {
			$cek_dealer = $this->db->get_where('tr_sales_program_dealer', ['id_program_md' => $cek_program->id_program_md]);
			if ($cek_dealer->num_rows() == 0) {
				$tersedia = 1;
			} else {
				$dealer = $this->db->get_where('tr_sales_program_dealer', ['id_program_md' => $id_program_md, 'id_dealer' => $id_dealer]);
				if ($dealer->num_rows() > 0) {
					$dealer = $dealer->row();
					// $cek_terjual=$this->db->query("SELECT * FROM tr_sales_order
					// 							   LEFT JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
					// 							   WHERE tr_spk.program_umum='$id_program_md' 
					// 							   AND tr_sales_order.status_so='so_invoice' 
					// 							   AND tr_sales_order.id_dealer='$id_dealer'
					// 		")->num_rows();
					$cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   -- AND tr_sales_order.status_so='so_invoice' 
		 									   AND tr_spk.id_dealer='$id_dealer'
		 									   AND status_spk<>'closed'
		 				")->num_rows();
					$tersedia = $cek_terjual <= $dealer->kuota ? $dealer->kuota - $cek_terjual : 0;
				} else {
					$tersedia = 0;
				}
			}
		} else {
			// $cek_terjual=$this->db->query("SELECT * FROM tr_sales_order
			// 									   LEFT JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
			// 									   WHERE tr_spk.program_umum='$id_program_md' AND tr_sales_order.status_so='so_invoice'
			// 				")->num_rows();
			$cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   AND status_spk<>'closed'
		 				")->num_rows();
			$tersedia = $cek_terjual <= $cek_program->kuota_program ? $cek_program->kuota_program - $cek_terjual : 0;
		}
		// if ($cek_ketersediaan->num_rows()>0)
		// {
		// 	$cek_k = $cek_ketersediaan->row();
		// 	if ($cek_k->kuota>0) {
		// 		$cek_terjual=$this->db->query("SELECT * FROM tr_sales_order
		// 									   LEFT JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
		// 									   WHERE tr_spk.program_umum='$id_program_md' AND tr_sales_order.status_so='so_invoice'
		// 				")->num_rows();
		// 				//$cek_terjual=11;
		// 		if ($cek_terjual <= $cek_k->kuota) {
		// 			$tersedia=$cek_k->kuota - $cek_terjual;
		// 		}else{
		// 			$tersedia=0;
		// 		}
		// 	}else{
		// 		$tersedia=1;
		// 	}
		// }else{
		// 	$tersedia='kosong';
		// }
		if ($tersedia > 0) {
			$nilai_voucher_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir  AND tr_sales_program.id_program_md='$id_program_md' ");
			if ($nilai_voucher_program->num_rows() > 0) {
				$program = $nilai_voucher_program->row();
				if (strtolower($jenis_beli) == 'cash') {
					$nilai = $nilai_voucher_program->row();
					$nilai_voucher_program = $nilai->tot_cash;
				} elseif ($jenis_beli == 'kredit') {
					$nilai = $nilai_voucher_program->row();
					$nilai_voucher_program = $nilai->tot_kredit;
				}
				if ($program->id_jenis_sales_program == 'SP-002') {
					$nilai_voucher_program = 0;
				}
			} else {
				$nilai_voucher_program = '';
			}
			// echo $nilai_voucher_program . '##';
			// $cek_program = $this->db->query("SELECT * FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan");
			$cek_program = $this->db->query("SELECT DISTINCT(id_program_md)as id_program_md_gabungan FROM(
				SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan='$id_program_md'
				UNION
				SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md IN (SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md_gabungan='$id_program_md') OR id_program_md='$id_program_md' ) as tbl_gabungan
				WHERE id_program_md<>'$id_program_md'
			");
			if (strtolower($jenis_beli) == 'cash') {
				// echo "<option> - choose- </option>";
				$x = 0;
				foreach ($cek_program->result() as $rs) {
					$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md 
					WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' 
					AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' 
					AND id_warna LIKE '%$id_warna%' 
					AND tr_sales_program_tipe.status<>'new' 
					AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir
					");
					if ($cek_program->num_rows() > 0) {
						$ck = $cek_program->row();
						if ($ck->tot_cash > 0) {
							$result[] = ['id_program_md' => $ck->id_program_md, 'judul_kegiatan' => $ck->judul_kegiatan];
							$x++;
						}
					}
				}
				// echo "##$x";
			} elseif (strtolower($jenis_beli) == 'kredit') {
				// echo "<option> - choose- </option>";
				$xx = 0;
				foreach ($cek_program->result() as $rs) {
					$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir");
					if ($cek_program->num_rows() > 0) {
						$ck = $cek_program->row();
						if ($ck->tot_kredit > 0) {
							// echo "<option value='$ck->id_program_md'>$ck->id_program_md | $ck->judul_kegiatan</option>";
							$result[] = ['id_program_md' => $ck->id_program_md, 'judul_kegiatan' => $ck->judul_kegiatan];
						}
					}
					$xx++;
				}
				// echo "##$xx";
			}
			// $program_tipe = $this->db->get_where('tr_sales_program_tipe', ['id_program_md' => $id_program_md])->row();
			// echo "##$program->jenis_barang";
		}
		if (isset($result)) {
			send_json($result);
		} else {
			$result = [];
			send_json($result);
		}
	}

	public function fetch_data_prospek_datatables()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$this->load->model('m_dealer_prospek_datatables');

		$list = $this->m_dealer_prospek_datatables->get_datatables($id_dealer);
		
		$data = array();
		$no = $_POST['start'];

		$id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");

        foreach($list as $row) {       
			// $edit         = $this->m_admin->set_tombol($id_menu, $group, 'edit');
			// $delete       = $this->m_admin->set_tombol($id_menu, $group, 'delete');
			// $approval     = $this->m_admin->set_tombol($id_menu, $group, 'approval');
			// $print        = $this->m_admin->set_tombol($id_menu, $group, 'print');
			$status       = '';
			$tombol_cetak = '';
			$nama = ($row->nama_konsumen);
			$tombol_edit       = "<a href='dealer/prospek/edit?id=$row->id_prospek'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button> </a>";
			$tombol_view ="<a href='dealer/prospek/detail?id=$row->id_prospek'>".$nama." </a>";
			$status = "<span class='label label-success'>$row->status_prospek</span>";
                
			$no++;
			$rows = array();
			$rows[] = $no;
			$rows[] = $row->id_prospek;
			$rows[] = $row->id_customer;
			$rows[] = $tombol_view;
			$rows[] = $row->nama_lengkap;
			$rows[] = $row->no_hp;
			$rows[] = $status;
			$rows[] = $tombol_edit;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_dealer_prospek_datatables->count_all($id_dealer),
			"recordsFiltered" => $this->m_dealer_prospek_datatables->count_filtered($id_dealer),
			"data" => $data,
		);
		echo json_encode($output);
	}
}
