<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitoring_supply extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitoring_supply";
    protected $title  = "Monitoring Supply";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('m_admin');		
		//===== Load Library =====
		
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();

		// if($name=="" OR $auth=='false')
		// {
		// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		// }elseif($sess=='false'){
		// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		// }
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function get_sales_order(){
		$data = $this->db
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
		->select("date_format(so.batas_waktu, '%d-%m-%Y') as batas_waktu")
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
		->from('tr_h3_md_sales_order as so')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer', 'left')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->limit(1)
		->get()->row_array()
		;
		send_json($data);
	}

	public function get_sales_order_parts(){
		$qty_sim_part = $this->db
        ->select('jpp.qty_sim_part')
        ->from('ms_jumlah_pit_dealers as jpd')
        ->join('ms_jumlah_pit_parts as jpp', "jpd.id_jumlah_pit = jpp.id_jumlah_pit")
        ->where('jpd.id_dealer', $this->input->post('id_dealer'))
        ->where('jpp.id_part = sop.id_part')
        ->get_compiled_select()
        ;

        $actual_stock = $this->db
        ->select('sum(ds.stock)')
        ->from('ms_h3_dealer_stock as ds')
        ->where('ds.id_part = sop.id_part')
        ->where('ds.id_dealer', $this->input->post('id_dealer'))
        ->group_by('ds.id_part')
        ->get_compiled_select()
		;
		
		$stock_md = $this->db
		->select('sum(sp.qty)')
		->from('tr_stok_part as sp')
		->where('sp.id_part = sop.id_part')
		->group_by('sp.id_part')
		->get_compiled_select();


		$data = $this->db
		->select('sop.id_part')
		->select('p.nama_part')
		->select('sop.harga')
		->select('
            concat(
                "Rp ",
                format(p.harga_dealer_user, 0, "ID_id")
            )
        as harga_dealer_user')
        ->select('p.harga_dealer_user as harga')
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
		->select('sop.qty_order')
		->select('sop.qty_pemenuhan')
		->select('ifnull(sop.tipe_diskon, "") as tipe_diskon')
		->select('ifnull(sop.diskon_value, "") as diskon_value')
		->select('ifnull(sop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
		->select('ifnull(sop.diskon_value_campaign, "") as diskon_value_campaign')
		->from('tr_h3_md_sales_order_parts as sop')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->result();

		send_json($data);
	}

	public function get_rincian_proses(){
		$data = [];
		$data['parts'] = $this->db
		->select('sop.id_part')
		->select('p.nama_part')
		->select('sop.qty_order')
		->select('dop.qty_supply')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = sop.id_part and do.id_sales_order = so.id_sales_order)')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->result_array()
		;

		send_json($data);
	}

	public function get_rincian_picking(){
		$data = [];
		$data['parts'] = $this->db
		->select('sop.id_part')
		->select('p.nama_part')
		->select('plp.qty_disiapkan')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = sop.id_part and do.id_sales_order = so.id_sales_order)')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_picking_list_parts as plp', '(pl.id_picking_list = plp.id_picking_list and plp.id_part = sop.id_part)')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->result_array()
		;

		send_json($data);
	}

	public function get_rincian_scan(){
		$data = [];
		
		$scan_parts = $this->db
		->select('sum(splp.qty_scan)')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_part = sop.id_part')
		->where('splp.id_picking_list = pl.id_picking_list')
		->group_by('splp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('sop.id_part')
		->select('p.nama_part')
		->select("({$scan_parts}) as qty_scan")
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = sop.id_part and do.id_sales_order = so.id_sales_order)')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_picking_list_parts as plp', '(pl.id_picking_list = plp.id_picking_list and plp.id_part = sop.id_part)')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->result_array()
		;
		send_json($data);
	}

	public function get_rincian_faktur(){
		$data = [];

		$data['faktur'] = $this->db
		->select('ps.tgl_faktur')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->row_array()
		;
		send_json($data);
	}

	public function get_rincian_packing(){
		$data = [];
		
		$scan_parts = $this->db
		->select('sum(splp.qty_scan)')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_part = sop.id_part')
		->where('splp.id_picking_list = pl.id_picking_list')
		->group_by('splp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('sop.id_part')
		->select('p.nama_part')
		->select("({$scan_parts}) as qty_scan")
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = sop.id_part and do.id_sales_order = so.id_sales_order)')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_picking_list_parts as plp', '(pl.id_picking_list = plp.id_picking_list and plp.id_part = sop.id_part)')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->result_array()
		;
		send_json($data);
	}

	public function get_rincian_shipping(){
		$data = [];

		$data['shipping'] = $this->db
		->select('')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet')
		->join('tr_h3_md_surat_pengantar as sp', 'spi.id_surat_pengantar = sp.id_surat_pengantar')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->row_array();
		
		$scan_parts = $this->db
		->select('sum(splp.qty_scan)')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_part = sop.id_part')
		->where('splp.id_picking_list = pl.id_picking_list')
		->group_by('splp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('sop.id_part')
		->select('p.nama_part')
		->select("({$scan_parts}) as qty_scan")
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = sop.id_part and do.id_sales_order = so.id_sales_order)')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
		->join('tr_h3_md_picking_list_parts as plp', '(pl.id_picking_list = plp.id_picking_list and plp.id_part = sop.id_part)')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->where('so.id_ref', $this->input->post('id_rincian_po'))
		->get()->result_array()
		;
		send_json($data);
	}
}