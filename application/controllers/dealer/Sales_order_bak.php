<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sales_order extends CI_Controller
{
	var $tables =   "tr_sales_order";

	var $folder =   "dealer";

	var $page   =		"sales_order";

	var $pk     =   "id_sales_order";

	var $title  =   "Sales Order";
	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('M_h1_dealer_sales_order', 'm_so');
		$this->load->model('m_h1_dealer_spk', 'm_spk');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang_helper');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		//$this->load->library('ciqrcode');
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
	function mata_uang($a)
	{
		if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if (is_numeric($a) and $a != 0 and $a != "") {
			return number_format($a, 0, ',', '.');
		} else {
			return $a;
		}
	}
	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view_new";
		$id_dealer = $this->m_admin->cari_dealer();
/*
		$data['dt_sales_order'] = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_dealer = '$id_dealer' 
			AND (status_close IS NULL OR status_close <> 'closed')  			
			AND (status_cetak <> 'konsumen' OR status_cetak IS NULL)
			ORDER BY tr_sales_order.id_sales_order ASC");
*/
		$this->template($data);
	}
	public function gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= "view_gc";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sales_order'] = $this->db->query("SELECT * FROM tr_sales_order_gc 
			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc			
			WHERE tr_sales_order_gc.id_dealer = '$id_dealer' 
			AND (status_close IS NULL OR status_close <> 'reject')  
      AND (status_cetak <> 'konsumen' OR status_cetak IS NULL)
      ORDER BY tr_sales_order_gc.id_sales_order_gc ASC");
		$this->template($data);
	}
	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= "History " . $this->title;
		$data['set']		= "history";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sales_order'] = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_dealer = '$id_dealer' 
			AND (status_close = 'closed' OR status_close = 'reject' OR status_cetak = 'konsumen') 
			ORDER BY tr_sales_order.id_sales_order ASC");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}

	public function getDataHistory()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");

        $id_dealer = $this->m_admin->cari_dealer();

        $cari = '';
		if ($search != '') {
			$cari = " 
			and (tr_prospek.nama_konsumen LIKE '%$search%' 
						OR tr_spk.alamat LIKE '%$search%' 
						OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%' 
						OR ms_warna.warna LIKE '%$search%'
						OR tr_scan_barcode.no_rangka LIKE '%$search%'
						OR tr_sales_order.no_mesin LIKE '%$search%')
			";
		}

        $dataSo = $this->db->query("

			SELECT
				tr_sales_order.*,
				tr_prospek.nama_konsumen,
				tr_spk.alamat,
				ms_tipe_kendaraan.tipe_ahm,
				ms_warna.warna,
				tr_scan_barcode.no_rangka 
			FROM
				tr_sales_order
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna 
			WHERE
				tr_sales_order.id_dealer = '$id_dealer' 
				AND ( status_close = 'closed' OR status_close = 'reject' OR status_cetak = 'konsumen' ) 
			$cari
			ORDER BY $order_field $order_ascdesc
			LIMIT $start,$limit


        	");

        $data = array();


		
        foreach($dataSo->result() as $row)
        {
        	$no_faktur = $this->db->query("SELECT nomor_faktur from tr_fkb where no_mesin_spasi='$row->no_mesin'");
	          if ($no_faktur->num_rows() > 0) {
	            $no_faktur = $no_faktur->row()->nomor_faktur;
	          } else {
	            $no_faktur = '';
	          }
	          $tombol1 = "";
	          $tombol = "";  

	          $tombol1 = "<a href='dealer/sales_order/cetak_so?id=$row->id_sales_order' target='_blank' >
                              <button class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                            </a>
                            <a href='dealer/sales_order/cetak_cover?id=$row->id_sales_order' target='_blank' >
                              <button class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                            </a>
                            <a href='dealer/sales_order/cetak_invoice?id=$row->id_sales_order' target='_blank' >
                              <button class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                            </a>
                            <a href='dealer/sales_order/cetak_barcode?id=$row->id_sales_order' target='_blank' >
                              <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                            </a>";
                      $am = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $row->no_mesin);
                      $no_rangka = ($am->num_rows() > 0) ? $am->row()->no_rangka : "";

            $data[]= array(
            	'',
                "<a href='dealer/sales_order/konsumen?id=$row->id_sales_order'>$row->id_sales_order</a>",
                $row->no_mesin,
                $no_rangka,
                $no_faktur,
                $row->tipe_ahm,
                $row->warna,
                $row->nama_konsumen,
                $row->alamat,
                $tombol.$tombol1

               	
            );     
        }

        $get_total = $this->db->query("

        	SELECT
				tr_sales_order.*,
				tr_prospek.nama_konsumen,
				tr_spk.alamat,
				ms_tipe_kendaraan.tipe_ahm,
				ms_warna.warna,
				tr_scan_barcode.no_rangka 
			FROM
				tr_sales_order
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna 
			WHERE
				tr_sales_order.id_dealer = '$id_dealer' 
				AND ( status_close = 'closed' OR status_close = 'reject' OR status_cetak = 'konsumen' ) 
			$cari

        	");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

	public function history_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= "History " . $this->title . " Group Customer";
		$data['set']		= "history_gc";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sales_order'] = $this->db->query("SELECT * FROM tr_sales_order_gc 
			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc			
			WHERE tr_sales_order_gc.id_dealer = '$id_dealer' 
			AND (status_close = 'closed' OR status_close = 'reject' OR status_cetak = 'konsumen') 			
			ORDER BY tr_sales_order_gc.id_sales_order_gc ASC");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}

	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$id_dealer = $this->m_admin->cari_dealer();
		// $data['dt_spk'] = $this->db->query("SELECT DISTINCT(tr_spk.no_spk) FROM tr_spk 
		// 		LEFT JOIN tr_hasil_survey ON tr_spk.no_spk = tr_hasil_survey.no_spk
		// 		WHERE tr_spk.id_dealer = '$id_dealer' 
		// 		AND tr_spk.no_spk IN (SELECT no_spk FROM tr_cdb) 
		// 		AND tr_spk.no_spk NOT IN (SELECT no_spk FROM tr_sales_order WHERE no_spk IS NOT NULL) 
		// 		AND tr_hasil_survey.status_approval = 'approved'
		// 		AND tr_spk.jenis_beli = 'Kredit'
		// 		AND (tr_spk.status_spk = 'approved' OR tr_spk.status_spk='paid') ORDER BY tr_spk.no_spk ASC");												
		// $data['dt_spk2'] = $this->db->query("SELECT DISTINCT(tr_spk.no_spk) FROM tr_spk 
		// 		WHERE tr_spk.id_dealer = '$id_dealer' 
		// 		AND tr_spk.no_spk IN (SELECT no_spk FROM tr_cdb) 
		// 		AND tr_spk.no_spk NOT IN (SELECT no_spk FROM tr_sales_order WHERE no_spk IS NOT NULL) 
		// 		AND tr_spk.jenis_beli = 'Cash'				
		// 		AND (tr_spk.status_spk = 'approved' OR tr_spk.status_spk='paid') ORDER BY tr_spk.no_spk ASC");
		$data['dt_spk'] = $this->db->query("SELECT DISTINCT(tr_spk.no_spk) FROM tr_spk 
				LEFT JOIN tr_hasil_survey ON tr_spk.no_spk = tr_hasil_survey.no_spk
				WHERE tr_spk.id_dealer = '$id_dealer' 
				AND tr_spk.no_spk IN (SELECT no_spk FROM tr_cdb) 
				AND tr_spk.no_spk NOT IN (SELECT no_spk FROM tr_sales_order WHERE no_spk IS NOT NULL) 
				AND tr_hasil_survey.status_approval = 'approved'
				AND tr_spk.jenis_beli = 'Kredit'
				AND tr_spk.status_spk='approved'
				 ORDER BY tr_spk.no_spk DESC");
		$data['dt_spk2'] = $this->db->query("SELECT DISTINCT(tr_spk.no_spk) FROM tr_spk 
				WHERE tr_spk.id_dealer = '$id_dealer' 
				AND tr_spk.no_spk IN (SELECT no_spk FROM tr_cdb) 
				AND tr_spk.no_spk NOT IN (SELECT no_spk FROM tr_sales_order WHERE no_spk IS NOT NULL) 
				AND tr_spk.jenis_beli = 'Cash'
				AND tr_spk.status_spk in ('approved')
				ORDER BY tr_spk.no_spk ASC");
		$this->template($data);
	}
	public function add_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title . " Group Customer";
		$data['set']		= "insert_gc";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_spk'] = $this->db->query("SELECT DISTINCT(tr_spk.no_spk) FROM tr_spk 
				LEFT JOIN tr_hasil_survey ON tr_spk.no_spk = tr_hasil_survey.no_spk
				WHERE tr_spk.id_dealer = '$id_dealer' 
				AND tr_spk.no_spk IN (SELECT no_spk FROM tr_cdb) 
				AND tr_spk.no_spk NOT IN (SELECT no_spk FROM tr_sales_order WHERE no_spk IS NOT NULL) 
				AND tr_hasil_survey.status_approval = 'approved'
				AND tr_spk.jenis_beli = 'Kredit'
				AND tr_spk.status_spk = 'approved' ORDER BY tr_spk.no_spk ASC");
		$data['dt_spk2'] = $this->db->query("SELECT DISTINCT(tr_spk.no_spk) FROM tr_spk 
				WHERE tr_spk.id_dealer = '$id_dealer' 
				AND tr_spk.no_spk IN (SELECT no_spk FROM tr_cdb) 
				AND tr_spk.no_spk NOT IN (SELECT no_spk FROM tr_sales_order WHERE no_spk IS NOT NULL) 
				AND tr_spk.jenis_beli = 'Cash'				
				AND tr_spk.status_spk = 'approved' ORDER BY tr_spk.no_spk ASC");
		$this->template($data);
	}
	public function syarat_bbn()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Syarat BBN " . $this->title . " Group Customer";
		$data['set']		= "syarat_bbn";
		$id = $this->input->get("id_c");
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_so'] = $this->db->query("SELECT * FROM tr_sales_order_gc INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
				WHERE tr_sales_order_gc.id_sales_order_gc = '$id'");
		$this->template($data);
	}
	public function konsumen()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "konsumen";
		$id = $this->input->get("id");
		$data['mode'] = 'detail';
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE id_dealer = '$id_dealer' ORDER BY no_spk ASC");
		$data['dt_konsumen']	= $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_spk.*,ms_finance_company.finance_company,tr_sales_order.* FROM tr_spk 
					INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
					INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
					LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna					
					LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
					WHERE tr_sales_order.id_sales_order = '$id'");
		$this->template($data);
	}
	public function cek_spk_gc()
	{
		$no_spk_gc = $this->input->post('no_spk_gc');
		$sql = $this->db->query("SELECT * FROM tr_spk_gc WHERE no_spk_gc = '$no_spk_gc'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			echo "ok" . "|" . $dt_ve->nama_npwp . "|" . $dt_ve->no_npwp . "|" . $dt_ve->alamat . "|" . $dt_ve->id_kelurahan . "|" . $dt_ve->jenis_gc . "|" . $dt_ve->no_telp . "|" . $dt_ve->tgl_berdiri . "|" . $dt_ve->kodepos . "|" . $dt_ve->no_spk_gc . "|" . $dt_ve->jenis_beli;
		} else {
			echo "There is no data found!";
		}
	}
	public function getNosin_gc()
	{
		$no_mesin = $this->input->post('no_mesin');
		$sql = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_scan_barcode
					LEFT JOIN ms_tipe_kendaraan on tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			echo "ok" . "|" . $dt_ve->no_mesin . "|" . $dt_ve->no_rangka . "|" . $dt_ve->tipe_ahm . " - " . $dt_ve->warna;;
		} else {
			echo "There is no data found!";
		}
	}
	public function addDetail()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id_sales_order_gc	 = $this->input->post('id_sales_order_gc');
		if ($id_sales_order_gc	 <> '') {
			$data['id_sales_order_gc']			= $this->input->post('id_sales_order_gc');
			$data['status']					= 'input';
		} else {
			$data['status']					= 'new';
		}
		$data['no_spk_gc']	= $no_spk_gc		= $this->input->post('no_spk_gc');
		$data['no_mesin']	= $no_mesin		= $this->input->post('no_mesin');
		$data['created_by']				= $login_id;
		$data['created_at']				= $waktu;
		$cek = $this->db->query("SELECT * From tr_sales_order_gc_nosin WHERE created_by='$login_id' AND status='new' AND no_mesin = '$no_mesin' AND no_spk_gc	= '$no_spk_gc'");
		if ($cek->num_rows() > 0) {
			echo "No Mesin ini sudah dipilih sebelumnya";
		} else {
			$amb1 = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'")->row();
			$id_tipe_kendaraan = $amb1->tipe_motor;
			$id_warna = $amb1->warna;
			$cek_spk = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan WHERE no_spk_gc = '$no_spk_gc' AND id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'")->row()->qty;
			$cek_so = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			 	WHERE tr_sales_order_gc_nosin.no_spk_gc = '$no_spk_gc' AND tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan' AND tr_scan_barcode.warna = '$id_warna'")->num_rows();
			if ($cek_so < $cek_spk) {
				$this->m_admin->insert("tr_sales_order_gc_nosin", $data);
				echo "nihil";
			} else {
				echo "No Mesin yang anda pilih melebihi quota yang ditentukan";
			}
		}
	}
	public function delDetail()
	{
		$id			= $this->input->post('id');
		$this->m_admin->delete("tr_sales_order_gc_nosin", 'id', $id);
		echo "nihil";
	}
	public function getDetail_kendaraan()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id 		= $this->input->post('id');
		$data['detail'] = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$id'");
		$data['id'] = $id;
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_so_gc', $data);
	}
	public function getDetail_nosin()
	{
		$waktu 		= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id	= $this->session->userdata('id_user');
		$id 		= $this->input->post('id');
		$data['no_spk_gc'] 		= $this->input->post('no_spk_gc');
		if ($id == null or $id == '') {
			$data['detail'] = $this->db->query("SELECT tr_sales_order_gc_nosin.*,tr_scan_barcode.tipe_motor,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order_gc_nosin
					LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
					LEFT JOIN ms_tipe_kendaraan on tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			 		WHERE tr_sales_order_gc_nosin.status='new' AND tr_sales_order_gc_nosin.created_by='$login_id'");
		} else {
			$data['detail'] = $this->db->query("SELECT tr_sales_order_gc_nosin.*,tr_scan_barcode.tipe_motor,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order_gc_nosin
					LEFT JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
					LEFT JOIN ms_tipe_kendaraan on tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					WHERE tr_sales_order_gc_nosin.id_sales_order_gc='$id'");
		}
		$data['id'] = $id == null ? 0 : $id;
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->load->view('dealer/t_so_nosin_gc', $data);
	}
	public function cek_nosin()
	{
		$no_mesin = $this->input->post('no_mesin');
		$sql = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$no_mesin'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			$fkb = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$no_mesin'");
			if ($fkb->num_rows() > 0) {
				$fkb = $fkb->row()->tahun_produksi;
			} else {
				$fkb = '';
			}
			echo "ok" . "|" . $dt_ve->no_mesin . "|" . $dt_ve->no_rangka . "|" . $fkb;
		} else {
			echo "There is no data found!";
		}
	}
	public function get_ksu()
	{
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
		$data['ksu'] = $this->db->query("SELECT * FROM ms_koneksi_ksu 
									join ms_koneksi_ksu_detail on ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu
									join ms_ksu on ms_koneksi_ksu_detail.id_ksu = ms_ksu.id_ksu
								WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$id_tipe_kendaraan'")->result();
		$data['mode'] = $this->input->post('mode');
		$this->load->view('dealer/t_sales_order_ksu', $data);
	}
	public function getJenisBayar()
	{
		$data['jenis_bayar']    = $this->input->post('jenis_bayar');
		$data['id_sales_order'] = $this->input->post('id_sales_order');
		$isi_jenis              = $this->input->post('isi_jenis');
		$data['gc']              = $this->input->post('gc');
		if ($isi_jenis > 0) {
			$this->load->view('dealer/t_sales_order_jenis_bayar_detail', $data);
		} else {
			$this->load->view('dealer/t_sales_order_jenis_bayar', $data);
		}
	}
	public function take_spk()
	{
		$no_spk				= $this->input->post('no_spk');
		//Cek No Mesin Untuk No SPK
		$cek_nosin = $this->input->post('cek_nosin');
		// if ($cek_nosin=='ya') {
		// 	$nosin = $this->cek_nosin_spk($no_spk);
		// 	if ($nosin==false) {
		// 		echo 'kosong';
		// 		exit;
		// 	}
		// }
		$txt ='';
		$dt_so = $this->db->query("SELECT no_spk, no_mesin, tgl_po_leasing, no_po_leasing from tr_sales_order WHERE no_spk = '$no_spk'");
		if ($dt_so->num_rows() > 0) {
			$ds = $dt_so->row();
			$txt.='|'.$ds->no_po_leasing.'|'.$ds->tgl_po_leasing;
		}
	
		$dt_spk	= $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_spk.*,ms_finance_company.finance_company FROM tr_spk 
					INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
					LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna					
					LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
					WHERE tr_spk.no_spk = '$no_spk'");
		if ($dt_spk->num_rows() > 0) {
			$da = $dt_spk->row();

			$so  = $this->db->query("SELECT no_mesin FROM tr_sales_order WHERE no_spk='$no_spk'");
			$tahun ='';
			if($so->num_rows() > 0){
				$temp_no_mesin = $so->row()->no_mesin;
				$tahun = $this->db->query("SELECT tahun_produksi FROM tr_fkb WHERE no_mesin_spasi='$temp_no_mesin'");
				if($tahun->num_rows() > 0){
					$tahun = $tahun->row()->tahun_produksi;	
				}		
			}
			$tahun_produksi = $tahun;
		
			/*
			$so  = $this->db->query("SELECT no_mesin FROM tr_sales_order WHERE no_spk='$no_spk'")->row()->no_mesin;
			$tahun = $this->db->query("SELECT tahun_produksi FROM tr_fkb WHERE no_mesin_spasi='$so'");
			$tahun_produksi     = $tahun->num_rows() > 0 ? $tahun->row()->tahun_produksi : '';
			*/

			$nama_konsumen      = $da->nama_bpkb;
			$no_ktp             = $da->no_ktp_bpkb;
			$no_npwp            = $da->npwp;
			$id_kelurahan       = $da->id_kelurahan_bpkb;

			if($id_kelurahan ==''){
				$id_kelurahan       = $da->id_kelurahan;
			}
			
			$alamat             = $da->alamat_ktp_bpkb;
			$kodepos            = $da->kodepos;
			$alamat_sama        = $da->alamat_sama;
			$id_kelurahan2      = $da->id_kelurahan2;
			$no_hp              = $da->no_hp;
			$no_hp_2            = $da->no_hp_2;
			$no_telp            = $da->no_telp;
			$id_tipe_kendaraan  = $da->id_tipe_kendaraan . "-" . $da->tipe_ahm;
			$id_warna           = $da->id_warna . "-" . $da->warna;

			$return = $this->m_admin->detail_individu($no_spk);

			$harga_off_road     = $this->mata_uang($return['harga_off_road']);
			$biaya_bbn          = $this->mata_uang($return['bbn']);
			$ppn                = $this->mata_uang(floor($return['ppn']));
			$harga_on_road      = $this->mata_uang($return['harga_on_road']);
			$nama_bpkb          = $da->nama_bpkb;
			$tipe_pembelian     = $da->jenis_beli;
			$the_road           = $da->the_road;
			$harga_tunai        = $da->harga_tunai;
			$harga              = $this->mata_uang(floor($return['harga']));
			$program            = $da->program_umum;
			$nilai_voucher      = $da->voucher_1;
			$voucher_tambahan   = $da->voucher_tambahan_1 + $da->diskon;
			$id_finance_company = $da->id_finance_company;
			$finance_company    = $da->finance_company;
			$dp                 = $this->mata_uang($da->uang_muka);
			$program2           = $da->program_umum;
			$program_gabungan   = $da->program_gabungan;
			$nilai_voucher2     = $da->voucher_2;
			$voucher_tambahan_2 = $da->voucher_tambahan_2 + $da->diskon;
			$dp_setor           = $this->mata_uang($da->dp_stor);
			$tenor              = $this->mata_uang($da->tenor);
			$angsuran           = $this->mata_uang($da->angsuran);
			$id_tipe_kend       = $da->id_tipe_kendaraan;
			$id_w               = $da->id_warna;
			$no_mesin 			= $this->cek_nosin_fifo($id_tipe_kend, $id_w);
			if ($tipe_pembelian == 'Cash') {
				$total_bayar = $harga_tunai - ($nilai_voucher + $voucher_tambahan);
			} elseif ($tipe_pembelian == 'Kredit') {
				$total_bayar = $harga_tunai - ($nilai_voucher2 + $voucher_tambahan_2);
			}
			$id_tipe_kendaraan_only = $da->id_tipe_kendaraan;
			$sales_people = $this->db->query("SELECT CONCAT(tr_prospek.id_flp_md,' - ',nama_lengkap)as sales FROM tr_prospek 
				JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
				WHERE id_customer='$da->id_customer'
				")->row()->sales;
			$id_customer = $da->id_customer;
			$diskon = $this->mata_uang($da->diskon);
		} else {
			$nama_konsumen          = "";

			$no_ktp                 = "";

			$no_npwp                = "";

			$id_kelurahan           = "";

			$alamat                 = "";

			$kodepos                = "";

			$alamat_sama            = "";

			$id_kelurahan2          = "";

			$no_hp                  = "";

			$no_hp_2                = "";

			$no_telp                = "";

			$id_tipe_kendaraan      = "";

			$id_warna               = "";

			$tahun_produksi         = "";

			$harga_off_road         = "";

			$biaya_bbn              = "";

			$ppn                    = "";

			$harga_on_road          = "";

			$nama_bpkb              = "";

			$tipe_pembelian         = "";

			$the_road               = "";

			$harga_tunai            = "";

			$program                = "";

			$nilai_voucher          = "";

			$voucher_tambahan       = "";

			$total_bayar            = "";

			$finance_company        = "";

			$dp                     = "";

			$program2               = "";

			$nilai_voucher2         = "";

			$voucher_tambahan_2     = "";

			$dp_setor               = "";

			$tenor                  = "";

			$angsuran               = "";

			$id_tipe_kend           = '';

			$program_gabungan       = '';

			$harga                  = '';

			$id_tipe_kendaraan_only = '';

			$id_w                   = "";
			$sales_people           = '';
			$id_customer            = '';
			$no_mesin            = '';
			$diskon            = '';
			$id_finance_company = '';
		}
		echo $no_spk . "|" . $nama_konsumen . "|" . $no_ktp . "|" . $no_npwp . "|" . $id_kelurahan . "|" . $alamat . "|" . $kodepos . "|" . $alamat_sama . "|" . $id_kelurahan2 . "|" . $no_hp . "|" . $no_hp_2 . "|" . $no_telp . "|" . $id_tipe_kendaraan . "|" . $harga . "|" . $id_warna . "|" . $ppn . "|" . $tahun_produksi . "|" . $harga_off_road . "|" . $biaya_bbn . "|" . $harga_on_road . "|" . $nama_bpkb . "|" . $tipe_pembelian . "|" . $the_road . "|" . $this->mata_uang($harga_tunai) . "|" . $program . "|" . $this->mata_uang($nilai_voucher) . "|" . $this->mata_uang($voucher_tambahan) . "|" . $this->mata_uang($total_bayar) . "|" . $finance_company . "|" . $dp . "|" . $this->mata_uang($nilai_voucher2) . "|" . $this->mata_uang($voucher_tambahan_2) . "|" . $dp_setor . "|" . $tenor . "|" . $angsuran . "|" . $id_tipe_kend . "|" . $program2 . "|" . $program_gabungan . "|" . $id_tipe_kendaraan_only . "|" . $id_w . "|" . $sales_people . "|" . $id_customer . "|" . $diskon . "|" . $id_finance_company . "|" . $no_mesin."".$txt;
	}
	public function browseNosin()
	{
		$data['id_tipe_kendaraan'] = $this->input->post('id_tipe_kendaraan');
		$data['id_warna'] = $this->input->post('id_warna');
		$this->load->view('dealer/t_sales_order_browsenosin');
	}
	public function take_kec()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');
		$dt_kel		= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$kelurahan 	= $dt_kel->kelurahan;
		$id_kecamatan 	= $dt_kel->id_kecamatan;
		$dt_kec		= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan	= $dt_kec->kecamatan;
		$id_kabupaten 	= $dt_kec->id_kabupaten;
		$dt_kab		= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= $dt_kab->kabupaten;
		$id_provinsi  	= $dt_kab->id_provinsi;
		$dt_pro		= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  	= $dt_pro->provinsi;

		echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $kelurahan;
	}
	// public function cek_no_so()
	// {
	// 	$k = 0;        
	//    	while ($k == 0){
	// 	 	$tgl 						= date("d");
	// 	 	$cek_tgl				= date("Y-m-d");
	// 	 	$th 						= date("Y");
	// 	 	$bln 						= date("m");	
	// 	 	$hr 						= date("d");	
	// 	 	$id_dealer = $this->m_admin->cari_dealer();
	// 	 	$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");	
	// 	 	if ($get_dealer->num_rows() > 0) {
	// 			$get_dealer = $get_dealer->row()->kode_dealer_md;
	// 		}else{
	// 			$get_dealer ='';
	// 		}
	// 		$pj = strlen($get_dealer);
	// 	 	$pr_num = $this->db->query("SELECT *,mid(created_at,9,2)as tgl FROM tr_sales_order WHERE LEFT(created_at,10) = '$cek_tgl' AND mid(id_sales_order,4,$pj) = '$get_dealer' ORDER BY created_at DESC LIMIT 0,1");						
	// 	 	if($pr_num->num_rows()>0){						
	// 	 		$row 	= $pr_num->row();
	// 	 		$id = explode('/', $row->id_sales_order);
	// 	 		if (count($id) > 1) {
	// 		 		if ($tgl == $row->tgl) {
	// 		 			$kode 	= 'SO-'.$get_dealer.'/'.$th.'/'.$bln.'/'.$hr.'/'.sprintf("%'.04d",$id[4]+1);
	// 		 		}else{
	// 			 		$kode = 'SO-'.$get_dealer.'/'.$th.'/'.$bln.'/'.$hr.'/0001';
	// 	 			}
	// 	 		}else{
	// 	 			$kode = 'SO-'.$get_dealer.'/'.$th.'/'.$bln.'/'.$hr.'/0001';
	// 	 		}				
	// 	 	}else{
	// 	 		$kode = 'SO-'.$get_dealer.'/'.$th.'/'.$bln.'/'.$hr.'/0001';
	// 	 	} 			

	// 		$cek = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order = '$kode'");                         
	//     if($cek->num_rows() > 0){
	//         $k = 0;                
	//     }else{  
	//         $k = 1;
	//     }            
	//    }
	// 	return $kode;
	// }
	//No SO Reset Per Tahun & Per Dealer
	public function cek_no_so()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('Y');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber = $dealer->kode_dealer_md;

		$get_data  = $this->db->query("SELECT * FROM tr_sales_order WHERE LEFT(created_at,4) = '$th' AND id_dealer='$id_dealer' ORDER BY created_at DESC LIMIT 0,1 ");
		if ($get_data->num_rows() > 0) {
			$row            = $get_data->row();
			$id_sales_order = substr($row->id_sales_order, -6);
			$new_kode       = 'SO-' . $id_sumber . '/' . $th . '/' . sprintf("%'.06d", $id_sales_order + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_sales_order', ['id_sales_order' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -6);
					$new_kode = 'SO-' . $id_sumber . '/' . $th . '/' . sprintf("%'.06d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = 'SO-' . $id_sumber . '/' . $th . '/000001';
		}
		return strtoupper($new_kode);
	}
	public function cek_no_so_gc()
	{
		$k = 0;
		while ($k == 0) {
			$tgl 						= date("d");
			$cek_tgl				= date("Y-m-d");
			$th 						= date("Y");
			$bln 						= date("m");
			$hr 						= date("d");
			$id_dealer = $this->m_admin->cari_dealer();
			$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
			if ($get_dealer->num_rows() > 0) {
				$get_dealer = $get_dealer->row()->kode_dealer_md;
			} else {
				$get_dealer = '';
			}
			$pj = strlen($get_dealer);
			$pr_num = $this->db->query("SELECT *,mid(created_at,9,2)as tgl FROM tr_sales_order_gc WHERE LEFT(created_at,10) = '$cek_tgl' AND mid(id_sales_order_gc,4,$pj) = '$get_dealer' ORDER BY created_at DESC LIMIT 0,1");
			if ($pr_num->num_rows() > 0) {
				$row 	= $pr_num->row();
				$id = explode('/', $row->id_sales_order_gc);
				if (count($id) > 1) {
					if ($tgl == $row->tgl) {
						$kode 	= 'GC-' . $get_dealer . '/' . $th . '/' . $bln . '/' . $hr . '/' . sprintf("%'.04d", $id[4] + 1);
					} else {
						$kode = 'GC-' . $get_dealer . '/' . $th . '/' . $bln . '/' . $hr . '/0001';
					}
				} else {
					$kode = 'GC-' . $get_dealer . '/' . $th . '/' . $bln . '/' . $hr . '/0001';
				}
			} else {
				$kode = 'GC-' . $get_dealer . '/' . $th . '/' . $bln . '/' . $hr . '/0001';
			}

			$cek = $this->db->query("SELECT * FROM tr_sales_order_gc WHERE id_sales_order_gc = '$kode'");
			if ($cek->num_rows() > 0) {
				$k = 0;
			} else {
				$k = 1;
			}
		}
		return $kode;
	}
	public function tes()
	{
		// echo $spk = $this->input->get('spk');
		echo $this->cek_nosin_spk('19/09/09/00390-05545');
	}
	function cek_nosin_spk($no_spk)
	{
		$spk        = $this->db->get_where('tr_spk', ['no_spk' => $no_spk])->row();
		$cek_indent = $this->db->get_where('tr_po_dealer_indent', ['id_spk' => $no_spk]);
		$is_indent  = 'AND tr_penerimaan_unit_dealer_detail.po_indent IS NULL';
		$id_dealer  = $this->m_admin->cari_dealer();
		if ($cek_indent->num_rows() > 0) {
			$ind       = $cek_indent->row();
			if ($spk->no_mesin_spk == null or $spk->no_mesin_spk == '') {
				$is_indent = "AND tr_penerimaan_unit_dealer_detail.po_indent='ya' AND tr_penerimaan_unit_dealer_detail.id_indent='$ind->id_indent'";
			}
		}
		// $id_item = $spk->id_tipe_kendaraan.'-'.$spk->id_warna;

		$nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka
            FROM tr_penerimaan_unit_dealer_detail 
            JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 
            JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
            WHERE 
            tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
            AND tr_scan_barcode.tipe = 'RFS'
            AND tr_scan_barcode.status = '4' 
            AND tr_scan_barcode.tipe_motor = '$spk->id_tipe_kendaraan' 
            AND tr_scan_barcode.warna = '$spk->id_warna' 
            AND tr_penerimaan_unit_dealer.status = 'close'
            AND tr_penerimaan_unit_dealer_detail.jenis_pu = 'rfs'
            AND tr_penerimaan_unit_dealer_detail.retur = '0'
            AND tr_penerimaan_unit_dealer_detail.status_on_spk = 'booking'
            -- AND tr_penerimaan_unit_dealer_detail.no_spk = '$no_spk'
            $is_indent
            AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (
            		SELECT no_mesin FROM tr_sales_order WHERE no_mesin IS NOT NULL UNION SELECT no_mesin FROM tr_sales_order_gc_nosin)
			ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC lIMIT 1
            ");
		if ($nosin->num_rows() > 0) {
			return $nosin->row()->no_mesin;
		}
	}
	function cek_nosin_fifo($id_tipe_kendaraan, $id_warna)
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$dt_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_scan_barcode.tipe,tr_penerimaan_unit_dealer_detail.fifo

            FROM tr_penerimaan_unit_dealer_detail LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 

            LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer

            LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item

            LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 

            LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_penerimaan_unit_dealer_detail.status_dealer = 'input'

            AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
            AND tr_penerimaan_unit_dealer_detail.retur = '0' 
            and tr_scan_barcode.tipe='RFS' 
            AND tr_scan_barcode.status = '4' 
            AND ms_item.id_tipe_kendaraan='$id_tipe_kendaraan' AND ms_item.id_warna = '$id_warna' AND tr_penerimaan_unit_dealer.status = 'close'

            AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_sales_order WHERE no_mesin IS NOT NULL)

            ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC LIMIT 1");
		if ($dt_nosin->num_rows() > 0) {
			return $dt_nosin->row()->no_mesin;
		}
	}
	public function save()
	{
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);

		$tgl      = date('Y-m-d');

		$login_id = $this->session->userdata('id_user');

		$tabel    = $this->tables;

		$pk       = $this->pk;
		$estimasi = $this->db->query("SELECT * FROM ms_estimasi_stnk_bpkb_cash")->row();
		$data['estimasi_stnk']      =	$estimasi->estimasi_stnk;

		$data['estimasi_bpkb_cash'] =	$estimasi->estimasi_bpkb_cash;
		$no_spk     = $data['no_spk'] 	= $this->input->post('no_spk');
		$spk        = $this->db->get_where('tr_spk', ['no_spk' => $no_spk])->row();
		$no_mesin   = $this->input->post('no_mesin');
		$id_dealer  = $this->m_admin->cari_dealer();
		// $cek_indent = $this->db->get_where('tr_po_dealer_indent',['id_spk'=>$no_spk]);
		// $is_indent = 'AND tr_penerimaan_unit_dealer_detail.po_indent IS NULL';

		// if ($cek_indent->num_rows()>0) {
		// 	$ind       = $cek_indent->row();
		// 	$is_indent = "AND tr_penerimaan_unit_dealer_detail.po_indent='ya' AND tr_penerimaan_unit_dealer_detail.id_indent='$ind->id_indent'";
		// 	$this->db->update('tr_po_dealer_indent',['status'=>'completed'],['id_indent'=>$ind->id_indent]);
		// }
		// $id_item = $spk->id_tipe_kendaraan.'-'.$spk->id_warna;
		// $nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka
		//           FROM tr_penerimaan_unit_dealer_detail 
		//           JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 
		//           JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
		//           WHERE 
		//           tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
		//           AND tr_scan_barcode.tipe = 'RFS' 
		//           AND tr_scan_barcode.id_item = '$id_item' 
		//           AND tr_penerimaan_unit_dealer.status = 'close'
		//           AND tr_penerimaan_unit_dealer_detail.jenis_pu = 'rfs'
		//           AND tr_penerimaan_unit_dealer_detail.status_on_spk = 'booking'
		//           AND tr_penerimaan_unit_dealer_detail.no_spk = '$no_spk'
		//           $is_indent
		//           AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_sales_order WHERE no_mesin IS NOT NULL)
		// 	ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC lIMIT 1
		//           ");  

		$data['direct_gift']    = $this->input->post('direct_gift');

		$id_dealer              = $this->m_admin->cari_dealer();

		$data['id_dealer']      = $id_dealer;

		$data['status_so']      = "input";
		$data['status_cetak']      = "approve";

		$data['tgl_po_leasing'] = $this->input->post('tgl_po_leasing');

		$data['no_po_leasing']  = $this->input->post('no_po_leasing');

		$id                     = $this->cek_no_so();

		$data['id_sales_order'] = $id;

		$data['created_at']     = $waktu;

		$data['created_by']     = $login_id;
		if ($spk->jenis_beli == 'Cash') {
			if (isset($_POST['chk_program_umum'])) {
				$data['chk_program_umum'] = 1;
			}
			if (isset($_POST['chk_program_gabungan'])) {
				$data['chk_program_gabungan'] = 1;
			}
		} else {
			if (isset($_POST['chk_program_umum2'])) {
				$data['chk_program_umum'] = 1;
			}
			if (isset($_POST['chk_program_gabungan2'])) {
				$data['chk_program_gabungan'] = 1;
			}
		}
		// $no_mesin = $this->cek_nosin_spk($no_spk);
		// if ($no_mesin==false) {
		// 	$_SESSION['pesan'] 	= "Maaf no. mesin tidak tersedia !";
		// 	$_SESSION['tipe'] 	= "danger";
		// 	redirect(base_url('dealer/sales_order/add'),'refresh');
		// 	exit;
		// }else{
		$data['no_mesin'] = $no_mesin;
		$fkb              = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$no_mesin'");
		$tahun_produksi         = $fkb->num_rows() > 0 ? $fkb->row()->tahun_produksi : '';
		$scan                   = $this->db->get_where('tr_scan_barcode', ['no_mesin' => $no_mesin]);
		$data['no_rangka']      = $scan->num_rows() > 0 ? $scan->row()->no_rangka : '';
		$data['tahun_produksi'] = $tahun_produksi;
		$upd_spk                = ['status_spk' => 'close', 'no_mesin_spk' => $no_mesin];
		if ($no_mesin != $spk->no_mesin_spk) {
			$del_book[] = ['status_on_spk' => null, 'no_spk' => null, 'booking_at' => null, 'booking_by' => null, 'no_mesin' => $spk->no_mesin_spk];
		}
		$upd_penerimaan         = ['status_on_spk' => 'hard_book', 'hard_booking_at' => $waktu, 'hard_booking_by' => $login_id];
		// }
		$cek_nosin = $this->m_admin->getByID("tr_sales_order", "no_mesin", $no_mesin);
		if ($cek_nosin->num_rows() == 0) {
			$this->m_admin->insert($tabel, $data);
			if (isset($upd_spk)) {
				$this->db->update('tr_spk', $upd_spk, ['no_spk' => $no_spk]);
			}

			$cek_indent = $this->db->get_where('tr_po_dealer_indent', ['id_spk' => $no_spk]);
			if ($cek_indent->num_rows() > 0) {
				$ind       = $cek_indent->row();
				// cek apakah status nya cancel
				if ($ind->status == 'cancaled') {
					# code...
				} else {
					$this->db->update('tr_po_dealer_indent', ['status' => 'completed'], ['id_indent' => $ind->id_indent]);
				}
				
			}
			if (isset($del_book)) {
				$this->db->update_batch('tr_penerimaan_unit_dealer_detail', $del_book, 'no_mesin');
			}
			if (isset($upd_penerimaan)) {
				$this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $no_mesin]);
			}
			$save = "ok";
		} else {
			$save = "none";
		}
		if ($save == 'ok') {
			$get_so = $this->db->query("SELECT * FROM tr_sales_order WHERE id_dealer='$id_dealer' AND status_so='input' AND tr_sales_order.created_by = '$login_id' ORDER BY id_sales_order DESC LIMIT 0,1 ")->row()->id_sales_order;
			$id_ksu		= $this->input->post("id_ksu");
			if (is_array($id_ksu)) {
				foreach ($id_ksu as $key => $val) {
					if (isset($_POST["check_$key"])) {
						$dt_ksu['id_ksu'] 	= $_POST['id_ksu'][$key];
						$dt_ksu['id_sales_order'] 	= $get_so;
						$dt_ksu['id_koneksi_ksu'] 	= $_POST['id_koneksi_ksu'];
						$dt_ksu['id_dealer'] 		= $id_dealer;
						$dt_ksu['created_at']		= $waktu;
						$dt_ksu['created_by']		= $login_id;
						$this->m_admin->insert("tr_sales_order_ksu", $dt_ksu);
					}
				}
			}
			//Ubah status No. Mesin terjual
			$cek_pik = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor
		 	FROM tr_penerimaan_unit_dealer_detail 
		 	LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin		 					 			 	
		 	LEFT JOIN tr_sales_order ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_sales_order.no_mesin
		 	LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			 WHERE tr_penerimaan_unit_dealer_detail.no_mesin ='$no_mesin'")->row();
			if (isset($cek_pik->no_mesin)) {
				$this->db->query("UPDATE tr_scan_barcode SET status = 5 WHERE no_mesin = '$cek_pik->no_mesin'");
			}

			//Update Doc. SPK
			$this->m_spk->cetakSPK($no_spk, 1);

			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/add'>";
		} else {
			$_SESSION['pesan'] 	= "No Mesin yang dipilih sudah tersimpan di Sales Order sebelumnya";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function save_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl 				= date('Y-m-d');
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_sales_order_gc";
		$pk					= "id_sales_order_gc";

		$data['tgl_estimasi_bpkb'] =	$this->input->post('tgl_estimasi_bpkb');
		$data['tgl_estimasi_stnk'] =	$this->input->post('tgl_estimasi_stnk');
		$data['no_spk_gc'] = $no_spk_gc		= $this->input->post('no_spk_gc');
		$data['id_dealer'] 				= $id_dealer	= $this->m_admin->cari_dealer();
		$data['status_so'] 				= "input";
		$data['tgl_po_leasing']		= $this->input->post('tgl_po_leasing');
		$data['no_po_leasing']		= $this->input->post('no_po_leasing');
		$data['tgl_pengiriman']		= $this->input->post('tgl_pengiriman');
		$id  											= $this->cek_no_so_gc();
		$data['id_sales_order_gc'] = $id;
		$data['created_at']				= $waktu;
		$data['created_by']				= $login_id;
		$lastHeader = $this->db->query("SELECT * From tr_sales_order_gc_nosin WHERE created_by='$login_id' AND status='new'");
		$cek_qty_pesan = $this->db->query("SELECT SUM(qty) AS tot_qty FROM tr_spk_gc_kendaraan WHERE no_spk_gc='$no_spk_gc'")->row()->tot_qty;
		if ($lastHeader->num_rows() < $cek_qty_pesan) {
			$_SESSION['pesan'] 	= "Qty no. mesin kurang dari Qty. SPK";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
			die();
		}
		if ($lastHeader->num_rows() > 0) {
			$this->m_admin->insert($tabel, $data);
			$this->db->query("UPDATE tr_sales_order_gc_nosin set status='input', id_sales_order_gc = '$id', created_at='$waktu',created_by='$login_id' WHERE status='new' AND created_by='$login_id'");
			$cek = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin WHERE id_sales_order_gc = '$id'");
			foreach ($cek->result() as $isi) {
				$this->db->query("UPDATE tr_scan_barcode SET status = 5 WHERE no_mesin = '$isi->no_mesin'");
			}
			$this->db->query("UPDATE tr_order_survey_gc	SET status_survey = 'closed' WHERE no_spk_gc	= '$no_spk_gc'");
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/add_gc'>";
		} else {
			$_SESSION['pesan'] 	= "Detail No Mesin tidak boleh kosong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function save_syarat_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl 				= date('Y-m-d');
		$login_id		= $this->session->userdata('id_user');
		$tabel			= "tr_sales_order_gc";
		$pk					= "id_sales_order_gc";

		$id_sales_order_gc = $this->input->post('id_sales_order_gc');
		$no_spk_gc = $this->input->post('no_spk_gc');
		$jenis_gc = $this->input->post('jenis_gc');
		$simpan = "";
		if ($jenis_gc == 'Swasta/BUMN/Koperasi') {
			$data['npwp'] = $swasta_npwp =	$this->input->post('swasta_npwp');
			$data['situ'] = $swasta_situ =	$this->input->post('swasta_situ');
			$data['siup'] = $swasta_siup =	$this->input->post('swasta_siup');
			$data['tdp'] = $swasta_tdp =	$this->input->post('swasta_tdp');
			$data['surat_kuasa'] = $swasta_kuasa =	$this->input->post('swasta_kuasa');
			if ($swasta_npwp != 'on' or $swasta_situ != 'on' or $swasta_siup != 'on' or $swasta_tdp != 'on' or $swasta_kuasa != 'on') {
				$_SESSION['pesan']  = 'Semua syarat dokumen wajib di-check';
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			} else {
				$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id_sales_order_gc);
				$simpan = "ya";
			}
		} elseif ($jenis_gc == 'Instansi') {
			$data['surat_pernyataan'] = $inst_pernyataan =	$this->input->post('inst_pernyataan');
			$data['surat_kuasa'] = $inst_kuasa =	$this->input->post('inst_kuasa');
			$data['npwp'] = $inst_npwp =	$this->input->post('inst_npwp');
			if ($inst_npwp != 'on' or $inst_kuasa != 'on' or $inst_pernyataan != 'on') {
				$_SESSION['pesan']  = 'Semua syarat dokumen wajib di-check';
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			} else {
				$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id_sales_order_gc);
				$simpan = "ya";
			}
		} elseif ($jenis_gc == 'Joint Promo') {
			$data['surat_pernyataan'] = $joint_pernyataan =	$this->input->post('joint_pernyataan');
			if ($joint_pernyataan != 'on') {
				$_SESSION['pesan']  = 'Semua syarat dokumen wajib di-check';
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			} else {
				$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id_sales_order_gc);
				$simpan = "ya";
			}
		}
		if ($simpan == "ya") {
			$jum_nosin = $this->input->post("jum_nosin");
			for ($i = 1; $i <= $jum_nosin; $i++) {
				$no_mesin	= $_POST["no_mesin_" . $i];
				$ds['nama_stnk'] = $_POST["nama_stnk_" . $i];
				$ds['keterangan'] = $_POST["keterangan_" . $i];
				$this->m_admin->update("tr_sales_order_gc_nosin", $ds, "no_mesin", $no_mesin);
			}
			$_SESSION['pesan']  = 'Data berhasil disimpan';
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/gc'>";
		} else {
			$_SESSION['pesan']  = 'Proses simpan gagal';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	// public function approve()
	// {
	// 	$tabel			= $this->tables;
	// 	$pk					= $this->pk;		
	// 	$id 				= $this->input->get('id');			
	// 	$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
	// 	if($cek_approval == 'salah'){
	// 		$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
	// 		$_SESSION['tipe'] 	= "danger";			
	// 		echo "<script>history.go(-1)</script>";
	// 	}else{			
	// 		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 		$login_id		= $this->session->userdata('id_user');
	// 		$data['status_cetak']	= 'approve';	
	// 		$data['updated_at']		= $waktu;		
	// 		$data['updated_by']		= $login_id;			
	// 		$this->m_admin->update("tr_sales_order",$data,"id_sales_order",$id);						
	// 		$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 		$_SESSION['tipe'] 	= "success";
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sales_order'>";
	// 	}
	// }
	public function approve_gc()
	{
		$tabel			= "tr_sales_order_gc";
		$pk					= "id_sales_order_gc";
		$id 				= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel, $pk, $id);
		if ($cek_approval == 'salah') {
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id		= $this->session->userdata('id_user');
			$data['status_cetak']	= 'approve';
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/gc'>";
		}
	}
	// public function reject()
	// 	{
	// 		$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 		$login_id		= $this->session->userdata('id_user');
	// 		$id 				= $this->input->get('id');				
	// 		$data['status_cetak']	= 'reject';	
	// 		$data['status_close']	= 'reject';	
	// 		$data['updated_at']		= $waktu;		
	// 		$data['updated_by']		= $login_id;			

	// 		$so = $this->m_admin->getByID("tr_sales_order","id_sales_order",$id)->row();
	// 		$this->db->trans_begin();
	// 			$this->db->query("UPDATE tr_scan_barcode SET status = 4 WHERE no_mesin = '$so->no_mesin'");
	// 			// if (isset($upd_penerimaan)) {
	// 			// 	$this->db->update('tr_penerimaan_unit_dealer_detail',$upd_penerimaan,['no_mesin'=>$so->no_mesin]);
	// 			// }
	// 			$this->db->query("DELETE FROM tr_sales_order_ksu WHERE id_sales_order = '$id'");
	// 			$this->m_admin->update("tr_sales_order",$data,"id_sales_order",$id);						
	// 		if ($this->db->trans_status() === FALSE)
	//            {
	//            	$this->db->trans_rollback();
	//                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
	// 				$_SESSION['tipe'] 		= "danger";
	// 				echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sales_order'>";	
	//            }
	//            else{
	//                $this->db->trans_commit();
	//            	$_SESSION['pesan'] 	= "Data has been saved successfully";
	// 			$_SESSION['tipe'] 	= "success";
	// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sales_order'>";
	//            }

	// 	}
	public function reject_gc()
	{
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$id 				= $this->input->get('id');
		$data['status_cetak']	= 'reject';
		$data['status_close']	= 'reject';
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;
		$so = $this->m_admin->getByID("tr_sales_order_gc", "id_sales_order_gc", $id)->row();
		$so_2 = $this->m_admin->getByID("tr_sales_order_gc_nosin", "id_sales_order_gc", $id);
		foreach ($so_2->result() as $am) {
			$this->db->query("UPDATE tr_scan_barcode SET status = 4 WHERE no_mesin = '$am->no_mesin'");
		}
		$this->db->query("DELETE FROM tr_sales_order_gc_nosin WHERE id_sales_order_gc = '$id'");
		$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/gc'>";
	}
	public function cari_id()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 			= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek ORDER BY id_prospek DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 11;
			$id 	= substr($row->id_prospek, $pan, 11) + 1;
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
		echo $kode . "|" . $rt;
	}
	public function cari_kwitansi()
	{
		//KodeDealer/YY/MM/XXXXX		
		$th 				= date("y");
		$bln 				= date("m");
		$kode_dealer 		= "KWITANSI";
		$pr_num 			= $this->db->query("SELECT * FROM tr_sales_order ORDER BY no_kwitansi DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->no_kwitansi) - 5;
			$id 	= substr($row->no_kwitansi, $pan, 15) + 1;
			if ($id < 10) {
				$kode1 = $th . "/" . $bln . "/0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "/" . $bln . "/000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "/" . $bln . "/00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . "/" . $bln . "/0" . $id;
			}
			$kode = $kode_dealer . "/" . $kode1;
		} else {
			$kode = $kode_dealer . "/" . $th . "/" . $bln . "/00001";
		}
		return $kode;
	}
	public function qrcode_lokasi()
	{
		//header("Content-type: image/png");
		$lat = $this->input->get('id');
		$long = $this->input->get('id2');
		//	$params['data'] = "maps.google.com/local?q=$lat,$long"; //data yang akan di jadikan QR CODE
		//	$params['data'] = 'This is a text to encode become QR Code';
		//	$this->ciqrcode->generate($params); 
		$params['data'] = "maps.google.com/local?q=$lat,$long"; //data yang akan di jadikan QR CODE
		qrcode::png($params['data']);
	}
	public function cetak_so()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$amb = $this->m_admin->getByID("tr_sales_order", "id_sales_order", $id)->row();
		$cek_pik = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor
		 	FROM tr_penerimaan_unit_dealer_detail 
		 	LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin		 					 			 	
		 	LEFT JOIN tr_sales_order ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_sales_order.no_mesin
		 	LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
	 		WHERE tr_penerimaan_unit_dealer_detail.no_mesin ='$amb->no_mesin'")->row();

		if (isset($cek_pik->program_umum)) {
			$cek = $this->db->query("SELECT * FROM tr_sales_program WHERE '$tgl' BETWEEN periode_awal AND periode_akhir");
			if ($cek->num_rows() > 0) {
				$ty = $cek->row();
				$data['program_umum']		= $ty->id_sales_program;
			} else {
				$data['program_umum'] = "";
			}
		}
		if (isset($cek_pik->no_mesin)) {
			// $this->db->query("UPDATE tr_scan_barcode SET status = 5 WHERE no_mesin = '$cek_pik->no_mesin'");
			$cek_harga = $this->m_admin->getByID("ms_bbn_dealer", "id_tipe_kendaraan", $cek_pik->tipe_motor);
			$cek_harga2 = $this->m_admin->getByID("ms_bbn_biro", "id_tipe_kendaraan", $cek_pik->tipe_motor);
			/*	if($cek_harga->num_rows() > 0){
  			$m = $cek_harga->row();  			
  			if($cek_pik->tipe_customer == 'Customer Umum'){
  				$harga_m = $m->biaya_bbn;	  				
  			}else{
  				$harga_m = $m->biaya_instansi;	  				
  			}	  		
  		}else{
  			$harga_m = 0;  			
  		}
  		if($cek_harga2->num_rows() > 0){  			
  			$b = $cek_harga2->row();
  			if($cek_pik->tipe_customer == 'Customer Umum'){  				
  				$harga_b = $b->biaya_bbn;	
  			}else{  				
  				$harga_b = $b->biaya_instansi;	
  			}	  		
  		}else{  			
  			$harga_b = 0;
  		}
  	}else{ */
			$harga_m = 0;
			$harga_b = 0;
		}
		//echo $harga_m;
		$getSO = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$id'");
		$cetak_so_ke = $getSO->num_rows() > 0 ? $getSO->row()->cetak_so_ke : 0;
		if ($cetak_so_ke == 0) {
			$data['tgl_cetak_so'] = $waktu;
			$data['status_cetak'] = 'cetak_so';
			$data['cetak_so_by']  = $login_id;
			$data['biaya_bbn']    = $harga_m;
			$data['biaya_biro']   = $harga_b;
			$data['harga_unit']   = 0;
			$data['cetak_so_ke']  = 1;
			$data['updated_at']   = $waktu;
			$data['updated_by']   = $login_id;
		} else {
			$data['cetak_so_ke']  = $cetak_so_ke + 1;
		}

		$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);

		/*$dt_so = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,
			tr_spk.tipe_customer,tr_spk.program_umum
		 	FROM tr_penerimaan_unit_dealer_detail 
		 	LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin		 					 			 	
		 	LEFT JOIN tr_sales_order ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_sales_order.no_mesin
		 	LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
	 		WHERE tr_penerimaan_unit_dealer_detail.no_mesin ='$amb->no_mesin'")->row(); */
		$dt_so = $this->db->query("SELECT tr_sales_order.*,tr_spk.nama_bpkb as nama_bpkb1, tr_sales_order.no_mesin as no_mesinalias, tr_prospek.id_karyawan_dealer,
	  				tr_spk.*,tr_scan_barcode.id_item,ms_karyawan_dealer.nama_lengkap,ms_dealer.pic,
	  				ms_tipe_kendaraan.tipe_ahm, ms_warna.warna,tr_scan_barcode.no_rangka, ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_sales_order.no_mesin,
						CASE 
							WHEN tr_sales_order.tgl_pengiriman IS NULL THEN tr_spk.tgl_pengiriman
							ELSE tr_sales_order.tgl_pengiriman
						END AS tgl_pengiriman
			FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin			
			LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
			WHERE tr_sales_order.id_sales_order = '$id'
			")->row();
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
	  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
	  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
	  		WHERE ms_kelurahan.id_kelurahan = '$dt_so->id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kecamatan 		= $dt_kel->kecamatan;
		$id_kabupaten = $dt_kel->id_kabupaten;
		$kabupaten  	= $dt_kel->kabupaten;
		$id_provinsi  = $dt_kel->id_provinsi;
		$provinsi  		= $dt_kel->provinsi;
		if ($dt_so->alamat_sama != 'Ya') {
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
	  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
	  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
	  		WHERE ms_kelurahan.id_kelurahan = '$dt_so->id_kelurahan2'")->row();
			$kelurahan2 		= $dt_kel->kelurahan;
			$id_kecamatan 		= $dt_kel->id_kecamatan;
			$kecamatan2 		= $dt_kel->kecamatan;
			$id_kabupaten 		= $dt_kel->id_kabupaten;
			$kabupaten2  		= $dt_kel->kabupaten;
			$id_provinsi  		= $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;
			$alamat2 = $dt_so->alamat2;
		} else {
			$kelurahan2  = $kelurahan;
			$kecamatan2  = $kecamatan;
			$kabupaten2  = $kabupaten;
			$alamat2 =	$dt_so->alamat;
		}
		$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_so->id_finance_company'");
		if ($finco->num_rows() > 0) {
			$t = $finco->row();
			$finance_co = $t->finance_company;
		} else {
			$finance_co = "";
		}
		$fkb = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$dt_so->no_mesin'");
		if ($fkb->num_rows() > 0) {
			$fkb = $fkb->row()->tahun_produksi;
		} else {
			$fkb = '';
		}
		$pdf = new PDF_HTML('p', 'mm', 'A4');
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();
		// head	  
		$pdf->SetFont('ARIAL', 'B', 12);
		$pdf->Cell(190, 7, 'Sales Order', 1, 1, 'C');
		$pdf->SetFont('ARIAL', '', 12);
		$tgl = date('d-m-Y', strtotime($dt_so->tgl_cetak_so));
		$pdf->Cell(190, 7, 'No. SO : ' . $dt_so->id_sales_order, 1, 1, 'C');
		$pdf->Cell(190, 7, 'Tanggal : ' . $tgl, 1, 1, 'C');
		$pdf->Cell(190, 3, '', 0, 1, 'C');
		$pdf->SetFont('ARIAL', '', 10);
		/*$pdf->Cell(30, 5, 'Nama Pemesan', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 0, 'L');
	  $pdf->Cell(30, 5, 'Tempat Lahir', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'No.KTP', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 0, 'L');
	  $pdf->Cell(30, 5, 'Tanggal Lahir', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 1, 'L');*/
		$pdf->Cell(31, 5, 'Nama Pemesan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(95, 5, $dt_so->nama_konsumen, 0, 1, 'L');
		// $pdf->Cell(95, 5, 'Tempat Lahir : '.$dt_so->tempat_lahir, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Alamat Domisili', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(95, 5, $dt_so->alamat, 0, 1, 'L');
		//$pdf->Cell(95, 5, 'No. KTP : '.$dt_so->no_ktp, 0, 1, 'L'); //$pdf->Cell(95, 5, 'Tanggal Lahir : '.$dt_so->tgl_lahir, 0, 1, 'L');..
		$lokasi = explode(',', $dt_so->denah_lokasi);
		$latitude = str_replace(' ', '', $lokasi[0]);
		$longitude = str_replace(' ', '', $lokasi[1]);
		$qr_generate = "maps.google.com/local?q=$latitude,$longitude"; //data yang akan di jadikan QR CODE
		// $pdf->Cell(95, 5, 'Alamat Domisili : '.$dt_so->alamat, 0, 1, 'L'); 
		//$pdf->Link(95, 5, 'Lokasi : '.$qr_generate, 0, 1, 'L');
		//$pdf->Cell(14, 5, 'Lokasi : ', 0, 0, 'L');
		// Begin with regular font
		$pdf->SetTextColor(0, 0, 255);
		//$pdf->Write(5, "$qr_generate","$qr_generate",1);
		//	  $pdf->Cell(20, 5, '', 0, 1, 'L');
		$pdf->SetTextColor(0, 0, 0);
		//$pdf->Cell(20,5 ,'','','','',false, "$qr_generate"); 
		//$html = '<a href="'.$qr_generate.'">$qr_generate</a>';
		//$pdf->WriteHTML($html);
		//$pdf->Link(90,10,10,10, $qr_generate);
		// $pdf->Cell(95, 5, 'Kelurahan : '.$kelurahan, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kelurahan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(95, 5, $kelurahan, 0, 1, 'L');

		$qr_generate = "maps.google.com/local?q=$latitude,$longitude"; //data yang akan di jadikan QR CODE

		//$pdf->Cell(20, 5, 'QRCODE : ', 0, 1, 'L');

		//$pdf->Image(site_url().'/dealer/sales_order/qrcode_lokasi?id='.$latitude.'&id2='.$longitude, 10,10,30,30, 'png');
		//$qr_generate = "maps.google.com/local?q=11111111,111111111"; //data yang akan di jadikan QR CODE
		//	$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=55x55",170,49,40,0,'PNG');
		//$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77",164,36,28,0,'PNG');
		// $pdf->Image($this->ciqrcode->generate($params),10,10,30,30);
		//$pdf->Cell(95, 5, 'Kecamatan : '.$kecamatan, 0, 1, 'L');

		//  if ($dt_so->jenis_beli == 'Cash') {
		// 	$voucher_tambahan = $dt_so->voucher_tambahan_1 + $dt_so->diskon;
		// 	if ($dt_so->the_road == 'On The Road') {
		// 		$total_bayar = $dt_so->harga_on_road - ($dt_so->voucher_1 + $voucher_tambahan);
		// 		$bbn = $dt_so->biaya_bbn;
		// 	} elseif ($dt_so->the_road == 'Off The Road') {
		// 		$total_bayar = $dt_so->harga_off_road - ($dt_so->voucher_1 + $voucher_tambahan);
		// 		$bbn = 0;
		// 	}
		// 	//$ho = $dt_so->harga_on_road - ($dt_so->voucher_1 + $voucher_tambahan) - $dt_so->biaya_bbn;
		// 	$ho = $total_bayar - $dt_so->biaya_bbn;
		// 	$total_bayar = $dt_so->total_bayar;
		//     $ho = $total_bayar - $bbn;
		// } else {
		// 	$voucher_tambahan = $dt_so->voucher_tambahan_2 + $dt_so->diskon;
		// 	if ($dt_so->the_road == 'On The Road') {					
		// 		$total_bayar = $dt_so->harga_on_road - ($dt_so->voucher_2 + $voucher_tambahan);
		// 		$bbn = $dt_so->biaya_bbn;
		// 	} elseif ($dt_so->the_road == 'Off The Road') {
		// 		$total_bayar = $dt_so->harga_off_road - ($dt_so->voucher_2 + $voucher_tambahan);
		// 		$bbn = 0;
		// 	}
		// 	//$ho = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan) - $row->biaya_bbn;
		// 	$ho = $total_bayar - $dt_so->biaya_bbn;
		// 	$total_bayar = $dt_so->total_bayar;
		//     $ho = $total_bayar - $bbn;
		// }

		$return 	= $this->m_admin->detail_individu($dt_so->no_spk);

		$pdf->Cell(31, 5, 'Kecamatan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(95, 5, $kecamatan, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kota/Kabupaten', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(95, 5, $kabupaten, 0, 1, 'L');
		$pdf->Cell(95, 5, '', 0, 0, 'L');
		$pdf->Cell(34, 5, 'Harga', 0, 0, 'L');
		$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
		$pdf->Cell(30, 5, $this->mata_uang($return['harga']), 0, 1, 'R');
		$pdf->Cell(31, 5, 'No. HP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_hp, 0, 0, 'L');
		$pdf->Cell(34, 5, 'PPN', 0, 0, 'L');
		$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
		$pdf->Cell(30, 5, $this->mata_uang($return['ppn']), 0, 1, 'R');
		$pdf->Cell(31, 5, 'No. Telp', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_telp, 0, 0, 'L');
		$pdf->Cell(34, 5, 'Harga Off The Road', 0, 0, 'L');
		$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
		$pdf->Cell(30, 5, $this->mata_uang($return['harga_off_road']), 0, 1, 'R');
		// $pdf->Cell(95, 5, 'Kota/Kabupaten : '.$kabupaten, 0, 1, 'L');
		//  $pdf->Cell(95, 5, 'No. HP : '.$dt_so->no_hp, 0, 1, 'L');
		//$pdf->Cell(95, 5, 'No. Telp : '.$dt_so->no_telp, 0, 1, 'L');
		//  $kelompok_harga = $this->db->query("SELECT * FROM ms_kelompok_harga WHERE kelompok_harga='$dt_so->tipe_customer'")->row()->id_kelompok_harga;
		//  $off_the_road = $this->db->query("SELECT * FROM ms_kelompok_md WHERE id_kelompok_harga='$kelompok_harga' AND id_item = '$dt_so->id_item'")->row();
		// $pdf->Cell(95, 5, 'Kota/Kabupaten : '.$kabupaten, 0, 0, 'L'); $pdf->Cell(95, 5, 'Harga : '.$off_the_road->harga_jual, 0, 1, 'L');
		//$ppn = $off_the_road->harga_jual * 0.1;
		//  $pdf->Cell(95, 5, 'No. HP : '.$dt_so->no_hp, 0, 0, 'L'); $pdf->Cell(95, 5, 'PPN : '.$ppn, 0, 1, 'L');
		//$harga_off_the_road = $off_the_road->harga_jual + $ppn;
		//$pdf->Cell(95, 5, 'No. Telp : '.$dt_so->no_telp, 0, 0, 'L');$pdf->Cell(95, 5, 'Harga Off The Road : '.$harga_off_the_road, 0, 1, 'L');
		$pdf->Cell(95, 5, '', 0, 0, 'L');
		$pdf->Cell(34, 5, 'Biaya BBN', 0, 0, 'L');
		$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
		$pdf->Cell(30, 5, $this->mata_uang($return['bbn']), 0, 1, 'R');
		$pdf->Cell(31, 5, 'No. KTP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_ktp, 0, 0, 'L');
		if ($dt_so->jenis_beli == 'Cash') {
			$tot = $dt_so->harga_tunai - ($dt_so->voucher_1 + $dt_so->voucher_tambahan_1 + $dt_so->diskon);
		} else {
			$tot = $dt_so->harga_tunai - ($dt_so->voucher_2 + $dt_so->voucher_tambahan_2 + $dt_so->diskon);
		}



		$pdf->Cell(34, 5, 'Harga On The Road', 0, 0, 'L');
		$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
		$pdf->Cell(30, 5, $this->mata_uang($return['harga_on_road']), 0, 1, 'R');
		//$pdf->Cell(95, 5, 'Biaya BBN : '.number_format($dt_so->biaya_bbn, 0, ',', '.'), 0, 1, 'L');
		// $ontheroad = $harga_off_the_road+$dt_so->biaya_bbn;
		// $pdf->Cell(95, 5, 'Alamat KTP : '.$alamat2, 0, 1, 'L');// $pdf->Cell(95, 5, 'Harga On The Road : '.$ontheroad, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Alamat KTP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $alamat2, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kota/Kabupaten', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $kabupaten2, 0, 0, 'L');
		$pdf->Cell(34, 5, 'Tipe', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->id_tipe_kendaraan . "-" . $dt_so->tipe_ahm, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kelurahan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $kelurahan2, 0, 0, 'L');
		$pdf->Cell(34, 5, 'Warna', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->id_warna . "-" . $dt_so->warna, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kecamatan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $kecamatan2, 0, 0, 'L');
		$pdf->Cell(34, 5, 'Tahun Rakitan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $fkb, 0, 1, 'L');
		$pdf->Cell(31, 5, 'NPWP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->npwp, 0, 0, 'L');
		$pdf->Cell(34, 5, 'No. Mesin', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_mesin, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Nama Pada BPKB', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->nama_bpkb1, 0, 0, 'L');
		$pdf->Cell(34, 5, 'No. Rangka', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_rangka, 0, 1, 'L');
		/*
	  //$pdf->Cell(95, 5, 'Tipe : '.$dt_so->tipe_ahm, 0, 1, 'L');
	 // $pdf->Cell(95, 5, 'Kelurahan : '.$kelurahan2, 0, 0, 'L'); $pdf->Cell(95, 5, 'Warna : '.$dt_so->warna, 0, 1, 'L');
	 // $pdf->Cell(95, 5, 'Kecamatan : '.$kecamatan2, 0, 0, 'L'); $pdf->Cell(95, 5, 'Tahun Rakitan : '.$dt_so->tahun_rakitan, 0, 1, 'L');
	  $pdf->Cell(95, 5, 'NPWP :'.$dt_so->no_npwp, 0, 0, 'L'); $pdf->Cell(95, 5, 'No. Mesin : '.$dt_so->no_mesinalias, 0, 1, 'L');
	  $pdf->Cell(95, 5, 'Nama Pada BPKB : '.$dt_so->nama_bpkb1, 0, 0, 'L');$pdf->Cell(95, 5, 'No. Rangka : '.$dt_so->no_rangka, 0, 1, 'L');
	  */
		$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 11);
		$pdf->Cell(190, 7, 'Sistem Pembelian', 1, 1, 'C');
		//if ($dt_so->uang_muka == 0 or $dt_so->uang_muka == '') {
		$yy = 152;
		if ($dt_so->jenis_beli == 'Cash') {
			$yy = 178;
			//$tot = $dt_so->harga_tunai - ($dt_so->voucher_1 + $dt_so->voucher_tambahan_1+$dt_so->diskon);
			$tot = $return['harga_tunai'] - ($return['voucher'] + $return['voucher_tambahan']);
			// Jika Jenis Pembelian Cash
			$pdf->Cell(190, 7, 'Cash', 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(95, 3, '', 0, 1, 'L');

			$pdf->Cell(31, 5, 'Harga Tunai', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, 'Rp. ' . $this->mata_uang($return['harga_tunai']), 0, 0, 'L');
			$pdf->Cell(34, 5, 'Jenis', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $dt_so->the_road, 0, 1, 'L');

			$pdf->Cell(31, 5, 'Nilai Voucher', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, 'Rp. ' . $this->mata_uang($return['voucher']), 0, 0, 'L');
			$pdf->Cell(34, 5, 'Voucher Tambahan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, 'Rp. ' . $this->mata_uang($return['voucher_tambahan']), 0, 1, 'L');
			$pdf->Cell(31, 5, 'Total Bayar', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, 'Rp. ' . $this->mata_uang($return['total_bayar']), 0, 1, 'L');
		} elseif ($dt_so->jenis_beli == 'Kredit') {
			$yy = 198;
			// Jika Jenis Pembelian Kredit
			$pdf->SetFont('ARIAL', 'B', 11);
			$pdf->Cell(190, 7, 'Kredit', 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(95, 3, '', 0, 1, 'L');
			$pdf->Cell(31, 5, 'Leasing/FINCO', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $finance_co, 0, 1, 'L');
			$pdf->Cell(31, 5, 'Uang Muka/DP', 0, 0, 'L');
			$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
			$pdf->Cell(30, 5, $this->mata_uang($dt_so->dp_stor - $dt_so->diskon), 0, 1, 'R');
			//$pdf->Cell(95, 5, 'Leasing/FINCO : '.$finco->finance_company, 0, 1, 'L');
			//$pdf->Cell(95, 5, 'Uang Muka / DP : '. , 0, 1, 'L');
			$kerja				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_so->pekerjaan'");
			if ($kerja->num_rows() > 0) {
				$tr = $kerja->row();
				$pekerjaan = $tr->pekerjaan;
			} else {
				$pekerjaan = "-";
			}
			$voucher = $dt_so->voucher_2 == null ? '' : $this->mata_uang($dt_so->voucher_2);
			$jabatan = $dt_so->jabatan == '' ? '-' : $dt_so->jabatan;
			$lama_kerja = $dt_so->lama_kerja == '' ? '-' : $dt_so->lama_kerja;
			$penghasilan = $dt_so->penghasilan == '' ? '0' : $dt_so->penghasilan;
			$voucher_tambahan_2 = $dt_so->voucher_tambahan_2 == null ? '' : $this->mata_uang($dt_so->voucher_tambahan_2 + $dt_so->diskon);
			$pdf->Cell(31, 5, 'Voucher', 0, 0, 'L');
			$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
			$pdf->Cell(30, 5, $voucher, 0, 0, 'R');
			$pdf->Cell(30, 5, '', 0, 0, 'L');
			$pdf->Cell(34, 5, 'Voucher Tambahan', 0, 0, 'L');
			$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
			$pdf->Cell(30, 5, $voucher_tambahan_2, 0, 1, 'R');
			$pdf->Cell(31, 5, 'Angsuran', 0, 0, 'L');
			$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
			$pdf->Cell(30, 5, $this->mata_uang($dt_so->angsuran), 0, 1, 'R');
			$pdf->Cell(31, 5, 'Tenor', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(30, 5, $this->mata_uang($dt_so->tenor) . ' Bulan', 0, 1, 'L');
			$pdf->Cell(31, 5, 'Pekerjaan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $pekerjaan, 0, 0, 'L');
			$pdf->Cell(34, 5, 'Lama Kerja', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $lama_kerja, 0, 1, 'L');
			$pdf->Cell(31, 5, 'Jabatan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $jabatan, 0, 0, 'L');
			$pdf->Cell(34, 5, 'Penghasilan', 0, 0, 'L');
			$pdf->Cell(2, 5, ': Rp.', 0, 0, 'L');
			$pdf->Cell(30, 5, $this->mata_uang($penghasilan), 0, 1, 'R');
		}

		$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 11);
		$pdf->Cell(190, 7, 'Syarat dan Ketentuan', 1, 1, 'C');
		$pdf->SetFont('ARIAL', '', 10);
		$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->Cell(4, 5, '1.', 0, 0, 'L');
		$pdf->WriteHTML('Harga yang tercantum dalam Sales Order <b>telah mengikat</b>. <br>');
		$pdf->Cell(4, 5, '2.', 0, 0, 'L');
		$pdf->WriteHTML("Sales Order ini dianggap <b>sah</b> apabila telah ditandatangani oleh Pemesan, Sales Person, Kepala Cabang. <br>");
		$pdf->Cell(4, 5, '3.', 0, 0, 'L');
		// $pdf->MultiCell(186,5,"Pembayaran dengan Cek/Bilyet Giro/Transfer harus diatasnamakan $nama_rek dan dianggap sah apabila telah diterima di rekening $norek.",0,"L");
		$pdf->MultiCell(186, 5, "Pembayaran dengan Cek/Bilyet Giro/Transfer dianggap sah apabila telah diterima di rekening : ", 0, "L");
		$norek_de = $this->db->query("SELECT * FROM ms_norek_dealer WHERE id_dealer = '$dt_so->id_dealer' ");
		if ($norek_de->num_rows() > 0) {
			$norek_dealer = $norek_de->row()->id_norek_dealer;
		} else {
			$norek_dealer = "";
		}
		$detail_norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer_detail WHERE id_norek_dealer = '$norek_dealer' LIMIT 0,2");
		$x = 1;
		$cek = 0;
		$xx = 18;
		$count = 1;
		$count_isi = $detail_norek_dealer->num_rows();
		foreach ($detail_norek_dealer->result() as $key => $norek) {
			if ($count <= 2) {

				$bank = $this->db->query("SELECT * FROM ms_bank WHERE id_bank = '$norek->id_bank'")->row();
				$pdf->SetXY($xx, $yy);
				//	$pdf->MultiCell(70,5," Atas Nama \t\t\t\t: <b>$norek->nama_rek</b> \n Nama Bank \t\t\t: $bank->bank \n No Rekening \t: $norek->no_rek",0,'L');
				//$pdf->WriteHTML("Atas Nama\t\t\t\t\t\t\t: <b>$norek->nama_rek</b><br>
				// Nama Bank  \t\t\t\t: <b>$bank->bank</b><br>
				//No Rekening : <b> $norek->no_rek</b>
				//");
				$pdf->WriteHTML("Atas Nama\t\t\t\t\t\t\t: <b>$norek->nama_rek</b><br>");
				$pdf->SetX($xx);
				$pdf->WriteHTML("Nama Bank\t\t\t\t\t\t: $bank->bank<br>");
				$pdf->SetX($xx);
				$pdf->WriteHTML("No Rekening\t\t\t\t: $norek->no_rek<br>");
				if ($x < $count_isi) {
					$xx += 70;
					$pdf->SetXY($xx, $yy);
					$pdf->MultiCell(30, 5, "\n Atau \n", 0, 'L');
					$xx += 30;
				}
			}
			$count++;
			$x++;
		}
		$pdf->Ln(2);
		// $norek = implode(" atau ", $norek->no_rek);
		// $nama_rek = implode(" atau ", $nama_rek);
		$pdf->Cell(4, 5, '4.', 0, 0, 'L');
		$pdf->WriteHTML("Pembayaran Tunai dianggap <b>sah</b> apabila telah diterbitkan kwitansi oleh <b>$dt_so->nama_dealer.</b> <br>");
		$pdf->Cell(4, 5, '5.', 0, 0, 'L');
		$pdf->Multicell(186, 5, "Pengurusan STNK & BPKB dilaksanakan setelah 100% harga kendaraan lunas.", 0, "L");
		$pdf->Cell(4, 5, '6.', 0, 0, 'L');
		$pdf->WriteHTML("Nama pada Faktur STNK (BPKB) yang tercantum dalam Sales Order ini  <b>tidak dapat diubah.</b><br>");
		$pdf->Cell(4, 5, '7.', 0, 0, 'L');
		$pdf->WriteHTML("Sepeda motor yang sudah dibeli <b>tidak dapat dikembalikan</b> atau <b>ditukar.</b> <br>");
		$pdf->Cell(4, 5, '', 0, 1, 'L');
		$pdf->Cell(47.5, 5, 'Pemesan', 0, 0, 'C');
		$pdf->Cell(47.5, 5, 'Sales Person', 0, 0, 'C');
		$pdf->Cell(47.5, 5, 'Kepala Cabang', 0, 0, 'C');
		if ($dt_so->jenis_beli != 'Kredit') {
			$pdf->Cell(47.5, 5, 'Pengambil BPKB', 0, 1, 'C');
		}
		$pdf->Cell(4, 15, '', 0, 1, 'C');
		$pdf->Cell(47.5, 5, '( ' . $dt_so->nama_konsumen . ' )', 0, 0, 'C');
		$pdf->Cell(47.5, 5, '( ' . $dt_so->nama_lengkap . ' )', 0, 0, 'C');
		$pdf->Cell(47.5, 5, '( ' . $dt_so->pic . ' )', 0, 0, 'C');
		if ($dt_so->jenis_beli != 'Kredit') {
			$pdf->Cell(47.5, 5, '(                          )', 0, 1, 'C');
		}
		$estimasi = $this->db->query("SELECT * FROM ms_estimasi_stnk_bpkb_cash")->row();
		$tgl = date('Y-m-d');
		$stnk = date("Y-m-d", strtotime("+" . $estimasi->estimasi_stnk . " days", strtotime($tgl)));
		$bpkb = date("Y-m-d", strtotime("+" . $estimasi->estimasi_bpkb_cash . " days", strtotime($tgl)));
		$day = 1;
		$kirim = date("Y-m-d", strtotime("+" . $day . " days", strtotime($tgl)));
		$pdf->Ln(5);
		$pdf->Cell(43, 5, 'Tanggal Estimasi STNK', 0, 0, 'L');
		$pdf->Cell(50, 5, ':  ' . $stnk, 0, 1, 'L');
		if ($dt_so->jenis_beli == 'Cash') {
			$pdf->Cell(43, 5, 'Tanggal Estimasi BPKB', 0, 0, 'L');
			$pdf->Cell(50, 5, ':  ' . $bpkb, 0, 1, 'L');
		}
		$pdf->Cell(43, 5, 'Tanggal Pengiriman Unit', 0, 0, 'L');
		$pdf->Cell(50, 5, ':  ' . $dt_so->tgl_pengiriman, 0, 1, 'L');
		$pdf->setY(285);
		$pdf->SetFont('ARIAL', 'I', 9);
		$pdf->Cell(43, 5, 'Cetakan Ke-' . $dt_so->cetak_so_ke, 0, 0, 'L');
		//	  $pdf->Line(11, 31, 200, 31);	   	
		//ob_end_clean();
		$pdf->Output();
	}
	public function cetak_so_gc()
	{
		$data['tanggal'] = $tgl 				= gmdate("d/m/Y", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$data['cetak'] = 'cetak_so_gc';
		$data['id'] = $id 					= $this->input->get('id');
		$data['id_sales_order'] = $id;
		$amb = $this->m_admin->getByID("tr_sales_order_gc", "id_sales_order_gc", $id)->row();
		$getSO = $this->db->query("SELECT * FROM tr_sales_order_gc WHERE id_sales_order_gc = '$id'");
		$cetak_so_ke = $getSO->num_rows() > 0 ? $getSO->row()->cetak_so_ke : 0;
		if ($cetak_so_ke == 0) {
			$data2['tgl_cetak_so'] = $waktu;
			$data2['status_cetak'] = 'cetak_so';
			$data2['cetak_so_by']  = $login_id;
			// $data['biaya_bbn']    = $harga_m;		
			// $data['biaya_biro']   = $harga_b;		
			//$data['harga_unit']   = 0;		
			$data2['cetak_so_ke']  = 1;
			$data2['updated_at']   = $waktu;
			$data2['updated_by']   = $login_id;
		} else {
			$data2['cetak_so_ke']  = $cetak_so_ke + 1;
			if($getSO->row()->tgl_cetak_so!=''){
				$data['tanggal'] = $tgl = $getSO->row()->tgl_cetak_so;
			}
		}

		$this->m_admin->update("tr_sales_order_gc", $data2, "id_sales_order_gc", $id);

		$sql = $this->db->query("SELECT tr_sales_order_gc.*,tr_spk_gc.nama_npwp AS nama_bpkb1, tr_prospek_gc.id_karyawan_dealer,
	  				tr_spk_gc.*,ms_karyawan_dealer.nama_lengkap,ms_dealer.pic,tr_spk_gc.no_ktp,
	  				ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_sales_order_gc 
			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc	
			LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer			
			LEFT JOIN ms_dealer ON tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
			WHERE tr_sales_order_gc.id_sales_order_gc = '$id'");
		$so = $data['dt_so'] = $sql->row();
		//$this->load->view('dealer/sales_order_cetak_gc', $data);    
		$html = $this->load->view('dealer/sales_order_cetak_gc', $data, true);
		$mpdf->WriteHTML($html);
		$output = 'cetak_.pdf';
		$mpdf->Output("$output", 'I');
	}

	public function cetak_so_gc_old()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$amb = $this->m_admin->getByID("tr_sales_order_gc", "id_sales_order_gc", $id)->row();

		$getSO = $this->db->query("SELECT * FROM tr_sales_order_gc WHERE id_sales_order_gc = '$id'");
		$cetak_so_ke = $getSO->num_rows() > 0 ? $getSO->row()->cetak_so_ke : 0;
		if ($cetak_so_ke == 0) {
			$data['tgl_cetak_so'] = $waktu;
			$data['status_cetak'] = 'cetak_so';
			$data['cetak_so_by']  = $login_id;
			// $data['biaya_bbn']    = $harga_m;		
			// $data['biaya_biro']   = $harga_b;		
			//$data['harga_unit']   = 0;		
			$data['cetak_so_ke']  = 1;
			$data['updated_at']   = $waktu;
			$data['updated_by']   = $login_id;
		} else {
			$data['cetak_so_ke']  = $cetak_so_ke + 1;
		}

		$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);

		$dt_so = $this->db->query("SELECT tr_sales_order_gc.*,tr_spk_gc.nama_npwp AS nama_bpkb1, tr_prospek_gc.id_karyawan_dealer,
	  				tr_spk_gc.*,ms_karyawan_dealer.nama_lengkap,ms_dealer.pic,tr_spk_gc.no_ktp,
	  				ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_sales_order_gc 
			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc	
			LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer			
			LEFT JOIN ms_dealer ON tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
			WHERE tr_sales_order_gc.id_sales_order_gc = '$id'
			")->row();
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
	  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
	  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
	  		WHERE ms_kelurahan.id_kelurahan = '$dt_so->id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kecamatan 		= $dt_kel->kecamatan;
		$id_kabupaten = $dt_kel->id_kabupaten;
		$kabupaten  	= $dt_kel->kabupaten;
		$id_provinsi  = $dt_kel->id_provinsi;
		$provinsi  		= $dt_kel->provinsi;
		if ($dt_so->alamat_sama != 'Ya') {
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
	  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
	  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
	  		WHERE ms_kelurahan.id_kelurahan = '$dt_so->id_kelurahan2'")->row();
			$kelurahan2 		= $dt_kel->kelurahan;
			$id_kecamatan 		= $dt_kel->id_kecamatan;
			$kecamatan2 		= $dt_kel->kecamatan;
			$id_kabupaten 		= $dt_kel->id_kabupaten;
			$kabupaten2  		= $dt_kel->kabupaten;
			$id_provinsi  		= $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;
			$alamat2 = $dt_so->alamat2;
		} else {
			$kelurahan2  = $kelurahan;
			$kecamatan2  = $kecamatan;
			$kabupaten2  = $kabupaten;
			$alamat2 =	$dt_so->alamat;
		}
		$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_so->id_finance_company'");
		if ($finco->num_rows() > 0) {
			$t = $finco->row();
			$finance_co = $t->finance_company;
		} else {
			$finance_co = "";
		}
		// $fkb = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$dt_so->no_mesin'");
		// 	if ($fkb->num_rows() > 0) {
		// 		$fkb = $fkb->row()->tahun_produksi;
		// 	}else{
		$fkb = '';
		//	}
		$pdf = new PDF_HTML('p', 'mm', 'A4');
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();
		// head	  
		$pdf->SetFont('ARIAL', 'B', 12);
		$pdf->Cell(190, 7, 'Sales Order Group Customer', 1, 1, 'C');
		$pdf->SetFont('ARIAL', '', 12);
		$tgl = date('d-m-Y', strtotime($dt_so->tgl_cetak_so));
		$pdf->Cell(190, 7, 'No. SO : ' . $dt_so->id_sales_order_gc, 1, 1, 'C');
		$pdf->Cell(190, 7, 'Tanggal : ' . $tgl, 1, 1, 'C');
		$pdf->Cell(190, 3, '', 0, 1, 'C');
		$pdf->SetFont('ARIAL', '', 10);
		/*$pdf->Cell(30, 5, 'Nama Pemesan', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 0, 'L');
	  $pdf->Cell(30, 5, 'Tempat Lahir', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 1, 'L');
	  $pdf->Cell(30, 5, 'No.KTP', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 0, 'L');
	  $pdf->Cell(30, 5, 'Tanggal Lahir', 0, 0, 'L'); $pdf->Cell(60, 5, ':---', 0, 1, 'L');*/
		$pdf->Cell(31, 5, 'Nama Perusahaan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->nama_npwp, 0, 0, 'L');
		$pdf->Cell(31, 5, 'Penanggungjawab', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->nama_penanggung_jawab, 0, 1, 'L');
		$pdf->Cell(31, 5, 'No NPWP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_npwp, 0, 0, 'L');
		$pdf->Cell(31, 5, 'No KTP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_ktp, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Alamat Prsh', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->alamat, 0, 0, 'L');
		$pdf->Cell(31, 5, 'No Telp Prsh', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_telp, 0, 1, 'L');
		//$pdf->Cell(31, 5, 'Alamat Domisili', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(62, 5, $dt_so->alamat, 0, 1, 'L');
		//$pdf->Cell(95, 5, 'No. KTP : '.$dt_so->no_ktp, 0, 1, 'L'); //$pdf->Cell(95, 5, 'Tanggal Lahir : '.$dt_so->tgl_lahir, 0, 1, 'L');..
		//$lokasi = explode(',', $dt_so->denah_lokasi);
		//$latitude = str_replace(' ', '', $lokasi[0]);
		//$longitude = str_replace(' ', '', $lokasi[1]);
		//$qr_generate = "maps.google.com/local?q=$latitude,$longitude"; //data yang akan di jadikan QR CODE
		// $pdf->Cell(95, 5, 'Alamat Domisili : '.$dt_so->alamat, 0, 1, 'L'); 
		//$pdf->Link(95, 5, 'Lokasi : '.$qr_generate, 0, 1, 'L');
		//$pdf->Cell(14, 5, 'Lokasi : ', 0, 0, 'L');
		// Begin with regular font
		$pdf->SetTextColor(0, 0, 255);
		//$pdf->Write(5, "$qr_generate","$qr_generate",1);
		//	  $pdf->Cell(20, 5, '', 0, 1, 'L');
		$pdf->SetTextColor(0, 0, 0);
		//$pdf->Cell(20,5 ,'','','','',false, "$qr_generate"); 
		//$html = '<a href="'.$qr_generate.'">$qr_generate</a>';
		//$pdf->WriteHTML($html);
		//$pdf->Link(90,10,10,10, $qr_generate);
		// $pdf->Cell(95, 5, 'Kelurahan : '.$kelurahan, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kelurahan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $kelurahan, 0, 0, 'L');

		//$qr_generate = "maps.google.com/local?q=$latitude,$longitude"; //data yang akan di jadikan QR CODE

		//$pdf->Cell(20, 5, 'QRCODE : ', 0, 1, 'L');

		//$pdf->Image(site_url().'/dealer/sales_order/qrcode_lokasi?id='.$latitude.'&id2='.$longitude, 10,10,30,30, 'png');
		//$qr_generate = "maps.google.com/local?q=11111111,111111111"; //data yang akan di jadikan QR CODE
		//	$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=55x55",170,49,40,0,'PNG');
		//$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77",164,36,28,0,'PNG');
		// $pdf->Image($this->ciqrcode->generate($params),10,10,30,30);
		//$pdf->Cell(95, 5, 'Kecamatan : '.$kecamatan, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kecamatan', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $kecamatan, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Kota/Kabupaten', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $kabupaten, 0, 0, 'L');
		$pdf->Cell(31, 5, 'Provinsi', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $provinsi, 0, 1, 'L');
		//$pdf->Cell(95, 5, '', 0, 0, 'L');$pdf->Cell(34, 5, 'Harga', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(30, 5, 'Rp. '.$this->mata_uang($dt_so->harga), 0, 1, 'R');
		//$pdf->Cell(34, 5, 'PPN', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(30, 5, 'Rp. '.$this->mata_uang($dt_so->ppn), 0, 1, 'R');
		$pdf->Cell(31, 5, 'Tgl Berdiri Prsh', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->tgl_berdiri, 0, 0, 'L');
		$pdf->Cell(31, 5, 'No. HP', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, $dt_so->no_hp, 0, 1, 'L');
		$pdf->Cell(31, 5, 'Pengambil BPKB', 0, 0, 'L');
		$pdf->Cell(2, 5, ':', 0, 0, 'L');
		$pdf->Cell(62, 5, "", 0, 0, 'L');
		//$pdf->Cell(34, 5, 'Harga Off The Road', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(30, 5, 'Rp. '.$this->mata_uang($dt_so->harga_off_road), 0, 1, 'R');
		// $pdf->Cell(95, 5, 'Kota/Kabupaten : '.$kabupaten, 0, 1, 'L');
		//  $pdf->Cell(95, 5, 'No. HP : '.$dt_so->no_hp, 0, 1, 'L');
		//$pdf->Cell(95, 5, 'No. Telp : '.$dt_so->no_telp, 0, 1, 'L');
		//  $kelompok_harga = $this->db->query("SELECT * FROM ms_kelompok_harga WHERE kelompok_harga='$dt_so->tipe_customer'")->row()->id_kelompok_harga;
		//  $off_the_road = $this->db->query("SELECT * FROM ms_kelompok_md WHERE id_kelompok_harga='$kelompok_harga' AND id_item = '$dt_so->id_item'")->row();
		// $pdf->Cell(95, 5, 'Kota/Kabupaten : '.$kabupaten, 0, 0, 'L'); $pdf->Cell(95, 5, 'Harga : '.$off_the_road->harga_jual, 0, 1, 'L');
		//$ppn = $off_the_road->harga_jual * 0.1;
		//  $pdf->Cell(95, 5, 'No. HP : '.$dt_so->no_hp, 0, 0, 'L'); $pdf->Cell(95, 5, 'PPN : '.$ppn, 0, 1, 'L');
		//$harga_off_the_road = $off_the_road->harga_jual + $ppn;
		//$pdf->Cell(95, 5, 'No. Telp : '.$dt_so->no_telp, 0, 0, 'L');$pdf->Cell(95, 5, 'Harga Off The Road : '.$harga_off_the_road, 0, 1, 'L');
		//$pdf->Cell(95, 5, '', 0, 0, 'L'); 
		//$pdf->Cell(34, 5, 'Biaya BBN', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(30, 5, 'Rp. '.$this->mata_uang($dt_so->biaya_bbn), 0, 1, 'R');
		$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->SetFont('ARIAL', 'B', 10);
		$pdf->Cell(190, 7, 'Data Kendaraan', 1, 1, 'C');

		$get_tipe 	= $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin	 		
	 		INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
	 		INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE id_sales_order_gc = '$id'
	 		GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
		$i = 1;
		$total = 0;
		$total_harga = 0;
		foreach ($get_tipe->result() as $s) {
			$pdf->SetFont('ARIAL', '', 10);
			$harga = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE tr_spk_gc_detail.no_spk_gc = '$s->no_spk_gc' AND tr_spk_gc_detail.id_tipe_kendaraan = '$s->id_tipe_kendaraan' AND tr_spk_gc_detail.id_warna = '$s->id_warna' GROUP BY id_tipe_kendaraan")->row();
			$program = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$s->no_spk_gc'")->row();
			$ta = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi = '$s->no_mesin'");
			$tahun_rakit = "";
			if ($ta->num_rows() > 0) {
				$tahun_rakit = $ta->row()->tahun_produksi;
			}
			$pdf->Cell(31, 5, 'Tipe', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $s->tipe_ahm, 0, 0, 'L');
			$pdf->Cell(31, 5, 'Harga', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($harga->harga), 0, 1, 'L');
			$pdf->Cell(31, 5, 'Warna', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $s->warna, 0, 0, 'L');
			$pdf->Cell(31, 5, 'PPN', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang(0), 0, 1, 'L');
			$pdf->Cell(31, 5, 'Tahun Rakitan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $tahun_rakit, 0, 0, 'L');
			$pdf->Cell(31, 5, 'Harga Off The Road', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($harga->harga + $harga->nilai_voucher), 0, 1, 'L');
			$pdf->Cell(31, 5, 'Jumlah', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $harga->qty . " Unit", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Biaya Surat', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang(0), 0, 1, 'L');
			$pdf->Cell(31, 5, '', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($harga->biaya_bbn), 0, 0, 'L');
			$pdf->Cell(31, 5, 'Harga On The Road', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($harga->harga), 0, 1, 'L');
			$pdf->Cell(31, 5, '', 0, 0, 'L');
			$pdf->Cell(2, 5, '', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Program', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $program->id_program, 0, 1, 'L');
			$pdf->Cell(31, 5, '', 0, 0, 'L');
			$pdf->Cell(2, 5, '', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Voucher', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($harga->nilai_voucher), 0, 1, 'L');
			$pdf->Cell(31, 5, '', 0, 0, 'L');
			$pdf->Cell(2, 5, '', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Harga Akhir', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($tot = $harga->nilai_voucher + $harga->biaya_bbn + $harga->harga + $harga->voucher_tambahan), 0, 1, 'L');
			$total += $tot;
			$total_harga += $harga->harga;

			$pdf->SetFont('ARIAL', 'B', 8);
			$pdf->Cell(10, 5, 'No', 1, 0);
			$pdf->Cell(35, 5, 'Tipe', 1, 0);
			$pdf->Cell(35, 5, 'Warna', 1, 0);
			$pdf->Cell(35, 5, 'No Mesin', 1, 0);
			$pdf->Cell(35, 5, 'No Rangka', 1, 0);
			$pdf->Cell(40, 5, 'Nama STNK', 1, 1);
			$pdf->SetFont('times', '', 8);
			$get_nosin 	= $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
		  WHERE id_sales_order_gc = '$id' AND tr_scan_barcode.tipe_motor = '$s->id_tipe_kendaraan'");
			$i = 1;
			foreach ($get_nosin->result() as $r) {
				$cek_pik = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_item 
	          ON tr_scan_barcode.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
	          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
	          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$r->no_mesin'");
				if ($cek_pik->num_rows() > 0) {
					$cek_pik = $cek_pik->row();
					$pdf->Cell(10, 5, $i, 1, 0);
					$pdf->Cell(35, 5, $cek_pik->tipe_ahm, 1, 0);
					$pdf->Cell(35, 5, $cek_pik->warna, 1, 0);
					$pdf->Cell(35, 5, strtoupper($cek_pik->no_mesin), 1, 0);
					$pdf->Cell(35, 5, strtoupper($cek_pik->no_rangka), 1, 0);
					$pdf->Cell(40, 5, $r->nama_stnk, 1, 1);
				}
				$i++;
			}
			$pdf->Cell(2, 5, '', 5, 10);
		}
		$pdf->Cell(2, 5, '', 5, 10);
		$pdf->SetFont('ARIAL', 'B', 11);
		$pdf->Cell(190, 7, 'Sistem Pembelian', 1, 1, 'C');
		//if ($dt_so->uang_muka == 0 or $dt_so->uang_muka == '') {
		$yy = 152;
		if ($dt_so->jenis_beli == 'Cash') {
			$yy = 178;
			// Jika Jenis Pembelian Cash
			$pdf->Cell(190, 7, 'Cash', 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(95, 3, '', 0, 1, 'L');
			$pdf->Cell(31, 5, 'Jenis', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $dt_so->on_road_gc, 0, 0, 'L');
			$pdf->Cell(31, 5, 'Jumlah Harga', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($total_harga), 0, 1, 'L');
			$pdf->Cell(31, 5, 'Program Khusus', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Total Bayar', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $this->mata_uang($total), 0, 1, 'L');
			$pdf->Cell(95, 3, '', 0, 1, 'L');
		} elseif ($dt_so->jenis_beli == 'Kredit') {
			$yy = 298;
			// Jika Jenis Pembelian Kredit
			$pdf->SetFont('ARIAL', 'B', 11);
			$pdf->Cell(190, 7, 'Kredit', 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(95, 3, '', 0, 1, 'L');
			$pdf->Cell(31, 5, 'Leasing/FINCO', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $finance_co, 0, 0, 'L');
			$kerja				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_so->id_pekerjaan'");
			if ($kerja->num_rows() > 0) {
				$tr = $kerja->row();
				$pekerjaan = $tr->pekerjaan;
			} else {
				$pekerjaan = "-";
			}
			$pdf->Cell(31, 5, 'Pekerjaan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, $pekerjaan, 0, 1, 'L');
			$pdf->Cell(31, 5, 'Uang Muka/DP', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 1, 'L');
			$pdf->Cell(31, 5, 'Voucher Tambahan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Status Rumah', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 1, 'L');
			$pdf->Cell(31, 5, 'Angsuran', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Lama Kerja', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 1, 'L');
			$pdf->Cell(31, 5, 'Tenor', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 0, 'L');
			$pdf->Cell(31, 5, 'Total Penghasilan', 0, 0, 'L');
			$pdf->Cell(2, 5, ':', 0, 0, 'L');
			$pdf->Cell(62, 5, "", 0, 1, 'L');
			// $pdf->SetFont('ARIAL','B',8);

			// $pdf->Cell(10, 5, 'No', 1, 0);
			// $pdf->Cell(25, 5, 'Tipe-Warna', 1, 0);
			// $pdf->Cell(30, 5, 'Qty', 1, 0);
			// $pdf->Cell(30, 5, 'Harga', 1, 0);
			// $pdf->Cell(25, 5, 'Biaya BBN', 1, 0);
			// $pdf->Cell(35, 5, 'Nilai Voucher', 1, 0);
			// $pdf->Cell(35, 5, 'Vocuher Tambahan', 1, 0);		  
			// $pdf->Cell(35, 5, 'Total', 1, 0);
			// $pdf->SetFont('times','',8);		  		  
			//   $total=0;$no2=1; 
			//   $detail = $this->db->query("SELECT * FROM tr_spk_gc_detail LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			//     LEFT JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna WHERE no_spk_gc = '$dt_so->no_spk_gc'");
			//   foreach ($detail->result() as $rs){                            
			//   $pdf->Cell(10, 5, $no2, 1, 0);
			//   $pdf->Cell(25, 5, $rs->tipe_ahm, 1, 0);
			//   $pdf->Cell(30, 5, $rs->qty, 1, 0);
			//   $pdf->Cell(30, 5, $rs->harga, 1, 0);    
			//   $pdf->Cell(25, 5, $rs->biaya_bbn, 1, 0);    
			//   $pdf->Cell(35, 5, $rs->nilai_voucher, 1, 0);
			//   $pdf->Cell(35, 5, $rs->voucher_tambahan, 1, 0);	    
			//   $pdf->Cell(35, 5, $rs->total, 1, 1);	    
			//    $no2++;
			//    $total += $rs->total;		    		  
			// }
		}

		//$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->Cell(2, 5, '', 5, 10);
		$pdf->SetFont('ARIAL', 'B', 11);
		$pdf->Cell(190, 7, 'Syarat dan Ketentuan', 1, 1, 'C');
		$pdf->SetFont('ARIAL', '', 10);
		$pdf->Cell(95, 3, '', 0, 1, 'L');
		$pdf->Cell(4, 5, '1.', 0, 0, 'L');
		$pdf->WriteHTML('Harga yang tercantum dalam Sales Order <b>telah mengikat</b>. <br>');
		$pdf->Cell(4, 5, '2.', 0, 0, 'L');
		$pdf->WriteHTML("Surat Pesanan ini dianggap SAH apabila ditandatangani oleh Pemesan, Sales Person, dan Kepala Cabang. <br>");
		$pdf->Cell(4, 5, '3.', 0, 0, 'L');
		// $pdf->MultiCell(186,5,"Pembayaran dengan Cek/Bilyet Giro/Transfer harus diatasnamakan $nama_rek dan dianggap sah apabila telah diterima di rekening $norek.",0,"L");
		$pdf->MultiCell(86, 5, "Pembayaran dengan Cek/ Bilyet Giro/ Transfer dianggap sah apabila telah diterima di rekening:  ", 0, "L");

		$norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer WHERE id_dealer = '$dt_so->id_dealer' ")->row()->id_norek_dealer;
		$detail_norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer_detail WHERE id_norek_dealer = '$norek_dealer' LIMIT 0,2");
		$x = 1;
		$cek = 0;
		$xx = 18;
		$count = 1;
		$count_isi = $detail_norek_dealer->num_rows();
		foreach ($detail_norek_dealer->result() as $key => $norek) {
			if ($count <= 2) {

				$bank = $this->db->query("SELECT * FROM ms_bank WHERE id_bank = '$norek->id_bank'")->row();
				$pdf->SetXY($xx, $yy);
				$pdf->MultiCell(70, 5, " Atas Nama \t\t\t\t: <b>$norek->nama_rek</b> \n Nama Bank \t\t\t: $bank->bank \n No Rekening \t: $norek->no_rek", 0, 'L');
				$pdf->WriteHTML("Atas Nama\t\t\t\t\t\t\t: <b>$norek->nama_rek</b><br>
	  	 	Nama Bank  \t\t\t\t: <b>$bank->bank</b><br>
	  	 	 No Rekening : <b> $norek->no_rek</b>
	  	 	");
				$pdf->WriteHTML("Atas Nama\t\t\t\t\t\t\t: <b>$norek->nama_rek</b><br>");
				$pdf->SetX($xx);
				$pdf->WriteHTML("Nama Bank\t\t\t\t\t\t: $bank->bank<br>");
				$pdf->SetX($xx);
				$pdf->WriteHTML("No Rekening\t\t\t\t: $norek->no_rek<br>");
				if ($x < $count_isi) {
					$xx += 70;
					$pdf->SetXY($xx, $yy);
					$pdf->MultiCell(30, 5, "\n Atau \n", 0, 'L');
					$xx += 30;
				}
			}
			$count++;
			$x++;
		}
		$pdf->Ln(2);
		// $norek = implode(" atau ", $norek->no_rek);
		// $nama_rek = implode(" atau ", $nama_rek);
		$pdf->Cell(4, 5, '4.', 0, 0, 'L');
		$pdf->WriteHTML("Pembayaran Tunai dianggap <b>sah</b> apabila telah diterbitkan kwitansi oleh <b>$dt_so->nama_dealer.</b> <br>");
		$pdf->Cell(4, 5, '5.', 0, 0, 'L');
		$pdf->Multicell(186, 5, "Pengurusan STNK & BPKB dilaksanakan setelah 100% harga kendaraan lunas.", 0, "L");
		$pdf->Cell(4, 5, '6.', 0, 0, 'L');
		$pdf->WriteHTML("Nama pada Faktur STNK (BPKB) yang tercantum dalam Sales Order ini  <b>tidak dapat diubah.</b><br>");
		$pdf->Cell(4, 5, '7.', 0, 0, 'L');
		$pdf->WriteHTML("Sepeda motor yang sudah dibeli <b>tidak dapat dikembalikan</b> atau <b>ditukar.</b> <br>");

		$pdf->Cell(4, 5, '', 0, 1, 'L');
		$pdf->Cell(47.5, 5, 'Pemesan', 0, 0, 'C');
		$pdf->Cell(47.5, 5, 'Sales Person', 0, 0, 'C');
		$pdf->Cell(47.5, 5, 'Kepala Cabang', 0, 0, 'C');
		if ($dt_so->jenis_beli != 'Kredit') {
			$pdf->Cell(47.5, 5, 'Pengambil BPKB', 0, 1, 'C');
		}
		$pdf->Cell(4, 15, '', 0, 1, 'C');
		$pdf->Cell(47.5, 5, '( ' . $dt_so->nama_penanggung_jawab . ' )', 0, 0, 'C');
		$pdf->Cell(47.5, 5, '( ' . $dt_so->nama_npwp . ' )', 0, 0, 'C');
		$pdf->Cell(47.5, 5, '( ' . $dt_so->pic . ' )', 0, 0, 'C');
		if ($dt_so->jenis_beli != 'Kredit') {
			$pdf->Cell(47.5, 5, '(                          )', 0, 1, 'C');
		}
		$estimasi = $this->db->query("SELECT * FROM ms_estimasi_stnk_bpkb_cash")->row();
		$tgl = date('Y-m-d');
		$stnk = date("Y-m-d", strtotime("+" . $estimasi->estimasi_stnk . " days", strtotime($tgl)));
		$bpkb = date("Y-m-d", strtotime("+" . $estimasi->estimasi_bpkb_cash . " days", strtotime($tgl)));
		$day = 1;
		$kirim = date("Y-m-d", strtotime("+" . $day . " days", strtotime($tgl)));
		$pdf->Ln(5);
		//  $pdf->Cell(43, 5, 'Tanggal Estimasi STNK', 0, 0, 'L');$pdf->Cell(50, 5, ':  '.$stnk, 0, 1, 'L');
		//  if ($dt_so->jenis_beli =='Cash'){
		//  	$pdf->Cell(43, 5, 'Tanggal Estimasi BPKB', 0, 0, 'L');$pdf->Cell(50, 5, ':  '.$bpkb, 0, 1, 'L');
		// }
		//  $pdf->Cell(43, 5, 'Tanggal Pengiriman Unit', 0, 0, 'L');$pdf->Cell(50, 5, ':  '.date("Y-m-d"), 0, 1, 'L');
		$pdf->setY(285);
		$pdf->SetFont('ARIAL', 'I', 9);
		$pdf->Cell(43, 5, 'Cetakan Ke-' . $dt_so->cetak_so_ke, 0, 0, 'L');
		//	  $pdf->Line(11, 31, 200, 31);	   	
		//ob_end_clean();
		$pdf->Output();
	}
	// public function cek_no_invoice()
	// {
	// 	 $tgl 						= date("d");
	// 	 $cek_tgl					= date("Y-m");
	// 	 $th 						= date("Y");
	// 	 $bln 						= date("m/d");	
	// 	 $id_dealer = $this->m_admin->cari_dealer();
	// 	 $get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer' ");	
	// 	 if ($get_dealer->num_rows() > 0) {
	// 			$get_dealer = $get_dealer->row()->kode_dealer_md;
	// 			}else{
	// 				$get_dealer ='';
	// 			}
	// 	 $pr_num = $this->db->query("SELECT *,mid(tgl_cetak_invoice2,6,2)as bln FROM tr_sales_order WHERE LEFT(tgl_cetak_invoice2,7) = '$cek_tgl' ORDER BY tgl_cetak_invoice2 DESC LIMIT 0,1");						
	// 	 if($pr_num->num_rows()>0){


	// 	 	$row 	= $pr_num->row();
	// 	 	$id = explode('/', $row->no_invoice);
	// 	 	if (count($id) > 1) {
	// 	 		if ($bln == $row->bln) {
	// 	 			$isi 	= $th.'/'.$bln.'/'.$get_dealer.'/INU/'.sprintf("%'.05d",$id[4]+1);
	// 	 		}else{
	// 		 		$isi = $th.'/'.$bln.'/'.$get_dealer.'/INU/00001';
	// 	 		}
	// 	 	}else{
	// 	 		$isi = $th.'/'.$bln.'/'.$get_dealer.'/INU/00001';
	// 	 	}				
	// 	 	$kode = $isi;
	// 	 }else{
	// 	 		$kode = $th.'/'.$bln.'/'.$get_dealer.'/INU/00001';
	// 	 } 			
	// 	 return $kode;
	// }

	public function cek_no_invoice()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('Y');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$id_sumber = $dealer->kode_dealer_md;

		$get_data  = $this->db->query("SELECT * FROM tr_sales_order 
			WHERE LEFT(tgl_cetak_invoice2,4) = '$th' AND no_invoice!='' AND id_dealer='$id_dealer'
			ORDER BY tgl_cetak_invoice2 
			DESC LIMIT 0,1
			");
		if ($get_data->num_rows() > 0) {
			$row        = $get_data->row();
			$no_invoice = substr($row->no_invoice, -5);
			$new_kode   = $th . '/' . $id_sumber . '/INU/' . sprintf("%'.06d", $no_invoice + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_sales_order', ['no_invoice' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -5);
					$new_kode = $th . '/' . $id_sumber . '/INU/' . sprintf("%'.06d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = $th . '/' . $id_sumber . '/INU/000001';
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_sales_order', ['no_invoice' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -5);
					$new_kode = $th . '/' . $id_sumber . '/INU/' . sprintf("%'.06d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		}
		return strtoupper($new_kode);
	}
	public function tesinv()
	{
		echo $this->cek_no_invoice();
	}
	public function cetak_invoice()
	{
		$print = $this->input->post('print');
		// $no_invoice = $data['no_invoice'] = $this->cek_no_invoice();
		if ($print == 'ya') {
			$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
			$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$jam    = gmdate("H:i:s", time() + 60 * 60 * 7);

			$tanggal  = date('Y-m-d');

			$login_id = $this->session->userdata('id_user');

			$tabel    = $this->tables;

			$pk       = $this->pk;

			$id       = $this->input->post('id_sales_order');

			$cek_so = $this->db->query("SELECT * FROM tr_sales_order
											inner JOIN tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
										 	WHERE id_sales_order='$id'");
			if ($cek_so->num_rows() > 0) {
				$cek_so = $cek_so->row();

				if (is_null($cek_so->tgl_cetak_invoice)) {
					$data2['tgl_create_ssu']    = $waktu;
					$data2['create_ssu_by']     = $login_id;
					$data2['tgl_cetak_invoice'] = $tgl;
					$data2['jam_cetak_invoice'] = $jam;
				}
				if (isset($data2)) {
					$this->m_admin->update("tr_sales_order", $data2, "id_sales_order", $id);
				}


				$id_program_md = $cek_so->program_umum;
				if ($id_program_md != null or $id_program_md != '') {
					$cek_sp = $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md='$id_program_md' AND '$tanggal' BETWEEN periode_awal AND periode_akhir ");
					if ($cek_sp->num_rows() > 0) {
						$cek_sp = $cek_sp->num_rows();
					} else {
						$cek_sp = 1;
					}
				} else {
					$cek_sp = 1;
				}
			} else {
				$cek_sp = 1;
			}
			if ($cek_sp > 0) {
				$no_invoice = $this->cek_no_invoice();
				$cek_no_invoice = $this->db->query("SELECT no_invoice FROM tr_sales_order WHERE no_invoice = '$no_invoice'");
				if ($cek_no_invoice->num_rows() == 0) {
					$cek_invoice_so = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$id'");
					if ($cek_invoice_so->num_rows() == 1) {
						$cek = $cek_invoice_so->row();
						if ($cek->cetak_invoice_ke == 0) {
							$data['status_cetak']       = 'cetak_invoice';
							$data['status_so']          = 'so_invoice';
							$data['no_invoice']         = $no_invoice;
							$data['updated_at']         = $waktu;
							$data['updated_by']         = $login_id;
							$data['tgl_cetak_invoice2'] = $waktu;
							$data['cetak_invoice_by']   = $login_id;
							$data['cetak_invoice_ke']   = 1;
						} else {
							$cetak_invoice_ke         = $cek->cetak_invoice_ke;
							$data['cetak_invoice_ke'] = $cetak_invoice_ke + 1;
						}
						$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
					}
					$so = $this->db->query("SELECT tr_sales_order.*,ms_dealer.nama_dealer,ms_dealer.alamat as alamat_dealer,ms_dealer.no_telp as no_telp_dealer,ms_dealer.id_kelurahan as kelurahan_dealer, tr_scan_barcode.id_item, tr_spk.*, ms_tipe_kendaraan.tipe_ahm, ms_warna.warna,ms_finance_company.finance_company FROM tr_sales_order 
							left join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
							left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk
							left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
							left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
							left join ms_dealer on tr_sales_order.id_dealer = ms_dealer.id_dealer
							left join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company WHERE id_sales_order = '$id' ")->row();
					$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
			  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
			  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
			  		WHERE ms_kelurahan.id_kelurahan = '$so->id_kelurahan'")->row();
					$kelurahan 		= $dt_kel->kelurahan;
					$id_kecamatan = $dt_kel->id_kecamatan;
					$kecamatan 		= $dt_kel->kecamatan;
					$id_kabupaten = $dt_kel->id_kabupaten;
					$kabupaten  	= $dt_kel->kabupaten;
					$id_provinsi  = $dt_kel->id_provinsi;
					$provinsi  		= $dt_kel->provinsi;

					$dt_kel_dealer				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->kelurahan_dealer'")->row();
					$kelurahan_dealer 		= $dt_kel_dealer->kelurahan;
					$id_kecamatan_dealer = $dt_kel_dealer->id_kecamatan;
					$dt_kec_dealer				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan_dealer'")->row();
					$kecamatan_dealer 		= $dt_kec_dealer->kecamatan;
					$id_kabupaten_dealer = $dt_kec_dealer->id_kabupaten;
					$dt_kab_dealer				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten_dealer'")->row();
					$kabupaten_dealer  	= $dt_kab_dealer->kabupaten;
					$pdf = new FPDF('P', 'mm', array(215.9, 297));
					$pdf->SetMargins(8, 8, 8);
					$pdf->AddPage();
					$pdf->SetAutoPageBreak(false);
					// head	  
					$pdf->SetFont('ARIAL', '', 9);
					$pdf->Cell(190, 4, $so->nama_dealer, 0, 1, 'L');
					$pdf->Cell(190, 4, $so->alamat_dealer, 0, 1, 'L');
					$pdf->Ln(4);
					$pdf->Cell(190, 4, $kabupaten_dealer, 0, 1, 'L');
					$pdf->Cell(190, 4, $so->no_telp_dealer, 0, 1, 'L');
					$pdf->SetFont('ARIAL', 'B', 12);
					$pdf->Cell(200, 7, 'INVOICE', 0, 1, 'C');

					if ($so->the_road == 'Off The Road') {
						$so->biaya_bbn = 0;
					}

					if ($so->jenis_beli == 'Cash') {
						$pdf->SetFont('ARIAL', '', 9);
						$pdf->Cell(30, 5, 'Nomor Invoice', 0, 0, 'L');
						$pdf->Cell(5, 5, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 5, $so->no_invoice, 0, 0, 'L');
						$pdf->Cell(30, 5, 'Customer', 0, 0, 'L');
						$pdf->Cell(5, 5, ' : ', 0, 0, 'L');
						// $pdf->Cell(65, 5, $so->nama_konsumen." ($so->id_customer)", 0, 1, 'L');
						$pdf->Cell(65, 5, $so->nama_konsumen, 0, 1, 'L');
						$pdf->Cell(30, 5, 'Tgl Invoice', 0, 0, 'L');
						$pdf->Cell(5, 5, ' : ', 0, 0, 'L');
						$tgl_cetak_invoice = date('d-m-Y', strtotime($so->tgl_cetak_invoice2));
						$pdf->Cell(65, 5, $tgl_cetak_invoice, 0, 0, 'L');
						$pdf->Cell(30, 5, 'Alamat Pembeli', 0, 0, 'L');
						$pdf->Cell(5, 5, ' : ', 0, 1, 'L');
						$pdf->Cell(30, 5, 'Nomor SPK', 0, 0, 'L');
						$pdf->Cell(5, 5, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 5, $so->no_spk, 0, 1, 'L');
						$pdf->Cell(30, 5, 'Nomor SO', 0, 0, 'L');
						$pdf->Cell(5, 5, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 5, $so->id_sales_order, 0, 1, 'L');
						$pdf->setXY(143, 40);
						$pdf->MultiCell(65, 5, "$so->alamat \n $kelurahan \n $kecamatan \n $kabupaten", 0, 1);
						$pdf->setXY(8, 63);
						$pdf->Line(8, 62, 208, 62);
						$pdf->Cell(6, 4, 'No.', 0, 0, 'C');
						$pdf->Cell(20, 4, 'KODE', 0, 0, 'C');
						$pdf->Cell(59, 4, 'KETERANGAN', 0, 0, 'C');
						$pdf->Cell(30, 4, 'HARGA', 0, 0, 'C');
						$pdf->Cell(30, 4, 'DISCOUNT', 0, 0, 'C');
						$pdf->Cell(25, 4, 'QTY', 0, 0, 'C');
						$pdf->Cell(30, 4, 'NILAI', 0, 1, 'C');
						$pdf->Line(8, 67, 208, 67);
						$pdf->Ln(1);
						$pdf->Cell(6, 4, '1', 0, 0, 'C');
						$pdf->Cell(20, 4, $so->id_item, 0, 0, 'C');
						$pdf->Cell(59, 4, $so->tipe_ahm . '-' . $so->warna, 0, 0, 'C');
						// $harga =round($so->harga_off_road/1.1);
						// $discount = round(($so->voucher_1 + $so->voucher_tambahan_1)/1.1)+$so->diskon;
						$return  = $this->m_admin->detail_individu($so->no_spk);
						//$harga = $return['harga_off_road'];
						$discount_2 = $return['voucher_tambahan'] + $return['voucher'] + $return['voucher2'];
						$discount = round($discount_2 / 1.1);
						$harga = $return['harga'] + $discount;
						$nilai = $harga - $discount;
						if (is_numeric($nilai) and $nilai != 0 and $nilai != "") $nilai_rp = $this->mata_uang($nilai);
						else $nilai_rp = "";
						$pdf->Cell(30, 4, 'Rp. ' . number_format($harga, 0, ',', '.'), 0, 0, 'C');
						$pdf->Cell(30, 5, 'Rp. ' . number_format($discount, 0, ',', '.'), 0, 0, 'C');
						$pdf->Cell(25, 5, '1', 0, 0, 'C');
						$pdf->Cell(25, 5, 'Rp. ' . $nilai_rp, 0, 1, 'R');
						$pdf->Ln(9);
						$pdf->Line(8, 78, 208, 78);
						$program = $so->chk_program_umum == 1 ? $so->program_umum : '';
						// $pdf->Cell(123, 5, 'Program : '.$program, 0, 1, 'L');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Dasar Pengenaan Pajak (DPP)', 0, 0, 'L');
						$pdf->Cell(30, 5, 'Rp. ' . $nilai_rp, 0, 1, 'R');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Pajak Pertambahan Nilai (PPN)', 0, 0, 'L');
						$ppn = round($nilai / 10);
						// $pdf->Line(160,92,206,92);
						$pdf->Cell(30, 5, 'Rp. ' . number_format($ppn, 0, ',', '.'), "B", 1, 'R');
						// $jml = floor($nilai + $ppn);
						$jml = $nilai + $ppn;

						if ($jml % 10 == 9) {
							$jml += 1;
							$jml = floor($jml);
						} else if ($jml % 10 == 1) {
							$jml -= 1;
						}

						$pdf->Cell(195, 7, 'Rp. ' . number_format($jml, 0, ',', '.'), 0, 1, 'R');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Biaya Balik Nama', 0, 0, 'L');
						$pdf->Cell(30, 5, 'Rp. ' . number_format($so->biaya_bbn, 0, ',', '.'), "B", 1, 'R');
						$pdf->setX(50);
						// $pdf->Cell(123, 5, 'Diskon', 0, 0, 'L');
						// $pdf->Cell(30, 5, number_format($so->diskon, 0, ',', '.'), 0, 1, 'R');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Total', 0, 0, 'L');
						// $ppn = round($nilai / 10);
						// $pdf->Line(160,104,206,104);
						$tot = ($jml + $so->biaya_bbn);
						$pdf->Cell(30, 7, 'Rp. ' . number_format($tot, 0, ',', '.'), 0, 1, 'R');
						$tot_terbilang = ucwords(number_to_words($tot));
						$pdf->setY(113);
						$pdf->Cell(130, 7, 'Terbilang : ', 0, 1, 'L');
						$pdf->Cell(5, 7, '', 0, 0, 'L');
						$tot_terbilang = str_replace('  ', ' ', $tot_terbilang);
						$pdf->Cell(130, 7, $tot_terbilang . ' Rupiah', 0, 1, 'L');
					} elseif ($so->jenis_beli == 'Kredit') {
						$pdf->SetFont('ARIAL', '', 9);
						$pdf->Cell(30, 4, 'Nomor Invoice', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 4, $so->no_invoice, 0, 0, 'L');
						$pdf->Cell(30, 4, 'Customer', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						// $pdf->Cell(65, 4, $so->nama_konsumen." ($so->id_customer)", 0, 1, 'L');
						$pdf->Cell(65, 4, $so->nama_konsumen, 0, 1, 'L');
						$tgl_cetak_invoice = date('d-m-Y', strtotime($so->tgl_cetak_invoice2));
						$pdf->Cell(30, 4, 'Tgl Invoice', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 4, $tgl_cetak_invoice, 0, 0, 'L');
						$pdf->Cell(30, 4, 'Alamat Pembeli', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 1, 'L');
						$pdf->Cell(30, 4, 'Nomor SPK', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 4, $so->no_spk, 0, 1, 'L');
						$pdf->Cell(30, 4, 'Nomor SO', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 4, $so->id_sales_order, 0, 1, 'L');
						$pdf->Cell(30, 4, 'QQ', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						// $pdf->Cell(65, 4, $so->finance_company." ($so->id_finance_company)", 0, 1, 'L');
						$pdf->Cell(65, 4, $so->finance_company, 0, 1, 'L');
						$pdf->Cell(30, 4, 'Pembayaran', 0, 0, 'L');
						$pdf->Cell(5, 4, ' : ', 0, 0, 'L');
						$pdf->Cell(65, 4, $so->jenis_beli, 0, 1, 'L');
						$pdf->setXY(143, 40);
						$pdf->MultiCell(65, 4, "$so->alamat \n $kelurahan \n $kecamatan \n $kabupaten", 0, 1);
						$pdf->setXY(8, 63);
						$pdf->Line(8, 62, 208, 62);
						$pdf->Cell(6, 4, 'No.', 0, 0, 'C');
						$pdf->Cell(20, 4, 'KODE', 0, 0, 'C');
						$pdf->Cell(59, 4, 'KETERANGAN', 0, 0, 'C');
						$pdf->Cell(30, 4, 'HARGA', 0, 0, 'C');
						$pdf->Cell(30, 4, 'DISCOUNT', 0, 0, 'C');
						$pdf->Cell(25, 4, 'QTY', 0, 0, 'C');
						$pdf->Cell(30, 4, 'NILAI', 0, 1, 'C');
						$pdf->Line(8, 67, 208, 67);
						$pdf->Ln(1);
						$pdf->Cell(6, 4, '1', 0, 0, 'C');
						$pdf->Cell(20, 4, $so->id_item, 0, 0, 'C');
						$pdf->Cell(59, 4, $so->tipe_ahm . '-' . $so->warna, 0, 0, 'C');
						// $harga = round($so->harga_off_road / 1.1);

						$return  = $this->m_admin->detail_individu($so->no_spk);
						$discount_2 = $return['voucher_tambahan'] + $return['voucher'] + $return['voucher2'];
						$discount = round($discount_2 / 1.1);
						$harga = $return['harga'] + $discount;
						$nilai = round($harga) - $discount;
						// $nilai = round($harga - $discount);
						if (is_numeric($nilai) and $nilai != 0 and $nilai != "") $nilai_rp = $this->mata_uang($nilai);
						else $nilai_rp = "";
						$pdf->Cell(30, 4, 'Rp. ' . number_format($harga, 0, ',', '.'), 0, 0, 'C');
						$pdf->Cell(30, 5, 'Rp. ' . number_format($discount, 0, ',', '.'), 0, 0, 'C');
						$pdf->Cell(25, 5, '1', 0, 0, 'C');
						$pdf->Cell(25, 5, 'Rp. ' . $nilai_rp, 0, 1, 'R');
						$pdf->Ln(9);
						$pdf->Line(8, 78, 208, 78);
						$program = $so->chk_program_umum == 1 ? $so->program_umum : '';
						// $pdf->Cell(40, 5, 'Program', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$program, 0, 1, 'L');
						// $pdf->Cell(40, 5, 'Jumlah DP', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$this->mata_uang($so->dp_stor), 0, 1, 'L');
						// $pdf->Cell(40, 5, 'Tenor', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$so->tenor, 0, 1, 'L');
						// $pdf->Cell(40, 5, 'Cicilan', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$this->mata_uang($so->angsuran), 0, 1, 'L');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Dasar Pengenaan Pajak (DPP)', 0, 0, 'L');
						$nilai_round = round($nilai);
						$pdf->Cell(30, 5, 'Rp. ' . $this->mata_uang($nilai_round), 0, 1, 'R');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Pajak Pertambahan Nilai (PPN)', 0, 0, 'L');
						$ppn = round($nilai / 10);
						// $pdf->Line(160,113,206,113);
						$pdf->Cell(30, 5, 'Rp. ' . number_format($ppn, 0, ',', '.'), "B", 1, 'R');
						$jml = $nilai + $ppn;

						if ($jml % 10 == 9) {
							$jml += 1;
						} else if ($jml % 10 == 1) {
							$jml -= 1;
						}

						$pdf->Cell(195, 7, 'Rp. ' . number_format($jml, 0, ',', '.'), 0, 1, 'R');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Biaya Balik Nama', 0, 0, 'L');
						$pdf->Cell(30, 5, 'Rp. ' . number_format($so->biaya_bbn, 0, ',', '.'), "B", 1, 'R');
						// $pdf->setX(50) ; 
						// $pdf->Cell(123, 5, 'Diskon', 0, 0, 'L');
						// $pdf->Cell(30, 5, number_format($so->diskon, 0, ',', '.'), 0, 1, 'R');
						$pdf->setX(50);
						$pdf->Cell(123, 5, 'Total', 0, 0, 'L');
						// $ppn = round($nilai / 10);
						// $pdf->Line(160,129,206,129);
						$tot = ($jml + $so->biaya_bbn);
						$pdf->Cell(30, 7, 'Rp. ' . number_format($tot, 0, ',', '.'), 0, 1, 'R');
						$tot_terbilang = ucwords(number_to_words($tot));
						$pdf->setY(140);
						$pdf->Cell(130, 7, 'Terbilang : ', 0, 1, 'L');
						$pdf->Cell(5, 7, '', 0, 0, 'L');
						$tot_terbilang = str_replace('  ', ' ', $tot_terbilang);
						$pdf->Cell(130, 7, $tot_terbilang . ' Rupiah', 0, 1, 'L');
						$pdf->SetY(280);
						$pdf->Cell(5, 7, 'Cetakan Ke-' . $so->cetak_invoice_ke, 0, 0, 'L');
					}
					$pdf->Output();
				}
			} else {
				$_SESSION['pesan'] 	= "Duplicate entry for primary key";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
			}
		} else {
			$data['isi']   = $this->page;
			$data['title'] = $this->title;
			$data['set']   = "cetak_invoice";
			$id            = $this->input->get("id");
			$id_dealer     = $this->m_admin->cari_dealer();
			$row           = $this->db->query("SELECT *, 
				LEFT(tr_sales_order.created_at,10) AS tgl_so,
				(SELECT CONCAT(ms_karyawan_dealer.id_flp_md,' | ',nama_lengkap) 
				 FROM tr_prospek 
				 JOIN ms_karyawan_dealer ON ms_karyawan_dealer.id_karyawan_dealer=tr_prospek.id_karyawan_dealer
				 WHERE id_customer=tr_spk.id_customer ORDER BY tr_prospek.created_at DESC LIMIT 1) AS sales, 
				CONCAT(id_customer,' | ',nama_konsumen) AS customer,
				CONCAT (tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_motor,
				CONCAT (tr_spk.id_warna,' | ',warna) AS warna, tr_sales_order.no_mesin,tr_sales_order.no_rangka
				FROM tr_sales_order
				JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
				JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
				JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
				WHERE id_sales_order='$id' AND tr_spk.id_dealer=$id_dealer");
			if ($row->num_rows() > 0) {
				$row = $data['row'] = $row->row();
				$data['no_invoice'] = $row->no_invoice != '' ? $row->no_invoice : $this->cek_no_invoice();
				$this->template($data);
			}
		}
	}
	public function cetak_invoice_gc()
	{
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$jam      = gmdate("H:i:s", time() + 60 * 60 * 7);
		$tanggal  = date('Y-m-d');
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		$data['id'] = $id       = $this->input->get('id');
		$cek_so = $this->db->query("SELECT * FROM tr_sales_order_gc
										inner JOIN tr_spk_gc on tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
									 	WHERE id_sales_order_gc = '$id'");
		if ($cek_so->num_rows() > 0) {

			if (is_null($cek_so->row()->tgl_cetak_invoice)) {
				$data2['tgl_create_ssu']		= $waktu;
				$data2['create_ssu_by']			= $login_id;
				$data2['tgl_cetak_invoice'] = $tgl;
				$data2['jam_cetak_invoice'] = $jam;
			}
			if (isset($data2)) {
				$this->m_admin->update("tr_sales_order_gc", $data2, "id_sales_order_gc", $id);
			}
		}




		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$data['cetak'] = 'cetak_so_gc';

		//$so = $data['dt_so'] = $sql->row();  	  	
		$data['no_invoice'] = $this->cek_no_invoice();
		$data['waktu']    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$html = $this->load->view('dealer/cetak_invoice_so_gc', $data, true);
		$mpdf->WriteHTML($html);
		$output = 'cetak_.pdf';
		$mpdf->Output("$output", 'I');
	}

	public function cetak_cover()
	{
		$pdf = new PDF_HTML('P', 'mm', 'a4');
		//$pdf->SetMargins(8, 8, 8);
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false);
		$id = $this->input->get('id');
		$dt_so = $this->db->query("SELECT tr_sales_order.*,tr_spk.nama_bpkb as nama_bpkb1, tr_sales_order.no_mesin as no_mesinalias, 
	  				tr_spk.*,tr_prospek.*,tr_scan_barcode.id_item,
	  				ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm, ms_warna.warna,tr_scan_barcode.no_rangka, ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_sales_order.no_mesin,ms_karyawan_dealer.nama_lengkap as nama_sales,tr_spk.id_kelurahan,tr_spk.alamat FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			
			LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer on tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE tr_sales_order.id_sales_order = '$id'
			");
		if ($dt_so->num_rows() > 0) {
			$so = $dt_so->row();
			$fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$so->no_mesin'");
			if ($fkb->num_rows() > 0) {
				$fkb = $fkb->row()->nomor_faktur;
			} else {
				$fkb = '';
			}

			$id_kelurahan_tmp = ($so->id_kelurahan_bpkb != '') ? $so->id_kelurahan_bpkb : $so->id_kelurahan;

			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		  		WHERE ms_kelurahan.id_kelurahan = '$id_kelurahan_tmp'")->row();
			$kelurahan 		= $dt_kel->kelurahan;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$kecamatan 		= $dt_kel->kecamatan;
			$id_kabupaten = $dt_kel->id_kabupaten;
			$kabupaten  	= $dt_kel->kabupaten;
			$id_provinsi  = $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;
			$pdf->SetFont('ARIAL', 'B', 13);
			$pdf->Cell(120, 5, 'No Faktur : ' . $fkb, 0, 1, 'L');
			$pdf->Ln(6);
			$pdf->SetFont('ARIAL', 'B', 20);
			$pdf->Cell(190, 5, $so->nama_dealer, 0, 1, 'C');
			$pdf->Ln(9);
			$pdf->SetFont('ARIAL', 'B', 18);
			$pdf->Cell(65, 9, 'NAMA', 0, 0, 'L');
			$pdf->MultiCell(145, 9, ': ' . strtoupper(($so->nama_bpkb != '') ? $so->nama_bpkb : $so->nama_konsumen), 0, 1);
			$pdf->Cell(65, 9, 'ALAMAT', 0, 0, 'L');
			$pdf->Cell(3, 9, ':', 0, 0, 'L');
			$pdf->MultiCell(120, 9, strtoupper(($so->alamat_ktp_bpkb != '') ? $so->alamat_ktp_bpkb : $so->alamat), 0, 1);
			$pdf->Cell(65, 9, 'KELURAHAN', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($kelurahan), 0, 1, 'L');
			$pdf->Cell(65, 9, 'KECAMATAN', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($kecamatan), 0, 1, 'L');
			$pdf->Cell(65, 9, 'KOTA', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($kabupaten), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. HP', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->no_hp), 0, 1, 'L');
			$pdf->Cell(65, 9, 'TYPE', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->tipe_ahm), 0, 1, 'L');
			$pdf->Cell(65, 9, 'DESK AHM', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper(strip_tags($so->deskripsi_ahm)), 0, 1, 'L');
			$pdf->Cell(65, 9, 'WARNA', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->warna), 0, 1, 'L');
			$thn = $this->m_admin->getByID("tr_fkb", "no_mesin_spasi", $so->no_mesin);
			if ($thn->num_rows() > 0) {
				$ty = $thn->row();
				$tahun_p = $ty->tahun_produksi;
			} else {
				$tahun_p = "";
			}
			$pdf->Cell(65, 9, 'THN PRODUKSI', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($tahun_p), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. RANGKA', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->no_rangka), 0, 1, 'L');
			$pdf->Cell(65, 9, 'NO. MESIN', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->no_mesin), 0, 1, 'L');
			$tgl = date('d-m-Y', strtotime($so->tgl_cetak_so));
			$pdf->Cell(65, 9, 'TGL PEMBELIAN', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . $tgl, 0, 1, 'L');
			if ($so->jenis_beli == 'Kredit') {
				$ft = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $so->id_finance_company)->row();
				$fc = "( " . $ft->finance_company . " )";
			} else {
				$fc = "";
			}
			$pdf->Cell(65, 9, 'PEMBAYARAN', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->jenis_beli) . $fc, 0, 1, 'L');
			$pdf->Cell(65, 9, 'SALES PEOPLE', 0, 0, 'L');
			$pdf->Cell(145, 9, ': ' . strtoupper($so->nama_sales), 0, 1, 'L');
			//$pdf->Line(5,148.5,5,0);
			$pdf->Output();
		}
	}
	public function cetak_cover_gc()
	{
		$pdf = new PDF_HTML('P', 'mm', 'a4');
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(true, 10);
		$id = $this->input->get('id');
		$dt_so = $this->db->query("SELECT tr_sales_order_gc.*,tr_spk_gc.nama_npwp as nama_bpkb1,
	  				tr_spk_gc.*,tr_prospek_gc.*, ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,ms_karyawan_dealer.nama_lengkap as nama_sales,tr_spk_gc.id_kelurahan,tr_spk_gc.alamat FROM tr_sales_order_gc 
			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
			LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc			
			LEFT JOIN ms_dealer ON tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
			LEFT JOIN ms_karyawan_dealer on tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
			WHERE tr_sales_order_gc.id_sales_order_gc = '$id'
			");
		if ($dt_so->num_rows() > 0) {
			$so = $dt_so->row();

			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		  		WHERE ms_kelurahan.id_kelurahan = '$so->id_kelurahan'")->row();
			$kelurahan 		= $dt_kel->kelurahan;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$kecamatan 		= $dt_kel->kecamatan;
			$id_kabupaten = $dt_kel->id_kabupaten;
			$kabupaten  	= $dt_kel->kabupaten;
			$id_provinsi  = $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;
			$pdf->SetFont('ARIAL', 'B', 13);
			// $pdf->Cell(120, 5, 'No Faktur : '.$so->id_sales_order_gc, 0, 1, 'L');
			$pdf->Ln(6);
			$pdf->SetFont('ARIAL', 'B', 20);
			$pdf->Cell(190, 5, $so->nama_dealer, 0, 1, 'C');
			$pdf->Ln(9);
			$pdf->SetFont('ARIAL', 'B', 17);
			$pdf->Cell(60, 9, 'NAMA', 0, 0, 'L');
			$pdf->MultiCell(140, 9, ': ' . strtoupper($so->nama_bpkb1), 0, 1);
			$pdf->Cell(60, 9, 'ALAMAT', 0, 0, 'L');
			$pdf->Cell(3, 9, ': ', 0, 0, 'L');
			$pdf->MultiCell(138, 9, strtoupper($so->alamat), 0, 1);
			$pdf->Cell(60, 9, 'KELURAHAN', 0, 0, 'L');
			$pdf->Cell(140, 9, ': ' . strtoupper($kelurahan), 0, 1, 'L');
			$pdf->Cell(60, 9, 'KECAMATAN', 0, 0, 'L');
			$pdf->Cell(140, 9, ': ' . strtoupper($kecamatan), 0, 1, 'L');
			$pdf->Cell(60, 9, 'KOTA', 0, 0, 'L');
			$pdf->Cell(140, 9, ': ' . strtoupper($kabupaten), 0, 1, 'L');
			// $pdf->Cell(65, 9, 'TYPE', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->tipe_ahm), 0, 1, 'L');
			// $pdf->Cell(65, 9, 'DESK AHM', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->deskripsi_ahm), 0, 1, 'L');
			// $pdf->Cell(65, 9, 'WARNA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->warna), 0, 1, 'L');
			// $thn = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$so->no_mesin);
			// if($thn->num_rows() > 0){
			// 	$ty = $th->row();
			// 	$tahun_p = $ty->tahun_produksi;
			// }else{
			// 	$tahun_p = "";
			// }
			// $pdf->Cell(65, 9, 'THN PRODUKSI', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($tahun_p), 0, 1, 'L');
			// $pdf->Cell(65, 9, 'NO. RANGKA', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->no_rangka), 0, 1, 'L');
			// $pdf->Cell(65, 9, 'NO. MESIN', 0, 0, 'L');$pdf->Cell(145, 9, ': '.strtoupper($so->no_mesin), 0, 1, 'L');
			$tgl = date('d-m-Y', strtotime($so->tgl_cetak_so));
			$pdf->Cell(60, 9, 'TGL PEMBELIAN', 0, 0, 'L');
			$pdf->Cell(140, 9, ': ' . $tgl, 0, 1, 'L');
			if ($so->jenis_beli == 'Kredit') {
				$ft = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $so->id_finance_company)->row();
				$fc = "( " . $ft->finance_company . " )";
			} else {
				$fc = "";
			}
			$pdf->Cell(60, 9, 'PEMBAYARAN', 0, 0, 'L');
			$pdf->Cell(140, 9, ': ' . strtoupper($so->jenis_beli) . $fc, 0, 1, 'L');
			$pdf->Cell(60, 9, 'SALES PEOPLE', 0, 0, 'L');
			$pdf->Cell(140, 9, ': ' . strtoupper($so->nama_sales), 0, 1, 'L');
			//$pdf->Line(5,148.5,5,0);

			$pdf->Cell(190, 9, 'DETAIL', 0, 1, 'C');

			$pdf->SetFont('ARIAL', 'B', 15);
			$pdf->Cell(20, 9, 'No.', 1, 0, 'L');
			$pdf->Cell(105, 9, 'No. Faktur', 1, 0, 'L');
			$pdf->Cell(65, 9, 'No. Mesin', 1, 1, 'L');
			$dt_nosin = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin 
				LEFT JOIN tr_fkb ON tr_fkb.no_mesin_spasi=tr_sales_order_gc_nosin.no_mesin
				JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
				WHERE id_sales_order_gc='$so->id_sales_order_gc'");
			$no = 1;
			$pdf->SetFont('ARIAL', 'B', 14);
			foreach ($dt_nosin->result() as $rs) {
				$pdf->Cell(20, 8, $no, 1, 0, 'L');
				$pdf->Cell(105, 8, $rs->nomor_faktur, 1, 0, 'L');
				$pdf->Cell(65, 8, $rs->no_mesin, 1, 1, 'L');
				$no++;
			}
			$pdf->Output();
		}
	}
	public function create_ssu()
	{
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$jam      = gmdate("H:i:s", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		$id       = $this->input->get('id');
		$data['tgl_create_ssu']		= $waktu;
		$data['create_ssu_by']		= $login_id;
		//$data['cetak_invoice_by']	= $login_id;	
		$data['tgl_cetak_invoice']	= $tgl;
		$data['jam_cetak_invoice']	= $jam;
		$data['status_so']	= "so_invoice";
		$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
		$_SESSION['pesan'] 	= "Created SSU Has Been successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order'>";
	}
	public function cetak_kwitansi_()
	{
		if (!$this->input->post('submit')) {
			$data['isi']    = $this->page;
			$data['title']	= $this->title;
			$data['set']		= "kwitansi";
			$id = $this->input->get("id");
			$id_dealer = $this->m_admin->cari_dealer();
			$data['id_sales_order'] = $id;
			// $data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE id_dealer = '$id_dealer' ORDER BY no_spk ASC");															
			// // $data['dt_konsumen']	= $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_spk.*,ms_finance_company.finance_company,tr_sales_order.* FROM tr_spk 
			// // 			INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
			// // 			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
			// // 			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna					
			// // 			LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
			// // 			WHERE tr_sales_order.id_sales_order = '$id' AND tr_spk.id_dealer='$id_dealer'");	
			$data['konsumen'] = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_sales_order.id_sales_order='$id' ORDER BY tr_sales_order.id_sales_order ASC");
			$data['cek_jenis_bayar'] 	= $this->db->query("SELECT * FROM tr_sales_order_jenis_bayar WHERE id_sales_order='$id'");
			$this->template($data);
		} else {
			$tgl                    = gmdate("y-m-d", time() + 60 * 60 * 7);
			$waktu                  = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id               = $this->session->userdata('id_user');
			$tabel                  = $this->tables;
			$pk                     = $this->pk;
			$getLastID 				= $this->db->query("SELECT * FROM tr_sales_order_jenis_bayar ORDER BY id_jenis_bayar DESC");
			$newID 					= $getLastID->num_rows() > 0 ? $getLastID->row()->id_jenis_bayar + 1 : 1;
			$data['id_jenis_bayar'] = $newID;
			$id_sales_order = $data['id_sales_order'] = $this->input->post('id_sales_order');
			$data['uang_dibayar']   = $this->input->post('uang_dibayar');
			$data['jenis_bayar']    = $this->input->post('jenis_bayar');

			$data['created_by']     = $login_id;
			$data['created_at']     = $tgl;
			$data['status']         = "input";

			$login_id               = $this->session->userdata('id_user');
			$getDetailJenis         = $this->db->query("SELECT * FROM tr_sales_order_jenis_bayar_detail WHERE status='new' AND created_by='$login_id'");
			if ($getDetailJenis->num_rows() > 0) {
				foreach ($getDetailJenis->result() as $key => $val) {
					$dt[$key]['id'] = $val->id;
					$dt[$key]['id_jenis_bayar'] = $newID;
					$dt[$key]['created_at'] = $tgl;
					$dt[$key]['created_by']     = $login_id;
					$dt[$key]['status'] = 'input';
				}
				$this->db->update_batch('tr_sales_order_jenis_bayar_detail', $dt, 'id');
			}
			$this->db->trans_begin();
			$this->m_admin->insert('tr_sales_order_jenis_bayar', $data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 		= "Something Wen't Wrong";
				$_SESSION['tipe'] 		= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/cetak_kwitansi?id=" . $id_sales_order . "'>";
			} else {
				$this->db->trans_commit();
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/cetak_kwitansi?id=" . $id_sales_order . "'>";
			}
		}
	}
	public function cetak_kwitansi_gc_()
	{
		if (!$this->input->post('submit')) {
			$data['isi']    = $this->page;
			$data['title']	= $this->title;
			$data['set']		= "kwitansi_gc";
			$id = $this->input->get("id");
			$id_dealer = $this->m_admin->cari_dealer();
			$data['id_sales_order_gc'] = $id;
			// $data['dt_spk'] = $this->db->query("SELECT * FROM tr_spk WHERE id_dealer = '$id_dealer' ORDER BY no_spk ASC");															
			// // $data['dt_konsumen']	= $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_spk.*,ms_finance_company.finance_company,tr_sales_order.* FROM tr_spk 
			// // 			INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
			// // 			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
			// // 			LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna					
			// // 			LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
			// // 			WHERE tr_sales_order.id_sales_order = '$id' AND tr_spk.id_dealer='$id_dealer'");	
			$data['konsumen'] = $this->db->query("SELECT tr_sales_order_gc.*,tr_spk_gc.nama_npwp,tr_spk_gc.alamat FROM tr_sales_order_gc 
				LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
				WHERE tr_sales_order_gc.id_dealer = '$id_dealer' AND tr_sales_order_gc.id_sales_order_gc='$id' 
				ORDER BY tr_sales_order_gc.id_sales_order_gc ASC");
			$data['cek_jenis_bayar'] 	= $this->db->query("SELECT * FROM tr_sales_order_gc_jenis_bayar WHERE id_sales_order_gc='$id'");
			$this->template($data);
		} else {
			$tgl                    = gmdate("y-m-d", time() + 60 * 60 * 7);
			$waktu                  = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id               = $this->session->userdata('id_user');
			$tabel                  = $this->tables;
			$pk                     = $this->pk;
			$getLastID 				= $this->db->query("SELECT * FROM tr_sales_order_gc_jenis_bayar ORDER BY id_jenis_bayar DESC");
			$newID 					= $getLastID->num_rows() > 0 ? $getLastID->row()->id_jenis_bayar + 1 : 1;
			$data['id_jenis_bayar'] = $newID;
			$id_sales_order = $data['id_sales_order_gc'] = $this->input->post('id_sales_order_gc');
			$data['uang_dibayar']   = $this->input->post('uang_dibayar');
			$data['jenis_bayar']    = $this->input->post('jenis_bayar');

			$data['created_by']     = $login_id;
			$data['created_at']     = $tgl;
			$data['status']         = "input";

			$login_id               = $this->session->userdata('id_user');
			$getDetailJenis         = $this->db->query("SELECT * FROM tr_sales_order_gc_jenis_bayar_detail WHERE status='new' AND created_by='$login_id'");
			if ($getDetailJenis->num_rows() > 0) {
				foreach ($getDetailJenis->result() as $key => $val) {
					$dt[$key]['id']             = $val->id;
					$dt[$key]['id_jenis_bayar'] = $newID;
					$dt[$key]['created_at']     = $tgl;
					$dt[$key]['created_by']     = $login_id;
					$dt[$key]['status']         = 'input';
				}
				$this->db->update_batch('tr_sales_order_gc_jenis_bayar_detail', $dt, 'id');
			}
			$this->db->trans_begin();
			$this->m_admin->insert('tr_sales_order_gc_jenis_bayar', $data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$_SESSION['pesan'] 		= "Something Wen't Wrong";
				$_SESSION['tipe'] 		= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/cetak_kwitansi_gc?id=" . $id_sales_order . "'>";
			} else {
				$this->db->trans_commit();
				$_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/cetak_kwitansi_gc?id=" . $id_sales_order . "'>";
			}
		}
	}
	public function cetak_kwitansi_act()
	{
		$id = $this->input->get('id');
		$sql = $this->m_admin->getByID("tr_sales_order", "id_sales_order", $id);
		if ($sql->num_rows() > 0) {
			$tgl 			= gmdate("y-m-d", time() + 60 * 60 * 7);
			$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;
			$data['status_cetak']	= 'cetak_kwitansi';
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$no_kwitansi					= $this->cari_kwitansi();
			$data['no_kwitansi']	= $no_kwitansi;
			$data['tgl_cetak_kwitansi']		= $waktu;
			$data['cetak_kwitansi_by']		= $login_id;
			$cek = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order = '$id'")->row();
			if ($cek->no_kwitansi == "") {
				$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
			}
			$id_dealer = $data['id_dealer'] = $this->m_admin->cari_dealer();
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in = 'UTF-8';
			$mpdf->autoLangToFont = true;
			$data['cetak'] = 'cetak_kwitansi';
			$data['id_sales_order'] = $id;
			$so = $data['so'] = $sql->row();
			$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();
			$data['konsumen'] = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_sales_order.id_sales_order='$id' ORDER BY tr_sales_order.id_sales_order ASC")->row();
			$html = $this->load->view('dealer/sales_order_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order'>";
		}
	}
	public function cetak_kwitansi_act_gc()
	{
		$id = $this->input->get('id');
		$sql = $this->m_admin->getByID("tr_sales_order_gc", "id_sales_order_gc", $id);
		if ($sql->num_rows() > 0) {
			$tgl 			= gmdate("y-m-d", time() + 60 * 60 * 7);
			$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
			$login_id		= $this->session->userdata('id_user');
			$tabel			= $this->tables;
			$pk 				= $this->pk;
			$data['status_cetak']	= 'cetak_kwitansi';
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$no_kwitansi					= $this->cari_kwitansi();
			$data['no_kwitansi']	= $no_kwitansi;
			$data['tgl_cetak_kwitansi']		= $waktu;
			$data['cetak_kwitansi_by']		= $login_id;
			$cek = $this->db->query("SELECT * FROM tr_sales_order_gc WHERE id_sales_order_gc = '$id'")->row();
			if ($cek->no_kwitansi == "") {
				$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
			}
			$id_dealer = $data['id_dealer'] = $this->m_admin->cari_dealer();
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in = 'UTF-8';
			$mpdf->autoLangToFont = true;
			$data['cetak'] = 'cetak_kwitansi';
			$data['id_sales_order'] = $id;
			$so = $data['so'] = $sql->row();
			$data['dealer'] = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer'")->row();
			$data['konsumen'] = $this->db->query("SELECT tr_sales_order_gc.*,tr_spk_gc.nama_npwp,tr_spk_gc.alamat FROM tr_sales_order_gc 
					LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc 
					WHERE tr_sales_order_gc.id_dealer = '$id_dealer' AND tr_sales_order_gc.id_sales_order_gc = '$id' 
					ORDER BY tr_sales_order_gc.id_sales_order_gc ASC")->row();
			$html = $this->load->view('dealer/sales_order_cetak_gc', $data, true);
			// render the view into HTML b
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/gc'>";
		}
	}
	// public function cetak_kwitansi_act()
	// {
	// 	$tgl 			= gmdate("y-m-d", time()+60*60*7);
	// 	$waktu 			= gmdate("Y-m-d H:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk 				= $this->pk;		
	// 	$id 				= $this->input->get('id');		
	// 	$data['status_cetak']	='cetak_kwitansi';	
	// 	$data['updated_at']		= $waktu;		
	// 	$data['updated_by']		= $login_id;
	// 	$data['tgl_cetak_kwitansi']		= $waktu;		
	// 	$data['cetak_kwitansi_by']		= $login_id;		
	// 	$this->m_admin->update("tr_sales_order",$data,"id_sales_order",$id);
	// 	$id_dealer = $this->m_admin->cari_dealer();		
	// 	$konsumen= $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_sales_order.id_sales_order='$id' ORDER BY tr_sales_order.id_sales_order ASC");
	// 	if ($konsumen->num_rows()>0) {
	// 		$konsumen = $konsumen->row();
	// 		 $pdf = new PDF_HTML('p','mm','A4');
	// 		  $pdf->SetMargins(10, 10, 10);
	// 	      $pdf->AddPage();
	// 	       // head	  
	// 	      $pdf->SetFont('ARIAL','',10);
	// 		  $pdf->Cell(190, 4, 'PT. Sinar Senotasa Primatama', 0, 1, 'L');
	// 		  $pdf->Cell(190, 4, 'JL. Alamat', 0, 1, 'L');
	// 		  $pdf->Cell(40, 4, 'Telp. ', 0, 0, 'L');$pdf->Cell(150, 4, ': 08 ', 0, 1, 'L');
	// 		  $pdf->Cell(40, 4, 'Fax ', 0, 0, 'L');$pdf->Cell(150, 4, ': 08 ', 0, 1, 'L');
	// 		  $pdf->Ln(5);
	// 		  $pdf->SetFont('ARIAL','B',12);
	// 		  $pdf->Cell(190, 7, 'Cetak Kwitansi', 0, 1, 'C');
	// 		  $pdf->SetFont('ARIAL','',10);
	// 		  $pdf->Cell(190, 5, 'No. : ', 0, 1, 'C');
	// 		  $pdf->Cell(190, 5, 'Tanggal : ', 0, 1, 'C');
	// 		  $pdf->SetFont('ARIAL','',10);
	// 		  $pdf->Ln(5);
	// 		  $getJenisBayar = $this->db->query("SELECT * FROM tr_sales_order_jenis_bayar WHERE id_sales_order='$id'");
	// 		  if ($getJenisBayar->num_rows()>0) {
	// 		  	$jenis_bayar = $getJenisBayar->row();
	// 		  }
	// 		  	$id_jenis_bayar = $jenis_bayar->id_jenis_bayar;
	// 			$getDetailJenis = $this->db->query("SELECT * FROM tr_sales_order_jenis_bayar_detail WHERE id_jenis_bayar='$id_jenis_bayar'"); 
	// 		  $pdf->Cell(40, 5, 'Telah Terima Dari', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$konsumen->nama_konsumen, 0, 1, 'L');
	// 		  $pdf->Cell(40, 5, 'Uang Sejumlah', 0, 0, 'L');$pdf->Cell(100, 5, ': '.$this->mata_uang($jenis_bayar->uang_dibayar), 0, 1, 'L');
	// 		  $pdf->Cell(40, 5, 'Keterangan', 0, 0, 'L');$pdf->Cell(100, 5, ': ', 0, 1, 'L');
	// 		  $pdf->Cell(190, 5, 'C', 0, 1, 'R');
	// 		  $pdf->Cell(40, 5, 'Untuk Pembayaran', 0, 1, 'L');
	// 		  $pdf->Ln(1);
	// 		  $pdf->Cell(10,6, 'No.', 1, 0, 'C');
	// 		  $pdf->Cell(35,6, 'Kode Account', 1, 0, 'C');
	// 		  $pdf->Cell(40,6, 'No. Referensi', 1, 0, 'C');
	// 		  $pdf->Cell(65,6, 'Keterangan', 1, 0, 'C');
	// 		  $pdf->Cell(40,6, 'Nilai', 1, 1, 'C');
	// 		  $pdf->Cell(10,5, 'No.', 1, 0, 'C');
	// 		  $pdf->Cell(35,5, 'Kode Account', 1, 0, 'C');
	// 		  $pdf->Cell(40,5, 'No. Referensi', 1, 0, 'C');
	// 		  $pdf->Cell(65,5, 'Keterangan', 1, 0, 'C');
	// 		  $pdf->Cell(40,5, 'Nilai', 1, 1, 'C');
	// 		  $pdf->Ln(5);
	// 		  $pdf->Cell(40, 5, 'Data Kendaraan', 0, 1, 'L');
	// 		  $pdf->Ln(1);
	// 		  $pdf->Cell(35,6, 'No. Mesin', 1, 0, 'C');
	// 		  $pdf->Cell(35,6, 'No. Rangka', 1, 0, 'C');
	// 		  $pdf->Cell(53,6, 'Tipe', 1, 0, 'C');
	// 		  $pdf->Cell(52,6, 'Warna', 1, 0, 'C');
	// 		  $pdf->Cell(15,6, 'Tahun', 1, 1, 'C');
	// 		  $pdf->Output(); 	
	// 	}else{
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/sales_order'>";
	// 	}
	// }
	public function cetak_barcode()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$data['status_cetak']	= 'cetak_barcode';
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;
		$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
		$pdf = new PDF_HTML('p', 'mm', 'A4');
		$pdf->SetMargins(4, 4, 4);
		$pdf->AddPage();
		// head	  
		$pdf->SetFont('ARIAL', '', 7.5);
		$so = $this->db->query("SELECT tr_sales_order.*,ms_dealer.nama_dealer,ms_dealer.alamat as alamat_dealer,ms_dealer.no_telp,ms_dealer.id_kelurahan as kelurahan_dealer, tr_scan_barcode.id_item, tr_spk.*, ms_tipe_kendaraan.tipe_ahm, ms_warna.warna,ms_finance_company.finance_company,ms_tipe_kendaraan.deskripsi_ahm,tr_scan_barcode.no_rangka as norangka FROM tr_sales_order 
					left join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
					left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk
					left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
					left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
					left join ms_dealer on tr_sales_order.id_dealer = ms_dealer.id_dealer
					left join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					WHERE id_sales_order = '$id' ");
		if ($so->num_rows() > 0) {
			$so = $so->row();
			$x = 4;
			$y = 4;
			$batas = 3;
			$count_batas = 1;
			$x_b = 15;
			$y_b = 6;

			for ($i = 0; $i < 7; $i++) {
				$pdf->Code128($x_b, $y_b, $so->no_mesin, 41, 9);
				$pdf->SetXY($x, $y);
				$tgl = date('d-m-Y', strtotime($so->tgl_cetak_invoice));
				$deskripsi_ahm = strip_tags($so->deskripsi_ahm);
				$pdf->MultiCell(66, 3, "\n\n\n\n  No. Mesin\t\t\t\t\t\t\t\t\t\t\t\t: $so->no_mesin\n  No. Rangka\t\t\t\t\t\t\t\t\t: $so->norangka / $so->id_item\n  Nama\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $so->nama_bpkb\n  Alamat\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $so->alamat_ktp_bpkb\n  No.HP\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $so->no_hp\n  Tgl. Pembelian\t\t\t\t: $tgl\n  Tipe / No. Polisi\t\t\t: $deskripsi_ahm\n\n", 1, 1);
				$count_batas++;
				$x += 66;
				$x_b += 66;
				if ($count_batas > 3) {
					$x = 4;
					$y += 38;
					$y_b += 38;
					$x_b = 15;
					$count_batas = 1;
				}
			}
		}
		$pdf->Output();
	}
	public function cetak_barcode_gc()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$data['status_cetak']	= 'cetak_barcode';
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;
		$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
		// $pdf = new PDF_HTML('p','mm','A4');
		// $pdf->SetMargins(4, 4, 4);
		// head	  

		$sql = $this->db->query("SELECT tr_sales_order_gc.*, tr_sales_order_gc_nosin.*,ms_dealer.nama_dealer,ms_dealer.alamat AS alamat_dealer,
		  		ms_dealer.no_telp,ms_dealer.id_kelurahan AS kelurahan_dealer, tr_scan_barcode.id_item, tr_spk_gc.*, ms_tipe_kendaraan.tipe_ahm, 
		  		ms_warna.warna,ms_finance_company.finance_company,ms_tipe_kendaraan.deskripsi_ahm,tr_scan_barcode.no_rangka AS norangka FROM tr_sales_order_gc_nosin 
					LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
					LEFT JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_sales_order_gc_nosin.no_mesin
					LEFT JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc_nosin.no_spk_gc
					LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
					LEFT JOIN ms_dealer ON tr_spk_gc.id_dealer = ms_dealer.id_dealer
					LEFT JOIN ms_finance_company ON tr_spk_gc.id_finance_company = ms_finance_company.id_finance_company
					WHERE tr_sales_order_gc.id_sales_order_gc = '$id' ");
		if ($sql->num_rows() > 0) {
			//$so = $so->row();
			// $x=4;$y=4;$batas=3;$count_batas=1;$x_b=15;$y_b=6;	
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in = 'UTF-8';
			$mpdf->autoLangToFont = true;
			foreach ($sql->result() as $so) {
				$dt_cetak['so'] = $so;
				$dt_cetak['cetak'] = 'cetak_barcode_gc';
				$html = $this->load->view('dealer/sales_order_cetak_gc', $dt_cetak, true);
				$mpdf->WriteHTML($html);
				$mpdf->AddPage();
				// $pdf->AddPage();	
				// $pdf->SetFont('ARIAL','',7.5);		
				// 	for ($i=0; $i < 7; $i++) { 
				// 	 	$pdf->Code128($x_b,$y_b,$so->no_mesin,41,9);
				// 	 	$pdf->SetXY($x,$y);
				// 	 	$tgl = date('d-m-Y', strtotime($so->tgl_cetak_invoice));
				// 	 	$deskripsi_ahm = strip_tags($so->deskripsi_ahm);
				//  	  $pdf->MultiCell(66, 3, "\n\n\n\n  No. Mesin\t\t\t\t\t\t\t\t\t\t\t\t: $so->no_mesin\n  No. Rangka\t\t\t\t\t\t\t\t\t: $so->norangka\n  Nama\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $so->nama_npwp\n  Alamat\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $so->alamat\n  No.HP\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $so->no_hp\n  Tgl. Pembelian\t\t\t\t: $tgl\n  Tipe / No. Polisi\t\t\t: $so->deskripsi_ahm\n\n",1, 1);
				//  		$count_batas++;
				//  		$x+=66;
				//  		$x_b+=66;
				//  		if ($count_batas > 3) {
				//  			$x=4;
				//  			$y+=38;
				//  			$y_b+=38;
				//  			$x_b=15;
				//  			$count_batas=1;
				//  		}
				// 	}
			}

			$mpdf->Output("cetak_barcode_gc.pdf", 'I');
		}
		//$pdf->Cell(190, 7, 'Cetak Barcode', 1, 1, 'C');
		// $pdf->Output(); 	
	}
	public function cetak_sppu()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$data['status_cetak']	= 'cetak_sppu';
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;
		$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
		$pdf = new FPDF('p', 'mm', 'A4');
		$pdf->SetMargins(10, 10, 10);
		$pdf->AddPage();
		// head	  
		$pdf->SetFont('ARIAL', 'B', 13);
		$pdf->Cell(190, 7, 'Cetak DO', 1, 1, 'C');
		$pdf->Output();
	}
	public function cek_no_bastk()
	{
		$tgl 						= date("d");
		$cek_tgl					= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");
		$id_dealer = $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer' ");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
		} else {
			$get_dealer = '';
		}
		$pr_num 				= $this->db->query("SELECT *,mid(tgl_bastk,6,2)as bln FROM tr_sales_order WHERE LEFT(tgl_bastk,7) = '$cek_tgl' AND id_dealer='$id_dealer' ORDER BY no_bastk DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {


			$row 	= $pr_num->row();
			$id = explode('/', $row->no_bastk);
			if (count($id) > 1) {
				if ($bln == $row->bln) {
					$isi 	= $th . '/' . $bln . '/' . $get_dealer . '/BASTK/' . sprintf("%'.05d", $id[4] + 1);
				} else {
					$isi = $th . '/' . $bln . '/' . $get_dealer . '/BASTK/00001';
				}
			} else {
				$isi = $th . '/' . $bln . '/' . $get_dealer . '/BASTK/00001';
			}
			$kode = $isi;
		} else {
			$kode = $th . '/' . $bln . '/' . $get_dealer . '/BASTK/00001';
		}
		return $kode;
	}
	public function cek_no_bastk_gc()
	{
		$tgl 						= date("d");
		$cek_tgl					= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");
		$id_dealer = $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer' ");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
		} else {
			$get_dealer = '';
		}
		$pr_num 				= $this->db->query("SELECT *,mid(tgl_bastk,6,2)as bln FROM tr_sales_order_gc WHERE LEFT(tgl_bastk,7) = '$cek_tgl' AND id_dealer='$id_dealer' ORDER BY no_bastk DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {


			$row 	= $pr_num->row();
			$id = explode('/', $row->no_bastk);
			if (count($id) > 1) {
				if ($bln == $row->bln) {
					$isi 	= $th . '/' . $bln . '/' . $get_dealer . '/BASTK/' . sprintf("%'.05d", $id[4] + 1);
				} else {
					$isi = $th . '/' . $bln . '/' . $get_dealer . '/BASTK/00001';
				}
			} else {
				$isi = $th . '/' . $bln . '/' . $get_dealer . '/BASTK/00001';
			}
			$kode = $isi;
		} else {
			$kode = $th . '/' . $bln . '/' . $get_dealer . '/BASTK/00001';
		}
		return $kode;
	}

	public function tesdel()
	{
		echo $this->get_delivery_document_id();
	}
	public function get_delivery_document_id()
	{
		$th       = date('Y');
		$bln      = date('m');
		$th_bln   = date('Y-m');
		$th_kecil = date('y');
		$dmy = date('dmy');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
		$get_data  = $this->db->query("SELECT * FROM tr_sales_order WHERE LEFT(tr_sales_order.created_at,7)='$th_bln' AND id_dealer=$id_dealer AND delivery_document_id IS NOT NULL
			ORDER BY tr_sales_order.created_at DESC LIMIT 1");
		if ($get_data->num_rows() > 0) {
			$row      = $get_data->row();
			$delivery_document_id = substr($row->delivery_document_id, -4);
			$new_kode = 'DDoc/' . $dealer->kode_dealer_md . '/' . $dmy . '/' . sprintf("%'.04d", $delivery_document_id + 1);
			$i = 0;
			while ($i < 1) {
				$cek = $this->db->get_where('tr_sales_order', ['delivery_document_id' => $new_kode])->num_rows();
				if ($cek > 0) {
					$neww     = substr($new_kode, -4);
					$new_kode = 'DDoc/' . $dealer->kode_dealer_md . '/' . $dmy . '/' . sprintf("%'.04d", $neww + 1);
					$i        = 0;
				} else {
					$i++;
				}
			}
		} else {
			$new_kode = 'DDoc/' . $dealer->kode_dealer_md . '/' . $dmy . '/0001';
		}
		return $new_kode;
	}
	public function bastk()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$no_bastk 		= $this->cek_no_bastk();
		$cek_no_bastk = $this->db->query("SELECT * FROM tr_sales_order WHERE no_bastk = '$no_bastk' ");
		if ($cek_no_bastk->num_rows() == 0) {
			$id_sales_order = $data['id_sales_order']       = $this->input->get('id_sales_order');
			$data['id_master_plat']       = $this->input->get('id_master_plat');
			$data['tgl_pengiriman']       = $this->input->get('tgl_pengiriman');
			$data['waktu_pengiriman']     = $this->input->get('waktu_pengiriman');
			$data['lokasi_pengiriman']    = $this->input->get('lokasi_pengiriman');
			$data['nama_penerima']        = $this->input->get('nama_penerima');
			$data['no_hp_penerima']       = $this->input->get('no_hp_penerima');
			$data['delivery_document_id'] = $this->get_delivery_document_id();
			$data['ambil']                = $ambil 	= $this->input->get('ambil');
			$data['no_bastk']             = $no_bastk;
			$data['tgl_bastk']            = $waktu;
			$data['status_cetak']         = 'cetak_bastk';
			$status_delivery = 'ready';
			$cek_so          = $this->db->get_where("tr_sales_order", ['id_sales_order' => $id_sales_order])->row();
			if ($cek_so->status_delivery != null) {
				if ($cek_so->status_delivery != 'ready') {
					$status_delivery = 'back_to_dealer';
				}
			}
			$data['status_delivery']	  = $status_delivery;
			$data['updated_at']           = $waktu;
			$data['updated_by']           = $login_id;
			$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $this->input->get('id_sales_order'));
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sppu'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function bastk_gc()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$no_bastk 		= $this->cek_no_bastk_gc();
		$cek_no_bastk = $this->db->query("SELECT * FROM tr_sales_order_gc WHERE no_bastk = '$no_bastk' ");
		if ($cek_no_bastk->num_rows() == 0) {

			$id_sales_order_gc = $data['id_sales_order_gc'] 	= $this->input->get('id_sales_order_gc');
			$cek_so_gc = $this->db->get_where('tr_sales_order_gc', ['id_sales_order_gc' => $id_sales_order_gc])->row();
			$data['id_master_plat']    = $this->input->get('id_master_plat');
			$data['ambil']             = $ambil 	= $this->input->get('ambil');
			$data['tgl_pengiriman']    = $this->input->get('tgl_pengiriman');
			$data['waktu_pengiriman']  = $this->input->get('waktu_pengiriman');
			$data['lokasi_pengiriman'] = $this->input->get('lokasi_pengiriman');
			$data['nama_penerima']     = $this->input->get('nama_penerima');
			$data['no_hp_penerima']    = $this->input->get('no_hp_penerima');
			if ($cek_so_gc->no_bastk == null) {
				$data['no_bastk'] 		= $no_bastk;
				$data['tgl_bastk']		= $waktu;
				$data['status_cetak']	= 'cetak_bastk';
			}
			$data['bastk_ke'] = $cek_so_gc->bastk_ke + 1;
			$data['updated_at']     = $waktu;
			$data['updated_by']     = $login_id;
			$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $this->input->get('id_sales_order_gc'));
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sppu'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function saveJenisBayar()
	{
		$waktu                 = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id              = $this->session->userdata('id_user');
		$data['no_rek_tujuan'] = $this->input->post('no_rek_tujuan');
		$data['bank_konsumen'] = $this->input->post('bank_konsumen');
		$data['nilai']         = $this->input->post('nilai');
		$data['tgl_transfer']  = $this->input->post('tgl_transfer');
		$data['status']        = 'new';
		$data['created_by']    = $login_id;
		$data['created_at']    = $waktu;
		$cek_gc = $this->input->post('gc');
		if ($cek_gc != 'tidak') {
			$this->m_admin->insert("tr_sales_order_gc_jenis_bayar_detail", $data);
		} else {
			$this->m_admin->insert("tr_sales_order_jenis_bayar_detail", $data);
		}
		echo "nihil";
	}
	public function delete_jenis_bayar()
	{
		$id = $this->input->post('id');
		$gc = $this->input->post('gc');
		$tbl_detail = $gc == 'ya' ? 'tr_sales_order_gc_jenis_bayar_detail' : 'tr_sales_order_jenis_bayar_detail';
		$this->db->query("DELETE FROM $tbl_detail WHERE id = '$id'");
		echo "nihil";
	}
	public function saveJenisBayarGiro()
	{
		$waktu                 = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id              = $this->session->userdata('id_user');
		$data['no_rek_tujuan'] = $this->input->post('no_rek_tujuan');
		$data['nilai']         = $this->input->post('nilai');
		$data['bank_konsumen'] = $this->input->post('bank_konsumen');
		$data['no_cek_giro']   = $this->input->post('no_cek_giro');
		$data['tgl_cek_giro']  = $this->input->post('tgl_cek_giro');
		$data['status']        = 'new';
		$data['created_by']    = $login_id;
		$data['created_at']    = $waktu;
		$this->m_admin->insert("tr_sales_order_jenis_bayar_detail", $data);
		echo "nihil";
	}
	public function simpan_jenis_giro()
	{
		$waktu                 = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id              = $this->session->userdata('id_user');
		$data['no_rek_tujuan'] = $this->input->post('no_rek_tujuan');
		$data['nilai']         = $this->input->post('nilai');
		$data['bank_konsumen'] = $this->input->post('bank_konsumen');
		$data['tgl_transfer']  = $this->input->post('tgl_transfer');
		$data['status']        = 'new';
		$data['created_by']    = $login_id;
		$data['created_at']    = $waktu;
		$this->m_admin->insert("tr_sales_order_jenis_bayar_detail", $data);
		echo "nihil";
	}
	public function close()
	{
		$waktu                 = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id              = $this->session->userdata('id_user');
		$id 									 = $this->input->get('id');
		$data['status_close']  = "closed";
		$data['updated_by']    = $login_id;
		$data['updated_at']    = $waktu;
		$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
		$_SESSION['pesan'] 	= "Data has been closed successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order'>";
	}
	public function close_gc()
	{
		$waktu                 = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id              = $this->session->userdata('id_user');
		$id 									 = $this->input->get('id');
		$data['status_close']  = "closed";
		$data['updated_by']    = $login_id;
		$data['updated_at']    = $waktu;
		$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
		$_SESSION['pesan'] 	= "Data has been closed successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/gc'>";
	}
	function getSales()
	{
		$id_sales_order = $this->input->post('id');
		$sales = $this->m_so->getSales($id_sales_order);
		send_json($sales);
	}

	function cekTglPengiriman()
	{
		$id_sales_order = $this->input->post('id_sales_order');
		$tgl_pengiriman = $this->input->post('tgl_pengiriman');
		$waktu_pengiriman = $this->input->post('waktu_pengiriman');
		$cek_so = $this->db->query("SELECT * FROM tr_sales_order WHERE id_sales_order='$id_sales_order'")->row();
		$tgl_skrg             = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl_waktu_pengiriman = $tgl_pengiriman . ' ' . $waktu_pengiriman;
		$cek                  = bandingTgl($cek_so->created_at, $tgl_waktu_pengiriman);
		$bastk                = 0;
		if ($cek_so->tgl_bastk != null) {
			if ($cek_so->tgl_bastk != '') {
				$cek   = bandingTgl($cek_so->tgl_bastk, $tgl_waktu_pengiriman);
				$bastk = 1;
			}
		}
		if ($cek > 0) {
			$result = ['status' => 'selisih', 'bastk' => $bastk];
		} else {
			$result = ['status' => 'oke', 'bastk' => $bastk];
		}
		echo json_encode($result);
	}
	public function set_bastk_gc()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "set_bastk_gc";
		$filter['id_sales_order_gc'] = $this->input->get('id');
		$row = $this->m_so->getSalesOrderGC($filter);
		if ($row->num_rows() > 0) {
			$data['row'] = $row->row();
			$data['detail_nosin'] = $this->m_so->getSalesOrderGCNoMesin($filter)->result();
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sales_order/gc'>";
		}
	}
	public function save_bastk_gc()
	{
		$post     = $this->input->post();
		$waktu    = waktu_full();
		$login    = user()->id_user;
		$no_bastk = $this->cek_no_bastk_gc();
		$filter = ['id_sales_order_gc' => $post['id_sales_order_gc']];
		$so_gc = $this->m_so->getSalesOrderGC($filter);
		if ($so_gc->num_rows() == 0) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'Data tidak ditemukan !'
			];
			send_json($rsp);
		} else {
			$so_gc = $so_gc->row();
		}
		// send_json($so_gc);
		$upd = [
			'ambil'             => $post['ambil'],
			'tgl_pengiriman'    => $post['tgl_pengiriman'],
			'waktu_pengiriman'  => $post['waktu_pengiriman'],
			'lokasi_pengiriman' => $post['lokasi_pengiriman'],
			'nama_penerima'     => $post['nama_penerima'],
			'no_hp_penerima'    => $post['no_hp_penerima'],
			'bastk_ke'          => $so_gc->bastk_ke + 1,
			'updated_at'        => $waktu,
			'updated_by'        => $login,
		];
		if ($so_gc->no_bastk == null) {
			$upd['no_bastk']     = $no_bastk;
			$upd['tgl_bastk']    = $waktu;
			$upd['status_cetak'] = 'cetak_bastk';
		}

		$last_id_doc_gc = $this->m_so->get_delivery_document_id_gc(true);
		foreach ($post['details'] as $dt) {

			$filter_nosin['no_mesin'] = $dt['no_mesin'];
			$so_gc_n = $this->m_so->getSalesOrderGCNoMesin($filter_nosin)->row();
			if ($so_gc_n->status_delivery == null) {
				$status_delivery = 'ready';
			}

			$delivery_document_id = $so_gc_n->delivery_document_id;
			if ($so_gc_n->delivery_document_id == null) {
				if ($last_id_doc_gc === false) {
					$delivery_document_id = $this->m_so->get_delivery_document_id_gc(null, 'new');
				} else {
					$delivery_document_id = $this->m_so->get_delivery_document_id_gc(null, $last_id_doc_gc);
					$last_id_doc_gc = $delivery_document_id;
				}
			}

			$upd_detail[] = [
				'no_mesin'       => $dt['no_mesin'],
				'status_delivery' => isset($status_delivery) ? $status_delivery : NULL,
				'delivery_document_id' => $delivery_document_id,
				'id_master_plat' => $dt['id_master_plat'],
				'updated_at'     => $waktu,
				'updated_by'     => $login,
			];
		}
		// $res = ['upd' => $upd, 'upd_detail' => $upd_detail];
		// send_json($res);
		$this->db->trans_begin();
		//Header Update
		$this->db->update('tr_sales_order_gc', $upd, $filter);
		// Detail Update
		$this->db->where($filter);
		$this->db->update_batch('tr_sales_order_gc_nosin', $upd_detail, 'no_mesin');
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
				'link' => base_url('dealer/' . $this->page . '/gc')
			];
			$_SESSION['pesan']   = "Data has been updated successfully";
			$_SESSION['tipe']   = "success";
		}
		send_json($rsp);
	}

	public function getDataAllSO()
    {
        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
	$limit = $_POST['length']; // Ambil data limit per page
	$start = $_POST['start']; // Ambil data start
	$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
	$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
	$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"

        $id_menu = $this->m_admin->getMenu($this->page);
		$group 	= $this->session->userdata("group");

        $id_dealer = $this->m_admin->cari_dealer();

        $cari = '';
		if ($search != '') {
			$cari = " 
			and (tr_prospek.nama_konsumen LIKE '%$search%' 
						OR tr_spk.alamat LIKE '%$search%' 
						OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%' 
						OR ms_warna.warna LIKE '%$search%'
						OR tr_scan_barcode.no_rangka LIKE '%$search%'
						OR tr_sales_order.no_mesin LIKE '%$search%')
			";
		}

        $dataSo = $this->db->query("

			SELECT
				tr_sales_order.*,
				tr_prospek.nama_konsumen,
				tr_spk.alamat,
				ms_tipe_kendaraan.tipe_ahm,
				ms_warna.warna,
				tr_scan_barcode.no_rangka 
			FROM
				tr_sales_order
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna 
			WHERE
				tr_sales_order.id_dealer = '$id_dealer' 
				AND (status_close IS NULL OR status_close <> 'closed')  			
				AND (status_cetak <> 'konsumen' OR status_cetak IS NULL)
			$cari
			ORDER BY $order_field $order_ascdesc
			LIMIT $start,$limit
        	");

        $data = array();

        foreach($dataSo->result() as $row)
        {
                $print    = $this->m_admin->set_tombol($id_menu, $group, 'print');
        	$no_faktur = $this->db->query("SELECT nomor_faktur from tr_fkb where no_mesin_spasi='$row->no_mesin'");
	          if ($no_faktur->num_rows() > 0) {
	            $no_faktur = $no_faktur->row()->nomor_faktur;
	          } else {
	            $no_faktur = '';
	          }
	          $tombol1 = "";
	          $tombol = "";  

	         $cetak_so = "<a href='dealer/sales_order/cetak_so?id=$row->id_sales_order' target='_blank' >
                              <button $print class='btn btn-flat btn-xs bg-blue' ><i class='fa fa-print'></i> Cetak SO</button>
                            </a>
                            ";
                        $cetak_cover = "<a href='dealer/sales_order/cetak_cover?id=$row->id_sales_order' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-green'><i class='fa fa-print'></i> Cetak Cover</button> 
                          </a>";
                        $cetak_invoice = "
                          <a href='dealer/sales_order/cetak_invoice?id=$row->id_sales_order' target='_blank' >
                            <button $print class='btn btn-flat btn-xs bg-blue'><i class='fa fa-print'></i> Cetak Invoice</button>
                          </a>";
                        $cetak_barcode = "<a href='dealer/sales_order/cetak_barcode?id=$row->id_sales_order' target='_blank' >
                          <button $print class='btn btn-flat btn-xs btn-danger'><i class='fa fa-print'></i> Cetak Barcode AHASS</button>
                        </a>";
                        $cetak_kwitansi = "<a href='dealer/sales_order/cetak_kwitansi?id=$row->id_sales_order' target='_blank' >
                        <button $print class='btn btn-flat btn-xs bg-maroon'><i class='fa fa-print'></i> Cetak Kwitansi</button>
                      </a>";
                        $btn_bastk = "
                            <button $print type=\"button\" class=\"btn btn-success btn-flat btn-xs\"  id_sales_order=\"$row->id_sales_order\" onclick=\"choosedriver('$row->id_sales_order')\">BASTK</button>";
                        $btn_close = "<a href='dealer/sales_order/close?id=$row->id_sales_order'>
                        <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-close'></i> Close</button>
                      </a>";
                        $paid = '';
                        if ($row->is_paid == 1) {
                          $paid = "<i class='fa fa-check'></i>";
                          if ($row->status_cetak == '') {
                            $tombol = $cetak_so;
                          } elseif ($row->status_cetak == 'approve') {
                            $tombol1 = $cetak_so;
                          } elseif ($row->status_cetak == 'cetak_so' and $row->tgl_cetak_so != null) {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice;
                          } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_so') and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice . ' ' . $cetak_barcode;
                          } elseif (($row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode') and $row->tgl_bastk != null and $row->status_cetak != 'cetak_bastk' and $row->status_so == 'so_invoice') {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice . ' ' . $cetak_barcode . ' ' . $btn_bastk;
                          } elseif (($row->status_cetak == 'cetak_bastk' or $row->status_cetak == 'cetak_invoice' or $row->status_cetak == 'cetak_barcode'  or $row->status_cetak == 'cetak_kwitansi') and $row->status_so == 'so_invoice') {
                            $tombol1 = $cetak_so . ' ' . $cetak_cover . ' ' . $cetak_invoice . ' ' . $cetak_barcode . ' ' . $btn_bastk . ' ' . $btn_close;
                          } elseif ($row->status_cetak == 'reject') {
                            // $tombol ="<a href='dealer/sales_order/edit?id=$row->id_sales_order' target='_blank' >
                            //             <button $edit class='btn btn-flat btn-xs btn-warning'><i class='fa fa-pencil'></i> Edit</button>
                            //           </a>";
                            $tombol = "";
                            $tombol1 = "";
                          }
                        }

            $data[]= array(
            	'',
                "<a href='dealer/sales_order/konsumen?id=$row->id_sales_order'>$row->id_sales_order</a>",
                $row->no_mesin,
                $row->no_rangka,
                $no_faktur,
                $row->tipe_ahm,
                $row->warna,
                $row->nama_konsumen,
                $row->alamat,
                $paid,
                $tombol.$tombol1
            );     
        }

        $get_total = $this->db->query("
        	SELECT
				tr_sales_order.*,
				tr_prospek.nama_konsumen,
				tr_spk.alamat,
				ms_tipe_kendaraan.tipe_ahm,
				ms_warna.warna,
				tr_scan_barcode.no_rangka 
			FROM
				tr_sales_order
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna 
			WHERE
				tr_sales_order.id_dealer = '$id_dealer' 
				AND (status_close IS NULL OR status_close <> 'closed')  			
				AND (status_cetak <> 'konsumen' OR status_cetak IS NULL)
			$cari
        	");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
}
