<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pengajuan_bbn_md extends CI_Controller
{
	var $tables =   "tr_pengajuan_bbn";

	var $folder =   "h1";

	var $page   =		"pengajuan_bbn_md";

	var $pk     =   "id_pengajuan_bbn";

	var $title  =   "Pengajuan BBN Dealer ke MD";
	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_kelurahan');
		$this->load->model('h1_pengajuan_bbn_md');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
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
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view";
		$data['dt_bbn'] = "";
		$this->template($data);
	}

	public function get_data_pengajuan_bbm_md($page='')
	{
		$page = $this->input->get('page');
		$search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
		$limit = $_POST['length']; // Ambil data limit per page
		$start = $_POST['start']; // Ambil data start
		$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
		$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
		$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
		if ($page == 'history') {
			$dataBbnMd = $this->h1_pengajuan_bbn_md->data_bbn_md_history($search, $limit, $start, $order_field, $order_ascdesc);
		} else {
			$dataBbnMd = $this->h1_pengajuan_bbn_md->data_bbn_md($search, $limit, $start, $order_field, $order_ascdesc);
		}
        
        $data = array();
        foreach($dataBbnMd->result() as $row)
        {

            $st = $row->status_faktur;            
            if($st=='approved'){            
                // $tombol = "<a href='h1/pengajuan_bbn_md/check?id=$row->no_bastd' class='btn btn-primary btn-flat btn-xs'>Check</a>                
                //   <a href='h1/pengajuan_bbn_md/cetak_faktur?id=$row->no_bastd' class='btn btn-warning btn-flat btn-xs'>Cetak Faktur</a>
                //   <a href='h1/pengajuan_bbn_md/cetak_permohonan?id=$row->no_bastd' class='btn btn-warning btn-flat btn-xs'>Cetak Permohonan STNK</a>
                //   <a href='h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb?id=$row->no_bastd' class='btn btn-success btn-flat btn-xs'>Cetak Pendaftaran BPKB</a>";
              $tombol="";
            }elseif($st=='rejected'){
              $tombol = "";
            }else{
              $tombol = "<a href='h1/pengajuan_bbn_md/cek_approval?id=$row->no_bastd' class='btn btn-primary btn-flat btn-xs'>Approve/Reject</a>";
            }
            $cek = $this->m_admin->getByID("tr_pengajuan_bbn","no_bastd",$row->no_bastd);
            $cek2 = $this->m_admin->getByID("tr_faktur_stnk","no_bastd",$row->no_bastd);            
            if($cek->num_rows() > 0){
              $id_    = $cek->row();
              if ($id_->status_pengajuan=='checked') {
                $status = "<span class='label label-primary'>approved</span>";
              }else{
                $status = "<span class='label label-danger'>$id_->status_pengajuan</span>";
              }
            }else{
              $id2    = $cek2->row();              
              if($id2->status_faktur == 'input'){
                $status = "<span class='label label-danger'>$id2->status_faktur</span>";
              }else{
                $status = "<span class='label label-primary'>$id2->status_faktur</span>";
              }              
            }     

            $data[]= array(
            	'',
                "<a href='h1/pengajuan_bbn_md/detail?id=$row->no_bastd'>
                  $row->no_bastd
                </a>",
                $row->tgl_bastd,
                $row->nama_dealer,
                $status,
                $tombol
            );     
        }
        if ($page == 'history') {
        	$total_data = $this->h1_pengajuan_bbn_md->count_filter_bbm_md_history($search);
        } else {
        	$total_data = $this->h1_pengajuan_bbn_md->count_filter_bbm_md($search);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
	}

	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$this->template($data);
	}
	public function cetak_faktur_act()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id       = $this->input->get('no');
		$id2      = $this->input->get('b');
		$row = $this->db->get_where('tr_pengajuan_bbn_detail', ['no_mesin' => $id])->row();
		$data['cetak_faktur_ke'] = $row->cetak_faktur_ke + 1;
		$data['cetak']           = "ya";
		$data['tgl_cetak']       = $tgl;
		$this->m_admin->update("tr_pengajuan_bbn_detail", $data, "no_mesin", $id);
		//$r = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$id)->row();
		$cek_r = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail 
            WHERE tr_pengajuan_bbn_detail.no_mesin = '$id' and tr_pengajuan_bbn_detail.no_bastd = '$id2'");
		$data['r'] = $cek_r->row();
		$no_bastd = $cek_r->row()->no_bastd;
		$cek_dealer = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$data['dealer'] = $cek_dealer->row();
		$mpdf = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'UTF-8';
		$mpdf->autoLangToFont = true;
		$data['cetak'] = 'cetak_kwitansi';
		//$data['id_penerimaan_bank'] = $id;

		$html = $this->load->view('h1/cetak_faktur_bbn', $data, true);
		$mpdf->WriteHTML($html);
		$output = 'cetak_.pdf';
		$mpdf->Output("$output", 'I');
	}

	public function ajax_list()
	{
		$list = $this->m_kelurahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$cek = $this->m_admin->getByID("ms_kecamatan", "id_kecamatan", $isi->id_kecamatan);
			$kecamatan = ($cek->num_rows() > 0) ? $cek->row()->kecamatan : "";
			$id_kabupaten = ($cek->num_rows() > 0) ? $cek->row()->id_kabupaten : "";

			$cek2 = $this->m_admin->getByID("ms_kabupaten", "id_kabupaten", $id_kabupaten);
			$kabupaten = ($cek2->num_rows() > 0) ? $cek2->row()->kabupaten : "";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->kelurahan;
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
	public function cek_approval()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cek Approval " . $this->title;
		$data['set']		= "cek_approval";
		$no_bastd				=	$this->input->get("id");
		$data['no_bastd']		=	$this->input->get("id");
		$data['dt_stnk'] 		= $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
					 WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$data['dt_faktur'] 	= $this->m_admin->getByID("tr_faktur_stnk", "no_bastd", $no_bastd);
		$data['get_nosin']  = $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin=tr_scan_barcode.no_mesin 
                          WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd' ORDER BY tr_scan_barcode.tipe_motor ASC");

		$data['get_nosin1']  = $this->db->query("SELECT * FROM tr_faktur_stnk_detail 
			INNER JOIN tr_bantuan_bbn_luar ON tr_faktur_stnk_detail.no_mesin=tr_bantuan_bbn_luar.no_mesin 
	  		WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd' ORDER BY tr_bantuan_bbn_luar.id_tipe_kendaraan ASC");

		$this->template($data);
	}
	public function save_approval()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_bastd		= $this->input->post("no_bastd");
		$save				= $this->input->post("save");
		$da['updated_at'] 		= $waktu;
		$da['updated_by'] 		= $login_id;
		$da['tgl_approval'] 	= $tgl;			
		if ($save == 'approve') {
			$da['status_faktur'] 	= "approved";
		} else {
			$da['status_faktur'] 	= "rejected";
		}
		$this->m_admin->update("tr_faktur_stnk", $da, "no_bastd", $no_bastd);

		$ins_bbn['tgl_bastd']        = "";
		$ins_bbn['no_bastd']         = $no_bastd;
		$id                = $this->m_admin->getByID("tr_faktur_stnk", "no_bastd", $no_bastd)->row();
		$ins_bbn['id_dealer']        = $id->id_dealer;
		$ins_bbn['status_pengajuan'] = "checked";
		$cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn WHERE no_bastd = '$no_bastd'");

		if ($cek->num_rows() > 0) {
			$id = $cek->row();
			$this->m_admin->update("tr_pengajuan_bbn", $ins_bbn, "id_pengajuan_bbn", $id->id_pengajuan_bbn);
		} else {
			$this->m_admin->insert("tr_pengajuan_bbn", $ins_bbn);
		}
		$_SESSION['pesan']     = "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/pengajuan_bbn_md'>";
	}
	public function detail()
	{
		$id    					= $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "detail";
		$no_bastd 			= $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'");
		$data['dt_faktur'] = $this->m_admin->getByID("tr_faktur_stnk", "no_bastd", $id);
		$this->template($data);
	}
	public function check()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Check " . $this->title;
		$data['set']		= "check";
		$no_bastd 			= $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'");
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$this->template($data);
	}

	public function edit()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Edit " . $this->title;
		$data['set']		= "edit";
		$id_so 					= $this->input->get('id');
		$no_bastd 			= $this->input->get('b');
		$no_mesin 	= $data['no_mesin']		= $this->input->get('no');
		$query = $this->db->query("SELECT tr_sales_order.*,ms_pekerjaan.pekerjaan AS kerja,tr_scan_barcode.no_rangka,ms_item.id_item,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,
			ms_warna.warna,tr_spk.*
			FROM tr_sales_order 
			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN ms_pekerjaan ON ms_pekerjaan.id_pekerjaan = tr_spk.pekerjaan
			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_sales_order = '$id_so'");
		$query3 = $this->db->query("SELECT tr_sales_order_gc.*,tr_sales_order_gc_nosin.nama_stnk AS nama_konsumen, tr_scan_barcode.no_rangka,ms_item.id_item,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,
			tr_sales_order_gc_nosin.no_mesin,tr_scan_barcode.warna AS id_warna, '' as nama_ibu, '' as tgl_ibu, tr_scan_barcode.tipe_motor AS id_tipe_kendaraan, ms_warna.warna,tr_spk_gc.*
			FROM tr_sales_order_gc_nosin
			INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc 
			INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc			
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
			INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
			WHERE tr_sales_order_gc.id_sales_order_gc = '$id_so' AND tr_sales_order_gc_nosin.no_mesin = '$no_mesin'");
		$query4 = $this->db->query("SELECT tr_bantuan_bbn_luar.*,tr_bantuan_bbn_luar.nama_gadis_ibu as nama_ibu, tr_bantuan_bbn_luar.tgl_lahir_ibu as tgl_ibu, tr_bantuan_bbn_luar.no_npwp as npwp,ms_pekerjaan.pekerjaan AS kerja,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,
			ms_warna.warna
			FROM tr_bantuan_bbn_luar 
			LEFT JOIN ms_pekerjaan ON ms_pekerjaan.id_pekerjaan = tr_bantuan_bbn_luar.id_pekerjaan
			INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn_luar.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_bantuan_bbn_luar.id_warna = ms_warna.id_warna
			WHERE tr_bantuan_bbn_luar.no_mesin = '$no_mesin'");
		$cek  = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$no_bastd' AND no_mesin = '$no_mesin'");
		$cek_bbn_luar  = $this->db->query("SELECT * FROM tr_bantuan_bbn_luar WHERE no_mesin = '$no_mesin'");
		if ($cek->num_rows() > 0) {
			$query2 = $this->db->query("SELECT tr_pengajuan_bbn_detail.*,ms_pekerjaan.pekerjaan AS kerja, tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_pengajuan_bbn_detail INNER JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin
						LEFT JOIN ms_pekerjaan ON tr_pengajuan_bbn_detail.pekerjaan = ms_pekerjaan.id_pekerjaan
						INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 
						INNER JOIN tr_faktur_stnk_detail ON tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk_detail.no_bastd
						WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd' AND tr_pengajuan_bbn_detail.no_mesin = '$no_mesin'");
			if ($query2->num_rows() > 0) {
				$data['dt_so'] = $query2;
			} else {
				$query5 = $this->db->query("
				SELECT tr_pengajuan_bbn_detail.*,ms_pekerjaan.pekerjaan AS kerja, tr_bantuan_bbn_luar.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_pengajuan_bbn_detail INNER JOIN tr_bantuan_bbn_luar ON tr_pengajuan_bbn_detail.no_mesin = tr_bantuan_bbn_luar.no_mesin
						LEFT JOIN ms_pekerjaan ON tr_pengajuan_bbn_detail.pekerjaan = ms_pekerjaan.id_pekerjaan
						INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn_luar.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_bantuan_bbn_luar.id_warna = ms_warna.id_warna 
						INNER JOIN tr_faktur_stnk_detail ON tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk_detail.no_bastd
						WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd' AND tr_pengajuan_bbn_detail.no_mesin = '$no_mesin'
				");
				$data['dt_so'] = $query5;
			}
			
		} elseif ($query->num_rows() > 0) {
			$data['dt_so'] = $query;
		} elseif ($query3->num_rows() > 0) {
			$data['dt_so'] = $query3;
		} else {
			$data['dt_so'] = $query4;
		}
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan", "kelurahan", "ASC");
		$data['dt_kelurahan'] = $this->db->query("SELECT * FROM ms_kelurahan 
			LEFT JOIN ms_kecamatan on ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
			ORDER BY kelurahan ASC");
		$data['no_bastd'] = $no_bastd;
		$this->template($data);
	}
	public function take_kec()
	{
		$id_kelurahan	= $this->input->post('id_kelurahan');
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();
		$kelurahan 		= strtoupper($dt_kel->kelurahan);
		$id_kecamatan = $dt_kel->id_kecamatan;
		$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();
		$kecamatan 		= strtoupper($dt_kec->kecamatan);
		$id_kabupaten = $dt_kec->id_kabupaten;
		$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();
		$kabupaten  	= strtoupper($dt_kab->kabupaten);
		$id_provinsi  = $dt_kab->id_provinsi;
		$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();
		$provinsi  		= strtoupper($dt_pro->provinsi);

		echo $id_kecamatan . "|" . $kecamatan . "|" . $id_kabupaten . "|" . $kabupaten . "|" . $id_provinsi . "|" . $provinsi . "|" . $kelurahan;
	}
	public function update()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_bastd		= $this->input->post("no_bastd");
		$no_mesin		= $this->input->post("no_mesin");
		$da['no_bastd']          = $this->input->post("no_bastd");
		$da['nama_konsumen']     = $this->input->post("nama_konsumen");
		$da['alamat']            = $this->input->post("alamat");
		$da['no_mesin']          = $this->input->post("no_mesin");
		$da['no_rangka']         = $this->input->post("no_rangka");
		$da['no_faktur']         = $this->input->post("no_faktur");
		$da['id_tipe_kendaraan'] = $this->input->post("id_tipe_kendaraan");
		$da['id_warna']          = $this->input->post("id_warna");
		$da['tahun']             = $this->input->post("tahun");
		$da['biaya_bbn']         = $biaya_bbn = $this->input->post("biaya_bbn");
		$da['biaya_bbn_md_bj']         = $this->input->post("biaya_bbn_md_bj");
		$da['tgl_jual']     = $this->input->post("tgl_jual");
		$da['tempat_lahir'] = $this->input->post("tempat_lahir");
		$da['tgl_lahir']    = $this->input->post("tgl_lahir");
		$da['id_kelurahan'] = $this->input->post("id_kelurahan");
		$da['kelurahan']    = $this->input->post("kelurahan");
		$da['kecamatan']    = $this->input->post("kecamatan");
		$da['kabupaten']    = $this->input->post("kabupaten");
		$da['provinsi']     = $this->input->post("provinsi");
		$da['no_hp']        = $this->input->post("no_hp");
		$da['no_telp']      = $this->input->post("no_telp");
		$da['no_ktp']       = $this->input->post("no_ktp");
		$da['no_kk']        = $this->input->post("no_kk");
		$da['npwp']         = $this->input->post("no_npwp");
		$da['no_tdp']       = $this->input->post("no_tdp");
		$da['nama_ibu']     = $this->input->post("nama_ibu");
		$da['tgl_ibu']      = $this->input->post("tgl_ibu");
		$da['pekerjaan']    = $this->input->post("pekerjaan");
		$da['penghasilan']  = $this->input->post("penghasilan");
		$tgl_mohon_samsat   = $this->input->post("tgl_mohon_samsat");
		$da['tgl_mohon_samsat']  = $tgl_mohon_samsat == '' || $tgl_mohon_samsat == '0000-00-00' ? NULL : $tgl_mohon_samsat;
		$da['keterangan_d']      = $this->input->post("keterangan_d");
		$da['kekurangan']        = $this->input->post("kekurangan");

		if ($biaya_bbn != 0 or $biaya_bbn != "") {
			if ($this->input->post('sengaja') == '1')
				$da['sengaja'] = $this->input->post('sengaja');
			else $da['sengaja'] 		= "";
			$submit = $this->input->post('save');
			if ($submit == 'reject') {
				$da['reject'] = 'ya';
				$_SESSION['pesan'] 	= "Data has been rejected successfully";
			} else {
				$_SESSION['pesan'] 	= "Data has been updated successfully";
			}
			$cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$no_bastd' AND no_mesin = '$no_mesin'");
			if ($cek->num_rows() > 0) {
				$id = $cek->row();
				$this->m_admin->update("tr_pengajuan_bbn_detail", $da, "id_pengajuan_bbn_detail", $id->id_pengajuan_bbn_detail);
			} else {
				$this->m_admin->insert("tr_pengajuan_bbn_detail", $da);
			}
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengajuan_bbn_md/check?id=".$no_bastd."'>";				
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/cetak_faktur'>";
		} else {
			$_SESSION['pesan'] 		= "Biaya BBN Masih Kosong";
			$_SESSION['tipe'] 		= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function save_reject()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_bastd		= $this->input->post("no_bastd");
		$da['no_bastd'] 			= $this->input->post("no_bastd");
		$da['no_retur'] 			= $this->input->post("no_retur");
		$da['tgl_retur'] 			= $this->input->post("tgl_retur");
		$da['alasan_retur'] 	= $this->input->post("alasan_retur");
		$da['keterangan'] 		= $this->input->post("keterangan");
		$da['status_pengajuan'] = "rejected";
		$da['updated_at'] 		= $waktu;
		$da['updated_by'] 		= $login_id;
		$cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn WHERE no_bastd = '$no_bastd'");
		if ($cek->num_rows() > 0) {
			$id = $cek->row();
			$this->m_admin->update("tr_pengajuan_bbn", $da, "id_pengajuan_bbn", $id->id_pengajuan_bbn);
		} else {
			$this->m_admin->insert("tr_pengajuan_bbn", $da);
		}
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/cetak_faktur'>";
	}
	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_bastd		= $this->input->post("no_bastd");
		$no_mesin		= $this->input->post("no_mesin");
		$save				= $this->input->post("save");
		if ($save == 'save') {
			$da['no_bastd']         = $this->input->post("no_bastd");
			$no_bastd               = $this->input->post("no_bastd");
			$da['tgl_bastd']        = "";
			$id                     = $this->m_admin->getByID("tr_faktur_stnk", "no_bastd", $no_bastd)->row();
			$da['id_dealer']        = $id->id_dealer;
			$da['status_pengajuan'] = "checked";
			$cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn WHERE no_bastd = '$no_bastd'");
			if ($cek->num_rows() > 0) {
				$id = $cek->row();
				$this->m_admin->update("tr_pengajuan_bbn", $da, "id_pengajuan_bbn", $id->id_pengajuan_bbn);
			} else {
				$this->m_admin->insert("tr_pengajuan_bbn", $da);
			}
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengajuan_bbn_md'>";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/cetak_faktur'>";
		} elseif ($save == 'reject') {
			$da['no_bastd'] = $this->input->post("no_bastd");
			$no_bastd       = $this->input->post("no_bastd");
			$da['reject']   = "ya";
			$cek = $this->db->query("SELECT * FROM tr_pengajuan_bbn WHERE no_bastd = '$no_bastd'");
			$id = $cek->row();
			$this->m_admin->update("tr_pengajuan_bbn", $da, "id_pengajuan_bbn", $id->id_pengajuan_bbn);
			$_SESSION['pesan'] 	= "Data has been rejected successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengajuan_bbn_md/check?id=".$no_bastd."'>";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/cetak_faktur'>";
		} else {
			$this->m_admin->delete("tr_pengajuan_bbn_detail", "no_bastd", $no_bastd);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/pengajuan_bbn_md/check?id=".$no_bastd."'>";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/cetak_faktur'>";
		}
	}
	public function reject()
	{
		$data['isi']   = $this->page;
		$data['title'] = "Reject " . $this->title;
		$data['set']   = "reject";
		$no_bastd      = $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'");
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$this->template($data);
	}
	public function cetak_faktur()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cetak Faktur " . $this->title;
		$data['set']		= "cetak_faktur";
		$no_bastd 			= $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$no_bastd' AND tgl_mohon_samsat <> '' AND (reject = '' OR reject IS NULL)");
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$this->template($data);
	}
	public function cetak_permohonan()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cetak Permohonan STNK";
		$data['set']		= "cetak_permohonan";
		$no_bastd 			= $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$no_bastd'");
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$this->template($data);
	}
	public function cetak_pendaftaran_bpkb()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cetak Pendaftaran BPKB";
		$data['set']		= "cetak_pendaftaran_bpkb";
		$no_bastd 			= $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$no_bastd'");
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		$this->template($data);
	}
	public function cetak_tagihan_ubahnama_stnk()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cetak Tagihan Permohonan Ubah Nama STNK";
		$data['set']		= "cetak_tagihan_ubahnama_stnk";
		$no_bastd 			= $this->input->get('id');
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$no_bastd'");
		// $data['dt_tagihan'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail inner join tr_pengajuan_bbn on tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
		// 	inner join ms_dealer on tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
		// 					WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd' AND tr_pengajuan_bbn_detail.sengaja='1' order by nama_konsumen ASC ");			
		$data['dt_tagihan'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail inner join tr_pengajuan_bbn on tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
			inner join ms_dealer on tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
							WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd'");
		$this->template($data);
	}
	public function cetak_tagihan_ubahnama_stnk_act()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');

		$data['cetak'] 				= "ya";
		$data['tgl_cetak']		= $tgl;
		$this->m_admin->update("tr_pengajuan_bbn_detail", $data, "no_mesin", $id);
		$sql = $this->m_admin->getByID("tr_pengajuan_bbn_detail", "no_mesin", $id)->row();
		// public function cetak_tagihan_ubahnama_stnk_act()
		// {
		//        $no_bastd = $this->input->get('id');
		//        $sql = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail inner join tr_pengajuan_bbn on tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
		// 		inner join ms_dealer on tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
		// 						WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd'");
		if ($sql->num_rows() > 0) {
			$mpdf = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in = 'UTF-8';
			$mpdf->autoLangToFont = true;
			$data['cetak'] = 'cetak_tagihan_ubahnama_stnk';
			$data['stnk'] = $sql;
			$data['jml'] = $sql->num_rows();
			$html = $this->load->view('h1/pengajuan_bbn_md_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/pengajuan_bbn_md'>";
		}
	}
	function download_file($tgl)
	{

		$tgl 			= gmdate("dmY", time() + 60 * 60 * 7);
		$bulan 		= gmdate("mY", time() + 60 * 60 * 7);
		$folder 	= "downloads/sj/";
		$filename = $folder . $bulan;
		if (!file_exists($filename)) {
			mkdir($folder . $bulan, 0777);
		}

		$data['no'] = "GENERATE FILE SAMSAT-" . $tgl;
		$data['tgl'] = $tgl;
		$this->load->view("h1/file_samsat", $data);
	}
	public function cari_id_adm()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");

		$pr_num = $this->db->query("SELECT * FROM tr_adm_bbn ORDER BY id_adm_bbn DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_adm_bbn) - 3;
			$id 	= substr($row->id_adm_bbn, 11, 5) + 1;
			$isi 	= sprintf("%'.05d", $id);
			$kode = $th . $bln . "/ABB/" . $isi;
		} else {
			$kode = $th . $bln . "/ABB/00001";
		}
		return $kode;
	}
	public function cari_id_ads()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");

		$pr_num = $this->db->query("SELECT * FROM tr_adm_stnk ORDER BY id_adm_stnk DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_adm_stnk) - 3;
			$id 	= substr($row->id_adm_stnk, 11, 5) + 1;
			$isi 	= sprintf("%'.05d", $id);
			$kode = $th . $bln . "/ABS/" . $isi;
		} else {
			$kode = $th . $bln . "/ABS/00001";
		}
		return $kode;
	}
	public function cari_id_adj()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");

		$pr_num = $this->db->query("SELECT * FROM tr_adm_jual ORDER BY id_adm_jual DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_adm_jual) - 3;
			$id 	= substr($row->id_adm_jual, 11, 5) + 1;
			$isi 	= sprintf("%'.05d", $id);
			$kode = $th . $bln . "/ABJ/" . $isi;
		} else {
			$kode = $th . $bln . "/ABJ/00001";
		}
		return $kode;
	}
	public function cari_id_adb()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");

		$pr_num = $this->db->query("SELECT * FROM tr_adm_bpkb ORDER BY id_adm_bpkb DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_adm_bpkb) - 3;
			$id 	= substr($row->id_adm_bpkb, 11, 5) + 1;
			$isi 	= sprintf("%'.05d", $id);
			$kode = $th . $bln . "/ABP/" . $isi;
		} else {
			$kode = $th . $bln . "/ABP/00001";
		}
		return $kode;
	}
	public function download()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tanggal		= $this->input->post('tgl_mohon_samsat');
		$nama_biro_jasa = $this->input->post('nama_biro_jasa');
		$tgl 						= gmdate("dmY", time() + 60 * 60 * 7);
		$tgl_fix 				= gmdate("Y-m-d", time() + 60 * 60 * 7);
		$bulan 		= gmdate("mY", time() + 60 * 60 * 7);
		$folder 	= "downloads/sj/";
		$filename = $folder . $bulan;
		if (!file_exists($filename)) {
			mkdir($folder . $bulan, 0777);
		}
		$tmp = $this->m_admin->get_tmp();
		$rand = rand(1111, 9999);
		$id_generate = $tmp . $rand;
		$da['tgl_mohon_samsat'] = $tanggal;
		$da['nama_biro_jasa'] 	= $nama_biro_jasa;
		$da['id_generate'] 			= $id_generate;
		$da['status'] 					= "input";
		$da['created_at']				= $waktu;
		$da['created_by']				= $login_id;
		$this->m_admin->insert("tr_kirim_biro", $da);
		// $r = $this->db->query("SELECT SUM(biaya_bbn_md_bj) AS jum FROM tr_pengajuan_bbn_detail 
		// 	WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND (tr_pengajuan_bbn_detail.status_bbn = '' OR tr_pengajuan_bbn_detail.status_bbn IS NULL)")->row();
		$query = $this->db->query("SELECT tr_pengajuan_bbn_detail.*, ms_warna.warna,ms_tipe_kendaraan.tipe_ahm
						FROM tr_pengajuan_bbn_detail 
						INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna 						
						WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND (tr_pengajuan_bbn_detail.status_bbn = '' OR tr_pengajuan_bbn_detail.status_bbn IS NULL)");

		foreach ($query->result() as $isi) {
			$cek_biaya_bbn_md_bj = $this->db->query("SELECT id_tipe_kendaraan, tahun_produksi , biaya_bbn , biaya_instansi FROM ms_bbn_biro WHERE id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tahun_produksi = '$isi->tahun'");
			if ($cek_biaya_bbn_md_bj->num_rows() > 0) {
				$biaya_bbn_md_bj = 1;
				$gt = $cek_biaya_bbn_md_bj->row();
				$cek_faktur = $this->db->query("SELECT no_mesin, id_sales_order FROM tr_faktur_stnk INNER JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
					WHERE tr_faktur_stnk.no_bastd = '$isi->no_bastd'")->row()->id_sales_order;
				$rt = explode("-", $cek_faktur);
				if ($rt[0] == 'GC') {
					$cek_plat = $this->db->query("SELECT tr_prospek_gc.jenis FROM tr_sales_order_gc_nosin
						INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc 
						INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
						INNER JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
						WHERE tr_sales_order_gc_nosin.no_mesin = '$isi->mesin'");
					$jenis = ($cek_plat->num_rows() > 0) ? $cek_plat->row()->jenis : "" ;
					if($jenis=="Instansi"){
						$tipe_customer = "Instansi";
						$biaya_bbn_md_bj = $gt->biaya_instansi;
					}else{
						$tipe_customer = "Customer Umum";
						$biaya_bbn_md_bj = $gt->biaya_bbn;
					}
				} else {
					$tipe_customer = "Customer Umum";
					$biaya_bbn_md_bj = $gt->biaya_bbn;
				}
				/*
				if ($tipe_customer == 'Customer Umum') {
					$biaya_bbn_md_bj = $gt->biaya_bbn;
				} elseif ($tipe_customer == 'Instansi') {
					$biaya_bbn_md_bj = $gt->biaya_instansi;
				}*/
			} else {
				$biaya_bbn_md_bj = 0;
			}
			$this->db->query("UPDATE tr_pengajuan_bbn_detail SET biaya_bbn_md_bj='$biaya_bbn_md_bj' WHERE no_mesin = '$isi->no_mesin'");
		}
		$r = $this->db->query("SELECT SUM(biaya_bbn_md_bj) AS jum FROM tr_pengajuan_bbn_detail 
			WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal'")->row();

		$fee = $this->db->query("SELECT * FROM ms_fee_bbn")->row()->fee_biro;
		$tot_unit = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_pengajuan_bbn_detail 
			WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND cetak='ya'")->row()->jum;
		$ds['id_adm_bbn'] 			= $this->cari_id_adm();
		$ds['tgl_faktur'] 			= $tgl_fix;
		$ds['tgl_mohon_samsat'] = $tanggal;
		// $ds['total'] 						= $r->jum + $tot_unit;
		$ds['total'] 						= $r->jum + ($fee * $tot_unit);
		$ds['total_unit']				= $tot_unit;
		$ds['nama_biro_jasa'] 	= $nama_biro_jasa;
		$ds['status_adm']				= "input";
		$ds['created_at']				= $waktu;
		$ds['created_by']				= $login_id;
		$this->m_admin->insert("tr_adm_bbn", $ds);
		$r = $this->db->query("SELECT COUNT(no_bastd) AS jum FROM tr_pengajuan_bbn_detail 
			WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND (tr_pengajuan_bbn_detail.status_bbn = '' OR tr_pengajuan_bbn_detail.status_bbn IS NULL)")->row();
		$h1 = $this->db->query("SELECT * FROM ms_setting_h1 WHERE id_setting_h1 = '1'");
		if ($h1->num_rows() > 0) {
			$j = $h1->row();
			$biaya_s = $j->biaya_stnk;
			$biaya_p = $j->biaya_plat;
			$biaya_j = $j->biaya_penjualan;
		} else {
			$biaya_s = 0;
			$biaya_p = 0;
			$biaya_j = 0;
		}
		$dt['id_adm_stnk'] 			= $this->cari_id_ads();
		$dt['tgl_faktur'] 			= $tgl_fix;
		$dt['tgl_mohon_samsat'] = $tanggal;
		$dt['nama_biro_jasa'] 	= $nama_biro_jasa;
		$dt['total'] 						= $r->jum * ($biaya_s + $biaya_p);
		$dt['status_adm']				= "input";
		$dt['created_at']				= $waktu;
		$dt['created_by']				= $login_id;
		$this->m_admin->insert("tr_adm_stnk", $dt);

		$dj['id_adm_jual'] 			= $this->cari_id_adj();
		$dj['tgl_faktur'] 			= $tgl_fix;
		$dj['tgl_mohon_samsat'] = $tanggal;
		$dj['nama_biro_jasa'] 	= $nama_biro_jasa;
		$dj['total'] 						= $r->jum * $biaya_j;
		$dj['status_jual']			= "input";
		$dj['created_at']				= $waktu;
		$dj['created_by']				= $login_id;
		$this->m_admin->insert("tr_adm_jual", $dj);


		$r = $this->db->query("SELECT COUNT(no_bastd) AS jum FROM tr_pengajuan_bbn_detail 
			WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND (tr_pengajuan_bbn_detail.status_bbn = '' OR tr_pengajuan_bbn_detail.status_bbn IS NULL)")->row();
		$h1 = $this->db->query("SELECT * FROM ms_setting_h1 WHERE id_setting_h1 = '1'");
		if ($h1->num_rows() > 0) {
			$j = $h1->row();
			$biaya_g = $j->biaya_bpkb;
		} else {
			$biaya_g = 0;
		}
		$dw['id_adm_bpkb'] 			= $this->cari_id_adb();
		$dw['tgl_faktur'] 			= $tgl_fix;
		$dw['tgl_mohon_samsat'] = $tanggal;
		$dw['total'] 						= $r->jum * $biaya_g;
		$dw['status_adm']				= "input";
		$dw['nama_biro_jasa'] 	= $nama_biro_jasa;
		$dw['created_at']				= $waktu;
		$dw['created_by']				= $login_id;
		$this->m_admin->insert("tr_adm_bpkb", $dw);

		$data['no'] = "GENERATE FILE SAMSAT-" . $tanggal;
		$data['tanggal'] = $tanggal;
		$data['id_generate'] = $id_generate;
		$this->load->view("h1/file_samsat", $data);
	}
	public function generateDetail()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tanggal		= $this->input->post('tgl_mohon_samsat');
		$data['tanggal']		= $this->input->post('tgl_mohon_samsat');
		$nama_biro_jasa = $this->input->post('nama_biro_jasa');
		$tgl 			= gmdate("dmY", time() + 60 * 60 * 7);
		$bulan 		= gmdate("mY", time() + 60 * 60 * 7);
		$data['detail'] = $this->db->query("SELECT tr_pengajuan_bbn_detail.*, ms_warna.warna,ms_tipe_kendaraan.tipe_ahm
						FROM tr_pengajuan_bbn_detail 
						INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON tr_pengajuan_bbn_detail.id_warna = ms_warna.id_warna 						
						WHERE tr_pengajuan_bbn_detail.tgl_mohon_samsat = '$tanggal' AND tr_pengajuan_bbn_detail.cetak = 'ya' 
						AND (tr_pengajuan_bbn_detail.status_bbn = '' OR tr_pengajuan_bbn_detail.status_bbn IS NULL)");
		$this->load->view("h1/t_generatepengajuan_bbn_md", $data);
	}
	public function generate()
	{
		$r = $this->input->get('r');
		$data['isi']    = isset($r) ? $r : $this->page;
		$data['r'] = isset($r) ? $r : $this->page;
		$data['title']	= isset($r) ? 'Cetak Faktur' : $this->title;
		$data['set']		= "generate";
		$this->template($data);
	}
	public function cetak_faktur_act_old()
	{
		$tgl      = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu    = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id = $this->session->userdata('id_user');
		$tabel    = $this->tables;
		$pk       = $this->pk;
		$id       = $this->input->get('no');
		$id2      = $this->input->get('b');
		$row = $this->db->get_where('tr_pengajuan_bbn_detail', ['no_mesin' => $id])->row();
		$data['cetak_faktur_ke'] = $row->cetak_faktur_ke + 1;
		$data['cetak']           = "ya";
		$data['tgl_cetak']       = $tgl;
		$this->m_admin->update("tr_pengajuan_bbn_detail", $data, "no_mesin", $id);
		//$r = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$id)->row();
		$r = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail 
            WHERE tr_pengajuan_bbn_detail.no_mesin = '$id' and tr_pengajuan_bbn_detail.no_bastd = '$id2'")->row();
		$dealer = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
			WHERE tr_faktur_stnk.no_bastd = '$r->no_bastd'")->row();
		$pdf = new FPDF('p', 'mm', array(202.5, 279.4));
		$pdf->AddPage();
		// head	  
		//$pdf->Image(base_url().'/assets/panel/images/Scan_Faktur.jpg', 0, 0, 202.5);
		$pdf->SetFont('COURIER', '', 12);
		$pdf->SetXY(12, 55);
		$pdf->Cell(190, 5, $dealer->nama_dealer, 0, 1, 'C');
		$pdf->SetFont('COURIER', '', 11);
		// $pdf->SetXY(30,61);
		// $pdf->Cell(80, 5, $r->no_faktur, 0, 1, 'C');
		$pdf->SetXY(136, 61);
		$pdf->Cell(80, 5, date('d-m-Y', strtotime($r->tgl_mohon_samsat)), 0, 1, 'C');
		$pdf->SetXY(85, 78);
		$pdf->Cell(100, 5, strtoupper($r->nama_konsumen), 0, 1, 'L');
		$pdf->SetXY(85, 86);


		//$pdf->Cell(20, 4, 'KODE', 1, 1, 'C');


		//$pdf->MultiCell(170, 5, $r->alamat.$r->alamat.$r->alamat.$r->alamat.$r->alamat, 1, 1);
		$pdf->MultiCell(170, 5, strtoupper($r->alamat), 0, 1);

		$pdf->SetX(85);
		$getKel = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan='$r->id_kelurahan'");
		$kel = $getKel->num_rows() > 0 ? $getKel->row()->kelurahan : '';
		$pdf->MultiCell(170, 5, strtoupper($kel) . ' - KEC.' . strtoupper($r->kecamatan), 0, 1);
		$pdf->SetX(85);
		$pdf->MultiCell(170, 5, strtoupper($r->kabupaten), 0, 1);
		//$pdf->Multicell(100, 4, $r->alamat.'wfrewg regreg grreg gregre greger grehjrgre ggreger ergreg regergre gregew ', 0, 1);
		$pdf->SetXY(85, 103);
		$pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
		$pdf->Output();
	}
	// public function cetak_tagihan_ubahnama_stnk_act(){
	// 	$tgl 				= gmdate("y-m-d", time()+60*60*7);
	// 	$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id		= $this->session->userdata('id_user');
	// 	$tabel			= $this->tables;
	// 	$pk 				= $this->pk;		
	// 	$id 				= $this->input->get('id');				

	// 	$data['cetak'] 				= "ya";		
	// 	$data['tgl_cetak']		= $tgl;						
	// 	//$this->m_admin->update("tr_pengajuan_bbn_detail",$data,"no_mesin",$id);	
	// 	$r = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$id)->row();

	// 	$pdf = new FPDF('p','mm','A4');
	//    $pdf->AddPage();
	//       // head	  
	//   $pdf->SetFont('TIMES','',14);
	//   $pdf->Cell(190, 5, 'PT SINAR SENTOSA PRIMATAMA (PAL 6)-CETAK TAGIHAN UBAH NAMA STNK', 1, 1, 'C');
	//   $pdf->SetFont('TIMES','',12);
	//   $pdf->Cell(150, 5, ''.date('d-m-Y').'', 0, 1, 'R');	  
	//   $pdf->Cell(100, 5, $r->nama_konsumen, 0, 1, 'L');
	//   $pdf->Cell(100, 5, $r->alamat, 0, 1, 'L');
	//   $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
	//   $pdf->Output(); 
	// }
	public function cetak_permohonan_act()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$no_mesin 				= $this->input->get('no');
		$no_bastd 				= $this->input->get('b');

		$data['cetak'] 				= "ya";
		$data['tgl_cetak']		= $tgl;
		//$this->m_admin->update("tr_pengajuan_bbn_detail",$data,"no_mesin",$id);	
		/*
		$r = $this->db->query("SELECT tr_pengajuan_bbn_detail.*,tr_pengajuan_bbn_detail.no_mesin as mesin, ms_tipe_kendaraan.*,ms_warna.*, 
							tr_fkb.merk, tr_fkb.jenis, tr_fkb.modell, tr_fkb.tahun_produksi,tr_fkb.isi_silinder, tr_fkb.bahan_bakar  
						FROM tr_pengajuan_bbn_detail INNER JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin
						INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
						left JOIN tr_fkb ON tr_pengajuan_bbn_detail.no_mesin = tr_fkb.no_mesin_spasi
						WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd' AND tr_pengajuan_bbn_detail.no_mesin = '$no_mesin'")->row();
		*/
		$r = $this->db->query("SELECT tr_pengajuan_bbn_detail.*,tr_pengajuan_bbn_detail.no_mesin as mesin, ms_tipe_kendaraan.*,ms_warna.*, x.tahun_produksi,
			'HONDA' as merk, 'SEPEDA MOTOR' as jenis, 'SOLO' as modell, ms_tipe_kendaraan.cc_motor as isi_silinder, 'BENSIN' as bahan_bakar  
			FROM tr_pengajuan_bbn_detail 
			INNER JOIN 
			(
			select no_mesin , no_rangka , id_tipe_kendaraan , id_warna , tahun_produksi from tr_bantuan_bbn_luar tbbl where no_mesin = '$no_mesin'
			union
			select a.no_mesin , a.no_rangka , a.tipe_motor , a.warna, b.tahun_produksi from tr_scan_barcode a join tr_fkb b on a.no_mesin =b.no_mesin_spasi where a.no_mesin = '$no_mesin'
			) x on tr_pengajuan_bbn_detail.no_mesin  = x.no_mesin
			INNER JOIN ms_tipe_kendaraan ON x.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON x.id_warna = ms_warna.id_warna
			WHERE tr_pengajuan_bbn_detail.no_bastd = '$no_bastd' AND tr_pengajuan_bbn_detail.no_mesin = '$no_mesin'")->row();

		$pdf = new FPDF('p', 'mm', array(209.6, 296));
		$pdf->AddPage();
		// head	  
		//$pdf->Image(base_url().'/assets/panel/images/Scan_STNK.jpg', 0, 0, 209.6);
		$pdf->SetFont('COURIER', '', 12);
		$pdf->SetXY(12, 55);
		//$pdf->Cell(190, 5, $r->nama_dealer, 0, 1, 'C');
		$pdf->SetFont('COURIER', '', 11);
		/* Baru1
	  $pdf->SetXY(74,54);
	  $pdf->Cell(100, 6, $r->nama_konsumen, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->MultiCell(90, 5, $r->alamat, 0, 1);
	  $pdf->SetXY(74,70);
	  $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->no_telp.'/'.$r->no_hp, 0, 1, 'L');
	  $pdf->SetXY(74,86);
	  $pdf->Cell(100, 4, $r->merk, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->deskripsi_samsat, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 6, $r->jenis, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->modell, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->tahun_produksi, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->isi_silinder, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, 'MH1'.$r->no_rangka, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->mesin, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->warna_samsat, 0, 1, 'L');
	  $pdf->SetX(74);
	  $pdf->Cell(100, 5, $r->bahan_bakar, 0, 1, 'L');
*/
		// Baru2
		// $pdf->SetXY(70,56);
		// $pdf->Cell(100, 6, $r->nama_konsumen, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->MultiCell(90, 5, $r->alamat, 0, 1);
		// $pdf->SetXY(70,72);
		// $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->no_telp.'/'.$r->no_hp, 0, 1, 'L');
		// $pdf->SetXY(70,88);
		// $pdf->Cell(100, 4, $r->merk, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->deskripsi_samsat, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 6, $r->jenis, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->modell, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->tahun_produksi, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->isi_silinder, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, 'MH1'.$r->no_rangka, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->mesin, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->warna_samsat, 0, 1, 'L');
		// $pdf->SetX(70);
		// $pdf->Cell(100, 5, $r->bahan_bakar, 0, 1, 'L');
		//Lama
		$pdf->SetXY(46, 73); //+2
		$pdf->Cell(103, 5, strtoupper($r->merk), 0, 0, 'L');
		$pdf->Cell(80, 5, strtoupper($r->warna_samsat), 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->deskripsi_samsat, 0, 0, 'L');
		$pdf->Cell(80, 5, $r->bahan_bakar, 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->jenis, 0, 0, 'L');
		$pdf->Cell(80, 5, '', 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->modell, 0, 0, 'L');
		$pdf->Cell(80, 5, '', 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->tahun_produksi, 0, 0, 'L');
		$pdf->Cell(80, 5, '', 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->isi_silinder, 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, 'MH1' . $r->no_rangka, 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->mesin, 0, 1, 'L');
		$pdf->SetXY(46, 162);
		$pdf->Cell(103, 5, strtoupper($r->nama_konsumen), 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->MultiCell(55, 5, strtoupper($r->alamat), 0, 'T', false);
		$pdf->Cell(103, 5, '', 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, '', 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->no_telp, 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, '', 0, 1, 'L');
		$pdf->SetX(46);
		$pdf->Cell(103, 5, $r->no_ktp, 0, 1, 'L');
		$pdf->Output();
	}
	public function cetak_pendaftaran_bpkb_act()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('no');

		$data['cetak'] 				= "ya";
		$data['tgl_cetak']		= $tgl;
		//$this->m_admin->update("tr_pengajuan_bbn_detail",$data,"no_mesin",$id);	
		/*
		$r = $this->db->query("SELECT tr_pengajuan_bbn_detail.*,tr_pengajuan_bbn_detail.no_mesin as mesin, ms_tipe_kendaraan.*,ms_warna.*, 
							tr_fkb.merk, tr_fkb.jenis, tr_fkb.modell, tr_fkb.tahun_produksi,tr_fkb.isi_silinder, tr_fkb.bahan_bakar  
						FROM tr_pengajuan_bbn_detail INNER JOIN tr_scan_barcode ON tr_pengajuan_bbn_detail.no_mesin = tr_scan_barcode.no_mesin
						INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
						left JOIN tr_fkb ON tr_pengajuan_bbn_detail.no_mesin = tr_fkb.no_mesin_spasi
						WHERE tr_pengajuan_bbn_detail.no_mesin = '$id'")->row();
		*/
		$r = $this->db->query("SELECT tr_pengajuan_bbn_detail.*,tr_pengajuan_bbn_detail.no_mesin as mesin, ms_tipe_kendaraan.*,ms_warna.*, x.tahun_produksi,
			'HONDA' as merk, 'SEPEDA MOTOR' as jenis, 'SOLO' as modell, ms_tipe_kendaraan.cc_motor as isi_silinder, 'BENSIN' as bahan_bakar  
			FROM tr_pengajuan_bbn_detail 
			INNER JOIN 
			(
			select no_mesin , no_rangka , id_tipe_kendaraan , id_warna , tahun_produksi from tr_bantuan_bbn_luar tbbl where no_mesin ='$id'
			union
			select a.no_mesin , a.no_rangka , a.tipe_motor , a.warna, b.tahun_produksi from tr_scan_barcode a join tr_fkb b on a.no_mesin =b.no_mesin_spasi where a.no_mesin ='$id'
			) x on tr_pengajuan_bbn_detail.no_mesin  = x.no_mesin
			INNER JOIN ms_tipe_kendaraan ON x.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON x.id_warna = ms_warna.id_warna
			WHERE tr_pengajuan_bbn_detail.no_mesin ='$id'")->row();
	
		//$r = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$id)->row();
		$pdf = new FPDF('p', 'mm', array(209.8, 296));
		$pdf->AddPage();
		// head	  
		// $pdf->Image(base_url().'/assets/panel/images/Scan_BPKB.jpg', 0, 0, 209.6);
		$pdf->SetFont('COURIER', 'B', 11);
		// $pdf->SetXY(64,66);
		// $pdf->Cell(100, 4.5, $r->nama_konsumen, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->MultiCell(90, 5, $r->alamat, 0, 1);
		// $pdf->SetXY(64,84.3);
		// $pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->no_telp, 0, 1, 'L');
		// $pdf->SetXY(64,99);
		// $pdf->Cell(100, 5, 'Honda', 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->deskripsi_samsat, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->jenis, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->modell, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->tahun_produksi, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->isi_silinder, 0, 1, 'L');
		// $pdf->Cell(100, 4.2, '', 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, 'MH1'.$r->no_rangka, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 5, $r->mesin, 0, 1, 'L');

		// $pdf->SetXY(157,99.5);
		// $pdf->Cell(100, 5, $r->warna_samsat, 0, 1, 'L');
		// $pdf->SetXY(157,105);
		// $pdf->Cell(100, 5, $r->bahan_bakar, 0, 1, 'L');
		// $pdf->SetXY(64,167);
		// $pdf->Cell(100, 4.5, $r->no_faktur, 0, 1, 'L');
		// $pdf->SetX(64);
		// $pdf->Cell(100, 4, 'Astra Honda Motor', 0, 1, 'L');
		$pdf->SetXY(56, 72);
		$pdf->Cell(100, 4.5, strtoupper($r->nama_konsumen), 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->MultiCell(90, 5, strtoupper($r->alamat), 0, 1);
		$pdf->SetXY(56, 84.3);
		$pdf->Cell(100, 5, $r->no_ktp, 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, $r->no_telp, 0, 1, 'L');
		$pdf->SetXY(56, 105);
		$pdf->Cell(100, 5, 'HONDA', 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, strip_tags($r->deskripsi_samsat), 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, $r->jenis, 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, $r->modell, 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, $r->tahun_produksi, 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, $r->isi_silinder, 0, 1, 'L');
		$pdf->Cell(100, 4.2, '', 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, 'MH1' . $r->no_rangka, 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 5, $r->mesin, 0, 1, 'L');

		$pdf->SetXY(157, 105);
		$pdf->Cell(100, 5, $r->warna_samsat, 0, 1, 'L');
		$pdf->SetXY(157, 111);
		$pdf->Cell(100, 5, $r->bahan_bakar, 0, 1, 'L');
		$pdf->SetXY(56, 173);
		$pdf->Cell(100, 4.5, $r->no_faktur, 0, 1, 'L');
		$pdf->SetX(56);
		$pdf->Cell(100, 4, strtoupper('Astra Honda Motor'), 0, 1, 'L');
		$pdf->Output();

	}
}
