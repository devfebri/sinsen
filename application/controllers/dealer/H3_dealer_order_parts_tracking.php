<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_order_parts_tracking extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_order_parts_tracking";
	var $title  = "Order & Parts tracking";

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
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');		
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('dealer_model', 'dealer');		
		$this->load->model('ms_part_model', 'ms_part');		
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['kelompok_part'] = $this->db->from('ms_kelompok_part')->get()->result();

		$this->template($data);	
	}

	public function track_part(){
		$this->db
		->select('po.po_id')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.kelompok_part')
		->select('pop.kuantitas as po')
		->select("opt.qty_book as book")
		->select("opt.qty_pick as pick")
		->select("opt.qty_pack as pack")
		->select("opt.qty_bill as bill")
		->select("opt.qty_ship as ship")
		->select('opt.id_tipe_kendaraan')
		->select('pop.id_tipe_kendaraan as id_tipe_kendaraan_pop')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('po.id_dealer', $this->m_admin->cari_dealer())
		->group_start()
		->where('po.order_to', null)
		->or_where('po.order_to', 0)
		->group_end()
		;

		if($this->input->post('kategori_po') != null && $this->input->post('kategori_po') == 'KPB'){
			$this->db->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part and opt.id_tipe_kendaraan = pop.id_tipe_kendaraan)');
		}else{
			$this->db->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)');
		}

		if($this->input->post('tipe_filter') == 'purchase_order'){
			$this->db->where('pop.po_id', $this->input->post('filter_value'));
		}

		if($this->input->post('tipe_filter') == 'part_number'){
			$this->db->where('pop.id_part', $this->input->post('filter_value'));
		}
		
		if($this->input->post('tipe_po') != null){
			$this->db->where('po.po_type', strtoupper($this->input->post('tipe_po')));
		}

		send_json($this->db->get()->result());
	}

	public function check_ship_date(){
		$this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('ps.id_packing_sheet as ship_number')
		->select('plp.qty_disiapkan as ship_qty')
		->select('e.nama_ekspedisi as ekspedisi')
		->select('date_format(sp.created_at, "%d-%m-%Y %H:%i") as created_at')
		->select('sp.no_plat')
		->select('sp.id_surat_pengantar')
		->from('tr_h3_dealer_purchase_order_parts pop')
		->join('tr_h3_md_sales_order as so', 'so.id_ref = pop.po_id')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order')
		->join('tr_h3_md_picking_list_parts as plp', '(pl.id_picking_list = plp.id_picking_list and plp.id_part = pop.id_part)')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet')
		->join('tr_h3_md_surat_pengantar as sp', 'sp.id_surat_pengantar = spi.id_surat_pengantar')
		->join('ms_h3_md_ekspedisi as e', 'e.id = sp.id_ekspedisi', 'left')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->post('po_id'))
		->where('pop.id_part', $this->input->post('id_part'));

		send_json($this->db->get()->result());
	}
}