<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_picking_list extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_stock_model', 'stock');
	}

    public function index(){
        $data = [];
		$picking_list = $this->db
		->select('pl.id_picking_list')
		->select('date_format(pl.created_at, "%d-%m-%Y") as created_at')
		->select('k.nama_lengkap as nama_picker')
		->select('d.nama_dealer')
		->select('so.po_type')
		->select('d.alamat')
		->select('so.id_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('
			case
				when do.sudah_revisi = 1 then concat(do.id_do_sales_order, "-REV")
				else do.id_do_sales_order
			end as id_do_sales_order
		', false)
		->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('
			case
				when pl.start_pick is null then "-"
				else date_format(pl.start_pick, "%d-%m-%Y %H:%i")
			end as start_pick
		', false)
		->select('
			case
				when pl.end_pick is null then "-"
				else date_format(pl.end_pick, "%d-%m-%Y %H:%i")
			end as end_pick
		', false)
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
		->where('pl.id_ref', $this->input->get('id_do_sales_order'))
		->get()->row_array();
		$data['picking_list'] = $picking_list;

		$qty_supply = $this->db
		->select('SUM(plp.qty_supply)')
		->from('tr_h3_md_picking_list_parts as plp')
		->where('plp.id_picking_list = pl.id_picking_list')
		->where('plp.id_part = dop.id_part')
		->get_compiled_select();

		$qty_disiapkan = $this->db
		->select('SUM(plp.qty_disiapkan)')
		->from('tr_h3_md_picking_list_parts as plp')
		->where('plp.id_picking_list = pl.id_picking_list')
		->where('plp.id_part = dop.id_part')
		->get_compiled_select();

		$parts = $this->db
		->select('dop.id_part')
		->select('p.nama_part')
		->select('sop.qty_order as qty_so')
		->select('dop.qty_supply as qty_do')
		->select("IFNULL(({$qty_supply}), 0) as qty_picking")
		->select("IFNULL(({$qty_disiapkan}), 0) as qty_disiapkan")
		->from('tr_h3_md_do_sales_order_parts as dop')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order and sop.id_part = dop.id_part)')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dop.id_do_sales_order')
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->where('dop.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->where('dop.qty_supply > 0')
		->get()->result_array();

		$parts = array_map(function($data){
			$data['qty_avs'] = $this->stock->qty_avs($data['id_part']);
			$data['service_rate'] = (floatval($data['qty_disiapkan']) / floatval($data['qty_picking']) * 100);
			return $data;
		}, $parts);

		$data['parts'] = $parts;


        $this->load->view('iframe/md/h3/h3_md_picking_list', $data);
    }

}