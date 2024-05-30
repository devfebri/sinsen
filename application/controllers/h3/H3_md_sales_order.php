<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_sales_order extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_sales_order";
	protected $title  = "Sales Order";

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
		// if($name=="" OR $auth=='false')
		// {
		// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		// }elseif($sess=='false'){
		// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		// }

		$this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
		$this->load->model('H3_md_ms_sim_part_model', 'sim_part');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add()
	{

		$data['mode']    = 'insert';
		$data['set']     = "form";

		if ($this->input->get('generateByPO') != null) {
			$data['sales_order'] = $sales_order = $this->db
				->select('po.po_id as id_ref')
				->select('po.id_dealer')
				->select('d.nama_dealer')
				->select('d.kode_dealer_md')
				->select('d.alamat')
				->select('
				case
					when tpi.id is not null then 
						case
							when po.po_type = "FIX" then DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tpi.fix DAY), "%Y-%m-%d")
							when po.po_type = "REG" then DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tpi.reg DAY), "%Y-%m-%d")
						end
					else 
						case
							when po.po_type = "FIX" then DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tp.fix DAY), "%Y-%m-%d")
							when po.po_type = "REG" then DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tp.reg DAY), "%Y-%m-%d")
						end
				end as batas_waktu
			', false)
				->select('po.po_type')
				->select('"Credit" as jenis_pembayaran')
				->select('po.kategori_po')
				->select('po.produk')
				->select('"Dealer" as tipe_source')
				->select('"" as id_salesman')
				->select('"" as nama_salesman')
				->select('0 as is_ev')
				->select('po.created_by_md')
				->select('po.autofulfillment_md as autofulfillment_md')
				->select('0 as gimmick')
				->from('tr_h3_dealer_purchase_order po')
				->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
				->join('master_tipe_po_item as tpi', 'tpi.id_dealer = po.id_dealer', 'left')
				->join('master_tipe_po as tp', '1 = 1')
				->limit(1)
				->where('po.po_id', $this->input->get('po_id'))
				->get()->row_array();

			$data['sales_order_parts'] = $this->db
				->select('pop.id_part')
				->select('p.nama_part')
				->select('pop.tipe_diskon')
				->select('pop.diskon_value')
				->select('pop.tipe_diskon_campaign')
				->select('pop.diskon_value_campaign')
				->select('pop.harga_saat_dibeli as harga')
				->select('ppdd.qty_pemenuhan as qty_order')
				->select('ppdd.qty_pemenuhan')
				->from('tr_h3_dealer_purchase_order_parts as pop')
				->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
				->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.po_id = pop.po_id and ppdd.id_part_int = pop.id_part_int)')
				->where('pop.po_id', $this->input->get('po_id'))
				->where('ppdd.qty_pemenuhan > 0')
				->get()->result_array();

			$data['sales_order_parts'] = array_map(function ($part) use ($sales_order) {
				$part['qty_avs'] = $this->stock->qty_avs($part['id_part'], [], false, $sales_order['po_type'] == 'HLO');
				$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $sales_order['id_dealer']);
				return $part;
			}, $data['sales_order_parts']);
		} else if ($this->input->get('generateByRekapPurchaseOrder') != null) {
			$data['sales_order'] = $sales_order = $this->db
				->select('r.id_dealer')
				->select('d.nama_dealer')
				->select('d.kode_dealer_md')
				->select('d.alamat')
				->select('r.tipe_po as po_type')
				->select('"Credit" as jenis_pembayaran')
				->select('"Non SIM Part" as kategori_po')
				->select('"Parts" as produk')
				->select('"Dealer" as tipe_source')
				->select('"" as id_salesman')
				->select('"" as nama_salesman')
				->select('1 as created_by_md')
				->select('0 as gimmick')
				->select('r.id as id_rekap_purchase_order_dealer')
				->from('tr_h3_md_rekap_purchase_order_dealer as r')
				->join('ms_dealer as d', 'd.id_dealer = r.id_dealer')
				->where('r.id', $this->input->get('id_rekap_purchase_order_dealer'))
				->limit(1)
				->get()->row_array();
			$data['sales_order']['batas_waktu'] = null;

			$parts = $this->db
				->select('rpp.id_part')
				->select('p.nama_part')
				// ->select('pop.tipe_diskon')
				// ->select('pop.diskon_value')
				// ->select('pop.tipe_diskon_campaign')
				// ->select('pop.diskon_value_campaign')
				->select('p.harga_dealer_user as harga')
				->select('SUM(rpp.kuantitas) as qty_order')
				->select('SUM(rpp.kuantitas) as qty_pemenuhan')
				->select('IFNULL(p.qty_dus, 1) as qty_dus')
				->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpp')
				->join('ms_part as p', 'p.id_part = rpp.id_part')
				->where('rpp.id_rekap', $this->input->get('id_rekap_purchase_order_dealer'))
				->group_by('rpp.id_part')
				->get()->result_array();


			$jumlah_dus = $this->get_jumlah_dus($parts);
			$data['sales_order_parts'] = array_map(function ($part) use ($sales_order, $jumlah_dus) {
				$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);
				$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $sales_order['id_dealer']);

				$diskon_part_tertentu = $this->diskon_part_tertentu->get_diskon($part['id_part'], $sales_order['id_dealer'], $sales_order['po_type']);
				if ($diskon_part_tertentu != null) {
					$part['tipe_diskon'] = $diskon_part_tertentu['tipe_diskon'];
					$part['diskon_value'] = $diskon_part_tertentu['diskon_value'];
				}

				$sales_campaign = $this->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['qty_order']);
				if ($sales_campaign != null) {
					$part['tipe_diskon_campaign'] = $sales_campaign['tipe_diskon'];
					$part['diskon_value_campaign'] = $sales_campaign['diskon_value'];
				}

				return $part;
			}, $parts);
		} else if ($this->input->get('generateGimmick') != null) {
			$data['sales_order'] = $sales_order = $this->db
				->select('so.id_dealer')
				->select('d.nama_dealer')
				->select('d.kode_dealer_md')
				->select('d.alamat')
				->select('
				CASE
					WHEN tpi.id IS NOT NULL THEN DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tpi.reg DAY), "%Y-%m-%d" )
					ELSE DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tp.reg DAY), "%Y-%m-%d" )
				END as batas_waktu
			', false)
				->select('"REG" as po_type')
				->select('"Credit" as jenis_pembayaran')
				->select('"Non SIM Part" as kategori_po')
				->select('sc.kategori as produk')
				->select('"Dealer" as tipe_source')
				->select('"" as id_salesman')
				->select('"" as nama_salesman')
				->select('1 as created_by_md')
				->select('1 as gimmick')
				->select('0 as gimmick_tidak_langsung')
				->from('tr_h3_md_do_sales_order as do')
				->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
				->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
				->join('master_tipe_po_item as tpi', 'tpi.id_dealer = so.id_dealer', 'left')
				->join('master_tipe_po as tp', '1 = 1')
				->join('ms_h3_md_sales_campaign as sc', "sc.id = {$this->input->get('id_campaign')}")
				->where('do.id_do_sales_order', $this->input->get('id_do_sales_order'))
				->limit(1)
				->get()->row_array();
			$data['sales_order']['id_campaign'] = $this->input->get('id_campaign');
			$data['sales_order']['id_item'] = $this->input->get('id_item');
			$data['sales_order']['no_do_sumber_gimmick'] = $this->input->get('id_do_sales_order');

			$parts = $this->db
				->select('dog.id as id_do_gimmick')
				->select('dog.id_part')
				->select('p.nama_part')
				->select('"" as tipe_diskon')
				->select('0 as diskon_value')
				->select('"" as tipe_diskon_campaign')
				->select('0 as diskon_value_campaign')
				->select('p.harga_dealer_user as harga')
				->select('SUM(dog.qty_hadiah) as qty_order')
				->select('SUM(dog.qty_hadiah) as qty_pemenuhan')
				->from('tr_h3_md_do_sales_order_gimmick as dog')
				->join('ms_part as p', 'p.id_part = dog.id_part')
				->join('ms_h3_md_sales_campaign as sc', 'sc.id = dog.id_campaign')
				->where('dog.id_do_sales_order', $this->input->get('id_do_sales_order'))
				->where('dog.id_campaign', $this->input->get('id_campaign'))
				->where('dog.id_item', $this->input->get('id_item'))
				->where('sc.reward_gimmick', 'Langsung')
				->group_by('dog.id_part')
				->get()->result_array();

			// $jumlah_dus = $this->get_jumlah_dus($parts);
			$data['sales_order_parts'] = array_map(function ($part) use ($sales_order) {
				$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);
				$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $sales_order['id_dealer']);

				// $diskon_part_tertentu = $this->diskon_part_tertentu->get_diskon($part['id_part'], $sales_order['id_dealer'], $sales_order['po_type']);
				// if($diskon_part_tertentu != null){
				// 	$part['tipe_diskon'] = $diskon_part_tertentu['tipe_diskon'];
				// 	$part['diskon_value'] = $diskon_part_tertentu['diskon_value'];
				// }

				// $sales_campaign = $this->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['qty_order']);
				// if($sales_campaign != null){
				// 	$part['tipe_diskon_campaign'] = $sales_campaign['tipe_diskon'];
				// 	$part['diskon_value_campaign'] = $sales_campaign['diskon_value'];
				// }

				return $part;
			}, $parts);
		} else if ($this->input->get('generateSalesOrderEkspedisi') != null) {
			$data['sales_order'] = $sales_order = $this->db
				->select('d.id_dealer')
				->select('d.nama_dealer')
				->select('d.kode_dealer_md')
				->select('d.alamat')
				->select('
				CASE
					WHEN tpi.id IS NOT NULL THEN DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tpi.reg DAY), "%Y-%m-%d" )
					ELSE DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tp.reg DAY), "%Y-%m-%d" )
				END as batas_waktu
			', false)
				->select('"REG" as po_type')
				->select('"Tunai" as jenis_pembayaran')
				->select('"Non SIM Part" as kategori_po')
				->select('"Parts" as produk')
				->select('"Dealer" as tipe_source')
				->select('"" as id_salesman')
				->select('"" as nama_salesman')
				->select('1 as created_by_md')
				->select('0 as gimmick')
				->select('bapb.no_bapb')
				->from('tr_h3_md_berita_acara_penerimaan_barang as bapb')
				->join('ms_h3_md_ekspedisi as e', 'e.id = bapb.id_vendor')
				->join('ms_dealer as d', 'd.id_dealer = e.id_dealer')
				->join('master_tipe_po_item as tpi', 'tpi.id_dealer = d.id_dealer', 'left')
				->join('master_tipe_po as tp', '1 = 1')
				->where('bapb.no_bapb', $this->input->get('no_bapb'))
				->limit(1)
				->get()->row_array();

			$parts = $this->db
				->select('pbapbi.id_part')
				->select('p.nama_part')
				->select('"" as tipe_diskon')
				->select('0 as diskon_value')
				->select('"" as tipe_diskon_campaign')
				->select('0 as diskon_value_campaign')
				->select('p.harga_dealer_user as harga')
				->select('SUM(pbapbi.qty_rusak) as qty_order')
				->select('SUM(pbapbi.qty_rusak) as qty_pemenuhan')
				->select('skp.produk')
				->from('tr_h3_md_pelunasan_bapb as pbapb')
				->join('tr_h3_md_pelunasan_bapb_items as pbapbi', 'pbapbi.no_pelunasan = pbapb.no_pelunasan')
				->join('ms_part as p', 'p.id_part = pbapbi.id_part')
				->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part_int = p.kelompok_part_int')
				->where('pbapb.no_bapb', $this->input->get('no_bapb'))
				->where('pbapbi.tipe_ganti', 'Uang')
				->group_by('pbapbi.id_part')
				->get()->result_array();

			$data['sales_order_parts'] = array_map(function ($part) use ($sales_order) {
				$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);
				$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $sales_order['id_dealer']);
				return $part;
			}, $parts);

			$data['sales_order']['produk'] = $data['sales_order_parts'][0]['produk'];
		} else if ($this->input->get('generatePOLogistik') != null) {
			$data['sales_order'] = $sales_order = $this->db
				->select('d.id_dealer')
				->select('d.nama_dealer')
				->select('d.kode_dealer_md')
				->select('d.alamat')
				->select('
				CASE
					WHEN tpi.id IS NOT NULL THEN DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tpi.reg DAY), "%Y-%m-%d" )
					ELSE DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tp.reg DAY), "%Y-%m-%d" )
				END as batas_waktu
			', false)
				->select('"REG" as po_type')
				->select('"Credit" as jenis_pembayaran')
				->select('"Non SIM Part" as kategori_po')
				->select('"Parts" as produk')
				->select('"Dealer" as tipe_source')
				->select('"" as id_salesman')
				->select('"" as nama_salesman')
				->select('1 as created_by_md')
				->select('0 as gimmick')
				->select('pol.id_po_logistik')
				->from('tr_h3_md_po_logistik as pol')
				->join('ms_dealer as d', 'pol.id_dealer = d.id_dealer')
				->join('master_tipe_po_item as tpi', 'tpi.id_dealer = d.id_dealer', 'left')
				->join('master_tipe_po as tp', '1 = 1')
				->where('pol.id_po_logistik', $this->input->get('id_po_logistik'))
				->limit(1)
				->get()->row_array();

			$parts = $this->db
				->select('pbapbi.id_part')
				->select('p.nama_part')
				->select('"" as tipe_diskon')
				->select('0 as diskon_value')
				->select('"" as tipe_diskon_campaign')
				->select('0 as diskon_value_campaign')
				->select('p.harga_dealer_user as harga')
				->select('SUM(pbapbi.qty_rusak) as qty_order')
				->select('SUM(pbapbi.qty_rusak) as qty_pemenuhan')
				->from('tr_h3_md_pelunasan_bapb as pbapb')
				->join('tr_h3_md_pelunasan_bapb_items as pbapbi', 'pbapbi.no_pelunasan = pbapb.no_pelunasan')
				->join('ms_part as p', 'p.id_part = pbapbi.id_part')
				->where('pbapb.no_bapb', $this->input->get('no_bapb'))
				->where('pbapbi.tipe_ganti', 'Barang')
				->group_by('pbapbi.id_part')
				->get()->result_array();

			$parts = $this->db
				->select('polp.id_part')
				->select('p.nama_part')
				->select('"" as tipe_diskon')
				->select('0 as diskon_value')
				->select('"" as tipe_diskon_campaign')
				->select('0 as diskon_value_campaign')
				->select('p.harga_dealer_user as harga')
				->select('SUM(polp.qty_supply) as qty_order')
				->select('SUM(polp.qty_supply) as qty_pemenuhan')
				->from('tr_h3_md_po_logistik_parts as polp')
				->join('ms_part as p', 'p.id_part = polp.id_part')
				->where('polp.id_po_logistik', $this->input->get('id_po_logistik'))
				->where('polp.qty_supply >', 0)
				->group_by('polp.id_part')
				->get()->result_array();

			// $jumlah_dus = $this->get_jumlah_dus($parts);
			$data['sales_order_parts'] = array_map(function ($part) use ($sales_order) {
				$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);
				$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $sales_order['id_dealer']);

				// $diskon_part_tertentu = $this->diskon_part_tertentu->get_diskon($part['id_part'], $sales_order['id_dealer'], $sales_order['po_type']);
				// if($diskon_part_tertentu != null){
				// 	$part['tipe_diskon'] = $diskon_part_tertentu['tipe_diskon'];
				// 	$part['diskon_value'] = $diskon_part_tertentu['diskon_value'];
				// }

				// $sales_campaign = $this->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['qty_order']);
				// if($sales_campaign != null){
				// 	$part['tipe_diskon_campaign'] = $sales_campaign['tipe_diskon'];
				// 	$part['diskon_value_campaign'] = $sales_campaign['diskon_value'];
				// }

				return $part;
			}, $parts);
		} else if ($this->input->get('generateGimmickTidakLangsung') != null) {
			$data['sales_order'] = $this->db
				->select('d.id_dealer')
				->select('d.nama_dealer')
				->select('d.kode_dealer_md')
				->select('d.alamat')
				->select('
				CASE
					WHEN tpi.id IS NOT NULL THEN DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tpi.reg DAY), "%Y-%m-%d" )
					ELSE DATE_FORMAT( DATE_ADD(NOW(), INTERVAL tp.reg DAY), "%Y-%m-%d" )
				END as batas_waktu
			', false)
				->select('"REG" as po_type')
				->select('"Credit" as jenis_pembayaran')
				->select('"Non SIM Part" as kategori_po')
				->select('sc.kategori as produk')
				->select('"Dealer" as tipe_source')
				->select('"" as id_salesman')
				->select('"" as nama_salesman')
				->select('1 as created_by_md')
				->select('1 as gimmick')
				->select('1 as gimmick_tidak_langsung')
				->select('perolehan.id as id_perolehan')
				->select('perolehan.id_campaign')
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
				->join('ms_dealer as d', 'd.id_dealer = perolehan.id_dealer')
				->join('master_tipe_po_item as tpi', 'tpi.id_dealer = d.id_dealer', 'left')
				->join('master_tipe_po as tp', '1 = 1')
				->join('ms_h3_md_sales_campaign as sc', 'sc.id = perolehan.id_campaign')
				->where('perolehan.id', $this->input->get('id_perolehan'))
				->get()->row_array();

			$sales_campaign = $this->db
				->select('sc.id')
				->select('sc.produk_program_gimmick')
				->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
				->join('ms_h3_md_sales_campaign as sc', 'sc.id = perolehan.id_campaign')
				->where('perolehan.id', $this->input->get('id_perolehan'))
				->get()->row_array();

			$parts = [];
			if ($sales_campaign['produk_program_gimmick'] == 'Global') :
				$parts = $this->db
					->select('sc_global.id_part')
					->select('p.nama_part')
					->select('"" as tipe_diskon')
					->select('0 as diskon_value')
					->select('"" as tipe_diskon_campaign')
					->select('0 as diskon_value_campaign')
					->select('p.harga_dealer_user as harga')
					->select('
					SUM(
						case
							when sc_global.satuan_hadiah = "Dus" then ( p_global.count_gimmick * (sc_global.qty_hadiah * p.qty_dus) )
							else p_global.count_gimmick * sc_global.qty_hadiah
						end
					) as qty_order
				', false)
					->select('skp.produk')
					->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
					->join('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_global as p_global', 'p_global.id_perolehan = perolehan.id')
					->join('ms_h3_md_sales_campaign_detail_gimmick_global as sc_global', '(sc_global.id = p_global.id_gimmick_global AND sc_global.hadiah_part = 1)')
					->join('ms_part as p', 'p.id_part = sc_global.id_part')
					->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
					->where('perolehan.id', $this->input->get('id_perolehan'))
					->where('p_global.count_gimmick > ', 0)
					->group_by('sc_global.id_part')
					->get()->result_array();
			elseif ($sales_campaign['produk_program_gimmick'] == 'Per Item') :
				$parts = $this->db
					->select('sc_gimmick.id_part')
					->select('p.nama_part')
					->select('"" as tipe_diskon')
					->select('0 as diskon_value')
					->select('"" as tipe_diskon_campaign')
					->select('0 as diskon_value_campaign')
					->select('p.harga_dealer_user as harga')
					->select('
					case
						when sc_gimmick.satuan_hadiah = "Dus" then (p_item.count_gimmick * (sc_gimmick.qty_hadiah * p.qty_dus))
						else (p_item.count_gimmick * sc_gimmick.qty_hadiah)
					end as qty_order
				', false)
					->select('skp.produk')
					->from('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan')
					->join('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_item as p_item', 'p_item.id_perolehan = perolehan.id')
					->join('ms_h3_md_sales_campaign_detail_gimmick_item as sc_gimmick', '(sc_gimmick.id = p_item.id_gimmick_item  AND sc_gimmick.hadiah_part = 1)')
					->join('ms_part as p', 'p.id_part = sc_gimmick.id_part')
					->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
					->where('perolehan.id', $this->input->get('id_perolehan'))
					->where('p_item.count_gimmick > ', 0)
					->get()->result_array();
			endif;

			if (count($parts) > 0) {
				$data['sales_order']['produk'] = $parts[0]['produk'];
			}

			$data['sales_order_parts'] = array_map(function ($part) use ($data) {
				$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);
				$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $data['sales_order']['id_dealer']);
				$part['qty_pemenuhan'] = $part['qty_order'];
				return $part;
			}, $parts);
		}

		$this->template($data);
	}

	public function get_batas_waktu()
	{
		$tipe_po_item = $this->db
			->select("
			case
				when '{$this->db->escape_str($this->input->get('po_type'))}' = 'FIX' then date_format( date_add(now(), interval tpi.fix day), '%Y-%m-%d' )
				when '{$this->db->escape_str($this->input->get('po_type'))}' = 'REG' then date_format( date_add(now(), interval tpi.reg day), '%Y-%m-%d' )
			end as batas_waktu
		")
			->from('master_tipe_po_item as tpi')
			->where('tpi.id_dealer', $this->db->escape_str($this->input->get('id_dealer')))
			->get()->row_array();

		if ($tipe_po_item != null) {
			send_json($tipe_po_item);
		}

		$tipe_po = $this->db
			->select("
			case
				when '{$this->db->escape_str($this->input->get('po_type'))}' = 'FIX' then date_format( date_add(now(), interval tp.fix day), '%Y-%m-%d' )
				when '{$this->db->escape_str($this->input->get('po_type'))}' = 'REG' then date_format( date_add(now(), interval tp.reg day), '%Y-%m-%d' )
			end as batas_waktu
		")
			->from('master_tipe_po as tp')
			->get()->row_array();

		send_json($tipe_po);
	}

	public function get_qty_actual_dan_simpart_dealer()
	{
		$data = [];
		foreach ($this->input->post('parts') as $part) {
			$detail_part = $this->db
				->from('ms_part')
				->where('id_part', $part['id_part'])
				->limit(1)
				->get()->row_array();

			$part['qty_actual_dealer'] = $this->stock->qty_actual_dealer($part['id_part'], $this->input->post('id_dealer'));
			$part['qty_sim_part'] = $this->sim_part->qty_sim_part($this->input->post('id_dealer'), $detail_part['id_part_int']);

			$data[] = $part;
		}

		send_json($data);
	}

	public function get_target_customer_query($tanggal_order = null)
	{
		if ($tanggal_order == null) {
			$tanggal_order = date('Y-m-d', time());
		}

		$this->load->model('H3_md_target_salesman_model', 'target_salesman');

		$this->target_salesman->get_target_sales_query($tanggal_order, $this->input->get('id_dealer'));
	}

	public function get_target_customer()
	{
		$this->get_target_customer_query($this->db->escape_str($this->input->get('tanggal_order')));
		$this->db->select('ts.id_salesman');
		$this->db->select('k.nama_lengkap as nama_salesman');
		$this->db->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman');
		$data = $this->db->get()->row_array();

		if ($data == null) {
			$data = [];
		}
		send_json($data);
	}

	public function get_statistik_penjualan_customer()
	{
		$this->get_target_customer_query($this->db->escape_str($this->input->get('tanggal_order')));
		$target_salesman = $this->db->get_compiled_select();

		$tahun_dan_bulan = date('Y-m', time());

		$this->db
			->select('sum(so.total_amount)')
			->from('tr_h3_md_sales_order as so')
			->where('so.id_dealer', $this->input->get('id_dealer'))
			->where("date_format(so.tanggal_order, '%Y-%m') = '{$tahun_dan_bulan}'", null, false)
			->where('so.status !=', 'Canceled')
			->where('so.gimmick', 0);

		if ($this->input->get('produk') != 'Other') {
			$this->db->where('so.produk', $this->db->escape_str($this->input->get('produk')));
		} else {
			$this->db->where('true = false', null, false);
		}

		$sales_order_target = $this->db->get_compiled_select();

		$this->db
			->select('sum(dso.total)')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
			->where("date_format(dso.tanggal, '%Y-%m') = '{$tahun_dan_bulan}'", null, false)
			->where('so.id_dealer', $this->db->escape_str($this->input->get('id_dealer')))
			->where('dso.sudah_create_faktur', 1);

		if ($this->input->get('produk') != 'Other') {
			$this->db->where('so.produk', $this->db->escape_str($this->input->get('produk')));
		} else {
			$this->db->where('true = false', null, false);
		}

		$sales_order_out_target = $this->db->get_compiled_select();

		$data = $this->db
			->select("ifnull( ({$target_salesman}), 0) as target_customer")
			->select("ifnull( ({$sales_order_target}), 0) as sales_order_target")
			->select("ifnull( ({$sales_order_out_target}), 0) as sales_order_out_target")
			->get()->row_array();

		if ($data['target_customer'] != 0) {
			$data['persentase_sales_order_target'] = ($data['sales_order_target'] / $data['target_customer']) * 100;
			$data['persentase_sales_order_out_target'] = ($data['sales_order_out_target'] / $data['target_customer']) * 100;

			$data['persentase_sales_order_target'] = $data['persentase_sales_order_target'];
			$data['persentase_sales_order_out_target'] = $data['persentase_sales_order_out_target'];
		} else {
			$data['persentase_sales_order_target'] = 0;
			$data['persentase_sales_order_out_target'] = 0;
		}
		send_json($data);
	}

	public function get_plafon()
	{
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		
		$id_dealer = $this->input->get('id_dealer');
		$gimmick = $this->input->get('gimmick');
		$kategori_po = $this->input->get('kategori_po');
		$id_sales_order = $this->input->get('id_sales_order');

        $this->benchmark->mark('data_start');
		$data = [
			'plafon' => floatval($this->plafon->get_plafon($id_dealer, $gimmick, $kategori_po)) + $this->plafon->get_plafon_sementara($id_sales_order),
			'plafon_booking' => floatval($this->plafon->get_plafon_booking($id_dealer, $gimmick, $kategori_po)),
			'plafon_yang_dipakai' => $this->plafon->get_plafon_terpakai($id_dealer, $gimmick, $kategori_po),
			'time' => floatval($this->benchmark->elapsed_time('data_start', 'data_end')),
		];
        $this->benchmark->mark('data_end');

		send_json($data);
	}

	public function get_parts_sales_campaign()
	{
		$result = [];
		foreach ($this->input->post('order') as $part) {
			$diskon = $this->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['qty_order']);
			if ($diskon != null) {
				$result[] = $diskon;
			}
		}
		send_json($result);
	}

	public function save()
	{
		$this->validate();

		$this->db->trans_start();
		if ($this->input->post('created_by_md') == 1) {
			$purchase_order = array_merge($this->input->post([
				'id_dealer', 'kategori_po', 'po_type', 'created_by_md', 'batas_waktu', 'total_amount', 'produk', 'id_salesman', 'gimmick', 'gimmick_tidak_langsung', 'id_perolehan', 'autofulfillment_md','is_ev'
			]), [
				'po_id' => $this->purchase_order->generatePONumber($this->input->post('po_type'), $this->input->post('id_dealer')),
				'tanggal_order' => date('Y-m-d'),
				'status' => 'Processed by MD',
				'created_by_md' => 1,
			]);

			if ($this->input->post('id_rekap_purchase_order_dealer') != null and $this->input->post('id_rekap_purchase_order_dealer') != '') {
				$purchase_order['po_rekap'] = 1;
			}

			$purchase_order_parts = $this->getOnly([
				'id_part', 'harga', 'qty_order', 'qty_on_hand',
				'qty_pemenuhan', 'tipe_diskon', 'diskon_value',
				'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon', 'jenis_diskon_campaign'
			], $this->input->post('parts'), [
				'po_id' => $purchase_order['po_id'],
			]);

			$purchase_order_parts = array_map(function ($part) {
				return [
					'po_id' => $part['po_id'],
					'id_part' => $part['id_part'],
					'harga_saat_dibeli' => $part['harga'],
					'kuantitas' => $part['qty_order'],
					'tipe_diskon' => $part['tipe_diskon'],
					'diskon_value' => $part['diskon_value'],
					'tipe_diskon_campaign' => $part['tipe_diskon_campaign'],
					'diskon_value_campaign' => $part['diskon_value_campaign'],
				];
			}, $purchase_order_parts);

			$purchase_order = $this->clean_data($purchase_order);
			$this->purchase_order->insert($purchase_order);
			
			// Create Order Parts Tracking
			foreach ($purchase_order_parts as $data) {
				// $this->order_parts_tracking->insert(
				// 	$this->get_in_array(['po_id', 'id_part'], $data)
				// );

				$po_id_int = $this->db->select('id')
									  ->from('tr_h3_dealer_purchase_order')
									  ->where('po_id',$data['po_id'])
									  ->get()->row_array();
									  
				$id_part_int = $this->db->select('id_part_int')
				->from('ms_part')
				->where('id_part',$data['id_part'])
				->get()->row_array();

				$data2 = array(
					'po_id' => $data['po_id'],
					'po_id_int' => $po_id_int['id'],
					'id_part_int' => $id_part_int['id_part_int'],
					'id_part' => $data['id_part'],
					'created_at' => date('Y-m-d H:i:s', time()),
        			'created_by' => $this->session->userdata('id_user')
				);
				
				$this->db->insert('tr_h3_dealer_order_parts_tracking', $data2);
			}

			$this->purchase_order_parts->insert_batch($purchase_order_parts);
		}

		$sales_order = array_merge($this->input->post([
			'id_dealer', 'kategori_po', 'jenis_pembayaran', 'id_ref',
			'bulan_kpb', 'tipe_source', 'po_type', 'created_by_md',
			'batas_waktu', 'id_salesman', 'total_amount', 'produk',
			'target_customer', 'sales_order_target', 'persentase_sales_order_target', 'sales_order_out_target',
			'persentase_sales_order_out_target', 'id_rekap_purchase_order_dealer', 'gimmick', 'id_campaign',
			'gimmick_tidak_langsung', 'id_perolehan',
			'id_item', 'no_do_sumber_gimmick', 'no_bapb', 'id_po_logistik', 'referensi_po_bundling','autofulfillment_md','is_hadiah','is_ev'
		]), [
			'id_sales_order' => $this->sales_order->generateID($this->input->post('po_type'), $this->input->post('id_dealer'), null, $this->input->post('gimmick')),
			'tanggal_order' => date('Y-m-d', time()),
			'type_ref' => 'purchase_order_dealer'
		]);

		if ($this->input->post('created_by_md') == 1) {
			$sales_order['id_ref'] = $purchase_order['po_id'];
		}

		if($this->input->post('is_hadiah') == 1){
			$sales_order['gimmick'] = 1;
		}

		$sales_order_parts = $this->getOnly([
			'id_part', 'harga', 'qty_order', 'qty_on_hand',
			'qty_pemenuhan', 'tipe_diskon', 'diskon_value',
			'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon',
			'id_do_gimmick'
		], $this->input->post('parts'), [
			'id_sales_order' => $sales_order['id_sales_order'],
			'qty_on_hand' => 0
		]);

		$sales_order_parts = array_map(function ($part) {
			if ($this->input->post('created_by_md') == 1) {
				$part['qty_pemenuhan'] = $part['qty_order'];
			}
			return $part;
		}, $sales_order_parts);

		if ($this->input->post('id_rekap_purchase_order_dealer') != null && $this->input->post('id_rekap_purchase_order_dealer') != '') {
			$rekap_parts = $this->db
				->select('rpodp.po_id')
				->select('rpodp.id_part')
				->select('rpodp.kuantitas')
				->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
				->where('rpodp.id_rekap', $this->input->post('id_rekap_purchase_order_dealer'))
				->get()->result_array();

			if (count($rekap_parts) > 0) {
				foreach ($rekap_parts as $row) {
					$this->db
						->set('ppdd.qty_so', "ppdd.qty_so + {$row['kuantitas']}", false)
						->set('ppdd.qty_pemenuhan', "ppdd.qty_pemenuhan - {$row['kuantitas']}", false)
						->where('ppdd.id_part', $row['id_part'])
						->where('ppdd.po_id', $row['po_id'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppdd');

					$this->purchase_order->set_processed_by_md($row['po_id']);

					log_message('info', "[Sales Order] Mengurangi {$row['kuantitas']} qty Pemenuhan untuk kode part {$row['id_part']} pada pemenuhan PO dealer {$row['po_id']}");
					log_message('info', "[Sales Order] Menambah {$row['kuantitas']} qty SO untuk kode part {$row['id_part']} pada pemenuhan PO dealer {$row['po_id']}");
				}
			}
		} elseif (($this->input->post('po_type') == 'HLO' || $this->input->post('po_type') == 'URG') and $this->input->post('id_ref') != null and $this->input->post('id_ref') != '') {
			// Update Qty SO di pemenuhan PO dari dealer.
			if (count($sales_order_parts) > 0) {
				foreach ($sales_order_parts as $part) {
					$this->db
						->set('ppd.qty_pemenuhan', "ppd.qty_pemenuhan - {$part['qty_order']}", false)
						->set('ppd.qty_so', "ppd.qty_so + {$part['qty_order']}", false)
						->where('ppd.id_part', $part['id_part'])
						->where('ppd.po_id', $this->input->post('id_ref'))
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');

					log_message('info', "[Sales Order] Mengurangi {$part['qty_order']} qty Pemenuhan untuk kode part {$part['id_part']} pada pemenuhan PO dealer {$this->input->post('id_ref')}");
					log_message('info', "[Sales Order] Menambah {$part['qty_order']} qty SO untuk kode part {$part['id_part']} pada pemenuhan PO dealer {$this->input->post('id_ref')}");
				}
			}
		}

		// Ubah status claim gimmick menjadi sudah claim.
		if ($this->input->post('gimmick') == 1 and $this->input->post('gimmick_tidak_langsung') == 0) {
			$this->db
				->set('dog.sudah_claim', 1)
				->where('dog.id_do_sales_order', $this->input->post('no_do_sumber_gimmick'))
				->where('dog.id_campaign', $this->input->post('id_campaign'))
				->where('dog.id_item', $this->input->post('id_item'))
				->update('tr_h3_md_do_sales_order_gimmick as dog');
		} elseif ($this->input->post('gimmick') == 1 and $this->input->post('gimmick_tidak_langsung') == 1) {
			$this->db
				->set('perolehan.sudah_create_so', 1)
				->where('perolehan.id', $this->input->post('id_perolehan'))
				->update('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan');
		}

		$sales_order = $this->clean_data($sales_order);
		$this->purchase_order->set_processed_by_md($sales_order['id_ref']);
		$this->sales_order->insert($sales_order);
		$this->sales_order_parts->insert_batch($sales_order_parts);

		if (($this->input->post('id_rekap_purchase_order_dealer') != null && $this->input->post('id_rekap_purchase_order_dealer') != '') ||(($this->input->post('po_type') == 'HLO' || $this->input->post('po_type') == 'URG') and $this->input->post('id_ref') != null and $this->input->post('id_ref') != '')) {
			foreach($sales_order_parts as $part){
				$qty_booking = $part['qty_pemenuhan'];
					$this->db
							->set('sop.qty_booking', $qty_booking)
							->where('sop.id_sales_order', $sales_order['id_sales_order'])
							->where('sop.id_part', $part['id_part'])
							->update('tr_h3_md_sales_order_parts as sop');
			}
				
		}

		if ($this->input->post('id_po_logistik') != null && $this->input->post('id_po_logistik') != '') {
			$this->db
				->set('polp.qty_supply', 0)
				->where('polp.id_po_logistik', $this->input->post('id_po_logistik'))
				->update('tr_h3_md_po_logistik_parts as polp');

			$this->load->model('H3_md_po_logistik_parts_detail_model', 'po_logistik_parts_detail');
			$this->po_logistik_parts_detail->update_qty_book($this->input->post('id_po_logistik'));
		}
		$this->db->trans_complete();

		$sales_order = (array) $this->sales_order->find($sales_order['id_sales_order'], 'id_sales_order');
		if ($this->db->trans_status() and $sales_order != null) {
			if ($this->input->post('kategori_po') == 'Bundling H1') {
				$this->session->set_userdata('pesan', "PO Bundling {$this->input->post('referensi_po_bundling')} berhasil diproses dengan sales order {$sales_order['id_sales_order']}.");
				$this->session->set_userdata('tipe', 'success');
			}
			send_json([
				'message' => 'Berhasil simpan sales order MD',
				'payload' => $sales_order,
				'redirect_url' => base_url('h3/h3_md_sales_order/detail?id=' . $sales_order['id_sales_order'])
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil simpan sales order MD'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['sales_order'] = $this->sales_order->get_sales_order($this->input->get('id'));
		$data['sales_order_parts'] = $this->sales_order_parts->get_sales_order_parts($this->input->get('id'));
		$data['qty_do'] = $this->db->select('IFNULL(SUM(dsop.qty_supply),0) as qty_supply')
		->from('tr_h3_md_do_sales_order dso')
		->join('tr_h3_md_do_sales_order_parts dsop', 'dso.id=dsop.id_do_sales_order_int')
		->where('dso.id_sales_order',$this->input->get('id'))
		->where_not_in('dso.status',array('Canceled','Rejected'))
		->get()->row_array();

		//Rekap PO 
		$cek_rekap_po = $this->db->select('id_rekap_purchase_order_dealer')
								->select('id_ref')
								->from('tr_h3_md_sales_order')
								->where('id_sales_order',$this->input->get('id'))
								->get()->row_array();
		if($cek_rekap_po['id_rekap_purchase_order_dealer'] == null ||$cek_rekap_po['id_rekap_purchase_order_dealer'] == 0 || $cek_rekap_po['id_rekap_purchase_order_dealer'] == ''){
			$data['po_id'] = $cek_rekap_po;
		}else{
			$no_po = $this->db->select('GROUP_CONCAT(rpdi.id_referensi SEPARATOR ",") as id_ref')
								->from('tr_h3_md_sales_order as so')
								->join('tr_h3_md_rekap_purchase_order_dealer_item rpdi','rpdi.id_rekap = so.id_rekap_purchase_order_dealer')
								->where('so.id_sales_order',$this->input->get('id'))
								->group_by('so.id_sales_order')
								->get()->row_array();
			$data['po_id'] = $no_po;
		}

		$data['qty_so'] = $this->db->select('IFNULL(SUM(sop.qty_order),0) as qty_order')
				->from('tr_h3_md_sales_order so')
				->join('tr_h3_md_sales_order_parts sop', 'sop.id_sales_order_int=so.id')
				->where('so.id_sales_order',$this->input->get('id'))
				->get()->row_array();


		$data['do'] = $this->db->select('status')
				->from('tr_h3_md_do_sales_order dso')
				->where('dso.id_sales_order',$this->input->get('id'))
				->get()->row_array();		
		$this->template($data);
	}

	public function cancel_v1()
	{
		$this->db->trans_start();
		$sales_order = (array) $this->sales_order->find($this->input->get('id'), 'id_sales_order');

		if ($sales_order['id_rekap_purchase_order_dealer'] != null && $sales_order['id_rekap_purchase_order_dealer'] != '') {
			$rekap_parts = $this->db
				->select('rpodp.po_id')
				->select('rpodp.id_part')
				->select('rpodp.kuantitas')
				->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
				->where('rpodp.id_rekap', $sales_order['id_rekap_purchase_order_dealer'])
				->get()->result_array();

			if (count($rekap_parts) > 0) {
				foreach ($rekap_parts as $row) {
					$this->db
						->set('ppdd.qty_so', "ppdd.qty_so - {$row['kuantitas']}", false)
						->set('ppdd.qty_pemenuhan', "ppdd.qty_pemenuhan + {$row['kuantitas']}", false)
						->where('ppdd.id_part', $row['id_part'])
						->where('ppdd.po_id', $row['po_id'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppdd');
				}
			}
		} elseif (($sales_order['po_type'] == 'HLO' || $sales_order['po_type'] == 'URG') and $sales_order['id_ref'] != null and $sales_order['id_ref'] != '') {
			$sales_order_parts = $this->db
				->select('sop.id_part')
				->select('sop.qty_order')
				->from('tr_h3_md_sales_order_parts as sop')
				->where('sop.id_sales_order', $sales_order['id_sales_order'])
				->get()->result_array();

			// Update Qty SO di pemenuhan PO dari dealer.
			if (count($sales_order_parts) > 0) {
				foreach ($sales_order_parts as $part) {
					$this->db
						->set('ppd.qty_so', "ppd.qty_so - {$part['qty_order']}", false)
						->set('ppd.qty_pemenuhan', "ppd.qty_pemenuhan + {$part['qty_order']}", false)
						->where('ppd.id_part', $part['id_part'])
						->where('ppd.po_id', $sales_order['id_ref'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');
				}
			}
		}

		if ($sales_order['gimmick'] == 1 and $sales_order['gimmick_tidak_langsung'] == 0) {
			$this->db
				->set('dog.sudah_claim', 0)
				->where('dog.id_campaign', $sales_order['id_campaign'])
				->where('dog.id_item', $sales_order['id_item'])
				->where('dog.id_do_sales_order', $sales_order['no_do_sumber_gimmick'])
				->update('tr_h3_md_do_sales_order_gimmick as dog');
		} elseif ($sales_order['gimmick'] == 1 and $sales_order['gimmick_tidak_langsung'] == 1) {
			$this->db
				->set('perolehan.sudah_create_so', 0)
				->where('perolehan.id', $sales_order['id_perolehan'])
				->update('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan');
		}

		$this->sales_order->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user')
		], [
			'id_sales_order' => $this->input->get('id')
		]);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', "Sales Order {$this->input->get('id')} berhasil dicancel.");
			$this->session->set_userdata('tipe', 'success');
		} else {
			$this->session->set_userdata('pesan', "Sales Order {$this->input->get('id')} tidak berhasil dicancel.");
			$this->session->set_userdata('tipe', 'danger');
		}
		redirect(
			base_url("h3/h3_md_sales_order/detail?id={$this->input->get('id')}")
		);
	}

	public function cancel()
	{
		$this->db->trans_start();
		$sales_order = (array) $this->sales_order->find($this->input->post('id'), 'id_sales_order');


		//Check PW 
		$inputPassword = $this->input->post('pw');

		$correctPassword = $this->db->select('ms.password')
									->from('tr_h3_md_setting_menu_password ms')
									->join('ms_menu mm','mm.id_menu=ms.id_menu')
									->where('mm.menu_link',$this->uri->segment(2))
									->get()
									->row_array();

		if(!empty($correctPassword)){
			$correctPassword['password'] = $correctPassword['password'];
		}else{ 
			$correctPassword['password'] = 'sparepart';
		}
								
		if ($inputPassword != $correctPassword['password']) {
			send_json([
				'status' => 'gagal',
				'message' => 'Tidak Berhasil Cancel SO. Cek kembali PW',
				'errors' => $this->form_validation->error_array()
			], 422);
		} 

		if ($sales_order['id_rekap_purchase_order_dealer'] != null && $sales_order['id_rekap_purchase_order_dealer'] != '') {
			$rekap_parts = $this->db
				->select('rpodp.po_id')
				->select('rpodp.id_part')
				->select('rpodp.kuantitas')
				->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
				->where('rpodp.id_rekap', $sales_order['id_rekap_purchase_order_dealer'])
				->get()->result_array();

			if (count($rekap_parts) > 0) {
				foreach ($rekap_parts as $row) {
					$this->db
						->set('ppdd.qty_so', "ppdd.qty_so - {$row['kuantitas']}", false)
						->set('ppdd.qty_pemenuhan', "ppdd.qty_pemenuhan + {$row['kuantitas']}", false)
						->where('ppdd.id_part', $row['id_part'])
						->where('ppdd.po_id', $row['po_id'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppdd');
				}
			}
		} elseif (($sales_order['po_type'] == 'HLO' || $sales_order['po_type'] == 'URG') and $sales_order['id_ref'] != null and $sales_order['id_ref'] != '') {
			$sales_order_parts = $this->db
				->select('sop.id_part')
				->select('sop.qty_order')
				->from('tr_h3_md_sales_order_parts as sop')
				->where('sop.id_sales_order', $sales_order['id_sales_order'])
				->get()->result_array();

			// Update Qty SO di pemenuhan PO dari dealer.
			if (count($sales_order_parts) > 0) {
				foreach ($sales_order_parts as $part) {
					$this->db
						->set('ppd.qty_so', "ppd.qty_so - {$part['qty_order']}", false)
						->set('ppd.qty_pemenuhan', "ppd.qty_pemenuhan + {$part['qty_order']}", false)
						->where('ppd.id_part', $part['id_part'])
						->where('ppd.po_id', $sales_order['id_ref'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');
				}
			}
		}

		if ($sales_order['gimmick'] == 1 and $sales_order['gimmick_tidak_langsung'] == 0) {
			$this->db
				->set('dog.sudah_claim', 0)
				->where('dog.id_campaign', $sales_order['id_campaign'])
				->where('dog.id_item', $sales_order['id_item'])
				->where('dog.id_do_sales_order', $sales_order['no_do_sumber_gimmick'])
				->update('tr_h3_md_do_sales_order_gimmick as dog');
		} elseif ($sales_order['gimmick'] == 1 and $sales_order['gimmick_tidak_langsung'] == 1) {
			$this->db
				->set('perolehan.sudah_create_so', 0)
				->where('perolehan.id', $sales_order['id_perolehan'])
				->update('tr_h3_perolehan_sales_campaign_gimmick_tidak_langsung_dealers as perolehan');
		}

		$this->sales_order->update([
			'status' => 'Canceled',
			'alasan_cancel_so' => $this->input->post('alasan_reject'),
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user')
		], [
			'id_sales_order' => $this->input->post('id')
		]);
		$this->db->trans_complete();
		if ($this->db->trans_status()) {
			send_json([
				'status' => 'Sukses',
				'message' => 'Berhasil Cancel SO MD',
				'payload' => $sales_order,
				'redirect_url' => base_url('h3/h3_md_sales_order/detail?id=' . $sales_order['id_sales_order'])
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil cancel sales order MD'
			], 422);
		}
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['sales_order'] = $this->sales_order->get_sales_order($this->input->get('id'));
		$data['sales_order_parts'] = $this->sales_order_parts->get_sales_order_parts($this->input->get('id'));

		$this->template($data);
	}

	public function update()
	{
		$this->validate();
		$this->db->trans_start();

		$sales_order = $this->input->post([
			'jenis_pembayaran', 'id_salesman', 'total_amount', 'kategori_po','is_hadiah'
		]);
		$sales_order['delete_at_create_do_sales_order'] = 0;

		if($this->input->post('is_hadiah') == 1){
			$sales_order['gimmick'] = 1;
		}

		$part_keys = [
			'id_part', 'harga', 'qty_order', 'qty_on_hand',
			'qty_pemenuhan', 'tipe_diskon', 'diskon_value',
			'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon', 'jenis_diskon_campaign'
		];
		if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
			$part_keys[] = 'id_tipe_kendaraan';
		}
		$sales_order_parts = $this->getOnly($part_keys, $this->input->post('parts'), $this->input->post(['id_sales_order']));

		$sales_order_parts = array_map(function ($part) {
			// if ($this->input->post('created_by_md') == 1) {
			$part['qty_pemenuhan'] = $part['qty_order'];
			// }
			return $part;
		}, $sales_order_parts);

		$this->sales_order->update($sales_order, $this->input->post(['id_sales_order']));
		$this->sales_order_parts->update_batch($sales_order_parts, $this->input->post(['id_sales_order']));

		$purchase_order_parts = $this->getOnly([
			'id_part', 'harga', 'qty_order', 'qty_on_hand',
			'qty_pemenuhan', 'tipe_diskon', 'diskon_value',
			'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon'
		], $this->input->post('parts'), [
			'po_id' => $this->input->post('id_ref'),
		]);

		$purchase_order_parts = array_map(function ($part) {
			return [
				'po_id' => $part['po_id'],
				'id_part' => $part['id_part'],
				'harga_saat_dibeli' => $part['harga'],
				'kuantitas' => $part['qty_order'],
				'tipe_diskon' => $part['tipe_diskon'],
				'diskon_value' => $part['diskon_value'],
				'tipe_diskon_campaign' => $part['tipe_diskon_campaign'],
				'diskon_value_campaign' => $part['diskon_value_campaign'],
			];
		}, $purchase_order_parts);

		if ($this->input->post('created_by_md') == 1) {
			$this->purchase_order_parts->update_batch($purchase_order_parts, [
				'po_id' => $this->input->post('id_ref'),
			]);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$sales_order = (array) $this->sales_order->get($this->input->post(['id_sales_order']), true);
			send_json([
				'message' => 'Berhasil memperbarui sales order MD',
				'payload' => $sales_order,
				'redirect_url' => base_url('h3/h3_md_sales_order/detail?id=' . $sales_order['id_sales_order'])
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil memperbarui sales order MD'
			], 422);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('po_type', 'Tipe PO', 'required');
		$this->form_validation->set_rules('kategori_po', 'Kategori PO', 'required');
		// $this->form_validation->set_rules('id_salesman', 'Nama Salesman', 'required');
		$this->form_validation->set_rules('jenis_pembayaran', 'Jenis Pembayaran', 'required');
		$this->form_validation->set_rules('tipe_source', 'Tipe Source', 'required');
		$this->form_validation->set_rules('produk', 'Produk', 'required');
		// $this->form_validation->set_rules('is_ev', 'EV/Non EV', 'required');

		if ($this->input->post('gimmick') == 1 and $this->input->post('gimmick_tidak_langsung') == 1 and $this->uri->segment(3) == 'save') {
			$this->db
				->from('tr_h3_md_sales_order as so')
				->where('so.id_perolehan', $this->input->post('id_perolehan'))
				->where('so.gimmick', 1)
				->where('so.gimmick_tidak_langsung', 1)
				->where('so.status != ', 'Canceled');

			if (count($this->db->get()->result_array()) > 0) {
				send_json([
					'message' => 'Tidak bisa buat sales order karena sudah SO gimmick sebelumnya'
				], 422);
			}
		}

		if ($this->input->post('gimmick') == 1 and $this->input->post('gimmick_tidak_langsung') == 0 and $this->uri->segment(3) == 'save') {
			$this->db
				->from('tr_h3_md_sales_order as so')
				->where('so.id_perolehan', $this->input->post('id_perolehan'))
				->where('so.gimmick', 1)
				->where('so.gimmick_tidak_langsung', 0)
				->where('so.no_do_sumber_gimmick', $this->input->post('no_do_sumber_gimmick'))
				->where('so.id_dealer', $this->input->post('id_dealer'))
				->where('so.status != ', 'Canceled');

			if (count($this->db->get()->result_array()) > 0) {
				send_json([
					'message' => 'Tidak bisa buat sales order karena sudah SO gimmick sebelumnya'
				], 422);
			}
		}

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function download_template()
	{
		$this->load->helper('download');
		force_download('assets/template/sales_order_md_template.xlsx', NULL);
	}

	public function upload()
	{
		$data['mode']    = 'upload';
		$data['set']     = "form";

		$this->template($data);
	}

	public function store_upload()
	{
		$config['upload_path'] = './uploads/sales_order_md_upload/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')) {
			$errors = [
				'file' => $this->upload->display_errors('', '')
			];
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $errors
			], 422);
		} else {
			$this->read_excel($this->upload->data()['file_name']);
		}
	}

	public function read_excel($filename)
	{
		//  Include PHPExcel_IOFactory
		include APPPATH . 'third_party/PHPExcel/PHPExcel/IOFactory.php';

		$filepath = "./uploads/sales_order_md_upload/{$filename}";

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
			'kode_customer' => $sheet->getCell('B1')->getValue(),
			'produk' => $sheet->getCell('E4')->getValue(),
			'kategori_po' => $sheet->getCell('E5')->getValue(),
			'id_salesman' => $sheet->getCell('E6')->getValue(),
			'po_type' => $sheet->getCell('E3')->getValue(),
			'bulan_kpb' => $sheet->getCell('E2')->getValue(),
			'tanggal_order' => $sheet->getCell('E1')->getValue(),
		];

		$parts = [];
		for ($row = 9; $row <= $highestRow; $row++) {
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
			if ($rowData[1] == null || $rowData[1] == '') continue;
			$part = [];
			$part['id_part'] = $rowData[1];
			$part['harga'] = $rowData[3];
			$part['qty_order'] = $rowData[4];
			$parts[] = $part;
		}
		$validate_data['parts'] = $parts;

		$this->validate_sales_upload($validate_data);

		$kode_customer = $sheet->getCell('B1')->getValue();
		$dealer = $this->db
			->select('d.id_dealer')
			->from('ms_dealer as d')
			->where('d.kode_dealer_md', $kode_customer)
			->get()->row();

		$salesman = $this->db
			->from('ms_karyawan as k')
			->where('k.npk', $sheet->getCell('E6')->getValue())
			->get()->row();

		$tanggal_order = $sheet->getCell('E1');
		if (PHPExcel_Shared_Date::isDateTime($tanggal_order) && $tanggal_order->getValue() != null) {
			$unixTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($tanggal_order->getValue());
			$tanggal_order = date('Y-m-d', $unixTimeStamp);
		} else {
			$tanggal_order = $sheet->getCell('E1')->getValue();
		}

		$part_for_produk = $this->db
			->select('skp.produk')
			->from('ms_part as p')
			->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
			->where('p.id_part', $parts[0]['id_part'])
			->get()->row_array();

		if ($part_for_produk != null) {
			$produk = $part_for_produk['produk'];
		} else {
			$produk = $sheet->getCell('E4')->getValue();
		}


		$po_type = $sheet->getCell('E3')->getValue();
		$purchase_order = [
			'id_dealer' => $dealer->id_dealer,
			'tanggal_order' => $tanggal_order,
			'po_type' => $po_type,
			'produk' => $produk,
			'kategori_po' => $sheet->getCell('E5')->getValue(),
			'id_salesman' => $salesman != null ? $salesman->id_karyawan : null,
			'po_id' => $this->purchase_order->generatePONumber($po_type, $dealer->id_dealer, $tanggal_order),
			'status' => 'Processed by MD',
			'created_by_md' => 1,
		];

		$sales_order = [
			'id_dealer' => $dealer->id_dealer,
			'tanggal_order' => $tanggal_order,
			'bulan_kpb' => $sheet->getCell('E2')->getValue(),
			'po_type' => $po_type,
			'produk' => $produk,
			'kategori_po' => $sheet->getCell('E5')->getValue(),
			'id_salesman' => $salesman != null ? $salesman->id_karyawan : null,
			'tipe_source' => 'Dealer',
			'jenis_pembayaran' => 'Credit',
			'id_sales_order' => $this->sales_order->generateID($po_type, $dealer->id_dealer, $tanggal_order),
			'id_ref' => $purchase_order['po_id'],
			'type_ref' => 'purchase_order_dealer',
			'from_upload' => 1
		];

		if ($po_type == 'REG' || $po_type == 'FIX') {
			$this->load->model('H3_md_ms_tipe_po_model', 'tipe_po');
			$purchase_order['batas_waktu'] = $this->tipe_po->get_batas_waktu($dealer->id_dealer, $po_type);
			$sales_order['batas_waktu'] = $this->tipe_po->get_batas_waktu($dealer->id_dealer, $po_type);
		}

		$total_amount = 0;
		$purchase_order_parts = [];
		$sales_order_parts = [];
		$jumlah_dus = $this->get_jumlah_dus($parts);
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		foreach ($parts as $part) {
			$set_diskon = [
				'tipe_diskon' => null,
				'diskon_value' => null,
				'tipe_diskon_campaign' => null,
				'diskon_value_campaign' => null
			];
			$purchase_order_part = [
				'po_id' => $purchase_order['po_id'],
				'id_part' => $part['id_part'],
				'harga_saat_dibeli' => $part['harga'],
				'kuantitas' => $part['qty_order'],
			];
			$purchase_order_part = array_merge($purchase_order_part, $set_diskon);

			$sales_order_part = [
				'id_sales_order' => $sales_order['id_sales_order'],
				'id_part' => $part['id_part'],
				'harga' => $part['harga'],
				'qty_order' => $part['qty_order'],
				'qty_on_hand' => $part['qty_order'],
				'qty_pemenuhan' => $part['qty_order'],
			];
			$sales_order_part = array_merge($sales_order_part, $set_diskon);

			$diskon = null;
			if ($sales_order['produk'] == 'Oil') {
				$diskon = $this->diskon_oli_reguler->get_diskon($part['id_part'], $sales_order['id_dealer'], $jumlah_dus);
			} else {
				$diskon = $this->diskon_part_tertentu->get_diskon($part['id_part'], $sales_order['id_dealer'], $sales_order['po_type'], $sales_order['produk']);
			}
			if ($diskon != null) {
				$purchase_order_part['tipe_diskon'] = $diskon['tipe_diskon'];
				$sales_order_part['tipe_diskon'] = $diskon['tipe_diskon'];

				$purchase_order_part['diskon_value'] = $diskon['diskon_value'];
				$sales_order_part['diskon_value'] = $diskon['diskon_value'];
			}

			if ($sales_order['kategori_po'] != 'KPB' || !$sales_order['po_type'] != 'HLO') {
				$diskon_campaign = $this->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['qty_order']);

				if ($diskon_campaign != null) {
					$purchase_order_part['tipe_diskon_campaign'] = $diskon_campaign['tipe_diskon'];
					$sales_order_part['tipe_diskon_campaign'] = $diskon_campaign['tipe_diskon'];

					$purchase_order_part['diskon_value_campaign'] = $diskon_campaign['diskon_value'];
					$sales_order_part['diskon_value_campaign'] = $diskon_campaign['diskon_value'];
				}
			}

			$harga_setelah_diskon = $this->do_sales_order_parts->harga_setelah_diskon([
				'harga_jual' => $part['harga'],
				'tipe_diskon_satuan_dealer' => $sales_order_part['tipe_diskon'],
				'diskon_satuan_dealer' => $sales_order_part['diskon_value'],
				'tipe_diskon_campaign' => $sales_order_part['tipe_diskon_campaign'],
				'diskon_campaign' => $sales_order_part['diskon_value_campaign'],
			]);
			$total_amount += $harga_setelah_diskon * $part['qty_order'];
			$purchase_order_parts[] = $purchase_order_part;
			$sales_order_parts[] = $sales_order_part;
		}
		$purchase_order['total_amount'] = $total_amount;
		$sales_order['total_amount'] = $total_amount;
		$this->db->trans_start();
		$this->purchase_order->insert($purchase_order);
		$this->purchase_order_parts->insert_batch($purchase_order_parts);
		foreach ($purchase_order_parts as $part) {
			$this->order_parts_tracking->insert(
				$this->get_in_array(['po_id', 'id_part'], $part)
			);
		}
		$this->sales_order->insert($sales_order);
		$this->sales_order_parts->insert_batch($sales_order_parts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Import Sales Order berhasil dilakukan.');
			$this->session->set_userdata('tipe', 'success');

			$sales_order = $this->sales_order->find($sales_order['id_sales_order'], 'id_sales_order');
			send_json([
				'payload' => $sales_order,
			]);
		} else {
			$this->session->set_userdata('pesan', 'Import Sales Order tidak berhasil dilakukan.');
			$this->session->set_userdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	private function get_jumlah_dus($parts)
	{
		$total_dus = 0;
		foreach ($parts as $part) {
			$data_part = $this->db
				->select('IFNULL(p.qty_dus, 1) as qty_dus')
				->from('ms_part as p')
				->where('p.id_part', $part['id_part'])
				->get()->row_array();

			$total_dus += $part['qty_order'] / $data_part['qty_dus'];
		}

		return floor($total_dus);
	}

	public function validate_sales_upload($data)
	{
		$this->form_validation->set_data($data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kode_customer', 'Kode Customer', array(
			'required',
			array(
				'kode_customer_callable',
				function ($str) {
					$dealer = $this->db->from('ms_dealer as d')
						->where('d.kode_dealer_md', trim($str))
						->get()->row();

					if ($dealer == null) {
						$this->form_validation->set_message('kode_customer_callable', 'Kode Customer tidak ditemukan.');
						return false;
					}
					return true;
				}
			)
		));
		$this->form_validation->set_rules('po_type', 'Tipe PO', 'required|in_list[FIX,REG,URG,HLO]');
		if (isset($data['bulan_kpb'])) {
			$this->form_validation->set_rules('bulan_kpb', 'Bulan KPB', 'numeric');
		}
		$this->form_validation->set_rules('kategori_po', 'Kategori PO', 'required|in_list[SIM Part,Non SIM Part]');

		if (isset($data['id_salesman'])) {
			$this->form_validation->set_rules('id_salesman', 'Salesman', array(
				array(
					'salesman_callable',
					function ($str) {
						$karyawan = $this->db
							->from('ms_karyawan as k')
							->where('k.npk', $str)
							->get()->row();

						if ($karyawan == null) {
							$this->form_validation->set_message('salesman_callable', 'Salesman tidak ditemukan.');
							return false;
						}
						return true;
					}
				)
			));
		}

		$this->form_validation->set_rules('produk', 'Produk', 'required|in_list[Parts,Oil,Acc]');

		$this->form_validation->set_rules('tanggal_order', 'Tanggal Order', array(
			'required',
			array(
				'tanggal_order_callable',
				function ($data) {
					if (!is_numeric($data)) {
						$this->form_validation->set_message('tanggal_order_callable', 'Format Tanggal Order harus YYYY-MM-DD.');
						return false;
					}
					return true;
				}
			)
		));

		$produks = [];
		foreach ($data['parts'] as $part) {
			$row = $this->db
				->select('skp.produk')
				->from('ms_part as p')
				->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = p.kelompok_part')
				->where('p.id_part', $part['id_part'])
				->get()->row_array();

			if ($row != null) {
				$produks[] = $row['produk'];
			}
		}
		$produks = array_unique($produks);

		if (!$this->form_validation->run() || count($produks) > 1) {
			$upload_errors = [];
			foreach ($this->form_validation->error_array() as $key => $value) {
				$upload_errors[] = $value;
			}

			if (count($produks) > 1) {
				$upload_errors[] = 'Kode part memiliki tipe produk yang beragam.';
			}

			send_json([
				'error_type' => 'upload_error',
				'message' => 'Data tidak valid',
				'errors' => $upload_errors
			], 422);
		}
	}

	public function generate_qty_booking(){
		// $kelompok_part = array(
		// 	'7','15','22'
		// );

		$id_part_int = array(
		'6537',
		'46014',
		'46007',
		'11098',
		'25566',
		'56543',
		'20136',
		'45983',
		'6608',
		'9711',
		'20117',
		'25154',
		'2349',
		'2350',
		'26257',
		'26233',
		'21479',
		'2306',
		'2332',
		'50861',
		'26264',
		'25018',
		'25016',
		'25616',
		'4267',
		'18426',
		'6641',
		'39276',
		'557',
		'22451',
		'25135',
		'35759',
		'26262',
		'3256',
		'52962',
		'8317',
		'650',
		'370',
		'18647',
		'13740',
		'36920',
		'2895',
		'3630',
		'35740',
		'6618',
		'39231',
		'25134',
		'664',
		'52729',
		'25642',
		'6642',
		'56003',
		'50155',
		'32550',
		'3255',
		'50817',
		'29092',
		'3243',
		'29154',
		'42413',
		'25027',
		'39114',
		'532',
		'52725',
		'573',
		'41091',
		'32548',
		'20832',
		'12558'
			);
		$parts = $this->db->select('mp.id_part_int, mp.id_part')
						  ->from('ms_part as mp')
						//   ->join('ms_kelompok_part as mkp','mkp.id=mp.kelompok_part_int')
						//   ->where_in('mp.kelompok_part_int',$kelompok_part)
						  ->where_in('mp.id_part_int',$id_part_int)
						  ->get()->result_array();
		// var_dump($parts);
		// die();				  
		$jumlah_data = 0;
		foreach($parts as $part){
			// $qty_booking = $this->stock_int->qty_booking($part['id_part_int']);
			$qty_intransit = $this->stock_int->qty_intransit($part['id_part_int']);
			
			$check_id_part = $this->db->select('id_part_int')
										->from('tr_stok_part_summary')
										->where('id_part_int',$part['id_part_int'])->get()->row_array();

				if($check_id_part['id_part_int']!=NULL){
					$this->db->set('qty_intransit', $qty_intransit);
					// $this->db->set('qty_book', $qty_booking);
					$this->db->where('id_part_int', $part['id_part_int']);
					$this->db->update('tr_stok_part_summary');
					// var_dump("test");
					// die();
				}else{
					$data = array(
					'id_part' => $part['id_part'],
					'id_part_int' => $part['id_part_int'],
					'qty_intransit' => $qty_intransit
					// 'qty_book' => $qty_booking
					);
					$this->db->insert('tr_stok_part_summary', $data);
					// var_dump("test1");
					// die();
				}
			// var_dump($update_data);
			// die();
			$jumlah_data++;
		}

		echo "Berhasil update : ". $jumlah_data ." part";
	}

	public function generate_parts_fix(){

		$this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('H3_md_niguri_header_model', 'niguri_header');


        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $this->db
        ->select('ar.id_part')
        ->select('ar.id_part_int')
        ->select('ar.id_dealer')
        ->select('ar.suggested_order')
        ->select('ar.adjusted_order')
        ->select('mp.minimal_order as qty_min_order')
		->select('mp.harga_md_dealer as harga')
        ->select('mp.harga_dealer_user')
        ->select('mp.nama_part')
        ->select('mp.kelompok_part')
        ->select('ar.suggested_order as kuantitas')
        ->select('ar.adjusted_order as qty_order')
        ->from('tr_h3_md_autofulfillment as ar')
        ->join('ms_part as mp', 'mp.id_part = ar.id_part')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = mp.kelompok_part')
        ->where('ar.id_dealer', $this->input->get('nama_dealer'))
        ->where('skp.produk', $this->input->get('produk'))
        ->where('mp.fix', 1)
        ->group_start()
        ->where('ar.suggested_order >', 0)
        ->or_where('ar.adjusted_order >', 0)
        ->group_end()
        ->order_by('ar.suggested_order', 'desc')
        ;

        // if($this->input->get('kategori_po') != null and $this->input->get('kategori_po') == 'SIM Part'){
        //     $this->db->where('mp.sim_part', 1);
        // }else if($this->input->get('kategori_po') != null and $this->input->get('kategori_po') == 'Non SIM Part'){
        //     $this->db->where('mp.sim_part', 0);
        // }

        // $this->db->where('skp.produk', $this->input->get('produk'));

		$parts = [];

        // send_json(
        //     $this->db->get()->result_array()
        // );

		foreach ($this->db->get()->result_array() as $row) {
            $row['qty_on_hand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
            // $row['qty_in_transit'] = $this->stock_int->qty_intransit($row['id_part_int']);
            // $row['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($row['id_part_int']);
            // $row['avg_sales'] = round($this->do_sales_order->qty_avg_sales($row['id_part_int'], 'id_part_int'));
            // $row['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($row['id_part_int'], $this->input->get('jenis_po'), $tanggal_order, $pesan_untuk_bulan);
            // $row['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($row['id_part'], $this->input->get('jenis_po'), $tanggal_order, $pesan_untuk_bulan);

			// $row['qty_on_hand'] = 0;
            // $row['qty_avs'] = 0;
            $row['qty_in_transit'] = 0;
            $row['fix_bulan_lalu'] = 0;
            $row['avg_sales'] = 0;
            $row['qty_bo'] = 0;
            $row['qty_bo_dealer'] = 0;

            // $qty_suggest = $this->niguri_header->qty_suggest($row['id_part'], $this->input->get('jenis_po'), $tanggal_order);
            // if($qty_suggest != null){
            //     $row['qty_suggest'] = $qty_suggest['qty_suggest'];

            //     if($perbedaan_bulan != 0 AND in_array($perbedaan_bulan, range(1,5)) AND $this->input->get('jenis_po') == 'FIX'){
            //         $row['_n_key'] = "fix_order_n_{$perbedaan_bulan}";
            //     }
            // }else{
                $row['qty_suggest'] = 0;
            // }
            $parts[] = $row;
        }

		send_json($parts);
	}

	public function generate_parts_reg(){
		$this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('H3_md_niguri_header_model', 'niguri_header');


        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        $this->db
        ->select('ar.id_part')
        ->select('ar.id_part_int')
        ->select('ar.id_dealer')
        ->select('ar.suggested_order')
        ->select('ar.adjusted_order')
        ->select('mp.harga_dealer_user as harga_saat_dibeli')
        ->select('mp.nama_part')
        ->select('mp.kelompok_part')
        ->select('ar.suggested_order as kuantitas')
        ->select('ar.adjusted_order as kuantitas')
        ->from('tr_h3_md_autofulfillment as ar')
        ->join('ms_part as mp', 'mp.id_part = ar.id_part')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = mp.kelompok_part')
        ->where('ar.id_dealer', $this->input->get('nama_dealer'))
        ->where('skp.produk', $this->input->get('produk'))
        ->where('mp.reguler', 1)
        ->group_start()
        ->where('ar.suggested_order >', 0)
        ->or_where('ar.adjusted_order >', 0)
        ->group_end()
        ->order_by('ar.suggested_order', 'desc')
        ;

        // if($this->input->get('kategori_po') != null and $this->input->get('kategori_po') == 'SIM Part'){
        //     $this->db->where('mp.sim_part', 1);
        // }else if($this->input->get('kategori_po') != null and $this->input->get('kategori_po') == 'Non SIM Part'){
        //     $this->db->where('mp.sim_part', 0);
        // }

        // $this->db->where('skp.produk', $this->input->get('produk'));

		$parts = [];

        // send_json(
        //     $this->db->get()->result_array()
        // );

		foreach ($this->db->get()->result_array() as $row) {
            $row['qty_on_hand'] = $this->stock_int->qty_on_hand($row['id_part_int']);
            $row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
            // $row['qty_in_transit'] = $this->stock_int->qty_intransit($row['id_part_int']);
            // $row['fix_bulan_lalu'] = $this->purchase_order_parts->qty_fix_bulan_lalu($row['id_part_int']);
            // $row['avg_sales'] = round($this->do_sales_order->qty_avg_sales($row['id_part_int'], 'id_part_int'));
            // $row['qty_bo'] = $this->purchase_order_parts->qty_bo_ahm($row['id_part_int'], $this->input->get('jenis_po'), $tanggal_order, $pesan_untuk_bulan);
            // $row['qty_bo_dealer'] = $this->purchase_order_parts->qty_bo_dealer($row['id_part'], $this->input->get('jenis_po'), $tanggal_order, $pesan_untuk_bulan);

			// $row['qty_on_hand'] = 0;
            // $row['qty_avs'] = 0;
            $row['qty_in_transit'] = 0;
            $row['fix_bulan_lalu'] = 0;
            $row['avg_sales'] = 0;
            $row['qty_bo'] = 0;
            $row['qty_bo_dealer'] = 0;

            // $qty_suggest = $this->niguri_header->qty_suggest($row['id_part'], $this->input->get('jenis_po'), $tanggal_order);
            // if($qty_suggest != null){
            //     $row['qty_suggest'] = $qty_suggest['qty_suggest'];

            //     if($perbedaan_bulan != 0 AND in_array($perbedaan_bulan, range(1,5)) AND $this->input->get('jenis_po') == 'FIX'){
            //         $row['_n_key'] = "fix_order_n_{$perbedaan_bulan}";
            //     }
            // }else{
                $row['qty_suggest'] = 0;
            // }
            $parts[] = $row;
        }

		send_json($parts);
	}

	public function update_harga()
    {
        $this->load->helper('calculate_discount');
        $id_sales_order = $this->input->get('id_sales_order');

		//Update harga baru di SO
		$parts = $this->db
			->select('sop.id_part')
			->select('sop.hpp')
			->select('sop.harga')
			->select('sop.harga_setelah_diskon')
			->select('sop.diskon')
			->select('sop.diskon_value')
			->select('sop.id_sales_order_int')
			->select('sop.tipe_diskon')
			->select('sop.qty_pemenuhan')
			->select('sop.id_part_int')
			->select('sop.id_sales_order')
			->select('p.harga_dealer_user as harga_baru')
			->select('p.harga_md_dealer as hpp_baru')
			->from('tr_h3_md_sales_order_parts sop')
			->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
			->where('sop.id_sales_order', $id_sales_order)
			->get()->result_array();

		// Cek apakah SO telah buat DO 
		$cek_do = $this->db->select('id_do_sales_order')
							->select('id as id_do_sales_order_int')
							->select('approved_at')
							->select('sub_total')
							->select('total_ppn')
							->from('tr_h3_md_do_sales_order')
							->where('id_sales_order', $id_sales_order)
							->get()->row_array();	

		$total_harga_so = '';
		foreach ($parts as $part) {
			$harga_lama = $part['harga'];
			$harga_baru = $part['harga_baru'];
			$total_harga_part = 0;
			$diskon_value = '';
			if ($harga_lama != $harga_baru) {
				$diskon_harga_baru = calculate_discount($part['diskon_value'], $part['tipe_diskon'], $part['harga_baru']);
				$harga_baru_setelah_diskon = $harga_baru - $diskon_harga_baru;

				$kuantitas = $part['qty_pemenuhan'];
				$total_harga_part = $harga_baru_setelah_diskon * $kuantitas;

				if($part['tipe_diskon'] == 'Persen'){
					$diskon_value = round(($part['diskon_value']/100)*$harga_baru);
				}elseif($part['tipe_diskon'] == 'Rupiah'){
					$diskon_value = $harga_baru - $part['diskon_value'];
				}

				$this->db
					->set('hpp', $part['hpp_baru'])
					->set('harga', $harga_baru)
					->set('diskon', $diskon_value)
					->set('harga_setelah_diskon', $harga_baru_setelah_diskon)
					->where('id_sales_order_int', $part['id_sales_order_int'])
					->where('id_part_int', $part['id_part_int'])
					->update('tr_h3_md_sales_order_parts sop');

				log_message('info', sprintf('Harga part %s pada sales order MD %s diupdate menjadi %s', $part['id_part'], $part['id_sales_order'], $harga_baru));
				

				if(!empty($cek_do)){
				//Update harga DO
					$this->db
						->set('harga_beli', $part['hpp_baru'])
						->set('harga_jual', $harga_baru)
						->set('harga_setelah_diskon', $harga_baru_setelah_diskon)
						->where('id_do_sales_order_int', $cek_do['id_do_sales_order_int'])
						->where('id_part_int', $part['id_part_int'])
						->update('tr_h3_md_do_sales_order_parts sop');
				}
			}else{
				$kuantitas = $part['qty_pemenuhan'];
				$total_harga_part = $part['harga_setelah_diskon']*$kuantitas;
			}
			$total_harga_so += $total_harga_part;
		}	

		//Cek total harga lama dan total harga baru 
		$total_so = $this->db->select('total_amount')
							->from('tr_h3_md_sales_order')
							->where('id_sales_order',$id_sales_order)
							->get()->row_array();
				
		if($total_so['total_amount'] != $total_harga_so){
			$this->db
					->set('total_amount', $total_harga_so)
					->where('id_sales_order', $id_sales_order)
					->update('tr_h3_md_sales_order so');
		}

		if(!empty($cek_do)){
			if($cek_do['sub_total'] != $total_harga_so){
				if($cek_do['total_ppn'] != null || $cek_do['total_ppn'] != ''){
					$ppn = $total_harga_so*round(getPPN(1.1,false)/10,2);
					$total = $ppn + $total_harga_so;

					$this->db
					->set('sub_total', $total_harga_so)
					->set('total_ppn', $ppn)
					->set('total', $total)
					->where('id',  $cek_do['id_do_sales_order_int'])
					->update('tr_h3_md_do_sales_order do');
				}else{
					$this->db
					->set('sub_total', $total_harga_so)
					->set('total', $total_harga_so)
					->where('id',  $cek_do['id_do_sales_order_int'])
					->update('tr_h3_md_do_sales_order do');
				}
			}
		}

        redirect(
            base_url(sprintf('h3/h3_md_sales_order/detail?id=%s', $id_sales_order))
        );
    }

}
