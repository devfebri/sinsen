<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_penerimaan_po_vendor extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_penerimaan_po_vendor";
    protected $title  = "Penerimaan PO Vendor";

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
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_penerimaan_po_vendor_model', 'penerimaan_po_vendor');
		$this->load->model('h3_md_penerimaan_po_vendor_parts_model', 'penerimaan_po_vendor_parts');
		$this->load->model('h3_md_po_vendor_model', 'po_vendor');
		$this->load->model('h3_md_po_vendor_parts_model', 'po_vendor_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('vendor_model', 'vendor');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_lokasi_rak_model', 'lokasi_rak');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['penerimaan_po_vendor'] = $this->penerimaan_po_vendor->all();
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function get_po_vendor_parts(){
		$qty_diterima = $this->db
		->select('sum(ppvp_sq.qty_diterima)')
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp_sq')
		->join('tr_h3_md_penerimaan_po_vendor as ppv_sq', 'ppv_sq.id_penerimaan_po_vendor = ppvp_sq.id_penerimaan_po_vendor')
		->where('ppv_sq.id_po_vendor = pvp.id_po_vendor')
		->where('ppvp_sq.id_part = pvp.id_part')
		->where('ppv_sq.status', 'Processed')
		->get_compiled_select();

		$data = $this->db
		->select('pvp.id_part')
		->select('mp.nama_part')
		->select('pvp.qty_on_hand')
		->select('pvp.qty_avg_sales')
		->select('pvp.qty_order')
		->select("ifnull(({$qty_diterima}), 0) as qty_telah_diterima", false)
		->select("pvp.qty_order - ifnull(({$qty_diterima}), 0) as sisa_penerimaan", false)
		->select('0 as qty_diterima')
		->select('0 as qty_lebih')
		->select('0 as qty_kurang')
		->select('"" as keterangan')
		->from('tr_h3_md_po_vendor_parts as pvp')
		->where('pvp.id_po_vendor', $this->input->get('id_po_vendor'))
		->join('ms_part as mp', 'mp.id_part = pvp.id_part')
		->get()->result_array();

		$data = array_map(function($part){
			$part['qty_avg_sales_order'] = $this->stock->qty_avs($part['id_part']);

			$lokasi = $this->lokasi_rak->suggest_lokasi($part['id_part'], $part['sisa_penerimaan']);

			if($lokasi != null){
				$part['id_lokasi_rak'] = $lokasi['id_lokasi_rak'];
				$part['kode_lokasi_rak'] = $lokasi['kode_lokasi_rak'];
				$part['kapasitas_tersedia'] = intval($lokasi['kapasitas_tersedia']);
				$part['setting_per_part'] = $lokasi['setting_per_part'] == 1;
			}else{
				$part['id_lokasi_rak'] = null;
				$part['kode_lokasi_rak'] = null;
				$part['kapasitas_tersedia'] = 0;
				$part['setting_per_part'] = 0;
			}
			
			return $part;
		}, $data);

		send_json($data);
	}

	public function save(){
		$this->validate();
		$penerimaan_po_vendor = array_merge($this->input->post([
			'surat_jalan_ekspedisi', 'tgl_surat_jalan_ekspedisi', 'no_plat', 'nama_driver', 'id_po_vendor', 'id_ekspedisi',
			'type_mobil','harga_ongkos_angkut_part','jenis_ongkos_angkut_part','per_satuan_ongkos_angkut_part','berat_truk',
			'total_harga_angkut'
		]), [
			'id_penerimaan_po_vendor' => $this->penerimaan_po_vendor->generateID(),
			'tanggal' => date('Y-m-d', time())
		]);

		$parts = $this->getOnly([
			'id_part', 'qty_order', 'qty_on_hand', 
			'qty_avg_sales', 'qty_diterima', 'qty_telah_diterima',
			'qty_lebih', 'qty_kurang', 'keterangan', 'id_lokasi_rak'
		], $this->input->post('parts'), [
			'id_penerimaan_po_vendor' => $penerimaan_po_vendor['id_penerimaan_po_vendor']
		]);

		$this->db->trans_start();
		$this->penerimaan_po_vendor->insert($penerimaan_po_vendor);
		$this->penerimaan_po_vendor_parts->insert_batch($parts);
		$this->db->trans_complete();

		$penerimaan_po_vendor = (array) $this->penerimaan_po_vendor->find($penerimaan_po_vendor['id_penerimaan_po_vendor'], 'id_penerimaan_po_vendor');
		if ($this->db->trans_status() AND $penerimaan_po_vendor != null) {
			$this->session->set_flashdata('pesan', 'Penerimaan PO Vendor berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_penerimaan_po_vendor=%s', $this->page, $penerimaan_po_vendor['id_penerimaan_po_vendor'])),
				'payload' => $penerimaan_po_vendor,
			]);
		}else{
			$message = 'Penerimaan PO Vendor tidak berhasil dibuat.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => $message
			], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['penerimaan_po_vendor'] = $this->db
		->select('ppv.id_penerimaan_po_vendor')
		->select('ppv.surat_jalan_ekspedisi')
		->select('ppv.tgl_surat_jalan_ekspedisi')
		->select('ppv.id_po_vendor')
		->select('ppv.no_plat')
		->select('ppv.nama_driver')
		->select('ppv.tanggal')
		->select('ekspedisi.id as id_ekspedisi')
		->select('ekspedisi.nama_ekspedisi')
		->select('ppv.type_mobil')
		->select('ppv.harga_ongkos_angkut_part')
		->select('ppv.jenis_ongkos_angkut_part')
		->select('ppv.per_satuan_ongkos_angkut_part')
		->select('ppv.berat_truk')
		->select('ppv.status')
		->from('tr_h3_md_penerimaan_po_vendor as ppv')
		->join('ms_h3_md_ekspedisi as ekspedisi', 'ekspedisi.id = ppv.id_ekspedisi')
		->where('ppv.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->row();

		$parts = $this->db
		->select('ppvp.*')
		->select('p.nama_part')
		->select('lokasi.kode_lokasi_rak')
        ->select("(lokasi.kapasitas - lokasi.kapasitas_terpakai) as kapasitas_tersedia")
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp')
		->join('ms_part as p', 'p.id_part = ppvp.id_part')
		->join('ms_h3_md_lokasi_rak as lokasi', 'lokasi.id = ppvp.id_lokasi_rak', 'left')
		->where('ppvp.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->result_array();

		$parts = array_map(function($part){
			$lokasi = $this->lokasi_rak->suggest_lokasi($part['id_part'], $part['qty_order'], false, $part['id_lokasi_rak']);
			if($lokasi != null){
				$part['kapasitas_tersedia'] = intval($lokasi['kapasitas_tersedia']);
				$part['setting_per_part'] = $lokasi['setting_per_part'] == 1;
			}
			return $part;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['penerimaan_po_vendor'] = $this->db
		->select('ppv.id_penerimaan_po_vendor')
		->select('ppv.surat_jalan_ekspedisi')
		->select('ppv.tgl_surat_jalan_ekspedisi')
		->select('ppv.id_po_vendor')
		->select('ppv.no_plat')
		->select('ppv.nama_driver')
		->select('ppv.tanggal')
		->select('ekspedisi.id as id_ekspedisi')
		->select('ekspedisi.nama_ekspedisi')
		->select('ppv.type_mobil')
		->select('ppv.harga_ongkos_angkut_part')
		->select('ppv.jenis_ongkos_angkut_part')
		->select('ppv.per_satuan_ongkos_angkut_part')
		->select('ppv.berat_truk')
		->select('ppv.status')
		->from('tr_h3_md_penerimaan_po_vendor as ppv')
		->join('ms_h3_md_ekspedisi as ekspedisi', 'ekspedisi.id = ppv.id_ekspedisi')
		->where('ppv.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->row();

		$parts = $this->db
		->select('ppvp.*')
		->select('p.nama_part')
		->select('lokasi.kode_lokasi_rak')
        ->select("(lokasi.kapasitas - lokasi.kapasitas_terpakai) as kapasitas_tersedia")
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp')
		->join('ms_part as p', 'p.id_part = ppvp.id_part')
		->join('ms_h3_md_lokasi_rak as lokasi', 'lokasi.id = ppvp.id_lokasi_rak', 'left')
		->where('ppvp.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->result_array();

		$parts = array_map(function($part){
			$lokasi = $this->lokasi_rak->suggest_lokasi($part['id_part'], $part['qty_order'], false, $part['id_lokasi_rak']);
			if($lokasi != null){
				$part['kapasitas_tersedia'] = intval($lokasi['kapasitas_tersedia']);
				$part['setting_per_part'] = $lokasi['setting_per_part'] == 1;
			}
			return $part;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$penerimaan_po_vendor = $this->input->post([
			'surat_jalan_ekspedisi', 'tgl_surat_jalan_ekspedisi', 'no_plat', 'nama_driver', 'id_po_vendor', 'id_ekspedisi',
			'type_mobil','harga_ongkos_angkut_part','jenis_ongkos_angkut_part','per_satuan_ongkos_angkut_part','berat_truk',
			'total_harga_angkut'
		]);

		$parts = $this->getOnly([
			'id_part', 'qty_order', 'qty_on_hand', 
			'qty_avg_sales', 'qty_diterima', 
			'qty_lebih', 'qty_kurang', 'keterangan', 'id_lokasi_rak'
		], $this->input->post('parts'), $this->input->post(['id_penerimaan_po_vendor']));

		$this->db->trans_start();
		$this->penerimaan_po_vendor->update($penerimaan_po_vendor, $this->input->post(['id_penerimaan_po_vendor']));
		$this->penerimaan_po_vendor_parts->update_batch($parts, $this->input->post(['id_penerimaan_po_vendor']));
		$this->db->trans_complete();

		$penerimaan_po_vendor = (array) $this->penerimaan_po_vendor->get($this->input->post(['id_penerimaan_po_vendor']), true);
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Penerimaan PO Vendor berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_penerimaan_po_vendor=%s', $this->page, $penerimaan_po_vendor['id_penerimaan_po_vendor'])),
				'payload' => $penerimaan_po_vendor,
			]);
		}else{
			$message = 'Penerimaan PO Vendor tidak berhasil diupdate.';

			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => $message
			], 422);
		}
	}

	public function proses(){
		$this->db->trans_start();
		$this->penerimaan_po_vendor->update([
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user'),
			'status' => 'Processed',
		], $this->input->get(['id_penerimaan_po_vendor']));

		$parts = $this->db
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp')
		->where('ppvp.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->where('ppvp.qty_diterima > 0')
		->where('ppvp.id_lokasi_rak !=', null)
		->get()->result_array();
		foreach ($parts as $part) {
			$this->create_or_update_stock($part['id_part'], $part['id_lokasi_rak'], $part['qty_diterima'], $this->input->get('id_penerimaan_po_vendor'));
		}

		$penerimaan_po_vendor = $this->penerimaan_po_vendor->get($this->input->get(['id_penerimaan_po_vendor']));

		$part_penerimaan_po_vendor = $this->db
		->select('IFNULL(SUM(ppvp.qty_diterima), 0) as qty_diterima')
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp')
		->join('tr_h3_md_penerimaan_po_vendor as ppv', 'ppv.id_penerimaan_po_vendor = ppvp.id_penerimaan_po_vendor')
		->where('ppv.id_po_vendor', $this->input->get('id_po_vendor'))
		->where('ppv.status', 'Processed')
		->get()->row_array()['qty_diterima'];

		$part_po_vendor = $this->db
		->select('IFNULL(SUM(pvp.qty_order), 0) as qty_order')
		->from('tr_h3_md_po_vendor_parts as pvp')
		->where('pvp.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->row_array()['qty_order'];

		if($part_penerimaan_po_vendor == $part_po_vendor){
			$this->po_vendor->update([
				'status' => 'Closed',
				'closed_at' => date('Y-m-d H:i:s', time()),
				'closed_by' => $this->session->userdata('id_user')
			], $this->input->get(['id_po_vendor']));
		}
		$this->db->trans_complete();

		$penerimaan_po_vendor = (array) $this->penerimaan_po_vendor->get($this->input->get(['id_penerimaan_po_vendor']), true);
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Penerimaan PO vendor berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');
			send_json([
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_penerimaan_po_vendor=%s', $this->page, $penerimaan_po_vendor['id_penerimaan_po_vendor'])),
			]);
		}else{
			$message = 'Penerimaan PO vendor tidak berhasil diproses.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => $message,
			], 422);
		}
	}

	public function create_or_update_stock($part, $lokasi, $qty, $referensi = ''){
		$this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
		$this->load->model('H3_md_lokasi_rak_parts_model', 'lokasi_rak_parts');

		$transaksi_stock = [
			'id_part' => $part,
			'id_lokasi_rak' => $lokasi,
			'tipe_transaksi' => '+',
			'sumber_transaksi' => $this->page,
			'referensi' => $referensi,
			'stock_value' => $qty,
		];

		$this->kartu_stock->insert($transaksi_stock);

		$stock = $this->db
		->from('tr_stok_part as s')
		->where('s.id_part', $part)
		->where('s.id_lokasi_rak', $lokasi)
		->limit(1)
		->get()->row();

		$this->stock->add_stock($part, $lokasi, $qty);

		$lokasi_rak_parts = $this->lokasi_rak_parts->get([
			'id_lokasi_rak' => $lokasi,
			'id_part' => $part
		], true);

		if($lokasi_rak_parts == null){
			$this->lokasi_rak_parts->insert([
				'id_lokasi_rak' => $lokasi,
				'id_part' => $part,
				'qty_maks' => 1
			]);
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->penerimaan_po_vendor->update([
			'cancel_at' => date('Y-m-d H:i:s', time()),
			'cancel_by' => $this->session->userdata('id_user'),
			'status' => 'Canceled',
			'alasan_cancel' => $this->input->get('alasan_cancel'),
		], $this->input->get(['id_penerimaan_po_vendor']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Penerimaan PO vendor berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'info');
			send_json(
				$this->penerimaan_po_vendor->get($this->input->get(['id_penerimaan_po_vendor']), true)
			);
		}else{
			$this->session->set_flashdata('pesan', 'Penerimaan PO vendor tidak berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function harga_ekspedisi(){
		$query = $this->db
		->from('ms_h3_md_ongkos_angkut_part as o')
		->where('o.id_vendor', $this->input->get('id_ekspedisi'))
		->where('o.type_mobil', $this->input->get('type_mobil'))
		->where('o.start_date <=', date('Y-m-d'))
		->order_by('o.start_date', 'desc')
		->limit(1)
		->get()->row()
		;

		$data = [];
		if($query != null){
			$data['jenis_ongkos_angkut_part'] = $query->jenis;
			$data['per_satuan_ongkos_angkut_part'] = $query->per_satuan;
			$data['harga_ongkos_angkut_part'] = $query->harga;
		}else{
			$data['jenis_ongkos_angkut_part'] = '';
			$data['per_satuan_ongkos_angkut_part'] = 0;
			$data['harga_ongkos_angkut_part'] = 0;
		}

		send_json($data);
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		
		if($this->uri->segment(3) == 'save'){
			$this->form_validation->set_rules('surat_jalan_ekspedisi', 'No Surat Jalan Ekspedisi', 'required|is_unique[tr_h3_md_penerimaan_po_vendor.surat_jalan_ekspedisi]', [
				'is_unique' => '%s sudah pernah diterima.'
			]);
		}

		$this->form_validation->set_rules('tgl_surat_jalan_ekspedisi', 'Tgl Surat Jalan Ekspedisi', 'required');
		$this->form_validation->set_rules('no_plat', 'No Plat', 'required');
		$this->form_validation->set_rules('nama_driver', 'Nama Driver', 'required');
		$this->form_validation->set_rules('id_po_vendor', 'PO Vendor', 'required');
		$this->form_validation->set_rules('id_ekspedisi', 'Ekspedisi', 'required');

        if (!$this->form_validation->run()){
            send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
	}

	public function cetak(){
		$this->load->helper('terbilang');

		$data['penerimaan_po_vendor'] = $this->db
		->select('pov.id_penerimaan_po_vendor')
		->select('date_format(pov.tanggal, "%d/%m/%Y") as tanggal_penerimaan')
		->select('po.id_po_vendor')
		->select('date_format(po.tanggal, "%d/%m/%Y") as tanggal_po_vendor')
		->from('tr_h3_md_penerimaan_po_vendor as pov')
		->join('tr_h3_md_po_vendor as po', 'po.id_po_vendor = pov.id_po_vendor')
		->where('pov.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('povp.id_part')
		->select('p.nama_part')
		->select('povp.qty_diterima')
		->select('lr.kode_lokasi_rak')
		->select('vendor_parts.harga')
		->select('(vendor_parts.harga * povp.qty_diterima) as total_amount')
		->from('tr_h3_md_penerimaan_po_vendor_parts as povp')
		->join('tr_h3_md_penerimaan_po_vendor as pov', 'pov.id_penerimaan_po_vendor = povp.id_penerimaan_po_vendor')
		->join('tr_h3_md_po_vendor_parts as vendor_parts', '(vendor_parts.id_part = povp.id_part and vendor_parts.id_po_vendor = pov.id_po_vendor)')
		->join('ms_part as p', 'p.id_part = povp.id_part')
		->join('ms_h3_md_lokasi_rak as lr', 'lr.id = povp.id_lokasi_rak')
		->where('povp.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->result_array();

        // $this->load->library('mpdf_l');
        require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_cetak_penerimaan_part_vendor', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("Penerimaan PO Vendor.pdf", "I");
	}

	public function generate_invoice_ekspedisi(){
		$this->db
		->select('ppv.id_penerimaan_po_vendor')
		->from('tr_h3_md_penerimaan_po_vendor as ppv')
		->join('tr_h3_md_invoice_ekspedisi as ie', '(ie.referensi = ppv.id_penerimaan_po_vendor and ie.tipe_referensi = "penerimaan_po_vendor")', 'left')
		->where('ppv.status', 'Processed')
		->where('ie.id', null)
		;

		foreach ($this->db->get()->result_array() as $row) {
			$this->penerimaan_po_vendor->create_invoice_ekspedisi($row['id_penerimaan_po_vendor']);
		}
	}
}