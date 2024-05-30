<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_create_do extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_stock_model', 'stock');
	}

    public function index(){
        $data = [];
        $delivery_order = $this->db
		->select('so.id_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('so.po_type')
		->select('so.produk')
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('so.jenis_pembayaran')
		->select('do.status')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('do.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->row_array();
		$plafon = $this->plafon->get_plafon($delivery_order['id_dealer']);
		$plafon_booking = $this->plafon->get_plafon_booking($delivery_order['id_dealer']);
		$delivery_order['plafon'] = $plafon;
		$delivery_order['plafon_booking'] = $plafon_booking;
		$delivery_order['sisa_plafon'] = $plafon - $plafon_booking;
		$data['delivery_order'] = $delivery_order;

		$parts = $this->db
		->select('dop.id_part')
		->select('p.nama_part')
		->select('dop.harga_jual')
		->select('dop.tipe_diskon_satuan_dealer')
		->select('dop.diskon_satuan_dealer')
		->select('dop.tipe_diskon_campaign')
		->select('dop.diskon_campaign')
		->select('sop.qty_order as qty_so')
		->select('dop.qty_supply as qty_do')
		->select('( (dop.qty_supply/sop.qty_order) * 100 ) as service_rate')
		->from('tr_h3_md_do_sales_order_parts as dop')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
		->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order and sop.id_part = dop.id_part)')
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->where('dop.id_do_sales_order', $this->input->get('id_do_sales_order'))
		->get()->result_array()
		;

		$parts = array_map(function($data){
			$data['qty_avs'] = $this->stock->qty_avs($data['id_part']);
			return $data;
		}, $parts);

		$data['parts'] = $parts;

        $this->load->view('iframe/md/h3/h3_md_create_do', $data);
    }

}