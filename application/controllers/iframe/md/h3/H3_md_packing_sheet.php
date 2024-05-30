<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_packing_sheet extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_stock_model', 'stock');
	}

    public function index(){
		$data = [];
		
		$jumlah_koli = $this->db
		->select('COUNT( DISTINCT(splp.no_dus) )')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_picking_list = pl.id_picking_list')
		->get_compiled_select();

		$packing_sheet = $this->db
		->select('ps.id_packing_sheet')
		->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('ps.no_faktur')
		->select('date_format(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur')
		->select('d.pemilik')
		->select('e.nama_ekspedisi')
		->select('do.id_do_sales_order')
		->select('so.id_sales_order')
		->select("IFNULL( ({$jumlah_koli}), 0 ) as jumlah_koli", false)
		->from('tr_h3_md_packing_sheet as ps')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet', 'left')
		->join('tr_h3_md_surat_pengantar as sp', 'sp.id_surat_pengantar = spi.id_surat_pengantar', 'left')
		->join('ms_h3_md_ekspedisi as e', 'e.id = sp.id_ekspedisi', 'left')
		->where('do.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->row_array();
		$data['packing_sheet'] = $packing_sheet;

		$qty_scan = $this->db
		->select('SUM(splp.qty_scan)')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_scan_picking_list_parts as splp', 'splp.id_picking_list = pl.id_picking_list')
		->where('pl.id_ref = dop.id_do_sales_order')
		->where('splp.id_part = dop.id_part')
		->get_compiled_select();

		$parts = $this->db
		->select('dop.id_part')
		->select('p.nama_part')
		->select("IFNULL( ({$qty_scan}), 0 )  as qty", false)
		->from('tr_h3_md_do_sales_order_parts as dop')
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->where('dop.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->result_array();

		$data['parts'] = $parts;

        $this->load->view('iframe/md/h3/h3_md_packing_sheet', $data);
    }

}