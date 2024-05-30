<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_po_dealer_lain extends Honda_Controller {
	var $folder = "dealer";
	var $page   = "h3_dealer_po_dealer_lain";
	var $title  = "Purchase Order Dari Dealer lain";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');		
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('dealer_model', 'dealer');		
		$this->load->model('ms_part_model', 'ms_part');		
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";

		$this->template($data);	
	}

	public function add()
	{
		$data['kode_md'] = 'E22';
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['part_group'] = $this->db->distinct()->select('kelompok_part')->from('ms_part')->get()->result();
		$data['dealer_terdekat'] = $this->dealer->dealer_terdekat();
		$data['dealer'] = $this->db->from('ms_dealer')->where('id_dealer', $this->m_admin->cari_dealer())->get()->row();
		$this->template($data);	
	}

	public function detail()
	{				
		$data['isi']   = $this->page;		
		$data['title'] = 'Purchase Order';		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$data['dealer'] = $this->db->from('ms_dealer')->where('id_dealer', $this->m_admin->cari_dealer())->get()->row();
		$purchase_order = $this->db
		->select('po.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('ifnull(po.tanggal_selesai, "Belum Selesai") as tanggal_selesai')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->where('po.po_id', $this->input->get('id'))
		->get()->row()
		;

		$data['parts'] = $this->db
		->select('pop.*')
		->select('p.nama_part')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $purchase_order->po_id)
		->get()->result();

		$data['purchase_order'] = $purchase_order;
		$this->template($data);
	}

	public function reject(){
		$this->db->trans_start();
		$this->purchase_order->update([
			'status' => 'Rejected',
			'keterangan' => $this->input->post('keterangan')
		], [
			'po_id' => $this->input->post('po_id')
		]);
		$this->db->trans_complete();
		if($this->db->trans_status()){
			$result = $this->purchase_order->get($this->input->post(['po_id']), true);
			send_json($result);
		}else{
		  $this->output->set_status_header(500);
		}	
	}
}