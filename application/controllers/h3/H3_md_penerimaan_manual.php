<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_penerimaan_manual extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_penerimaan_manual";
    protected $title  = "Penerimaan Manual";

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

		$this->load->model('h3_md_po_vendor_model', 'po_vendor');
		$this->load->model('h3_md_po_vendor_parts_model', 'po_vendor_parts');
		$this->load->model('H3_md_penerimaan_manual_model', 'penerimaan_manual');
		$this->load->model('H3_md_penerimaan_manual_parts_model', 'penerimaan_manual_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('vendor_model', 'vendor');
		$this->load->model('H3_md_stock_model', 'stock');
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

	public function save(){
		$this->validate();
		$penerimaan_manual = array_merge($this->input->post(['id_vendor', 'id_ekspedisi', 'nama_supir', 'no_polisi', 'id_gudang', 'id_referensi', 'tanggal_referensi', 'keterangan']), [
			'id_penerimaan_manual' => $this->penerimaan_manual->generateID(),
			'tanggal_penerimaan_manual' => date('Y-m-d', time())
		]);

		$parts = $this->getOnly([
			'id_part', 'qty_terima', 
			'id_lokasi_rak', 'id_lokasi_rak_suggest', 
			'harga', 'total_harga'
		], $this->input->post('parts'), [
			'id_penerimaan_manual' => $penerimaan_manual['id_penerimaan_manual']
		]);

		$this->db->trans_start();
		$this->penerimaan_manual->insert($penerimaan_manual);
		$this->penerimaan_manual_parts->insert_batch($parts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Penerimaan Manual berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
			send_json($this->penerimaan_manual->find($penerimaan_manual['id_penerimaan_manual'], 'id_penerimaan_manual'));
		}else{
			$this->session->set_flashdata('pesan', 'Penerimaan Manual tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$data['penerimaan_manual'] = $this->db
		->select('pm.id_penerimaan_manual')
		->select('pm.nama_supir')
		->select('pm.no_polisi')
		->select('pm.id_referensi')
		->select('pm.tanggal_referensi')
		->select('pm.status')
		->select('v.id_vendor')
		->select('v.vendor_name')
		->select('e.id as id_ekspedisi')
		->select('e.nama_ekspedisi')
		->select('pm.id_gudang')
		->select('g.nama_gudang')
		->select('pm.keterangan')
		->from('tr_h3_md_penerimaan_manual as pm')
		->join('ms_vendor as v', 'v.id_vendor = pm.id_vendor', 'left')
		->join('ms_h3_md_ekspedisi as e', 'e.id = pm.id_ekspedisi', 'left')
		->join('ms_h3_md_gudang as g', 'g.id = pm.id_gudang')
		->where('pm.id_penerimaan_manual', $this->input->get('id_penerimaan_manual'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('pmp.id_part')
		->select('p.nama_part')
		->select('pmp.qty_terima')
		->select('pmp.id_lokasi_rak_suggest')
		->select('lokasi_suggest.kode_lokasi_rak as lokasi_suggest')
		->select('pmp.id_lokasi_rak')
		->select('lokasi.kode_lokasi_rak as lokasi')
		->select('pmp.harga')
		->from('tr_h3_md_penerimaan_manual_parts as pmp')
		->join('ms_part as p', 'p.id_part = pmp.id_part')
		->join('ms_h3_md_lokasi_rak as lokasi', 'lokasi.id = pmp.id_lokasi_rak', 'left')
		->join('ms_h3_md_lokasi_rak as lokasi_suggest', 'lokasi_suggest.id = pmp.id_lokasi_rak_suggest', 'left')
		->where('pmp.id_penerimaan_manual', $this->input->get('id_penerimaan_manual'))
		->get()->result_array();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";

		$data['penerimaan_manual'] = $this->db
		->select('pm.id_penerimaan_manual')
		->select('pm.nama_supir')
		->select('pm.no_polisi')
		->select('pm.id_referensi')
		->select('pm.tanggal_referensi')
		->select('pm.status')
		->select('v.id_vendor')
		->select('v.vendor_name')
		->select('e.id as id_ekspedisi')
		->select('e.nama_ekspedisi')
		->select('pm.id_gudang')
		->select('g.nama_gudang')
		->select('pm.keterangan')
		->from('tr_h3_md_penerimaan_manual as pm')
		->join('ms_vendor as v', 'v.id_vendor = pm.id_vendor', 'left')
		->join('ms_h3_md_ekspedisi as e', 'e.id = pm.id_ekspedisi', 'left')
		->join('ms_h3_md_gudang as g', 'g.id = pm.id_gudang')
		->where('pm.id_penerimaan_manual', $this->input->get('id_penerimaan_manual'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('pmp.id_part')
		->select('p.nama_part')
		->select('pmp.qty_terima')
		->select('pmp.id_lokasi_rak_suggest')
		->select('lokasi_suggest.kode_lokasi_rak as lokasi_suggest')
		->select('pmp.id_lokasi_rak')
		->select('lokasi.kode_lokasi_rak as lokasi')
		->select('pmp.harga')
		->from('tr_h3_md_penerimaan_manual_parts as pmp')
		->join('ms_part as p', 'p.id_part = pmp.id_part')
		->join('ms_h3_md_lokasi_rak as lokasi', 'lokasi.id = pmp.id_lokasi_rak', 'left')
		->join('ms_h3_md_lokasi_rak as lokasi_suggest', 'lokasi_suggest.id = pmp.id_lokasi_rak_suggest', 'left')
		->where('pmp.id_penerimaan_manual', $this->input->get('id_penerimaan_manual'))
		->get()->result_array();

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$penerimaan_manual = $this->input->post(['id_vendor', 'id_ekspedisi', 'nama_supir', 'no_polisi', 'id_gudang', 'id_referensi', 'tanggal_referensi', 'keterangan']);

		$parts = $this->getOnly([
			'id_part', 'qty_terima', 
			'id_lokasi_rak', 'id_lokasi_rak_suggest', 
			'harga', 'total_harga'
		], $this->input->post('parts'), $this->input->post(['id_penerimaan_manual']));

		$this->db->trans_start();
		$this->penerimaan_manual->update($penerimaan_manual, $this->input->post(['id_penerimaan_manual']));
		$this->penerimaan_manual_parts->update_batch($parts, $this->input->post(['id_penerimaan_manual']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Penerimaan Manual berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			send_json(
				$this->penerimaan_manual->find($this->input->post('id_penerimaan_manual'), 'id_penerimaan_manual')
			);
		}else{
			$this->session->set_flashdata('pesan', 'Penerimaan Manual tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function proses(){
		$this->db->trans_start();
		$this->penerimaan_manual->update([
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user'),
			'status' => 'Closed'
		], $this->input->get(['id_penerimaan_manual']));

		$parts = $this->penerimaan_manual_parts->get($this->input->get(['id_penerimaan_manual']));
		foreach ($parts as $part) {
			$this->create_or_update_stock($part->id_part, $part->id_lokasi_rak, $part->qty_terima, $this->input->get('id_penerimaan_manual'));
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Penerimaan Manual berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			send_json(
				$this->penerimaan_manual->find($this->input->get('id_penerimaan_manual'), 'id_penerimaan_manual')
			);
		}else{
			$this->session->set_flashdata('pesan', 'Penerimaan Manual tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function cetak(){
		$this->load->helper('terbilang');

		$data['penerimaan_manual'] = $this->db
		->select('pm.id_penerimaan_manual')
		->select('pm.nama_supir')
		->select('pm.no_polisi')
		->select('pm.id_referensi')
		->select('pm.tanggal_referensi')
		->select('pm.tanggal_penerimaan_manual')
		->select('pm.status')
		->select('v.id_vendor')
		->select('v.vendor_name')
		->select('e.id as id_ekspedisi')
		->select('e.nama_ekspedisi')
		->select('pm.id_gudang')
		->select('g.nama_gudang')
		->select('pm.keterangan')
		->from('tr_h3_md_penerimaan_manual as pm')
		->join('ms_vendor as v', 'v.id_vendor = pm.id_vendor', 'left')
		->join('ms_h3_md_ekspedisi as e', 'e.id = pm.id_ekspedisi', 'left')
		->join('ms_h3_md_gudang as g', 'g.id = pm.id_gudang')
		->where('pm.id_penerimaan_manual', $this->input->get('id_penerimaan_manual'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('pmp.id_part')
		->select('p.nama_part')
		->select('pmp.qty_terima')
		->select('pmp.harga')
		->select('format(pmp.harga, 0, "ID_id") as harga_formatted', false)
		->select('(pmp.harga * pmp.qty_terima) as total_amount')
		->select('format( (pmp.harga * pmp.qty_terima) , 0, "ID_id") as total_amount_formatted', false)
		->select('lokasi.kode_lokasi_rak as lokasi')
		->from('tr_h3_md_penerimaan_manual_parts as pmp')
		->join('ms_part as p', 'p.id_part = pmp.id_part')
		->join('ms_h3_md_lokasi_rak as lokasi', 'lokasi.id = pmp.id_lokasi_rak', 'left')
		->join('ms_h3_md_lokasi_rak as lokasi_suggest', 'lokasi_suggest.id = pmp.id_lokasi_rak_suggest', 'left')
		->where('pmp.id_penerimaan_manual', $this->input->get('id_penerimaan_manual'))
		->get()->result_array();


        // $this->load->library('mpdf_l');
        require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_cetak_penerimaan_manual', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("{$data['penerimaan_manual']['id_penerimaan_manual']}.pdf", "I");
	}

	public function create_or_update_stock($part, $lokasi, $qty, $referensi = ''){
		$this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
		$this->load->model('H3_md_lokasi_rak_parts_model', 'lokasi_rak_parts');

		$part = $this->db
			->select('id_part_int')
			->select('id_part')
			->from('ms_part')
			->where('id_part', $part)
			->limit(1)
			->get()->row_array();

		$transaksi_stock = [
			'id_part' => $part['id_part'],
			'id_part_int' => $part['id_part_int'],
			'id_lokasi_rak' => $lokasi,
			'tipe_transaksi' => '+',
			'sumber_transaksi' => $this->page,
			'referensi' => $referensi,
			'stock_value' => $qty,
		];

		$this->kartu_stock->insert($transaksi_stock);

		//Cek Stok sesuai dengan kode lokasi
		$stock = $this->db
		->from('tr_stok_part as s')
		->where('s.id_part_int', $part['id_part_int'])
		->where('s.id_lokasi_rak', $lokasi)
		->limit(1)
		->get()->row();

		if($stock != null){
			$this->db->set('qty', "qty + {$qty}", FALSE)
			->where('id_part_int', $part['id_part_int'])
			->where('id_lokasi_rak', $lokasi)
			->update('tr_stok_part');
		}else{
			$this->db->insert('tr_stok_part', [
				'qty' => $qty,
				'id_part' => $part['id_part'],
				'id_part_int' => $part['id_part_int'],
				'id_lokasi_rak' => $lokasi
			]);
		}

		//Cek Stok di Summary 
		$stock_summary = $this->db
		->from('tr_stok_part_summary as s')
		->where('s.id_part_int', $part['id_part_int'])
		->limit(1)
		->get()->row();

		if($stock_summary != null){
			$this->db->set('qty', "qty + {$qty}", FALSE)
			->where('id_part_int', $part['id_part_int'])
			->update('tr_stok_part_summary');
		}else{
			$this->db->insert('tr_stok_part_summary', [
				'qty' => $qty,
				'id_part' => $part['id_part'],
				'id_part_int' => $part['id_part_int']
			]);
		}

		$lokasi_rak_parts = $this->lokasi_rak_parts->get([
			'id_lokasi_rak' => $lokasi,
			'id_part' => $part['id_part']
		], true);

		if($lokasi_rak_parts != null){
			$this->db->set('qty_maks', "qty_maks + {$qty}", FALSE)
			->where('id_part', $part['id_part'])
			->where('id_lokasi_rak', $lokasi)
			->update('ms_h3_md_lokasi_rak_parts');
		}else{
			$this->lokasi_rak_parts->insert([
				'id_lokasi_rak' => $lokasi,
				'id_part' => $part['id_part'],
				'qty_maks' => $qty
			]);
		}
	}

	public function get_suggest_lokasi(){
		$result = [];
		foreach ($this->input->post('parts') as $part) {
			$kapasitas_lokasi_terpakai = $this->db
			->select('sum(sp.qty) as qty')
			->from('tr_stok_part as sp')
			->where('sp.id_lokasi_rak = lr.id')
			->get_compiled_select();

			$lokasi_suggest = $this->db
			->select('lr.id')
			->select('lr.kode_lokasi_rak')
			->select("lr.kapasitas - ({$kapasitas_lokasi_terpakai}) as kapasitas_tersedia")
			->from('ms_h3_md_lokasi_rak_parts as lrp')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = lrp.id_lokasi_rak')
			->where('lr.id_gudang', $this->input->post('id_gudang'))
			->where('lrp.id_part', $part['id_part'])
			->where("( lr.kapasitas - ({$kapasitas_lokasi_terpakai}) ) > {$part['qty_terima']}")
			->order_by('kapasitas_tersedia', 'desc')
			->having('kapasitas_tersedia > 0')
			->get()->row_array()
			;

			$row = [];
			if ($lokasi_suggest != null) {
				$row['id_part'] = $part['id_part'];
				$row['id_lokasi_suggest'] = $lokasi_suggest['id'];
				$row['lokasi_suggest'] = $lokasi_suggest['kode_lokasi_rak'];
				$result[] = $row;
			}
		}

		send_json($result);
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_vendor', 'Vendor', 'required');
		// $this->form_validation->set_rules('id_ekspedisi', 'Ekspedisi', 'required');
		// $this->form_validation->set_rules('nama_supir', 'Nama Supir', 'required');
		// $this->form_validation->set_rules('no_polisi', 'Nomor Polisi', 'required');
		$this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
		$this->form_validation->set_rules('id_referensi', 'Referensi', 'required');
		$this->form_validation->set_rules('tanggal_referensi', 'Tanggal Referensi', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }
}