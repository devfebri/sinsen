<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_hutang_do extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_hutang_do";
	protected $title  = "Hutang DO";

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

		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_packing_sheet_model', 'packing_sheet');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['do_sales_order'] = $this->db
		->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('dso.id_do_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('so.id_sales_order')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('po.po_type')
		->select('dso.status')
		->select('dso.check_diskon_insentif')
		->select('dso.diskon_insentif')
		->select('dso.check_diskon_cashback')
		->select('dso.diskon_cashback')
		->select('po.id_dealer')
		->select('pl.selesai_scan')
		->from('tr_h3_md_do_sales_order as dso')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('tr_h3_md_picking_list as pl', '(pl.id_ref = dso.id_do_sales_order and pl.selesai_scan = 1)')
		->where('dso.id_do_sales_order', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['do_sales_order_parts'] = $this->db
		->select('dsop.id_part')
		->select('p.nama_part')
		->select('sop.harga')
		->select('dsop.qty_supply')
		->select('dsop.tipe_diskon_satuan_dealer')
		->select('dsop.diskon_satuan_dealer')
		->select('dsop.tipe_diskon_campaign')
		->select('dsop.diskon_campaign')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('d.alamat')
		->select('ifnull(kp.include_ppn, 0) as include_ppn')
		->select('p.harga_dealer_user as harga_jual')
		->select('p.harga_md_dealer as harga_beli')
		->from('tr_h3_md_do_sales_order_parts as dsop')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dsop.id_do_sales_order')
		->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part)')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_part as p', 'p.id_part = dsop.id_part')
		->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
		->where('dsop.id_do_sales_order', $this->input->get('id'))
		->get()->result();

		$this->template($data);
	}

	public function view_check(){
		$data['mode']    = 'view_check';
		$data['set']     = "form";
		$data['do_sales_order'] = $this->db
		->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('dso.id_do_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('so.id_sales_order')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('po.po_type')
		->select('dso.status')
		->select('dso.check_diskon_insentif')
		->select('dso.diskon_insentif')
		->select('dso.check_diskon_cashback')
		->select('dso.diskon_cashback')
		->select('po.id_dealer')
		->select('pl.selesai_scan')
		->from('tr_h3_md_do_sales_order as dso')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('tr_h3_md_picking_list as pl', '(pl.id_ref = dso.id_do_sales_order and pl.selesai_scan = 1)')
		->where('dso.id_do_sales_order', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['do_sales_order_parts'] = $this->db
		->select('dsop.id_part')
		->select('p.nama_part')
		->select('dsop.qty_supply as qty_do')
		->select('splp.qty_scan')
		->select('ABS(splp.qty_scan - dsop.qty_supply) as qty_selisih')
		->from('tr_h3_md_do_sales_order_parts as dsop')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dsop.id_do_sales_order')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
		->join('tr_h3_md_scan_picking_list_parts as splp', '(splp.id_picking_list = pl.id_picking_list and splp.id_part = dsop.id_part)')
		->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part)')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_part as p', 'p.id_part = dsop.id_part')
		->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
		->where('dsop.id_do_sales_order', $this->input->get('id'))
		->get()->result();

		$this->template($data);
	}

	public function cetak(){
		$do_sales_order= $this->db
		->select('ps.id as id_entity_packing_sheet')
		->from('tr_h3_md_do_sales_order as dso')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('tr_h3_md_picking_list as pl', '(pl.id_ref = dso.id_do_sales_order and pl.selesai_scan = 1)')
		->join('tr_h3_md_packing_sheet as ps', 'pl.id_picking_list = ps.id_picking_list')
		->where('dso.id_do_sales_order', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$fakturBelumDibuat = $this->packing_sheet->get([
			'id' => $do_sales_order->id_entity_packing_sheet,
			'no_faktur !=' => null
		], true) == null;

		if($fakturBelumDibuat){
			$this->packing_sheet->update([
				'tgl_faktur' => date('Y-m-d', time()),
				'no_faktur' => $this->packing_sheet->generateFaktur(),
			], [
				'id' => $do_sales_order->id_entity_packing_sheet,
			]);
		}

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Faktur berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/detail?id={$this->input->get('id')}'>";
		} else {
			$this->session->set_flashdata('pesan', 'Faktur tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/detail?id={$this->input->get('id')}'>";
		}
	}

	public function approve(){
		$this->db->trans_start();
		foreach ($this->input->post('parts') as $part) {
			$this->do_sales_order_parts->update([
				'id_do_sales_order' => $this->input->post('id_do_sales_order'),
				'id_part' => $part['id_part'],
				'qty_supply' => $part['qty_scan']
			]);
		}

		$data = [
			'id_do_sales_order' => $this->do_sales_order->generateID(),
			'id_do_sales_order_old' => $this->input->post('id_do_sales_order'),
			'tanggal_approve_revisi' => date('Y-m-d H:i:s'),
			'sudah_revisi' => 1,
			'status_revisi' => 'Approved',
		];
		$this->do_sales_order->update($data, $this->input->post(['id_do_sales_order']));
		$this->do_sales_order_parts->update([
			'id_do_sales_order' => $data['id_do_sales_order']
		], $this->input->post(['id_do_sales_order']));
		$this->picking_list->update([
			'id_ref' => $data['id_do_sales_order']
		], [
			'id_ref' => $this->input->post('id_do_sales_order')
		]);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$do = $this->do_sales_order->find($data['id_do_sales_order'], 'id_do_sales_order');
			send_json($do);
		}else{
			$this->set_status_header(500);
		}
	}

	public function reject(){
		$this->db->trans_start();
		$data = array_merge($this->input->post(['alasan_reject', 'total']), [
			'status' => 'Rejected'
		]);
		$this->do_sales_order->update($data, $this->input->post(['id_do_sales_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$do = $this->do_sales_order->get($this->input->post(['id_do_sales_order']), true);
			send_json($do);
		}else{
			$this->set_status_header(500);
		}
	}
}