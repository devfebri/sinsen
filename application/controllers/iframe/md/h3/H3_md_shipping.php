<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_shipping extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_stock_model', 'stock');
	}

    public function index(){
		$data = [];

		$id_surat_pengantar_by_do = $this->db
		->select('spi.id_surat_pengantar')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet')
		->where('do.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->row_array();

		if($id_surat_pengantar_by_do != null){
			$id_surat_pengantar_by_do = $id_surat_pengantar_by_do['id_surat_pengantar'];
		}
		
		$surat_pengantar = $this->db
		->select('sp.id_surat_pengantar')
		->select('d.nama_dealer')
		->select('e.nama_ekspedisi')
		->select('sp.no_plat')
		->from('tr_h3_md_surat_pengantar as sp')
		->join('ms_dealer as d', 'd.id_dealer = sp.id_dealer')
		->join('ms_h3_md_ekspedisi as e', 'e.id = sp.id_ekspedisi')
		->where('sp.id_surat_pengantar', $id_surat_pengantar_by_do)
		->get()->row_array();

		$data['surat_pengantar'] = $surat_pengantar;


		$jumlah_koli = $this->db
		->select('COUNT( DISTINCT(splp.no_dus) )')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_picking_list = pl.id_picking_list')
		->get_compiled_select();

		$items = $this->db
		->select('spi.id_packing_sheet')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('do.id_do_sales_order')
		->select('so.po_type')
		->select("IFNULL( ({$jumlah_koli}), 0 ) as jumlah_koli", false)
		->from('tr_h3_md_surat_pengantar_items as spi')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = spi.id_packing_sheet')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->where('spi.id_surat_pengantar', $surat_pengantar['id_surat_pengantar'])
		->get()->result_array();

		$data['items'] = $items;


        $this->load->view('iframe/md/h3/h3_md_shipping', $data);
    }

}