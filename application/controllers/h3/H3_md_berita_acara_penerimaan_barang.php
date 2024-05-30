<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_berita_acara_penerimaan_barang extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_berita_acara_penerimaan_barang";
    protected $title  = "Berita Acara Penerimaan Barang";

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
		$this->load->model('H3_md_berita_acara_penerimaan_barang_model', 'berita_acara');		
		$this->load->model('H3_md_berita_acara_penerimaan_barang_items_model', 'berita_acara_items');
		$this->load->model('H3_md_penerimaan_barang_items_model', 'penerimaan_barang_items');
		$this->load->model('H3_md_penerimaan_barang_reasons_model', 'penerimaan_barang_reasons');
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

	public function parts_laporan_penerimaan_barang(){
		$this->db
		->select('pbi.surat_jalan_ahm')
		->select('pbi.packing_sheet_number')
		->select('pbi.nomor_karton')
		->select('psp.id_part')
		->select('p.nama_part')
		->select('psp.packing_sheet_quantity')
		->select('psp.packing_sheet_quantity as qty_diterima')
		->select('pbi.id_lokasi_rak')
		->select('0 as qty_rusak')
		->select('"" as keterangan_bapb')
		->from('tr_h3_md_penerimaan_barang as pb')
		->join('tr_h3_md_penerimaan_barang_items as pbi', 'pbi.no_penerimaan_barang = pb.no_penerimaan_barang')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = pbi.id_part and psp.no_doos = pbi.nomor_karton and psp.packing_sheet_number = pbi.packing_sheet_number)')
		->join('ms_part as p', 'p.id_part = pbi.id_part')
		->where('pb.no_surat_jalan_ekspedisi', $this->input->get('no_surat_jalan_ekspedisi'))
		// ->having('qty_rusak > 0')
		;

		send_json($this->db->get()->result());
	}

	public function save(){
		$this->validate();
		$berita_acara = $this->input->post([
			'no_surat_jalan_ekspedisi', 'nama_driver', 'no_plat', 'id_vendor', 'tanggal_serah_terima'
		]);
		$berita_acara = array_merge($berita_acara, [
			'no_bapb' => $this->berita_acara->generateID()
		]);
		$items = $this->getOnly(true, $this->input->post('parts'), [
			'no_bapb' => $this->berita_acara->generateID()
		]);
		$this->db->trans_start();
		$this->berita_acara->insert($berita_acara);
		$this->berita_acara_items->insert_batch($items);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($this->berita_acara->find($berita_acara['no_bapb'], 'no_bapb'));
		}else{
			send_json([
				'message' => 'BA penerimaan barang tidak berhasil dibuat',
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('tanggal_serah_terima', 'Tanggal Serah Terima', 'required');
        $this->form_validation->set_rules('id_vendor', 'Ekspedisi', 'required');
        $this->form_validation->set_rules('nama_driver', 'Nama Driver', 'required');
        $this->form_validation->set_rules('no_plat', 'Nomor Plat', 'required');
        $this->form_validation->set_rules('no_surat_jalan_ekspedisi', 'Nomor Surat Jalan Ekspedisi', 'required');

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
		$data['berita_acara'] = $this->db
		->select('ba.*')
		->select('e.nama_ekspedisi as vendor_name')
		->select('e.id_dealer')
		->select('so.id_sales_order')
		->from('tr_h3_md_berita_acara_penerimaan_barang as ba')
		->join('ms_h3_md_ekspedisi as e', 'e.id = ba.id_vendor')
		->join('tr_h3_md_sales_order as so', '(so.no_bapb = ba.no_bapb and so.status != "Canceled")', 'left')
		->where('ba.no_bapb', $this->input->get('no_bapb'))
		->limit(1)
		->get()->row();

		$qty_claim_ahm = $this->db
		->select('SUM(pbr.qty) as qty')
		->from('tr_h3_md_penerimaan_barang_reasons as pbr')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = pbr.id_claim')
		->where('pbr.id_penerimaan_barang_item = pbi.id')
		->where('kc.tipe_claim !=', 'Claim Ekspedisi')
		->get_compiled_select();

		$this->db->start_cache();
		$this->db
		->select('SUM(pbi.qty_rusak)')
		->from('tr_h3_md_pelunasan_bapb as pb')
		->join('tr_h3_md_pelunasan_bapb_items as pbi', 'pbi.no_pelunasan = pb.no_pelunasan')
		->where('pb.no_bapb = ba.no_bapb', null, false)
		->where('pbi.id_part = bai.id_part', null, false)
		->where('pbi.nomor_karton = bai.nomor_karton', null, false)
		->where('pbi.packing_sheet_number = bai.packing_sheet_number', null, false)
		->where('pbi.no_po = bai.no_po', null, false);
		$this->db->stop_cache();

		$qty_pelunasan_barang = $this->db
		->where('pbi.tipe_ganti', 'Barang')
		->get_compiled_select();

		$qty_pelunasan_uang = $this->db
		->where('pbi.tipe_ganti', 'Uang')
		->get_compiled_select();

		$this->db->flush_cache();

		$data['parts'] = $this->db
		->select('pbi.surat_jalan_ahm')
		->select('bai.packing_sheet_number')
		->select('bai.nomor_karton')
		->select('bai.no_po')
		->select('bai.id_part')
		->select('p.nama_part')
		->select('psp.packing_sheet_quantity')
		->select("IFNULL(({$qty_claim_ahm}), 0) as qty_claim_ahm", false)
		->select("IFNULL(({$qty_pelunasan_barang}), 0) as qty_pelunasan_barang", false)
		->select("IFNULL(({$qty_pelunasan_uang}), 0) as qty_pelunasan_uang", false)
		->select('bai.qty_diterima')
		->select('bai.qty_rusak')
		->select('bai.keterangan_bapb')
		->select('bai.id_lokasi_rak')
		->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
		->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = bai.no_bapb')
		->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi')
		->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part = bai.id_part and pbi.packing_sheet_number_int = bai.packing_sheet_number_int and pbi.nomor_karton_int = bai.nomor_karton_int)')
		->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = bai.packing_sheet_number')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = bai.id_part and psp.no_doos_int = bai.nomor_karton_int and psp.packing_sheet_number_int = bai.packing_sheet_number_int)')
		->join('ms_part as p', 'p.id_part = bai.id_part')
		->where('bai.no_bapb', $this->input->get('no_bapb'))
		->get()->result();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['berita_acara'] = $this->db
		->select('ba.*')
		->select('e.nama_ekspedisi as vendor_name')
		->select('e.id_dealer')
		->select('so.id_sales_order')
		->from('tr_h3_md_berita_acara_penerimaan_barang as ba')
		->join('ms_h3_md_ekspedisi as e', 'e.id = ba.id_vendor')
		->join('tr_h3_md_sales_order as so', '(so.no_bapb = ba.no_bapb and so.status != "Canceled")', 'left')
		->where('ba.no_bapb', $this->input->get('no_bapb'))
		->limit(1)
		->get()->row();

		$qty_claim_ahm = $this->db
		->select('SUM(pbr.qty) as qty')
		->from('tr_h3_md_penerimaan_barang_reasons as pbr')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = pbr.id_claim')
		->where('pbr.id_penerimaan_barang_item = pbi.id')
		->where('kc.tipe_claim !=', 'Claim Ekspedisi')
		->get_compiled_select();

		$this->db->start_cache();
		$this->db
		->select('SUM(pbi.qty_rusak)')
		->from('tr_h3_md_pelunasan_bapb as pb')
		->join('tr_h3_md_pelunasan_bapb_items as pbi', 'pbi.no_pelunasan = pb.no_pelunasan')
		->where('pb.no_bapb = ba.no_bapb', null, false)
		->where('pbi.id_part = bai.id_part', null, false)
		->where('pbi.nomor_karton = bai.nomor_karton', null, false)
		->where('pbi.packing_sheet_number = bai.packing_sheet_number', null, false)
		->where('pbi.no_po = bai.no_po', null, false);
		$this->db->stop_cache();

		$qty_pelunasan_barang = $this->db
		->where('pbi.tipe_ganti', 'Barang')
		->get_compiled_select();

		$qty_pelunasan_uang = $this->db
		->where('pbi.tipe_ganti', 'Uang')
		->get_compiled_select();

		$this->db->flush_cache();

		$data['parts'] = $this->db
		->select('pbi.surat_jalan_ahm as surat_jalan_ahm')
		->select('bai.packing_sheet_number')
		->select('bai.nomor_karton')
		->select('bai.no_po')
		->select('bai.id_part')
		->select('p.nama_part')
		->select('psp.packing_sheet_quantity')
		->select("IFNULL(({$qty_claim_ahm}), 0) as qty_claim_ahm", false)
		->select("IFNULL(({$qty_pelunasan_barang}), 0) as qty_pelunasan_barang", false)
		->select("IFNULL(({$qty_pelunasan_uang}), 0) as qty_pelunasan_uang", false)
		->select('bai.qty_diterima')
		->select('bai.qty_rusak')
		->select('bai.keterangan_bapb')
		->select('bai.id_lokasi_rak')
		->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
		->join('tr_h3_md_berita_acara_penerimaan_barang as ba', 'ba.no_bapb = bai.no_bapb')
		->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = ba.no_surat_jalan_ekspedisi')
		->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.id_part = bai.id_part and pbi.packing_sheet_number = bai.packing_sheet_number and pbi.nomor_karton = bai.nomor_karton)')
		->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = bai.packing_sheet_number')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = bai.id_part and psp.no_doos = bai.nomor_karton and psp.packing_sheet_number = bai.packing_sheet_number)')
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
		$parts = $this->input->post('parts');
		$items = $this->getOnly([
			'nomor_karton', 'id_part', 'qty_diterima', 
			'qty_rusak', 'id_lokasi_rak', 'keterangan_bapb', 
			'packing_sheet_number', 'no_po', 'surat_jalan_ahm'
		], $parts, $this->input->post(['no_bapb']));
		$this->db->trans_start();
		$this->berita_acara->update($berita_acara, $this->input->post(['no_bapb']));
		$this->berita_acara_items->update_batch($items, $this->input->post(['no_bapb']));
		if(count($parts) > 0){
			foreach ($parts as $part) {
				$condition = [
					'no_surat_jalan_ekspedisi' => $this->input->post('no_surat_jalan_ekspedisi'),
					'packing_sheet_number' => $part['packing_sheet_number'],
					'nomor_karton' => $part['nomor_karton'],
					'id_part' => $part['id_part'],
					'no_po' => $part['no_po'],
				];
				$item = (array) $this->penerimaan_barang_items->get($condition, true);

				$this->penerimaan_barang_items->update([
					'qty_diterima' => $part['qty_diterima']
				], $condition);

				$kategori_claim_ekspedisi = $this->db
				->select('kc.id')
				->from('ms_kategori_claim_c3 as kc')
				->where('kc.kode_claim', 'CE')
				->where('kc.tipe_claim', 'Claim Ekspedisi')
				->get()->row_array();

				if($kategori_claim_ekspedisi != null){
					$this->penerimaan_barang_reasons->update([
						'qty' => $part['qty_rusak'],
						'keterangan' => $part['keterangan_bapb'],
					], [
						'id_penerimaan_barang_item' => $item['id'],
						'id_claim' => $kategori_claim_ekspedisi['id']
					]);
				}
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'BAPB berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			send_json($this->berita_acara->get($this->input->post(['no_bapb']), true));
		}else{
			$this->session->set_flashdata('pesan', 'BAPB tidak berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json([
				'message' => 'BAPB tidak berhasil diperbarui.'
			], 422);
		}
	}

	public function cancel(){
		$bapb_sudah_cancel = $this->berita_acara->get([
			'no_bapb' => $this->input->get('no_bapb'),
			'status' => 'Canceled'
		], true);

		if($bapb_sudah_cancel != null){
			$this->session->set_flashdata('pesan', 'BAPB sudah pernah di dibatalkan.');
			$this->session->set_flashdata('tipe', 'warning');
			
			send_json(
				$this->berita_acara->get($this->input->get(['no_bapb']), true)
			);
		}

		$this->db->trans_start();
		$this->berita_acara->update([
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user'),
			'status' => 'Canceled'
		], $this->input->get(['no_bapb']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_flashdata('pesan', 'BAPB berhasil dibatalkan.');
			$this->session->set_flashdata('tipe', 'success');

			send_json(
				$this->berita_acara->get($this->input->get(['no_bapb']), true)
			);
		}else{
			send_json([
				'message' => 'BA penerimaan barang tidak berhasil dibatalkan',
			], 422);
		}
	}
}