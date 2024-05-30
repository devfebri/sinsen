<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_back_order extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_back_order";
	protected $title  = "Back Order";

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

		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_packing_sheet_model', 'packing_sheet');
		$this->load->model('H3_md_stock_model', 'stock');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['sales_order'] = $this->db
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('po.po_type')
		->select('so.id_sales_order')
		->select('so.batas_waktu')
		->select('so.tanggal_order')
		->select('DATEDIFF(so.batas_waktu, so.tanggal_order) as date_diff', false)
		->select('so.status')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->where('so.id_sales_order', $this->input->get('id_sales_order'))
		->get()->row();


		$qty_do = $this->sales_order_parts->qty_do('so.id_sales_order', 'sop.id_part', true);
		$parts = $this->db
		->select('sop.id_sales_order')
		->select('sop.id_part')
		->select('p.nama_part')
		->select('sop.harga_setelah_diskon as harga')
		->select('sop.qty_pemenuhan as qty_so')
		->select("({$qty_do}) as qty_supply")
		->select("(sop.qty_pemenuhan - ({$qty_do})) as qty_bo")
		->from('tr_h3_md_sales_order_parts as sop')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->where('so.id_sales_order', $this->input->get('id_sales_order'))
		->get()->result_array();

		$parts = array_map(function($data){
			$data['qty_avs'] = $this->stock->qty_avs($data['id_part']);
			$data['qty_bo'] = $data['qty_so'] - $this->sales_order_parts->qty_do($data['id_sales_order'], $data['id_part']);

			return $data;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function proses(){
		$this->db->trans_start();
		$this->sales_order->update([
			'status' => 'New SO BO',
		], $this->input->post(['id_sales_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->sales_order->get($this->input->post(['id_sales_order']), true)
			);
		}else{
			$this->set_status_header(500);
		}
	}

	public function close(){
		$this->db->trans_start();
		$this->calculate_qty_pemenuhan_hotline($this->input->post('id_sales_order'));
		
		$this->sales_order->update([
			'status' => 'Closed',
			'closed_at' => date('Y-m-d H:i:s', time()),
			'closed_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_sales_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->sales_order->get($this->input->post(['id_sales_order']), true)
			);
		}else{
			$this->set_status_header(500);
		}
	}

	public function calculate_qty_pemenuhan_hotline($id_sales_order){
		$qty_supply = $this->db
		->select('SUM(dop.qty_supply) as qty_supply')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
		->where('do.id_sales_order = sop.id_sales_order')
		->where('dop.id_part = sop.id_part')
		->get_compiled_select();
		
		$sales_order_parts = $this->db
		->select('so.po_type')
		->select('so.id_ref')
		->select('so.id_rekap_purchase_order_dealer')
		->select('sop.id_part')
		->select('sop.qty_order')
		->select("IFNULL(({$qty_supply}), 0) as qty_supply")
		->from('tr_h3_md_sales_order_parts as sop')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
		->where('sop.id_sales_order', $id_sales_order)
		->get()->result_array();

		foreach ($sales_order_parts as $part) {
			$selisih = intval($part['qty_order']) - intval($part['qty_supply']);

			if($part['id_rekap_purchase_order_dealer'] != null and $selisih > 0){
				$purchase_orders = $this->db
				->select('rpodp.po_id')
				->select('rpodp.id_part')
				->select('rpodp.kuantitas')
				->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
				->join('tr_h3_dealer_purchase_order as po', 'po.po_id = rpodp.po_id')
				->where('rpodp.id_rekap', $part['id_rekap_purchase_order_dealer'])
				->where('rpodp.id_part', $part['id_part'])
				->order_by('po.created_at', 'asc')
				->get()->result_array();

				$supply_untuk_dipecah = $selisih;
				foreach ($purchase_orders as $purchase_order) {
					if($purchase_order['kuantitas'] <= $supply_untuk_dipecah){
						$this->db
						->set('ppd.qty_so', "ppd.qty_so - {$purchase_order['kuantitas']}", false)
						->set('ppd.qty_pemenuhan', "ppd.qty_pemenuhan + {$purchase_order['kuantitas']}", false)
						->where('ppd.po_id', $purchase_order['po_id'])
						->where('ppd.id_part', $purchase_order['id_part'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');

						$supply_untuk_dipecah -= $purchase_order['kuantitas'];
					}else if($purchase_order['kuantitas'] >= $supply_untuk_dipecah){
						$this->db
						->set('ppd.qty_so', "ppd.qty_so - {$supply_untuk_dipecah}", false)
						->set('ppd.qty_pemenuhan', "ppd.qty_pemenuhan + {$supply_untuk_dipecah}", false)
						->where('ppd.po_id', $purchase_order['po_id'])
						->where('ppd.id_part', $purchase_order['id_part'])
						->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');
						break;
					}

					if($supply_untuk_dipecah == 0) break;
				}
			}else if(($part['po_type'] == 'HLO' OR $part['po_type'] == 'URG') and $part['id_ref'] != null and $part['id_rekap_purchase_order_dealer'] == null and $selisih > 0){
				$this->db
				->set('ppd.qty_so', "ppd.qty_so - {$selisih}", false)
				->set('ppd.qty_pemenuhan', "ppd.qty_pemenuhan + {$selisih}", false)
				->where('ppd.id_part', $part['id_part'])
				->where('ppd.po_id', $part['id_ref'])
				->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');

				log_message('debug', "[Back Order Distribusi][close] Mengurangi {$part['selisih']} qty SO untuk kode part {$part['id_part']} pada pemenuhan PO dealer {$part['id_ref']}");
			}
		}
	}
}