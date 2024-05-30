<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Spk extends CI_Controller
{
	var $tables =   "tr_spk";
	var $folder =   "dealer";
	var $page   =		"spk";
	var $pk     =   "no_spk";
	var $title  =   "SPK (Surat Pesanan Kendaraan) ";
	function mata_uang($a)
	{
		if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		return number_format($a, 0, ',', '.');
	}
	function format_tgl($a)
	{
		return date('d/m/Y', strtotime($a));
	}
	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_kelurahan');
		$this->load->model('m_h1_dealer_spk', 'm_spk');
		$this->load->model('m_h1_dealer_prospek', 'm_prospek');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		//---- cek session -------//		
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
		$_SESSION['id_tipe'] = "";
		$_SESSION['id_warna'] = "";
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		//$data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE id_dealer = '$id_dealer' AND no_spk NOT IN(SELECT no_spk FROM tr_sales_order) ORDER BY created_at DESC");
		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,tr_prospek.nama_konsumen,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer=tr_prospek.id_customer
		 				INNER JOIN ms_warna ON tr_spk.id_warna=ms_warna.id_warna
		 				INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
		 				WHERE tr_spk.id_dealer = '$id_dealer'
		 				AND no_spk NOT IN (select no_spk FROM tr_sales_order WHERE no_spk=tr_spk.no_spk)
						AND expired IS NULL AND status_spk IN('approved','booking','rejected')
		 				ORDER BY tr_spk.created_at DESC");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC LIMIT 1");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function outstanding()
	{
		$_SESSION['id_tipe'] = "";
		$_SESSION['id_warna'] = "";
		$data['isi']    = $this->page;
		$data['title']	= 'Outstanding ' . $this->title;
		$data['set']		= "outstanding";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,tr_prospek.nama_konsumen,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_spk 
		INNER JOIN tr_prospek ON tr_spk.id_customer=tr_prospek.id_customer
		INNER JOIN ms_warna ON tr_spk.id_warna=ms_warna.id_warna
		INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
		WHERE tr_spk.id_dealer = '$id_dealer'
		AND no_spk NOT IN (select no_spk FROM tr_sales_order WHERE no_spk=tr_spk.no_spk)
		AND expired IS NOT NULL and expired ='1' AND status_spk IN('approved','booking','paid')
		ORDER BY tr_spk.created_at DESC");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC LIMIT 1");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}

	public function gc()
	{
		$_SESSION['id_tipe'] = "";
		$_SESSION['id_warna'] = "";
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= "view_gc";
		$id_dealer = $this->m_admin->cari_dealer();
		//$data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE id_dealer = '$id_dealer' AND no_spk NOT IN(SELECT no_spk FROM tr_sales_order) ORDER BY created_at DESC");
		$data['dt_spk'] = $this->db->query("SELECT tr_spk_gc.* FROM tr_spk_gc
		 				WHERE tr_spk_gc.id_dealer = '$id_dealer'
						 AND expired IS NULL AND (status IN('input','rejected') OR status IS NULL)
		 				ORDER BY tr_spk_gc.created_at DESC");
		$data['dt_customer'] = $this->db->query("SELECT id_customer,no_hp,nama_konsumen FROM tr_prospek WHERE tr_prospek.id_dealer = '$id_dealer' ORDER BY id_customer ASC LIMIT 1");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC LIMIT 1");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "history_fix";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_customer'] = $this->db->query("SELECT id_customer,no_hp,nama_konsumen FROM tr_prospek WHERE tr_prospek.id_dealer = '$id_dealer' ORDER BY id_customer ASC");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
		$this->template($data);
	}

	public function get_history_spk()
	{
		$search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $id_dealer = $this->m_admin->cari_dealer();
        $dataSpk = $this->get_data_history_spk($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer);

        $data = array();
        foreach($dataSpk->result() as $rows)
        {
			$tipe = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $rows->id_tipe_kendaraan);
			$ahm = ($tipe->num_rows() > 0) ? $tipe->row()->tipe_ahm : "";
			$warna = $this->m_admin->getByID("ms_warna", "id_warna", $rows->id_warna);
			$war = ($warna->num_rows() > 0) ? $warna->row()->warna : "";

            $data[]= array(
            	'',
                "<a href='" . base_url('dealer/spk/detail?id=') . "$rows->no_spk'>$rows->no_spk</a>",
                $rows->nama_konsumen,
                $rows->alamat,
                $ahm,
                $war,
                $rows->no_ktp,
                "<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk/cetak?id=$rows->no_spk'>
	              <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
	            </a>"
            );     
        }
        $total_data = $this->get_count_history_spk($search, $id_dealer);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
	}

	public function get_data_history_spk($search, $limit, $start, $order_field, $order_ascdesc, $id_dealer)
	{
		$cari = '';
		if ($search != '') {
			$cari = " AND (tr_spk.id_tipe_kendaraan LIKE '%$search%' OR tr_spk.id_warna LIKE '%$search%' OR tr_spk.no_spk LIKE '%$search%' OR tr_spk.nama_konsumen LIKE '%$search%') ";
		} 

		$sql = "
			SELECT
				id_tipe_kendaraan,
				id_warna,
				nama_konsumen,
				so.no_spk,
				alamat,
				no_ktp 
			FROM
				tr_spk
				LEFT JOIN tr_sales_order so ON so.no_spk = tr_spk.no_spk 
			WHERE
				( so.id_sales_order IS NOT NULL OR tr_spk.status_spk = 'rejected' ) 
				AND so.id_dealer = '$id_dealer' $cari 
			ORDER BY
				$order_field $order_ascdesc
			LIMIT $start, $limit
		";

		return $this->db->query($sql);
	}

	public function get_count_history_spk($search,$id_dealer)
	{
		$cari = '';
		if ($search != '') {
			$cari = " AND (tr_spk.id_tipe_kendaraan LIKE '%$search%' OR tr_spk.id_warna LIKE '%$search%' OR tr_spk.no_spk LIKE '%$search%' OR tr_spk.nama_konsumen LIKE '%$search%') ";
		} 

		$sql = "
			SELECT
				id_tipe_kendaraan,
				id_warna,
				nama_konsumen,
				so.no_spk,
				alamat,
				no_ktp 
			FROM
				tr_spk
				LEFT JOIN tr_sales_order so ON so.no_spk = tr_spk.no_spk 
			WHERE
				( so.id_sales_order IS NOT NULL OR tr_spk.status_spk = 'rejected' ) 
				AND so.id_dealer = '$id_dealer' $cari 
		";

		return $this->db->query($sql)->num_rows();
	}

	public function cetak_gc()
	{
		$data['tanggal'] = $tgl 				= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$data['id'] = $id 					= $this->input->get('id_c');
		$data['no_spk'] = $id;
		$sql = $this->db->query("SELECT tr_spk_gc.*, tr_prospek_gc.id_karyawan_dealer,
	  				ms_karyawan_dealer.nama_lengkap,ms_dealer.pic,tr_spk_gc.no_ktp,
	  				ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_spk_gc 
			LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer			
			LEFT JOIN ms_dealer ON tr_spk_gc.id_dealer = ms_dealer.id_dealer
			WHERE tr_spk_gc.no_spk_gc = '$id'");
		$spk = $data['dt_spk'] = $sql->row();
		if ($spk->jenis_gc == 'Instansi') {
			$data['cetak'] = 'cetak_spk_gc_2';
		} else {
			$data['cetak'] = 'cetak_spk_gc_1';
		}

		// if(isset($_GET['tes'])){
		// 	$data['cetak'] = "cetak_spk_gc_2";
		// }else{
		// 	$data['cetak'] = "tes";
		// }
		//$this->load->view('dealer/sales_order_cetak_gc', $data);    
		$html = $this->load->view('dealer/spk_cetak_gc', $data, true);
		$mpdf->WriteHTML($html);
		$output = 'cetak_.pdf';
		$mpdf->Output("$output", 'I');
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan
																							ORDER BY ms_kelurahan.kelurahan ASC");
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
		// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "id_pekerjaan", "ASC");
		$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan NOT IN ('9','10') ORDER BY id_pekerjaan ASC ");

		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "id_pengeluaran_bulan", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		// $data['dt_customer'] = $this->m_admin->getSortCond("ms_customer","id_customer","nama","ASC");			
		$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['dt_prospek'] = $this->m_admin->getSortCond("tr_prospek", "no_hp", "id_tipe_kendaraan", "alamat", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "id_pendidikan", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi", "hobi", "ASC");
		$data['event'] = $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");
		$id_dealer = $this->m_admin->cari_dealer();
		// $data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek LEFT JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
		// 					LEFT JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
		// 					WHERE tr_prospek.id_dealer = '$id_dealer'
		// 					ORDER BY tr_prospek.id_customer ASC");		
		$data['dt_customer'] = $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.nama_lengkap FROM tr_prospek LEFT JOIN ms_dealer ON tr_prospek.id_dealer=ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
			LEFT JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan
			LEFT JOIN ms_kecamatan ON tr_prospek.id_kecamatan=ms_kecamatan.id_kecamatan
			LEFT JOIN ms_kabupaten ON tr_prospek.id_kabupaten=ms_kabupaten.id_kabupaten
			LEFT JOIN ms_provinsi ON tr_prospek.id_prospek=ms_provinsi.id_provinsi
			LEFT JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			WHERE tr_prospek.active = '1' AND tr_prospek.id_dealer = '$id_dealer' and tr_prospek.status_prospek = 'Deal'
			AND id_customer NOT IN (SELECT id_customer FROM tr_spk 
								JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
								WHERE tr_sales_order.status_delivery IS NOT NULL AND id_customer=tr_prospek.id_customer
							   )
			ORDER BY tr_prospek.created_at DESC");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
		$this->template($data);
	}
	public function add_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= "insert_gc";
		$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan
																							ORDER BY ms_kelurahan.kelurahan ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		/*
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
		*/

		$data['dt_npwp'] = $this->db->query("SELECT tr_prospek_gc.*,ms_kelurahan.*, ms_karyawan_dealer.nama_lengkap FROM tr_prospek_gc 
							LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");

		$data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek LEFT JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
							LEFT JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek.id_dealer = '$id_dealer'
							ORDER BY tr_prospek.id_customer ASC");
		$this->template($data);
	}
	public function ajax_list()
	{
		$list = $this->m_kelurahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan", "id_kecamatan", $isi->id_kecamatan);
			$kabupaten = '';
			if ($cek->num_rows() > 0) {
				$t = $cek->row();
				$kecamatan = $t->kecamatan;
				$kab = $this->db->get_where('ms_kabupaten', ['id_kabupaten' => $t->id_kabupaten]);
				$kabupaten = $kab->num_rows() > 0 ? $kab->row()->kabupaten : '';
			} else {
				$kecamatan = "";
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->id_kelurahan . '-' . $isi->kelurahan;
			$row[] = $kecamatan;
			$row[] = $kabupaten;
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
	public function ajax_list_2()
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
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem2('$isi->id_kelurahan')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
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
	public function cari_id()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 	= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_spk ORDER BY no_spk DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->no_spk) - 9;
			$id 	= substr($row->no_spk, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/0" . $id;
			}
			$kode = $kode1 . "-" . $kode_dealer;
		} else {
			$kode = $th . "/" . $bln . "/" . $tgl . "/00001-" . $kode_dealer;
		}
		$rt = rand(1111, 9999);
		echo $kode . "|" . $rt;
	}
	public function cari_id_new()
	{

		// //$tgl				= $this->input->post('tgl');
		// $th 				= date("y");
		// $bln 				= date("m");
		// $tgl 				= date("d");
		// $dealer 		= $this->session->userdata("id_karyawan_dealer");
		// $isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
		// 						WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		// $kode_dealer 	= $isi->kode_dealer_md;
		// $pr_num 			= $this->db->query("SELECT * FROM tr_spk ORDER BY no_spk DESC LIMIT 0,1");						
		// if($pr_num->num_rows()>0){
		// 	$row 	= $pr_num->row();				
		// 	$pan  = strlen($row->no_spk)-9;
		// 	$id 	= substr($row->no_spk,$pan,5)+1;	
		// 	if($id < 10){
		// 		$kode1 = $th."/".$bln."/".$tgl."/0000".$id;          
		//     }elseif($id > 9 && $id <= 99){
		// 		$kode1 = $th."/".$bln."/".$tgl."/000".$id;                    
		//     }elseif($id > 99 && $id <= 999){
		// 		$kode1 = $th."/".$bln."/".$tgl."/00".$id;          					          
		//     }elseif($id > 999){
		// 		$kode1 = $th."/".$bln."/".$tgl."/0".$id;                    
		//     }
		// 	$kode = $kode1."-".$kode_dealer;
		// }else{
		// 	$kode = $th."/".$bln."/".$tgl."/00001-".$kode_dealer;
		// } 	
		// $rt = rand(1111,9999);
		// echo $kode."|".$rt;

		$th       = date('Y');
		$bln      = date('m/d');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$id_dealer = $this->m_admin->cari_dealer();
		// $id_sumber='E20';
		// if ($id_dealer!=null) {
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM tr_spk
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$no_spk = substr($row->no_spk, 9, 5);
			$new_kode   = $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $no_spk + 1) . '-' . $id_sumber;
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_spk', ['no_spk' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, 9, 5);
					$new_kode = $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $neww + 1) . '-' . $id_sumber;
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = $th_kecil . '/' . $bln . '/' . '00001-' . $id_sumber;
		}
		return strtoupper($new_kode);
	}
	public function cari_gc()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 		= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 	= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_spk_gc ORDER BY no_spk_gc DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->no_spk_gc) - 9;
			$id 	= substr($row->no_spk_gc, $pan, 5) + 1;
			if ($id < 10) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . "/" . $bln . "/" . $tgl . "/0" . $id;
			}
			$kode = "GC" . $kode1 . "-" . $kode_dealer;
		} else {
			$kode = "GC" . $th . "/" . $bln . "/" . $tgl . "/00001-" . $kode_dealer;
		}

		return $kode;
	}
	public function cek_program_gc()
	{
		$tipe	= $this->input->post('id_tipe_kendaraan');
		$qty	= $this->input->post('qty');
		$hasil = explode("|", $tipe);
		$out = "";
		foreach ($hasil as $ambil) {
			//echo $ambil;
			$id_tipe_kendaraan = $ambil;
			$id_sales_program	= $this->input->post('id_sales_program');
			$beli	= strtolower($this->input->post('beli'));
			$id_dealer	= $this->m_admin->cari_dealer();
			$cek_program  = $this->db->query("SELECT * FROM tr_sales_program INNER JOIN tr_sales_program_dealer ON tr_sales_program.id_program_md = tr_sales_program_dealer.id_program_md
						INNER JOIN tr_sales_program_tipe ON tr_sales_program.id_program_md = tr_sales_program_tipe.id_program_md
						WHERE tr_sales_program_dealer.id_dealer = '$id_dealer' AND tr_sales_program_tipe.id_tipe_kendaraan = '$id_tipe_kendaraan'
						AND tr_sales_program_tipe.qty_minimum <= '$qty'
						AND tr_sales_program.id_program_md = '$id_sales_program'");
			if ($cek_program->num_rows() > 0) {
				$am = $cek_program->row();
				if ($beli == 'cash') {
					$ahm = $am->ahm_cash;
					$dealer = $am->dealer_cash;
					$md = $am->md_cash;
					$other = $am->other_cash;
				} else {
					$ahm = $am->ahm_kredit;
					$dealer = $am->dealer_kredit;
					$md = $am->md_kredit;
					$other = $am->other_kredit;
				}
				$h = $ahm + $md + $dealer + $other;
			} else {
				$h = 0;
			}
			$out = $out . "|" . $h;
		}
		echo $out;
	}
	public function take_kec()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$kode_pos 		= $dt_kel->kode_pos;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan 		= $dt_kec->kecamatan;
		$id_kabupaten = $dt_kec->id_kabupaten;
		$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= $dt_kab->kabupaten;
		$id_provinsi  = $dt_kab->id_provinsi;
		$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  		= $dt_pro->provinsi;

		echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $kelurahan . "|" . $kode_pos;
	}
	public function cek_harga()
	{
		$id_tipe_kendaraan = $this->input->post("id_tipe_kendaraan");
		$tipe_customer = $this->input->post("tipe_customer");

		echo $id_tipe_kendaraan . "" . $tipe_customer;
	}
	public function take_ref()
	{
		$no_rangka 	= $this->input->post("no_rangka");
		$no_ktp 		= $this->input->post("no_ktp");
		$cek 				= $this->db->query("SELECT ms_refferal.refferal_id,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_spk.nama_konsumen,tr_spk.tgl_lahir,tr_spk.no_ktp 
						 				FROM ms_refferal INNER JOIN tr_scan_barcode ON ms_refferal.no_rangka = tr_scan_barcode.no_rangka
										INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
										INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
										WHERE ms_refferal.no_rangka = '$no_rangka' AND tr_sales_order.status_so = 'so_invoice'
										AND tr_spk.no_ktp <> '$no_ktp'");
		if ($cek->num_rows() > 0) {
			$isi = $cek->row();
			$no_rangka 		= $isi->no_rangka;
			$refferal_id 	= $isi->refferal_id;
			$nama_konsumen = $isi->nama_konsumen;
			$tgl_lahir 		= $isi->tgl_lahir;
			$no_ktp 			= $isi->no_ktp;
		} else {
			$no_rangka 		= "";
			$refferal_id 	= "";
			$nama_konsumen = "";
			$tgl_lahir 		= "";
			$no_ktp 			= "";
		}
		echo $no_rangka . "|" . $refferal_id . "|" . $nama_konsumen . "|" . $tgl_lahir . "|" . $no_ktp;
	}
	public function take_robd()
	{
		$no_rangka 	= $this->input->post("no_rangka");
		$no_ktp 		= $this->input->post("no_ktp");
		$cek 				= $this->db->query("SELECT ms_refferal.refferal_id,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_spk.nama_konsumen,tr_spk.tgl_lahir,tr_spk.no_ktp 
						 				FROM ms_refferal INNER JOIN tr_scan_barcode ON ms_refferal.no_rangka = tr_scan_barcode.no_rangka
										INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
										INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
										WHERE ms_refferal.no_rangka = '$no_rangka' AND tr_sales_order.status_so = 'so_invoice'
										AND tr_spk.no_ktp = '$no_ktp'");
		if ($cek->num_rows() > 0) {
			$isi = $cek->row();
			$no_rangka 		= $isi->no_rangka;
			$refferal_id 	= $isi->refferal_id;
			$nama_konsumen = $isi->nama_konsumen;
			$tgl_lahir 		= $isi->tgl_lahir;
			$no_ktp 			= $isi->no_ktp;
		} else {
			$no_rangka 		= "";
			$refferal_id 	= "";
			$nama_konsumen = "";
			$tgl_lahir 		= "";
			$no_ktp 			= "";
		}
		echo $no_rangka . "|" . $refferal_id . "|" . $nama_konsumen . "|" . $tgl_lahir . "|" . $no_ktp;
	}
	public function warna_slot()
	{
		$id_tipe_kendaraan	= $this->input->post('id_tipe_kendaraan');
		$id_customer	= $this->input->post('id_customer');

		$dt_warna = $this->db->query("SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
		foreach ($dt_warna->result() as $res) {
			$id_warna = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$id_customer'")->row()->id_warna;
			$selected = $res->id_warna == $id_warna ? 'selected' : '';
			echo "<option value='$res->id_warna' $selected>$res->id_warna | $res->warna</option>";
		}

		echo $data;
	}
	public function cek_customer()
	{
		$id_customer = $this->input->post('id_customer');
		$sql = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer = '$id_customer'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			$karyawan = $this->db->get_where('ms_karyawan_dealer', ['id_karyawan_dealer' => $dt_ve->id_karyawan_dealer])->row()->nama_lengkap;
			$get_disc = $this->db->query("SELECT CASE WHEN tr_pengajuan_diskon.id_diskon IS NULL THEN tr_pengajuan_diskon.nominal_diskon
			ELSE ms_diskon.value END AS value FROM tr_pengajuan_diskon LEFT JOIN ms_diskon ON tr_pengajuan_diskon.id_diskon=ms_diskon.id_diskon WHERE id_prospek='$dt_ve->id_prospek' AND tr_pengajuan_diskon.status='Approved Disc'");
			$diskon = 0;
			if ($get_disc->num_rows() > 0) {
				$diskon = $get_disc->row()->value;
			}
			$tenor     = 0;
			$angsuran  = 0;
			$uang_muka = 0;
			$get_skema_kredit = $this->db->get_where('tr_skema_kredit', ['id_prospek' => $dt_ve->id_prospek]);
			$id_finance_company = '';
			$finance_company    = '';
			if ($get_skema_kredit->num_rows() > 0) {
				$skm       = $get_skema_kredit->row();
				$tenor     = $skm->tenor;
				$angsuran  = $skm->angsuran;
				$uang_muka = $skm->dp;
				$id_finance_company = $skm->id_finco;
				$finco = $this->db->get_where('ms_finance_company', ['id_finance_company' => $id_finance_company]);
				$finance_company = $finco->num_rows() > 0 ? $finco->row()->finance_company : '';
			}
			echo "ok" . "|" . $dt_ve->nama_konsumen . "|" . $dt_ve->id_kelurahan . "|" . $dt_ve->alamat . "|" . $dt_ve->tgl_lahir . "|" . $dt_ve->jenis_pembelian . "|" . $dt_ve->jenis_wn . "|" . $dt_ve->no_ktp . "|" . $dt_ve->no_kk . "|" . $dt_ve->no_hp . "|" . $dt_ve->email . "|" . $dt_ve->pekerjaan . "|" . $dt_ve->id_tipe_kendaraan . "|" . $dt_ve->id_warna . "|" . $dt_ve->tempat_lahir . "|" . $dt_ve->no_ktp . "|" . $dt_ve->no_npwp . "|" . $dt_ve->pendidikan . "|" . $dt_ve->jenis_kelamin . "|" . $dt_ve->kodepos . "|" . $dt_ve->status_nohp . "|" . $dt_ve->sedia_hub . "|" . $dt_ve->merk_sebelumnya . "|" . $dt_ve->jenis_sebelumnya . "|" . $dt_ve->digunakan . "|" . $dt_ve->pemakai_motor . "|" . $dt_ve->agama . "|" . $dt_ve->no_telp . "|" . $dt_ve->id_warna . "|" . $dt_ve->id_flp_md . "|" . $karyawan . '|' . $diskon . '|' . $dt_ve->longitude . '|' . $dt_ve->latitude . '|' . ucwords($dt_ve->rencana_pembayaran . '|' . $tenor . '|' . $angsuran . '|' . $id_finance_company . '|' . $finance_company);
		} else {
			echo "There is no data found!";
		}
	}
	public function cek_prospek()
	{
		$id_prospek_gc = $this->input->post('id_prospek_gc');
		$sql = $this->db->query("SELECT * FROM tr_prospek_gc WHERE id_prospek_gc = '$id_prospek_gc'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			echo "ok" . "|" . $dt_ve->nama_npwp . "|" . $dt_ve->no_npwp . "|" . $dt_ve->alamat . "|" . $dt_ve->id_kelurahan . "|" . $dt_ve->jenis . "|" . $dt_ve->no_telp . "|" . $dt_ve->tgl_berdiri . "|" . $dt_ve->nama_penanggung_jawab . "|" . $dt_ve->email . "|" . $dt_ve->no_hp . "|" . $dt_ve->status_nohp . "|" . $dt_ve->kodepos . "|" . $dt_ve->id_prospek_gc;
		} else {
			echo "There is no data found!";
		}
	}
	public function cek_statushp()
	{
		$id_prospek_gc	= $this->input->post('id_prospek_gc');
		$dt_status_hp = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		foreach ($dt_status_hp->result() as $res) {
			$status = $this->db->query("SELECT * FROM tr_prospek_gc WHERE id_prospek_gc = '$id_prospek_gc'")->row()->status_nohp;
			$selected = $res->id_status_hp == $status ? 'selected' : '';
			echo "<option value='$res->id_status_hp' $selected>$res->status_hp</option>";
		}

		// echo $data;
	}
	public function getDetail()
	{
		$id 		= $this->input->post('id');
		$data['id']           = $id;
		$filter['id_prospek'] = $id;
		$data['detail']       = $this->m_prospek->getProspekGCDetail($filter);
		$data['jenis']        = "prospek";
		$data['dt_tipe']      = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna']     = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_spk_gc', $data);
	}

	function tes_detail($id)
	{
		$filter['id_prospek'] = $id;
		$result = $this->m_prospek->getProspekGCDetail($filter);
		send_json($result->result());
	}

	public function getDetail2()
	{
		$waktu    = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$id       = $this->input->post('id');

		$data['detail'] = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$id'");
		$data['id'] = $id;
		$data['jenis'] = "spk";
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_spk_gc', $data);
	}
	public function getDetail_cash()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id 		= $this->input->post('id');
		// $data['detail'] = $this->db->query("SELECT tr_prospek_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_prospek_gc_kendaraan
		// 			LEFT JOIN ms_tipe_kendaraan on tr_prospek_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		// 			LEFT JOIN ms_warna ON tr_prospek_gc_kendaraan.id_warna = ms_warna.id_warna
		// 			WHERE id_prospek_gc='$id'");
		$data['id'] = $id;
		$filter['id_prospek'] = $id;
		$data['detail'] = $this->m_prospek->getProspekGCDetail($filter);
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_spk_gc_cash', $data);
	}
	public function getDetail_kredit()
	{
		$waktu                = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
		$login_id             = $this->session->userdata('id_user');
		$id                   = $this->input->post('id');
		$filter['id_prospek'] = $id;
		$data['detail']       = $this->m_prospek->getProspekGCDetail($filter);
		$data['id']           = $id;
		$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_spk_gc_kredit', $data);
	}
	public function getWarna()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		echo $id_warna          = $this->input->post('id_warna');
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
				$selected = $res->id_warna == $id_warna ? 'selected' : '';
				echo "<option value='$res->id_warna' $selected>$res->id_warna | $res->warna</option>";
			}
		} else {
			echo "<option value=''>- choose -</option>";
		}
	}
	public function cek_bbn()
	{
		$id_tipe_kendaraan 	= $this->input->post("id_tipe_kendaraan");
		$id_warna	 					= $this->input->post("id_warna");
		// $nilai_voucher = $this->input->post('nilai_voucher');
		$tipe 							= "Customer Umum";
		$cek_bbn = $this->db->query("SELECT * FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");
		if ($cek_bbn->num_rows() > 0) {
			$te = $cek_bbn->row();
			$biaya_bbn = $te->biaya_bbn;
		} else {
			$biaya_bbn = 0;
		}
		$item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
		if ($item->num_rows() > 0) {
			$ty = $item->row();
			$id_item = $ty->id_item;
		} else {
			$id_item = "";
		}
		$date = date('Y-m-d');
		$cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md 
			INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
			WHERE ms_kelompok_md.id_item = '$id_item' AND start_date <='$date' AND ms_kelompok_harga.target_market = '$tipe' ORDER BY start_date DESC LIMIT 0,1");
		if ($cek_harga->num_rows() > 0) {
			$ts = $cek_harga->row();
			$harga_jual = $ts->harga_jual;
		} else {
			$harga_jual = 0;
		}


		// if ($row->jenis_beli == 'Cash') {
		//   $voucher_tambahan = $row->voucher_tambahan_1 + $row->diskon;
		//   if ($row->the_road == 'On The Road') {
		//     $total_bayar = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan);
		//     $bbn = $row->biaya_bbn;
		//   } elseif ($row->the_road == 'Off The Road') {
		//     $total_bayar = $row->harga_off_road - ($row->voucher_1 + $voucher_tambahan);
		//     $bbn = 0;
		//   }
		//   $ho = $total_bayar - $row->biaya_bbn;
		// } else {
		//   $voucher_tambahan = $row->voucher_tambahan_2 + $row->diskon;
		//   if ($row->the_road == 'On The Road') {          
		//     $total_bayar = $row->harga_on_road - ($row->voucher_2 + $voucher_tambahan);
		//     $bbn = $row->biaya_bbn;
		//   } elseif ($row->the_road == 'Off The Road') {
		//     $total_bayar = $row->harga_off_road - ($row->voucher_2 + $voucher_tambahan);
		//     $bbn = 0;
		//   }
		//   //$ho = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan) - $row->biaya_bbn;
		//   $ho = $total_bayar - $row->biaya_bbn;
		// }                

		// $harga_jual = $harga_jual - $nilai_voucher;
		$harga 		= floor($harga_jual / 1.1);
		$ppn 			= floor(0.1 * $harga);
		$harga_on = $harga_jual + $biaya_bbn;
		$harga_tunai = $harga_on;
		echo $biaya_bbn . "|" . $harga_on . "|" . $harga_jual . "|" . $ppn . "|" . $harga . "|" . $harga_tunai;
	}
	public function save_demo()
	{
		$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai. tipe file yg harus diupload adalah (*.jpg,*.png)!";
		$_SESSION['tipe'] 	= "danger";
		$_SESSION['id_warna'] 	= $this->input->post("id_warna");
		$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");

		echo "<script>history.go(-1)</script>";
	}
	public function get_kode_indent()
	{
		$th               = date('Y');
		$bln              = date('m');
		$th_bln           = date('Y-m');
		$th_kecil         = date('y');
		$dmy              = date('dmy');
		$id_dealer        = $this->m_admin->cari_dealer();
		// $id_sumber     ='E20';
		// if ($id_dealer !=null) {
		$dealer           = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber        = $dealer->kode_dealer_md;
		// }
		$get_data  = $this->db->query("SELECT * FROM tr_po_dealer_indent
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$id_indent = substr($row->id_indent, -4);
			$new_kode   = 'INDENT/' . $id_sumber . '/' . $dmy . '/' . sprintf("%'.04d", $id_indent + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_po_dealer_indent', ['id_indent' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -5);
					$new_kode = 'INDENT/' . $id_sumber . '/' . $dmy . '/' . sprintf("%'.04d", $id_indent + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = 'INDENT/' . $id_sumber . '/' . $dmy . '/00001';
		}
		return strtoupper($new_kode);
	}
	public function get_nosin_fifo($id_tipe_kendaraan, $id_warna)
	{
		$id_dealer = $this->m_admin->cari_dealer();
		// $dt = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.* FROM tr_penerimaan_unit_dealer_detail
		//             JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
		//             JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
		//             JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		//             JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
		//             JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
		//             WHERE tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan' 
		//             AND tr_scan_barcode.warna = '$id_warna' 
		//             AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
		//             AND tr_scan_barcode.status = '4' 
		//             AND tr_scan_barcode.tipe = 'RFS'
		//             AND tr_penerimaan_unit_dealer.status = 'close'
		//             AND tr_penerimaan_unit_dealer_detail.jenis_pu='RFS'
		//             AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
		//             AND po_indent IS NULL
		//             AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT * FROM (SELECT no_mesin_spk FROM tr_spk WHERE no_mesin_spk IS NOT NULL
		// 			UNION SELECT no_mesin FROM tr_sales_order) AS tabel GROUP BY no_mesin_spk)
		//             ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC LIMIT 1
		//             ");
		$dt = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.* FROM tr_penerimaan_unit_dealer_detail
		JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
		JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
		JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
		JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer            
		LEFT JOIN tr_spk spk ON spk.no_mesin_spk=tr_penerimaan_unit_dealer_detail.no_mesin
		LEFT JOIN tr_sales_order so ON so.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin  
		LEFT JOIN tr_sales_order_gc_nosin so_gc ON so_gc.no_mesin= tr_penerimaan_unit_dealer_detail.no_mesin
		WHERE tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan' 
		AND tr_scan_barcode.warna = '$id_warna' 
		AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
		AND tr_scan_barcode.status = '4' 
		AND tr_scan_barcode.tipe = 'RFS'
		AND tr_penerimaan_unit_dealer.status = 'close'
		AND tr_penerimaan_unit_dealer_detail.jenis_pu='RFS'
		AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
		AND po_indent IS NULL
		AND spk.no_mesin_spk IS NULL AND so.no_mesin IS NULL AND so_gc.no_mesin IS NULL
		ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC LIMIT 1
                ");
		if ($dt->num_rows() > 0) {
			return $dt->row()->no_mesin;
		}
	}
	public function save()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl_kini		= gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		// $id  				= $this->input->post($pk);
		$no_spk = $id = $this->cari_id_new();
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		$config['upload_path'] 			= './assets/panel/files/';
		$config['allowed_types'] 		= 'jpg|jpeg|png';
		$config['max_size']					= '150';
		$config2['upload_path'] 		= './assets/panel/files/';
		$config2['allowed_types'] 	= 'jpg|jpeg|png';
		$config2['max_size']				= '550';
		$jenis_beli = $this->input->post('jenis_beli');
		$type_ktp1 = $_FILES["file_foto"]["type"];

		$type_kk   = $_FILES["file_kk"]["type"];

		if ($jenis_beli == 'Kredit') {
			$type_ktp2 = $_FILES["file_ktp_2"]["type"];
		}
		if ($type_ktp1 == 'image/jpeg' or $type_ktp1 == 'image/png' or $type_ktp1 == 'image/jpg') {
			$format_foto = "ok";
		} else {
			$format_foto = "salah";
		}
		if ($type_kk == 'image/jpeg' or $type_kk == 'image/png' or $type_kk == 'image/jpg') {
			$format_kk = "ok";
		} else {
			$format_kk = "salah";
		}
		if ($jenis_beli == 'Kredit') {
			if ($type_ktp2 == 'image/jpeg' or $type_ktp2 == 'image/png' or $type_ktp2 == 'image/jpg') {
				$format_ktp_2 = "ok";
			} else {
				$format_ktp_2 = "salah";
			}
		}

		$file_foto = '';
		$file_kk = '';

		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_foto')) {
			$file_foto = "gagal";
		} else {
			$file_foto = $this->upload->file_name;
		}
		$this->upload->initialize($config2);
		if (!$this->upload->do_upload('file_kk')) {
			//$file_kk = "gagal";
			$file_kk = $this->upload->file_name;
		} else {
			$file_kk = $this->upload->file_name;
		}
		if ($jenis_beli == 'Kredit') {
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('file_ktp_2')) {
				$file_ktp_2 = "gagal";
			} else {
				$file_ktp_2 = $this->upload->file_name;
			}
		}
		if ($cek == 0) {
			$r = "";
			$isi_ktp = $this->input->post('no_ktp');
			$ktp = strlen($isi_ktp);
			// if ($ktp < 16) {
			// 	$jum = 16 - $ktp;
			// 	for ($i = 1; $i <= $jum; $i++) {
			// 		$r = $r . "0";
			// 	}
			// 	$ktp_f = $r . $isi_ktp;
			// } else {
			// 	$ktp_f = $isi_ktp;
			// }
			$ktp_f = $isi_ktp;
			$r2 = "";
			$isi_ktp2 = $this->input->post('no_ktp_penjamin');
			$ktp2 = strlen($isi_ktp2);
			if ($ktp2 < 16) {
				$jum = 16 - $ktp2;
				for ($i = 1; $i <= $jum; $i++) {
					$r2 = $r2 . "0";
				}
				$ktp_p = $r2 . $isi_ktp2;
			} else {
				$ktp_p = $isi_ktp2;
			}

			// $no_spk = $data['no_spk'] 						= $this->input->post('no_spk');
			$data['no_spk'] 						= $no_spk;
			$data['tgl_spk'] 						= $this->input->post('tgl_spk');
			$data['id_customer'] 				= $this->input->post('id_customer');
			$nama_konsumen = $data['nama_konsumen']				= $this->input->post('nama_konsumen');
			$data['tempat_lahir'] 			= $this->input->post('tempat_lahir');
			$data['tgl_lahir'] 					= $this->input->post('tgl_lahir');
			$data['jenis_wn'] 					= $this->input->post('jenis_wn');
			$no_ktp = $data['no_ktp'] 						= $ktp_f;
			$data['no_kk'] 							= $this->input->post('no_kk');
			$data['npwp'] 							= $this->input->post('npwp');
			$data['file_foto'] 					= $file_foto;
			$data['file_kk'] 						= $file_kk;

			$data['id_kelurahan'] 			= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] 			= $region[1];
			$data['id_kabupaten'] 			= $region[2];
			$data['id_provinsi'] 				= $region[3];
			$alamat = $data['alamat'] 						= $this->input->post('alamat');
			$data['kodepos'] 						= $this->input->post('kodepos');
			$d_lokasi = $this->input->post('denah_lokasi');
			if ($d_lokasi != "") {
				$p_lokasi = explode(',', $d_lokasi);
				if (is_array($p_lokasi) and count($p_lokasi) == 2) {
					$latitude = str_replace(' ', '', $p_lokasi[0]);
					$longitude = str_replace(' ', '', $p_lokasi[1]);
					if ($latitude != "" and $longitude != "") {
						$data['denah_lokasi'] 			= $d_lokasi;
					} else {
						$data['denah_lokasi'] = "-1.613510, 103.594603";
					}
				} else {
					$data['denah_lokasi'] = "-1.613510, 103.594603";
				}
			} else {
				$data['denah_lokasi'] = "-1.613510, 103.594603";
			}
			$data['tgl_pengiriman']   = $this->input->post('tgl_pengiriman');
			$data['waktu_pengiriman'] = $this->input->post('waktu_pengiriman');
			$data['nama_bpkb_stnk']   = $this->input->post('nama_bpkb_stnk');
			$data['no_ktp_bpkb']      = $this->input->post('no_ktp_bpkb');
			$data['alamat_ktp_bpkb']  = $this->input->post('alamat_ktp_bpkb');
			$data['longitude']        = $this->input->post('longitude');
			$data['latitude']         = $this->input->post('latitude');
			$data['rt']               = $this->input->post('rt');
			$data['rw']               = $this->input->post('rw');
			$data['fax']              = $this->input->post('fax');
			$data['kode_ppn']         = $this->input->post('kode_ppn');
			$data['tanda_jadi']       = $this->input->post('tanda_jadi');
			$data['diskon']           = $this->input->post('diskon');
			$data['id_event']         = $this->input->post('id_event');
			$data['no_kk']            = $this->input->post('no_kk');
			$data['alamat_kk']        = $this->input->post('alamat_kk');
			$data['id_kelurahan_kk']  = $this->input->post('id_kelurahan_kk');
			$data['kode_pos_kk']      = $this->input->post('kode_pos_kk');
			$data['faktur_pajak']      = $this->input->post('faktur_pajak');
			$anggota                  = $this->input->post('anggota_kk');

			for ($i = 0; $i < count($anggota); $i++) {
				$anggota_[] = ['no_spk' => $no_spk, 'anggota' => $anggota[$i]];
			}
			$data['alamat_sama'] 				= $this->input->post('tanya');
			$data['id_kelurahan2'] 			= $this->input->post('id_kelurahan2');
			$id_kelurahan2 							= $this->input->post('id_kelurahan2');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan2));
			$data['id_kecamatan2'] 			= $region[1];
			$data['id_kabupaten2'] 			= $region[2];
			$data['id_provinsi2'] 			= $region[3];
			$data['alamat2'] 						= $this->input->post('alamat2');
			$data['kodepos2'] 					= $this->input->post('kodepos2');
			$data['status_rumah'] 			= $this->input->post('status_rumah');
			$data['lama_tinggal'] 			= $this->input->post('lama_tinggal');
			$data['pekerjaan'] 					= $this->input->post('pekerjaan');
			$data['lama_kerja'] 				= $this->input->post('lama_kerja');
			$data['jabatan'] 						= $this->input->post('jabatan');
			$data['pengeluaran_bulan'] 	= $this->input->post('pengeluaran_bulan');
			$data['penghasilan']  		= preg_replace('/[^A-Za-z0-9\  ]/', '', $this->input->post('penghasilan'));
			$data['no_hp'] 						= $this->input->post('no_hp');
			$data['status_hp'] 				= $this->input->post('status_hp');
			$data['no_hp_2'] 						= $this->input->post('no_hp_2');
			$data['status_hp_2'] 				= $this->input->post('status_hp_2');
			// $no_telp = $data['no_telp'] 						= $this->input->post('no_telp');	
			$no_telp = $data['no_telp'] 						= preg_replace('/[^0-9\  ]/', '', $this->input->post('no_telp'));
			$email = $data['email'] 							= $this->input->post('email');
			$data['refferal_id'] 				= $this->input->post('refferal_id');
			$data['robd_id'] 						= $this->input->post('robd_id');
			$ket = $data['keterangan'] 				= $this->input->post('keterangan');
			$data['nama_ibu'] 					= $this->input->post('nama_ibu');
			$data['tgl_ibu'] 						= $this->input->post('tgl_ibu');
			$id_tipe_kendaraan = $data['id_tipe_kendaraan']	= $this->input->post('id_tipe_kendaraan');
			$id_warna = $data['id_warna'] 					= $this->input->post('id_warna');
			$data['harga'] 							= $this->input->post('harga');
			$data['ppn'] 								= $this->input->post('ppn');
			$data['harga_off_road'] 		= $this->input->post('harga_off');
			$data['harga_on_road'] 			= $this->input->post('harga_on');
			$data['biaya_bbn'] 					= $this->input->post('biaya_bbn');
			$jenis_beli = $data['jenis_beli'] 				= $this->input->post('jenis_beli');
			$data['the_road'] 					= $this->input->post('the_road');
			$data['harga_tunai'] 				= $this->input->post('harga_tunai');
			//$data['program_khusus_1'] 	= $this->input->post('program_khusus_1');
			$data['voucher_1'] 					= $this->input->post('voucher_1');
			$data['voucher_tambahan_1']	= $this->input->post('voucher_tambahan_1');
			$total_bayar = $data['harga_tunai'] - $data['voucher_1'] - $data['voucher_tambahan_1'];
			$data['total_bayar'] 				= $total_bayar;
			// $data['total_bayar'] 				= $this->input->post('total_bayar');	
			$id_finance_company = $data['id_finance_company'] = $this->input->post('id_finance_company');
			$uang_muka = $data['uang_muka'] 					= $this->input->post('uang_muka');
			//$data['program_khusus_2'] 	= $this->input->post('program_khusus_2');
			$data['voucher_2'] 					= $this->input->post('voucher_2') == '' ? 0 : $this->input->post('voucher_2');
			$data['voucher_tambahan_2'] 					= $this->input->post('voucher_tambahan_2') == '' ? 0 : $this->input->post('voucher_tambahan_2');
			$tenor = $data['tenor'] 							= $this->input->post('tenor');
			$nilai_dp = $data['dp_stor'] 						= $uang_muka - $data['voucher_2'] - $data['voucher_tambahan_2'];
			$angsuran = $data['angsuran'] 					= $this->input->post('angsuran');
			$data['nama_penjamin'] = $nama_penjamin 			= $this->input->post('nama_penjamin');
			$data['hub_penjamin'] 			= $this->input->post('hub_penjamin');
			$no_ktp_penjamin = $data['no_ktp_penjamin']		= $ktp_p;
			$data['no_hp_penjamin']			= $this->input->post('no_hp_penjamin');
			$data['alamat_penjamin'] = $alamat_penjamin		= $this->input->post('alamat_penjamin');
			$data['tempat_lahir_penjamin']	= $tempat_lahir_penjamin	= $this->input->post('tempat_lahir_penjamin');
			$data['tgl_lahir_penjamin']	= $tgl_lahir_penjamin = $this->input->post('tgl_lahir_penjamin');
			$data['pekerjaan_penjamin']	= $this->input->post('pekerjaan_penjamin');
			$data['penghasilan_penjamin']		= $this->input->post('penghasilan_penjamin');
			$data['nama_bpkb']					= $this->input->post('nama_bpkb');
			if ($jenis_beli == "Kredit") {
				$data['file_ktp_2'] 				= $file_ktp_2;
			}
			$id_dealer = $data['id_dealer']					= $this->m_admin->cari_dealer();
			$data['created_at']					= $waktu;
			$data['created_by']					= $login_id;
			$data['status_spk']					= "input";
			$no_mesin = null;
			$dt = $this->get_nosin_fifo($id_tipe_kendaraan, $id_warna);

			if ($dt == false) {
				$id_ind = $this->get_kode_indent();
				$indent = [
					'id_indent' 				=> $id_ind,
					'id_spk'            => $no_spk,
					'id_dealer'         => $id_dealer,
					'nama_konsumen'     => $nama_konsumen,
					'alamat'            => $alamat,
					'no_ktp'            => $no_ktp,
					'no_telp'           => $no_telp,
					'email'             => $email,
					'id_tipe_kendaraan' => $id_tipe_kendaraan,
					'id_warna'          => $id_warna,
					'nilai_dp'          => $nilai_dp,
					'ket'               => $ket,
					'qty'               => 1,
					'status'			=> 'requested',
					'tgl'               => date('Y-m-d'),
					'created_at'        => $waktu,
					'created_by'        => $login_id
				];

				$id_po = $this->newPO_ID('indent', $id_dealer);
				$item = $this->db->query("SELECT id_item FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
				$id_item = ($item->num_rows() > 0) ? $item->row()->id_item : "";
				$bulan  = date("m");
				$tahun  = date("Y");
				$po_indent = [
					'id_po' 				=> $id_po,
					'bulan'         => $bulan,
					'tahun'         => $tahun,
					'tgl'     			=> $tgl_kini,
					'id_dealer'     => $id_dealer,
					'created_at'    => $waktu,
					'created_by'    => $login_id,
					'po_from'       => $no_spk,
					'status' 				=> 'input',
					'jenis_po' 				=> 'PO Indent',
					'submission_deadline' => $tgl_kini,
					'id_pos_dealer' => ''
				];
				$po_indent_detail = [
					'id_po' 				=> $id_po,
					'id_item'         => $id_item,
					'qty_order'         => 1,
					'qty_po_fix'         => 1
				];
				$status = 'booking';
			} else {
				$no_mesin       = $dt;
				$status         = 'booking';
				$upd_penerimaan = ['no_spk' => $no_spk, 'status_on_spk' => $status, 'booking_at' => $waktu, 'booking_by' => $login_id];
			}
			$data['no_mesin_spk']    = $no_mesin;
			$data['status_spk']      = $status;

			$config['upload_path']   = './assets/panel/spk_file';
			$config['allowed_types'] = 'jpg|png|jpeg|bmp|pdf|doc|docx|xls|xlsx';
			$config['max_size']      = '2048';
			$config['max_width']     = '2000';
			$config['max_height']    = '2000';
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name']  = TRUE;
			$file_pendukung = count($_FILES['file_pendukung']['name']);
			$nama_file      = $this->input->post('nama_file');
			for ($i = 0; $i < $file_pendukung; $i++) {
				$_FILES['file']['name']     = $_FILES['file_pendukung']['name'][$i];
				$_FILES['file']['type']     = $_FILES['file_pendukung']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['file_pendukung']['tmp_name'][$i];
				$_FILES['file']['error']    = $_FILES['file_pendukung']['error'][$i];
				$_FILES['file']['size']     = $_FILES['file_pendukung']['size'][$i];
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('file')) {
					// Uploaded file data
					$fileData                 = $this->upload->data();
					$ins_file[$i]['file']      = $fileData['file_name'];
					$ins_file[$i]['no_spk']    = $no_spk;
					$ins_file[$i]['nama_file'] = $nama_file[$i];
				} else {
					// echo $this->upload->display_errors();
				}
			}
			if ($jenis_beli == 'Cash') {
				$data['program_umum'] 	= $this->input->post('program_umum');
				$data['program_gabungan'] 	= $this->input->post('program_gabungan');
				
				if($data['program_gabungan'] == '- choose-'){
					$data['program_gabungan'] = '';
				}

				$jenis_program_temp= substr($data['program_umum'], -6);
				if (($data['program_umum'] == '' || $data['program_umum'] == NULL) && $data['voucher_1'] > 0) {
					$_SESSION['pesan'] 	= "Sales program tidak terpilih, tetapi voucher terisi !";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				}
				if ($data['program_umum'] !== '' && $data['program_umum'] !== NULL && $data['voucher_1'] == 0 && $jenis_program_temp !=='SP-002') {
					$_SESSION['pesan'] 	= "Sales program terpilih, tetapi voucher tidak terisi !";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				}
				if ($format_kk == 'salah') {
					$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($format_foto == 'salah') {
					$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai. tipe file yg harus diupload adalah (*.jpg,*.png)!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($file_foto == 'gagal') {
					$_SESSION['pesan'] 	= "Ukuran file KTP yg diupload terlalu besar!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($ktp < 16) {
					$_SESSION['pesan'] 	= "Panjang Karakter No KTP Pemohon tidak sesuai!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($file_kk == 'gagal') {
					$_SESSION['pesan'] 	= "Ukuran file KK yg diupload terlalu besar!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} else {
					// $tes = send_json($po_indent_detail);
					$this->db->trans_begin();
					$this->m_admin->insert($tabel, $data);
					if (isset($anggota_)) {
						$this->db->insert_batch('tr_spk_anggota_kk', $anggota_);
					}
					if (isset($indent)) {
						if ($this->input->post('tanda_jadi') == '' OR $this->input->post('tanda_jadi') == '0') {
							$_SESSION['pesan'] 	= "SPK ini Indent, tanda jadi tidak boleh di isi 0";
							$_SESSION['tipe'] 	= "danger";
							$_SESSION['id_warna'] 	= $this->input->post("id_warna");
							$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
							echo "<script>history.go(-1)</script>";
						}
						$this->db->insert('tr_po_dealer_indent', $indent);
					}
					if (isset($po_indent)) {
						$this->db->insert('tr_po_dealer', $po_indent);
					}
					if (isset($po_indent_detail)) {
						$this->db->insert('tr_po_dealer_detail', $po_indent_detail);
					}
					if (isset($ins_file)) {
						$this->db->insert_batch('tr_spk_file', $ins_file);
					}
					if (isset($upd_penerimaan)) {
						$this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $no_mesin]);
					}
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$_SESSION['pesan'] 	= "Telah terjadi kesalahan";
						$_SESSION['tipe'] 	= "danger";
						$_SESSION['id_warna'] 	= $this->input->post("id_warna");
						$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
						echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/add'>";
					} else {
						$this->db->trans_commit();

						$_SESSION['pesan'] 	= "Data has been saved successfully";
						$_SESSION['tipe'] 	= "success";
						echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/add'>";
					}
				}
			} elseif ($jenis_beli == 'Kredit') {
				$data['program_umum'] 	= $this->input->post('program_umum_k');
				$data['program_gabungan'] 	= $this->input->post('program_gabungan_k');
				$jenis_program_temp = substr($data['program_umum'], -6);

				if($data['program_gabungan'] == '- choose-'){
					$data['program_gabungan'] = '';
				}

				if (($data['program_umum'] == '' || $data['program_umum'] == NULL) && $data['voucher_2'] > 0) {
					$_SESSION['pesan'] 	= "Sales program tidak terpilih, tetapi voucher terisi !";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				}

				if ($data['program_umum'] !== '' && $data['program_umum'] !== NULL && $data['voucher_2'] == 0 && $jenis_program_temp !=='SP-002') {
					$_SESSION['pesan'] 	= "Sales program terpilih, tetapi voucher tidak terisi !";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				}

				if ($format_kk == 'salah') {
					$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($format_foto == 'salah') {
					$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai. tipe file yg harus diupload adalah (*.jpg,*.png)!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($format_ktp_2 == 'salah') {
					$_SESSION['pesan'] 	= "Format file KTP/KK Penjamin yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($file_foto == 'gagal') {
					$_SESSION['pesan'] 	= "File KTP Pemohon yang diupload terlalu besar!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($file_kk == 'gagal') {
					$_SESSION['pesan'] 	= "File KK Pemohon yang diupload terlalu besar!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($file_ktp_2 == 'gagal') {
					$_SESSION['pesan'] 	= "File KTP Penjamin yang diupload terlalu besar!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($id_finance_company == '') {
					$_SESSION['pesan'] 	= "Tentukan dulu Finance Company!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($ktp2 < 16) {
					$_SESSION['pesan']    = "Panjang Karakter No KTP Penjamin tidak sesuai!";

					$_SESSION['tipe']     = "danger";

					$_SESSION['id_warna'] = $this->input->post("id_warna");

					$_SESSION['id_tipe']  = $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($ktp < 16) {
					$_SESSION['pesan'] 	= "Panjang Karakter No KTP Pemohon tidak sesuai!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($no_ktp_penjamin == '') {
					$_SESSION['pesan'] 	= "No KTP Penjamin harus diisi!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($nama_penjamin == '') {
					$_SESSION['pesan'] 	= "Nama Penjamin harus diisi!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($tempat_lahir_penjamin == '') {
					$_SESSION['pesan'] 	= "Tempat Lahir Penjamin harus diisi!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($tgl_lahir_penjamin == '') {
					$_SESSION['pesan'] 	= "Tgl Lahir Penjamin harus diisi!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} elseif ($angsuran == '' or $angsuran == 0 or $tenor == '' or $tenor == 0 or $uang_muka == '' or $uang_muka == 0) {
					$_SESSION['pesan'] 	= "DP Gross, Tenor dan Angsuran harus diisi dan tidak boleh 0!";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<script>history.go(-1)</script>";
				} else {
					// send_json($data);
					$this->db->trans_begin();
					$this->m_admin->insert($tabel, $data);
					if (isset($anggota_)) {
						$this->db->insert_batch('tr_spk_anggota_kk', $anggota_);
					}
					if (isset($indent)) {
						$this->db->insert('tr_po_dealer_indent', $indent);
					}
					if (isset($po_indent)) {
						$this->db->insert('tr_po_dealer', $po_indent);
					}
					if (isset($po_indent_detail)) {
						$this->db->insert('tr_po_dealer_detail', $po_indent_detail);
					}
					if (isset($ins_file)) {
						$this->db->insert_batch('tr_spk_file', $ins_file);
					}
					if (isset($upd_penerimaan)) {
						$this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $no_mesin]);
					}
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$_SESSION['pesan'] 	= "Telah terjadi kesalahan";
						$_SESSION['tipe'] 	= "danger";
						echo "<script>history.go(-1)</script>";
					} else {
						$this->db->trans_commit();

						$_SESSION['pesan'] 	= "Data has been saved successfully";
						$_SESSION['tipe'] 	= "success";
						echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/add'>";
					}
				}
			}
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			$_SESSION['id_warna'] 	= $this->input->post("id_warna");
			$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
			echo "<script>history.go(-1)</script>";
		}
	}
	public function newPO_ID($po_type, $id_dealer)
	{
		// $po_type = $this->input->get('type');
		// $id_dealer = $this->input->get('id_dealer');
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('Ym');
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

		$get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
		WHERE id_dealer='$id_dealer'
		ORDER BY created_at DESC LIMIT 0,1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			// $thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
			// if ($th_bln==$thbln_po) {
			$id       = substr($row->id_po, -4);
			$new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $id + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_po_dealer', ['id_po' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -4);
					$new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
			// }else{
			// 	$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
			// }
		} else {
			$new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/0001';
		}
		return strtoupper($new_kode);
		//echo strtoupper($new_kode);
	}
	public function getAksesoris()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna          = $this->input->post('id_warna');
		$ksu = $this->db->query("SELECT *,(SELECT ksu FROM ms_ksu WHERE id_ksu=ms_koneksi_ksu_detail.id_ksu) AS ksu FROM ms_koneksi_ksu_detail JOIN ms_koneksi_ksu ON ms_koneksi_ksu_detail.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu 
			WHERE id_tipe_kendaraan='$id_tipe_kendaraan'
			")->result();
		echo json_encode($ksu);
	}
	public function save_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tanggal 		= gmdate("y-m-d", time() + 60 * 60 * 7);
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
			$data['no_spk_gc']             = $no_spk_gc			= $this->cari_gc();
			$data['tgl_spk_gc']            = $tanggal;
			$data['no_npwp']               = $this->input->post('no_npwp');
			$data['nama_npwp']             = $this->input->post('nama_npwp');
			$data['no_tdp']             = $this->input->post('no_tdp');
			$data['no_telp']               = $dp['no_telp']				= preg_replace('/[^0-9\  ]/', '', $this->input->post('no_telp'));
			$data['jenis_gc']              = $this->input->post('jenis_gc');
			$data['tgl_berdiri']           = $dp['tgl_berdiri']			= $this->input->post('tgl_berdiri');
			$data['id_kelurahan']          = $dp['id_kelurahan']		= $this->input->post('id_kelurahan');
			$id_kelurahan                  = $this->input->post('id_kelurahan');
			$region                        = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan']          = $dp['id_kecamatan']		= $region[1];
			$data['id_kabupaten']          = $dp['id_kabupaten']		= $region[2];
			$data['id_provinsi']           = $dp['id_provinsi']		= $region[3];
			$data['alamat']                = $dp['alamat']			= $this->input->post('alamat');
			$data['kodepos']               = $this->input->post('kodepos');
			$data['longitude']             = $this->input->post('longitude');
			$data['latitude']              = $this->input->post('latitude');
			$data['nama_bpkb']             = $this->input->post('nama_bpkb');
			$data['alamat_sama']           = $this->input->post('tanya');
			$data['id_kelurahan2']         = $this->input->post('id_kelurahan2');
			$id_kelurahan2                 = $this->input->post('id_kelurahan2');
			$region                        = explode("-", $this->m_admin->getRegion($id_kelurahan2));
			$data['id_kecamatan2']         = $region[1];
			$data['id_kabupaten2']         = $region[2];
			$data['id_provinsi2']          = $region[3];
			$data['alamat2']               = $this->input->post('alamat2');
			$data['kodepos2']              = $this->input->post('kodepos2');
			$data['nama_penanggung_jawab'] = $dp['nama_penanggung_jawab']	= $this->input->post('nama_penanggung_jawab');
			$data['email']                 = $dp['email'] = $this->input->post('email');
			$data['no_hp']                 = $dp['no_hp'] = $this->input->post('no_hp');
			$data['status_nohp']           = $dp['status_nohp'] = $this->input->post('status_nohp');
			$data['id_program']            = $this->input->post('id_sales_program_gc');
			$data['nilai_voucher']         = $this->input->post('nilai_voucher');
			$data['nama_penjamin']         = $this->input->post('nama_penjamin');
			$data['tempat_lahir']          = $this->input->post('tempat_lahir');
			$data['tgl_lahir']             = $this->input->post('tgl_lahir');
			$data['alamat_penjamin']       = $this->input->post('alamat_penjamin');
			$data['no_hp_penjamin']        = $this->input->post('no_hp_penjamin');
			$data['no_ktp']                = $this->input->post('no_ktp');
			$data['jenis_beli']            = $jenis_beli	= $this->input->post('jenis_beli');
			$data['id_finance_company']    = $this->input->post('id_finance_company');
			$data['id_pekerjaan']          = $this->input->post('id_pekerjaan');
			$data['id_prospek_gc']         = $id_prospek_gc	= $this->input->post('id_prospek_gc');
			$data['on_road_gc']            = $this->input->post('on_road_gc');
			$data['tanda_jadi']            = $this->input->post('tanda_jadi');
			// $data['id_kelurahan_kk']  = $this->input->post('id_kelurahan_kk');
			// $data['kode_pos_kk']      = $this->input->post('kode_pos_kk');
			$data['faktur_pajak']      = $this->input->post('faktur_pajak');
			$data['no_fax']      = $this->input->post('no_fax');
			$data['id_event']      = $this->input->post('id_event');
			$data['tgl_pengiriman']      = $this->input->post('tgl_pengiriman');
			$data['waktu_pengiriman']      = $this->input->post('waktu_pengiriman');
			$data['status']                = "input";
			$data['status_survey']         = "baru";

			$data['id_dealer']             = $this->m_admin->cari_dealer();
			$data['created_at']            = $waktu;
			$data['created_by']            = $login_id;
			$jumlah_detail = $this->input->post("jumlah_detail");
			for ($i = 1; $i <= $jumlah_detail; $i++) {
				$ds['id_tipe_kendaraan'] 	= $id_tipe_kendaraan2	= $_POST["id_tipe_kendaraan2_" . $i];
				$ds['id_warna'] 				 	= $id_warna2	= $_POST["id_warna2_" . $i];
				$ds['no_spk_gc']					= $no_spk_gc;
				$ds['qty'] 								= $_POST["qty2_" . $i];
				$ds['tahun_produksi'] 		= $_POST["tahun2_" . $i];
				$ds['total_unit']					= $_POST["total_unit_" . $i];
				$ds['total_harga']				= $_POST["total_harga_" . $i];

				$cek = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan WHERE id_tipe_kendaraan = '$id_tipe_kendaraan2' AND id_warna = '$id_warna2' AND no_spk_gc = '$no_spk_gc'");
				if ($cek->num_rows() > 0) {
					$t = $cek->row();
					$this->m_admin->update("tr_spk_gc_kendaraan", $ds, "id", $t->id);
				} else {
					$this->m_admin->insert("tr_spk_gc_kendaraan", $ds);
				}
			}
			if ($jenis_beli == 'Kredit') {
				$jumlah = $this->input->post("jumlah_kredit");
				for ($i = 1; $i <= $jumlah; $i++) {
					$da['id_tipe_kendaraan'] 	= $id_tipe_kendaraan	= $_POST["id_tipe_kendaraan_" . $i];
					$da['id_warna'] 				 	= $id_warna	= $_POST["id_warna_" . $i];
					$da['qty'] 				 				= $id_warna	= $_POST["qty_" . $i];
					$da['nilai_voucher'] 			= $_POST["nilai_voucher_" . $i];
					$da['voucher_tambahan']		= $_POST["voucher_tambahan_" . $i];
					$da['dp_stor']						= $_POST["dp_stor_" . $i];
					$da['harga'] 							= $_POST["harga_jual_" . $i];
					$da['biaya_bbn'] 					= $_POST["biaya_bbn_" . $i];
					$da['angsuran']						= $_POST["angsuran_" . $i];
					$da['tenor']							= $_POST["tenor_" . $i];
					$da['total']							= $_POST["total_" . $i];
					$da['no_spk_gc']					= $no_spk_gc;

					$cek = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna' AND no_spk_gc = '$no_spk_gc'");
					if ($cek->num_rows() > 0) {
						$t = $cek->row();
						$this->m_admin->update("tr_spk_gc_detail", $da, "id", $t->id);
					} else {
						$this->m_admin->insert("tr_spk_gc_detail", $da);
					}
				}
			} elseif ($jenis_beli == 'Cash') {
				$jumlah_cash = $this->input->post("jumlah_gc");
				for ($i = 1; $i <= $jumlah_cash; $i++) {
					$dc['id_tipe_kendaraan'] 	= $id_tipe_kendaraan	= $_POST["id_tipe_kendaraan_gc_" . $i];
					$dc['id_warna'] 				 	= $id_warna	= $_POST["id_warna_gc_" . $i];
					$dc['qty'] 								= $_POST["qty_gc_" . $i];
					$dc['harga'] 							= $_POST["harga_jual_gc_" . $i];
					$dc['nilai_voucher'] 			= $_POST["nilai_voucher_gc_" . $i];
					$dc['biaya_bbn'] 					= $_POST["biaya_bbn_gc_" . $i];
					$dc['voucher_tambahan']		= $_POST["voucher_tambahan_gc_" . $i];
					$dc['total']							= $_POST["total_gc_" . $i];
					$dc['no_spk_gc']					= $no_spk_gc;

					$cek = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna' AND no_spk_gc = '$no_spk_gc'");
					if ($cek->num_rows() > 0) {
						$t = $cek->row();
						$this->m_admin->update("tr_spk_gc_detail", $dc, "id", $t->id);
					} else {
						$this->m_admin->insert("tr_spk_gc_detail", $dc);
					}
				}
			}
			$cek_ = $this->db->query("SELECT * FROM tr_spk_gc WHERE no_spk_gc = '$no_spk_gc'");
			if ($cek_->num_rows() > 0) {
				$t = $cek_->row();
				$this->m_admin->update("tr_spk_gc", $data, "no_spk_gc", $t->no_spk_gc);
			} else {
				$this->m_admin->insert("tr_spk_gc", $data);
			}
			$this->m_admin->update("tr_prospek_gc", $dp, "id_prospek_gc", $id_prospek_gc);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/add_gc'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			$_SESSION['id_warna'] 	= $this->input->post("id_warna");
			$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
			echo "<script>history.go(-1)</script>";
		}
	}
	public function update_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tanggal 		= gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();


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
		$no_spk_gc			= $this->input->post('no_spk_gc');
		$data['tgl_spk_gc']            = $tanggal;
		$data['no_npwp']               = $this->input->post('no_npwp');
		$data['nama_npwp']             = $this->input->post('nama_npwp');
		$data['no_tdp']                = $this->input->post('no_tdp');
		$data['no_telp']               = $dp['no_telp']				= preg_replace('/[^0-9\  ]/', '', $this->input->post('no_telp'));
		$data['jenis_gc']              = $this->input->post('jenis_gc');
		$data['tgl_berdiri']           = $dp['tgl_berdiri']			= $this->input->post('tgl_berdiri');
		$data['id_kelurahan']          = $dp['id_kelurahan']		= $this->input->post('id_kelurahan');
		$id_kelurahan                  = $this->input->post('id_kelurahan');
		$region                        = explode("-", $this->m_admin->getRegion($id_kelurahan));
		$data['id_kecamatan']          = $dp['id_kecamatan']		= $region[1];
		$data['id_kabupaten']          = $dp['id_kabupaten']		= $region[2];
		$data['id_provinsi']           = $dp['id_provinsi']		= $region[3];
		$data['alamat']                = $dp['alamat']			= $this->input->post('alamat');
		$data['kodepos']               = $dp['kodepos']			= $this->input->post('kodepos');
		$data['nama_bpkb']             = $this->input->post('nama_bpkb');
		$data['longitude']             = $this->input->post('longitude');
		$data['latitude']              = $this->input->post('latitude');
		$data['alamat_sama']           = $this->input->post('tanya');
		$data['id_kelurahan2']         = $this->input->post('id_kelurahan2');
		$data['on_road_gc']            = $this->input->post('on_road_gc');
		$id_kelurahan2                 = $this->input->post('id_kelurahan2');
		$region2                       = explode("-", $this->m_admin->getRegion($id_kelurahan2));
		$data['id_kecamatan2']         = $region2[1];
		$data['id_kabupaten2']         = $region2[2];
		$data['id_provinsi2']          = $region2[3];
		$data['alamat2']               = $this->input->post('alamat2');
		$data['kodepos2']              = $this->input->post('kodepos2');
		$data['nama_penanggung_jawab'] = $dp['nama_penanggung_jawab']	= $this->input->post('nama_penanggung_jawab');
		$data['email']                 = $dp['email'] = $this->input->post('email');
		$data['no_hp']                 = $dp['no_hp'] = $this->input->post('no_hp');
		$data['status_nohp']           = $dp['status_nohp'] = $this->input->post('status_nohp');
		$data['id_program']            = $this->input->post('id_sales_program_gc');
		$data['nilai_voucher']         = $this->input->post('nilai_voucher');
		$data['nama_penjamin']         = $this->input->post('nama_penjamin');
		$data['tempat_lahir']          = $this->input->post('tempat_lahir');
		$data['tgl_lahir']             = $this->input->post('tgl_lahir_penjamin');
		$data['alamat_penjamin']       = $this->input->post('alamat_penjamin');
		$data['no_hp_penjamin']        = $this->input->post('no_hp_penjamin');
		$data['no_ktp']                = $this->input->post('no_ktp');
		$data['jenis_beli']            = $jenis_beli	= $this->input->post('jenis_beli');
		$data['id_finance_company']    = $this->input->post('id_finance_company');
		$data['id_pekerjaan']          = $this->input->post('id_pekerjaan');
		$data['tanda_jadi']          = $this->input->post('tanda_jadi');
		// $data['id_kelurahan_kk']  = $this->input->post('id_kelurahan_kk');
		// $data['kode_pos_kk']      = $this->input->post('kode_pos_kk');
		$data['faktur_pajak']      = $this->input->post('faktur_pajak');
		$data['no_fax']      = $this->input->post('no_fax');
		$data['id_event']      = $this->input->post('id_event');
		$data['tgl_pengiriman']      = $this->input->post('tgl_pengiriman');
		$data['waktu_pengiriman']      = $this->input->post('waktu_pengiriman');
		$ambil                         = $this->m_admin->getByID("tr_spk_gc", "no_spk_gc", $no_spk_gc)->row();
		$id_prospek_gc                 = $ambil->id_prospek_gc;
		if (isset($_POST['out'])) {
			$data['tgl_spk_gc'] = $this->input->post('tgl_spk_gc');
			$data['expired'] = NULL;
			// $data['expired_at'] = NULL;
		}

		$data['id_dealer']					= $this->m_admin->cari_dealer();
		$data['updated_at']					= $waktu;
		$data['updated_by']					= $login_id;

		$cek_ = $this->db->query("SELECT * FROM tr_spk_gc WHERE no_spk_gc = '$no_spk_gc'");
		$t = $cek_->row();
		$this->m_admin->update("tr_spk_gc", $data, "no_spk_gc", $no_spk_gc);
		$this->m_admin->update("tr_prospek_gc", $dp, "id_prospek_gc", $id_prospek_gc);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/gc'>";
	}
	public function edit()
	{
		$tabel         = $this->tables;
		$pk            = $this->pk;
		$id            = $this->input->get("id");
		$data['isi']   = $this->page;
		$data['title'] = "Edit " . $this->title;
		$data['set']   = "edit";
		$data['form']  = "edit";
		$filter[$pk]   = $id;
		if (isset($_GET['set'])) {
			if ($_GET['set'] == 'outstanding') {
				// $data['out']       = true;
				// $filter['expired'] = 1;
			}
		}
		$rs_spk = $this->db->get_where('tr_spk', $filter);
		if ($rs_spk->num_rows() > 0) {
			$row = $data['dt_spk'] = $rs_spk->row();
			$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
			// $data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
			$data['dt_pekerjaan'] = $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan WHERE id_pekerjaan!='9' ORDER BY id_pekerjaan ASC ");
			
			$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
			$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
			$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
			$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
			$data['dt_prospek'] = $this->m_admin->getSortCond("tr_prospek", "no_hp", "id_tipe_kendaraan", "alamat", "ASC");
			$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
			$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
			$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
			$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
			$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi", "hobi", "ASC");
			$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
			$data['event'] = $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");
			$data['anggota_'] = $this->db->query("SELECT * FROM tr_spk_anggota_kk WHERE no_spk='$id'")->result();
			$data['file_pendukung_'] = $this->db->query("SELECT * FROM tr_spk_file WHERE no_spk='$id'")->result();
			$id_dealer = $this->m_admin->cari_dealer();
			$data['dt_customer'] = $this->db->query("SELECT tr_prospek.*,ms_tipe_kendaraan.*,ms_kelurahan.*,ms_karyawan_dealer.nama_lengkap FROM tr_prospek INNER JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
							LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
							WHERE tr_prospek.id_dealer = '$id_dealer'
							ORDER BY tr_prospek.id_customer ASC");
			$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
			if (isset($_GET['set'])) {
				// $data['dt_spk']->jenis_beli         = '';
				// $data['dt_spk']->tanda_jadi         = 0;
				// $data['dt_spk']->diskon             = 0;
				// $data['dt_spk']->harga_off_road     = 0;
				// $data['dt_spk']->harga              = 0;
				// $data['dt_spk']->ppn                = 0;
				// $data['dt_spk']->biaya_bbn          = 0;
				// $data['dt_spk']->harga_tunai        = 0;
				// $data['dt_spk']->voucher_1          = NULL;
				// $data['dt_spk']->voucher_2          = NULL;
				// $data['dt_spk']->voucher_tambahan_1 = NULL;
				// $data['dt_spk']->voucher_tambahan_2 = NULL;
				// $data['dt_spk']->program_gabungan   = NULL;
				// $data['dt_spk']->program_khusus_1   = NULL;
				// $data['dt_spk']->program_khusus_2   = NULL;
				// $data['dt_spk']->program_umum       = '';
				// $data['dt_spk']->dp_stor            = 0;
				// $data['dt_spk']->tenor              = 0;
				// $data['dt_spk']->angsuran           = 0;
				// $data['dt_spk']->file_ktp_2                = '';
				// $data['dt_spk']->nama_penjamin             = '';
				// $data['dt_spk']->hub_penjamin              = '';
				// $data['dt_spk']->no_ktp_penjamin           = '';
				// $data['dt_spk']->alamat_penjamin           = '';
				// $data['dt_spk']->no_hp_penjamin            = '';
				// $data['dt_spk']->tempat_lahir_penjamin     = '';
				// $data['dt_spk']->tgl_lahir_penjamin        = '';
				// $data['dt_spk']->pekerjaan_penjamin        = '';
				// $data['dt_spk']->penghasilan_penjamin      = '';
				# code...
			}
			// send_json($data);
			$dt_kk = $this->db->query("
			SELECT * 
			FROM ms_kelurahan kel
			LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
			LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
			LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi WHere kel.id_kelurahan='{$row->id_kelurahan_kk}'
			")->row();
			$data['dt_spk']->kelurahan_kk = $dt_kk->kelurahan;
			$data['dt_spk']->kecamatan_kk = $dt_kk->kecamatan;
			$data['dt_spk']->kabupaten_kk = $dt_kk->kabupaten;
			$data['dt_spk']->provinsi_kk = $dt_kk->provinsi;
			$this->template($data);
		}
	}
	public function detail()
	{
		$tabel          = $this->tables;
		$pk             = $this->pk;
		$id             = $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']  = "Detail " . $this->title;
		$data['set']    = "edit";
		$data['form']    = "detail";

		$res_spk = $this->m_admin->getByID($tabel, $pk, $id);
		if ($res_spk->num_rows() > 0) {
			$row = $data['dt_spk'] = $res_spk->row();
		}
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "agama", "ASC");
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "pengeluaran", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->db->query("SELECT ms_item.id_warna,ms_warna.* from ms_item 
				inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
				WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'
				GROUP BY ms_item.id_warna
				ORDER BY ms_warna.warna ASC");
		$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
		$data['dt_prospek'] = $this->m_admin->getSortCond("tr_prospek", "no_hp", "id_tipe_kendaraan", "alamat", "ASC");
		$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "pendidikan", "ASC");
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
		$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
		$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
		$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi", "hobi", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$data['event'] = $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");
		$data['anggota_'] = $this->db->query("SELECT * FROM tr_spk_anggota_kk WHERE no_spk='$id'")->result();
		$data['file_pendukung_'] = $this->db->query("SELECT * FROM tr_spk_file WHERE no_spk='$id'")->result();
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek INNER JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek.id_dealer = '$id_dealer'
							ORDER BY tr_prospek.id_customer ASC");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
		
		if($row->id_kelurahan_kk !=''){
			$dt_kk = $this->db->query("
				SELECT * 
				FROM ms_kelurahan kel
				LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
				LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
				LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi WHere kel.id_kelurahan='{$row->id_kelurahan_kk}'
			")->row();
			$data['dt_spk']->kelurahan_kk = $dt_kk->kelurahan;
			$data['dt_spk']->kecamatan_kk = $dt_kk->kecamatan;
			$data['dt_spk']->kabupaten_kk = $dt_kk->kabupaten;
			$data['dt_spk']->provinsi_kk = $dt_kk->provinsi;
		}else{
			$data['dt_spk']->kelurahan_kk = '';
			$data['dt_spk']->kecamatan_kk = '';
			$data['dt_spk']->kabupaten_kk = '';
			$data['dt_spk']->provinsi_kk = '';
		}
		$this->template($data);
	}
	public function edit_gc()
	{
		$tabel 	= "tr_spk_gc";
		$pk 		= "no_spk_gc";
		$id 		= $this->input->get("id_c");
		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title . " Group Customer";
		$data['set']		= "edit_gc";
		$data['form']		= "edit";
		if (isset($_GET['set'])) {
			$data['out'] = true;
		}
		$data['dt_spk'] = $this->m_admin->getByID($tabel, $pk, $id);
		if ($data['dt_spk']->num_rows() > 0) {
			$row = $data['dt_spk']->row();
		}
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek INNER JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek.id_dealer = '$id_dealer'
							ORDER BY tr_prospek.id_customer ASC");
		/*
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
		*/
		$data['dt_npwp'] = $this->db->query("SELECT tr_prospek_gc.*,ms_kelurahan.*, ms_karyawan_dealer.nama_lengkap  FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");

		$this->template($data);
	}
	public function detail_gc()
	{
		$tabel 	= "tr_spk_gc";
		$pk 		= "no_spk_gc";
		$id 		= $this->input->get("id_c");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title . " Group Customer";
		$data['set']		= "edit_gc";
		$data['form']		= "detail";
		$data['dt_spk'] = $this->m_admin->getByID($tabel, $pk, $id);
		if ($data['dt_spk']->num_rows() > 0) {
			$row = $data['dt_spk']->row();
		}
		$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "pekerjaan", "ASC");
		$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
		$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek INNER JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek.id_dealer = '$id_dealer'
							ORDER BY tr_prospek.id_customer ASC");
		$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
		$this->template($data);
	}
	public function update()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');

		$config['upload_path'] 			= './assets/panel/files/';
		$config['allowed_types'] 		= 'jpg|jpeg|png';
		$config['max_size']					= '100';
		$config2['upload_path'] 		= './assets/panel/files/';
		$config2['allowed_types'] 		= 'jpg|jpeg|png';
		$config2['max_size']				= '500';
		$type_ktp1 		= $_FILES["file_foto"]["type"];
		$type_kk 			= $_FILES["file_kk"]["type"];
		$jenis_beli = $this->input->post('jenis_beli');

		if ($jenis_beli == 'Kredit') {
			$type_ktp2 = $_FILES["file_ktp_2"]["type"];
		}
		$format_kk = "";
		$format_ktp_2 = "";
		$format_foto = "";
		$file_foto = "";
		$file_kk = "";
		$file_ktp_2 = "";
		if (isset($_POST['file_foto'])) {
			if ($type_ktp1 == 'image/jpeg' or $type_ktp1 == 'image/png' or $type_ktp1 == 'image/jpg') {
				$format_foto = "ok";
			} else {
				$format_foto = "salah";
			}
		}
		if (isset($_POST['file_kk'])) {
			if ($type_kk == 'image/jpeg' or $type_kk == 'image/png' or $type_kk == 'image/jpg') {
				$format_kk = "ok";
			} else {
				$format_kk = "salah";
			}
		}
		if (isset($_POST['file_ktp_2'])) {
			if ($type_ktp2 == 'image/jpeg' or $type_ktp2 == 'image/png' or $type_ktp2 == 'image/jpg') {
				$format_ktp_2 = "ok";
			} else {
				$format_ktp_2 = "salah";
			}
		}
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id_)->num_rows();
		if ($id == $id_) {

			$this->upload->initialize($config);
			if ($this->upload->do_upload('file_foto')) {
				$da['file_foto'] = $data['file_foto'] = $this->upload->file_name;
				$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
			} else {
				$file_foto = "besar";
			}
			$this->upload->initialize($config2);
			if ($this->upload->do_upload('file_kk')) {
				$da['file_kk'] = $data['file_kk'] = $this->upload->file_name;
				$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
			} else {
				$file_kk = "besar";
			}
			$this->upload->initialize($config);
			if ($jenis_beli == 'Kredit') {
				if ($this->upload->do_upload('file_ktp_2')) {
					$da['file_ktp_2'] = $data['file_ktp_2'] = $this->upload->file_name;
					$one = $this->m_admin->getByID($tabel, $pk, $id)->row();
				} else {
					$file_ktp_2 = "besar";
				}
			}

			$isi_ktp = $this->input->post('no_ktp');
			$ktp = strlen($isi_ktp);
			// if ($ktp < 16) {
			// 	$jum = 16 - $ktp;
			// 	for ($i = 1; $i <= $jum; $i++) {
			// 		$r = $r . "0";
			// 	}
			// 	$ktp_f = $r . $isi_ktp;
			// } else {
			// 	$ktp_f = $isi_ktp;
			// }
			$ktp_f = $isi_ktp;
			$r2 = "";
			$isi_ktp2 = $this->input->post('no_ktp_penjamin');
			$ktp2 = strlen($isi_ktp2);
			if ($ktp2 < 16) {
				$jum = 16 - $ktp2;
				for ($i = 1; $i <= $jum; $i++) {
					$r2 = $r2 . "0";
				}
				$ktp_p = $r2 . $isi_ktp2;
			} else {
				$ktp_p = $isi_ktp2;
			}
			$no_spk = $data['no_spk'] = $da['no_spk'] 						= $this->input->post('no_spk');
			$data['tgl_spk'] = $da['tgl_spk'] 						= $this->input->post('tgl_spk');
			$data['id_customer'] = $da['id_customer'] 				= $this->input->post('id_customer');
			$nama_konsumen = $data['nama_konsumen'] = $da['nama_konsumen']				= $this->input->post('nama_konsumen');
			$data['tempat_lahir'] = $da['tempat_lahir'] 			= $this->input->post('tempat_lahir');
			$data['tgl_lahir'] = $da['tgl_lahir'] 					= $this->input->post('tgl_lahir');
			$data['jenis_wn'] = $da['jenis_wn'] 					= $this->input->post('jenis_wn');
			$no_ktp = $data['no_ktp'] = $da['no_ktp'] 						= $ktp_f;
			$data['no_kk'] = $da['no_kk'] 							= $this->input->post('no_kk');
			$data['npwp'] = $da['npwp'] 							= $this->input->post('npwp');
			$data['id_kelurahan'] = $da['id_kelurahan'] 			= $this->input->post('id_kelurahan');
			$id_kelurahan 							= $this->input->post('id_kelurahan');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan));
			$data['id_kecamatan'] = $da['id_kecamatan'] 			= $region[1];
			$data['id_kabupaten'] = $da['id_kabupaten'] 			= $region[2];
			$data['id_provinsi'] = $da['id_provinsi'] 				= $region[3];
			$alamat = $data['alamat'] = $da['alamat'] 						= $this->input->post('alamat');
			$data['kodepos'] = $da['kodepos'] 						= $this->input->post('kodepos');
			$d_lokasi = $this->input->post('denah_lokasi');
			if ($d_lokasi != "") {
				$p_lokasi = explode(',', $d_lokasi);
				if (is_array($p_lokasi) and count($p_lokasi) == 2) {
					$latitude = str_replace(' ', '', $p_lokasi[0]);
					$longitude = str_replace(' ', '', $p_lokasi[1]);
					if ($latitude != "" and $longitude != "") {
						$denah_lokasi 			= $d_lokasi;
					} else {
						$denah_lokasi = "-1.613510, 103.594603";
					}
				} else {
					$denah_lokasi = "-1.613510, 103.594603";
				}
			} else {
				$denah_lokasi = "-1.613510, 103.594603";
			}
			$data['denah_lokasi'] = $da['denah_lokasi'] = $denah_lokasi;
			$data['alamat_sama'] = $da['alamat_sama']           = $this->input->post('tanya');
			$data['id_kelurahan2'] = $da['id_kelurahan2']         = $this->input->post('id_kelurahan2');

			$id_kelurahan2 							= $this->input->post('id_kelurahan2');
			$region = explode("-", $this->m_admin->getRegion($id_kelurahan2));

			$data['id_kecamatan2'] = $da['id_kecamatan2']         = $region[1];
			$data['id_kabupaten2'] = $da['id_kabupaten2']         = $region[2];
			$data['id_provinsi2'] = $da['id_provinsi2']          = $region[3];
			$data['alamat2'] = $da['alamat2']               = $this->input->post('alamat2');
			$data['kodepos2'] = $da['kodepos2']              = $this->input->post('kodepos2');
			$data['status_rumah'] = $da['status_rumah']          = $this->input->post('status_rumah');
			$data['lama_tinggal'] = $da['lama_tinggal']          = $this->input->post('lama_tinggal');
			$data['pekerjaan'] = $da['pekerjaan']             = $this->input->post('pekerjaan');
			$data['lama_kerja'] = $da['lama_kerja']            = $this->input->post('lama_kerja');
			$data['jabatan'] = $da['jabatan']               = $this->input->post('jabatan');
			$data['pengeluaran_bulan'] = $da['pengeluaran_bulan']     = $this->input->post('pengeluaran_bulan');
			$data['penghasilan'] = $da['penghasilan']           = preg_replace('/[^0-9\  ]/', '', $this->input->post('penghasilan'));
			$data['no_hp'] = $da['no_hp']                 = $this->input->post('no_hp');
			$data['status_hp'] = $da['status_hp']             = $this->input->post('status_hp');
			$data['no_hp_2'] = $da['no_hp_2']               = $this->input->post('no_hp_2');
			$data['status_hp_2'] = $da['status_hp_2']           = $this->input->post('status_hp_2');
			$no_telp = $data['no_telp'] = $da['no_telp']               = preg_replace('/[^0-9\  ]/', '', $this->input->post('no_telp'));
			$email = $data['email'] = $da['email']                 = $this->input->post('email');
			$data['refferal_id'] = $da['refferal_id']           = $this->input->post('refferal_id');
			$data['robd_id'] = $da['robd_id']               = $this->input->post('robd_id');
			$ket = $data['keterangan'] = $da['keterangan']            = $this->input->post('keterangan');
			$data['nama_ibu'] = $da['nama_ibu']              = $this->input->post('nama_ibu');
			$data['tgl_ibu'] = $da['tgl_ibu']               = $this->input->post('tgl_ibu');
			$id_tipe_kendaraan = $data['id_tipe_kendaraan'] = $da['id_tipe_kendaraan']     = $this->input->post('id_tipe_kendaraan');
			$id_warna = $data['id_warna'] = $da['id_warna']              = $this->input->post('id_warna');
			$data['harga'] = $da['harga']                 = $this->input->post('harga');
			$data['ppn'] = $da['ppn']                   = $this->input->post('ppn');
			$data['harga_off_road'] = $da['harga_off_road']        = $this->input->post('harga_off');
			$data['harga_on_road'] = $da['harga_on_road']         = $this->input->post('harga_on');
			$data['biaya_bbn'] = $da['biaya_bbn']             = $this->input->post('biaya_bbn');
			$jenis_beli            				 = $this->input->post('jenis_beli');
			$data['jenis_beli'] = $da['jenis_beli']            = $jenis_beli;
			$data['the_road'] = $da['the_road']              = $this->input->post('the_road');
			$data['harga_tunai'] = $da['harga_tunai']           = $this->input->post('harga_tunai');
			$data['program_khusus_1'] = $da['program_khusus_1']      = $this->input->post('program_khusus_1');
			$data['program_umum'] = $da['program_umum']          = $this->input->post('program_umum');
			$data['program_gabungan'] = $da['program_gabungan']          = $this->input->post('program_gabungan');
			$data['voucher_1'] = $da['voucher_1']             = $this->input->post('voucher_1');
			$data['voucher_tambahan_1'] = $da['voucher_tambahan_1']    = $this->input->post('voucher_tambahan_1');
			$total_bayar = $data['harga_tunai'] - $data['voucher_1'] - $data['voucher_tambahan_1'];
			$data['total_bayar'] = $da['total_bayar']           = $total_bayar;
			// $data['total_bayar']=$da['total_bayar']           = $this->input->post('total_bayar');	
			$id_finance_company = $data['id_finance_company'] = $da['id_finance_company']    = $this->input->post('id_finance_company');
			$uang_muka = $data['uang_muka'] = $da['uang_muka']             = $this->input->post('uang_muka');
			$data['program_khusus_2'] = $da['program_khusus_2']      = $this->input->post('program_khusus_2');
			$data['voucher_2'] = $da['voucher_2']             = $this->input->post('voucher_2') == '' ? 0 : $this->input->post('voucher_2');
			$data['voucher_2'] = $da['voucher_2']           	 = preg_replace('/[^0-9\  ]/', '', $this->input->post('nilai_voucher2'));
			$data['voucher_tambahan_2'] = $da['voucher_tambahan_2']    = $this->input->post('voucher_tambahan_2');
			$tenor = $data['tenor'] = $da['tenor']                 = $this->input->post('tenor');
			$dp_stor = $uang_muka - $data['voucher_2'] - $data['voucher_tambahan_2'];
			$nilai_dp = $data['dp_stor'] = $da['dp_stor']               = $dp_stor;
			$angsuran = $data['angsuran'] = $da['angsuran']              = $this->input->post('angsuran');
			$data['nama_penjamin'] = $da['nama_penjamin'] = $nama_penjamin  = $this->input->post('nama_penjamin');
			$data['hub_penjamin'] = $da['hub_penjamin']          = $this->input->post('hub_penjamin');
			$no_ktp_penjamin = $data['no_ktp_penjamin'] = $da['no_ktp_penjamin']       = $ktp_p;
			$data['no_hp_penjamin'] = $da['no_hp_penjamin']        = $this->input->post('no_hp_penjamin');
			$data['alamat_penjamin'] = $da['alamat_penjamin']       = $this->input->post('alamat_penjamin');
			$data['tempat_lahir_penjamin'] = $da['tempat_lahir_penjamin'] = $tempat_lahir_penjamin = $this->input->post('tempat_lahir_penjamin');
			$data['tgl_lahir_penjamin'] = $da['tgl_lahir_penjamin']   = $tgl_lahir_penjamin = $this->input->post('tgl_lahir_penjamin');
			$da['pekerjaan_penjamin'] = $data['pekerjaan_penjamin']    = $this->input->post('pekerjaan_penjamin');
			$da['penghasilan_penjamin'] = $data['penghasilan_penjamin']  = $this->input->post('penghasilan_penjamin');
			$da['nama_bpkb'] = $data['nama_bpkb']             = $this->input->post('nama_bpkb');
			$da['id_dealer'] = $data['id_dealer']             = $this->m_admin->cari_dealer();
			$da['status_survey'] = $data['status_survey']				 = "baru";
			$da['updated_at'] = $data['updated_at']            = $waktu;
			$da['updated_by'] = $data['updated_by']            = $login_id;
			$da['created_at']            = $waktu;
			$da['updated_by']            = $login_id;
			$data['tgl_pengiriman']  = $this->input->post('tgl_pengiriman');
			$data['waktu_pengiriman']  = $this->input->post('waktu_pengiriman');
			$data['nama_bpkb_stnk']  = $this->input->post('nama_bpkb_stnk');
			$data['no_ktp_bpkb']     = $this->input->post('no_ktp_bpkb');
			$data['alamat_ktp_bpkb'] = $this->input->post('alamat_ktp_bpkb');
			$data['longitude']       = $this->input->post('longitude');
			$data['latitude']        = $this->input->post('latitude');
			$data['rt']              = $this->input->post('rt');
			$data['rw']              = $this->input->post('rw');
			$data['fax']             = $this->input->post('fax');
			$data['kode_ppn']        = $this->input->post('kode_ppn');
			$data['tanda_jadi']      = $this->input->post('tanda_jadi');
			$data['diskon']          = $this->input->post('diskon');
			$data['id_event']        = $this->input->post('id_event');
			$data['no_kk']           = $this->input->post('no_kk');
			$data['alamat_kk']       = $this->input->post('alamat_kk');
			$data['id_kelurahan_kk']  = $this->input->post('id_kelurahan_kk');
			$data['kode_pos_kk']      = $this->input->post('kode_pos_kk');
			$data['faktur_pajak']      = $this->input->post('faktur_pajak');
			if (isset($_POST['out'])) {
				$data['tgl_spk']       = $this->input->post('tgl_spk');
			}
			$anggota                 = $this->input->post('anggota_kk');
			for ($i = 0; $i < count($anggota); $i++) {
				$anggota_[] = ['no_spk' => $no_spk, 'anggota' => $anggota[$i]];
			}
			$cek_spk = $this->db->get_where('tr_spk', ['no_spk' => $no_spk])->row();
			$id_dealer = $this->m_admin->cari_dealer();

			if ($cek_spk->id_tipe_kendaraan != $id_tipe_kendaraan || $cek_spk->id_warna != $id_warna) {
				// // redirect('dealer/sales_order','refresh');
				// echo $id_tipe_kendaraan.'--'.$cek_spk->id_tipe_kendaraan;
				// echo '</br>'.$id_warna.'--'.$cek_spk->id_warna;
				$no_mesin = null;
				$dt = $this->get_nosin_fifo($id_tipe_kendaraan, $id_warna);
				if ($dt == false) {
					$cek_ind = $this->db->get_where('tr_po_dealer_indent', ['id_spk' => $no_spk]);
					if ($cek_ind->num_rows() > 0) {
						$indt = $cek_ind->row();
						// $del_indent = ['id_indent' => $indt->id_indent];

						//cancel indent
						$this->db->where('id_indent', $indt->id_indent);
						$this->db->update('tr_po_dealer_indent', array(
							'status' =>'canceled',
							'id_reasons' => '12', //  ganti varian atau tipe
						));
						// insert indent baru
						$ins_indent = [
							'id_indent' => $this->get_kode_indent(),
							'id_spk'            => $no_spk,
							'id_dealer'         => $id_dealer,
							'nama_konsumen'     => $nama_konsumen,
							'alamat'            => $alamat,
							'no_ktp'            => $no_ktp,
							'no_telp'           => $no_telp,
							'email'             => $email,
							'id_tipe_kendaraan' => $id_tipe_kendaraan,
							'id_warna'          => $id_warna,
							'nilai_dp'          => $nilai_dp,
							'ket'               => $ket,
							'qty'               => 1,
							'status'			=> 'requested',
							'tgl'               => date('Y-m-d'),
							'created_at'        => $waktu,
							'created_by'        => $login_id
						];

					} else {
						$ins_indent = [
							'id_indent' => $this->get_kode_indent(),
							'id_spk'            => $no_spk,
							'id_dealer'         => $id_dealer,
							'nama_konsumen'     => $nama_konsumen,
							'alamat'            => $alamat,
							'no_ktp'            => $no_ktp,
							'no_telp'           => $no_telp,
							'email'             => $email,
							'id_tipe_kendaraan' => $id_tipe_kendaraan,
							'id_warna'          => $id_warna,
							'nilai_dp'          => $nilai_dp,
							'ket'               => $ket,
							'qty'               => 1,
							'status'			=> 'requested',
							'tgl'               => date('Y-m-d'),
							'created_at'        => $waktu,
							'created_by'        => $login_id
						];
						$id_po = $this->newPO_ID('indent', $id_dealer);
						$item = $this->db->query("SELECT id_item FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
						$id_item = ($item->num_rows() > 0) ? $item->row()->id_item : "";
						$bulan  = date("m");
						$tahun  = date("Y");
						$po_indent = [
							'id_po' 				=> $id_po,
							'bulan'         => $bulan,
							'tahun'         => $tahun,
							'tgl'     			=> date('Y-m-d'),
							'id_dealer'     => $id_dealer,
							'created_at'    => $waktu,
							'created_by'    => $login_id,
							'po_from'       => $no_spk,
							'status' 				=> 'input',
							'jenis_po' 				=> 'PO Indent',
							'submission_deadline' => date('Y-m-d'),
							'id_pos_dealer' => ''
						];
						$po_indent_detail = [
							'id_po' 				=> $id_po,
							'id_item'         => $id_item,
							'qty_order'         => 1,
							'qty_po_fix'         => 1
						];
					}
					$status = 'booking';
					$cek_penerimaan = $this->db->get_where('tr_penerimaan_unit_dealer_detail', ['no_mesin' => $cek_spk->no_mesin_spk])->num_rows();
					if ($cek_penerimaan > 0) {
						$kosong_penerimaan[] = ['no_mesin' => $cek_spk->no_mesin_spk, 'no_spk' => null, 'status_on_spk' => null];
					}
				} else {
					$no_mesin       = $dt;
					$status         = 'booking';
					$kosong_penerimaan[] = ['no_mesin' => $cek_spk->no_mesin_spk, 'no_spk' => null, 'status_on_spk' => null];
					$upd_penerimaan = ['no_spk' => $no_spk, 'status_on_spk' => $status, 'booking_at' => $waktu, 'booking_by' => $login_id];

					$cek_ind = $this->db->get_where('tr_po_dealer_indent', ['id_spk' => $no_spk]);
					if ($cek_ind->num_rows() > 0) {
						$indt = $cek_ind->row();
						
						//cancel indent
						$this->db->where('id_indent', $indt->id_indent);
						$this->db->update('tr_po_dealer_indent', array(
							'status' =>'canceled',
							'id_reasons' => '12', //  ganti varian atau tipe
						));
					}
					

				}
				$data['no_mesin_spk']    = $no_mesin;
				// $data['status_spk']      = $status;
			}
			$config['upload_path']   = './assets/panel/spk_file';
			$config['allowed_types'] = 'jpg|png|jpeg|bmp|pdf|doc|docx|xls|xlsx';
			$config['max_size']      = '2048';
			$config['max_width']     = '2000';
			$config['max_height']    = '2000';
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name']  = TRUE;
			$file_pendukung = count($_FILES['file_pendukung']['name']);
			$file = $this->input->post('file');
			$nama_file      = $this->input->post('nama_file');
			for ($i = 0; $i < $file_pendukung; $i++) {
				if ($file[$i] == '') {
					$_FILES['file']['name']     = $_FILES['file_pendukung']['name'][$i];
					$_FILES['file']['type']     = $_FILES['file_pendukung']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['file_pendukung']['tmp_name'][$i];
					$_FILES['file']['error']    = $_FILES['file_pendukung']['error'][$i];
					$_FILES['file']['size']     = $_FILES['file_pendukung']['size'][$i];
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('file')) {
						// Uploaded file data
						$fileData                  = $this->upload->data();
						$ins_file[$i]['file']      = $fileData['file_name'];
						$ins_file[$i]['no_spk']    = $no_spk;
						$ins_file[$i]['nama_file'] = $nama_file[$i];
					} else {
						// echo $this->upload->display_errors();
					}
				} else {
					$not_delete_pendukung[] = "'" . $file[$i] . "'";
				}
			}
			if ($format_kk == 'salah') {
				$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($format_foto == 'salah') {
				$_SESSION['pesan'] 	= "Format file KTP/KK yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($format_ktp_2 == 'salah') {
				$_SESSION['pesan'] 	= "Format file KTP/KK Penjamin yg diupload tidak sesuai, tipe file yg harus diupload adalah (*.jpg,*.png)!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($file_foto == 'gagal') {
				$_SESSION['pesan'] 	= "Ukuran file KTP/KK yg diupload terlalu besar!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($file_kk == 'gagal') {
				$_SESSION['pesan'] 	= "Ukuran file KTP/KK yg diupload terlalu besar!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($file_ktp_2 == 'gagal') {
				$_SESSION['pesan'] 	= "Ukuran file KTP/KK Penjamin yg diupload terlalu besar!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($id_finance_company == '' and $jenis_beli == 'Kredit') {
				$_SESSION['pesan'] 	= "Tentukan dulu Finance Company!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($no_ktp_penjamin == '' and $jenis_beli == 'Kredit') {
				$_SESSION['pesan'] 	= "No KTP Penjamin harus diisi!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($nama_penjamin == '' and $jenis_beli == 'Kredit') {
				$_SESSION['pesan'] 	= "Nama Penjamin harus diisi!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($ktp < 16) {
				$_SESSION['pesan'] 	= "Panjang Karakter No KTP Pemohon tidak sesuai!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($ktp2 < 16 and $jenis_beli == 'Kredit') {
				$_SESSION['pesan'] 	= "Panjang Karakter No KTP Penjamin tidak sesuai!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($tempat_lahir_penjamin == '' and $jenis_beli == 'Kredit') {
				$_SESSION['pesan'] 	= "Tempat Lahir Penjamin harus diisi!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($tgl_lahir_penjamin == '' and $jenis_beli == 'Kredit') {
				$_SESSION['pesan'] 	= "Tgl Lahir Penjamin harus diisi!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} elseif ($jenis_beli == 'Kredit' and ($angsuran == '' or $angsuran == 0 or $tenor == '' or $tenor == 0 or $uang_muka == '' or $uang_muka == 0)) {
				$_SESSION['pesan'] 	= "DP Gross, Tenor dan Angsuran harus diisi dan tidak boleh 0!";
				$_SESSION['tipe'] 	= "danger";
				$_SESSION['id_warna'] 	= $this->input->post("id_warna");
				$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
				echo "<script>history.go(-1)</script>";
			} else {
				if ($jenis_beli == 'Kredit') {
					$data['program_umum']     = $this->input->post('program_umum');
					$data['program_gabungan'] = $this->input->post('program_gabungan_k');
					if (($data['program_umum'] == '' || $data['program_umum'] == NULL) && $data['voucher_2'] > 0) {
						$_SESSION['pesan'] 	= "Sales program tidak terpilih, tetapi voucher terisi !";
						$_SESSION['tipe'] 	= "danger";
						$_SESSION['id_warna'] 	= $this->input->post("id_warna");
						$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
						echo "<script>history.go(-1)</script>";
					}
				} elseif ($jenis_beli == 'Cash') {
					if (($data['program_umum'] == '' || $data['program_umum'] == NULL) && $data['voucher_1'] > 0) {
						$_SESSION['pesan'] 	= "Sales program tidak terpilih, tetapi voucher terisi !";
						$_SESSION['tipe'] 	= "danger";
						$_SESSION['id_warna'] 	= $this->input->post("id_warna");
						$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
						echo "<script>history.go(-1)</script>";
					}
				}
				$spk = $this->m_admin->getByID("tr_spk", "no_spk", $id)->row();

				$this->db->trans_begin();
				if ($jenis_beli == 'Kredit') {
					$update_old_survey = $this->db->query("SELECT no_order_survey, no_spk FROM tr_order_survey WHERE no_spk='$no_spk' AND status_survey in ('baru','') ");
					if ($update_old_survey->num_rows() > 0) {
						foreach ($update_old_survey->result() as $dt) {
							$data_survey['status_survey'] = 'cancel';
							$this->m_admin->update('tr_order_survey', $data_survey, 'no_order_survey', $dt->no_order_survey);
						}
					}

					$da['no_order_survey'] 	= $this->m_admin->cari_id("tr_order_survey", "no_order_survey");
					$this->m_admin->insert("tr_order_survey", $da);
				}
				$this->m_admin->update($tabel, $data, $pk, $id);
				$this->db->delete('tr_spk_anggota_kk', ['no_spk' => $no_spk]);
				if (isset($anggota_)) {
					$this->db->insert_batch('tr_spk_anggota_kk', $anggota_);
				}
				if (isset($not_delete_pendukung)) {
					$not_delete = implode(',', $not_delete_pendukung);
					$no_delete = $this->db->query("SELECT * FROM tr_spk_file WHERE no_spk='$no_spk' AND file NOT IN($not_delete)");
					if ($no_delete->num_rows() > 0) {
						foreach ($no_delete->result() as $dt) {
							if (file_exists(FCPATH . "assets/panel/spk_file/" . $dt->file)) {
								unlink("assets/panel/spk_file/" . $dt->file); //Hapus Gambar
							}
							$this->db->delete('tr_spk_file', ['file' => $dt->file]);
						}
					}
				} else {
					$this->db->delete('tr_spk_file', ['no_spk' => $no_spk]);
				}
				if (isset($ins_file)) {
					$this->db->insert_batch('tr_spk_file', $ins_file);
				}
				if (isset($kosong_penerimaan)) {
					$this->db->update_batch('tr_penerimaan_unit_dealer_detail', $kosong_penerimaan, 'no_mesin');
				}
				if (isset($del_indent)) {
					$this->db->delete('tr_po_dealer_indent', $del_indent);
				}
				if (isset($upd_penerimaan)) {
					$this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $no_mesin]);
				}
				if (isset($ins_indent)) {
					if ($this->input->post('tanda_jadi') == '' OR $this->input->post('tanda_jadi') == '0') {
							$_SESSION['pesan'] 	= "SPK ini Indent, tanda jadi tidak boleh di isi 0";
							$_SESSION['tipe'] 	= "danger";
							$_SESSION['id_warna'] 	= $this->input->post("id_warna");
							$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
							echo "<script>history.go(-1)</script>";
					}
					$this->db->insert('tr_po_dealer_indent', $ins_indent);
				}
				if (isset($po_indent)) {
					$this->db->insert('tr_po_dealer', $po_indent);
				}
				if (isset($po_indent_detail)) {
					$this->db->insert('tr_po_dealer_detail', $po_indent_detail);
				}
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$_SESSION['pesan'] 	= "Telah terjadi kesalahan";
					$_SESSION['tipe'] 	= "danger";
					$_SESSION['id_warna'] 	= $this->input->post("id_warna");
					$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/add'>";
				} else {
					$this->db->trans_commit();

					$_SESSION['pesan'] 	= "Data has been updated successfully";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/add'>";
				}
			}
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			$_SESSION['id_warna'] 	= $this->input->post("id_warna");
			$_SESSION['id_tipe'] 	= $this->input->post("id_tipe_kendaraan");
			echo "<script>history.go(-1)</script>";
		}
	}
	public function approve_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_spk_gc";
		$pk 				= "no_spk_gc";
		$id					= $this->input->get("id");
		$cek_approval  = $this->m_admin->cek_approval($tabel, $pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$dt['status'] = 'approved';
			$dt['updated_by'] = $login_id;
			$dt['updated_at'] = $waktu;
			$this->m_admin->update($tabel, $dt, "no_spk_gc", $id);
			$cek = $this->m_admin->getByID("tr_spk_gc", "no_spk_gc", $id)->row();
			if ($cek->jenis_beli == "Kredit") {
				$data['no_spk_gc'] 					= $cek->no_spk_gc;
				$data['tgl_spk_gc'] 				= $cek->tgl_spk_gc;
				$data['no_npwp'] 						= $cek->no_npwp;
				$data['nama_npwp']					= $cek->nama_npwp;
				$data['no_telp'] 						= $cek->no_telp;
				$data['jenis_gc'] 					= $cek->jenis_gc;
				$data['tgl_berdiri'] 				= $cek->tgl_berdiri;
				$data['id_kelurahan'] 			= $cek->id_kelurahan;
				$region = explode("-", $this->m_admin->getRegion($cek->id_kelurahan));
				$data['id_kecamatan'] 			= $region[1];
				$data['id_kabupaten'] 			= $region[2];
				$data['id_provinsi'] 				= $region[3];
				$data['alamat'] 						= $cek->alamat;
				$data['kodepos'] 						= $cek->kodepos;
				$data['alamat_sama'] 				= $cek->alamat_sama;
				$data['id_kelurahan2'] 			= $cek->id_kelurahan2;
				$id_kelurahan2 							= $cek->id_kelurahan2;
				$region = explode("-", $this->m_admin->getRegion($id_kelurahan2));
				$data['id_kecamatan2'] 			= $region[1];
				$data['id_kabupaten2'] 			= $region[2];
				$data['id_provinsi2'] 			= $region[3];
				$data['alamat2'] 						= $cek->alamat2;
				$data['kodepos2'] 					= $cek->kodepos2;
				$data['nama_penanggung_jawab'] 		=  $cek->nama_penanggung_jawab;
				$data['email'] 							= $cek->email;
				$data['no_hp'] 							= $cek->no_hp;
				$data['status_nohp'] 				= $cek->status_nohp;
				$data['id_program'] 				= $cek->id_program;
				$data['nilai_voucher'] 			= $cek->nilai_voucher;
				$data['nama_penjamin'] 			= $cek->nama_penjamin;
				$data['tempat_lahir'] 			= $cek->tempat_lahir;
				$data['tgl_lahir'] 					= $cek->tgl_lahir;
				$data['alamat_penjamin'] 		= $cek->alamat_penjamin;
				$data['no_hp_penjamin'] 		= $cek->no_hp_penjamin;
				$data['no_ktp'] 						= $cek->no_ktp;
				$data['jenis_beli'] 				= $cek->jenis_beli;
				$data['id_finance_company'] = $cek->id_finance_company;
				$data['id_pekerjaan'] 			= $cek->id_pekerjaan;
				$data['status_survey'] 			= "process";

				$data['id_dealer']					= $cek->id_dealer;
				$data['created_at']					= $waktu;
				$data['created_by']					= $login_id;
				$this->m_admin->insert("tr_order_survey_gc", $data);
			}
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/gc'>";
		}
	}
	public function reject_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_spk_gc";
		$pk 				= "no_spk_gc";
		$id					= $this->input->get("id");
		$cek_approval  = $this->m_admin->cek_approval($tabel, $pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$dt['status'] = 'rejected';
			$dt['updated_by'] = $login_id;
			$dt['updated_at'] = $waktu;
			$this->m_admin->update($tabel, $dt, "no_spk_gc", $id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk/gc'>";
		}
	}
	public function approve()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");
		$cek_approval  = $this->m_admin->cek_approval($tabel, $pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$da['updated_at']					= $waktu;
			$da['updated_by']					= $login_id;
			$da['status_spk']					= "approved";
			$da['status_survey']			= "baru";
			$this->m_admin->update($tabel, $da, $pk, $id);
			$isi_ktp = $this->input->post('no_ktp');

			$spk = $this->m_admin->getByID("tr_spk", "no_spk", $id)->row();
			if ($spk->jenis_beli == 'Kredit') {
				$data['no_order_survey'] 	= $this->m_admin->cari_id("tr_order_survey", "no_order_survey");
				$data['no_spk'] 						= $spk->no_spk;
				$data['tgl_spk'] 						= $spk->tgl_spk;
				$data['id_customer'] 				= $spk->id_customer;
				$data['nama_konsumen']			= $spk->nama_konsumen;
				$data['tempat_lahir'] 			= $spk->tempat_lahir;
				$data['tgl_lahir'] 					= $spk->tgl_lahir;
				$data['jenis_wn'] 					= $spk->jenis_wn;
				$data['no_ktp'] 						= $spk->no_ktp;
				$data['no_kk'] 							= $spk->no_kk;
				$data['npwp'] 							= $spk->npwp;
				$data['id_kelurahan'] 			= $spk->id_kelurahan;
				$data['id_kecamatan'] 			= $spk->id_kecamatan;
				$data['id_kabupaten'] 			= $spk->id_kabupaten;
				$data['id_provinsi'] 				= $spk->id_provinsi;
				$data['alamat'] 						= $spk->alamat;
				$data['kodepos'] 						= $spk->kodepos;
				$data['denah_lokasi']				= $spk->denah_lokasi;
				$data['alamat_sama']           = $spk->alamat_sama;
				$data['id_kelurahan2']         = $spk->id_kelurahan2;
				$data['id_kecamatan2']         = $spk->id_kecamatan2;
				$data['id_kabupaten2']         = $spk->id_kabupaten2;
				$data['id_provinsi2']          = $spk->id_provinsi2;
				$data['alamat2']               = $spk->alamat2;
				$data['kodepos2']              = $spk->kodepos2;
				$data['status_rumah']          = $spk->status_rumah;
				$data['lama_tinggal']          = $spk->lama_tinggal;
				$data['pekerjaan']             = $spk->pekerjaan;
				$data['lama_kerja']            = $spk->lama_kerja;
				$data['jabatan']               = $spk->jabatan;
				$data['pengeluaran_bulan']     = $spk->pengeluaran_bulan;
				$data['penghasilan']           = preg_replace('/[^0-9\  ]/', '', $spk->penghasilan);
				$data['no_hp']                 = $spk->no_hp;
				$data['status_hp']             = $spk->status_hp;
				$data['no_hp_2']               = $spk->no_hp_2;
				$data['status_hp_2']           = $spk->status_hp_2;
				$data['no_telp']               = $spk->no_telp;
				$data['email']                 = $spk->email;
				$data['refferal_id']           = $spk->refferal_id;
				$data['robd_id']               = $spk->robd_id;
				$data['keterangan']            = $spk->keterangan;
				$data['nama_ibu']              = $spk->nama_ibu;
				$data['tgl_ibu']               = $spk->tgl_ibu;
				$data['id_tipe_kendaraan']     = $spk->id_tipe_kendaraan;
				$data['id_warna']              = $spk->id_warna;
				$data['harga']                 = $spk->harga;
				$data['ppn']                   = $spk->ppn;
				$data['harga_off_road']        = $spk->harga_off_road;
				$data['harga_on_road']         = $spk->harga_on_road;
				$data['biaya_bbn']             = $spk->biaya_bbn;
				$data['jenis_beli']            = $spk->jenis_beli;
				$data['the_road']              = $spk->the_road;
				$data['harga_tunai']           = $spk->harga_tunai;
				$data['program_khusus_1']      = $spk->program_khusus_1;
				$data['program_umum']          = $spk->program_umum;
				$data['voucher_1']             = $spk->voucher_1;
				$data['voucher_tambahan_1']    = $spk->voucher_tambahan_1;
				$data['total_bayar']           = $spk->total_bayar;
				$data['id_finance_company']    = $spk->id_finance_company;
				$data['uang_muka']             = $spk->uang_muka;
				$data['program_khusus_2']      = $spk->program_khusus_2;
				$data['voucher_2']             = $spk->voucher_2;
				$data['voucher_tambahan_2']    = $spk->voucher_tambahan_2;
				$data['tenor']                 = $spk->tenor;
				$data['dp_stor']               = $spk->dp_stor;
				$data['angsuran']              = $spk->angsuran;
				$data['nama_penjamin']         = $spk->nama_penjamin;
				$data['hub_penjamin']          = $spk->hub_penjamin;
				$data['no_ktp_penjamin']       = $spk->no_ktp_penjamin;
				$data['no_hp_penjamin']        = $spk->no_hp_penjamin;
				$data['alamat_penjamin']       = $spk->alamat_penjamin;
				$data['tempat_lahir_penjamin'] = $spk->tempat_lahir_penjamin;
				$data['tgl_lahir_penjamin']    = $spk->tgl_lahir_penjamin;
				$data['pekerjaan_penjamin']    = $spk->pekerjaan_penjamin;
				$data['penghasilan_penjamin']  = $spk->penghasilan_penjamin;
				$data['nama_bpkb']             = $spk->nama_bpkb;
				$data['file_foto']			   = $spk->file_foto;
				$data['file_kk']			   = $spk->file_kk;
				$data['file_ktp_2']			   = $spk->file_ktp_2;
				$data['id_dealer']             = $this->m_admin->cari_dealer();
				$data['created_at']				= $waktu;
				$data['created_by']				= $login_id;
				$data['status_survey']				= 'baru';
				$this->m_admin->insert("tr_order_survey", $data);
			}
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk'>";
		}
	}
	public function reject()
	{
		$waktu              = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);

		$login_id           = $this->session->userdata('id_user');

		$tabel              = $this->tables;

		$pk                 = $this->pk;

		$id                 = $this->input->get("id");

		$data['updated_at'] = $waktu;

		$data['updated_by'] = $login_id;

		$data['status_spk'] = "rejected";
		$upd_penerimaan   = ['no_spk' => null, 'status_on_spk' => null,];
		$this->m_admin->update($tabel, $data, $pk, $id);
		$this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_spk' => $id]);
		$this->db->delete('tr_po_dealer_indent', ['id_spk' => $id]);
		$_SESSION['pesan']  = "Data has been updated successfully";

		$_SESSION['tipe']   = "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk'>";
	}
	// public function close()
	// {		
	// 	$waktu                = gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 	$login_id             = $this->session->userdata('id_user');	
	// 	$tabel                = $this->tables;
	// 	$pk                   = $this->pk;

	// 	$id                   = $this->input->get("id");		
	// 	$alasan_close         = $this->input->get("alasan_close");		
	// 	$data['updated_at']   = $waktu;		
	// 	$data['updated_by']   = $login_id;	
	// 	$data['status_spk']   = "closed";	
	// 	$data['alasan_close'] = $alasan_close;	
	// 	$this->m_admin->update($tabel,$data,$pk,$id);
	// 	$_SESSION['pesan'] 	= "Data has been closed successfully";
	// 	$_SESSION['tipe'] 	= "success";
	// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/spk'>";		
	// }
	public function cetak()
	{
		$tgl                    = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu                  = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id               = $this->session->userdata('id_user');
		$id                     = $this->input->get("id");
		$data['updated_at']     = $waktu;
		$data['updated_by']     = $login_id;
		$data['status_prospek'] = "Deal";
		$spk = $this->m_admin->getByID("tr_spk", "no_spk", $id)->row();
		$this->m_admin->update("tr_prospek", $data, "id_customer", $spk->id_customer);
		$this->m_spk->cetakSPK($id);
	}

	public function getProgram()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$jenis_beli = $this->input->post('jenis_beli');
		$program_umum = $this->input->post('program_umum');
		$dt = date('Y-m-d');
		// $cek_program = $this->db->query("
		// 	SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, 
		// 		(SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan 
		// 	FROM tr_sales_program inner 
		// 	JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md 
		// 	WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' 
		// 	AND id_warna LIKE '%$id_warna%' 
		// 	AND tr_sales_program_tipe.status<>'new' 
		// 	AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir 
		// 	AND '$id_dealer' IN (SELECT id_dealer FROM tr_sales_program_dealer WHERE id_program_md=tr_sales_program.id_program_md)");
		$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, 
				(SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan 
			FROM tr_sales_program 
			inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md
			INNER JOIN ms_jenis_sales_program ON tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
			WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' 
			AND id_warna LIKE '%$id_warna%' 
			AND ms_jenis_sales_program.jenis_sales_program <> 'Group Customer'
			AND tr_sales_program_tipe.status<>'new' 
			AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir
            AND
            (CASE 
            	WHEN tr_sales_program.kuota_program>0 
            	THEN 1=1 
            	ELSE 
            		CASE
            			WHEN (SELECT COUNT(id_dealer) FROM tr_sales_program_dealer WHERE id_program_md=tr_sales_program.id_program_md)>0
            			THEN
            				'$id_dealer' IN (SELECT id_dealer FROM tr_sales_program_dealer WHERE id_program_md=tr_sales_program.id_program_md) 
            			ELSE
            			1=1
            		END
            END)
            ");
		if ($jenis_beli == 'Cash') {
			echo "<option value=''> - choose- </option>";
			foreach ($cek_program->result() as $rs) {
				$selected = $rs->id_program_md == $program_umum ? 'selected' : '';
				// if ($rs->tot_gabungan ==0) {
				if ($rs->tot_cash > 0) {
					echo "<option $selected value='$rs->id_program_md'>$rs->id_program_md | $rs->judul_kegiatan</option>";
				}
				// }
			}
		} elseif ($jenis_beli == 'Kredit') {
			echo "<option value=''> - choose- </option>";
			foreach ($cek_program->result() as $rs) {
				$selected = $rs->id_program_md == $program_umum ? 'selected' : '';
				// if ($rs->tot_gabungan ==0) {
				if ($rs->tot_kredit > 0) {
					echo "<option $selected value='$rs->id_program_md'>$rs->id_program_md | $rs->judul_kegiatan</option>";
				}
				// }
			}
		}
	}
	public function getProgramTambahan()
	{
		// masih ada error apabila tidak ada program nya di form edit/ detail/view/ history

		$id_program_md = $this->input->post('id_program_md');
		$jenis_beli = $this->input->post('jenis_beli');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$dt = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();

		// $cek_ketersediaan=$this->db->query("SELECT * FROM tr_sales_program_dealer
		// 									LEFT JOIN tr_sales_program on tr_sales_program_dealer.id_program_md=tr_sales_program.id_program_md
		// 									WHERE tr_sales_program_dealer.id_program_md='$id_program_md' AND tr_sales_program_dealer.id_dealer='$id_dealer'
		// 									");
		$cek_program = $this->db->get_where('tr_sales_program', ['id_program_md' => $id_program_md])->row();
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
				if ($jenis_beli == 'Cash') {
					$nilai = $nilai_voucher_program->row();
					$nilai_voucher_program = $nilai->tot_cash;
				} elseif ($jenis_beli == 'Kredit') {
					$nilai = $nilai_voucher_program->row();
					$nilai_voucher_program = $nilai->tot_kredit;
				}
				if ($program->id_jenis_sales_program == 'SP-002') {
					$nilai_voucher_program = 0;
				}
			} else {
				$nilai_voucher_program = '';
			}
			echo $nilai_voucher_program . '##';
			// $cek_program = $this->db->query("SELECT * FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan");
			$cek_program = $this->db->query("SELECT DISTINCT(id_program_md)as id_program_md_gabungan FROM(
				SELECT id_program_md FROM tr_sales_program_gabungan WHERE id_program_md='$id_program_md' OR id_program_md_gabungan='$id_program_md'
				UNION
				SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md=(SELECT id_program_md_gabungan FROM tr_sales_program_gabungan a
				join tr_sales_program_tipe b on a.id_program_md_gabungan = b.id_program_md  WHERE id_program_md_gabungan='$id_program_md' and b.id_tipe_kendaraan  = '$id_tipe_kendaraan' and b.id_warna like '%$id_warna%'
				group by id_program_md_gabungan ) OR id_program_md='$id_program_md' ) as tbl_gabungan
				WHERE id_program_md<>'$id_program_md'
			");
			if ($jenis_beli == 'Cash') {
				echo "<option> - choose- </option>";
				$x = 0;
				foreach ($cek_program->result() as $rs) {
					$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir");
					if ($cek_program->num_rows() > 0) {
						$ck = $cek_program->row();
						if ($ck->tot_cash > 0) {
							echo "<option value='$ck->id_program_md'>$ck->id_program_md | $ck->judul_kegiatan</option>";
							$x++;
						}
					}
				}
				echo "##$x";
			} elseif ($jenis_beli == 'Kredit') {
				echo "<option> - choose- </option>";
				$xx = 0;
				foreach ($cek_program->result() as $rs) {
					$cek_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND tr_sales_program.id_program_md='$rs->id_program_md_gabungan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir");
					if ($cek_program->num_rows() > 0) {
						$ck = $cek_program->row();
						if ($ck->tot_kredit > 0) {
							echo "<option value='$ck->id_program_md'>$ck->id_program_md | $ck->judul_kegiatan</option>";
						}
					}
					$xx++;
				}
				echo "##$xx";
			}
			$program_tipe = $this->db->get_where('tr_sales_program_tipe', ['id_program_md' => $id_program_md])->row();
			echo "##$program->jenis_barang";
		} elseif ($tersedia == 0) {
			echo "Penjualan dengan program ini sudah mencapai kuota##";
		}
	}
	public function getVoucherGabungan()
	{
		$id_program_md = $this->input->post('id_program_md');
		$id_program_gabungan = $this->input->post('id_program_gabungan');
		$jenis_beli = $this->input->post('jenis_beli');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$id_warna = $this->input->post('id_warna');
		$dt = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();
		$tersedia = 0;
		$nilai_voucher_program = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir  AND tr_sales_program.id_program_md='$id_program_md' ");
		if ($nilai_voucher_program->num_rows() > 0) {
			$program = $nilai_voucher_program->row();
			if ($jenis_beli == 'Cash') {
				$nilai = $nilai_voucher_program->row();
				$nilai_voucher_program = $nilai->tot_cash;
			} elseif ($jenis_beli == 'Kredit') {
				$nilai = $nilai_voucher_program->row();
				$nilai_voucher_program = $nilai->tot_kredit;
			}
			if ($program->id_jenis_sales_program == 'SP-002') {
				$nilai_voucher_program = 0;
			}
		} else {
			$nilai_voucher_program = '';
		}

		$nilai_vouch_gab = $this->db->query("SELECT *,(ahm_cash+md_cash+dealer_cash+other_cash) as tot_cash,(ahm_kredit+md_kredit+dealer_kredit+other_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$id_tipe_kendaraan' AND id_warna LIKE '%$id_warna%' AND tr_sales_program_tipe.status<>'new' AND '$dt' between tr_sales_program.periode_awal AND tr_sales_program.periode_akhir  AND tr_sales_program.id_program_md='$id_program_gabungan' ");
		if ($nilai_vouch_gab->num_rows() > 0) {
			$cek_program = $nilai_vouch_gab->row();
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
						// 							   WHERE tr_spk.program_umum='$id_program_gabungan' AND tr_sales_order.status_so='so_invoice' AND tr_sales_order.id_dealer='$id_dealer'
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
				// 									   WHERE tr_spk.program_umum='$id_program_gabungan' AND tr_sales_order.status_so='so_invoice'
				// 				")->num_rows();
				$cek_terjual = $this->db->query("SELECT * FROM tr_spk
		 									   WHERE tr_spk.program_umum='$id_program_md' 
		 									   -- AND tr_sales_order.status_so='so_invoice' 
		 									   -- AND tr_spk.id_dealer='$id_dealer'
		 									   AND status_spk<>'closed'
		 				")->num_rows();
				$tersedia = $cek_terjual <= $cek_program->kuota_program ? $cek_program->kuota_program - $cek_terjual : 0;
			}
		}
		if ($tersedia > 0) {
			if ($nilai_vouch_gab->num_rows() > 0) {
				$program = $nilai_vouch_gab->row();
				if ($jenis_beli == 'Cash') {
					$nilai = $nilai_vouch_gab->row();
					$nilai_vouch_gab = $nilai->tot_cash;
				} elseif ($jenis_beli == 'Kredit') {
					$nilai = $nilai_vouch_gab->row();
					$nilai_vouch_gab = $nilai->tot_kredit;
				}
				if ($program->id_jenis_sales_program == 'SP-002') {
					$nilai_vouch_gab = 0;
				}
				$jenis_barang = $program->jenis_barang;
			} else {
				$nilai_vouch_gab = '';
				$jenis_barang = '';
			}
			echo $nilai_voucher_program + $nilai_vouch_gab;
			echo "##$jenis_barang";
		} else {
			echo "Penjualan dengan program ini sudah mencapai kuota";
			// echo "";
		}
	}
	public function cancel()
	{
		$id             = $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "cancel";
		$id_dealer = $this->m_admin->cari_dealer();
		$dt_spk = $this->db->query("SELECT tr_spk.*,tipe_ahm,warna, (SELECT COUNT(id_spk) FROM tr_po_dealer_indent WHERE id_spk=no_spk) AS indent FROM tr_spk 
			JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
			JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
			WHERE no_spk='$id' AND id_dealer='$id_dealer'");
		if ($dt_spk->num_rows() > 0) {
			$data['row'] = $dt_spk->row();
			$data['dt_spk'] = $this->db->query("SELECT tr_spk.*,ms_kelurahan.id_kelurahan FROM tr_spk LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan=ms_kelurahan.id_kelurahan ORDER BY ms_kelurahan.kelurahan ASC");
			$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama", "id_agama", "ASC");
			$data['dt_pekerjaan'] = $this->m_admin->getSortCond("ms_pekerjaan", "id_pekerjaan", "ASC");
			$data['dt_pengeluaran'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan", "id_pengeluaran_bulan", "ASC");
			$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
			$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
			$data['dt_customer'] = $this->m_admin->getSortCond("ms_customer", "id_customer", "nama", "ASC");
			$data['dt_finance'] = $this->m_admin->getSortCond("ms_finance_company", "finance_company", "ASC");
			$data['dt_status_hp'] = $this->m_admin->getSortCond("ms_status_hp", "status_hp", "ASC");
			$data['dt_prospek'] = $this->m_admin->getSortCond("tr_prospek", "no_hp", "id_tipe_kendaraan", "alamat", "ASC");
			$data['dt_pendidikan'] = $this->m_admin->getSortCond("ms_pendidikan", "id_pendidikan", "ASC");
			$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya", "merk_sebelumnya", "ASC");
			$data['dt_jenis_sebelumnya'] = $this->m_admin->getSortCond("ms_jenis_sebelumnya", "jenis_sebelumnya", "ASC");
			$data['dt_digunakan'] = $this->m_admin->getSortCond("ms_digunakan", "digunakan", "ASC");
			$data['dt_hobi'] = $this->m_admin->getSortCond("ms_hobi", "hobi", "ASC");
			$data['reasons']       = $this->db->get('ms_reasons');
			$data['alasan_cancel'] = $this->db->get('ms_alasan_cancel');
			$data['event'] = $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");
			$id_dealer = $this->m_admin->cari_dealer();
			$data['dt_customer'] = $this->db->query("SELECT * FROM tr_prospek LEFT JOIN ms_tipe_kendaraan ON tr_prospek.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
							LEFT JOIN ms_kelurahan ON tr_prospek.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek.id_dealer = '$id_dealer'
							ORDER BY tr_prospek.id_customer ASC");
			$data['dt_npwp'] = $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC");
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk'>";
		}
		//$this->load->view('trans/logistik',$data);
	}
	public function save_cancel()
	{
		$waktu            = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id         = $this->session->userdata('id_user');
		$tabel            = $this->tables;
		$pk               = $this->pk;
		$no_spk           = $this->input->post("no_spk");
		$alasan_close     = $this->input->post("alasan_close");
		$id_reasons       = $this->input->post("id_reasons");
		$id_alasan_cancel = $this->input->post("id_alasan_cancel");
		$alasan_cancel_indent = $this->input->post("alasan_cancel_indent");

		$data['updated_at']   = $waktu;
		$data['updated_by']   = $login_id;
		$data['status_spk']   = "canceled";
		$data['no_mesin_spk'] = null;
		$data['id_reasons']   = $id_reasons;
		$data['alasan_close'] = $alasan_close;
		$get_spk        = $this->db->get_where("tr_spk", ['no_spk' => $no_spk])->row();
		$upd_penerimaan = ['status_on_spk' => null, 'no_spk' => null];

		$cek_indent = $this->db->get_where('tr_po_dealer_indent', ['id_spk' => $no_spk]);
		if ($cek_indent->num_rows() > 0) {
			$indent = ['status' => 'canceled', 'id_reasons' => $id_alasan_cancel, 'alasan_cancel_indent' => $alasan_cancel_indent];
		}
		$this->db->trans_begin();

		// update status cancel spk
		$this->m_admin->update($tabel, $data, $pk, $no_spk);


		//kembalikan status booking unit
		$this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $get_spk->no_mesin_spk]);
		if (isset($indent)) {

			// update status cancel indent dealer
			$this->db->update('tr_po_dealer_indent', $indent, ['id_spk' => $no_spk]);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] = "Data has been canceled successfully";
			$_SESSION['tipe']  = "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/spk'>";
		}
	}
	function getSkemaKredit()
	{
		$id_customer = $this->input->post('id_customer');
		$prospek = $this->db->query("SELECT * FROM tr_prospek WHERE id_customer='$id_customer' ORDER BY created_at DESC LIMIT 1");
		$response['status'] = 'kosong';
		if ($prospek->num_rows() > 0) {
			$pr = $prospek->row();
			$get_skema_kredit = $this->db->get_where('tr_skema_kredit', ['id_prospek' => $pr->id_prospek]);
			if ($get_skema_kredit->num_rows() > 0) {
				$skm                  = $get_skema_kredit->row();
				$response['status']   = 'sukses';
				$response['tenor']    = $skm->tenor;
				$response['angsuran'] = $skm->angsuran;
				$response['uang_muka'] = $skm->dp;
				$id_finance_company = $response['id_finance_company'] = $skm->id_finco;
				$finco = $this->db->get_where('ms_finance_company', ['id_finance_company' => $id_finance_company]);
				$response['finance_company'] = $finco->num_rows() > 0 ? $finco->row()->finance_company : '';
			}
		}
		echo json_encode($response);
	}
	public function history_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= 'History ' . $this->title . ' Group Customer';
		$data['set']		= "history_gc";
		$data['dt_npwp'] = $this->m_spk->getDataNPWP();
		$this->template($data);
	}

	public function fetch_history_gc()
	{
		$fetch_data = $this->make_query_fetch();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Cetak SPK' class='btn btn-warning btn-xs btn-flat' href=\"" . base_url('dealer/' . $this->page . '/cetak_gc?id_c=' . $rs->no_spk_gc) . "\"><i class='fa fa-print'></i></a>";

			// if ($rs->status == 'input') {
			//   $status = '<label class="label label-primary">Input</label>';
			//   // if (can_access($this->page, 'can_update'))  
			//   $button .= $btn_approval;
			// } else
			if ($rs->status == 'approved') {
				$status = '<label class="label label-success">Approved</label>';
			}
			//  elseif ($rs->status == 'rejected') {
			//   $status = '<label class="label label-danger">Rejected</label>';
			// }
			$button .= $btn_cetak;
			$sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail_gc?id_c=' . $rs->no_spk_gc . '">' . $rs->no_spk_gc . '</a>';
			$sub_array[] = $rs->nama_npwp;
			$sub_array[] = $rs->no_npwp;
			$sub_array[] = $rs->alamat;
			$sub_array[] = $status;
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
			'order_column' => 'history',
			'search' => $this->input->post('search')['value'],
			'status_in' => "'approved'",
		];
		if ($recordsFiltered == true) {
			return $this->m_spk->getSPKGC($filter)->num_rows();
		} else {
			return $this->m_spk->getSPKGC($filter)->result();
		}
	}
	public function outstanding_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= 'Outstanding ' . $this->title . ' Group Customer';
		$data['set']		= "outstanding_gc";
		$data['dt_npwp'] = $this->m_spk->getDataNPWP();
		$this->template($data);
	}

	public function fetch_outstanding_gc()
	{
		$fetch_data = $this->make_query_outstanding_gc();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_cetak = "<a data-toggle='tooltip' title='Cetak SPK GC' class='btn btn-warning btn-xs btn-flat' href=\"" . base_url('dealer/' . $this->page . '/cetak_gc?id_c=' . $rs->no_spk_gc) . "\"><i class='fa fa-print'></i></a>";

			$btn_edit = "<a data-toggle='tooltip' title='Edit SPK GC' class='btn btn-primary btn-xs btn-flat' href=\"" . base_url('dealer/' . $this->page . '/edit_gc?set=outstandaing&id_c=' . $rs->no_spk_gc) . "\"><i class='fa fa-edit'></i></a>";

			if (can_access($this->page, 'can_update'))  $button .= $btn_edit . ' ';
			// if ($rs->status == 'input') {
			//   $status = '<label class="label label-primary">Input</label>';
			//   // if (can_access($this->page, 'can_update'))  
			//   $button .= $btn_approval;
			// } else
			if ($rs->status == 'approved') {
				$status = '<label class="label label-success">Approved</label>';
			}
			//  elseif ($rs->status == 'rejected') {
			//   $status = '<label class="label label-danger">Rejected</label>';
			// }
			$button .= $btn_cetak;
			$sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail_gc?id_c=' . $rs->no_spk_gc . '">' . $rs->no_spk_gc . '</a>';
			$sub_array[] = $rs->nama_npwp;
			$sub_array[] = $rs->no_npwp;
			$sub_array[] = $rs->alamat;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_outstanding_gc(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_outstanding_gc($recordsFiltered = null)
	{
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST["order"] : '',
			'order_column' => 'history',
			'search' => $this->input->post('search')['value'],
			'expired' => 1
		];
		if ($recordsFiltered == true) {
			return $this->m_spk->getSPKGC($filter)->num_rows();
		} else {
			return $this->m_spk->getSPKGC($filter)->result();
		}
	}
	function tes_harga()
	{
		$id = $this->input->get('id');
		$hrg = $this->m_admin->detail_individu2($id);
		send_json($hrg);
	}
}
