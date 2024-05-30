<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_mutasi_gudang extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_mutasi_gudang";
    protected $title  = "Mutasi Gudang";

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

		$this->load->model('h3_md_mutasi_gudang_model', 'mutasi_gudang');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('ms_gudang_model', 'ms_gudang');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
		$this->load->model('H3_md_lokasi_rak_parts_model', 'lokasi_rak_parts');
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

	public function get_max_qty_mutasi(){
		$data = $this->db
		->select('sp.qty')
		->from('tr_stok_part as sp')
		->where('sp.id_part', $this->input->get('id_part'))
		->where('sp.id_lokasi_rak', $this->input->get('id_lokasi_rak_awal'))
		->get()->row_array()
		;

		send_json([
			'max_qty' => $data != null ? $data['qty'] : 0
		]);
	}

	public function save(){
		$this->validate();
		
		$data = array_merge($this->input->post([
			'id_part', 'id_gudang_awal', 'id_lokasi_awal',
			'id_gudang_tujuan', 'id_lokasi_tujuan', 'qty',
		]), [
			'id_mutasi_gudang' => $this->mutasi_gudang->generateID(),
			'tanggal' => date('Y-m-d', time())
		]);

		$this->db->trans_start();
		$this->mutasi_gudang->insert($data);
		$this->proses_mutasi($data['id_mutasi_gudang']);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->mutasi_gudang->find($data['id_mutasi_gudang'], 'id_mutasi_gudang')
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['mutasi'] = $this->db
		->select('mg.id')
		->select('mg.id_mutasi_gudang')
		->select('mg.id_part')
		->select('p.nama_part')
		->select('mg.qty')
		->select('mg.id_gudang_awal')
		->select('gudang_awal.nama_gudang as nama_gudang_awal')
		->select('mg.id_lokasi_awal')
		->select('lokasi_awal.kode_lokasi_rak as kode_lokasi_rak_awal')
		->select('mg.id_gudang_tujuan')
		->select('lokasi_tujuan.kode_lokasi_rak as kode_lokasi_rak_tujuan')
		->select('gudang_tujuan.nama_gudang as nama_gudang_tujuan')
		->select('mg.id_lokasi_tujuan')
		->from('tr_h3_md_mutasi_gudang as mg')
		->join('ms_h3_md_gudang as gudang_awal', 'gudang_awal.id = mg.id_gudang_awal')
		->join('ms_h3_md_lokasi_rak as lokasi_awal', 'lokasi_awal.id = mg.id_lokasi_awal')
		->join('ms_h3_md_gudang as gudang_tujuan', 'gudang_tujuan.id = mg.id_gudang_tujuan')
		->join('ms_h3_md_lokasi_rak as lokasi_tujuan', 'lokasi_tujuan.id = mg.id_lokasi_tujuan')
		->join('ms_part as p', 'p.id_part = mg.id_part')
		->where('mg.id_mutasi_gudang', $this->input->get('id_mutasi_gudang'))
		->get()->row_array();

		$this->template($data);
	}

	public function proses_mutasi($id_mutasi_gudang){
		$data = $this->mutasi_gudang->find($id_mutasi_gudang, 'id_mutasi_gudang');

		// Potong stok dari tempat awal.
		$transaksi_stock = [
			'id_part' => $data->id_part,
			'id_lokasi_rak' => $data->id_lokasi_awal,
			'tipe_transaksi' => '-',
			'sumber_transaksi' => $this->page,
			'referensi' => $id_mutasi_gudang,
			'stock_value' => $data->qty,
		];
		$this->kartu_stock->insert($transaksi_stock);

		$this->db
		->set('qty', "qty - {$data->qty}", FALSE)
		->where('id_part', $data->id_part)
		->where('id_lokasi_rak', $data->id_lokasi_awal)
		->update('tr_stok_part');

		$this->create_or_update_stock($data->id_part, $data->id_lokasi_tujuan, $data->qty, $id_mutasi_gudang);
	}

	public function create_or_update_stock($part, $lokasi, $qty, $referensi = ''){
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

		if($stock != null){
			$this->db->set('qty', "qty + {$qty}", FALSE)
			->where('id_part', $part)
			->where('id_lokasi_rak', $lokasi)
			->update('tr_stok_part');
		}else{
			$this->db->insert('tr_stok_part', [
				'qty' => $qty,
				'id_part' => $part,
				'id_lokasi_rak' => $lokasi
			]);
		}

		$lokasi_rak_parts = $this->lokasi_rak_parts->get([
			'id_lokasi_rak' => $lokasi,
			'id_part' => $part
		], true);

		if($lokasi_rak_parts != null){
			$this->db->set('qty_maks', "qty_maks + {$qty}", FALSE)
			->where('id_part', $part)
			->where('id_lokasi_rak', $lokasi)
			->update('ms_h3_md_lokasi_rak_parts');
		}else{
			$this->lokasi_rak_parts->insert([
				'id_lokasi_rak' => $lokasi,
				'id_part' => $part,
				'qty_maks' => $qty
			]);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_part', 'Part', 'required');
		$this->form_validation->set_rules('id_gudang_awal', 'Gudang Awal', 'required');
		$this->form_validation->set_rules('id_lokasi_awal', 'Lokasi Awal', 'required');
		$this->form_validation->set_rules('id_gudang_tujuan', 'Gudang Tujuan', 'required');
		$this->form_validation->set_rules('id_lokasi_tujuan', 'Lokasi Tujuan', 'required');
		$this->form_validation->set_rules('qty', 'Kuantitas Mutasi', 'greater_than[0]');

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