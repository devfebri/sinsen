<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_pemenuhan_po_dealer extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_pemenuhan_po_dealer";
	protected $title  = "Pemenuhan Purchase Hotline Dealer";

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
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('h3_md_purchase_hotline_model', 'purchase_hotline');
		$this->load->model('h3_md_purchase_hotline_parts_model', 'purchase_hotline_parts');
		$this->load->model('h3_md_pemenuhan_po_model', 'pemenuhan_po');
		$this->load->model('h3_md_pemenuhan_po_parts_model', 'pemenuhan_po_parts');
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('stock_md_model', 'stock_md');
		$this->load->model('H3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po_dari_dealer');
		$this->load->model('H3_md_stock_model', 'stock');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('rd.status')
        ->select('rd.id_booking')
        ->select('rd.id_customer')
        ->select('c.nama_customer')
        ->select('c.no_identitas')
        ->select('c.no_hp as no_hp_customer')
        ->select('kel.kelurahan')
        ->select('kec.kecamatan')
        ->select('kab.kabupaten')
        ->select('prov.provinsi')
        ->select('c.alamat')
        ->select('c.no_polisi')
        ->select('tk.tipe_ahm as tipe_kendaraan')
        ->select('tk.deskripsi_ahm as deskripsi_unit')
        ->select('w.warna as deskripsi_warna')
        ->select('c.no_mesin')
        ->select('c.no_rangka')
        ->select('c.tahun_produksi')
        ->select('rd.id_data_pemesan')
        ->select('rd.masukkan_pemesan')
        ->select('prh.nama as nama_pemesan')
        ->select('prh.no_hp')
        ->select('sa_form.id_sa_form')
        ->select('wo.id_work_order')
        ->select('sa_form.no_buku_claim_c2')
        ->select('sa_form.no_claim_c2')
        ->select('rd.penomoran_ulang')
        ->select('rd.form_warranty_claim_c2_c2')
        ->select('rd.copy_faktur_ahm_claim_c1_c2')
        ->select('rd.gesekan_nomor_framebody_claim_c1_c2')
        ->select('rd.gesekan_nomor_crankcase_claim_c1_c2')
        ->select('rd.copy_ktp_claim_c1_c2')
        ->select('rd.copy_stnk_claim_c1_c2')
        ->select('rd.copy_bpkb_faktur_ahm_non_claim')
        ->select('rd.copy_stnk_non_claim')
        ->select('rd.copy_ktp_non_claim')
        ->select('rd.gesekan_nomor_framebody_non_claim')
        ->select('rd.gesekan_nomor_crankcase_non_claim')
        ->select('rd.potongan_no_rangka_mesin_non_claim')
        ->select('rd.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim')
        ->select('rd.surat_laporan_forensik_kepolisian_non_claim')
        ->select('rd.tipe_penomoran_ulang')
        ->select('rd.vor')
        ->select('rd.uang_muka')
        ->select('rd.job_return_flag')
        ->select('rd.ada_keterangan_tambahan')
        ->select('rd.keterangan_tambahan')
		->select('po.*')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
		->where('po.po_id', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_penerimaan_barang_items as pbi')
		->where('pbi.no_po = po_md.id_purchase_order', null, false)
		->where('pbi.id_part = pop_md.id_part', null, false)
		->get_compiled_select();

		$qty_po_hotline = $this->db
		->select("SUM(
			(pop_md.qty_order - IFNULL(({$qty_penerimaan}), 0))
		) as qty_order")
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
		->where('po_md.referensi_po_hotline = pop.po_id')
		->where('pop_md.id_part = pop.id_part')
		->where('po_md.status', 'Approved')
		->where('po_md.jenis_po', 'HTL')
		->get_compiled_select();

		$qty_po_urgent = $this->db
		->select("SUM(
			(pop_md.qty_order - IFNULL(({$qty_penerimaan}), 0))
		) as qty_order")
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
		->where('pop_md.referensi = pop.po_id')
		->where('pop_md.id_part = pop.id_part')
		->where('po_md.status', 'Approved')
		->where('po_md.jenis_po', 'URG')
		->get_compiled_select();

		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_penerimaan_barang_items as pbi', 'pbi.no_po = po_md.id_purchase_order')
		->where('po_md.referensi_po_hotline = pop.po_id', null, false)
		->where('pbi.id_part = pop.id_part', null, false)
		->get_compiled_select();

		if($data['purchase']->po_revisi=='1'){
			$parts = $this->db
			->select('pop.id_part')
			->select('pop.id_part_int')
			->select('p.nama_part')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.kuantitas as qty_order')
			->select('IFNULL(ppdd.qty_so, 0) as qty_so')
			->select("(IFNULL(({$qty_po_hotline}), 0) + IFNULL(({$qty_po_urgent}), 0)) as qty_po")
			->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
			->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
			->select("ppdd.qty_do")
			->select("( pop.kuantitas - IFNULL(ppdd.qty_so, 0) - IFNULL(ppdd.qty_supply, 0) - IFNULL(({$qty_penerimaan}), 0) - IFNULL(({$qty_po_urgent}), 0) - ifnull(ppdd.qty_do, 0) ) as qty_belum_terpenuhi")
			->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
			->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
			->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
			->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->join('tr_h3_dealer_purchase_order as po', 'po.id=pop.po_id_int')
			->join('tr_h3_dealer_request_document as rd', 'rd.id=po.id_booking_int')
			->join('tr_h3_dealer_request_document_parts as rdp', 'rd.id=rdp.id_booking_int and pop.id_part_int and rdp.id_part_int and rdp.id_part_int=opt.id_part_int and rdp.id_part_int=ppdd.id_part_int')
			->where('pop.po_id', $this->input->get('id'))
			// ->where('rdp.part_revisi_dari_md',0)
			->group_start()
				->where('rdp.part_revisi_dari_md',0)
				->or_where('rdp.revisi_part_dealer',1)
			->group_end()
			->get()->result_array();
		}else{
			$parts = $this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.id_part_int')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.kuantitas as qty_order')
			->select('IFNULL(ppdd.qty_so, 0) as qty_so')
			->select("(IFNULL(({$qty_po_hotline}), 0) + IFNULL(({$qty_po_urgent}), 0)) as qty_po")
			->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
			->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
			->select("ppdd.qty_do")
			->select("( pop.kuantitas - IFNULL(ppdd.qty_so, 0) - IFNULL(ppdd.qty_supply, 0) - IFNULL(({$qty_penerimaan}), 0) - IFNULL(({$qty_po_urgent}), 0) - ifnull(ppdd.qty_do, 0) ) as qty_belum_terpenuhi")
			->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
			->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
			->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
			->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('pop.po_id', $this->input->get('id'))
			->get()->result_array();
		}

		// $parts = $this->db
		// ->select('pop.id_part')
		// ->select('p.nama_part')
		// ->select('pop.id_part_int')
		// ->select('pop.harga_saat_dibeli as harga')
		// ->select('pop.kuantitas as qty_order')
		// ->select('IFNULL(ppdd.qty_so, 0) as qty_so')
		// ->select("(IFNULL(({$qty_po_hotline}), 0) + IFNULL(({$qty_po_urgent}), 0)) as qty_po")
		// ->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
		// ->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
		// ->select("ppdd.qty_do")
		// ->select("( pop.kuantitas - IFNULL(ppdd.qty_so, 0) - IFNULL(ppdd.qty_supply, 0) - IFNULL(({$qty_penerimaan}), 0) - IFNULL(({$qty_po_urgent}), 0) - ifnull(ppdd.qty_do, 0) ) as qty_belum_terpenuhi")
		// ->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
		// ->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
		// ->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
		// ->from('tr_h3_dealer_purchase_order_parts as pop')
		// ->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
		// ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
		// ->join('ms_part as p', 'p.id_part = pop.id_part')
		// ->where('pop.po_id', $this->input->get('id'))
		// ->get()->result_array();

		$data['parts'] = array_map(function($part){
			$part['qty_on_hand'] = $this->stock->qty_on_hand($part['id_part'], null);
			$part['qty_avs'] = $this->stock->qty_avs($part['id_part'], [$this->input->get('id')], false, true);
			return $part;
		}, $parts);

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('rd.status')
        ->select('rd.id_booking')
        ->select('rd.id_customer')
        ->select('c.nama_customer')
        ->select('c.no_identitas')
        ->select('c.no_hp as no_hp_customer')
        ->select('kel.kelurahan')
        ->select('kec.kecamatan')
        ->select('kab.kabupaten')
        ->select('prov.provinsi')
        ->select('c.alamat')
        ->select('c.no_polisi')
        ->select('tk.tipe_ahm as tipe_kendaraan')
        ->select('tk.deskripsi_ahm as deskripsi_unit')
        ->select('w.warna as deskripsi_warna')
        ->select('c.no_mesin')
        ->select('c.no_rangka')
        ->select('c.tahun_produksi')
        ->select('rd.id_data_pemesan')
        ->select('rd.masukkan_pemesan')
        ->select('prh.nama as nama_pemesan')
        ->select('prh.no_hp')
        ->select('sa_form.id_sa_form')
        ->select('wo.id_work_order')
        ->select('sa_form.no_buku_claim_c2')
        ->select('sa_form.no_claim_c2')
        ->select('rd.penomoran_ulang')
        ->select('rd.form_warranty_claim_c2_c2')
        ->select('rd.copy_faktur_ahm_claim_c1_c2')
        ->select('rd.gesekan_nomor_framebody_claim_c1_c2')
        ->select('rd.gesekan_nomor_crankcase_claim_c1_c2')
        ->select('rd.copy_ktp_claim_c1_c2')
        ->select('rd.copy_stnk_claim_c1_c2')
        ->select('rd.copy_bpkb_faktur_ahm_non_claim')
        ->select('rd.copy_stnk_non_claim')
        ->select('rd.copy_ktp_non_claim')
        ->select('rd.gesekan_nomor_framebody_non_claim')
        ->select('rd.gesekan_nomor_crankcase_non_claim')
        ->select('rd.potongan_no_rangka_mesin_non_claim')
        ->select('rd.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim')
        ->select('rd.surat_laporan_forensik_kepolisian_non_claim')
        ->select('rd.tipe_penomoran_ulang')
        ->select('rd.vor')
        ->select('rd.uang_muka')
        ->select('rd.job_return_flag')
        ->select('rd.ada_keterangan_tambahan')
        ->select('rd.keterangan_tambahan')
		->select('po.*')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
		->where('po.po_id', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_penerimaan_barang_items as pbi')
		->where('pbi.no_po = po_md.id_purchase_order', null, false)
		->where('pbi.id_part = pop_md.id_part', null, false)
		->get_compiled_select();

		$qty_po_hotline = $this->db
		->select("SUM(
			(pop_md.qty_order - IFNULL(({$qty_penerimaan}), 0))
		) as qty_order")
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
		->where('po_md.referensi_po_hotline = pop.po_id')
		->where('pop_md.id_part = pop.id_part')
		->where('po_md.status', 'Approved')
		->where('po_md.jenis_po', 'HTL')
		->get_compiled_select();

		$qty_po_urgent = $this->db
		->select("SUM(
			(pop_md.qty_order - IFNULL(({$qty_penerimaan}), 0))
		) as qty_order")
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
		->where('pop_md.referensi = pop.po_id')
		->where('pop_md.id_part = pop.id_part')
		->where('po_md.status', 'Approved')
		->where('po_md.jenis_po', 'URG')
		->get_compiled_select();

		$qty_penerimaan = $this->db
		->select('SUM(pbi.qty_diterima) as qty_diterima', false)
		->from('tr_h3_md_purchase_order as po_md')
		->join('tr_h3_md_penerimaan_barang_items as pbi', 'pbi.no_po = po_md.id_purchase_order')
		->where('po_md.referensi_po_hotline = pop.po_id', null, false)
		->where('pbi.id_part = pop.id_part', null, false)
		->get_compiled_select();

		if($data['purchase']->po_revisi=='1'){
			$this->db->set('status','Processed by MD')
						 ->set('status_md','Open PO')
						 ->set('proses_at',date('Y-m-d H:i:s', time()))
						 ->set('proses_by',$this->session->userdata('id_user'))
						 ->set('po_revisi',1)
				         ->where('po_id',$this->input->get('id'))
						 ->update('tr_h3_dealer_purchase_order');
						 
			$parts = $this->db
			->select('pop.id_part')
			->select('pop.id_part_int')
			->select('p.nama_part')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.kuantitas as qty_order')
			->select('IFNULL(ppdd.qty_so, 0) as qty_so')
			->select("(IFNULL(({$qty_po_hotline}), 0) + IFNULL(({$qty_po_urgent}), 0)) as qty_po")
			->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
			->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
			->select("ppdd.qty_do")
			->select("( pop.kuantitas - IFNULL(ppdd.qty_so, 0) - IFNULL(ppdd.qty_supply, 0) - IFNULL(({$qty_penerimaan}), 0) - IFNULL(({$qty_po_urgent}), 0) - ifnull(ppdd.qty_do, 0) ) as qty_belum_terpenuhi")
			->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
			->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
			->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
			->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->join('tr_h3_dealer_purchase_order as po', 'po.id=pop.po_id_int')
			->join('tr_h3_dealer_request_document as rd', 'rd.id=po.id_booking_int')
			->join('tr_h3_dealer_request_document_parts as rdp', 'rd.id=rdp.id_booking_int and pop.id_part_int and rdp.id_part_int and rdp.id_part_int=opt.id_part_int and rdp.id_part_int=ppdd.id_part_int')
			->where('pop.po_id', $this->input->get('id'))
			// ->where('rdp.part_revisi_dari_md',1)
			->group_start()
			->where('rdp.part_revisi_dari_md',0)
			->or_where('rdp.revisi_part_dealer',1)
			->group_end()
			->get()->result_array();
		}else{
			$parts = $this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.id_part_int')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.kuantitas as qty_order')
			->select('IFNULL(ppdd.qty_so, 0) as qty_so')
			->select("(IFNULL(({$qty_po_hotline}), 0) + IFNULL(({$qty_po_urgent}), 0)) as qty_po")
			->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
			->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
			->select("ppdd.qty_do")
			->select("(pop.kuantitas - IFNULL(ppdd.qty_so, 0) - IFNULL(ppdd.qty_supply, 0) - IFNULL(({$qty_po_hotline}), 0) - IFNULL(({$qty_po_urgent}), 0) - ifnull(ppdd.qty_do, 0)) as qty_belum_terpenuhi")
			->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
			->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
			->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
			->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('pop.po_id', $this->input->get('id'))
			->order_by('pop.id_part', 'asc')
			->get()->result_array();
		}

		// $parts = $this->db
		// ->select('pop.id_part')
		// ->select('p.nama_part')
		// ->select('pop.id_part_int')
		// ->select('pop.harga_saat_dibeli as harga')
		// ->select('pop.kuantitas as qty_order')
		// ->select('IFNULL(ppdd.qty_so, 0) as qty_so')
		// ->select("(IFNULL(({$qty_po_hotline}), 0) + IFNULL(({$qty_po_urgent}), 0)) as qty_po")
		// ->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
		// ->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
		// ->select("ppdd.qty_do")
		// ->select("(pop.kuantitas - IFNULL(ppdd.qty_so, 0) - IFNULL(ppdd.qty_supply, 0) - IFNULL(({$qty_po_hotline}), 0) - IFNULL(({$qty_po_urgent}), 0) - ifnull(ppdd.qty_do, 0)) as qty_belum_terpenuhi")
		// ->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
		// ->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
		// ->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
		// ->from('tr_h3_dealer_purchase_order_parts as pop')
		// ->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
		// ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
		// ->join('ms_part as p', 'p.id_part = pop.id_part')
		// ->where('pop.po_id', $this->input->get('id'))
		// ->order_by('pop.id_part', 'asc')
		// ->get()->result_array();

		$data['parts'] = array_map(function($part){
			$part['qty_on_hand'] = $this->stock->qty_on_hand($part['id_part'], null);
			$part['qty_avs'] = $this->stock->qty_avs($part['id_part'], [$this->input->get('id')], false, true);
			return $part;
		}, $parts);

		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();
		foreach ($this->input->post('parts') as $part) {
			$condition = [
				'id_part' => $part['id_part'],
				'po_id' => $this->input->post('po_id')
			];
			$data_update = $this->get_in_array(['qty_pemenuhan', 'qty_hotline', 'qty_urgent'], $part);

			$pemenuhan_po_dari_dealer = $this->pemenuhan_po_dari_dealer->get($condition, true);
			if($pemenuhan_po_dari_dealer != null){
				$this->pemenuhan_po_dari_dealer->update($data_update, $condition);
			}else{
				$this->pemenuhan_po_dari_dealer->insert(
					array_merge($data_update, $condition)
				);
			}

			//Update/Insert data qty booking ke stok part summary
			$check_id_part = $this->db->select('id_part_int')
								  ->from('tr_stok_part_summary')
								  ->where('id_part_int',$part['id_part_int'])->get()->row_array();
			if($check_id_part['id_part_int']!=NULL){
				$this->db->set('qty_book', "qty_book + {$part['qty_pemenuhan']}", false);
				$this->db->where('id_part_int', $part['id_part_int']);
				$this->db->update('tr_stok_part_summary');
			}else{
				$data = array(
					'id_part' => $part['id_part'],
					'id_part_int' => $part['id_part_int'],
					'qty_book' => $part['qty_pemenuhan']
				);
				$this->db->insert('tr_stok_part_summary', $data);
			}
			
		}

		$this->purchase_order->set_tanggal_po_md($this->input->post('po_id'));
		$this->purchase_order->set_proses_book($this->input->post('po_id'));

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$purchase_order = $this->purchase_order->find($this->input->post('po_id'), 'po_id');
			send_json($purchase_order);
		} else {
			$this->output->set_status_header(400);
		}
	}
}
