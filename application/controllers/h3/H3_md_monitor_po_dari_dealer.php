<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitor_po_dari_dealer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitor_po_dari_dealer";
    protected $title  = "Monitor PO dari Dealer";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('language');
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

		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_md_purchase_hotline_model', 'purchase_hotline');
		$this->load->model('h3_md_purchase_hotline_parts_model', 'purchase_hotline_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('H3_md_stock_model', 'stock');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['purchase_order'] = $this->db
		->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
		->select('date_format(po.submit_at, "%d/%m/%Y") as tanggal_submit')
		->select('po.po_id')
		->select('po.po_type')
		->select('po.produk')
		->select('po.status')
		->select('po.pesan_untuk_bulan')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('k.nama_lengkap as nama_salesman')
		->select('date_format(po.batas_waktu, "%d/%m/%Y") as batas_waktu')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = po.id_salesman', 'left')
		->where('po.po_id', $this->input->get('id'))
		->limit(1)
		->get()->row_array();

		$parts = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.hoo_flag')
		->select('p.import_lokal')
		->select('p.current')
		->select('pop.harga_saat_dibeli')
		->select('pop.kuantitas')
		->select('IFNULL(pop.tipe_diskon, "") as tipe_diskon')
		->select('pop.diskon_value')
		->select('IFNULL(pop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
		->select('pop.diskon_value_campaign')
		->select('pop.jenis_diskon_campaign')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result_array();

		$parts = array_map(function($data){
			$data['qty_avs'] = $this->stock->qty_avs($data['id_part']);
			return $data;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function proses(){
		$this->db->trans_status();
		$purchase = $this->purchase_order->find($this->input->get('id'), 'po_id');

		if($purchase->po_type != 'HLO' && $purchase->po_type != 'URG'){
			$sales = [
				'id_dealer' => $purchase->id_dealer,
				'id_ref' => $purchase->po_id,
				'kategori_po' => $purchase->kategori_po,
				'id_salesman' => $purchase->id_salesman,
				'produk' => $purchase->produk,
				'batas_waktu' => $purchase->batas_waktu,
				'total_amount' => $purchase->total_amount,
				'tipe_source' => 'Dealer',
				'po_type' => $purchase->po_type,
				'type_ref' => 'purchase_order_dealer',
				'tanggal_order' => date('Y-m-d', time()),
				'id_sales_order' => $this->sales_order->generateID($purchase->po_type, $purchase->id_dealer),
				'jenis_pembayaran' => 'Credit'
			];

			$parts = $this->db
			->select('pop.id_part')
			->select('pop.kuantitas as qty_order')
			->select('pop.kuantitas as qty_pemenuhan')
			->select('pop.tipe_diskon')
			->select('pop.diskon_value')
			->select('pop.tipe_diskon_campaign')
			->select('pop.diskon_value_campaign')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.id_campaign_diskon')
			->select('pop.jenis_diskon_campaign')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->where('pop.po_id', $this->input->get('id'))
			->get()->result_array();

			$parts = array_map(function($part) use ($sales){
				$part['qty_on_hand'] = $this->stock->qty_on_hand($part['id_part']);
				$part['id_sales_order'] = $sales['id_sales_order'];
				return $part;
			}, $parts);

			$this->sales_order->insert($sales);
			$this->sales_order_parts->insert_batch($parts);
		}

		$this->purchase_order->update([
			'status' => 'Processed by MD',
			'status_md' => 'Open PO',
			'proses_at' => date('Y-m-d H:i:s'),
			'proses_by' => $this->session->userdata('id_user')
		], [
			'po_id' => $this->input->get('id')
		]);

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Purchase Order berhasil diproses');
			$this->session->set_flashdata('tipe', 'info');
		}else{
			$this->session->set_flashdata('pesan', 'Purchase order gagal untuk diproses.');
			$this->session->set_flashdata('tipe', 'danger');
		}
		echo "h3/{$this->page}";
		die();
	}

	public function reject(){
		$this->db->trans_status();
		$this->purchase_order->update([
			'status' => 'Rejected'
		], [
			'po_id' => $this->input->get('id')
		]);

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Purchase Order direject');
			$this->session->set_flashdata('tipe', 'info');
		}else{
			$this->session->set_flashdata('pesan', 'Purchase order gagal untuk direject.');
			$this->session->set_flashdata('tipe', 'danger');
		}
		echo "h3/{$this->page}";
		die();
	}
}