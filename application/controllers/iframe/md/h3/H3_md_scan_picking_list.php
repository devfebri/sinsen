<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_scan_picking_list extends CI_Controller {

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

		$picking_list = $this->db
		->select('pl.id_picking_list')
		->select('pl.id_ref_int')
		->select('date_format(pl.created_at, "%d-%m-%Y") as created_at')
		->select('
			case
				when do.sudah_revisi = 1 then concat(do.id_do_sales_order, "-REV")
				else do.id_do_sales_order
			end as id_do_sales_order
		', false)
		->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal')
		->select('k.nama_lengkap as nama_picker')
		->select('d.nama_dealer')
		->select('so.kategori_po')
		->select('d.alamat')
		->select('so.po_type')
		->select('so.id_sales_order')
		->select('
			case
				when pl.start_scan is null then "-"
				else date_format(pl.start_scan, "%d-%m-%Y %H:%i")
			end as start_scan
		', false)
		->select('
			case
				when pl.end_scan is null then "-"
				else date_format(pl.end_scan, "%d-%m-%Y %H:%i")
			end as end_scan
		', false)
		->select("IFNULL( ({$jumlah_koli}), 0 ) as jumlah_koli", false)
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker')
		->where('pl.id_ref', $this->input->get('id_do_sales_order'))
		->get()->row_array();
		$data['picking_list'] = $picking_list;

		$qty_picking = $this->db
		->select('SUM(plp.qty_disiapkan) as qty', false)
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list_int = pl.id')
		->where('pl.id_ref_int = dop.id_do_sales_order_int', null, false)
		->where('plp.id_part_int = dop.id_part_int', null, false)
		->get_compiled_select();

		$qty_scan = $this->db
		->select('SUM(splp.qty_scan) as qty_scan', false)
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list_int = pl.id')
		->join('tr_h3_md_scan_picking_list_parts as splp', '(splp.id_picking_list = pl.id_picking_list and splp.id_part = plp.id_part and splp.id_lokasi_rak = plp.id_lokasi_rak)')
		->where('pl.id_ref_int = dop.id_do_sales_order_int', null, false)
		->where('splp.id_part = dop.id_part', null, false)
		->get_compiled_select();

		$this->db
		->select('dop.id_do_sales_order_int')
		->select('dop.id_part')
		->select('p.nama_part')
		->select('dop.qty_supply as qty_do')
		->select(sprintf('IFNULL((%s), 0) as qty_picking', $qty_picking), false)
		->select(sprintf('IFNULL((%s), 0) as qty_scan', $qty_scan), false)
		->from('tr_h3_md_do_sales_order_parts as dop')
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->where('dop.id_do_sales_order_int', $picking_list['id_ref_int']);
		$parts = array_map(function($data){
			$data['service_rate'] = (floatval($data['qty_scan']) / floatval($data['qty_picking']) * 100);
			return $data;
		}, $this->db->get()->result_array());

		$data['parts'] = $parts;

		$data['picking_list_parts'] = $this->db
		->select('splp.id_part')
		->select('p.nama_part')
		->select('splp.no_dus')
		->select('splp.qty_scan')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->join('ms_part as p', 'p.id_part = splp.id_part')
		->where('splp.id_picking_list', $picking_list['id_picking_list'])
		->order_by('splp.no_dus', 'asc')
		->order_by('splp.id_part', 'asc')
		->get()->result_array();

        $this->load->view('iframe/md/h3/h3_md_scan_picking_list', $data);
    }

}