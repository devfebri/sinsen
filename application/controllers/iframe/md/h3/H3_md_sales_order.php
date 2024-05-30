<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_sales_order extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
	}

    public function index(){
        $data = [];
        $sales_order = $this->db
		->select('so.id_sales_order')
		->select('so.jenis_pembayaran')
		->select('so.batas_waktu')
		->select('so.created_by_md')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('d.id_dealer')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('so.po_type')
		->select('date_format(so.batas_waktu, "%d-%m-%Y") as batas_waktu')
		->select('so.kategori_po')
		->select('so.produk')
		->select('so.id_salesman')
		->select('so.tipe_source')
		->select('so.target_customer')
		->select('so.sales_order_target')
		->select('so.persentase_sales_order_target')
		->select('so.sales_order_out_target')
		->select('so.persentase_sales_order_out_target')
		->select('k.nama_lengkap as nama_salesman')
		->select('so.status')
		->from('tr_h3_md_sales_order as so')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('so.id_sales_order', $this->input->get('id_sales_order'))
		->get()->row_array();
		$data['sales_order'] = $sales_order;

		$qty_sim_part = $this->db
        ->select('jpp.qty_sim_part')
        ->from('ms_jumlah_pit_dealers as jpd')
        ->join('ms_jumlah_pit_parts as jpp', "jpd.id_jumlah_pit = jpp.id_jumlah_pit")
        ->where('jpd.id_dealer = so.id_dealer')
        ->where('jpp.id_part = sop.id_part')
        ->get_compiled_select()
        ;

        $actual_stock = $this->db
        ->select('sum(ds.stock)')
        ->from('ms_h3_dealer_stock as ds')
        ->where('ds.id_part = sop.id_part')
        ->where('ds.id_dealer = so.id_dealer')
        ->group_by('ds.id_part')
        ->get_compiled_select()
		;
		
		$stock_md = $this->db
		->select('sum(sp.qty)')
		->from('tr_stok_part as sp')
		->where('sp.id_part = sop.id_part')
		->group_by('sp.id_part')
		->get_compiled_select();

		$qty_do = $this->db
		->select('SUM(dop.qty_supply) as qty_supply', false)
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
		->where('do.id_sales_order = sop.id_sales_order', null, false)
		->where('dop.id_part = sop.id_part', null, false)
		->where('do.status !=', 'Canceled')
		->get_compiled_select();

		$qty_revisi = $this->db
		->select('SUM(dri.qty_revisi) as qty_revisi', false)
		->from('tr_h3_md_do_revisi as dr')
		->join('tr_h3_md_do_revisi_item as dri', 'dri.id_revisi = dr.id')
		->where('dr.id_do_sales_order = do.id_do_sales_order', null, false)
		->where('dri.id_part = sop.id_part', null, false)
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('sop.id_sales_order')
		->select('sop.id_part')
		->select('p.nama_part')
		->select('sop.id_tipe_kendaraan')
		->select('sop.harga')
		->select('
            concat(
                "Rp ",
                format(sop.harga, 0, "ID_id")
            )
        as harga_dealer_user')
        ->select('sop.harga')
        ->select("
            ifnull(
                ({$qty_sim_part}), 0
            )
        as qty_sim_part")
        ->select("
            ifnull(
                ({$actual_stock}), 0
            )
		as qty_actual_dealer")
		->select("
            ifnull(
                ({$stock_md}), 0
            )
        as qty_avs")
		->select("IFNULL( ({$qty_do}), 0 ) as qty_do", false)
		// ->select("IFNULL( ({$qty_revisi}), 0 ) as qty_revisi", false)
		->select('sop.qty_order')
		->select('sop.qty_pemenuhan')
		->select('IFNULL(sop.tipe_diskon, "") as tipe_diskon')
		->select('IFNULL(sop.diskon_value, "") as diskon_value')
		->select('IFNULL(sop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
		->select('IFNULL(sop.diskon_value_campaign, "") as diskon_value_campaign')
		->from('tr_h3_md_sales_order_parts as sop')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		// ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = sop.id_sales_order', 'left')
		->where('sop.id_sales_order', $this->input->get('id_sales_order'))
        ->get()->result();

        $this->load->view('iframe/md/h3/h3_md_sales_order', $data);
    }

}