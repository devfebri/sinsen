<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_pelunasan_bapb extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_pelunasan_bapb";
    protected $title  = "Pelunasan BAPB";

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
		$this->load->model('H3_md_pelunasan_bapb_model', 'pelunasan_bapb');		
		$this->load->model('H3_md_pelunasan_bapb_items_model', 'pelunasan_bapb_items');
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

	public function parts_bapb(){
		$this->db
		->select('bai.surat_jalan_ahm')
		->select('bai.packing_sheet_number')
		->select('bai.nomor_karton')
		->select('bai.no_po')
		->select('bai.id_part')
		->select('p.nama_part')
		->select('bai.qty_rusak')
		->select('bai.id_lokasi_rak')
		->select('"" as tipe_ganti')
		->select('0 as proses_pembayaran')
		->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
		->join('ms_part as p', 'p.id_part = bai.id_part')
		->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = bai.no_bapb')
		->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi')
		->where('bai.no_bapb', $this->input->get('no_bapb'))
		->having('qty_rusak > 0')
		;

		send_json($this->db->get()->result());
	}

	public function save(){
		$this->validate();
		$pelunasan_bapb = $this->input->post([
			'no_bapb', 'tanggal_pelunasan', 'keterangan'
		]);
		$pelunasan_bapb = array_merge($pelunasan_bapb, [
			'no_pelunasan' => $this->pelunasan_bapb->generateID()
		]);
		$items = $this->getOnly(true, $this->input->post('parts'), [
			'no_pelunasan' => $this->pelunasan_bapb->generateID()
		]);

		$this->db->trans_start();
		$this->pelunasan_bapb->insert($pelunasan_bapb);
		$this->pelunasan_bapb_items->insert_batch($items);
		$this->db->trans_complete();

		$pelunasan_bapb = (array) $this->pelunasan_bapb->find($pelunasan_bapb['no_pelunasan'], 'no_pelunasan');
		if ($this->db->trans_status() AND $pelunasan_bapb != null) {
			send_json([
				'message' => 'Berhasil membuat pelunasan BAPB',
				'payload' => $pelunasan_bapb,
				'redirect_url' => base_url(sprintf('h3/h3_md_pelunasan_bapb/detail?no_pelunasan=%s', $pelunasan_bapb['no_pelunasan']))
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil membuat pelunasan BAPB'
			], 422);
		}
	}

	public function update_stock($part){
		$stock = $this->db
		->from('tr_stok_part as s')
		->where('s.id_part', $part['id_part'])
		->where('s.id_lokasi_rak', $part['id_lokasi_rak'])
		->limit(1)
		->get()->row();

		if($stock != null){
			$this->db->set('qty', "qty + {$part['qty_rusak']}", FALSE)
			->where('id_part', $part['id_part'])
			->where('id_lokasi_rak', $part['id_lokasi_rak'])
			->update('tr_stok_part');
		}else{
			$this->db->insert('tr_stok_part', [
				'qty' => $part['qty_rusak'],
				'id_part' => $part['id_part'],
				'id_lokasi_rak' => $part['id_lokasi_rak']
			]);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('tanggal_pelunasan', 'Tanggal Pelunasan', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('no_bapb', 'Nomor BAPB', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['pelunasan_bapb'] = $this->db
		->select('pl.*')
		->select('ba.no_surat_jalan_ekspedisi')
		->from('tr_h3_md_pelunasan_bapb as pl')
		->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = pl.no_bapb')
		->where('pl.no_pelunasan', $this->input->get('no_pelunasan'))
		->limit(1)
		->get()->row();

		$data['parts'] = $this->db
		->select('pli.surat_jalan_ahm')
		->select('pli.packing_sheet_number')
		->select('pli.nomor_karton')
		->select('pli.id_part')
		->select('pli.no_po')
		->select('p.nama_part')
		->select('pli.qty_rusak')
		->select('pli.tipe_ganti')
		->select('pli.proses_pembayaran')
		->from('tr_h3_md_pelunasan_bapb_items as pli')
		->join('ms_part as p', 'p.id_part = pli.id_part')
		->join('tr_h3_md_pelunasan_bapb as pl', 'pl.no_pelunasan = pli.no_pelunasan')
		->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = pl.no_bapb')
		->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi')
		->where('pli.no_pelunasan', $this->input->get('no_pelunasan'))
		->get()->result();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['berita_acara'] = $this->db
		->select('ba.*')
		->select('v.vendor_name')
		->from('tr_h3_md_berita_acara_penerimaan_barang as ba')
		->join('ms_vendor as v', 'v.id_vendor = ba.id_vendor')
		->where('ba.no_bapb', $this->input->get('no_bapb'))
		->limit(1)
		->get()->row();

		$data['parts'] = $this->db
		->select('pb.surat_jalan_ahm')
		->select('pb.packing_sheet_number')
		->select('bai.nomor_karton')
		->select('bai.id_part')
		->select('p.nama_part')
		->select('psp.packing_sheet_quantity')
		->select('bai.qty_diterima')
		->select('bai.qty_rusak')
		->select('bai.keterangan_bapb')
		->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
		->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = bai.no_bapb')
		->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi')
		->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = pb.packing_sheet_number')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = bai.id_part and psp.no_doos = bai.nomor_karton and psp.packing_sheet_number = pb.packing_sheet_number)')
		->join('ms_part as p', 'p.id_part = bai.id_part')
		->where('bai.no_bapb', $this->input->get('no_bapb'))
		->get()->result();

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$berita_acara = $this->input->post([
			'no_surat_jalan_ekspedisi', 'nama_driver', 'no_plat', 'id_vendor', 'tanggal_serah_terima'
		]);
		$items = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['no_bapb']));
		$this->db->trans_start();
		$this->berita_acara->update($berita_acara, $this->input->post(['no_bapb']));
		$this->berita_acara_items->update_batch($items, $this->input->post(['no_bapb']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($this->berita_acara->get($this->input->post(['no_bapb']), true));
		}else{
			$this->output->set_status_header(400);
		}
	}
}