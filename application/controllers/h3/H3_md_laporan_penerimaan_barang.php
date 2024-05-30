<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_laporan_penerimaan_barang extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_laporan_penerimaan_barang";
	protected $title  = "Laporan Penerimaan Barang";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_penerimaan_barang_model', 'penerimaan_barang');
		$this->load->model('H3_md_penerimaan_barang_items_model', 'penerimaan_barang_items');
		$this->load->model('H3_md_penerimaan_barang_jumlah_koli_model', 'penerimaan_barang_jumlah_koli');
		$this->load->model('H3_md_penerimaan_barang_surat_jalan_ahm_model', 'penerimaan_barang_surat_jalan_ahm');
		$this->load->model('H3_md_penerimaan_barang_reasons_model', 'penerimaan_barang_reasons');
		$this->load->model('H3_md_invoice_ekspedisi_model', 'invoice_ekspedisi');
		$this->load->model('H3_md_invoice_ekspedisi_item_model', 'invoice_ekspedisi_item');
		$this->load->model('h3_md_claim_main_dealer_ke_ahm_model', 'claim_main_dealer_ke_ahm');
		$this->load->model('h3_md_claim_main_dealer_ke_ahm_item_model', 'claim_main_dealer_ke_ahm_item');
		$this->load->model('H3_md_berita_acara_penerimaan_barang_model', 'berita_acara');
		$this->load->model('H3_md_berita_acara_penerimaan_barang_items_model', 'berita_acara_items');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
		$this->load->model('H3_md_lokasi_rak_model', 'lokasi_rak');
		$this->load->model('H3_md_lokasi_rak_parts_model', 'lokasi_rak_parts');
		$this->load->model('H3_md_berita_acara_penerimaan_barang_model', 'berita_acara');
		$this->load->model('H3_md_berita_acara_penerimaan_barang_items_model', 'berita_acara_items');

		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
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

	public function index()
	{
		$data['set']	= "index";
		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function list_biaya_ekspedisi()
	{
		$this->db
			->select('o.jenis')
			->from('ms_h3_md_ongkos_angkut_part as o')
			->where('o.id_vendor', $this->input->get('id_vendor'))
			->group_by('o.jenis');

		send_json($this->db->get()->result());
	}

	public function harga_ekspedisi()
	{
		$query = $this->db
			->from('ms_h3_md_ongkos_angkut_part as o')
			->where('o.id_vendor', $this->input->get('id_vendor'))
			->where('o.type_mobil', $this->input->get('type_mobil'))
			->where('o.start_date <=', date('Y-m-d'))
			->order_by('o.start_date', 'desc')
			->order_by('o.created_at', 'desc')
			->limit(1)
			->get()->row();

		$data = [];
		if ($query != null) {
			$data['jenis_ongkos_angkut_part'] = $query->jenis;
			$data['per_satuan_ongkos_angkut_part'] = $query->per_satuan;
			$data['harga_ongkos_angkut_part'] = $query->harga;
		} else {
			$data['jenis_ongkos_angkut_part'] = '';
			$data['per_satuan_ongkos_angkut_part'] = 0;
			$data['harga_ongkos_angkut_part'] = 0;
		}

		send_json($data);
	}

	public function get_parts_query_old()
	{
		$no_surat_jalan_ekspedisi = $this->input->post('no_surat_jalan_ekspedisi');
		$list_nomor_karton = $this->input->post('list_nomor_karton');
		// $list_nomor_karton2 = $this->input->post('list_nomor_karton2');

		$this->db
			->select('pbi.id as id_penerimaan_barang_item')
			->select('pbi.no_penerimaan_barang')
			->select('pbi.no_surat_jalan_ekspedisi')
			->select('date_format(ps.packing_sheet_date, "%d-%m-%Y") as packing_sheet_date')
			->select('psli.surat_jalan_ahm')
			->select('psli.surat_jalan_ahm_int')
			->select('ps.id as packing_sheet_number_int')
			->select('ps.packing_sheet_number')
			->select('psp.no_doos as nomor_karton')
			->select('psp.no_doos_int as nomor_karton_int')
			->select('psp.no_po_int')
			->select('psp.no_po')
			->select('psp.id_part')
			->select('psp.id_part_int')
			->select('p.nama_part')
			->select('p.harga_dealer_user as harga')
			->select('psp.packing_sheet_quantity')
			->select('
			case 
				when pbi.id is not null then pbi.qty_diterima
				else 0
			end as qty_diterima
		', false)
			->select('
			case
				when pbi.id is not null then pbi.id_lokasi_rak
				else ""
			end as id_lokasi_rak
		', false)
			->select('
			case
				when pbi.id is not null then lr.kode_lokasi_rak
				else ""
			end as kode_lokasi_rak
		', false)
			->select("
			case
				when pbi.id is not null then ( lr.kapasitas - lr.kapasitas_terpakai )
				else 0
			end as kapasitas_tersedia
		", false)
			->select('
			case
				when pbi.id is not null then pbi.id_lokasi_rak_temporary
				else ""
			end as id_lokasi_rak_temporary
		', false)
			->select('
			case
				when pbi.id is not null then lr_temp.kode_lokasi_rak
				else ""
			end as kode_lokasi_rak_temporary
		', false)
			->select('IFNULL(pbi.tersimpan, 0) as tersimpan')
			->select('IFNULL(pbi.proses_claim_ahm, 0) as proses_claim_ahm')
			->select('IFNULL(pbi.proses_claim_ekspedisi, 0) as proses_claim_ekspedisi')
			->select('0 as checked_for_kekurangan_part')
			->select('0 as edit')
			->from('tr_h3_md_psl_items as psli')
			->join('tr_h3_md_ps as ps', 'ps.id = psli.packing_sheet_number_int')
			->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = ps.id')
			// ->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number = ps.packing_sheet_number')
			->join('ms_part as p', 'p.id_part_int = psp.id_part_int')
			->join('tr_h3_md_penerimaan_barang_items as pbi', "(pbi.id_part = psp.id_part and pbi.packing_sheet_number = psp.packing_sheet_number and pbi.nomor_karton = psp.no_doos and pbi.no_po = psp.no_po)", 'left')
			->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = pbi.no_surat_jalan_ekspedisi', 'left')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = pbi.id_lokasi_rak', 'left')
			->join('ms_h3_md_lokasi_rak as lr_temp', 'lr_temp.id = pbi.id_lokasi_rak_temporary', 'left')
			->where('psp.packing_sheet_quantity >', 0);

		$this->db->order_by('ps.packing_sheet_date', 'asc');
		$this->db->order_by('ps.packing_sheet_number', 'asc');
		$this->db->order_by('psp.no_doos', 'asc');
		$this->db->order_by('psp.id_part', 'asc');

		if ($list_nomor_karton != null and count($list_nomor_karton) > 0) {
			$this->db->where_in('psp.no_doos_int', $list_nomor_karton);
		} else {
			$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
		}

		// if ($list_nomor_karton2 != null and count($list_nomor_karton2) > 0) {
		// 	$this->db->where_in('psp.no_doos', $list_nomor_karton2);
		// } else {
		// 	$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
		// }

		if ($this->input->post('status') == 'Closed') {
			$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
			$this->db->where('IFNULL(pbi.tersimpan, 0) = 1', null, false);
		} elseif ($this->input->post('status') == 'Open') {
			if ($this->input->post('add_new') == 0) {
				$this->db->group_start();
				$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
				$this->db->or_where('pbi.no_surat_jalan_ekspedisi is null', null, false);
				$this->db->group_end();
			} else {
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where('pbi.no_surat_jalan_ekspedisi is null', null, false);
				$this->db->or_where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
				$this->db->group_end();
				$this->db->or_where('pbi.tersimpan', 0);
				$this->db->group_end();
			}
		}
	}

	public function get_parts_query()
	{
		$jenis_penerimaan_barang = $this->input->post('jenis_penerimaan_barang');
		$no_surat_jalan_ekspedisi = $this->input->post('no_surat_jalan_ekspedisi');
		$list_nomor_karton = $this->input->post('list_nomor_karton');
		$list_nomor_karton_ev = $this->input->post('list_nomor_karton_ev');

		if(!(empty($list_nomor_karton)) ||empty($list_nomor_karton)||empty($list_nomor_karton_ev)){
			$this->db
					->select('"non_ev" as kategori_penerimaan_barang')
					->select('pbi.id as id_penerimaan_barang_item')
					->select('pbi.no_penerimaan_barang')
					->select('pbi.no_surat_jalan_ekspedisi')
					->select('date_format(ps.packing_sheet_date, "%d-%m-%Y") as packing_sheet_date')
					->select('psli.surat_jalan_ahm')
					->select('psli.surat_jalan_ahm_int')
					->select('ps.id as packing_sheet_number_int')
					->select('ps.packing_sheet_number')
					->select('psp.no_doos as nomor_karton')
					->select('psp.no_doos_int as nomor_karton_int')
					->select('psp.no_po_int')
					->select('psp.no_po')
					->select('psp.id_part')
					->select('psp.id_part_int')
					->select('p.nama_part')
					->select('p.harga_dealer_user as harga')
					->select('psp.packing_sheet_quantity')
					->select('
						case 
							when pbi.id is not null then pbi.qty_diterima
							else 0
						end as qty_diterima
					', false)
						->select('
						case
							when pbi.id is not null then pbi.id_lokasi_rak
							else ""
						end as id_lokasi_rak
					', false)
						->select('
						case
							when pbi.id is not null then lr.kode_lokasi_rak
							else ""
						end as kode_lokasi_rak
					', false)
						->select("
						case
							when pbi.id is not null then ( lr.kapasitas - lr.kapasitas_terpakai )
							else 0
						end as kapasitas_tersedia
					", false)
						->select('
						case
							when pbi.id is not null then pbi.id_lokasi_rak_temporary
							else ""
						end as id_lokasi_rak_temporary
					', false)
						->select('
						case
							when pbi.id is not null then lr_temp.kode_lokasi_rak
							else ""
						end as kode_lokasi_rak_temporary
					', false)
						->select('IFNULL(pbi.tersimpan, 0) as tersimpan')
						->select('IFNULL(pbi.proses_claim_ahm, 0) as proses_claim_ahm')
						->select('IFNULL(pbi.proses_claim_ekspedisi, 0) as proses_claim_ekspedisi')
						->select('0 as checked_for_kekurangan_part')
						->select('0 as edit')
						->from('tr_h3_md_psl_items as psli')
						->join('tr_h3_md_ps as ps', 'ps.id = psli.packing_sheet_number_int')
						->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = ps.id')
						->join('ms_part as p', 'p.id_part_int = psp.id_part_int', 'left')
						->join('tr_h3_md_penerimaan_barang_items as pbi', "(pbi.id_part_int = psp.id_part_int and pbi.packing_sheet_number_int = psp.packing_sheet_number_int and pbi.nomor_karton = psp.no_doos and pbi.no_po = psp.no_po)", 'left')
						->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = pbi.no_surat_jalan_ekspedisi', 'left')
						->join('ms_h3_md_lokasi_rak as lr', 'lr.id = pbi.id_lokasi_rak', 'left')
						->join('ms_h3_md_lokasi_rak as lr_temp', 'lr_temp.id = pbi.id_lokasi_rak_temporary', 'left')
						->where('psp.packing_sheet_quantity >', 0);
			
					$this->db->order_by('ps.packing_sheet_date', 'asc');
					$this->db->order_by('ps.packing_sheet_number', 'asc');
					$this->db->order_by('psp.no_doos', 'asc');
					$this->db->order_by('psp.id_part', 'asc');
			
					if ($list_nomor_karton != null and count($list_nomor_karton) > 0) {
						$this->db->where_in('psp.no_doos_int',$list_nomor_karton);
					} else {
						$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
					}
					
			
					if ($this->input->post('status') == 'Closed') {
						$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
						$this->db->where('IFNULL(pbi.tersimpan, 0) = 1', null, false);
					} elseif ($this->input->post('status') == 'Open') {
						if ($this->input->post('add_new') == 0) {
							$this->db->group_start();
							$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
							$this->db->or_where('pbi.no_surat_jalan_ekspedisi is null', null, false);
							$this->db->group_end();
						} else {
							$this->db->group_start();
							$this->db->group_start();
							$this->db->where('pbi.no_surat_jalan_ekspedisi is null', null, false);
							$this->db->or_where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
							$this->db->group_end();
							$this->db->or_where('pbi.tersimpan', 0);
							$this->db->group_end();
						}
					}
		}
		
	}

	public function get_parts_query2()
	{
		$no_surat_jalan_ekspedisi = $this->input->post('no_surat_jalan_ekspedisi');
		$list_nomor_karton_ev = $this->input->post('list_nomor_karton_ev');

		if(!(empty($list_nomor_karton_ev))||empty($list_nomor_karton_ev)){
			$this->db
					->select('"ev" as kategori_penerimaan_barang')
					->select('"" as packing_sheet_date')
					->select('sl.box_id')
					->select('sl.packing_id as packing_sheet_number')
					->select('sl.carton_id as nomor_karton')
					->select('sl.acc_tipe')
					->select('sl.part_id as id_part')
					->select('1 as packing_sheet_quantity')
					->select('sl.serial_number')
					->select('p.nama_part')
					->select('date_format(ps.packing_sheet_date, "%d-%m-%Y") as packing_sheet_date')
					->select('psli.surat_jalan_ahm')
					->select('psli.surat_jalan_ahm_int')
					->select('ps.id as packing_sheet_number_int')
					->select('ps.packing_sheet_number')
					->select('psp.no_doos as nomor_karton')
					->select('psp.no_doos_int as nomor_karton_int')
					->select('psp.no_po_int')
					->select('psp.no_po')
					->select('p.harga_dealer_user as harga')
					->select('
						case 
							when pbi.id is not null then pbi.qty_diterima
							else 0
						end as qty_diterima
					', false)
						->select('
						case
							when pbi.id is not null then pbi.id_lokasi_rak
							else ""
						end as id_lokasi_rak
					', false)
						->select('
						case
							when pbi.id is not null then lr.kode_lokasi_rak
							else ""
						end as kode_lokasi_rak
					', false)
						->select("
						case
							when pbi.id is not null then ( lr.kapasitas - lr.kapasitas_terpakai )
							else 0
						end as kapasitas_tersedia
					", false)
						->select('
						case
							when pbi.id is not null then pbi.id_lokasi_rak_temporary
							else ""
						end as id_lokasi_rak_temporary
					', false)
						->select('
						case
							when pbi.id is not null then lr_temp.kode_lokasi_rak
							else ""
						end as kode_lokasi_rak_temporary
					', false)
					->select('IFNULL(pbi.tersimpan, 0) as tersimpan')
					->select('IFNULL(pbi.proses_claim_ahm, 0) as proses_claim_ahm')
					->select('IFNULL(pbi.proses_claim_ekspedisi, 0) as proses_claim_ekspedisi')
					->select('0 as checked_for_kekurangan_part')
					->select('0 as edit')
					->select('pbi.id as id_penerimaan_barang_item')
					->select('pbi.no_penerimaan_barang')
					->select('pbi.no_surat_jalan_ekspedisi')
					->from('tr_shipping_list_ev_accrem as sl')
					->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number = sl.packing_id')
					->join('tr_h3_md_ps as ps', 'ps.id = psli.packing_sheet_number_int')
					->join('tr_h3_md_ps_parts as psp', 'psp.no_doos = sl.carton_id and sl.packing_id = psp.packing_sheet_number and sl.part_id = psp.id_part')
					->join('ms_part as p', 'p.id_part = sl.part_id')
					->join('tr_h3_md_penerimaan_barang_items as pbi', "(pbi.id_part = sl.part_id and pbi.packing_sheet_number = sl.packing_id and pbi.nomor_karton = sl.carton_id and sl.serial_number = pbi.serial_number)", 'left')
					->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = pbi.no_surat_jalan_ekspedisi', 'left')
					->join('ms_h3_md_lokasi_rak as lr', 'lr.id = pbi.id_lokasi_rak', 'left')
					->join('ms_h3_md_lokasi_rak as lr_temp', 'lr_temp.id = pbi.id_lokasi_rak_temporary', 'left');
					if ($list_nomor_karton_ev != null and count($list_nomor_karton_ev) > 0) {
						$this->db->where_in('sl.carton_id', $list_nomor_karton_ev);
					} else {
						$this->db->where('pbi.no_surat_jalan_ekspedisi', $no_surat_jalan_ekspedisi);
					}
				}
		
	}

	public function limit_get_parts()
	{
		if ($this->input->post('limit') != null) {
			$this->db->limit($this->input->post('limit'), $this->input->post('start'));
		}
	}

	public function limit_get_parts2()
	{
		if ($this->input->post('limit') != null) {
			$this->db->limit($this->input->post('limit'), $this->input->post('start'));
		}
	}

	public function validate_get_parts()
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(3) == 'update' or $this->input->post('no_penerimaan_barang') != null) {
			$penerimaan_barang = $this->penerimaan_barang->get($this->input->post(['no_penerimaan_barang']), true);
			if ($penerimaan_barang->no_surat_jalan_ekspedisi != $this->input->post('no_surat_jalan_ekspedisi')) {
				$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
			}
		} else {
			$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
		}

		if (!$this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			if (count($errors) > 0) {
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $errors
				], 422);
			}
		}
	}

	public function validate_get_parts2()
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(3) == 'update' or $this->input->post('no_penerimaan_barang') != null) {
			$penerimaan_barang = $this->penerimaan_barang->get($this->input->post(['no_penerimaan_barang']), true);
			if ($penerimaan_barang->no_surat_jalan_ekspedisi != $this->input->post('no_surat_jalan_ekspedisi')) {
				$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
			}
		} else {
			$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
		}

		if (!$this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			if (count($errors) > 0) {
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Data tidak valid',
					'errors' => $errors
				], 422);
			}
		}
	}

	public function get_parts()
	{
		$this->load->helper('query_execution_time');

		$this->validate_get_parts();

		$this->get_parts_query();
		$this->limit_get_parts();

		$parts = [];
		foreach ($this->db->get()->result_array() as $part) {
			if ($part['tersimpan'] == 0) {
				$lokasi = $this->lokasi_rak->suggest_lokasi2($part['id_part'], $part['packing_sheet_quantity']);

				if ($lokasi != null) {
					$part['id_lokasi_rak'] = $lokasi['id_lokasi_rak'];
					$part['kode_lokasi_rak'] = $lokasi['kode_lokasi_rak'];
					$part['kapasitas_tersedia'] = $lokasi['kapasitas_tersedia'];
				}
			}

			if ($part['id_penerimaan_barang_item'] != null) {
				$part['reasons'] = $this->db
					->select('kc.id')
					->select('kc.kode_claim')
					->select('kc.nama_claim')
					->select('kc.tipe_claim')
					->select('pbr.checked')
					->select('pbr.qty')
					->select('pbr.keterangan')
					->from('ms_kategori_claim_c3 as kc')
					->join('tr_h3_md_penerimaan_barang_reasons as pbr', 'pbr.id_claim = kc.id')
					->where('kc.active', 1)
					->where('pbr.id_penerimaan_barang_item', $part['id_penerimaan_barang_item'])
					->get()->result_array();
			} else {
				$part['reasons'] = $this->db
					->select('kc.id')
					->select('kc.kode_claim')
					->select('kc.nama_claim')
					->select('kc.tipe_claim')
					->select('0 as checked')
					->select('0 as qty')
					->select('"" as keterangan')
					->from('ms_kategori_claim_c3 as kc')
					->where('active', 1)
					->get()->result_array();
			}

			$parts[] = $part;
		}

		send_json([
			'parts' => $parts,
			'total' => $this->get_total_parts(),
			'limit' => $this->input->post('limit'),
			'start' => $this->input->post('start'),
			// 'query' => query_execution_time()
		]);
	}

	public function get_total_parts()
	{
		$this->get_parts_query();
		return $this->db->get()->num_rows();
	}

	public function get_parts2()
	{
		$this->load->helper('query_execution_time');

		$this->validate_get_parts2();

		$this->get_parts_query2();
		$this->limit_get_parts2();
		// $this->db->get()->result_array();
		// echo $this->db->last_query();
		// die();
		$parts = [];
		foreach ($this->db->get()->result_array() as $part) {
			// if($part['kategori_penerimaan_barang']=='non_ev'){
				
			// }
			if ($part['tersimpan'] == 0) {


				$lokasi = $this->lokasi_rak->suggest_lokasi($part['id_part'], $part['packing_sheet_quantity']);
				// echo $this->db->last_query();
				// die();
				if ($lokasi != null) {
					$part['id_lokasi_rak'] = $lokasi['id_lokasi_rak'];
					$part['kode_lokasi_rak'] = $lokasi['kode_lokasi_rak'];
					$part['kapasitas_tersedia'] = $lokasi['kapasitas_tersedia'];
				}
			}

			if ($part['id_penerimaan_barang_item'] != null) {
				$part['reasons'] = $this->db
					->select('kc.id')
					->select('kc.kode_claim')
					->select('kc.nama_claim')
					->select('kc.tipe_claim')
					->select('pbr.checked')
					->select('pbr.qty')
					->select('pbr.keterangan')
					->from('ms_kategori_claim_c3 as kc')
					->join('tr_h3_md_penerimaan_barang_reasons as pbr', 'pbr.id_claim = kc.id')
					->where('kc.active', 1)
					->where('pbr.id_penerimaan_barang_item', $part['id_penerimaan_barang_item'])
					->get()->result_array();
			} else {
				$part['reasons'] = $this->db
					->select('kc.id')
					->select('kc.kode_claim')
					->select('kc.nama_claim')
					->select('kc.tipe_claim')
					->select('0 as checked')
					->select('0 as qty')
					->select('"" as keterangan')
					->from('ms_kategori_claim_c3 as kc')
					->where('active', 1)
					->get()->result_array();
			}

			$parts[] = $part;
		}

		send_json([
			'parts' => $parts,
			'total' => $this->get_total_parts2(),
			'limit' => $this->input->post('limit'),
			'start' => $this->input->post('start'),
			// 'query' => query_execution_time()
		]);
	}

	public function get_total_parts2()
	{
		$this->get_parts_query2();
		return $this->db->get()->num_rows();
	}

	public function save()
	{
		$this->db->trans_start();

		$this->validate();
		$penerimaan_barang = array_merge($this->input->post([
			'no_surat_jalan_ekspedisi', 'no_plat', 'nama_driver', 'id_vendor', 'produk', 'jenis_ongkos_angkut_part',
			'per_satuan_ongkos_angkut_part', 'harga_ongkos_angkut_part', 'tgl_surat_jalan_ekspedisi',
			'jumlah_koli', 'alasan_barang_kurang', 'status', 'type_mobil', 'berat_truk', 'total_harga', 'ahm_belum_kirim'
		]), [
			'no_penerimaan_barang' => $this->penerimaan_barang->generateID()
		]);

		$this->penerimaan_barang->insert($penerimaan_barang);

		if (count($this->input->post('list_jumlah_koli')) > 0) {
			foreach ($this->input->post('list_jumlah_koli') as $each) {
				$data = [
					'no_penerimaan_barang' => $penerimaan_barang['no_penerimaan_barang'],
					'koli' => $each['koli'],
					'keterangan' => $each['keterangan'],
				];
				$this->penerimaan_barang_jumlah_koli->insert($data);
			}
		}

		if (count($this->input->post('parts')) > 0) {
			$parts = array_map(function ($part) {
				$part['no_surat_jalan_ekspedisi'] = $this->input->post('no_surat_jalan_ekspedisi');
				return $part;
			}, $this->input->post('parts'));
			$this->proses_parts($parts);
		}

		$this->create_invoice_ekspedisi($penerimaan_barang['no_penerimaan_barang']);

		if ($this->input->post('status') == 'Closed') {
			$this->db
				->set('pb.end_penerimaan', date('Y-m-d H:i:s', time()))
				->where('pb.end_penerimaan', null)
				->where('pb.no_penerimaan_barang', $penerimaan_barang['no_penerimaan_barang'])
				->update('tr_h3_md_penerimaan_barang as pb');
		}

		$this->db
			->set('pbi.no_penerimaan_barang', $penerimaan_barang['no_penerimaan_barang'])
			->where('pbi.no_surat_jalan_ekspedisi', $penerimaan_barang['no_surat_jalan_ekspedisi'])
			->update('tr_h3_md_penerimaan_barang_items as pbi');

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$result = $this->penerimaan_barang->find($penerimaan_barang['no_penerimaan_barang'], 'no_penerimaan_barang');
			send_json($result);
		} else {
			$this->output->set_status_header(400);
		}
	}

	public function keep()
	{
		$this->validate_part_penerimaan_barang();
		$penerimaan_barang = $this->simpan_header();

		if ($penerimaan_barang['status'] == 'Closed') {
			$this->db
					->set('pb.end_penerimaan', date('Y-m-d H:i:s', time()))
					// ->where('pb.end_penerimaan', null)
					->where('pb.no_penerimaan_barang', $penerimaan_barang['no_penerimaan_barang'])
					->update('tr_h3_md_penerimaan_barang as pb');
		}

		send_json([
			'redirect_url' => base_url(sprintf('h3/%s/detail?no_penerimaan_barang=%s', $this->page, $penerimaan_barang['no_penerimaan_barang']))
		]);
	}

	public function simpan_header()
	{
		$this->db->trans_start();

		$penerimaan_barang = array_merge($this->input->post([
			'no_surat_jalan_ekspedisi', 'no_plat', 'nama_driver', 'id_vendor', 'produk', 'jenis_ongkos_angkut_part',
			'per_satuan_ongkos_angkut_part', 'harga_ongkos_angkut_part', 'tgl_surat_jalan_ekspedisi',
			'jumlah_koli', 'alasan_barang_kurang', 'status', 'type_mobil', 'berat_truk', 'total_harga', 'ahm_belum_kirim'
		]));

		$no_penerimaan_barang = '';
		if ($this->input->post('no_penerimaan_barang') == null || $this->input->post('no_penerimaan_barang') == '') {
			$no_penerimaan_barang = $this->penerimaan_barang->generateID();
			$penerimaan_barang['no_penerimaan_barang'] = $no_penerimaan_barang;
			$penerimaan_barang['start_penerimaan'] = date('Y-m-d H:i:s', time());
			$this->penerimaan_barang->insert($penerimaan_barang);
			$this->penerimaan_barang_jumlah_koli->delete($penerimaan_barang['no_penerimaan_barang'], 'no_penerimaan_barang');
		} else {
			$no_penerimaan_barang = $this->input->post('no_penerimaan_barang');
			$this->penerimaan_barang->update($penerimaan_barang, [
				'no_penerimaan_barang' => $no_penerimaan_barang
			]);
			$this->penerimaan_barang_jumlah_koli->delete($no_penerimaan_barang, 'no_penerimaan_barang');
		}

		$list_jumlah_koli = $this->input->post('list_jumlah_koli');
		if ($list_jumlah_koli != null and count($list_jumlah_koli) > 0) {
			foreach ($list_jumlah_koli as $row) {
				$data = [
					'no_penerimaan_barang' => $no_penerimaan_barang,
					'koli' => $row['koli'],
					'keterangan' => $row['keterangan'],
				];
				$this->penerimaan_barang_jumlah_koli->insert($data);
			}
		}

		$this->create_invoice_ekspedisi($no_penerimaan_barang);

		if ($this->input->post('status') == 'Closed') {
			$this->create_notif_pic_hotline($no_penerimaan_barang);
			$this->create_notif_pic_urgent($no_penerimaan_barang);
		}

		$this->db->trans_complete();

		$penerimaan_barang = $this->db
			->select('pb.id')
			->select('pb.no_penerimaan_barang')
			->select('pb.no_surat_jalan_ekspedisi')
			->select('pb.no_plat')
			->select('pb.nama_driver')
			->select('pb.id_vendor')
			->select('pb.produk')
			->select('pb.jenis_ongkos_angkut_part')
			->select('pb.per_satuan_ongkos_angkut_part')
			->select('pb.harga_ongkos_angkut_part')
			->select('pb.tgl_surat_jalan_ekspedisi')
			->select('pb.jumlah_koli')
			->select('pb.alasan_barang_kurang')
			->select('pb.status')
			->select('pb.type_mobil')
			->select('pb.berat_truk')
			->select('pb.total_harga')
			->select('pb.ahm_belum_kirim')
			->from('tr_h3_md_penerimaan_barang as pb')
			->where('pb.no_penerimaan_barang', $no_penerimaan_barang)
			->get()->row_array();

		return $penerimaan_barang;
	}

	public function create_notif_pic_hotline($no_penerimaan_barang)
	{
		$menu_kategori = $this->db
			->from('ms_notifikasi_kategori')
			->where('kode_notif', 'notif_pic_hotline_penerimaan_barang_md')
			->get()->row_array();

		if ($menu_kategori == null) return;

		$this->load->model('notifikasi_model', 'notifikasi');

		$po_dealer_yang_dilakukan_penerimaan = $this->db
			->select('DISTINCT(po_md.referensi_po_hotline) as id_po_dealer', false)
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->join('tr_h3_md_purchase_order as po_md', 'po_md.id_purchase_order = pbi.no_po')
			->where('pbi.no_penerimaan_barang', $no_penerimaan_barang)
			->where('pbi.tersimpan', 1)
			->where('po_md.jenis_po', 'HTL')
			->get()->result_array();


		$this->db->trans_start();

		foreach ($po_dealer_yang_dilakukan_penerimaan as $po_dealer) {
			$penerimaan_barang = $this->db
				->select('pbi.id_part')
				->select('pbi.qty_diterima')
				->from('tr_h3_md_penerimaan_barang_items as pbi')
				->join('tr_h3_md_purchase_order as po_md', 'po_md.id_purchase_order = pbi.no_po')
				->where('pbi.no_penerimaan_barang', $no_penerimaan_barang)
				->where('po_md.referensi_po_hotline', $po_dealer['id_po_dealer'])
				->get()->result_array();

			foreach ($penerimaan_barang as $row) {
				$this->set_po_ahm_ke_book($po_dealer['id_po_dealer'], $row['id_part'], $row['qty_diterima']);
			}

			$kuantitas_diterima = array_sum(
				array_map(function ($row) {
					return floatval($row['qty_diterima']);
				}, $penerimaan_barang)
			);

			$kode_part = array_unique(
				array_map(function ($row) {
					return floatval($row['id_part']);
				}, $penerimaan_barang)
			);

			$count_kode_part = count($kode_part);

			$pesan = "Terdapat penerimaan barang ({$no_penerimaan_barang}) pada PO dealer ({$po_dealer['id_po_dealer']}) untuk {$count_kode_part} kode part dengan jumlah kuantitas {$kuantitas_diterima}.";

			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => $pesan,
				'link' => "{$menu_kategori['link']}/detail?id={$po_dealer['id_po_dealer']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}

		$this->db->trans_complete();
	}

	public function set_po_ahm_ke_book($po_id, $id_part, $kuantitas)
	{
		$this->db
			->set('ppdd.qty_pemenuhan', "ppdd.qty_pemenuhan + {$kuantitas}", false)
			->where('ppdd.id_part', $id_part)
			->where('ppdd.po_id', $po_id)
			->update('tr_h3_md_pemenuhan_po_dari_dealer as ppdd');
	}

	public function create_notif_pic_urgent($no_penerimaan_barang)
	{
		$menu_kategori = $this->db
			->from('ms_notifikasi_kategori')
			->where('kode_notif', 'notif_pic_urgent_penerimaan_barang_md')
			->get()->row_array();

		if ($menu_kategori == null) return;

		$this->load->model('notifikasi_model', 'notifikasi');

		$po_dealer_yang_dilakukan_penerimaan = $this->db
			->select('DISTINCT(pop_md.referensi) as id_po_dealer', false)
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->join('tr_h3_md_purchase_order as po_md', 'po_md.id_purchase_order = pbi.no_po')
			->join('tr_h3_md_purchase_order_parts as pop_md', '(pop_md.id_purchase_order = po_md.id_purchase_order)')
			->where('pbi.no_penerimaan_barang', $no_penerimaan_barang)
			->where('pbi.tersimpan', 1)
			->where('po_md.jenis_po', 'URG')
			->get()->result_array();

		$this->db->trans_start();

		foreach ($po_dealer_yang_dilakukan_penerimaan as $po_dealer) {
			$penerimaan_barang = $this->db
				->select('pbi.id_part')
				->select('pbi.qty_diterima')
				->from('tr_h3_md_penerimaan_barang_items as pbi')
				->join('tr_h3_md_purchase_order as po_md', 'po_md.id_purchase_order = pbi.no_po')
				->join('tr_h3_md_purchase_order_parts as pop_md', '(pop_md.id_purchase_order = po_md.id_purchase_order AND pop_md.id_part = pbi.id_part)')
				->join('tr_h3_md_pemenuhan_po_dari_dealer as pemenuhan', '(pemenuhan.po_id = pop_md.referensi and pemenuhan.id_part = pbi.id_part)')
				->join('tr_h3_dealer_purchase_order_parts as pop_dealer', '(pop_dealer.po_id = pop_md.referensi and pop_dealer.id_part = pbi.id_part)')
				->where('pbi.no_penerimaan_barang', $no_penerimaan_barang)
				->where('pop_md.referensi', $po_dealer['id_po_dealer'])
				->get()->result_array();

			foreach ($penerimaan_barang as $row) {
				$this->set_po_ahm_ke_book($po_dealer['id_po_dealer'], $row['id_part'], $row['qty_diterima']);
			}

			$kuantitas_diterima = array_sum(
				array_map(function ($row) {
					return floatval($row['qty_diterima']);
				}, $penerimaan_barang)
			);

			$kode_part = array_unique(
				array_map(function ($row) {
					return floatval($row['id_part']);
				}, $penerimaan_barang)
			);

			$count_kode_part = count($kode_part);

			$pesan = "Terdapat penerimaan barang ({$no_penerimaan_barang}) pada PO dealer ({$po_dealer['id_po_dealer']}) untuk {$count_kode_part} kode part dengan jumlah kuantitas {$kuantitas_diterima}.";

			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => $pesan,
				'link' => "{$menu_kategori['link']}/detail?id={$po_dealer['id_po_dealer']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}

		$this->db->trans_complete();
	}

	public function simpan_parts()
	{
		$this->db->trans_start();
		$this->validate_part_penerimaan_barang();

		$penerimaan_barang = $this->simpan_header();

		$parts = $this->input->post('parts');
		if (count($parts) > 0) {
			$parts = array_map(function ($part) use ($penerimaan_barang) {
				$part['no_surat_jalan_ekspedisi'] = $this->input->post('no_surat_jalan_ekspedisi');
				$part['no_penerimaan_barang'] = $penerimaan_barang['no_penerimaan_barang'];
				return $part;
			}, $parts);
			$this->proses_parts($parts, true);
			foreach ($parts as $part) {
				// $data = [
				// 	'no_surat_jalan_ekspedisi' => $part['no_surat_jalan_ekspedisi'],
				// 	'surat_jalan_ahm' => $part['surat_jalan_ahm'],
				// 	'surat_jalan_ahm_int' => $part['surat_jalan_ahm_int'],
				// ];
				// $penerimaan_barang_surat_jalan_ahm = $this->penerimaan_barang_surat_jalan_ahm->get($data, true);

				// if ($penerimaan_barang_surat_jalan_ahm == null) {
				// 	$this->penerimaan_barang_surat_jalan_ahm->insert($data);
				// }

				if($part['kategori_penerimaan_barang']=='ev'){
					$data = [
						'no_surat_jalan_ekspedisi' => $part['no_surat_jalan_ekspedisi'],
						'surat_jalan_ahm' => $part['surat_jalan_ahm'],
						'surat_jalan_ahm_int' => $part['surat_jalan_ahm_int'],
					];
					$penerimaan_barang_surat_jalan_ahm = $this->penerimaan_barang_surat_jalan_ahm->get($data, true);
	
					if ($penerimaan_barang_surat_jalan_ahm == null) {
						$this->penerimaan_barang_surat_jalan_ahm->insert($data);
					}
				}else{
					$data = [
						'no_surat_jalan_ekspedisi' => $part['no_surat_jalan_ekspedisi'],
						'surat_jalan_ahm' => $part['surat_jalan_ahm'],
						'surat_jalan_ahm_int' => $part['surat_jalan_ahm_int'],
					];
					$penerimaan_barang_surat_jalan_ahm = $this->penerimaan_barang_surat_jalan_ahm->get($data, true);
	
					if ($penerimaan_barang_surat_jalan_ahm == null) {
						$this->penerimaan_barang_surat_jalan_ahm->insert($data);
					}
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$parts = $this->input->post('parts');

			if (count($parts) > 0) {
				$parts = array_map(function ($row) {
					$row['tersimpan'] = 1;
					return $row;
				}, $parts);
			} else {
				$parts = [];
			}

			send_json([
				'penerimaan_barang' => $penerimaan_barang,
				'parts' => $parts
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil simpan parts'
			], 422);
		}
	}

	public function save_edit()
	{
		$condition = $this->input->post([
			'surat_jalan_ahm', 'packing_sheet_number', 'nomor_karton', 'no_surat_jalan_ekspedisi', 'no_po'
		]);
		$item = (array) $this->penerimaan_barang_items->get($condition, true);

		if ($item == null) {
			send_json([
				'error_type' => 'data_not_found',
				'message' => 'Data yang ingin dirubah tidak ditemukan.'
			], 403);
		}

		$data = $this->input->post(['qty_diterima']);
		$this->penerimaan_barang_items->update($data, $this->input->post([
			'surat_jalan_ahm', 'packing_sheet_number', 'nomor_karton', 'no_surat_jalan_ekspedisi', 'no_po'
		]));

		foreach ($this->input->post('reasons') as $reason) {
			$this->penerimaan_barang_reasons->update([
				'checked' => $reason['checked'],
				'qty' => $reason['qty'],
				'keterangan' => $reason['keterangan']
			], [
				'id_penerimaan_barang_item' => $item['id'],
				'id_claim' => $reason['id']
			]);
		}

		$this->penerimaan_barang_items->count_claim_ekspedisi($item['id']);
		$this->penerimaan_barang_items->count_selain_claim_ekspedisi($item['id']);
		$this->penerimaan_barang_items->set_jumlah_item_diterima_pada_karton($item['id']);
	}

	public function proses_parts($parts, $save_stok = false)
	{
		foreach ($parts as $part) {
			$data = $this->get_in_array([
				'id_part_int', 'id_part', 'qty_diterima', 'nomor_karton', 'nomor_karton_int', 'surat_jalan_ahm', 'surat_jalan_ahm_int',
				'packing_sheet_number', 'packing_sheet_number_int', 'id_lokasi_rak', 'id_lokasi_rak_temporary',
				'no_po', 'no_po_int', 'no_surat_jalan_ekspedisi', 'no_penerimaan_barang','serial_number'
			], $part);
			$data['qty_packing_sheet'] = $part['packing_sheet_quantity'];

			if($part['kategori_penerimaan_barang']=='ev'){
				$data['is_ev'] = 1;
			}else{
				$data['is_ev'] = 0;
			}

			if ($save_stok) {
				$data['tersimpan'] = 1;
			}

			// $this->hapus_item_penerimaan_jika_ada($data);

			// $id_penerimaan_barang_item = $this->penerimaan_barang_items->insert($data);

			if($part['kategori_penerimaan_barang']=='ev'){
				$this->hapus_item_penerimaan_ev_jika_ada($data);
				$id_penerimaan_barang_item = $this->penerimaan_barang_items->insert_ev($data);
			}else{
				
				$this->hapus_item_penerimaan_jika_ada($data);
				$id_penerimaan_barang_item = $this->penerimaan_barang_items->insert($data);
			}

			log_message('debug', sprintf('Melakukan penerimaan %s dengan nomor surat jalan ekspedisi %s untuk kode part %s packing sheet %s nomor karton %s dengan kuantitas %s pada lokasi %s', $part['no_penerimaan_barang'], $part['no_surat_jalan_ekspedisi'], $part['id_part'], $part['packing_sheet_number'], $part['nomor_karton'], $part['qty_diterima'], $part['id_lokasi_rak']));
			log_message('debug', sprintf('ID Penerimaan barang item %s', $id_penerimaan_barang_item));

			$reasons = [];
			foreach ($part['reasons'] as $reason) {
				$data = [
					'id_claim' => $reason['id'],
					'id_penerimaan_barang_item' => $id_penerimaan_barang_item,
					'checked' => $reason['checked'],
					'qty' => $reason['qty'],
					'keterangan' => $reason['keterangan']
				];

				$reasons[] = $data;
			}

			if (count($reasons) > 0) {
				log_message(
					'debug',
					sprintf('Melakukan pencatatan reason %s dengan nomor surat jalan ekspedisi %s untuk kode part %s packing sheet %s nomor karton %s dengan kuantitas %s pada lokasi %s', $part['no_penerimaan_barang'], $part['no_surat_jalan_ekspedisi'], $part['id_part'], $part['packing_sheet_number'], $part['nomor_karton'], $part['qty_diterima'], $part['id_lokasi_rak'])
				);
				log_message('debug', print_r($reasons, true));
				$this->penerimaan_barang_reasons->insert_batch($reasons);
			}

			/*
			$this->penerimaan_barang_items->count_claim_ekspedisi($id_penerimaan_barang_item);
			$this->penerimaan_barang_items->count_selain_claim_ekspedisi($id_penerimaan_barang_item);
			$this->penerimaan_barang_items->set_jumlah_item_diterima_pada_karton($id_penerimaan_barang_item);

			//Check data stock
			$check_kartu_stock = $this->db->select('nomor_karton')
				->from('tr_h3_md_kartu_stock')
				->where('nomor_karton', $part['nomor_karton'])
				->where('id_part', $part['id_part'])
				->where('no_po', $part['no_po'])
				->get()->row_array();


			if ($part['packing_sheet_quantity'] > 0 and $part['id_lokasi_rak'] != null and $save_stok and ($check_kartu_stock['nomor_karton'] == '' || $check_kartu_stock['nomor_karton'] == NULL)) {
				$this->update_stock($part['no_surat_jalan_ekspedisi'], $part);
			}

			//Update Tgl Penerimaan Barang MD di Tabel History hotline 
			$check_po_history = $this->db->select('id_purchase_order')
			->from('tr_h3_md_history_estimasi_waktu_hotline')
			->where('id_purchase_order', $part['no_po'])
			->where('id_part', $part['id_part'])
			->get()
			->num_rows();

			if($check_po_history > 0){
				$this->db->set('tgl_penerimaan_md',  date('Y-m-d H:i:s', time()));
				$this->db->where('id_purchase_order', $part['no_po']);
				$this->db->where('id_part', $part['id_part']);
				$this->db->update('tr_h3_md_history_estimasi_waktu_hotline');
			}

			*/

			if($part['kategori_penerimaan_barang'] == 'ev'){
				$this->penerimaan_barang_items->count_claim_ekspedisi($id_penerimaan_barang_item);
				$this->penerimaan_barang_items->count_selain_claim_ekspedisi($id_penerimaan_barang_item);

				//Check apakah Nomor Karton dan PN nya sama dengan history stock sebelumnya
				//Check data stock
				$check_kartu_stock = $this->db->select('nomor_karton')
				->from('tr_h3_md_kartu_stock')
				->where('nomor_karton', $part['nomor_karton'])
				->where('id_part', $part['id_part'])
				->where('serial_number', $part['serial_number'])
				->get()->row_array();


				if ($part['packing_sheet_quantity'] > 0 and $part['id_lokasi_rak'] != null and $save_stok and ($check_kartu_stock['nomor_karton'] == '' || $check_kartu_stock['nomor_karton'] == NULL)) {
					$this->update_stock_ev($part['no_surat_jalan_ekspedisi'], $part);
				}

				//Update Tgl Penerimaan Barang MD di Tabel History hotline 
				$check_po_history = $this->db->select('id_purchase_order')
				->from('tr_h3_md_history_estimasi_waktu_hotline')
				->where('id_purchase_order', $part['no_po'])
				->where('id_part', $part['id_part'])
				->get()
				->num_rows();

				if($check_po_history > 0){
					$this->db->set('tgl_penerimaan_md',  date('Y-m-d H:i:s', time()));
					$this->db->where('id_purchase_order', $part['no_po']);
					$this->db->where('id_part', $part['id_part']);
					$this->db->update('tr_h3_md_history_estimasi_waktu_hotline');
				}
			}else{
				$this->penerimaan_barang_items->count_claim_ekspedisi($id_penerimaan_barang_item);
				$this->penerimaan_barang_items->count_selain_claim_ekspedisi($id_penerimaan_barang_item);
				$this->penerimaan_barang_items->set_jumlah_item_diterima_pada_karton($id_penerimaan_barang_item);

				//Check apakah Nomor Karton dan PN nya sama dengan history stock sebelumnya
				//Check data stock
				$check_kartu_stock = $this->db->select('nomor_karton')
				->from('tr_h3_md_kartu_stock')
				->where('nomor_karton', $part['nomor_karton'])
				->where('id_part', $part['id_part'])
				->where('no_po', $part['no_po'])
				->get()->row_array();


				if ($part['packing_sheet_quantity'] > 0 and $part['id_lokasi_rak'] != null and $save_stok and ($check_kartu_stock['nomor_karton'] == '' || $check_kartu_stock['nomor_karton'] == NULL)) {
					$this->update_stock($part['no_surat_jalan_ekspedisi'], $part);
				}

				//Update Tgl Penerimaan Barang MD di Tabel History hotline 
				$check_po_history = $this->db->select('id_purchase_order')
				->from('tr_h3_md_history_estimasi_waktu_hotline')
				->where('id_purchase_order', $part['no_po'])
				->where('id_part', $part['id_part'])
				->get()
				->num_rows();

				if($check_po_history > 0){
					$this->db->set('tgl_penerimaan_md',  date('Y-m-d H:i:s', time()));
					$this->db->where('id_purchase_order', $part['no_po']);
					$this->db->where('id_part', $part['id_part']);
					$this->db->update('tr_h3_md_history_estimasi_waktu_hotline');
				}
			}
		}
	}

	public function validate_part_penerimaan_barang()
	{
		$this->form_validation->set_error_delimiters('', '');

		if ($this->input->post('no_penerimaan_barang') != null and $this->input->post('no_penerimaan_barang') != '') {
			$penerimaan_barang = $this->penerimaan_barang->get($this->input->post(['no_penerimaan_barang']), true);
			if ($penerimaan_barang->no_surat_jalan_ekspedisi != $this->input->post('no_surat_jalan_ekspedisi')) {
				$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
			}
		} else {
			$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
		}
		$this->form_validation->set_rules('no_plat', 'Nomor Plat', 'required');
		$this->form_validation->set_rules('berat_truk', 'Berat / Truk', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('jumlah_koli', 'Jumlah Koli', 'required|numeric|greater_than[0]');
		$this->form_validation->set_rules('tgl_surat_jalan_ekspedisi', 'Tanggal Surat Jalan Ekspedisi', 'required');
		$this->form_validation->set_rules('nama_driver', 'Nama Driver', 'required');
		$this->form_validation->set_rules('id_vendor', 'Ekspedisi', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 400);
		}
	}

	public function hapus_item_penerimaan_jika_ada($data)
	{
		$item = $this->penerimaan_barang_items->get([
			// 'no_surat_jalan_ekspedisi' => $data['no_surat_jalan_ekspedisi'],
			'packing_sheet_number_int' => $data['packing_sheet_number_int'],
			'nomor_karton_int' => $data['nomor_karton_int'],
			'id_part_int' => $data['id_part_int'],
			'no_po' => $data['no_po'],
		], true);

		if ($item != null) {
			$this->penerimaan_barang_items->delete($item->id);
			$this->penerimaan_barang_reasons->delete($item->id, 'id_penerimaan_barang_item');
		}
	}

	public function hapus_item_penerimaan_ev_jika_ada($data)
	{
		$item = $this->penerimaan_barang_items->get([
			// 'no_surat_jalan_ekspedisi' => $data['no_surat_jalan_ekspedisi'],
			'packing_sheet_number_int' => $data['packing_sheet_number_int'],
			'nomor_karton_int' => $data['nomor_karton_int'],
			'id_part_int' => $data['id_part_int'],
			'no_po' => $data['no_po'],
			'serial_number' => $data['serial_number'],
		], true);

		if ($item != null) {
			$this->penerimaan_barang_items->delete($item->id);
			$this->penerimaan_barang_reasons->delete($item->id, 'id_penerimaan_barang_item');
		}
	}

	public function hapus_parts()
	{
		$condition = $this->input->post([
			'surat_jalan_ahm', 'packing_sheet_number', 'nomor_karton', 'no_surat_jalan_ekspedisi', 'no_po', 'id_part'
		]);
		$item = (array) $this->penerimaan_barang_items->get($condition, true);

		if ($item == null) {
			send_json([
				'error_type' => 'data_not_found',
				'message' => 'Data yang ingin dihapus tidak ditemukan.'
			], 403);
		}

		$this->db->set('qty', "qty - {$item['qty_packing_sheet']}", FALSE)
			->where('id_part', $condition['id_part'])
			->where('id_lokasi_rak', $item['id_lokasi_rak'])
			->update('tr_stok_part');


		$this->db->where('nomor_karton', $condition['nomor_karton']);
		$this->db->where('packing_sheet_number', $condition['packing_sheet_number']);
		$this->db->where('id_part', $condition['id_part']);
		$this->db->delete('tr_h3_md_penerimaan_barang_items');

		$check_id_part = $this->db->select('id_part_int')
			->from('tr_stok_part_summary')
			->where('id_part_int', $item['id_part_int'])->get()->row_array();

		if ($check_id_part['id_part_int'] != NULL) {
			$this->db->set('qty', "qty - {$item['qty_packing_sheet']}", FALSE);
			$this->db->where('id_part_int', $item['id_part_int']);
			$this->db->update('tr_stok_part_summary');
		} else {
			$data = array(
				'id_part' => $item['id_part'],
				'id_part_int' => $item['id_part_int'],
				'qty' => $item['qty_packing_sheet']
			);
			$this->db->insert('tr_stok_part_summary', $data);
		}
	}

	public function set_proses_claim_to_ekspedisi()
	{
		$this->db->trans_start();

		$this->db->trans_complete();
		if (count($this->input->post()) > 0) {
			foreach ($this->input->post() as $part) {
				$this->db
					->set('proses_claim_ekspedisi', 1)
					->where('id_part', $part['id_part'])
					->where('nomor_karton', $part['nomor_karton'])
					->where('packing_sheet_number', $part['packing_sheet_number'])
					->where('no_surat_jalan_ekspedisi', $part['no_surat_jalan_ekspedisi'])
					->update('tr_h3_md_penerimaan_barang_items');
			}
		}
		if ($this->db->trans_status()) {
			send_json($this->input->post());
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function update_stock($referensi, $part)
	{
		$lokasi = $this->db
			->select('lr.id')
			->select("( lr.kapasitas - lr.kapasitas_terpakai ) as kapasitas_tersedia", false)
			->from('ms_h3_md_lokasi_rak as lr')
			->where('lr.id', $part['id_lokasi_rak'])
			->get()->row();

		$qty_diterima_temporary = 0;
		if ($part['id_lokasi_rak_temporary'] != null) {
			$qty_diterima_temporary = abs($part['packing_sheet_quantity'] - $lokasi->kapasitas_tersedia);
		}
		$qty_diterima_permanen = $part['packing_sheet_quantity'] - $qty_diterima_temporary;

		$this->create_or_update_stock($part['id_part_int'], $part['id_part'], $part['id_lokasi_rak'], $qty_diterima_permanen, $referensi, $part['packing_sheet_number'], $part['nomor_karton'], $part['no_po']);
		if ($part['id_lokasi_rak_temporary'] != null) {
			$this->create_or_update_stock($part['id_part_int'], $part['id_part'], $part['id_lokasi_rak_temporary'], $qty_diterima_temporary, $referensi, $part['packing_sheet_number'], $part['nomor_karton'], $part['no_po']);
		}
	}

	public function update_stock_ev($referensi, $part)
	{
		$lokasi = $this->db
			->select('lr.id')
			->select("( lr.kapasitas - lr.kapasitas_terpakai ) as kapasitas_tersedia", false)
			->from('ms_h3_md_lokasi_rak as lr')
			->where('lr.id', $part['id_lokasi_rak'])
			->get()->row();


		$id_part_int = $this->db
			->select('id_part_int')
			->from('ms_part as mp')
			->where('id_part', $part['id_part'])
			->get()->row_array();

		$qty_diterima_temporary = 0;
		if ($part['id_lokasi_rak_temporary'] != null) {
			$qty_diterima_temporary = abs($part['packing_sheet_quantity'] - $lokasi->kapasitas_tersedia);
		}
		$qty_diterima_permanen = $part['packing_sheet_quantity'] - $qty_diterima_temporary;

		$this->create_or_update_stock_ev($id_part_int['id_part_int'], $part['id_part'], $part['id_lokasi_rak'], $qty_diterima_permanen, $referensi, $part['packing_sheet_number'], $part['nomor_karton'], $part['serial_number'],$part['no_penerimaan_barang'], $part['no_po']);
		if ($part['id_lokasi_rak_temporary'] != null) {
			$this->create_or_update_stock_ev($id_part_int['id_part_int'], $part['id_part'], $part['id_lokasi_rak_temporary'], $qty_diterima_temporary, $referensi, $part['packing_sheet_number'], $part['nomor_karton'], $part['serial_number'],$part['no_penerimaan_barang'], $part['no_po']);
		}
	}

	public function create_or_update_stock($id_part_int, $part, $lokasi, $qty, $referensi = '', $packing_sheet_number, $nomor_karton, $no_po)
	{
		$transaksi_stock = [
			'id_part_int' => $id_part_int,
			'id_part' => $part,
			'id_lokasi_rak' => $lokasi,
			'tipe_transaksi' => '+',
			'sumber_transaksi' => $this->page,
			'referensi' => $referensi,
			'packing_sheet_number' => $packing_sheet_number,
			'nomor_karton' => $nomor_karton,
			'no_po' => $no_po,
			'stock_value' => $qty,
		];

		$this->kartu_stock->insert($transaksi_stock);

		$stock = $this->db
			->from('tr_stok_part as s')
			->where('s.id_part', $part)
			->where('s.id_lokasi_rak', $lokasi)
			->limit(1)
			->get()->row();

		if ($stock != null) {
			$this->db->set('qty', "qty + {$qty}", FALSE)
				->set('updated_at', date('Y-m-d H:i:s', time()))
				->where('id_part', $part)
				->where('id_lokasi_rak', $lokasi)
				->update('tr_stok_part');
		} else {
			$this->db->insert('tr_stok_part', [
				'qty' => $qty,
				'id_part' => $part,
				'id_part_int' => $id_part_int,
				'id_lokasi_rak' => $lokasi,
				'created_at' => date('Y-m-d H:i:s', time())
			]);
		}

		$lokasi_rak_parts = $this->lokasi_rak_parts->get([
			'id_lokasi_rak' => $lokasi,
			'id_part' => $part
		], true);

		if ($lokasi_rak_parts == null) {
			$this->lokasi_rak_parts->insert([
				'id_lokasi_rak' => $lokasi,
				'id_part' => $part,
				'qty_maks' => $qty
			]);
		}

		$check_id_part = $this->db->select('id_part_int')
			->from('tr_stok_part_summary')
			->where('id_part_int', $id_part_int)->get()->row_array();

		if ($check_id_part['id_part_int'] != NULL) {
			$this->db->set('qty', "qty + {$qty}", FALSE);
			$this->db->set('qty_intransit', "qty_intransit - {$qty}", FALSE);
			$this->db->where('id_part_int', $id_part_int);
			$this->db->update('tr_stok_part_summary');
		} else {
			$data = array(
				'id_part' => $part,
				'id_part_int' => $id_part_int,
				'qty' => $qty
			);
			$this->db->insert('tr_stok_part_summary', $data);
		}
	}

	public function create_or_update_stock_ev($id_part_int, $part, $lokasi, $qty, $referensi = '', $packing_sheet_number, $nomor_karton, $serial_number,$no_penerimaan_barang, $no_po)
	{

		$transaksi_stock = [
			'id_part_int' => $id_part_int,
			'id_part' => $part,
			'id_lokasi_rak' => $lokasi,
			'tipe_transaksi' => '+',
			'sumber_transaksi' => $this->page,
			'referensi' => $referensi,
			'packing_sheet_number' => $packing_sheet_number,
			'nomor_karton' => $nomor_karton,
			'stock_value' => $qty,
			'serial_number' => $serial_number,
			'no_po' => $no_po,
		];

		$this->kartu_stock->insert($transaksi_stock);

		$stock = $this->db
			->from('tr_stok_part as s')
			->where('s.id_part', $part)
			->where('s.id_lokasi_rak', $lokasi)
			->limit(1)
			->get()->row();

		if ($stock != null) {
			$this->db->set('qty', "qty + {$qty}", FALSE)
				->set('updated_at', date('Y-m-d H:i:s', time()))
				->where('id_part', $part)
				->where('id_lokasi_rak', $lokasi)
				->update('tr_stok_part');
		} else {
			$this->db->insert('tr_stok_part', [
				'qty' => $qty,
				'id_part' => $part,
				'id_part_int' => $id_part_int,
				'id_lokasi_rak' => $lokasi,
				'created_at' => date('Y-m-d H:i:s', time())
			]);
		}


		//Cek nama part, kelompok part di Ms Part
		$cek_part = $this->db
			->select('nama_part')
			->select('kelompok_part')
			->from('ms_part as mp')
			->where('mp.id_part', $part)
			->limit(1)
			->get()->row();

		$accesories_type = null;

		if($cek_part != null){
			if($cek_part->kelompok_part == 'EVBT'){
				$accesories_type = 'B';
			}elseif($cek_part->kelompok_part == 'EVCH'){
				$accesories_type = 'C';
			}
		} 

		//Cek Nama Lokasi Rak 
		$cek_lokasi_rak = $this->db
			->select('kode_lokasi_rak')
			->from('ms_h3_md_lokasi_rak as lr')
			->where('lr.id', $lokasi)
			->limit(1)
			->get()->row();

		//Cek no SL AHM 
		$cek_no_sl_ahm = $this->db
			->select('no_shipping_list')
			->select('created_at as created_at_shipping_list')
			->from('tr_shipping_list_ev_accrem as sl')
			->where('sl.serial_number', $serial_number)
			->limit(1)
			->get()->row();

		//Cek dan insert di tabel tr_h3_serial_ev_tracking
		$stock = $this->db
			->select('ts.serial_number')
			->from('tr_h3_serial_ev_tracking as ts')
			->where('ts.id_part', $part)
			->where('ts.serial_number', $serial_number)
			->limit(1)
			->get()->row();

		//Cek int, tgl, dan created by di tabel penerimaan MD
		$cek_penerimaan_md = $this->db
			->select('pb.no_penerimaan_barang_int as no_pb_int')
			->select('pb.created_at')
			->select('pb.created_by')
			->from('tr_h3_md_penerimaan_barang_items as pb')
			->where('pb.no_penerimaan_barang', $no_penerimaan_barang)
			->where('pb.serial_number', $serial_number)
			->where('pb.id_part', $part)
			->limit(1)
			->get()->row();

		if ($stock == null) {
			$this->db->insert('tr_h3_serial_ev_tracking', [
				'id_part' => $part,
				'id_part_int' => $id_part_int,
				'nama_part' => $cek_part->nama_part,
				'kelompok_part' => $cek_part->kelompok_part,
				'type_accesories' => $accesories_type,
				'serial_number' => $serial_number,
				'accStatus' => 2,
				'no_shipping_list' => $cek_no_sl_ahm->no_shipping_list,
				'created_at_shipping_list' => $cek_no_sl_ahm->created_at_shipping_list,
				'no_penerimaan_barang_md_int' => $cek_penerimaan_md->no_pb_int,
				'no_penerimaan_barang_md' => $no_penerimaan_barang,
				'id_lokasi_rak_md' => $lokasi,
				'kode_lokasi_rak_md' => $cek_lokasi_rak->kode_lokasi_rak,
				'fifo' => $this->generate_fifo(),
				'created_at_penerimaan_md' => $cek_penerimaan_md->created_at,
				'created_by_penerimaan_md' => $cek_penerimaan_md->created_by,
			]);
		}

		$lokasi_rak_parts = $this->lokasi_rak_parts->get([
			'id_lokasi_rak' => $lokasi,
			'id_part' => $part
		], true);

		if ($lokasi_rak_parts == null) {
			$this->lokasi_rak_parts->insert([
				'id_lokasi_rak' => $lokasi,
				'id_part' => $part,
				'qty_maks' => $qty
			]);
		}

		$check_id_part = $this->db->select('id_part_int')
										->from('tr_stok_part_summary')
										->where('id_part_int',$id_part_int)->get()->row_array();

		if($check_id_part['id_part_int']!=NULL){
			$this->db->set('qty', "qty + {$qty}", FALSE);
			$this->db->set('qty_intransit', "qty_intransit - {$qty}", FALSE);
			$this->db->where('id_part_int', $id_part_int);
			$this->db->update('tr_stok_part_summary');
		}else{
			$data = array(
			'id_part' => $part,
			'id_part_int' => $id_part_int,
			'qty' => $qty
			);
			$this->db->insert('tr_stok_part_summary', $data);
		}

		//Update Status di table Shipping List
			$this->db->set('is_penerimaan', 1);
			$this->db->where('part_id', $part);
			$this->db->where('packing_id', $packing_sheet_number);
			$this->db->where('carton_id', $nomor_karton);
			$this->db->where('serial_number', $serial_number);
			$this->db->update('tr_shipping_list_ev_accrem');

			$data_ev = array(
				'serialNo' =>  $serial_number,
				'accType' => $accesories_type,
				'accStatus' => 2,
				'mdReceiveDate' =>  $cek_penerimaan_md->created_at,
				'accStatus_2_processed_at' =>  $cek_penerimaan_md->created_at,
				'accStatus_2_processed_by_user' =>  $cek_penerimaan_md->created_by,
				'api_from' =>2,
				'last_updated' => date('Y-m-d H:i:s', time())
			);
			
			$this->db->insert('tr_status_ev_acc', $data_ev);

			//Insert data di table ev_log_send_api_3
			$data_ev_to_ahm = array(
				'serialNo' =>  $serial_number,
				'accStatus' => 2,
				'created_at' =>  $cek_penerimaan_md->created_at,
				'status_scan' => 1, 
			);
			
			$this->db->insert('ev_log_send_api_3', $data_ev_to_ahm);

		//Update jumlah item yg diterima di no karton 
			$this->db->set('jumlah_item_diterima', "jumlah_item_diterima + {$qty}", FALSE);
			$this->db->set('proses', 1);
			$this->db->where('nomor_karton', $nomor_karton);
			$this->db->update('tr_h3_md_nomor_karton');	
	}

	public function generate_fifo(){
		$tahun = date('Y');
        
		$get_data  = $this->db->query("SELECT fifo FROM tr_h3_serial_ev_tracking WHERE LEFT(created_at_penerimaan_md,4)='$tahun' ORDER BY created_at_penerimaan_md DESC LIMIT 0,1");

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_outbound_form_part_transfer = substr($row->fifo, -6);
            $new_kode   = $tahun . sprintf("%'.06d", $id_outbound_form_part_transfer + 1);
            $i = 0;
            while ($i < 1) {
                $cek = $this->db->get_where('tr_h3_serial_ev_tracking', ['fifo' => $new_kode])->num_rows();
                if ($cek > 0) {
                    $gen_number    = substr($new_kode, -6);
                    $new_kode = $tahun . sprintf("%'.06d", $gen_number + 1);
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {
            $new_kode   = $tahun. '000001';
        }

        return strtoupper($new_kode);
	}


	public function create_invoice_ekspedisi($referensi)
	{
		$penerimaan_barang = $this->db
			->select('pb.no_penerimaan_barang')
			->select('pb.no_surat_jalan_ekspedisi')
			->select('pb.berat_truk')
			->select('pb.per_satuan_ongkos_angkut_part')
			->select('pb.harga_ongkos_angkut_part')
			->select('e.ppn')
			->select('pb.status')
			->from('tr_h3_md_penerimaan_barang as pb')
			->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
			->where('pb.no_penerimaan_barang', $referensi)
			->get()->row_array();

		if ($penerimaan_barang == null) return;

		if ($penerimaan_barang['status'] == 'Closed') {
			$invoice_ekspedisi = [
				'no_invoice_ekspedisi' => $this->invoice_ekspedisi->generate_id(),
				'referensi' => $referensi,
				'tipe_referensi' => 'penerimaan_barang',
				'tanggal_invoice' => date('Y-m-d', time())
			];

			$invoice_ekspedisi['ppn_ekspedisi'] = $penerimaan_barang['ppn'];
			$invoice_ekspedisi['dpp'] = $penerimaan_barang['harga_ongkos_angkut_part'] * ($penerimaan_barang['berat_truk'] / $penerimaan_barang['per_satuan_ongkos_angkut_part']);
			$invoice_ekspedisi['ppn'] = $invoice_ekspedisi['dpp'] * (intval($penerimaan_barang['ppn']) / 100);
			$invoice_ekspedisi['grand_total'] = $invoice_ekspedisi['dpp'] + $invoice_ekspedisi['ppn'];

			$parts = $this->db
				->select("'{$invoice_ekspedisi['no_invoice_ekspedisi']}' as no_invoice_ekspedisi")
				->select('pbi.id_part')
				->select('SUM( IFNULL(psp.packing_sheet_quantity, 0) ) as qty_order')
				->select('SUM( IFNULL(pbi.qty_diterima, 0) ) as qty_diterima')
				->from('tr_h3_md_penerimaan_barang_items as pbi')
				->join('tr_h3_md_ps_parts as psp', '(psp.id_part = pbi.id_part and psp.packing_sheet_number = pbi.packing_sheet_number and psp.no_doos = pbi.nomor_karton and and psp.no_po = pbi.no_po)')
				->where('pbi.no_surat_jalan_ekspedisi', $penerimaan_barang['no_surat_jalan_ekspedisi'])
				->where('pbi.tersimpan', 1)
				->group_by('pbi.id_part')
				->get()->result_array();

			$this->invoice_ekspedisi->insert($invoice_ekspedisi);
			if (count($parts) > 0) {
				$this->invoice_ekspedisi_item->insert_batch($parts);
			}
		}
	}

	public function proses_claim()
	{
		$this->db->trans_start();

		$id_penerimaan_barang_header = $this->input->post('id_penerimaan_barang_header');

		$penerimaan_barang = $this->db
			->select('pb.id')
			->select('pb.no_penerimaan_barang')
			->from('tr_h3_md_penerimaan_barang as pb')
			->where('pb.id', $id_penerimaan_barang_header)
			->limit(1)
			->get()->row_array();

		if ($penerimaan_barang == null) {
			send_json([
				'message' => 'Penerimaan barang tidak ditemukan'
			], 404);
		}

		$packing_sheet_numbers = $this->db
			->select('DISTINCT(pbi.packing_sheet_number_int) as packing_sheet_number_int', false)
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->where_in('pbi.id', $this->input->post('id_penerimaan_barang'))
			->get()->result_array();

		foreach ($packing_sheet_numbers as $row) {
			$packing_sheet = $this->db
				->select('ps.id')
				->select('ps.packing_sheet_number')
				->select('ps.invoice_number_int')
				->select('ps.invoice_number')
				->from('tr_h3_md_ps as ps')
				->where('ps.id', $row['packing_sheet_number_int'])
				->get()->row_array();

			$claim_main_dealer = [
				'no_penerimaan_barang_int' => $penerimaan_barang['id'],
				'no_penerimaan_barang' => $penerimaan_barang['no_penerimaan_barang'],
				'packing_sheet_number_int' => $packing_sheet['id'],
				'packing_sheet_number' => $packing_sheet['packing_sheet_number'],
				'invoice_number_int' => $packing_sheet['invoice_number_int'],
				'invoice_number' => $packing_sheet['invoice_number'],
				'id_claim' => $this->claim_main_dealer_ke_ahm->generateID()
			];

			$parts = $this->db
				->select("'{$claim_main_dealer['id_claim']}' as id_claim")
				->select('pbi.id_part_int')
				->select('pbi.id_part')
				->select('pbi.nomor_karton as no_doos')
				->select('pbi.no_po')
				->select('pbi.no_po_int')
				->select('pbr.qty as qty_part_diclaim')
				->select('pbr.qty as qty_part_dikirim_ke_ahm')
				->select('pbr.id_claim as id_kode_claim')
				->select('pbi.id_lokasi_rak')
				->select('pbr.keterangan as keterangan')
				->from('tr_h3_md_penerimaan_barang_items as pbi')
				->join('tr_h3_md_penerimaan_barang_reasons as pbr', 'pbr.id_penerimaan_barang_item = pbi.id')
				->join('ms_kategori_claim_c3 as kc', 'kc.id = pbr.id_claim')
				->where('pbi.packing_sheet_number_int', $row['packing_sheet_number_int'])
				->where('kc.tipe_claim != ', 'Claim Ekspedisi')
				->where('pbr.checked', 1)
				->where('pbr.qty >', 0)
				->where_in('pbi.id', $this->input->post('id_penerimaan_barang'))
				->get()->result_array();

			if (count($parts) > 0) {
				$parts = array_map(function ($part) {
					$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);
					return $part;
				}, $parts);

				$this->claim_main_dealer_ke_ahm->insert($claim_main_dealer);
				$id = $this->db->insert_id();
				$parts = array_map(function ($part) use ($id) {
					$part['id_claim_int'] = $id;
					return $part;
				}, $parts);
				$this->claim_main_dealer_ke_ahm_item->insert_batch($parts);
			}
		}

		$this->db
			->set('proses_claim_ahm', 1)
			->where_in('id', $this->input->post('id_penerimaan_barang'))
			->update('tr_h3_md_penerimaan_barang_items');

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json([
				'message' => 'Berhasil buat claim',
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil buat claim'
			], 422);
		}
	}

	public function create_berita_acara()
	{
		$this->db->trans_start();

		$berita_acara = array_merge($this->input->get(['id_vendor', 'no_surat_jalan_ekspedisi', 'no_plat', 'nama_driver']), [
			'tanggal_serah_terima' => date('Y-m-d', time()),
			'no_bapb' => $this->berita_acara->generateID()
		]);

		$parts = $this->db
			->select("'{$berita_acara['no_bapb']}' as no_bapb")
			->select('pbi.id_part')
			->select('pbi.nomor_karton')
			->select('pbi.nomor_karton_int')
			->select('pbi.surat_jalan_ahm')
			->select('pbi.surat_jalan_ahm_int')
			->select('pbi.packing_sheet_number')
			->select('pbi.packing_sheet_number_int')
			->select('pbi.no_po')
			->select('pbi.no_po_int')
			->select('pbi.qty_diterima')
			->select('pbi.id_lokasi_rak')
			->select("pbr.qty as qty_rusak")
			->select("pbr.keterangan as keterangan_bapb")
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->join('tr_h3_md_penerimaan_barang_reasons as pbr', 'pbr.id_penerimaan_barang_item = pbi.id')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = pbr.id_claim')
			->where('pbi.no_surat_jalan_ekspedisi', $this->input->get('no_surat_jalan_ekspedisi'))
			->where_in('pbi.id', $this->input->get('id_penerimaan_barang'))
			->where('kc.tipe_claim', 'Claim Ekspedisi')
			->where('pbr.checked', 1)
			->where('pbr.qty >', 0)
			->get()->result_array();

		if (count($parts) > 0) {
			$this->berita_acara->insert($berita_acara);
			$this->berita_acara_items->insert_batch($parts);
		} else {
			send_json([
				'error_type' => 'validation_error',
				'response_type' => 'warning',
				'message' => 'Tidak dapat dibuatkan BAP. Karena Kuantitas Part 0'
			], 403);
		}

		$this->db
			->set('proses_claim_ekspedisi', 1)
			->where_in('id', $this->input->get('id_penerimaan_barang'))
			->update('tr_h3_md_penerimaan_barang_items');

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json(
				$this->berita_acara->find($berita_acara['no_bapb'], 'no_bapb')
			);
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(3) == 'update') {
			$penerimaan_barang = $this->penerimaan_barang->get($this->input->post(['no_penerimaan_barang']), true);
			if ($penerimaan_barang->no_surat_jalan_ekspedisi != $this->input->post('no_surat_jalan_ekspedisi')) {
				$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
			}
		} else {
			$this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_barang.no_surat_jalan_ekspedisi]');
		}
		$this->form_validation->set_rules('no_plat', 'Nomor Plat', 'required');
		$this->form_validation->set_rules('tgl_surat_jalan_ekspedisi', 'Tanggal Surat Jalan Ekspedisi', 'required');
		$this->form_validation->set_rules('nama_driver', 'Nama Driver', 'required');
		$this->form_validation->set_rules('id_vendor', 'Ekspedisi', 'required');
		$this->form_validation->set_rules('jumlah_koli', 'Jumlah Koli', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['penerimaan_barang'] = $this->db
			->select('pb.*')
			->select('date_format(pb.created_at, "%d-%m-%Y") as created_at')
			->select('e.nama_ekspedisi as vendor_name')
			->select('o.id as id_ongkos_angkut_part')
			->from('tr_h3_md_penerimaan_barang as pb')
			->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor', 'left')
			->join('ms_h3_md_ongkos_angkut_part as o', 'o.id_vendor = pb.id_vendor', 'left')
			->where('pb.no_penerimaan_barang', $this->input->get('no_penerimaan_barang'))
			->limit(1)
			->get()->row_array();

		$data['list_jumlah_koli'] = $this->penerimaan_barang_jumlah_koli->get($this->input->get(['no_penerimaan_barang']));

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['penerimaan_barang'] = $this->db
			->select('pb.*')
			->select('date_format(pb.created_at, "%d-%m-%Y") as created_at')
			->select('e.nama_ekspedisi as vendor_name')
			->select('o.id as id_ongkos_angkut_part')
			->from('tr_h3_md_penerimaan_barang as pb')
			->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor', 'left')
			->join('ms_h3_md_ongkos_angkut_part as o', 'o.id_vendor = pb.id_vendor', 'left')
			->where('pb.no_penerimaan_barang', $this->input->get('no_penerimaan_barang'))
			->limit(1)
			->get()->row_array();

		$surat_jalan_ahm = $this->db
			->select('distinct (pbi.surat_jalan_ahm_int) as surat_jalan_ahm_int')
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->where('pbi.no_surat_jalan_ekspedisi', $data['penerimaan_barang']['no_surat_jalan_ekspedisi'])
			->where('pbi.surat_jalan_ahm !=', null)
			->get()->result_array();

		$data['surat_jalan_ahm'] = array_map(function ($data) {
			return $data['surat_jalan_ahm_int'];
		}, $surat_jalan_ahm);

		$data['list_jumlah_koli'] = $this->penerimaan_barang_jumlah_koli->get($this->input->get(['no_penerimaan_barang']));

		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();

		$this->validate();
		$penerimaan_barang = $this->input->post([
			'no_surat_jalan_ekspedisi', 'no_plat', 'nama_driver', 'id_vendor', 'produk', 'jenis_ongkos_angkut_part',
			'per_satuan_ongkos_angkut_part', 'harga_ongkos_angkut_part', 'tgl_surat_jalan_ekspedisi',
			'jumlah_koli', 'alasan_barang_kurang', 'status', 'type_mobil', 'berat_truk', 'total_harga', 'ahm_belum_kirim'
		]);

		$this->penerimaan_barang->update($penerimaan_barang, $this->input->post(['no_penerimaan_barang']));

		$this->penerimaan_barang_jumlah_koli->delete($this->input->post('no_penerimaan_barang'), 'no_penerimaan_barang');
		if (count($this->input->post('list_jumlah_koli')) > 0) {
			foreach ($this->input->post('list_jumlah_koli') as $each) {
				$data = [
					'no_penerimaan_barang' => $this->input->post('no_penerimaan_barang'),
					'koli' => $each['koli'],
					'keterangan' => $each['keterangan'],
				];
				$this->penerimaan_barang_jumlah_koli->insert($data);
			}
		}

		if (count($this->input->post('parts')) > 0) {
			$parts = array_map(function ($part) {
				$part['no_surat_jalan_ekspedisi'] = $this->input->post('no_surat_jalan_ekspedisi');
				return $part;
			}, $this->input->post('parts'));
			$this->proses_parts($parts);
		}

		if ($this->input->post('status') == 'Closed') {
			$this->db
				->set('pb.end_penerimaan', date('Y-m-d H:i:s', time()))
				->where('pb.end_penerimaan', null)
				->where('pb.no_penerimaan_barang', $this->input->post('no_penerimaan_barang'))
				->update('tr_h3_md_penerimaan_barang as pb');
		}

		$this->db
			->set('pbi.no_penerimaan_barang', $this->input->post('no_penerimaan_barang'))
			->where('pbi.no_surat_jalan_ekspedisi', $penerimaan_barang['no_surat_jalan_ekspedisi'])
			->update('tr_h3_md_penerimaan_barang_items as pbi');

		$this->create_invoice_ekspedisi($this->input->post('no_penerimaan_barang'));

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$result = $this->penerimaan_barang->get($this->input->post(['no_penerimaan_barang']), true);
			send_json($result);
		} else {
			$this->output->set_status_header(400);
		}
	}

	public function get_jumlah_koli()
	{
		if ($this->input->get('surat_jalan_ahm') == null) {
			echo 0;
			die;
		}

		$data = $this->db
			->select('DISTINCT(psp.no_doos) as no_doos')
			->from('tr_h3_md_psl as psl')
			->join('tr_h3_md_psl_items as psli', 'psli.surat_jalan_ahm_int = psl.id')
			->join('tr_h3_md_ps as ps', 'ps.id = psli.packing_sheet_number_int')
			->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = ps.id')
			->where_in('psl.id', $this->input->get('surat_jalan_ahm'))
			->get()->result_array();

		echo count($data);
		die;
	}

	public function get_info_penyelesaian()
	{
		$surat_jalan_ahm = $this->db
			->select('DISTINCT(pbi.surat_jalan_ahm_int) as surat_jalan_ahm_int')
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->where('pbi.no_surat_jalan_ekspedisi', $this->input->get('no_surat_jalan_ekspedisi'))
			->get_compiled_select();


		$this->db->start_cache();
		$this->db
			// ->select('pbi.no_penerimaan_barang')
			// ->select('pbi.id_part')
			// ->select('psp.packing_sheet_number')
			// ->select('psp.no_doos')
			// ->select('pbi.tersimpan')
			->from('tr_h3_md_psl_items as psli')
			->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = psli.packing_sheet_number_int')
			->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.packing_sheet_number_int = psp.packing_sheet_number_int and pbi.nomor_karton = psp.no_doos and pbi.no_po = psp.no_po and pbi.id_part_int = psp.id_part_int)', 'left')
			->where("psli.surat_jalan_ahm_int IN ({$surat_jalan_ahm})", null, false)
			->where("
		case 
			when pbi.id is null then true
			else pbi.no_surat_jalan_ekspedisi = '{$this->input->get('no_surat_jalan_ekspedisi')}'
		end
		", null, false);
		$this->db->stop_cache();

		$total_parts = $this->db->get()->num_rows();

		$this->db->where('pbi.tersimpan', 1);
		$total_parts_tersimpan = $this->db->get()->num_rows();

		$this->db->group_by('pbi.id_part');
		$this->db->where('pbi.id is not null', null, false);
		$total_item = $this->db->get()->num_rows();

		$this->db->group_by('pbi.id_part');
		$this->db->where('pbi.tersimpan', 1);
		$total_item_tersimpan = $this->db->get()->num_rows();

		$this->db->select('IFNULL( SUM(psp.packing_sheet_quantity), 0) as packing_sheet_quantity');
		$total_pcs = $this->db->get()->row_array()['packing_sheet_quantity'];

		$this->db->select('IFNULL( SUM(psp.packing_sheet_quantity), 0) as packing_sheet_quantity');
		$this->db->where('pbi.tersimpan', 1);
		$total_pcs_tersimpan = $this->db->get()->row_array()['packing_sheet_quantity'];


		send_json([
			'total_parts' => intval($total_parts),
			'total_parts_tersimpan' => intval($total_parts_tersimpan),
			'total_item' => intval($total_item),
			'total_item_tersimpan' => intval($total_item_tersimpan),
			'total_pcs' => intval($total_pcs),
			'total_pcs_tersimpan' => intval($total_pcs_tersimpan),
		]);
	}

	public function get_info_nomor_karton_dari_scanner()
	{
		$nomor_karton_dari_scanner = $this->input->get('nomor_karton_dari_scanner');

		$data = $this->db
			->select('psp.no_doos_int as nomor_karton_int')
			->select('psp.no_doos as nomor_karton')
			->select('ps.id as packing_sheet_number_int')
			->select('ps.packing_sheet_number')
			->select('psl.id as surat_jalan_ahm_int')
			->select('psl.surat_jalan_ahm')
			->from('tr_h3_md_ps_parts as psp')
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = psp.packing_sheet_number', 'left')
			->join('tr_h3_md_psl_items as psl', 'psl.packing_sheet_number = ps.packing_sheet_number', 'left')
			->where('psp.no_doos', $nomor_karton_dari_scanner)
			->limit(1)
			->get()->row_array();

		if ($data != null and $data['surat_jalan_ahm'] == null) {
			log_message('debug', sprintf('[%s][%s] Surat Jalan AHM untuk nomor Karton %s tidak ditemukan', __FILE__, __FUNCTION__, $nomor_karton_dari_scanner));
			send_json([
				'message' => sprintf('Surat Jalan AHM untuk nomor karton %s di packing sheet %s tidak ditemukan', $data['nomor_karton'], $data['packing_sheet_number']),
			], 422);
		}

		if ($data == null) {
			log_message('debug', sprintf('[h3_md_laporan_penerimaan_barang][get_info_nomor_karton_dari_scanner] : Nomor Karton tidak ditemukan; nomor_karton=%s', $nomor_karton_dari_scanner));
			send_json([
				'error_type' => 'nomor_karton_not_found',
				'message' => 'Nomor karton tidak ditemukan.',
			], 422);
		}

		send_json($data);
	}

	public function download_excel_by_packing_sheet()
	{
		$this->load->model('H3_md_report_laporan_penerimaan_by_packing_sheet_model', 'report_laporan_penerimaan_by_packing_sheet');

		$periode_awal = $this->input->get('periode_awal');
		$periode_akhir = $this->input->get('periode_akhir');
		$no_penerimaan_barang = $this->input->get('no_penerimaan_barang');
		$this->report_laporan_penerimaan_by_packing_sheet->generatePdf($periode_awal, $periode_akhir, $no_penerimaan_barang);
	}

	public function download_excel_by_packing_sheet_with_amount()
	{
		$this->load->model('H3_md_report_laporan_penerimaan_by_packing_sheet_with_amount_model', 'report_laporan_penerimaan_by_packing_sheet_with_amount');

		$periode_awal = $this->input->get('periode_awal');
		$periode_akhir = $this->input->get('periode_akhir');
		$no_penerimaan_barang = $this->input->get('no_penerimaan_barang');
		$this->report_laporan_penerimaan_by_packing_sheet_with_amount->generatePdf($periode_awal, $periode_akhir, $no_penerimaan_barang);
	}

	public function download_excel_format_by_packing_sheet()
	{
		$this->load->model('H3_md_report_laporan_penerimaan_by_packing_sheet_model_excel_format', 'report_laporan_penerimaan_by_packing_sheet_excel_format');
		$no_penerimaan_barang = $this->input->get('no_penerimaan_barang');

		$data['report'] = $this->report_laporan_penerimaan_by_packing_sheet_excel_format->get_data($no_penerimaan_barang);
		$this->load->view("h3/laporan/h3_md_penerimaan_barang_download_excel_format_by_packing_sheet", $data);
	}

	public function get_count_surat_jalan_ahm_penerimaan_barang()
	{
		$no_penerimaan_barang_int = $this->input->get('no_penerimaan_barang_int');

		$count = $this->db
			->select('COUNT( DISTINCT(pbi.surat_jalan_ahm_int) ) as count', false)
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->where('pbi.no_penerimaan_barang_int', $no_penerimaan_barang_int)
			->get()->row_array();

		if ($count != null) {
			send_json([
				'count' => intval($count['count']),
			]);
		} else {
			send_json([
				'count' => 0,
			]);
		}
	}

	public function get_count_packing_sheet_penerimaan_barang()
	{
		$no_penerimaan_barang_int = $this->input->get('no_penerimaan_barang_int');

		$count = $this->db
			->select('COUNT( DISTINCT(pbi.packing_sheet_number_int) ) as count', false)
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->where('pbi.no_penerimaan_barang_int', $no_penerimaan_barang_int)
			->get()->row_array();

		if ($count != null) {
			send_json([
				'count' => intval($count['count']),
			]);
		} else {
			send_json([
				'count' => 0,
			]);
		}
	}
}
