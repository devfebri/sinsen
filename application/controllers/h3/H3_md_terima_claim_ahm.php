<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_terima_claim_ahm extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_terima_claim_ahm";
    protected $title  = "Terima Claim AHM";

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

		$this->load->model('H3_md_terima_claim_model', 'terima_claim_ahm');
		$this->load->model('H3_md_terima_claim_item_model', 'terima_claim_ahm_item');
		$this->load->model('H3_md_retur_pembelian_claim_model', 'retur_pembelian_claim');
		$this->load->model('H3_md_retur_pembelian_claim_items_model', 'retur_pembelian_claim_items');
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
		$terima_claim = array_merge($this->input->post([
			'tanggal_surat_jawaban',
		]), [
			'id_terima_claim_ahm' => $this->terima_claim_ahm->generateID()
		]);

		$parts = $this->getOnly([
			'id_claim', 'id_claim_int', 'id_part', 'id_part_int', 'no_doos', 'no_po', 'no_po_int', 'id_kode_claim', 'barang_checklist', 'ganti_barang', 'uang_checklist', 'ganti_uang', 'nominal_uang', 'ditolak_checklist', 'ditolak'
		], $this->input->post('parts'), [
			'id_terima_claim_ahm' => $terima_claim['id_terima_claim_ahm']
		]);
		$this->db->trans_start();
		$this->terima_claim_ahm->insert($terima_claim);
		$this->terima_claim_ahm_item->insert_batch($parts);
		$this->db->trans_complete();

		$terima_claim_ahm = (array) $this->terima_claim_ahm->find($terima_claim['id_terima_claim_ahm'], 'id_terima_claim_ahm');

		if ($this->db->trans_status() AND $terima_claim_ahm != null) {
			$message = 'Terima Claim AHM berhasil dibuat.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => $message,
				'payload' => $terima_claim_ahm,
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_terima_claim_ahm=%s', $this->page, $terima_claim_ahm['id_terima_claim_ahm']))
			]);
		}else{
			$message = 'Terima Claim AHM tidak berhasil dibuat.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => $message,
			], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['terima_claim'] = $this->db
		->select('tca.*')
		->select('date_format(tca.created_at, "%d/%m/%Y") as created_at')
		->from('tr_h3_md_terima_claim_ahm as tca')
		->where('tca.id_terima_claim_ahm', $this->input->get('id_terima_claim_ahm'))
		->get()->row();

		$data['parts'] = $this->db
        ->select('cmi.id_claim')
        ->select('cmi.id_claim_int')
        ->select('cmi.id_part')
        ->select('cmi.id_part_int')
		->select('p.nama_part')
		->select('cmi.no_doos')
		->select('cmi.no_po')
		->select('cmi.no_po_int')
        ->select('cmi.qty_part_diclaim')
        ->select('tcai.barang_checklist')
		->select('tcai.ganti_barang')
        ->select('tcai.uang_checklist')
        ->select('tcai.ganti_uang')
		->select('tcai.nominal_uang')
        ->select('tcai.ditolak_checklist')
		->select('tcai.ditolak')
		->select('tcai.id_kode_claim')
		->select('kc.kode_claim')
		->select('kc.nama_claim')
		->from('tr_h3_md_terima_claim_ahm_item as tcai')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm_item as cmi', '(cmi.id_claim = tcai.id_claim and cmi.id_part = tcai.id_part and cmi.no_doos = tcai.no_doos and cmi.no_po = tcai.no_po and cmi.id_kode_claim = tcai.id_kode_claim)')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm as cm', 'cm.id_claim = cmi.id_claim')
        ->join('ms_part as p', 'p.id_part = cmi.id_part')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cmi.id_kode_claim')
		->where('tcai.id_terima_claim_ahm', $this->input->get('id_terima_claim_ahm'))
		->get()->result_array();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";

		$data['terima_claim'] = $this->db
		->select('tca.*')
		->select('date_format(tca.created_at, "%d/%m/%Y") as created_at')
		->from('tr_h3_md_terima_claim_ahm as tca')
		->where('tca.id_terima_claim_ahm', $this->input->get('id_terima_claim_ahm'))
		->get()->row();

		$data['parts'] = $this->db
        ->select('cmi.id_claim')
        ->select('cmi.id_claim_int')
        ->select('cmi.id_part')
        ->select('cmi.id_part_int')
		->select('p.nama_part')
		->select('cmi.no_doos')
		->select('cmi.no_po')
		->select('cmi.no_po_int')
        ->select('cmi.qty_part_diclaim')
        ->select('tcai.barang_checklist')
		->select('tcai.ganti_barang')
        ->select('tcai.uang_checklist')
        ->select('tcai.ganti_uang')
		->select('tcai.nominal_uang')
        ->select('tcai.ditolak_checklist')
        ->select('tcai.ditolak')
		->select('tcai.id_kode_claim')
		->select('kc.kode_claim')
		->select('kc.nama_claim')
		->from('tr_h3_md_terima_claim_ahm_item as tcai')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm_item as cmi', '(cmi.id_claim = tcai.id_claim and cmi.id_part = tcai.id_part and cmi.no_doos = tcai.no_doos and cmi.no_po = tcai.no_po and cmi.id_kode_claim = tcai.id_kode_claim)')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm as cm', 'cm.id_claim = cmi.id_claim')
        ->join('ms_part as p', 'p.id_part = cmi.id_part')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cmi.id_kode_claim')
		->where('tcai.id_terima_claim_ahm', $this->input->get('id_terima_claim_ahm'))
		->get()->result();

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$terima_claim = $this->input->post([
			'tanggal_surat_jawaban',
		]);

		$parts = $this->getOnly([
			'id_claim', 'id_claim_int', 'id_part', 'id_part_int', 'no_doos', 'no_po', 'no_po_int', 'id_kode_claim', 'barang_checklist', 'ganti_barang', 'uang_checklist', 'ganti_uang', 'nominal_uang', 'ditolak_checklist', 'ditolak'
		], $this->input->post('parts'), $this->input->post(['id_terima_claim_ahm']));
		$this->db->trans_start();
		$this->terima_claim_ahm->update($terima_claim, $this->input->post(['id_terima_claim_ahm']));
		$this->terima_claim_ahm_item->update_batch($parts, $this->input->post(['id_terima_claim_ahm']));
		$this->db->trans_complete();

		$terima_claim_ahm = (array) $this->terima_claim_ahm->get($this->input->post(['id_terima_claim_ahm']), true);

		if ($this->db->trans_status() AND $terima_claim_ahm != null) {
			$message = 'Terima Claim AHM berhasil diupdate.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => $message,
				'payload' => $terima_claim_ahm,
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_terima_claim_ahm=%s', $this->page, $terima_claim_ahm['id_terima_claim_ahm']))
			]);
		}else{
			$message = 'Terima Claim AHM tidak berhasil diupdate.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => $message,
			], 422);
		}
	}

	public function proses(){
		$id_terima_claim_ahm = $this->input->post('id_terima_claim_ahm');

		$this->db->trans_start();
		$this->terima_claim_ahm->update([
			'status' => 'Processed',
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user')
		], [
			'id_terima_claim_ahm' => $id_terima_claim_ahm
		]);
		$this->proses_ganti_uang($id_terima_claim_ahm);
		$this->db->trans_complete();

		$terima_claim_ahm = $this->terima_claim_ahm->find($id_terima_claim_ahm, 'id_terima_claim_ahm');

		if ($this->db->trans_status() AND $terima_claim_ahm != null) {
			$this->session->set_flashdata('pesan', 'Terima Claim AHM berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'payload' => $terima_claim_ahm,
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_terima_claim_ahm=%s', $this->page, $id_terima_claim_ahm))
			]);
		}else{
			send_json([
				'message' => 'Terima Claim AHM tidak berhasil diproses.'
			], 422);
		}
	}

	private function proses_ganti_uang($id_terima_claim_ahm){
		$claim_main_dealers = $this->db
		->select('DISTINCT(tcai.id_claim) as id_claim')
		->from('tr_h3_md_terima_claim_ahm_item as tcai')
		->where('tcai.id_terima_claim_ahm', $id_terima_claim_ahm)
		->get()->result_array();

		foreach ($claim_main_dealers as $claim_main_dealer) {
			$parts_untuk_items_retur_pembelian = $this->db
			->select('tcai.id_terima_claim_ahm')
			->select('tcai.id_part')
			->select('tcai.no_doos')
			->select('tcai.no_po')
			->select('tcai.id_kode_claim')
			->select('tcai.ganti_uang as qty')
			->select('(tcai.nominal_uang * tcai.ganti_uang) as nominal')
			->from('tr_h3_md_terima_claim_ahm as tca')
			->join('tr_h3_md_terima_claim_ahm_item as tcai', 'tcai.id_terima_claim_ahm = tca.id_terima_claim_ahm')
			->where('tca.id_terima_claim_ahm', $id_terima_claim_ahm)
			->where('tcai.id_claim', $claim_main_dealer['id_claim'])
			->where('tcai.uang_checklist', 1)
			->get()->result_array();

			if(count($parts_untuk_items_retur_pembelian) > 0){
				$retur_pembelian_dengan_no_claim_sama = $this->db
				->select('rpc.no_retur')
				->from('tr_h3_md_retur_pembelian_claim as rpc')
				->where('rpc.id_claim', $claim_main_dealer['id_claim'])
				->where('rpc.status !=', 'Processed')
				->limit(1)
				->get()->row_array();

				if($retur_pembelian_dengan_no_claim_sama != null){
					$parts_untuk_items_retur_pembelian = array_map(function($row) use ($retur_pembelian_dengan_no_claim_sama) {
						$row['no_retur'] = $retur_pembelian_dengan_no_claim_sama['no_retur'];
						return $row;
					}, $parts_untuk_items_retur_pembelian);

					$this->retur_pembelian_claim_items->insert_batch($parts_untuk_items_retur_pembelian);
				}else{
					$retur_pembelian = [
						'no_retur' => $this->retur_pembelian_claim->generateID(),
						'tanggal' => date('Y-m-d', time()),
						'id_claim' => $claim_main_dealer['id_claim'],
					];
					$parts_untuk_items_retur_pembelian = array_map(function($row) use ($retur_pembelian) {
						$row['no_retur'] = $retur_pembelian['no_retur'];
						return $row;
					}, $parts_untuk_items_retur_pembelian);

					$this->retur_pembelian_claim->insert($retur_pembelian);
					$this->retur_pembelian_claim_items->insert_batch($parts_untuk_items_retur_pembelian);
				}
			}
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->terima_claim_ahm->update([
			'status' => 'Canceled',
			'cancel_at' => date('Y-m-d H:i:s', time()),
			'cancel_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_terima_claim_ahm']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Terima Claim AHM berhasil dibatalkan.');
			$this->session->set_flashdata('tipe', 'info');
			$terima_claim_ahm = $this->terima_claim_ahm->get($this->input->post(['id_terima_claim_ahm']), true);
			send_json($terima_claim_ahm);
		}else{
			$this->session->set_flashdata('pesan', 'Terima Claim AHM tidak berhasil dibatalkan.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('tanggal_surat_jawaban', 'Tanggal Surat Jawaban AHM', 'required');

        if (!$this->form_validation->run()){
            $data = $this->form_validation->error_array();
            send_json($data, 422);
        }
    }
}