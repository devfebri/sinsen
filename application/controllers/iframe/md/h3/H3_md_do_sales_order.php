<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_do_sales_order extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_ar_part_model', 'ar_part');
	}

    public function index(){
        $data = [];
        $do_sales_order = $this->db
		->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('so.id_dealer')
		->select('dso.id_do_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('so.id_sales_order')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('dso.top')
		->select('so.po_type')
		->select('dso.status')
		->select('dso.diskon_additional')
		->select('dso.check_diskon_insentif')
		->select('dso.diskon_insentif')
		->select('dso.check_diskon_cashback')
		->select('dso.diskon_cashback')
		->select('dso.diskon_cashback_otomatis')
		->select('dso.alasan_reject')
		->select('so.id_dealer')
		->select('so.id_salesman')
		->select('k.nama_lengkap as nama_salesman')
		->from('tr_h3_md_do_sales_order as dso')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
		->where('dso.id_do_sales_order', $this->input->get('id'))
		->limit(1)
		->get()->row_array();
		$plafon = $this->plafon->get_plafon($do_sales_order['id_dealer']);
		$plafon_booking = $this->plafon->get_plafon_booking($do_sales_order['id_dealer']);
		$do_sales_order['plafon_booking'] = $plafon_booking;
		$do_sales_order['plafon'] = $plafon;
		$data['do_sales_order'] = $do_sales_order;

		$data['monitoring_piutang'] = $this->ar_part->piutang_dealer($data['do_sales_order']['id_dealer']);

		$this->db
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
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_part as p', 'p.id_part = dsop.id_part')
		->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
		->where('dsop.id_do_sales_order', $this->input->get('id'));

		if($do_sales_order['kategori_po'] == 'KPB'){
			$this->db->select('dsop.id_tipe_kendaraan');
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part and sop.id_tipe_kendaraan = dsop.id_tipe_kendaraan)');
		}else{
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part)');
		}

		$data['do_sales_order_parts'] = $this->db->get()->result();

        $this->load->view('iframe/md/h3/h3_md_do_sales_order', $data);
    }

}