<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_purchase_order extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_purchase_order";
	protected $title  = "Purchase Order";

	public function __construct(){		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->library('Mcarbon');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_purchase_order_model', 'purchase_order');
		$this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('H3_md_history_estimasi_waktu_hotline_model', 'history_estimasi_waktu_hotline');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('part_model', 'master_part');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
	}

	public function index(){
		if($this->session->userdata('group') == 72 && $this->session->userdata('id_user') != 2015){
			echo 'You dont have access!';
			die();
		}
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['purchase_order'] = $this->purchase_order->all();
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";

		if($this->input->get('generatePOLogistik') != null){
			$data['purchase'] = $this->db
			->select('pol.id_po_logistik')
			->select('"URG" as jenis_po')
			->from('tr_h3_md_po_logistik as  pol')
			->where('pol.id_po_logistik', $this->input->get('id_po_logistik'))
			->limit(1)
			->get()->row_array();

			$data['parts'] = $this->db
			->select('polpd.dokumen_nrfs_id as referensi')
			->select('"logistik" as tipe_referensi')
			->select('d.id_dealer')
			->select('d.nama_dealer')
			->select('polpd.id_part')
			->select('p.nama_part')
			->select('p.harga_md_dealer as harga')
			->select('polpd.type_code as id_tipe_kendaraan')
			->select('tk.tipe_ahm')
			->select('polpd.qty_po_ahm as qty_order')
			->select('1 as checked')
			->select('sps.qty as qty_on_hand')
			->select('sps.qty_intransit as qty_in_transit')
			->from('tr_h3_md_po_logistik_parts_detail as polpd')
			->join('tr_dokumen_nrfs as nrfs', 'nrfs.dokumen_nrfs_id = polpd.dokumen_nrfs_id')
			->join('ms_dealer as d', 'd.id_dealer = nrfs.id_dealer')
			->join('ms_part as p', 'p.id_part = polpd.id_part')
			->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
			->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = polpd.type_code', 'left')
			->where('polpd.id_po_logistik', $this->input->get('id_po_logistik'))
			->where('polpd.qty_po_ahm >', 0)
			->get()->result_array();
			$data['parts'] = array_map(function($row){
				// $row['qty_in_transit'] = $this->stock->qty_intransit($row['id_part']);
				// $row['qty_on_hand'] = $this->stock->qty_on_hand($row['id_part']);
				return $row;
			}, $data['parts']);
		}
		$this->template($data);
	}

	public function save(){
		$this->validate();
		$data = array_merge($this->input->post(['jenis_po', 'keterangan', 'bulan', 'tahun', 'tanggal_po', 'total_amount', 'referensi_po_hotline', 'id_po_logistik', 'produk']), [
			'id_purchase_order' => $this->purchase_order->generateID($this->input->post('jenis_po'), $this->input->post('produk'),$this->input->post('bulan')),
		]);
		$data['tanggal_po'] = $data['tanggal_po'] == null ? date('Y-m-d', time()) : $data['tanggal_po'];
		$purchase = $this->clean_data($data);
		$this->purchase_order->insert($purchase);
		$id = $this->db->insert_id();

		$this->load->helper('array');

		$parts = [];
		$histories = [];
		foreach($this->input->post('parts') as $row){
			$part = elements([
				'id_part', 'qty_min_order',
				'qty_in_transit','qty_bo','avg_sales',
				'qty_bo_dealer','fix_bulan_lalu',
				'qty_on_hand','qty_suggest',
				'qty_order','harga',
				// untuk PO hotline
				'id_dealer', 'referensi', 'etd', 'eta',
				// untuk PO Urgent
				'id_tipe_kendaraan',
			], $row);

			$id_part_int = $this->db->select('id_part_int')
									->from('ms_part')
									->where('id_part',$part['id_part'])
									->get()->row_array();

			$part['id_purchase_order'] = $purchase['id_purchase_order'];
			$part['id_purchase_order_int'] = $id;
			$parts[] = $part;

			$history = elements(['id_part', 'eta', 'etd'], $row);
			$history['id_part_int'] = $id_part_int['id_part_int'];
			$history['source'] = 'setting_master';
			$history['po_id'] = $this->input->post('referensi_po_hotline');
			$history['id_purchase_order'] = $purchase['id_purchase_order'];
			$histories[] = $history;
		}

		$this->db->trans_start();
		$this->purchase_order_parts->insert_batch($parts);
		if($this->input->post('jenis_po') == 'HTL' AND count($histories) > 0){
			$this->history_estimasi_waktu_hotline->insert_batch($histories);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Purchase order berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');

			$purchase = (array) $this->purchase_order->find($purchase['id_purchase_order'], 'id_purchase_order');
			send_json([
				'message' => 'Berhasil membuat PO',
				'payload' => $purchase,
				'redirect_url' => base_url('h3/h3_md_purchase_order/detail?id_purchase_order=' . $purchase['id_purchase_order'])
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil membuat PO'
			], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('po.*')
		->select('rd.status')
        ->select('rd.id_dealer')
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
		->select('date_format(po.tanggal_po, "%d-%m-%Y") as tanggal_po_formatted')
		->select('po.status')
		->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_dealer_purchase_order as po_dealer', 'po_dealer.po_id = po.referensi_po_hotline', 'left')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po_dealer.id_booking', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->limit(1)
		->get()->row_array();


		if($data['purchase']['jenis_po']=='HTL' && $data['purchase']['status']=='Reject & Revisi by MD'){
			$parts = $this->db
					->select('pop.id_purchase_order,pop.id_purchase_order_int,pop.id_part_int,pop.id_part,pop.referensi,pop.tipe_referensi,pop.id_dealer,pop.qty_min_order,pop.qty_in_transit,pop.qty_bo,pop.avg_sales, pop.qty_bo_dealer,pop.fix_bulan_lalu, pop.qty_on_hand,pop.qty_suggest,pop.qty_order, pop.harga,pop.etd,pop.eta,date_format(pop.eta_revisi, "%d/%m/%Y") as eta_revisi')
					->select('tk.tipe_ahm')
					->select('p.nama_part')
					->select('p.kelompok_part')
					->select('p.import_lokal')
					->select('p.current')
					->select('p.hoo_flag')
					->select('p.hoo_max')
					->select('p.minimal_order')
					->select('d.nama_dealer')
					->select('1 as checked')
					->select('rqd.part_revisi_dari_md')
					->select('(CASE WHEN rqd.alasan_part_revisi_md = "discontinue" THEN "Discontinue" WHEN rqd.alasan_part_revisi_md = "part_set" THEN "Part Set" WHEN rqd.alasan_part_revisi_md = "supersede" THEN "Supersede" WHEN rqd.alasan_part_revisi_md = "lainnya" THEN "Lainnya" else "-" end) as alasan_part_revisi_md')
					->from('tr_h3_md_purchase_order_parts as pop')
					->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
					->join('ms_dealer as d', 'd.id_dealer = pop.id_dealer', 'left')
					->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = pop.id_tipe_kendaraan', 'left')
					->join('tr_h3_md_purchase_order as po','pop.id_purchase_order_int=po.id')
					->join('tr_h3_dealer_purchase_order as po_dealer', 'po_dealer.po_id = po.referensi_po_hotline')
					->join('tr_h3_dealer_purchase_order_parts as pop_dealer', 'po_dealer.po_id = pop_dealer.po_id and pop_dealer.id_part_int=pop.id_part_int')
					->join('tr_h3_dealer_request_document rd', 'rd.id_booking = po_dealer.id_booking')
					->join('tr_h3_dealer_request_document_parts rqd', 'rqd.id_booking = rd.id_booking and rqd.id_part_int=pop.id_part_int' )
					->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
					->get()->result_array();
		}else{
			$parts = $this->db
					->select('pop.*')
					->select('date_format(pop.eta_revisi, "%d/%m/%Y") as eta_revisi')
					->select('tk.tipe_ahm')
					->select('p.nama_part')
					->select('p.kelompok_part')
					->select('p.import_lokal')
					->select('p.current')
					->select('p.hoo_flag')
					->select('p.hoo_max')
					->select('p.minimal_order')
					->select('d.nama_dealer')
					->select('1 as checked')
					->from('tr_h3_md_purchase_order_parts as pop')
					->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
					->join('ms_dealer as d', 'd.id_dealer = pop.id_dealer', 'left')
					->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = pop.id_tipe_kendaraan', 'left')
					->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
					->get()->result_array();
		}

		// $parts = $this->db
		// ->select('pop.*')
		// ->select('date_format(pop.eta_revisi, "%d/%m/%Y") as eta_revisi')
		// ->select('tk.tipe_ahm')
		// ->select('p.nama_part')
		// ->select('p.kelompok_part')
		// ->select('p.import_lokal')
		// ->select('p.current')
		// ->select('p.hoo_flag')
		// ->select('p.hoo_max')
		// ->select('p.minimal_order')
		// ->select('d.nama_dealer')
		// ->select('1 as checked')
		// ->from('tr_h3_md_purchase_order_parts as pop')
		// ->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		// ->join('ms_dealer as d', 'd.id_dealer = pop.id_dealer', 'left')
		// ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = pop.id_tipe_kendaraan', 'left')
		// ->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		// ->get()->result_array();

		$pesan_untuk_bulan = null;
        if($data['purchase']['bulan'] != null and $data['purchase']['tahun'] != null){
            $tahun = Mcarbon::parse($data['purchase']['tahun']);
            $bulan = Mcarbon::parse($data['purchase']['bulan']);

			$pesan_untuk_bulan = Mcarbon::parse("{$tahun->format('Y')}-{$bulan->format('m')}-01");
        }else{
			$pesan_untuk_bulan = Mcarbon::parse($data['purchase']['tanggal_po']);
		}

		$bulan_berjalan = Mcarbon::parse($data['purchase']['tanggal_po']);

		// $parts = array_map(function($part) use ($pesan_untuk_bulan, $data, $bulan_berjalan) {
		// 	$part['qty_on_hand'] = $this->stock_int->qty_on_hand($part['id_part_int']);
        //     $part['qty_avs'] = $this->stock_int->qty_avs($part['id_part_int']);
		// 	$part['qty_in_transit'] = $this->stock_int->qty_intransit($part['id_part_int']);
        //     $part['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($part['id_part_int'], $pesan_untuk_bulan);
        //     $part['avg_sales'] = round($this->do_sales_order->qty_avg_sales($part['id_part_int'], 'id_part_int'));
        //     $part['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($part['id_part_int'], $data['purchase']['jenis_po'], $bulan_berjalan, $pesan_untuk_bulan);
        //     $part['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($part['id_part_int'], $data['purchase']['jenis_po'], $bulan_berjalan, $pesan_untuk_bulan);
		// 	return $part;
		// }, $parts);

		$part['qty_on_hand'] = 0;
		$part['qty_avs'] = 0;
		$part['qty_in_transit'] = 0;
		$part['fix_bulan_lalu'] = 0;
		$part['avg_sales'] = 0;
		$part['qty_bo'] = 0;
		$part['qty_bo_dealer'] = 0;

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('po.*')
		->select('rd.status')
        ->select('rd.id_dealer')
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
		->select('date_format(po.tanggal_po, "%d-%m-%Y") as tanggal_po_format')
		->select('po.status')
		->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_dealer_purchase_order as po_dealer', 'po_dealer.po_id = po.referensi_po_hotline', 'left')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po_dealer.id_booking', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
        // ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->limit(1)
		->get()->row_array();

		$parts = $this->db
		->select('pop.*')
		->select('tk.tipe_ahm')
		->select('p.nama_part')
		->select('p.kelompok_part')
		->select('p.minimal_order')
		->select('d.nama_dealer')
		->select('1 as checked')
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->join('ms_dealer as d', 'd.id_dealer = pop.id_dealer', 'left')
		->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = pop.id_tipe_kendaraan', 'left')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$pesan_untuk_bulan = null;
        if($data['purchase']['bulan'] != null and $data['purchase']['tahun'] != null){
            $tahun = Mcarbon::parse($data['purchase']['tahun']);
            $bulan = Mcarbon::parse($data['purchase']['bulan']);

			$pesan_untuk_bulan = Mcarbon::parse("{$tahun->format('Y')}-{$bulan->format('m')}-01");
        }else{
			$pesan_untuk_bulan = Mcarbon::parse($data['purchase']['tanggal_po']);
		}

		$bulan_berjalan = Mcarbon::parse($data['purchase']['tanggal_po']);

		// $parts = array_map(function($part) use ($pesan_untuk_bulan, $data, $bulan_berjalan) {
		// 	$part['qty_on_hand'] = $this->stock_int->qty_on_hand($part['id_part_int']);
        //     $part['qty_avs'] = $this->stock_int->qty_avs($part['id_part_int']);
		// 	$part['qty_in_transit'] = $this->stock_int->qty_intransit($part['id_part_int']);
        //     $part['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($part['id_part_int'], $pesan_untuk_bulan);
        //     $part['avg_sales'] = round($this->do_sales_order->qty_avg_sales($part['id_part_int'], 'id_part_int'));
        //     $part['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($part['id_part_int'], $data['purchase']['jenis_po'], $bulan_berjalan, $pesan_untuk_bulan);
        //     $part['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($part['id_part_int'], $data['purchase']['jenis_po'], $bulan_berjalan, $pesan_untuk_bulan);
		// 	return $part;
		// }, $parts);
		
		$part['qty_on_hand'] = 0;
		$part['qty_avs'] = 0;
		$part['qty_in_transit'] = 0;
		$part['fix_bulan_lalu'] = 0;
		$part['avg_sales'] = 0;
		$part['qty_bo'] = 0;
		$part['qty_bo_dealer'] = 0;

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$purchase = $this->input->post(['jenis_po', 'keterangan', 'bulan', 'tahun', 'tanggal_po', 'referensi_po_hotline', 'total_amount', 'produk']);
		$purchase = $this->clean_data($purchase);
		
		$parts = $this->getOnly([
			'id_part', 'qty_min_order',
			'qty_in_transit','qty_bo','avg_sales',
			'qty_bo_dealer','fix_bulan_lalu',
			'qty_on_hand','qty_suggest',
			'qty_order','harga',
			// untuk PO hotline
			'id_dealer', 'referensi', 'etd', 'eta',
			// untuk PO Urgent
			'id_tipe_kendaraan',
		], $this->input->post('parts'), [
			'id_purchase_order' => $this->input->post('id_purchase_order'),
			'id_purchase_order_int' => $this->input->post('id'),
		]);

		$this->db->trans_start();
		$this->purchase_order->update($purchase, $this->input->post(['id_purchase_order']));
		$this->purchase_order_parts->update_batch($parts, $this->input->post(['id_purchase_order']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Purchase order berhasil di perbarui.');
			$this->session->set_flashdata('tipe', 'info');

			$purchase = (array) $this->purchase_order->get($this->input->post(['id_purchase_order']), true);
			send_json([
				'message' => 'Berhasil memperbarui PO',
				'payload' => $purchase,
				'redirect_url' => base_url('h3/h3_md_purchase_order/detail?id_purchase_order=' . $this->input->post('id_purchase_order'))
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil memperbarui PO'
			], 422);
		}
	}

	public function approve(){
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order_dealer');

		$this->db->trans_start();
		$purchase = (array) $this->purchase_order->get($this->input->get(['id_purchase_order']), true);

		if($purchase['jenis_po'] == 'URG'){
			$parts = $this->db
			->select('pop.referensi')
			->select('pop.id_part')
			->select('po.jenis_po')
			->from('tr_h3_md_purchase_order_parts as pop')
			->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = pop.id_purchase_order')
			->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
			->get()->result_array();
	
			foreach ($parts as $part) {
				if($part['jenis_po'] == 'URG'){
					$this->db->set('ppdd.qty_urgent', 0);
				}else if($part['jenis_po'] == 'HTL'){
					$this->db->set('ppdd.qty_hotline', 0);
				}
	
				$this->db
				->where('ppdd.po_id', $part['referensi'])
				->where('ppdd.id_part', $part['id_part'])
				->update('tr_h3_md_pemenuhan_po_dari_dealer as ppdd');

				$this->purchase_order_dealer->set_tanggal_po_ahm($part['referensi']);
				$this->purchase_order_dealer->set_processed_by_ahm($part['referensi']);
			}
		}elseif($purchase['jenis_po'] == 'HTL'){
			$parts = $this->db
			->select('po.referensi_po_hotline')
			->select('pop.id_part')
			->from('tr_h3_md_purchase_order_parts as pop')
			->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = pop.id_purchase_order')
			->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
			->get()->result_array();

			foreach ($parts as $part) {
				$this->db
				->set('ppdd.qty_hotline', 0)
				->where('ppdd.po_id', $part['referensi_po_hotline'])
				->where('ppdd.id_part', $part['id_part'])
				->update('tr_h3_md_pemenuhan_po_dari_dealer as ppdd');

				$this->purchase_order_dealer->set_tanggal_po_ahm($part['referensi_po_hotline']);
				$this->purchase_order_dealer->set_processed_by_ahm($part['referensi_po_hotline']);
			}
		}

		$this->update_niguri($this->input->get('id_purchase_order'));

		$this->purchase_order->update([
			'status' => 'Approved',
			'approved_at' => date('Y-m-d H:i:s', time()),
			'approved_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_purchase_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO Telah disetujui.');
			$this->session->set_flashdata('tipe', 'info');
			redirect(
				base_url("h3/$this->page/detail?id_purchase_order={$this->input->get('id_purchase_order')}")
			);
		}else{
			$this->session->set_flashdata('pesan', 'PO tidak berhasil disetujui.');
			$this->session->set_flashdata('tipe', 'danger');
			redirect(
				base_url("h3/{$this->page}")
			);
		}
	}

	private function update_niguri($id_purchase_order){
		$this->db
		->select('po.id_purchase_order')
		->select('po.tanggal_po')
		->select('po.jenis_po')
		->select('DATE_FORMAT(po.bulan, "%m") as bulan', false)
		->select('DATE_FORMAT(po.tahun, "%Y") as tahun', false)
		->select('pop.id_part')
		->select('ROUND(n.qty_reguler) AS qty_reguler', false)
        ->select('n.id')
        ->select('n.id_part')
        ->select('n.fix_order_n')
        ->select('n.fix_order_n_1')
        ->select('n.fix_order_n_2')
        ->select('n.fix_order_n_3')
        ->select('n.fix_order_n_4')
        ->select('n.fix_order_n_5')
		->select('pop.qty_order as kuantitas')
		->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_md_purchase_order_parts as pop', 'pop.id_purchase_order = po.id_purchase_order')
		->join('tr_h3_md_niguri_header as nh', '(nh.bulan = DATE_FORMAT(po.tanggal_po, "%m") AND nh.tahun = DATE_FORMAT(po.tanggal_po, "%Y") AND nh.type_niguri = po.jenis_po)')
		->join('tr_h3_md_niguri as n', '(n.id_niguri_header = nh.id AND n.id_part = pop.id_part)')
		->where('po.id_purchase_order', $id_purchase_order)
		->group_start()
		->where('po.jenis_po', 'FIX')
		->or_where('po.jenis_po', 'REG')
		->group_end()
		;

		foreach ($this->db->get()->result_array() as $row) {
			if($row['jenis_po'] == 'FIX'){
				$tanggal_order = Mcarbon::parse($row['tanggal_po'])->startOfMonth();
				$pemesan_untuk_bulan = Mcarbon::parse("{$row['tahun']}-{$row['bulan']}-01")->startOfMonth();
				$perbedaan_bulan = $pemesan_untuk_bulan->diffInMonths($tanggal_order);

				$key_fix_order = 'fix_order_n';

				if($perbedaan_bulan > 0 AND $perbedaan_bulan <= 5){
					$key_fix_order .= '_' . $perbedaan_bulan;
				}

				if( floatval($row[$key_fix_order]) != floatval($row['kuantitas']) ){
					$this->db
					->set($key_fix_order, $row['kuantitas'])
					->where('id', $row['id'])
					->update('tr_h3_md_niguri');

					$niguri_reguler = $this->db
					->select('n.id')
					->from('tr_h3_md_niguri as n')
					->join('tr_h3_md_niguri_header as nh', 'nh.id = n.id_niguri_header')
					->where('n.id_part', $row['id_part'])
					->where('nh.type_niguri', 'REG')
					->where('nh.bulan', $tanggal_order->format('m'))
					->where('nh.tahun', $tanggal_order->format('Y'))
					->limit(1)
					->get()->row_array();

					if($niguri_reguler != null){
						$this->db
						->set($key_fix_order, $row['kuantitas'])
						->where('id', $niguri_reguler['id'])
						->update('tr_h3_md_niguri');
					}
				}
			}elseif($row['jenis_po'] == 'REG'){
				if( floatval($row['qty_reguler']) != floatval($row['kuantitas']) ){
					$this->db
					->set('qty_reguler', $row['kuantitas'])
					->where('id', $row['id'])
					->update('tr_h3_md_niguri');
				}
			}
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->purchase_order->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_purchase_order']));

		$id_po_md = $this->input->get('id_purchase_order');
		
		$no_po_dealer = $this->db->select('po.referensi_po_hotline')
								->from('tr_h3_md_purchase_order as po')
								->where('po.id_purchase_order',$id_po_md)->get()->row_array();

		$check_pemenuhan_po_dealer = $this->db->select('SUM(ppdd.qty_pemenuhan + ppdd.qty_urgent) as pemenuhan')
											->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd')
											->where('ppdd.po_id',$no_po_dealer['referensi_po_hotline'])
											->get()->row_array();

		if($check_pemenuhan_po_dealer['pemenuhan'] == 0){
			$this->db->set('status', 'Canceled')
					->set('status_md', 'Canceled')
					->set('cancel_at', date('Y-m-d H:i:s', time()))
					->set('cancel_by', $this->session->userdata('id_user'))
					->where('po_id', $no_po_dealer['referensi_po_hotline'])
					->update('tr_h3_dealer_purchase_order');
		}
				
		$this->db->trans_complete();

		if($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO berhasil di batalkan.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id_purchase_order={$this->input->get('id_purchase_order')}'>";
		}else{
			$this->session->set_flashdata('pesan', 'PO tidak berhasil di batalkan.');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}

	public function close(){
		$this->db->trans_start();
		$this->purchase_order->update([
			'status' => 'Closed',
			'closed_at' => date('Y-m-d H:i:s', time()),
			'closed_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_purchase_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO berhasil diclose.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id_purchase_order={$this->input->get('id_purchase_order')}'>";
		}else{
			$this->session->set_flashdata('pesan', 'PO tidak berhasil diclose.');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('jenis_po', 'Jenis PO', 'required');
		
		if($this->input->post('jenis_po') == 'FIX' || $this->input->post('jenis_po') == 'REG'){
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
			$this->form_validation->set_rules('bulan', 'Bulan', 'required');
			$this->form_validation->set_rules('tahun', 'Tahun', 'required');
		}
		
		if($this->input->post('jenis_po') == 'HTL'){
			$this->form_validation->set_rules('tanggal_po', 'Tanggal PO', 'required');
		}

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
	}
	
	public function parts_by_referensi_so_atau_po(){
		$referensi = $this->input->get('referensi');
		$jenis_po = $this->input->get('jenis_po');
		$parts = [];

		$po_logistik = $this->db
		->select('po.id')
		->select('po.po_logistik')
		->from('tr_h3_dealer_purchase_order as po')
		->where('po.po_logistik', 1)
		->where('po.po_id', $this->input->get('referensi'))
		->get()->row_array();

		$this->db
		->select('po.po_id as referensi')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.harga_md_dealer as harga')
		->select('1 as checked')
		->select('sps.qty as qty_on_hand')
		->select('sps.qty_intransit as qty_in_transit')
		->select("
			case
				when '{$jenis_po}' = 'HLO' then ifnull(ppdd.qty_hotline, 0)
				when '{$jenis_po}' = 'URG' then ifnull(ppdd.qty_urgent, 0)
				else 0
			end as qty_order
		", false)
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('tr_h3_dealer_purchase_order as po', 'po.id = pop.po_id_int')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
		->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.po_id_int = po.id and ppdd.id_part_int = pop.id_part_int)', 'left')
		->where('pop.po_id', $referensi)
		->having('qty_order >', 0)
		;

		if ($jenis_po == 'HTL') {
			$this->db
			->select('c.id_tipe_kendaraan')
			->select('tk.tipe_ahm')
			->join('tr_h3_dealer_request_document as rd', 'po.id_booking_int = rd.id', 'left')
			->join('ms_customer_h23 as c', 'c.id_customer_int = rd.id_customer_int', 'left')
			->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left');
			// $this->db->select('ifnull(ppdd.qty_hotline, 0) as qty_order');
		}else if ($jenis_po == 'URG') {
			$this->db
			->select('nrfs.type_code as id_tipe_kendaraan')
			->select('tk.tipe_ahm')
			->join('tr_dokumen_nrfs as nrfs', 'nrfs.dokumen_nrfs_id = po.dokumen_nrfs_id', 'left')
			->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = nrfs.type_code', 'left');
			// $this->db->select('ifnull(ppdd.qty_urgent, 0) as qty_order');
		}

		$parts = array_map(function($data){
			// $data['qty_on_hand'] = $this->stock->qty_on_hand($data['id_part']);
			// $data['qty_in_transit'] = $this->stock->qty_intransit($data['id_part']);
			return $data;
		}, $this->db->get()->result_array());
		
		send_json($parts);
	}

	public function parts_by_referensi_po_hotline(){
		$referensi_po_hotline = $this->input->get('referensi_po_hotline');
		$parts = [];

		$this->db
		->select('ifnull(ppdd.qty_hotline, 0) as qty_order')
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.harga_md_dealer as harga')
		->select('1 as checked')
		->select('sps.qty as qty_on_hand')
		->select('sps.qty_intransit as qty_in_transit')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
		->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.po_id = pop.po_id and ppdd.id_part = pop.id_part)', 'left')
		->where('pop.po_id', $referensi_po_hotline)
		->having('qty_order >', 0)
		;

		$parts = array_map(function($data){
			// $data['qty_on_hand'] = $this->stock->qty_on_hand($data['id_part']);
			// $data['qty_in_transit'] = $this->stock->qty_intransit($data['id_part']);
			$data['etd'] = null;
			$data['eta'] = null;
			return $data;
		}, $this->db->get()->result_array());
		
		send_json($parts);
	}

	public function update_eta_parts(){
        $this->load->model('h3_md_etd_model', 'etd');
        $result = [];
        foreach ($this->input->post('parts') as $part) {
            $data = $this->etd->get_estimated_time_delivery($part, $this->input->post('claim'), $this->input->post('tipe_claim'), $this->input->post('id_dealer'));
            if($data != null){
                $result[] = $data;
            }
        }
        
        send_json($result);
    }

	public function get_request_document(){
		$request_document = $this->db
		->select('rd.status')
        ->select('rd.id_dealer')
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
		->from('tr_h3_dealer_purchase_order as po_dealer')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po_dealer.id_booking')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        // ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
		->where('po_dealer.po_id', $this->input->get('referensi_po_hotline'))
		->limit(1)
		->get()->row_array();

		$parts = [];
		$this->db
		->select('ifnull(ppdd.qty_hotline, 0) as qty_order')
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.harga_md_dealer as harga')
		->select('p.import_lokal')
		->select('p.current')
		->select('p.hoo_flag')
		->select('p.hoo_max')
		->select('1 as checked')
		->select('sps.qty as qty_on_hand')
		->select('sps.qty_intransit as qty_in_transit')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
		->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.po_id = pop.po_id and ppdd.id_part = pop.id_part)', 'left')
		->where('pop.po_id', $this->input->get('referensi_po_hotline'))
		->having('qty_order >', 0)
		;

		$parts = array_map(function($data){
			// $data['qty_on_hand'] = $this->stock->qty_on_hand($data['id_part']);
			// $data['qty_in_transit'] = $this->stock->qty_intransit($data['id_part']);
			// $data['qty_in_transit'] = 0;
			$data['etd'] = null;
			$data['eta'] = null;
			return $data;
		}, $this->db->get()->result_array());

		send_json([
			'request_document' => $request_document,
			'parts' => $parts
		]);
	}

	public function generate_ppo(){
		$result = $this->query_ppo();
		// $result = $this->set_null_to_empty_string($result);
		// $result = $this->append_data($result);
		// send_json($result);
		$lines = $this->parse_data($result);

		$filename = str_replace('/', '', $this->input->get('id_purchase_order'));
		$filename = str_replace('-', '', $filename);
		$filename = "AHM-E20-{$filename}.PPO";

		$this->load->helper('download');
		force_download($filename, $lines);
	}

	public function query_ppo(){
		$this->db
		->select('"E20" as kode_md')
		->select('date_format(po.tanggal_po, "%d%m%Y") as tanggal_po')
		->select('po.jenis_po')
		->select('po.id_purchase_order')
		->select('pop.id_part')
		->select('pop.qty_order')
		->select('
			case
				when po.jenis_po = "FIX" then CONCAT( "01", SUBSTRING(po.bulan, 6, 2), SUBSTRING(po.tahun, 1, 4) )
				else date_format(po.tanggal_po, "%d%m%Y")
			end as delivery_date
		', false)
		->select('"" as claim_no')
		->select('case
			when po.jenis_po = "HTL" then ""
			else "N"
		end as additional_order', false)
		->select('"E20" as qq_code')
		->select('SUBSTR(c.nama_customer, 1, 50) as nama_konsumen')
		->select('SUBSTR(c.alamat, 1, 50) as alamat_konsumen')
		->select('prov.provinsi as kota')
		->select('kel.kode_pos')
		->select('c.id_tipe_kendaraan as tipe_motor')
		->select('date_format(tk.tgl_awal, "%Y") as tahun_perakitan')
		->select('
		case
			when po.jenis_po = "FIX" then "E20"
			else d.kode_dealer_md
		end as kode_dealer', false)
		->select('date_format(po_dealer.tanggal_order, "%d%m%Y") as tgl_po_dealer')
		->select('c.no_hp')
		->select('
			case
				when rd.vor = 1 then "Y"
				when rd.vor = 0 then "N"
			end as vor
		', false)
		->select('
			case
				when rd.job_return_flag = 1 then "Y"
				when rd.job_return_flag = 0 then "N"
			end as job_return_flag
		', false)
		->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_md_purchase_order_parts as pop', 'po.id_purchase_order = pop.id_purchase_order')
		->join('tr_h3_dealer_purchase_order as po_dealer', 'po_dealer.po_id = po.referensi_po_hotline', 'left')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po_dealer.id_booking', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
		->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
		->join('ms_dealer as d', 'd.id_dealer = po_dealer.id_dealer', 'left')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->order_by('pop.id_part', 'asc');

		return $this->db->get()->result_array();
	}

	public function set_null_to_empty_string($data){
		$result = [];
		foreach ($data as $row) {
			$new_row = [];
			foreach ($row as $key => $value) {
				if($value == null){
					$new_row[$key] = '';
				}else{
					$new_row[$key] = $value;
				}
			}
			$result[] = $new_row;
		}
		return $result;
	}

	public function append_data($data){
		$string_length_by_key = [
			'kode_md' => 5,
			'tanggal_po' => 8,
			'jenis_po' => 3,
			'id_purchase_order' => 30,
			'item_po' => 5,
			'id_part' => 25,
			'qty_order' => 10,
			'delivery_date' => 8,
			'claim_no' => 28,
			'additional_order' => 1,
			'qq_code' => 5,
			'nama_konsumen' => 50,
			'alamat_konsumen' => 50,
			'kota' => 30,
			'kode_pos' => 10,
			'tipe_motor' => 3,
			'tahun_perakitan' => 4,
			'kode_dealer' => 5,
			'tgl_po_dealer' => 8,
		];

		$result = [];
		foreach ($data as $row) {
			$new_row = [];
			foreach ($row as $key => $value) {
				if(isset($string_length_by_key[$key])){
					$append_string = '';
					for ($i=0; $i < ($string_length_by_key[$key] - strlen($value)); $i++) { 
						$append_string .= ' ';
					}
					$new_row[$key] = $value . $append_string;
				}else{
					$new_row[$key] = $value;
				}
			}
			$result[] = $new_row;
		}
		return $result;
	}

	public function parse_data($result){
		$lines = '';
		$item_po = 1;
		$length = count($result);
		foreach ($result as $each) {
			$line = '';
			$column = 1;
			foreach ($each as $key => $value) {
				if($column == 5){
					$line .= $item_po . ";";
				}
				$line .= $value . ";";
				$column++;
			}
			$lines .= $line;
			if($item_po != $length){
				$lines .= "\r\n";
			}
			$item_po++;
		}

		return $lines;
	}

	public function cetak(){
		$data = [];

		$data['purchase_order'] = $this->db
		->select('po.id_purchase_order')
		->select('date_format(po.tanggal_po, "%d/%m/%Y") as tanggal_po')
		->select('po.jenis_po')
		->select('date_format(po.bulan, "%c") as bulan')
		->select('"AHM - PT. Astra Honda Motor" as supplier')
		->select('"JL. KH Agus Salim 124 Madium" as dikirim_ke')
		->select('"TOP" as jenis_pembayaran')
		->from('tr_h3_md_purchase_order as po')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pop.qty_order')
		->select('p.harga_dealer_user as harga_jual')
		->select('pop.harga')
		->select('(pop.qty_order * pop.harga) as amount')
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

        require_once APPPATH .'third_party/mpdf/mpdf.php';
        $mpdf = new Mpdf();
        $html = $this->load->view('h3/h3_md_cetak_purchase_order', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output("{$data['purchase_order']['id_purchase_order']}.pdf", "I");
	}

	public function update_qty_bo_dealer(){
		$periode_back_order = null;
        if($this->input->post('bulan') != null and $this->input->post('tahun') != null){
            $tahun = date('Y', strtotime($this->input->post('tahun')));
            $bulan = date('m', strtotime($this->input->post('bulan')));
            $date_string = "{$tahun}-{$bulan}-01";
            $unix_time_bulan_lalu = strtotime('-30 day', strtotime($date_string));

            $date = new DateTime(
                date('Y-m-01', $unix_time_bulan_lalu)
            );
            $date->modify('last day of this month');
            $periode_back_order = $date->format('Y-m-d');
		}
		
		if($periode_back_order != null and count($this->input->post('parts')) > 0){
			$data = [];
			foreach ($this->input->post('parts') as $id_part) {
				$qty_bo_dealer = $this->purchase_order_parts->qty_bo_dealer($id_part, $periode_back_order);
				$data[] = [
					'id_part' => $id_part,
					'qty_bo_dealer' => $qty_bo_dealer
				];
			}
			send_json($data);
		}else{
			send_json([]);
		}
	}

	public function update_qty_suggest(){
        $this->load->model('H3_md_niguri_header_model', 'niguri_header');

		$tanggal_order = $this->input->post('tanggal_order') == null ? Mcarbon::now() : Mcarbon::parse($this->input->post('tanggal_order'));
        $tanggal_order = $tanggal_order->startOfMonth();
        $perbedaan_bulan = 0;
        $pesan_untuk_bulan = null;
        if($this->input->post('bulan') != null and $this->input->post('tahun') != null){
            $bulan = Mcarbon::parse($this->input->post('bulan'))->format('m');
            $tahun = Mcarbon::parse($this->input->post('tahun'))->format('Y');
            $pesan_untuk_bulan = "{$tahun}-{$bulan}-01";
            $pesan_untuk_bulan = Mcarbon::parse($pesan_untuk_bulan);
            $pesan_untuk_bulan = $pesan_untuk_bulan->startOfMonth();

            $perbedaan_bulan = $tanggal_order->diffInMonths($pesan_untuk_bulan);
        }

		$parts = $this->input->post('parts');;
		if(count($parts) > 0){
			$data = [];
			foreach ($parts as $id_part) {
				$row = [];
				$row['id_part'] = $id_part;

				$key_fix_order = "fix_order_n_{$perbedaan_bulan}";
				$row['key_fix_order'] = $key_fix_order;

				$qty_suggest = $this->niguri_header->qty_suggest($id_part, $tanggal_order);
				if($qty_suggest != null){
					$row['qty_suggest'] = $qty_suggest['qty_suggest'];
					$row['qty_order'] = $qty_suggest[$key_fix_order];
				}else{
					$row['qty_suggest'] = 0;
					$row['qty_order'] = 1;
				}
				$data[] = $row;
			}
			send_json($data);
		}else{
			send_json([]);
		}
	}

	public function generate_parts(){
		$this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('H3_md_niguri_header_model', 'niguri_header');

		$tanggal_order = $this->input->get('tanggal_order') == null ? Mcarbon::now() : Mcarbon::parse($this->input->get('tanggal_order'));
        $tanggal_order = $tanggal_order->startOfMonth();
		$perbedaan_bulan = 0;
        $pesan_untuk_bulan = null;
		$bulan = $tahun = 0;
        if($this->input->get('bulan') != null and $this->input->get('tahun') != null){
            $bulan = Mcarbon::parse($this->input->get('bulan'))->format('m');
            $tahun = Mcarbon::parse($this->input->get('tahun'))->format('Y');
            $pesan_untuk_bulan = "{$tahun}-{$bulan}-01";
            $pesan_untuk_bulan = Mcarbon::parse($pesan_untuk_bulan);
            $pesan_untuk_bulan = $pesan_untuk_bulan->startOfMonth();

            $perbedaan_bulan = (int) $pesan_untuk_bulan->format('m') - (int) $tanggal_order->format('m');
        }else{
			$pesan_untuk_bulan = Mcarbon::now();
		}

		$key_amount_fix = 'fix_order_n';

		if($perbedaan_bulan >= 1 AND $perbedaan_bulan <= 5){
			$key_amount_fix .= '_' . $perbedaan_bulan;
		}

		if($this->input->post('tanggal_po') != null){
            $bulan_berjalan = Mcarbon::parse($this->input->post('tanggal_po'));
        }else{
            $bulan_berjalan = Mcarbon::now();
        }

		$this->db
		->select('n.id_part_int')
		->select('n.id_part')
		->select('p.nama_part')
		->select('p.kelompok_part')
        ->select('p.minimal_order as qty_min_order')
		->select('p.harga_md_dealer as harga')
        ->select('p.harga_dealer_user')
        ->select('1 as checked')
        ->select('sps.qty as qty_on_hand')
        ->select('sps.qty_intransit as qty_in_transit')
		->select('sps.qty_book as qty_book')
        ->select('1 as checked')
		->from('tr_h3_md_niguri_header as nh')
		->join('tr_h3_md_niguri as n', 'n.id_niguri_header = nh.id')
		->join('ms_part as p', 'p.id_part_int = n.id_part_int')
		->join('tr_stok_part_summary as sps','sps.id_part_int=p.id_part_int')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part_int = p.kelompok_part_int')
		->where('nh.type_niguri', $this->input->get('jenis_po'))
		->where('nh.bulan', $tanggal_order->format('m'))
		->where('nh.tahun', $tanggal_order->format('Y'))
		->where('skp.produk', $this->input->get('produk'))
		;

		if ($this->input->get('jenis_po') == 'REG') {
			$this->db->where('n.qty_reguler > ', 0);
			$this->db->select('ROUND(n.qty_reguler) as qty_order', false);
		}else if($this->input->get('jenis_po') == 'FIX'){
			$this->db->where("n.{$key_amount_fix} > ", 0);
			$this->db->select("ROUND(n.{$key_amount_fix}) as qty_order", false);
		}

		$parts = [];
		foreach ($this->db->get()->result_array() as $row) {
            // $row['qty_on_hand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            // $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
            $row['qty_avs'] = $row['qty_on_hand']-$row['qty_book']-$this->stock_int->qty_claim($row['id_part_int']);
            // $row['qty_in_transit'] = $this->stock_int->qty_intransit($row['id_part_int']);
            $row['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($row['id_part_int'], $pesan_untuk_bulan);
            $row['avg_sales'] = round($this->do_sales_order->qty_avg_sales($row['id_part_int'], 'id_part_int'));
            $row['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($row['id_part_int'], $this->input->get('jenis_po'), $tanggal_order, $pesan_untuk_bulan);
            $row['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($row['id_part'], $this->input->get('jenis_po'), $tanggal_order, $pesan_untuk_bulan);

            $qty_suggest = $this->niguri_header->qty_suggest($row['id_part'], $this->input->get('jenis_po'), $tanggal_order);
            if($qty_suggest != null){
                $row['qty_suggest'] = $qty_suggest['qty_suggest'];

                if($perbedaan_bulan != 0 AND in_array($perbedaan_bulan, range(1,5)) AND $this->input->get('jenis_po') == 'FIX'){
                    $row['_n_key'] = "fix_order_n_{$perbedaan_bulan}";
                }
            }else{
                $row['qty_suggest'] = 0;
            }
            $parts[] = $row;
        }

		send_json($parts);
	}

	public function ps_dummy(){
		$purchase = $this->db
		->select('po.id_purchase_order')
		->select('date_format(po.tanggal_po, "%d%m%Y") as tanggal_po')
		->from('tr_h3_md_purchase_order as po')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->row_array();
		
		$fieldLengths = [
			'kode_produk' => 1,
			'kode_md' => 5,
			'packing_sheet_date' => 8,
			'packing_sheet_number' => 15,
			'no_po' => 30,
			'jenis_po' => 3,
			'tanggal_po' => 8,
			'no_urut' => 5,
			'no_doos' => 15,
			'id_part' => 25,
			'part_deskripsi' => 37,
			'packing_sheet_quantity' => 3,
			'qty_order' => 10,
			'qty_back_order' => 10,
		];

		$this->db
		->select('"P" as kode_produk')
		->select('"E20" as kode_md')
		->select('date_format(po.tanggal_po, "%d%m%Y") as packing_sheet_date')
		->select("concat('PE', date_format(po.tanggal_po, '%d%m%Y'), LEFT(po.id_purchase_order, 5)) as packing_sheet_number", false)
		->select('po.id_purchase_order as no_po')
		->select('date_format(po.tanggal_po, "%d%m%Y") as tanggal_po')
		->select('po.jenis_po as jenis_po')
		->select('pop.id as no_urut')
		->select("concat('KE', date_format(po.tanggal_po, '%d%m%Y'), pop.id) as no_doos", false)
		->select('pop.id_part')
		->select('substring(p.nama_part, 1, 37) as part_deskripsi')
		->select('pop.qty_order as packing_sheet_quantity')
		->select('pop.qty_order')
		->select('0 as qty_back_order')
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = pop.id_purchase_order')
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'));

		$data = [];
		$lines = '';
		foreach ($this->db->get()->result_array() as $row) {
			foreach ($fieldLengths as $key => $value) {
				$lines .= str_pad($row[$key], $value, ' ');
			}
			$lines .= "\n";
		}

		$filename = substr($this->input->get('id_purchase_order'), 0, 5);
		$filename .= $purchase['tanggal_po'];
		$filename = "E20{$filename}.PS";

		$this->load->helper('download');
		force_download($filename, $lines);
	}

	public function download_excel(){
		$this->load->model('H3_md_laporan_purchase_order_md_model', 'laporan_purchase_order_md');

		$id_purchase_order = $this->input->get('id_purchase_order');

		$this->laporan_purchase_order_md->generate($id_purchase_order);
	}

	public function revisi_po(){
		$data['mode']    = 'revisi_po';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->select('po.*')
		->select('rd.status')
        ->select('rd.id_dealer')
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
		->select('date_format(po.tanggal_po, "%d-%m-%Y") as tanggal_po_format')
		->select('po.status')
		->from('tr_h3_md_purchase_order as po')
		->join('tr_h3_dealer_purchase_order as po_dealer', 'po_dealer.po_id = po.referensi_po_hotline', 'left')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po_dealer.id_booking', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
        ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->join('ms_warna as w', 'w.id_warna = c.id_warna', 'left')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('tr_h2_sa_form as sa_form', 'sa_form.id_sa_form = rd.id_sa_form', 'left')
        ->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = sa_form.id_sa_form', 'left')
		->where('po.id_purchase_order', $this->input->get('id_purchase_order'))
		->limit(1)
		->get()->row_array();

		// $data['purchase'] = $purchase;

		// $check_no_booking = $this->db->select('id_booking')
		// 							 ->from('tr_h3_dealer_purchase_order')
		// 							 ->where('po_id',$data['purchase']['referensi_po_hotline'])
		// 							 ->get()->row_array();

		$parts = $this->db
		->select('pop.*')
		// ->select('tk.tipe_ahm')
		->select('p.nama_part')
		->select('p.kelompok_part')
		->select('p.minimal_order')
		->select('p.import_lokal')
		->select('p.current')
		->select('p.hoo_flag')
		->select('p.hoo_max')
		// ->select('d.nama_dealer')
		->select('1 as checked')
		// ->select('1 as checked_revisi')
		->select('rqd.part_revisi_dari_md')
		->select('rqd.alasan_part_revisi_md')
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->join('tr_h3_md_purchase_order as po','pop.id_purchase_order_int=po.id')
		// ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		// ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = pop.id_tipe_kendaraan', 'left')
		->join('tr_h3_dealer_purchase_order as po_dealer', 'po_dealer.po_id = po.referensi_po_hotline')
		->join('tr_h3_dealer_purchase_order_parts as pop_dealer', 'po_dealer.po_id = pop_dealer.po_id and pop_dealer.id_part_int=pop.id_part_int')
		->join('tr_h3_dealer_request_document rd', 'rd.id_booking = po_dealer.id_booking')
		->join('tr_h3_dealer_request_document_parts rqd', 'rqd.id_booking = rd.id_booking and rqd.id_part_int=pop.id_part_int' )
		->where('pop.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$pesan_untuk_bulan = null;
        if($data['purchase']['bulan'] != null and $data['purchase']['tahun'] != null){
            $tahun = Mcarbon::parse($data['purchase']['tahun']);
            $bulan = Mcarbon::parse($data['purchase']['bulan']);

			$pesan_untuk_bulan = Mcarbon::parse("{$tahun->format('Y')}-{$bulan->format('m')}-01");
        }else{
			$pesan_untuk_bulan = Mcarbon::parse($data['purchase']['tanggal_po']);
		}

		$bulan_berjalan = Mcarbon::parse($data['purchase']['tanggal_po']);

		$parts = array_map(function($part) use ($pesan_untuk_bulan, $data, $bulan_berjalan) {
			$part['qty_on_hand'] = $this->stock_int->qty_on_hand($part['id_part_int']);
            $part['qty_avs'] = $this->stock_int->qty_avs($part['id_part_int']);
			$part['qty_in_transit'] = $this->stock_int->qty_intransit($part['id_part_int']);
            $part['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($part['id_part_int'], $pesan_untuk_bulan);
            $part['avg_sales'] = round($this->do_sales_order->qty_avg_sales($part['id_part_int'], 'id_part_int'));
            $part['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($part['id_part_int'], $data['purchase']['jenis_po'], $bulan_berjalan, $pesan_untuk_bulan);
            $part['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($part['id_part_int'], $data['purchase']['jenis_po'], $bulan_berjalan, $pesan_untuk_bulan);
			return $part;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function save_revisi_po(){

		$this->db->trans_start();
		//Update Status MD
		$this->db->set('status','Reject & Revisi by MD')
						 ->set('revised_at',date('Y-m-d H:i:s', time()))
						 ->set('revised_by',$this->session->userdata('id_user'))
				         ->where('id_purchase_order',$this->input->post('id_purchase_order'))
						 ->update('tr_h3_md_purchase_order');

		//Update Status PO di Dealer
		$po_id = $this->db->select('referensi_po_hotline')
						  ->from('tr_h3_md_purchase_order')
						  ->where('id_purchase_order',$this->input->post('id_purchase_order'))
						  ->get()->row_array();
						  

		$this->db->set('status_md','Reject & Revisi by MD')
						 ->set('status','Reject & Revisi by MD')
						 ->set('revised_md_at',date('Y-m-d H:i:s', time()))
						 ->set('revised_md_by',$this->session->userdata('id_user'))
						 ->set('po_revisi',1)
				         ->where('po_id',$po_id['referensi_po_hotline'])
						 ->update('tr_h3_dealer_purchase_order');

		//Check no booking PO 
		$id_booking = $this->db->select('id_booking')
						->from('tr_h3_dealer_purchase_order')
						->where('po_id',$po_id['referensi_po_hotline'])
						->get()->row_array();

		$this->load->helper('array');
		$parts = [];
		foreach($this->input->post('parts') as $row){
			$part = elements([
				'id_part','id_part_int','alasan_part_revisi_md','part_revisi_dari_md'
			], $row);

			//Check id_part int
			$id_part_int = $this->db->select('id_part_int')
									->from('ms_part')
									->where('id_part',$row['id_part'])
									->get()->row_array();

			if($row['alasan_part_revisi_md'] != ''||$row['alasan_part_revisi_md'] != NULL){
				$this->db->set('alasan_part_revisi_md',$row['alasan_part_revisi_md'])
						 ->set('part_revisi_dari_md',1)
						 ->set('part_sebelum_revisi',1)
				         ->where('id_booking',$id_booking['id_booking'])
				         ->where('id_part_int',$id_part_int['id_part_int'])
						 ->update('tr_h3_dealer_request_document_parts');
			}else{
				$this->db->set('alasan_part_revisi_md',$row['alasan_part_revisi_md'])
						 ->set('part_revisi_dari_md',0)
						 ->set('part_sebelum_revisi',1)
				         ->where('id_booking',$id_booking['id_booking'])
				         ->where('id_part_int',$id_part_int['id_part_int'])
						 ->update('tr_h3_dealer_request_document_parts');
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Purchase order berhasil di perbarui.');
			$this->session->set_flashdata('tipe', 'info');

			$purchase = (array) $this->purchase_order->get($this->input->post(['id_purchase_order']), true);
			send_json([
				'message' => 'Berhasil mereject dan merevisi PO',
				'payload' => $purchase,
				'redirect_url' => base_url('h3/h3_md_purchase_order/detail?id_purchase_order=' . $this->input->post('id_purchase_order'))
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil mereject dan merevisi PO'
			], 422);
		}
	}

	
	public function download_template_import_po_reg_fix_ahm(){
		$this->load->helper('download');
		force_download('assets/template/upload_po_reg_fix_ahm_template.xlsx', NULL);
	}

	public function upload_po_reg_fix()
	{				
		$data['isi'] = $this->page;		
		$data['title'] = $this->title;			
		$data['mode'] = 'upload_po_reg_fix';
		$data['set'] = 'upload_po_reg_fix';	
		$this->template($data);		
	}

	public function store_upload_po_reg_fix()
	{
		$config['upload_path'] = './uploads/purchase_order_md_upload/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')) {
			$errors = [
				'file' => $this->upload->display_errors('', '')
			];
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			]);
		} else {
			$this->read_excel($this->upload->data()['file_name']);
		}
	}

	public function read_excel($filename)
	{
		//  Include PHPExcel_IOFactory
		include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

		$filepath = "./uploads/purchase_order_md_upload/{$filename}";

		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($filepath);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($filepath);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($filepath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		$validate_data = [
			'jenis_po' => $sheet->getCell('B1')->getValue(),
			'produk' => $sheet->getCell('B2')->getValue(),
			'keterangan' => $sheet->getCell('B3')->getValue(),
			'bulan' => $sheet->getCell('B4')->getValue(),
			'tahun' => $sheet->getCell('B5')->getValue(),
		];

		$parts = [];
		for ($row = 8; $row <= $highestRow; $row++) {
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
			if ($rowData[0] == null || $rowData[0] == '') continue;
			$part = [];
			$part['id_part'] = $rowData[0];
			$part['qty_order'] = $rowData[1];
			$parts[] = $part;
		}
		$validate_data['parts'] = $parts;

		$this->validate_purchase_order($validate_data);

		$tanggal_po = date('Y-m-d');
		$jenis_po = $sheet->getCell('B1')->getValue();
		$produk = $sheet->getCell('B2')->getValue();
		$keterangan = $sheet->getCell('B3')->getValue();
		$bulan = $sheet->getCell('B4')->getValue();
		$tahun = $sheet->getCell('B5')->getValue();
		if($jenis_po == 'REG' || $jenis_po == 'reg'){
			$tahun = $tahun.'-01-01';
		}else{
			$bln_tgl = date('m-d');
			$tahun = $tahun."-".$bln_tgl;
		}

		

		$this->db->trans_start();

		$purchase_order = [
			'tanggal_po' => $tanggal_po,
			'jenis_po' => $jenis_po,
			'produk' => $produk,
			'id_purchase_order' => $this->purchase_order->generateID($jenis_po, $produk, $bulan),
			'keterangan' => $keterangan,
			'bulan' => $bulan .'-01',
			'tahun' => $tahun,
			'from_upload' => 1,
			'sudah_back_order' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('id_user')
		];


		$this->purchase_order->insert($purchase_order);
		// $id = $this->db->insert_id();
	

		$total_amount = 0;
		$purchase_order_parts = [];

		$id_purchase_order_int = $this->db->query("select id FROM tr_h3_md_purchase_order WHERE id_purchase_order= '" . $purchase_order['id_purchase_order']."'")->row_array();
		
		foreach ($parts as $part) {
			$id_part_int = $this->db->query("select id_part_int, harga_md_dealer FROM ms_part WHERE id_part= '" . $part['id_part'] ."'")->row_array();

			$check_stok_on_hand = $this->db->query("select qty FROM tr_stok_part_summary WHERE id_part_int= '" . $id_part_int['id_part_int'] ."'")->row_array();

			if($check_stok_on_hand['qty'] != '' or $check_stok_on_hand['qty'] != NULL){
				$check_stok_on_hand = $check_stok_on_hand['qty'];
			}else{
				$check_stok_on_hand = 0;
			}
			$set_qty = [
				'qty_min_order' => null,
				'qty_in_transit' => 0,
				'qty_bo' => null,
				'avg_sales' => null,
				'qty_on_hand' => $check_stok_on_hand,
				'eta' => null,
				'etd' => null,
				'eta_revisi' => null,
			];

			$purchase_order_part = [
				'id_purchase_order' => $purchase_order['id_purchase_order'],
				'id_purchase_order_int' => $id_purchase_order_int['id'],
				'id_part' => $part['id_part'],
				'id_part_int' => $id_part_int['id_part_int'],
				'qty_order' => $part['qty_order'],
				'harga' => $id_part_int['harga_md_dealer'],
			];

			$purchase_order_part = array_merge($purchase_order_part, $set_qty);

			$total_amount += $id_part_int['harga_md_dealer'] * $part['qty_order'];
			$purchase_order_parts[] = $purchase_order_part;
		}
		$purchase_order['total_amount'] = $total_amount;

		$this->db->set('total_amount', $purchase_order['total_amount']);
		$this->db->where('id_purchase_order', $purchase_order['id_purchase_order']);
		$this->db->update('tr_h3_md_purchase_order');
		
		$this->purchase_order_parts->insert_batch($purchase_order_parts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Import Purchase Order berhasil dilakukan.');
			$this->session->set_userdata('tipe', 'success');

			$purchase_order = $this->purchase_order->find($purchase_order['id_purchase_order'], 'id_purchase_order');
			send_json([
				'payload' => $purchase_order,
			]);
		} else {
			$this->session->set_userdata('pesan', 'Import Purchase Order tidak berhasil dilakukan.');
			$this->session->set_userdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate_purchase_order($data)
	{
		$this->form_validation->set_data($data);
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('jenis_po', 'Tipe PO', 'required|in_list[FIX,REG]');
		$this->form_validation->set_rules('produk', 'Produk', 'required|in_list[Parts,Oil,Acc,Apparel,Tools,Other]');
		$this->form_validation->set_rules('bulan', 'Bulan', 'required|callback_checkDateFormat');
		$this->form_validation->set_rules('tahun', 'Tahun', 'numeric');
	

		$part_tidak_sesuai_produk = 0;
		$part_non_fix = [];
		foreach ($data['parts'] as $part) {
			$row = $this->db
				->select('skp.produk')
				->select('p.fix')
				->from('ms_part as p')
				->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
				->where('p.id_part', $part['id_part'])
				->get()->row_array();

			if($row['produk'] != $data['produk']){
				$part_tidak_sesuai_produk += 1;
			}

			if($data['jenis_po'] == 'FIX'){
				if($row['fix'] == 0){
					$part_non_fix[] = $part['id_part'];
				}
			}
		}
		$part_non_fix = array_unique($part_non_fix);

		if($data['jenis_po'] == 'FIX'){
			if (!$this->form_validation->run() || $part_tidak_sesuai_produk > 0 || count($part_non_fix) > 0) {
				$this->output->set_status_header(500);
				$upload_errors = [];
				foreach ($this->form_validation->error_array() as $key => $value) {
					$upload_errors[] = $value;
				}

				if($part_tidak_sesuai_produk > 0){
					$upload_errors[] = 'Terdapat Kode Part yang tidak sesuai dengan Tipe Produk';
				}

				if(count($part_non_fix) > 0){
					$upload_errors[] = 'Silahkan setting kelompok produk menjadi FIX pada part : '. implode(',' , $part_non_fix) . ' di Master Part.';
				}
				
				send_json([
					'error_type' => 'upload_error',
					'message' => 'Data tidak valid',
					'errors' => $upload_errors
				],422);
			}
		}else{
			if (!$this->form_validation->run() || $part_tidak_sesuai_produk > 0) {
				$this->output->set_status_header(500);
				$upload_errors = [];
				foreach ($this->form_validation->error_array() as $key => $value) {
					$upload_errors[] = $value;
				}

				if($part_tidak_sesuai_produk > 0){
					$upload_errors[] = 'Terdapat Kode Part yang tidak sesuai dengan Tipe Produk';
				}
				
				send_json([
					'error_type' => 'upload_error',
					'message' => 'Data tidak valid',
					'errors' => $upload_errors
				],422);
			}
		}
	}

	function checkDateFormat($bulan) {
        $d = DateTime::createFromFormat('Y-m', $bulan);
        if(($d && $d->format('Y-m') === $bulan) === FALSE){
            $this->form_validation->set_message('checkDateFormat', ''.$bulan.' tidak valid, format harus tahun-bulan.');
            return FALSE;
        }else{
            return TRUE;
        }
	}
}