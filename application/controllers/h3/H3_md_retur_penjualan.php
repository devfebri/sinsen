<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_retur_penjualan extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_retur_penjualan";
    protected $title  = "Retur Penjualan";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('calculate_discount');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

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

		$this->load->model('h3_md_retur_penjualan_model', 'retur_penjualan');
		$this->load->model('h3_md_retur_penjualan_parts_model', 'retur_penjualan_parts');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
		$this->load->model('H3_md_lokasi_rak_parts_model', 'lokasi_rak_parts');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";

		$this->template($data);
	}

	public function check_cashback_retur(){
		$parts_faktur = $this->db
		->select('dop.id_part')
		->select('dop.qty_supply as qty_order')
		->from('tr_h3_md_packing_sheet as ps')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
		->where('ps.no_faktur', $this->input->post('no_faktur'))
		->get()->result_array();

		$parts = $this->input->post('parts');

		if(count($parts) > 0){
			$parts_faktur = array_map(function($row) use ($parts){
				foreach ($parts as $part) {
					if($part['id_part'] == $row['id_part']){
						$row['qty_order'] -= $part['qty_order'];
						break;
					}
				}
				return $row;
			}, $parts_faktur);
		}

		// $this->output->set_status_header(500);
		// send_json($parts_faktur);

		// send_json(
		// 	[
		// 		'cashback' => $this->sales_campaign->get_cashback_campaign($parts_faktur),
		// 		'parts_faktur' => $parts_faktur
		// 	]
		// );
		
		send_json(
			$this->sales_campaign->get_cashback_campaign($parts_faktur)
		);
	}

	public function save(){
		$this->validate();
		$retur_penjualan = array_merge($this->input->post(['id_dealer', 'no_faktur', 'alasan', 'total_nilai_retur', 'tanggal_terima_retur']), [
			'id_retur_penjualan' => $this->retur_penjualan->generateID()
		]);
		$parts = $this->getOnly(['id_part', 'qty_retur', 'alasan', 'id_lokasi_rak'], $this->input->post('parts'), [
			'id_retur_penjualan' => $retur_penjualan['id_retur_penjualan']
		]);

		$this->db->trans_start();
		$this->retur_penjualan->insert($retur_penjualan);
		$this->retur_penjualan_parts->insert_batch($parts);
		
		// Update Tabel Packing Sheet menjadi retur penjualan = 1 
		$this->db->set('is_retur_penjualan', 1);
		$this->db->where('no_faktur', $this->input->post('no_faktur'));
		$this->db->update('tr_h3_md_packing_sheet');

		$this->db->trans_complete();

		$retur_penjualan = (array) $this->retur_penjualan->find($retur_penjualan['id_retur_penjualan'], 'id_retur_penjualan');
		if ($this->db->trans_status() AND $retur_penjualan != null) {
			$message = 'Retur Penjualan berhasil dibuat.';

			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'info');
			
			send_json([
				'message' => $message,
				'redirect_url' => base_url(sprintf('h3/h3_md_retur_penjualan/detail?id_retur_penjualan=%s', $retur_penjualan['id_retur_penjualan']))
			]);
		}else{
			send_json([
				'message' => 'Retur Penjualan tidak berhasil dibuat.'
			], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['retur_penjualan'] = $this->db
		->select('rp.id_retur_penjualan')
		->select('rp.status')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.alamat')
        ->select('k.nama_lengkap as nama_salesman')
		->select('rp.no_faktur')
		->select('ps.id_packing_sheet')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('rp.tanggal_terima_retur')
		->select('rp.alasan')
        ->select('do.diskon_cashback')
        ->select('do.diskon_cashback_otomatis')
		->select('do.diskon_insentif')
        ->select('do.total as total_nilai_faktur')
		->from('tr_h3_md_retur_penjualan as rp')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where('rp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->row_array();

		$qty_faktur = $this->db
		->select('SUM(plp.qty_disiapkan) as qty_disiapkan')
		->from('tr_h3_md_picking_list_parts as plp')
		->where('plp.id_picking_list = pl.id_picking_list')
		->where('plp.id_part = rpp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('rpp.id_part')
		->select('p.nama_part')
		->select('dop.harga_jual as harga_dealer_user')
		->select("IFNULL(({$qty_faktur}), 0) as qty_faktur")
		->select('dop.tipe_diskon_campaign')
        ->select('dop.diskon_campaign')
        ->select('dop.tipe_diskon_satuan_dealer')
        ->select('dop.diskon_satuan_dealer')
		->select('rpp.qty_retur')
		->select('rpp.id_lokasi_rak')
		->select('lr.kode_lokasi_rak')
		->select('rpp.alasan')
		->from('tr_h3_md_retur_penjualan_parts as rpp')
		->join('tr_h3_md_retur_penjualan as rp', 'rp.id_retur_penjualan = rpp.id_retur_penjualan')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref and dop.id_part = rpp.id_part)')
		->join('ms_part as p', 'p.id_part = rpp.id_part')
		->join('ms_h3_md_lokasi_rak as lr', 'lr.id = rpp.id_lokasi_rak', 'left')
		->where('rpp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->result_array();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['retur_penjualan'] = $this->db
		->select('rp.id_retur_penjualan')
		->select('rp.status')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.alamat')
        ->select('k.nama_lengkap as nama_salesman')
		->select('rp.no_faktur')
		->select('ps.id_packing_sheet')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('rp.tanggal_terima_retur')
		->select('rp.alasan')
        ->select('do.diskon_cashback')
        ->select('do.diskon_cashback_otomatis')
		->select('do.diskon_insentif')
        ->select('do.total as total_nilai_faktur')
		->from('tr_h3_md_retur_penjualan as rp')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where('rp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->row_array();

		$qty_faktur = $this->db
		->select('SUM(plp.qty_disiapkan) as qty_disiapkan')
		->from('tr_h3_md_picking_list_parts as plp')
		->where('plp.id_picking_list = pl.id_picking_list')
		->where('plp.id_part = rpp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('rpp.id_part')
		->select('p.nama_part')
		->select('dop.harga_jual as harga_dealer_user')
		->select("IFNULL(({$qty_faktur}), 0) as qty_faktur")
		->select('dop.tipe_diskon_campaign')
        ->select('dop.diskon_campaign')
        ->select('dop.tipe_diskon_satuan_dealer')
        ->select('dop.diskon_satuan_dealer')
		->select('rpp.qty_retur')
		->select('rpp.id_lokasi_rak')
		->select('lr.kode_lokasi_rak')
		->select('rpp.alasan')
		->from('tr_h3_md_retur_penjualan_parts as rpp')
		->join('tr_h3_md_retur_penjualan as rp', 'rp.id_retur_penjualan = rpp.id_retur_penjualan')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref and dop.id_part = rpp.id_part)')
		->join('ms_part as p', 'p.id_part = rpp.id_part')
		->join('ms_h3_md_lokasi_rak as lr', 'lr.id = rpp.id_lokasi_rak', 'left')
		->where('rpp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->result_array();

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$retur_penjualan = $this->input->post(['id_dealer', 'no_faktur', 'alasan', 'total_nilai_retur', 'tanggal_terima_retur']);
		$parts = $this->getOnly(['id_part', 'qty_retur', 'alasan', 'id_lokasi_rak'], $this->input->post('parts'), $this->input->post(['id_retur_penjualan']));

		$this->db->trans_start();
		$this->retur_penjualan->update($retur_penjualan, $this->input->post(['id_retur_penjualan']));
		$this->retur_penjualan_parts->update_batch($parts, $this->input->post(['id_retur_penjualan']));
		$this->db->trans_complete();

		$retur_penjualan = (array) $this->retur_penjualan->get($this->input->post(['id_retur_penjualan']), true);

		if ($this->db->trans_status() AND $retur_penjualan != null) {
			$message = 'Retur penjualan berhasil diupdate.';

			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => $message,
				'redirect_url' => base_url(sprintf('h3/h3_md_retur_penjualan/detail?id_retur_penjualan=%s', $retur_penjualan['id_retur_penjualan']))
			]);
		}else{
			send_json([
				'message' => 'Retur penjualan tidak berhasil diupdate.',
			], 422);
		}
	}

	public function proses(){
		$this->db->trans_start();
		$this->retur_penjualan->update([
			'status' => 'Processed',
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_retur_penjualan']));

		$retur_penjualan = (array) $this->retur_penjualan->get($this->input->get(['id_retur_penjualan']), true);

		$this->db
		->set('ar.total_amount', "ar.total_amount - {$retur_penjualan['total_nilai_retur']}", false)
		->where('ar.referensi', $retur_penjualan['no_faktur'])
		->update('tr_h3_md_ar_part as ar');

		$parts = $this->db
		->select('rpp.id_part')
		->select('rpp.id_lokasi_rak')
		->select('rpp.qty_retur')
		->from('tr_h3_md_retur_penjualan_parts as rpp')
		->where('rpp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->result_array();

		foreach ($parts as $part) {
			$this->create_or_update_stock($part['id_part'], $part['id_lokasi_rak'], $part['qty_retur'], $this->input->get('id_retur_penjualan'));
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Retur penjualan berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');
			$retur_penjualan = $this->retur_penjualan->get($this->input->get(['id_retur_penjualan']), true);
			send_json($retur_penjualan);
		}else{
			$this->session->set_flashdata('pesan', 'Retur penjualan tidak berhasil diproses.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function create_or_update_stock($part, $lokasi, $qty, $referensi = ''){
		$master_part = $this->db->from('ms_part as p')
				->select('p.id_part_int')
				->where('p.id_part', $part)
				->limit(1)
				->get()->row_array();

		$transaksi_stock = [
			'id_part_int' => $master_part['id_part_int'],
			'id_part' => $part,
			'id_lokasi_rak' => $lokasi,
			'tipe_transaksi' => '+',
			'sumber_transaksi' => $this->page,
			'referensi' => $referensi,
			'stock_value' => $qty,
		];

		$this->kartu_stock->insert($transaksi_stock);

		//Cek stok didalam rak yang dituju 
		$stock = $this->db
		->from('tr_stok_part as s')
		->where('s.id_part', $part)
		->where('s.id_lokasi_rak', $lokasi)
		->limit(1)
		->get()->row();

		if($stock != null){
			$this->db->set('qty', "qty + {$qty}", FALSE)
			->where('id_part', $part)
			->where('id_lokasi_rak', $lokasi)
			->update('tr_stok_part');
		}else{
			$this->db->insert('tr_stok_part', [
				'qty' => $qty,
				'id_part' => $part,
				'id_part_int' => $master_part['id_part_int'],
				'id_lokasi_rak' => $lokasi
			]);
		}

		//Cek stok di stok summary 
		$stock_summary = $this->db
		->from('tr_stok_part_summary as s')
		->where('s.id_part', $part)
		->limit(1)
		->get()->row();

		if($stock_summary != null){
			$this->db->set('qty', "qty + {$qty}", FALSE)
			->where('id_part', $part)
			->update('tr_stok_part_summary');
		}else{
			$this->db->insert('tr_stok_part_summary', [
				'qty' => $qty,
				'id_part' => $part,
				'id_part_int' => $master_part['id_part_int']
			]);
		}

		$lokasi_rak_parts = $this->lokasi_rak_parts->get([
			'id_lokasi_rak' => $lokasi,
			'id_part' => $part
		], true);

		if($lokasi_rak_parts == null){
			$this->lokasi_rak_parts->insert([
				'id_lokasi_rak' => $lokasi,
				'id_part' => $part,
				'id_part_int' => $master_part['id_part_int'],
				'qty_maks' => 1
			]);
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->retur_penjualan->update([
			'status' => 'Canceled',
			'cancel_at' => date('Y-m-d H:i:s', time()),
			'cancel_by' => $this->session->userdata('id_user'),
			'alasan_cancel' => $this->input->get('alasan_cancel')
		], $this->input->get(['id_retur_penjualan']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Retur penjualan berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'info');
			$retur_penjualan = $this->retur_penjualan->get($this->input->get(['id_retur_penjualan']), true);
			send_json($retur_penjualan);
		}else{
			$this->session->set_flashdata('pesan', 'Retur penjualan tidak berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		$this->form_validation->set_rules('no_faktur', 'No Faktur', 'required');
		$this->form_validation->set_rules('alasan', 'Alasan', 'required');
		$this->form_validation->set_rules('tanggal_terima_retur', 'Tanggal Terima Retur', 'required');

        if (!$this->form_validation->run()){
            $errors = $this->form_validation->error_array();
			send_json([
				'message' => 'Data tidak valid',
				'errors' => $errors,
				'error_type' => 'validation_error'
			], 422);
        }
	}
	
	public function cetak_memo_pengajuan_retur()
	{
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
		$mpdf->autoLangToFont           = true;

		$data = [];
		$data['retur'] = $this->db
		->select('rp.id_retur_penjualan')
		->select('rp.status')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
        ->select('k.nama_lengkap as nama_salesman')
		->select('rp.no_faktur')
		->select('ps.id_packing_sheet')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('rp.tanggal_terima_retur')
		->select('rp.alasan')
        ->select('do.diskon_cashback')
		->select('do.diskon_insentif')
		->select('do.total as total_nilai_faktur')
		->select('so.po_type as jenis_order')
		->from('tr_h3_md_retur_penjualan as rp')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where('rp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->row_array();

		$qty_faktur = $this->db
		->select('SUM(plp.qty_disiapkan) as qty_disiapkan')
		->from('tr_h3_md_picking_list_parts as plp')
		->where('plp.id_picking_list = pl.id_picking_list')
		->where('plp.id_part = rpp.id_part')
		->get_compiled_select();

		$items = $this->db
		->select('rpp.id_part')
		->select('p.nama_part')
		->select('dop.harga_jual as harga_dealer_user')
		->select("IFNULL(({$qty_faktur}), 0) as qty_faktur")
		->select('dop.tipe_diskon_campaign')
        ->select('dop.diskon_campaign')
        ->select('dop.tipe_diskon_satuan_dealer')
        ->select('dop.diskon_satuan_dealer')
		->select('rpp.qty_retur')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('ps.no_faktur')
		->select('rpp.alasan')
		->from('tr_h3_md_retur_penjualan_parts as rpp')
		->join('tr_h3_md_retur_penjualan as rp', 'rp.id_retur_penjualan = rpp.id_retur_penjualan')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref and dop.id_part = rpp.id_part)')
		->join('ms_part as p', 'p.id_part = rpp.id_part')
		->join('ms_h3_md_lokasi_rak as lr', 'lr.id = rpp.id_lokasi_rak', 'left')
		->where('rpp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->result_array();

		$items = array_map(function($item){
			$harga_setelah_diskon = floatval($item['harga_dealer_user']) - 
			calculate_discount($item['diskon_satuan_dealer'], $item['tipe_diskon_satuan_dealer'], $item['harga_dealer_user']) - 
			calculate_discount($item['diskon_campaign'], $item['tipe_diskon_campaign'], $item['harga_dealer_user'])
			;
			$item['amount'] = $harga_setelah_diskon * intval($item['qty_retur']);
			return $item;
		}, $items);

		$data['items'] = $items;

		$html = $this->load->view('h3/h3_md_cetakan_memo_pengajuan_retur', $data, true);
		
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "Memo Pengajuan Retur.pdf";
		$mpdf->Output($output, 'I');
	}

	public function cetak_retur_penjualan()
	{
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
		$mpdf->autoLangToFont           = true;

		$data = [];
		$data['retur'] = $this->db
		->select('rp.id_retur_penjualan')
		->select('rp.status')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('d.pic as pemesan')
        ->select('k.nama_lengkap as nama_salesman')
		->select('rp.no_faktur')
		->select('ps.id_packing_sheet')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('rp.tanggal_terima_retur')
		->select('rp.alasan')
        ->select('do.diskon_cashback')
		->select('do.diskon_insentif')
		->select('do.total as total_nilai_faktur')
		->select('so.po_type as jenis_order')
		->select('rp.total_nilai_retur')
		->select('(do.total - rp.total_nilai_retur) as total_setelah_retur')
		->from('tr_h3_md_retur_penjualan as rp')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
		->where('rp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->row_array();

		$qty_faktur = $this->db
		->select('SUM(plp.qty_disiapkan) as qty_disiapkan')
		->from('tr_h3_md_picking_list_parts as plp')
		->where('plp.id_picking_list = pl.id_picking_list')
		->where('plp.id_part = rpp.id_part')
		->get_compiled_select();

		$items = $this->db
		->select('rpp.id_part')
		->select('p.nama_part')
		->select('dop.harga_jual as harga_dealer_user')
		->select("IFNULL(({$qty_faktur}), 0) as qty_faktur")
		->select('dop.tipe_diskon_campaign')
        ->select('dop.diskon_campaign')
        ->select('dop.tipe_diskon_satuan_dealer')
        ->select('dop.diskon_satuan_dealer')
		->select('rpp.qty_retur')
		->select('rpp.alasan')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('ps.no_faktur')
		->from('tr_h3_md_retur_penjualan_parts as rpp')
		->join('tr_h3_md_retur_penjualan as rp', 'rp.id_retur_penjualan = rpp.id_retur_penjualan')
		->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref and dop.id_part = rpp.id_part)')
		->join('ms_part as p', 'p.id_part = rpp.id_part')
		->where('rpp.id_retur_penjualan', $this->input->get('id_retur_penjualan'))
		->get()->result_array();

		$items = array_map(function($item){
			$harga_setelah_diskon = floatval($item['harga_dealer_user']) - 
			calculate_discount($item['diskon_satuan_dealer'], $item['tipe_diskon_satuan_dealer'], $item['harga_dealer_user']) - 
			calculate_discount($item['diskon_campaign'], $item['tipe_diskon_campaign'], $item['harga_dealer_user'])
			;
			$item['amount'] = $harga_setelah_diskon * intval($item['qty_retur']);
			return $item;
		}, $items);

		$data['items'] = $items;

		$html = $this->load->view('h3/h3_md_cetakan_retur_penjualan', $data, true);
		
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "Cetakan Retur Penjualan.pdf";
		$mpdf->Output($output, 'I');
	}
}