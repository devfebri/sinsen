<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyerahan_stnk extends CI_Controller
{

	var $tables =   "tr_konfirmasi_dokumen";
	var $folder =   "dealer";
	var $page   =		"penyerahan_stnk";
	var $pk     =   "id_konfirmasi_dokumen";
	var $title  =   "Penyerahan STNK & Plat ke Konsumen";

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
		$this->load->library('PDF_HTML');
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
		/*$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";		
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_konfirmasi_dokumen_detail INNER JOIN tr_konfirmasi_dokumen 
				ON tr_konfirmasi_dokumen.no_serah_terima = tr_konfirmasi_dokumen_detail.no_serah_terima  WHERE tr_konfirmasi_dokumen.id_dealer = '$id_dealer' 
				ORDER BY id_konfirmasi_dokumen_detail ASC");						
		$this->template($data);			*/
		redirect(site_url('dealer/penyerahan_stnk/print_preview?p=plat'), 'refresh');
	}

	public function index2()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_konfirmasi_dokumen_detail INNER JOIN tr_konfirmasi_dokumen 
				ON tr_konfirmasi_dokumen.no_serah_terima = tr_konfirmasi_dokumen_detail.no_serah_terima  WHERE tr_konfirmasi_dokumen.id_dealer = '$id_dealer' 
				ORDER BY id_konfirmasi_dokumen_detail ASC");
		$this->template($data);
	}

	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_hist'] = $this->db->query("SELECT *,kd_stnk_konsumen as kd,(select count(kd_stnk_konsumen) from tr_tandaterima_stnk_konsumen_detail WHERE kd_stnk_konsumen = kd) as jml FROM tr_tandaterima_stnk_konsumen where id_dealer='$id_dealer' ORDER BY tgl_cetak DESC limit 1500");
		$this->template($data);
	}


	public function print_preview()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "preview";
		$data['setprint']	= $this->input->get('p');
		$setprint	= $this->input->get('p');
		$id_dealer = $this->m_admin->cari_dealer();
		if (isset($_POST['search'])) {
			$search = $this->input->post('search');
			$data['search'] = $search;

			/*	$data['dt_serah'] = $this->db->query("SELECT *,tr_terima_bj.nama_konsumen,tr_terima_bj.no_rangka FROM tr_konfirmasi_dokumen_detail INNER JOIN tr_konfirmasi_dokumen ON tr_konfirmasi_dokumen.no_serah_terima = tr_konfirmasi_dokumen_detail.no_serah_terima left join tr_terima_bj on tr_konfirmasi_dokumen_detail.no_mesin = tr_terima_bj.no_mesin LEFT JOIN tr_pengajuan_bbn_detail on tr_konfirmasi_dokumen_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
				left join tr_sales_order on tr_konfirmasi_dokumen_detail.no_mesin = tr_sales_order.no_mesin
				left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
				LEFT join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
				WHERE (tr_terima_bj.nama_konsumen like '%$search%' OR tr_konfirmasi_dokumen_detail.no_mesin like '%$search%' OR tr_terima_bj.no_rangka like '%$search%' OR ms_finance_company.finance_company like '%$search%') AND tr_konfirmasi_dokumen.id_dealer = '$id_dealer' AND tr_konfirmasi_dokumen_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_tandaterima_stnk_konsumen_detail JOIN tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen AND tr_tandaterima_stnk_konsumen.jenis_cetak ='$setprint')

				ORDER BY id_konfirmasi_dokumen_detail ASC");
*/
			if ($setprint == 'plat') {
				$data['dt_serah'] = $this->db->query("SELECT 
				no_srut,
				tr_terima_bj.nama_konsumen,
				no_plat,
				no_bpkb,
				no_stnk,
				tr_terima_bj.no_mesin,
				tr_terima_bj.no_rangka,
				CASE WHEN tr_sales_order.id_sales_order IS NOT NULL THEN tr_sales_order.id_sales_order WHEN so_gc.id_sales_order_gc IS NOT NULL then so_gc.id_sales_order_gc else 'Bantuan BBN' END AS id_sales_order
				FROM tr_penyerahan_plat_detail
				left JOIN tr_scan_barcode bc ON bc.no_mesin=tr_penyerahan_plat_detail.no_mesin
					left join tr_penyerahan_plat on tr_penyerahan_plat_detail.no_serah_plat = tr_penyerahan_plat.no_serah_plat
					left join tr_sales_order on tr_penyerahan_plat_detail.no_mesin = tr_sales_order.no_mesin
					left join tr_terima_bj on tr_penyerahan_plat_detail.no_mesin = tr_terima_bj.no_mesin
					LEFT JOIN tr_srut ON tr_srut.no_mesin = tr_penyerahan_plat_detail.no_mesin
					left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
					LEFT join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					LEFT JOIN tr_sales_order_gc_nosin so_gc ON tr_penyerahan_plat_detail.no_mesin = so_gc.no_mesin
					LEFT JOIN tr_spk_gc spk_gc ON so_gc.no_spk_gc = spk_gc.no_spk_gc
					LEFT JOIN ms_finance_company fc_gc ON spk_gc.id_finance_company = fc_gc.id_finance_company
					WHERE (tr_terima_bj.nama_konsumen like '%$search%' OR tr_penyerahan_plat_detail.no_mesin like '%$search%' OR tr_sales_order.no_rangka like '%$search%' OR ms_finance_company.finance_company like '%$search%') AND tr_penyerahan_plat.id_dealer = '$id_dealer' AND tr_penyerahan_plat_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_tandaterima_stnk_konsumen_detail JOIN tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen AND tr_tandaterima_stnk_konsumen.jenis_cetak ='$setprint')  AND tr_penyerahan_plat_detail.status_nosin='terima'
					GROUP BY tr_terima_bj.no_mesin
				");
			} elseif ($setprint == 'stnk') {
				$data['dt_serah'] = $this->db->query("SELECT 
				no_srut,
				tr_terima_bj.nama_konsumen,
				no_plat,
				no_bpkb,
				no_stnk,
				tr_terima_bj.no_mesin,
				tr_terima_bj.no_rangka,
				CASE WHEN tr_sales_order.id_sales_order IS NOT NULL THEN tr_sales_order.id_sales_order WHEN so_gc.id_sales_order_gc IS NOT NULL then so_gc.id_sales_order_gc else 'Bantuan BBN' END AS id_sales_order
				FROM tr_penyerahan_stnk_detail
					left join tr_penyerahan_stnk on tr_penyerahan_stnk_detail.no_serah_stnk = tr_penyerahan_stnk.no_serah_stnk
					LEFT JOIN tr_scan_barcode bc ON bc.no_mesin=tr_penyerahan_stnk_detail.no_mesin
					left join tr_sales_order on tr_penyerahan_stnk_detail.no_mesin = tr_sales_order.no_mesin
					left join tr_terima_bj on tr_penyerahan_stnk_detail.no_mesin = tr_terima_bj.no_mesin
					LEFT JOIN tr_srut ON tr_srut.no_mesin = tr_penyerahan_stnk_detail.no_mesin
					left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
					LEFT join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					LEFT JOIN tr_sales_order_gc_nosin so_gc ON tr_penyerahan_stnk_detail.no_mesin = so_gc.no_mesin
					LEFT JOIN tr_spk_gc spk_gc ON so_gc.no_spk_gc = spk_gc.no_spk_gc
					LEFT JOIN ms_finance_company fc_gc ON spk_gc.id_finance_company = fc_gc.id_finance_company
					WHERE (tr_terima_bj.nama_konsumen like '%$search%' OR tr_penyerahan_stnk_detail.no_mesin like '%$search%' OR bc.no_rangka like '%$search%' OR ms_finance_company.finance_company like '%$search%' OR fc_gc.finance_company like '%$search%') AND tr_penyerahan_stnk.id_dealer = '$id_dealer' AND tr_penyerahan_stnk_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_tandaterima_stnk_konsumen_detail JOIN tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen AND tr_tandaterima_stnk_konsumen.jenis_cetak ='$setprint') AND tr_penyerahan_stnk_detail.status_nosin='terima'
					GROUP BY tr_terima_bj.no_mesin
				");
			} elseif ($setprint == 'bpkb') {
				$data['dt_serah'] = $this->db->query("SELECT 
				no_srut,
				tr_terima_bj.nama_konsumen,
				no_plat,
				no_bpkb,
				no_stnk,
				tr_terima_bj.no_mesin,
				tr_terima_bj.no_rangka,
				CASE WHEN tr_sales_order.id_sales_order IS NOT NULL THEN tr_sales_order.id_sales_order WHEN so_gc.id_sales_order_gc IS NOT NULL then so_gc.id_sales_order_gc else 'Bantuan BBN' END AS id_sales_order
				FROM tr_penyerahan_bpkb_detail
				left JOIN tr_scan_barcode bc ON bc.no_mesin=tr_penyerahan_bpkb_detail.no_mesin
				LEFT JOIN tr_penyerahan_bpkb ON tr_penyerahan_bpkb_detail.no_serah_bpkb = tr_penyerahan_bpkb.no_serah_bpkb
				LEFT JOIN tr_terima_bj ON tr_penyerahan_bpkb_detail.no_mesin = tr_terima_bj.no_mesin
				LEFT JOIN tr_sales_order ON tr_penyerahan_bpkb_detail.no_mesin = tr_sales_order.no_mesin
				LEFT JOIN tr_srut ON tr_srut.no_mesin = tr_penyerahan_bpkb_detail.no_mesin
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
				LEFT JOIN tr_sales_order_gc_nosin so_gc ON tr_penyerahan_bpkb_detail.no_mesin = so_gc.no_mesin
				LEFT JOIN tr_spk_gc spk_gc ON so_gc.no_spk_gc = spk_gc.no_spk_gc
				LEFT JOIN ms_finance_company fc_gc ON spk_gc.id_finance_company = fc_gc.id_finance_company
					WHERE (tr_terima_bj.nama_konsumen like '%$search%' OR tr_penyerahan_bpkb_detail.no_mesin like '%$search%' OR bc.no_rangka like '%$search%' OR ms_finance_company.finance_company like '%$search%' OR fc_gc.finance_company like '%$search%') AND tr_penyerahan_bpkb.id_dealer = '$id_dealer' AND tr_penyerahan_bpkb_detail.no_mesin NOT IN(SELECT no_mesin FROM tr_tandaterima_stnk_konsumen_detail JOIN tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen AND tr_tandaterima_stnk_konsumen.jenis_cetak ='$setprint')  AND tr_penyerahan_bpkb_detail.status_nosin='terima'
					GROUP BY tr_terima_bj.no_mesin
				");
			} elseif ($setprint == 'srut') {
				$data['dt_serah'] = $this->db->query("SELECT 
				no_srut,
				tr_terima_bj.nama_konsumen,
				no_plat,
				no_bpkb,
				no_stnk,
				bc.no_mesin,
				bc.no_rangka,
				CASE WHEN tr_sales_order.id_sales_order IS NOT NULL THEN tr_sales_order.id_sales_order ELSE so_gc.id_sales_order_gc END AS id_sales_order
				
				FROM tr_penyerahan_srut 
				INNER JOIN tr_penyerahan_srut_detail ON tr_penyerahan_srut_detail.no_serah_terima = tr_penyerahan_srut.no_serah_terima
				LEFT JOIN tr_srut ON tr_penyerahan_srut_detail.no_mesin = tr_srut.no_mesin
				LEFT JOIN tr_scan_barcode bc ON bc.no_mesin=tr_penyerahan_srut_detail.no_mesin
				LEFT JOIN tr_terima_bj ON tr_penyerahan_srut_detail.no_mesin = tr_terima_bj.no_mesin
				LEFT JOIN tr_sales_order ON tr_penyerahan_srut_detail.no_mesin = tr_sales_order.no_mesin
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
				LEFT JOIN tr_sales_order_gc_nosin so_gc ON tr_penyerahan_srut_detail.no_mesin = so_gc.no_mesin
				LEFT JOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc = spk_gc.no_spk_gc
				LEFT JOIN ms_finance_company fc_gc ON fc_gc.id_finance_company = spk_gc.id_finance_company				
				WHERE (tr_terima_bj.nama_konsumen like '%$search%' 
						OR tr_penyerahan_srut_detail.no_mesin like '%$search%' 
						OR bc.no_rangka like '%$search%' 
						OR fc_gc.finance_company like '%$search%' 
						OR ms_finance_company.finance_company like '%$search%' ) 
						AND tr_penyerahan_srut.id_dealer = '$id_dealer' 
						AND tr_penyerahan_srut_detail.no_mesin IN (SELECT no_mesin FROM tr_terima_srut_detail JOIN tr_terima_srut ON tr_terima_srut_detail.no_serah_terima=tr_terima_srut_detail.no_serah_terima WHERE id_dealer=$id_dealer)
						AND tr_penyerahan_srut_detail.no_mesin 
						NOT IN(SELECT no_mesin FROM tr_tandaterima_stnk_konsumen_detail 
						JOIN tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen AND tr_tandaterima_stnk_konsumen.jenis_cetak ='$setprint')
						GROUP BY tr_terima_bj.no_mesin");
			}
		} else {
			$data['dt_serah'] = null;
			$data['search'] = '';
		}
		$this->template($data);
	}

	public function cek_kd_tt($setprint)
	{
		$tgl 						= date("d");
		$cek_tgl					= date("Y-m");
		$th 						= date("Y");
		$bln 						= date("m");
		$id_dealer = $this->m_admin->cari_dealer();
		$set = $setprint;
		$setprint					= 'TT_' . strtoupper($setprint);
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer' ");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
		} else {
			$get_dealer = '';
		}

		$pr_num 				= $this->db->query("SELECT *,mid(tgl_cetak,6,2)as bln FROM tr_tandaterima_stnk_konsumen WHERE LEFT(tgl_cetak,7) = '$cek_tgl' AND id_dealer='$id_dealer' AND jenis_cetak='$set' ORDER BY kd_stnk_konsumen DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$id = explode('/', $row->kd_stnk_konsumen);
			if (count($id) > 1) {
				if ($bln == $row->bln) {
					//$isi 	= $th.'/'.$bln.'/'.$get_dealer.'/BASTK/'.sprintf("%'.04d",$id[4]+1);
					$kode = $th . '/' . $bln . '/' . $get_dealer . '/' . sprintf("%'.04d", $id[3] + 1) . '/' . $setprint;
				} else {
					$kode = $th . '/' . $bln . '/' . $get_dealer . '/0001/' . $setprint;
				}
			} else {
				$kode = $th . '/' . $bln . '/' . $get_dealer . '/0001/' . $setprint;
			}
		} else {
			$kode = $th . '/' . $bln . '/' . $get_dealer . '/0001/' . $setprint;
		}
		return $kode;
	}

	public function cetak()
	{

		$post = $this->input->post();
		// send_json($post);
		$check      = $this->input->post('check');
		$no_mesin   = $this->input->post('no_mesin_checked');
		$disetujui  = $this->input->post('disetujui');
		$diterima   = $this->input->post('diterima');
		$diserahkan = $this->input->post('diserahkan');
		$setprint   = $this->input->post('setprint');
		$id_dealer  = $this->m_admin->cari_dealer();
		$waktu      = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');

		if (is_array($no_mesin)) {
			foreach ($no_mesin as $key => $val) {
				$nomes[$key] = "'$val'";
			}
		}

		if (!isset($nomes)) {
			$_SESSION['pesan'] 	= "Belum ada no. mesin yang dipilih !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/penyerahan_stnk/print_preview?p=" . $setprint . "'>";
		} else {
			$mesin = implode(',', $nomes);
			if ($setprint == 'plat') {
				$dt = $this->db->query("SELECT *,tr_terima_bj.no_mesin as nosin,tr_terima_bj.nama_konsumen,tr_terima_bj.no_rangka FROM tr_penyerahan_plat_detail
					left join tr_penyerahan_plat on tr_penyerahan_plat_detail.no_serah_plat = tr_penyerahan_plat.no_serah_plat
					left join tr_sales_order on tr_penyerahan_plat_detail.no_mesin = tr_sales_order.no_mesin
					left join tr_terima_bj on tr_penyerahan_plat_detail.no_mesin = tr_terima_bj.no_mesin
					LEFT JOIN tr_srut ON tr_srut.no_mesin = tr_penyerahan_plat_detail.no_mesin

					left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
					LEFT join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					left join ms_tipe_kendaraan on tr_terima_bj.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					left join ms_warna on tr_terima_bj.id_warna = ms_warna.id_warna
					WHERE tr_penyerahan_plat_detail.no_mesin IN ($mesin)
					GROUP BY tr_terima_bj.no_mesin 	
					ORDER BY tr_terima_bj.nama_konsumen ASC
				");
			} elseif ($setprint == 'stnk') {
				$dt = $this->db->query("SELECT *,tr_terima_bj.no_mesin as nosin,tr_terima_bj.nama_konsumen,tr_terima_bj.no_rangka FROM tr_penyerahan_stnk_detail
					left join tr_penyerahan_stnk on tr_penyerahan_stnk_detail.no_serah_stnk = tr_penyerahan_stnk.no_serah_stnk
					left join tr_sales_order on tr_penyerahan_stnk_detail.no_mesin = tr_sales_order.no_mesin
					left join tr_terima_bj on tr_penyerahan_stnk_detail.no_mesin = tr_terima_bj.no_mesin
					LEFT JOIN tr_srut ON tr_srut.no_mesin = tr_penyerahan_stnk_detail.no_mesin

					left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
					LEFT join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					left join ms_tipe_kendaraan on tr_terima_bj.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			left join ms_warna on tr_terima_bj.id_warna = ms_warna.id_warna
					 WHERE tr_penyerahan_stnk_detail.no_mesin IN ($mesin)
					GROUP BY tr_terima_bj.no_mesin
					ORDER BY tr_terima_bj.nama_konsumen ASC
				");
			} elseif ($setprint == 'bpkb') {
				$dt = $this->db->query("SELECT *,tr_terima_bj.no_mesin as nosin,tr_terima_bj.nama_konsumen,tr_terima_bj.no_rangka FROM tr_penyerahan_bpkb_detail
					left join tr_penyerahan_bpkb on tr_penyerahan_bpkb_detail.no_serah_bpkb = tr_penyerahan_bpkb.no_serah_bpkb
					left join tr_sales_order on tr_penyerahan_bpkb_detail.no_mesin = tr_sales_order.no_mesin
					left join tr_terima_bj on tr_penyerahan_bpkb_detail.no_mesin = tr_terima_bj.no_mesin
					LEFT JOIN tr_srut ON tr_srut.no_mesin = tr_penyerahan_bpkb_detail.no_mesin

					left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
					LEFT join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					left join ms_tipe_kendaraan on tr_terima_bj.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					left join ms_warna on tr_terima_bj.id_warna = ms_warna.id_warna
					WHERE tr_penyerahan_bpkb_detail.no_mesin IN ($mesin)
					GROUP BY tr_terima_bj.no_mesin 	
					ORDER BY tr_terima_bj.nama_konsumen ASC
				");
			} elseif ($setprint == 'srut') {
				$dt = $this->db->query("SELECT *, tpsd.no_mesin AS nosin FROM tr_terima_srut_detail AS tpsd
					LEFT JOIN tr_srut ON tpsd.no_mesin=tr_srut.no_mesin
					left join tr_sales_order on tpsd.no_mesin = tr_sales_order.no_mesin
					left join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
					left join tr_terima_bj on tpsd.no_mesin = tr_terima_bj.no_mesin
					left join ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					left join ms_warna on tr_spk.id_warna = ms_warna.id_warna
					WHERE tpsd.no_mesin IN ($mesin)
				");
			}



			if ($dt->num_rows() > 0) {
				$row = $dt->row();
				$jml = $dt->num_rows();
				$dl = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' ")->row();

				$kode_stnk = $data['kd_stnk_konsumen'] = $this->cek_kd_tt($setprint);
				$data['id_dealer']        = $id_dealer;
				$data['disetujui']        = $disetujui;
				$data['diterima']         = $diterima;
				$data['diserahkan']       = $diserahkan;
				$data['jenis_id']         = $this->input->post('jenis_id');
				$data['no_id']            = $this->input->post('no_id');

				if ($setprint == 'stnk') {
					$data['tgl_terima_stnk'] = $this->input->post('tgl_terima_stnk');
				}
				if ($setprint == 'plat') {
					$data['tgl_terima_plat'] = $this->input->post('tgl_terima_plat');
				}
				if ($setprint == 'srut') {
					$data['tgl_terima_srut'] = $this->input->post('tgl_terima_srut');
				}
				if ($setprint == 'bpkb') {
					$data['tgl_terima_bpkb'] = $this->input->post('tgl_terima_bpkb');
					$data['diperiksa']		  = $this->input->post('diperiksa');
					$diperiksa		  = $this->input->post('diperiksa');
				}
				$data['no_hp_penerima']		  = $this->input->post('no_hp_penerima');
				$data['jenis_cetak']		  = $setprint;
				$data['tgl_cetak']		  = $waktu;
				$data['created_by']		  = $login_id;
				$data['created_at']		  = $waktu;
				foreach ($dt->result() as $key => $val) {
					$detail[$key] = array(
						'kd_stnk_konsumen' => $data['kd_stnk_konsumen'],
						'no_mesin'          => $val->nosin,
						'no_rangka'         => $val->no_rangka,
						'id_tipe_kendaraan' => $val->id_tipe_kendaraan,
						'id_warna'          => $val->id_warna,
						'no_stnk'           => $val->no_stnk,
						'no_plat'           => $val->no_plat,
						'no_bpkb'           => $val->no_bpkb,
						'no_srut'           => $val->no_srut,
						'id_finance_company' => isset($val->id_finance_company) ? $val->id_finance_company : ''

					);
				}
				$tes = ['data' => $data, 'detail' => $detail];
				// send_json($tes);
				$this->db->trans_begin();
				$this->db->insert('tr_tandaterima_stnk_konsumen', $data);
				$this->db->insert_batch('tr_tandaterima_stnk_konsumen_detail', $detail);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					redirect('dealer/penyerahan_stnk/print_preview?p=' . $setprint, 'refresh');
				} else {
					$this->db->trans_commit();
					$pdf = new PDF_HTML('p', 'mm', 'A4');
					$pdf->AddPage();
					$pdf->SetFont('ARIAL', 'B', 12);
					if ($setprint == 'bpkb') {
						$pdf->SetMargins(7, 10, 7);
						$pdf->Cell(190, 7, 'TANDA TERIMA ' . strtoupper($setprint), 1, 1, 'C');
					} else {
						$pdf->SetMargins(10, 10, 10);
						$pdf->Cell(190, 7, 'TANDA TERIMA ' . strtoupper($setprint), 1, 1, 'C');
					}


					$pdf->SetFont('ARIAL', '', 10);
					$pdf->Ln(2);
					$pdf->Cell(20, 5, 'Nomor', 0, 0, 'L');
					$pdf->Cell(90, 5, ': ' . $data['kd_stnk_konsumen'], 0, 1, 'L');
					$pdf->Cell(20, 5, 'Tanggal', 0, 0, 'L');
					$pdf->Cell(90, 5, ': ' . date('d-m-Y'), 0, 0, 'L');
					if ($setprint == 'plat') {
						$pdf->SetFont('ARIAL', '', 10);
						$tgl_indo = tgl_indo(date('Y-m-d'), ' ');
						$hari = nama_hari(date('Y-m-d'));
						$pdf->Ln(10);
						$pdf->MultiCell(190, 7, "Pada hari ini $hari, $tgl_indo, telah diterima dari $dl->nama_dealer, $jml pasang plat dengan rincian sebagai berikut :", 0, 1);
						$pdf->Ln(2);
						$pdf->SetFont('ARIAL', 'B', 10);
						$pdf->Cell(10, 6, 'No.', 1, 0, 'C');
						$pdf->Cell(35, 6, 'Nama Konsumen', 1, 0, 'C');
						$pdf->Cell(23, 6, 'No Mesin', 1, 0, 'C');
						$pdf->Cell(24, 6, 'No Rangka', 1, 0, 'C');
						$pdf->Cell(40, 6, 'Tipe', 1, 0, 'C');
						$pdf->Cell(35, 6, 'Warna', 1, 0, 'C');
						$pdf->Cell(25, 6, 'No Polisi', 1, 1, 'C');
						$pdf->SetFont('ARIAL', '', 8);
						$no = 1;
						foreach ($dt->result() as $rs) {
							$sb = $this->db->get_where('tr_scan_barcode', ['no_mesin' => $rs->nosin]);
							if ($sb->num_rows() > 0) {
								$no_rangka = $sb->row()->no_rangka;
							} else {
								$no_rangka = $rs->no_rangka;
							}
							$pdf->Cell(10, 5, $no, 1, 0, 'C');
							$pdf->Cell(35, 5, $rs->nama_konsumen, 1, 0, 'C');
							$pdf->Cell(23, 5, $rs->nosin, 1, 0, 'C');
							$pdf->Cell(24, 5, $no_rangka, 1, 0, 'C');
							$pdf->Cell(40, 5, $rs->tipe_ahm, 1, 0, 'C');
							$pdf->Cell(35, 5, $rs->warna, 1, 0, 'C');
							$pdf->Cell(25, 5, $rs->no_plat, 1, 1, 'C');
							$no++;
						}
						$pdf->Ln(6);

						$pdf->Cell(63.3, 6, 'Disetujui Oleh,', 0, 0, 'C');
						$pdf->Cell(63.3, 6, 'Diserahkan Oleh,', 0, 0, 'C');
						$pdf->Cell(63.3, 6, 'Diterima Oleh,', 0, 1, 'C');
						$pdf->Ln(13);
						$pdf->Cell(63.3, 6, $disetujui, 0, 0, 'C');
						$pdf->Cell(63.3, 6, $diserahkan, 0, 0, 'C');
						$pdf->Cell(63.3, 6, $diterima, 0, 1, 'C');
					} elseif ($setprint == 'stnk') {
						$pdf->SetFont('ARIAL', '', 10);
						$tgl_indo = tgl_indo(date('Y-m-d'), ' ');
						$hari = nama_hari(date('Y-m-d'));
						$pdf->Ln(10);
						$pdf->MultiCell(190, 7, "Pada hari ini $hari, $tgl_indo, telah diterima dari $dl->nama_dealer, $jml lembar STNK dengan rincian sebagai berikut :", 0, 1);
						$pdf->Ln(2);
						$pdf->SetFont('ARIAL', 'B', 9);
						$pdf->Cell(7, 6, 'No.', 1, 0, 'C');
						$pdf->Cell(35, 6, 'Nama Konsumen', 1, 0, 'C');
						$pdf->Cell(22, 6, 'No Mesin', 1, 0, 'C');
						$pdf->Cell(23, 6, 'No Rangka', 1, 0, 'C');
						$pdf->Cell(40, 6, 'Tipe', 1, 0, 'C');
						$pdf->Cell(30, 6, 'Warna', 1, 0, 'C');
						$pdf->Cell(18, 6, 'No Polisi', 1, 0, 'C');
						$pdf->Cell(20, 6, 'No STNK', 1, 1, 'C');
						$pdf->SetFont('ARIAL', '', 8);
						$no = 1;
						foreach ($dt->result() as $rs) {
							$sb = $this->db->get_where('tr_scan_barcode', ['no_mesin' => $rs->nosin]);
							if ($sb->num_rows() > 0) {
								$no_rangka = $sb->row()->no_rangka;
							} else {
								$no_rangka = $rs->no_rangka;
							}
							$pdf->Cell(7, 5, $no, 1, 0, 'C');
							$pdf->Cell(35, 5, $rs->nama_konsumen, 1, 0, 'C');
							$pdf->Cell(22, 5, $rs->nosin, 1, 0, 'C');
							$pdf->Cell(23, 5, $no_rangka, 1, 0, 'C');
							$pdf->Cell(40, 5, $rs->tipe_ahm, 1, 0, 'C');
							$pdf->Cell(30, 5, $rs->warna, 1, 0, 'C');
							$pdf->Cell(18, 5, $rs->no_plat, 1, 0, 'C');
							$pdf->Cell(20, 5, $rs->no_stnk, 1, 1, 'C');
							$no++;
						}
						$pdf->Ln(6);

						$pdf->Cell(63.3, 6, 'Disetujui Oleh,', 0, 0, 'C');
						$pdf->Cell(63.3, 6, 'Diserahkan Oleh,', 0, 0, 'C');
						$pdf->Cell(63.3, 6, 'Diterima Oleh,', 0, 1, 'C');
						$pdf->Ln(13);
						$pdf->Cell(63.3, 6, $disetujui, 0, 0, 'C');
						$pdf->Cell(63.3, 6, $diserahkan, 0, 0, 'C');
						$pdf->Cell(63.3, 6, $diterima, 0, 1, 'C');
					} elseif ($setprint == 'bpkb') {
						$pdf->SetFont('ARIAL', '', 10);
						$tgl_indo = tgl_indo(date('Y-m-d'), ' ');
						$hari = nama_hari(date('Y-m-d'));
						$pdf->Ln(10);
						$pdf->MultiCell(196, 7, "Pada hari ini $hari, $tgl_indo, telah diterima dari $dl->nama_dealer, $jml lembar BPKB dengan rincian sebagai berikut :", 0, 1);
						$pdf->Ln(2);
						$pdf->SetFont('ARIAL', 'B', 9);
						$pdf->Cell(7, 6, 'No.', 1, 0, 'C');
						$pdf->Cell(32, 6, 'Nama Konsumen', 1, 0, 'C');
						$pdf->Cell(22, 6, 'No Mesin', 1, 0, 'C');
						$pdf->Cell(23, 6, 'No Rangka', 1, 0, 'C');
						$pdf->Cell(40, 6, 'Tipe', 1, 0, 'C');
						$pdf->Cell(30, 6, 'Warna', 1, 0, 'C');
						// $pdf->Cell(18,6,'No Polisi',1,0,'C');
						$pdf->Cell(22, 6, 'No BPKB', 1, 0, 'C');
						$pdf->Cell(23, 6, 'No FAKTUR', 1, 1, 'C');
						$pdf->SetFont('ARIAL', '', 8);
						$no = 1;
						foreach ($dt->result() as $rs) {
							$sb = $this->db->get_where('tr_scan_barcode', ['no_mesin' => $rs->nosin]);
							if ($sb->num_rows() > 0) {
								$no_rangka = $sb->row()->no_rangka;
							} else {
								$no_rangka = $rs->no_rangka;
							}
							$fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$rs->nosin'");
							if ($fkb->num_rows() > 0) {
								$fkb = $fkb->row()->nomor_faktur;
							} else {
								$fkb = '';
							}
							$pdf->Cell(7, 5, $no, 1, 0, 'C');
							$pdf->Cell(32, 5, $rs->nama_konsumen, 1, 0, 'C');
							$pdf->Cell(22, 5, $rs->nosin, 1, 0, 'C');
							$pdf->Cell(23, 5, $no_rangka, 1, 0, 'C');
							$pdf->Cell(40, 5, $rs->tipe_ahm, 1, 0, 'C');
							$pdf->Cell(30, 5, $rs->warna, 1, 0, 'C');
							// $pdf->Cell(18,5,$rs->no_plat,1,0,'C');
							$pdf->Cell(22, 5, $rs->no_bpkb, 1, 0, 'C');
							$pdf->Cell(23, 5, $fkb, 1, 1, 'C');
							$no++;
						}
						$pdf->Ln(6);

						$pdf->Cell(47.5, 6, 'Disetujui Oleh,', 0, 0, 'C');
						$pdf->Cell(47.5, 6, 'Diperiksa Oleh,', 0, 0, 'C');
						$pdf->Cell(47.5, 6, 'Diserahkan Oleh,', 0, 0, 'C');
						$pdf->Cell(47.5, 6, 'Diterima Oleh,', 0, 1, 'C');
						$pdf->Ln(13);
						$pdf->Cell(47.5, 6, $disetujui, 0, 0, 'C');
						$pdf->Cell(47.5, 6, $diperiksa, 0, 0, 'C');
						$pdf->Cell(47.5, 6, $diserahkan, 0, 0, 'C');
						$pdf->Cell(47.5, 6, $diterima, 0, 1, 'C');
					} elseif ($setprint == 'srut') {
						$this->cetak_srut($kode_stnk);
					}
					if ($setprint != 'srut') {
						$pdf->Output();
					}
				}
			}
		}
	}
	// public function tes_srut()
	// {
	// 	$this->cetak_srut('2019/07/PSB/0005/TT_SRUT');
	// }
	function cetak_srut($kode_stnk)
	{
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
		$mpdf->autoLangToFont           = true;
		$data['nomor']                  = $kode_stnk;
		//Cash 
		$cek_cash = $this->db->query("SELECT *,tr_tandaterima_stnk_konsumen_detail.no_mesin as nosin FROM tr_tandaterima_stnk_konsumen_detail
			left join tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
			LEFT JOIN tr_sales_order ON tr_tandaterima_stnk_konsumen_detail.no_mesin=tr_sales_order.no_mesin
			LEFT JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			left join ms_dealer on tr_tandaterima_stnk_konsumen.id_dealer = ms_dealer.id_dealer
			left join ms_tipe_kendaraan on tr_tandaterima_stnk_konsumen_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			left join ms_warna on tr_tandaterima_stnk_konsumen_detail.id_warna = ms_warna.id_warna
			left join tr_terima_bj on tr_tandaterima_stnk_konsumen_detail.no_mesin = tr_terima_bj.no_mesin
			WHERE tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen='$kode_stnk'
			AND tr_spk.jenis_beli='Cash'
			group by tr_terima_bj.no_mesin
			ORDER BY tr_tandaterima_stnk_konsumen_detail.id DESC");
		if ($cek_cash->num_rows() > 0) {
			$data['set']  = 'srut_cash';
			$srut 		  = $cek_cash->result();
			foreach ($srut as $rs) {
				$data['srut'] = $rs;
				$html   	 = $this->load->view('dealer/penyerahan_stnk_cetak', $data, true);
				$mpdf->AddPage();
				$mpdf->WriteHTML($html);
			}
		}

		//Kredit
		$cek_kredit = $this->db->query("SELECT *,tr_tandaterima_stnk_konsumen_detail.no_mesin as nosin FROM tr_tandaterima_stnk_konsumen_detail
			left join tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
			LEFT JOIN tr_sales_order ON tr_tandaterima_stnk_konsumen_detail.no_mesin=tr_sales_order.no_mesin
			LEFT JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			left join ms_dealer on tr_tandaterima_stnk_konsumen.id_dealer = ms_dealer.id_dealer
			left join ms_tipe_kendaraan on tr_tandaterima_stnk_konsumen_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			left join ms_warna on tr_tandaterima_stnk_konsumen_detail.id_warna = ms_warna.id_warna
			left join tr_terima_bj on tr_tandaterima_stnk_konsumen_detail.no_mesin = tr_terima_bj.no_mesin
			WHERE tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen='$kode_stnk'
			AND tr_spk.jenis_beli='Kredit'
			group by tr_terima_bj.no_mesin
			ORDER BY tr_tandaterima_stnk_konsumen_detail.id DESC");

		if ($cek_kredit->num_rows() > 0) {
			$data['set']  = 'srut_kredit';
			$data['srut'] = $cek_kredit->result();
			$html         = $this->load->view('dealer/penyerahan_stnk_cetak', $data, true);
			$mpdf->AddPage();
			$mpdf->WriteHTML($html);
		}
		$output       = 'cetak_penyerahan_srut.pdf';
		$mpdf->Output("$output", 'I');
	}
	
	public function cetak_ulang()
	{

		$check = $this->input->post('check');
		$kd_stnk_konsumen = $this->input->get('id');
		$setprint = $this->input->post('setprint');
		$id_dealer = $this->m_admin->cari_dealer();
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');

		$dt = $this->db->query("SELECT *,tr_tandaterima_stnk_konsumen_detail.no_mesin as nosin FROM tr_tandaterima_stnk_konsumen_detail
			left join tr_tandaterima_stnk_konsumen on tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen = tr_tandaterima_stnk_konsumen.kd_stnk_konsumen
			left join ms_dealer on tr_tandaterima_stnk_konsumen.id_dealer = ms_dealer.id_dealer
			left join ms_tipe_kendaraan on tr_tandaterima_stnk_konsumen_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			left join ms_warna on tr_tandaterima_stnk_konsumen_detail.id_warna = ms_warna.id_warna
			left join tr_terima_bj on tr_tandaterima_stnk_konsumen_detail.no_mesin = tr_terima_bj.no_mesin
			WHERE tr_tandaterima_stnk_konsumen_detail.kd_stnk_konsumen='$kd_stnk_konsumen'
			group by tr_terima_bj.no_mesin
			ORDER BY tr_terima_bj.nama_konsumen ASC");
		if ($dt->num_rows() > 0) {

			$row = $dt->row();
			$jml = $dt->num_rows();
			$setprint = $row->jenis_cetak;

			$this->db->trans_commit();
			$pdf = new PDF_HTML('p', 'mm', 'A4');
			$pdf->AddPage();
			$pdf->SetFont('ARIAL', 'B', 12);
			if ($setprint == 'bpkb') {
				$pdf->SetMargins(7, 10, 7);
				$pdf->Cell(190, 7, 'TANDA TERIMA ' . strtoupper($setprint), 1, 1, 'C');
			} else {
				$pdf->SetMargins(10, 10, 10);
				$pdf->Cell(190, 7, 'TANDA TERIMA ' . strtoupper($setprint), 1, 1, 'C');
			}


			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Ln(2);
			$pdf->Cell(20, 5, 'Nomor', 0, 0, 'L');
			$pdf->Cell(90, 5, ': ' . $row->kd_stnk_konsumen, 0, 1, 'L');
			$pdf->Cell(20, 5, 'Tanggal', 0, 0, 'L');
			$pdf->Cell(90, 5, ': ' . date('d-m-Y'), 0, 0, 'L');

			if ($setprint == 'plat') {
				$pdf->SetFont('ARIAL', '', 10);
				$tgl_ = explode(' ', $row->tgl_cetak);
				$tgl = $tgl_[0];
				$tgl_indo = tgl_indo($tgl);
				$hari = nama_hari($tgl);
				$pdf->Ln(10);
				$pdf->MultiCell(190, 7, "Pada hari ini $hari, $tgl_indo, telah diterima dari $row->nama_dealer, $jml pasang plat dengan rincian sebagai berikut :", 0, 1);
				$pdf->Ln(2);
				$pdf->SetFont('ARIAL', 'B', 10);
				$pdf->Cell(10, 6, 'No.', 1, 0, 'C');
				$pdf->Cell(35, 6, 'Nama Konsumen', 1, 0, 'C');
				$pdf->Cell(30, 6, 'No Mesin', 1, 0, 'C');
				$pdf->Cell(50, 6, 'Type', 1, 0, 'C');
				$pdf->Cell(40, 6, 'Warna', 1, 0, 'C');
				$pdf->Cell(25, 6, 'No Polisi', 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', 8);
				$no = 1;
				foreach ($dt->result() as $rs) {
					$cellWidth = 35; //lebar sel
					$cellHeight = 5; //tinggi sel satu baris normal

					//periksa apakah teksnya melibihi kolom?
					if ($pdf->GetStringWidth($rs->nama_konsumen) < $cellWidth) {
						//jika tidak, maka tidak melakukan apa-apa
						$line = 1;
					} else {
						//jika ya, maka hitung ketinggian yang dibutuhkan untuk sel akan dirapikan
						//dengan memisahkan teks agar sesuai dengan lebar sel
						//lalu hitung berapa banyak baris yang dibutuhkan agar teks pas dengan sel

						$textLength = strlen($rs->nama_konsumen);	//total panjang teks
						$errMargin = 5;		//margin kesalahan lebar sel, untuk jaga-jaga
						$startChar = 0;		//posisi awal karakter untuk setiap baris
						$maxChar = 0;			//karakter maksimum dalam satu baris, yang akan ditambahkan nanti
						$textArray = array();	//untuk menampung data untuk setiap baris
						$tmpString = "";		//untuk menampung teks untuk setiap baris (sementara)

						while ($startChar < $textLength) { //perulangan sampai akhir teks
							//perulangan sampai karakter maksimum tercapai
							while (
								$pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
								($startChar + $maxChar) < $textLength
							) {
								$maxChar++;
								$tmpString = substr($rs->nama_konsumen, $startChar, $maxChar);
							}
							//pindahkan ke baris berikutnya
							$startChar = $startChar + $maxChar;
							//kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
							array_push($textArray, $tmpString);
							//reset variabel penampung
							$maxChar = 0;
							$tmpString = '';
						}
						//dapatkan jumlah baris
						$line = count($textArray);
					}
					$pdf->Cell(10, ($line * $cellHeight), $no, 1, 0, 'C');
					$xPos = $pdf->GetX();
					$yPos = $pdf->GetY();
					$pdf->MultiCell($cellWidth, $cellHeight, $rs->nama_konsumen, 1, 'L');
					$pdf->SetXY($xPos + $cellWidth, $yPos);
					$pdf->Cell(30, ($line * $cellHeight), $rs->nosin, 1, 0, 'C');
					$pdf->Cell(50, ($line * $cellHeight), $rs->tipe_ahm, 1, 0, 'C');
					$pdf->Cell(40, ($line * $cellHeight), $rs->warna, 1, 0, 'C');
					$pdf->Cell(25, ($line * $cellHeight), $rs->no_plat, 1, 1, 'C');
					$no++;
				}
				$pdf->Ln(6);

				$pdf->Cell(63.3, 6, 'Disetujui Oleh,', 0, 0, 'C');
				$pdf->Cell(63.3, 6, 'Diserahkan Oleh,', 0, 0, 'C');
				$pdf->Cell(63.3, 6, 'Diterima Oleh,', 0, 1, 'C');
				$pdf->Ln(13);
				$pdf->Cell(63.3, 6, $row->disetujui, 0, 0, 'C');
				$pdf->Cell(63.3, 6, $row->diserahkan, 0, 0, 'C');
				$pdf->Cell(63.3, 6, $row->diterima, 0, 1, 'C');
			} elseif ($setprint == 'stnk') {
				$pdf->SetFont('ARIAL', '', 10);
				$tgl_ = explode(' ', $row->tgl_cetak);
				$tgl_indo = tgl_indo($tgl_[0]);
				$hari = nama_hari($tgl_[0]);
				$pdf->Ln(10);
				$pdf->MultiCell(190, 7, "Pada hari ini $hari, $tgl_indo, telah diterima dari $row->nama_dealer, $jml lembar STNK dengan rincian sebagai berikut :", 0, 1);
				$pdf->Ln(2);
				$pdf->SetFont('ARIAL', 'B', 9);
				$pdf->Cell(7, 6, 'No.', 1, 0, 'C');
				$pdf->Cell(35, 6, 'Nama Konsumen', 1, 0, 'C');
				$pdf->Cell(27, 6, 'No Mesin', 1, 0, 'C');
				$pdf->Cell(40, 6, 'Type', 1, 0, 'C');
				$pdf->Cell(35, 6, 'Warna', 1, 0, 'C');
				$pdf->Cell(20, 6, 'No Polisi', 1, 0, 'C');
				$pdf->Cell(25, 6, 'No STNK', 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', 8);
				$no = 1;
				foreach ($dt->result() as $rs) {
					$cellWidth = 35; //lebar sel
					$cellHeight = 5; //tinggi sel satu baris normal

					//periksa apakah teksnya melibihi kolom?
					if ($pdf->GetStringWidth($rs->nama_konsumen) < $cellWidth) {
						//jika tidak, maka tidak melakukan apa-apa
						$line = 1;
					} else {
						//jika ya, maka hitung ketinggian yang dibutuhkan untuk sel akan dirapikan
						//dengan memisahkan teks agar sesuai dengan lebar sel
						//lalu hitung berapa banyak baris yang dibutuhkan agar teks pas dengan sel

						$textLength = strlen($rs->nama_konsumen);	//total panjang teks
						$errMargin = 5;		//margin kesalahan lebar sel, untuk jaga-jaga
						$startChar = 0;		//posisi awal karakter untuk setiap baris
						$maxChar = 0;			//karakter maksimum dalam satu baris, yang akan ditambahkan nanti
						$textArray = array();	//untuk menampung data untuk setiap baris
						$tmpString = "";		//untuk menampung teks untuk setiap baris (sementara)

						while ($startChar < $textLength) { //perulangan sampai akhir teks
							//perulangan sampai karakter maksimum tercapai
							while (
								$pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
								($startChar + $maxChar) < $textLength
							) {
								$maxChar++;
								$tmpString = substr($rs->nama_konsumen, $startChar, $maxChar);
							}
							//pindahkan ke baris berikutnya
							$startChar = $startChar + $maxChar;
							//kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
							array_push($textArray, $tmpString);
							//reset variabel penampung
							$maxChar = 0;
							$tmpString = '';
						}
						//dapatkan jumlah baris
						$line = count($textArray);
					}

					$pdf->Cell(7, ($line * $cellHeight), $no, 1, 0, 'C');
					$xPos = $pdf->GetX();
					$yPos = $pdf->GetY();
					$pdf->MultiCell($cellWidth, $cellHeight, $rs->nama_konsumen, 1, 'L');
					$pdf->SetXY($xPos + $cellWidth, $yPos);
					$pdf->Cell(27, ($line * $cellHeight), $rs->nosin, 1, 0, 'C');
					$pdf->Cell(40, ($line * $cellHeight), $rs->tipe_ahm, 1, 0, 'C');
					$pdf->Cell(35, ($line * $cellHeight), $rs->warna, 1, 0, 'C');
					$pdf->Cell(20, ($line * $cellHeight), $rs->no_plat, 1, 0, 'C');
					$pdf->Cell(25, ($line * $cellHeight), $rs->no_stnk, 1, 1, 'C');
					$no++;
				}
				$pdf->Ln(6);

				$pdf->Cell(63.3, 6, 'Disetujui Oleh,', 0, 0, 'C');
				$pdf->Cell(63.3, 6, 'Diserahkan Oleh,', 0, 0, 'C');
				$pdf->Cell(63.3, 6, 'Diterima Oleh,', 0, 1, 'C');
				$pdf->Ln(13);
				$pdf->Cell(63.3, 6, $row->disetujui, 0, 0, 'C');
				$pdf->Cell(63.3, 6, $row->diserahkan, 0, 0, 'C');
				$pdf->Cell(63.3, 6, $row->diterima, 0, 1, 'C');
			} elseif ($setprint == 'bpkb') {
				$pdf->SetFont('ARIAL', '', 10);
				$tgl_ = explode(' ', $row->tgl_cetak);
				$tgl_indo = tgl_indo($tgl_[0]);
				$hari = nama_hari($tgl_[0]);
				$pdf->Ln(10);
				$pdf->MultiCell(196, 7, "Pada hari ini $hari, $tgl_indo, telah diterima dari $row->nama_dealer, $jml lembar BPKB dengan rincian sebagai berikut :", 0, 1);
				$pdf->Ln(2);
				$pdf->SetFont('ARIAL', 'B', 9);
				$pdf->Cell(7, 6, 'No.', 1, 0, 'C');
				$pdf->Cell(35, 6, 'Nama Konsumen', 1, 0, 'C');
				$pdf->Cell(22, 6, 'No Mesin', 1, 0, 'C');
				$pdf->Cell(40, 6, 'Type', 1, 0, 'C');
				$pdf->Cell(32, 6, 'Warna', 1, 0, 'C');
				$pdf->Cell(18, 6, 'No Polisi', 1, 0, 'C');
				$pdf->Cell(20, 6, 'No BPKB', 1, 0, 'C');
				$pdf->Cell(23, 6, 'No FAKTUR', 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', 8);
				$no = 1;
				foreach ($dt->result() as $rs) {
					$fkb = $this->db->query("SELECT nomor_faktur from tr_fkb WHERE no_mesin_spasi='$rs->nosin'");
					if ($fkb->num_rows() > 0) {
						$fkb = $fkb->row()->nomor_faktur;
					} else {
						$fkb = '';
					}

					$cellWidth = 35; //lebar sel
					$cellwidthrapi = 33; // dipakai utk lebar sel dengan konsumen PT / nama konsumen kepanjangan
					$cellHeight = 5; //tinggi sel satu baris normal
					//periksa apakah teksnya melibihi kolom?
					if ($pdf->GetStringWidth($rs->nama_konsumen) < $cellwidthrapi) {
						//jika tidak, maka tidak melakukan apa-apa
						$line = 1;
					} else {
						//jika ya, maka hitung ketinggian yang dibutuhkan untuk sel akan dirapikan
						//dengan memisahkan teks agar sesuai dengan lebar sel
						//lalu hitung berapa banyak baris yang dibutuhkan agar teks pas dengan sel

						$textLength = strlen($rs->nama_konsumen);	//total panjang teks
						$errMargin = 5;		//margin kesalahan lebar sel, untuk jaga-jaga
						$startChar = 0;		//posisi awal karakter untuk setiap baris
						$maxChar = 0;			//karakter maksimum dalam satu baris, yang akan ditambahkan nanti
						$textArray = array();	//untuk menampung data untuk setiap baris
						$tmpString = "";		//untuk menampung teks untuk setiap baris (sementara)
						
						while ($startChar < $textLength) { //perulangan sampai akhir teks
							//perulangan sampai karakter maksimum tercapai
							while (
								$pdf->GetStringWidth($tmpString) < ($cellwidthrapi - $errMargin) &&
								($startChar + $maxChar) < $textLength
							) {
								$maxChar++;
								$tmpString = substr($rs->nama_konsumen, $startChar, $maxChar);
							}
							//pindahkan ke baris berikutnya
							$startChar = $startChar + $maxChar;
							//kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
							array_push($textArray, $tmpString);
							//reset variabel penampung
							$maxChar = 0;
							$tmpString = '';
						}
						//dapatkan jumlah baris
						$line = count($textArray);
					}
					
					$pdf->Cell(7, ($line * $cellHeight), $no, 1, 0, 'C');
					$xPos = $pdf->GetX();
					$yPos = $pdf->GetY();
					$pdf->MultiCell($cellWidth, $cellHeight, $rs->nama_konsumen, 1, 'L');
					$pdf->SetXY($xPos + $cellWidth, $yPos);
					$pdf->Cell(22, ($line * $cellHeight), $rs->nosin, 1, 0, 'C');
					$pdf->Cell(40, ($line * $cellHeight), $rs->tipe_ahm, 1, 0, 'C');
					$pdf->Cell(32, ($line * $cellHeight), $rs->warna, 1, 0, 'C');
					$pdf->Cell(18, ($line * $cellHeight), $rs->no_plat, 1, 0, 'C');
					$pdf->Cell(20, ($line * $cellHeight), $rs->no_bpkb, 1, 0, 'C');
					$pdf->Cell(23, ($line * $cellHeight), $fkb, 1, 1, 'C');
					$no++;
				}
				$pdf->Ln(6);

				$pdf->Cell(47.5, 6, 'Disetujui Oleh,', 0, 0, 'C');
				$pdf->Cell(47.5, 6, 'Diperiksa Oleh,', 0, 0, 'C');
				$pdf->Cell(47.5, 6, 'Diserahkan Oleh,', 0, 0, 'C');
				$pdf->Cell(47.5, 6, 'Diterima Oleh,', 0, 1, 'C');
				$pdf->Ln(13);
				$pdf->Cell(47.5, 6, $row->disetujui, 0, 0, 'C');
				$pdf->Cell(47.5, 6, $row->diperiksa, 0, 0, 'C');
				$pdf->Cell(47.5, 6, $row->diserahkan, 0, 0, 'C');
				$pdf->Cell(47.5, 6, $row->diterima, 0, 1, 'C');
			} elseif ($setprint == 'srut') {
				$this->cetak_srut($kd_stnk_konsumen);
			}
			if ($setprint != 'srut') {
				$pdf->Output();
			}
		}
	}

	public function t_pu()
	{
		$id = $this->input->post('id_penerimaan_unit');
		$dq = "SELECT * FROM tr_penerimaan_unit_detail
						WHERE id_penerimaan_unit = '$id'";
		$data['dt_pu'] = $this->db->query($dq);
		$this->load->view('dealer/t_pu', $data);
	}


	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$data['dt_jenis_customer'] = $this->m_admin->getSortCond("ms_jenis_customer", "jenis_customer", "ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan", "tipe_ahm", "ASC");
		$data['dt_no_mesin'] = $this->m_admin->getSort("tr_scan_barcode", "no_mesin", "ASC");
		$data['dt_no_rangka'] = $this->m_admin->getSort("tr_scan_barcode", "no_rangka", "ASC");
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}
	public function approve()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Approve Survey Leasing";
		$data['set']	= "approve";
		$this->template($data);
	}
	public function reject()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Reject Survey Leasing";
		$data['set']	= "reject";
		$this->template($data);
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
	public function take_sales()
	{
		$id_karyawan_dealer	= $this->input->post('id_karyawan_dealer');
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
	public function save_pu()
	{
		$id_penerimaan_unit		= $this->input->post('id_penerimaan_unit');
		$no_shipping_list			= $this->input->post('no_shipping_list');
		$data['id_penerimaan_unit']		= $this->input->post('id_penerimaan_unit');
		$data['no_shipping_list']			= $this->input->post('no_shipping_list');
		$c = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id_penerimaan_unit' AND no_shipping_list = '$no_shipping_list'");
		if ($c->num_rows() > 0) {
			echo "no";
		} else {
			$cek2 = $this->m_admin->insert("tr_penerimaan_unit_detail", $data);
			echo "ok";
		}
	}
	public function delete_pu()
	{
		$id = $this->input->post('id_penerimaan_unit_detail');
		$this->db->query("DELETE FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit_detail = '$id'");
		echo "nihil";
	}
	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$data['id_penerimaan_unit'] 	= $this->input->post('id_penerimaan_unit');
			$data['no_antrian'] 					= $this->input->post('no_antrian');
			$data['no_surat_jalan'] 			= $this->input->post('no_surat_jalan');
			$data['tgl_surat_jalan'] 			= $this->input->post('tgl_surat_jalan');
			$data['ekspedisi'] 						= $this->input->post('ekspedisi');
			$data['no_polisi'] 						= $this->input->post('no_polisi');
			$data['nama_driver'] 					= $this->input->post('nama_driver');
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['gudang'] 							= $this->input->post('gudang');
			$data['tgl_penerimaan'] 			= $this->input->post('tgl_penerimaan');
			if ($this->input->post('active') == '1') $data['active'] = $this->input->post('active');
			else $data['active'] 		= "";
			$data['created_at']				= $waktu;
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel, $data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/penerimaan_unit/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cetak_striker()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cetak Ulang Stiker";
		$no_shipping_list 	= $this->input->get("id");
		$data['set']		= "cetak";
		$data['dt_shipping_list'] = $this->db->query("SELECT * FROM tr_shipping_list INNER JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna 
					WHERE tr_shipping_list.no_shipping_list = '$no_shipping_list'");
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function list_ksu()
	{
		$data['isi']    = $this->page;
		$data['title']	= "List KSU";
		$data['set']	= "list_ksu";
		//$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);
	}
}
