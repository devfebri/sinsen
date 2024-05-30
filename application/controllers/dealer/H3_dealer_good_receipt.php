<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_good_receipt extends Honda_Controller {
	var $tables = "tr_h3_dealer_good_receipt";	
	var $folder = "dealer";
	var $page   = "h3_dealer_good_receipt";
	var $title  = "Good Receipt";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h3_dealer_request_document_model', 'request_document');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_sales_order_model', 'sales_order');		
		$this->load->model('h3_dealer_good_receipt_model', 'good_receipt');		
		$this->load->model('h3_dealer_good_receipt_parts_model', 'good_receipt_parts');		
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('customer_model', 'customer');		
		$this->load->model('dealer_model', 'dealer');		
		$this->load->model('ms_part_model', 'ms_part');		
		$this->load->model('h3_dealer_gudang_h23_model', 'gudang_h23');
		$this->load->model('h3_dealer_stock_model', 'stock');
		$this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";
		$this->template($data);	
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = 'Purchase Order';		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$data['good_receipt'] = $this->db
		->select('gr.*')
		->select('gr.id_reference as nomor_shipping_list')
		->select('po.po_id as nomor_purchase_order')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_purchase_order')
		->select("
		case
			when po.po_type = 'fix' then 'Fix'
			when po.po_type = 'reg' then 'Reguler'
			when po.po_type = 'ugn' then 'Urgent'
			when po.po_type = 'hlo' then 'Hotline'
		end as jenis_purchase_order")
		->select('ps.no_faktur')
		->select('pb.id_penerimaan_barang as nomor_penerimaan')
		->select('date_format(pb.created_at, "%d-%m-%Y") as tanggal_penerimaan')
		->from('tr_h3_dealer_good_receipt as gr')
		->join('tr_h3_dealer_penerimaan_barang as pb', 'pb.id_packing_sheet = gr.id_reference', 'left')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
        ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
        ->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
		->where('gr.id_good_receipt', $this->input->get('id'))
		->get()->row();

		$data['referensi'] = $this->db
		->select('nsc.no_nsc as id_referensi')
        ->select('date_format(nsc.created_at, "%d-%m-%Y") as tanggal')
        ->select('"nsc" as tipe_referensi')
		->from('tr_h23_nsc as nsc')
		->where('nsc.no_nsc', $data['good_receipt']->id_reference)
		->get()->row();

		$kuantitas_part_dari_sales_return = $this->db
		->select('sorp.kuantitas_return')
		->from('tr_h3_dealer_sales_order_return_parts as sorp')
		->join('tr_h3_dealer_sales_order_return as sor', 'sor.id_sales_order_return = sorp.id_sales_order_return')
		->where('(sor.nomor_so = gr.id_reference AND sorp.id_part = grp.id_part)')
		->get_compiled_select();

		$kuantitas_part_dari_invoice = $this->db
		->select('kuantitas')
		->from('tr_h3_dealer_invoice_parts as ip')
		// ->join('tr_h3_dealer_invoice_parts as i', 'i.id_invoice = ip.id_invoice')
		->where('(ip.id_invoice = gr.id_reference AND ip.id_part = grp.id_part)')
		->get_compiled_select();


		$data['good_receipt_parts'] = $this->db
		->select('gr.ref_type')
		->select('grp.*')
		->select('p.nama_part')
		->select('s.satuan')
		->select('concat("Rp ", format(pop.harga_saat_dibeli, 0, "ID_id")) as harga_beli_md')
		->select("
			case 
				when gr.ref_type = 'return_exchange_so' then ({$kuantitas_part_dari_sales_return})
				when gr.ref_type = 'part_sales_work_order' then ({$kuantitas_part_dari_invoice})
			end as kuantitas
		")
		->from('tr_h3_dealer_good_receipt_parts as grp')
		->join('tr_h3_dealer_good_receipt as gr', 'grp.id_good_receipt=gr.id_good_receipt')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = gr.id_reference')
        ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
        ->join('tr_h3_dealer_purchase_order_parts as pop', '(so.id_ref = pop.po_id and pop.id_part = grp.id_part)')
		->join('ms_part as p', 'p.id_part = grp.id_part')
		->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
		->where('grp.id_good_receipt', $this->input->get('id'))
		->get()->result()
		;

		$this->template($data);	
	}
}