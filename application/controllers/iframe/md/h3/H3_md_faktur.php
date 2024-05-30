<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_faktur extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_stock_model', 'stock');
	}

    public function index(){
		$data = [];
		
		$faktur = $this->db
		->select('
			case
				when do.sudah_revisi = 1 then concat(do.id_do_sales_order, "-REV")
				else do.id_do_sales_order
			end as id_do_sales_order
		', false)
		->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('so.id_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('d.alamat')
		->select('
		case
			when so.jenis_pembayaran = "Tunai" then date_format(so.tanggal_order, "%d/%m/%Y")
			when so.jenis_pembayaran = "Credit" then 
				case
					when so.produk = "Oil" then date_format(
						date_add(do.tanggal, interval d.top_oli day),
						"%d-%m-%Y"
					)
					else date_format(
						date_add(do.tanggal, interval d.top_part day),
						"%d-%m-%Y"
					)
				end 
		end as top', false)
		->select('k.nama_lengkap as nama_salesman')
		->select('so.po_type')
		->select('so.kategori_po')
		->select('do.status')
		->select('do.sudah_revisi')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
		->where('do.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->row_array();
		$plafon = $this->plafon->get_plafon($faktur['id_dealer']);
		$plafon_booking = $this->plafon->get_plafon_booking($faktur['id_dealer']);
		$faktur['plafon_booking'] = $plafon_booking;
		$faktur['plafon'] = $plafon;
		$faktur['sisa_plafon'] = $plafon - $plafon_booking;
		$data['faktur'] = $faktur;

		$qty_scan = $this->db
		->select('SUM(splp.qty_scan)')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_scan_picking_list_parts as splp', 'splp.id_picking_list = pl.id_picking_list')
		->where('pl.id_ref = dop.id_do_sales_order')
		->where('splp.id_part = dop.id_part')
		->get_compiled_select();

		$qty_revisi = $this->db
		->select('SUM(dri.qty_revisi)')
		->from('tr_h3_md_do_revisi as dr')
		->join('tr_h3_md_do_revisi_item as dri', 'dri.id_revisi = dr.id')
		->where('dr.id_do_sales_order = dop.id_do_sales_order')
		->where('dri.id_part = dop.id_part')
		->where('dr.status', 'Open')
		->get_compiled_select();

		$parts = $this->db
		->select('dop.id_part')
		->select('p.nama_part')
		->select('IFNULL(kp.include_ppn, 0) as include_ppn')
		->select('dop.harga_jual')
		->select('dop.harga_beli')
		->select('dop.qty_supply as qty_do')
		->select("IFNULL( ({$qty_revisi}), 0 )  as qty_revisi", false)
		->select("IFNULL( ({$qty_scan}), 0 )  as qty_scan", false)
		->select("IFNULL( ({$qty_scan}), 0 )  as qty_faktur", false)
		->select('dop.tipe_diskon_campaign')
		->select('dop.diskon_campaign')
		->select('dop.tipe_diskon_satuan_dealer')
		->select('dop.diskon_satuan_dealer')
		->select('(sc.jenis_diskon_campaign = "Additional") as additional_discount', false)
		->from('tr_h3_md_do_sales_order_parts as dop')
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
		->join('ms_h3_md_sales_campaign as sc', '(sc.id = dop.id_diskon_campaign AND sc.jenis_reward_diskon = 1)', 'left')
		->where('dop.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->result_array();

		// $parts = array_map(function($data){
		// 	$data['service_rate'] = (floatval($data['qty_scan']) / floatval($data['qty_picking']) * 100);
		// 	return $data;
		// }, $parts);

		$data['parts'] = $parts;

        $this->load->view('iframe/md/h3/h3_md_faktur', $data);
    }

}