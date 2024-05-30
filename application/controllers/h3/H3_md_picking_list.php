<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_picking_list extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_picking_list";
    protected $title  = "Picking List";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_so_other_model', 'so_other');
		$this->load->model('h3_md_so_other_parts_model', 'so_other_parts');
		$this->load->model('h3_md_do_other_model', 'do_other');
		$this->load->model('h3_md_do_other_parts_model', 'do_other_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('karyawan_md_model', 'karyawan_md');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$this->page = 'h3_md_picking_list';
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['picking'] = $this->db
		->select('pl.id_picking_list')
		->select('date_format(pl.created_at, "%d-%m-%Y") as tanggal_picking')
		->select('po.po_type')
		->select('so.id_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('date_format(do.created_at, "%d-%m-%Y") as tanggal_do')
		->select('k.nama_lengkap as nama_picker')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('do.id_do_sales_order')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
		->where('pl.id_picking_list', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['parts'] = $this->db
		->select('plp.id_part')
		->select('p.nama_part')
		->select('dop.qty_supply as qty_do')
		->select('0 as qty_avs')
		->select('SUM(plp.qty_supply) as qty_picking')
		->select('SUM(plp.qty_disiapkan) as qty_disiapkan')
		->select('plp.recheck')
		->from('tr_h3_md_picking_list_parts as plp')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = plp.id_part and pl.id_ref = dop.id_do_sales_order)')
		->join('ms_part as p', 'p.id_part = plp.id_part')
		->where('plp.id_picking_list', $this->input->get('id'))
		->group_by('plp.id_part')
		->get()->result();

		$this->template($data);
	}
}