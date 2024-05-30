<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_pemenuhan_po_urgent_dealer extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_pemenuhan_po_urgent_dealer";
	protected $title  = "Pemenuhan Purchase Urgent Dealer";

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
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('h3_md_purchase_hotline_model', 'purchase_hotline');
		$this->load->model('h3_md_purchase_hotline_parts_model', 'purchase_hotline_parts');
		$this->load->model('h3_md_pemenuhan_po_model', 'pemenuhan_po');
		$this->load->model('h3_md_pemenuhan_po_parts_model', 'pemenuhan_po_parts');
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('stock_md_model', 'stock_md');
		$this->load->model('H3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po_dari_dealer');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$id = $this->input->get('id');

		$purchase_order = $this->db
			->select('po.*')
			->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->from('tr_h3_dealer_purchase_order as po')
			->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
			->where('po.po_id', $id)
			->limit(1)
			->get()->row_array();

		if ($purchase_order == null) throw new Exception('Purchase order dealer tidak ditemukan');

		$qty_avs = $this->stock_int->qty_avs('pop.id_part_int', [$purchase_order['id']], true, true);
		$qty_booking = $this->stock_int->qty_booking('pop.id_part_int', [], true);

		$qty_po_urgent = $this->db
			->select('SUM(pop_md.qty_order) as qty_order')
			->from('tr_h3_md_purchase_order as po_md')
			->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
			->where('pop_md.referensi = pop.po_id', null, false)
			->where('pop_md.id_part = pop.id_part', null, false)
			->where_in('po_md.status', array('Approved','Closed'))
			->where('po_md.jenis_po', 'URG')
			->get_compiled_select();

		$qty_penerimaan = $this->db
			->select('SUM(pbi.qty_diterima) as qty_diterima', false)
			->from('tr_h3_md_purchase_order as po_md')
			->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
			->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.no_po = po_md.id_purchase_order AND pbi.id_part = pop_md.id_part)')
			->where('pop_md.referensi = pop.po_id', null, false)
			->where('pop_md.id_part = pop.id_part', null, false)
			->get_compiled_select();

		$this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.kuantitas as qty_order')
			->select("( IFNULL(({$qty_po_urgent}), 0) - IFNULL(({$qty_penerimaan}), 0) ) as qty_po")
			->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
			->select("ppdd.qty_do as qty_fulfillment")
			->select("IFNULL(ppdd.qty_supply, 0) as qty_supply")
			->select('ifnull(ppdd.qty_so, 0) as qty_so')
			->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
			->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
			->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
			->select("ifnull( ({$qty_avs}), 0) as qty_avs")
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('pop.po_id', $id);

		$data['parts'] = array_map(function ($row) {
			$row['qty_belum_terpenuhi'] = $row['qty_order'] - $row['qty_fulfillment'] - $row['qty_po'] - $row['qty_supply'] - $row['qty_so'];
			return $row;
		}, $this->db->get()->result_array());

		$data['purchase'] = $purchase_order;


		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$id = $this->input->get('id');

		$purchase_order = $this->db
			->select('po.*')
			->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->from('tr_h3_dealer_purchase_order as po')
			->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
			->where('po.po_id', $id)
			->limit(1)
			->get()->row_array();

		if ($purchase_order == null) throw new Exception('Purchase order dealer tidak ditemukan');

		$qty_avs = $this->stock_int->qty_avs('pop.id_part_int', [$purchase_order['id']], true, true);

		$qty_po_urgent = $this->db
			->select('SUM(pop_md.qty_order) as qty_order')
			->from('tr_h3_md_purchase_order as po_md')
			->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
			->where('pop_md.referensi = pop.po_id')
			->where('pop_md.id_part = pop.id_part')
			->where_in('po_md.status', array('Approved','Closed'))
			->where('po_md.jenis_po', 'URG')
			->get_compiled_select();

		$qty_penerimaan = $this->db
			->select('SUM(pbi.qty_diterima) as qty_diterima', false)
			->from('tr_h3_md_purchase_order as po_md')
			->join('tr_h3_md_purchase_order_parts as pop_md', 'pop_md.id_purchase_order = po_md.id_purchase_order')
			->join('tr_h3_md_penerimaan_barang_items as pbi', '(pbi.no_po = po_md.id_purchase_order AND pbi.id_part = pop_md.id_part)')
			->where('pop_md.referensi = pop.po_id', null, false)
			->where('pop_md.id_part = pop.id_part', null, false)
			->get_compiled_select();

		$this->db
			->select('pop.id_part')
			->select('p.nama_part')
			->select('pop.harga_saat_dibeli as harga')
			->select('pop.kuantitas as qty_order')
			->select("( IFNULL(({$qty_po_urgent}), 0) - IFNULL(({$qty_penerimaan}), 0) ) as qty_po")
			->select("IFNULL(({$qty_penerimaan}), 0) as qty_penerimaan")
			->select("ppdd.qty_do as qty_fulfillment")
			->select("ppdd.qty_supply")
			->select('ifnull(ppdd.qty_so, 0) as qty_so')
			->select('ifnull(ppdd.qty_pemenuhan, 0) as qty_pemenuhan')
			->select('ifnull(ppdd.qty_hotline, 0) as qty_hotline')
			->select('ifnull(ppdd.qty_urgent, 0) as qty_urgent')
			->select("ifnull( ({$qty_avs}), 0) as qty_avs")
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', '(ppdd.id_part = pop.id_part and ppdd.po_id = pop.po_id)', 'left')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('pop.po_id', $id);

		$data['parts'] = array_map(function ($row) {
			$row['qty_belum_terpenuhi'] = $row['qty_order'] - $row['qty_fulfillment'] - $row['qty_po'] - $row['qty_supply'] - $row['qty_so'];
			return $row;
		}, $this->db->get()->result_array());

		$data['purchase'] = $purchase_order;

		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();
		foreach ($this->input->post('parts') as $row) {
			$condition = [
				'po_id' => $this->input->post('po_id'),
				'id_part' => $row['id_part'],
			];

			$pemenuhan = $this->pemenuhan_po_dari_dealer->get($condition, true);

			if ($pemenuhan != null) {
				$this->pemenuhan_po_dari_dealer->update([
					'qty_pemenuhan' => $row['qty_pemenuhan'],
					'qty_hotline' => $row['qty_hotline'],
					'qty_urgent' => $row['qty_urgent'],
				], $condition);
			}
		}

		$this->purchase_order->set_tanggal_po_md($this->input->post('po_id'));
		$this->purchase_order->set_proses_book($this->input->post('po_id'));

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$purchase_order = $this->purchase_order->find($this->input->post('po_id'), 'po_id');
			send_json($purchase_order);
		} else {
			$this->output->set_status_header(400);
		}
	}
}
