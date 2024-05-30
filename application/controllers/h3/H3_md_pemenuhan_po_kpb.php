<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_pemenuhan_po_kpb extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_pemenuhan_po_kpb";
	protected $title  = "Pemenuhan PO KPB";

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');
		$this->load->model('m_admin');
		$this->load->library('upload');
		$this->load->library('form_validation');

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
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
		$this->load->model('h3_md_purchase_hotline_model', 'purchase_hotline');
		$this->load->model('h3_md_purchase_hotline_parts_model', 'purchase_hotline_parts');
		$this->load->model('h3_md_pemenuhan_po_model', 'pemenuhan_po');
		$this->load->model('h3_md_pemenuhan_po_parts_model', 'pemenuhan_po_parts');
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('stock_md_model', 'stock_md');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
		$this->load->model('H3_md_ms_tipe_po_model', 'tipe_po');
		$this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');
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
		$data['dealer_kpb'] = $this->db
			->from('ms_dealer')
			->where('tipe_plafon_h3', 'kpb')
			->limit(1)
			->get()->row_array();

		$data['purchase'] = $this->db
			->select('po.id_po_kpb')
			->select('d.id_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('date_format(po.tgl_po_kpb, "%d/%m/%Y") as tgl_po_kpb_formatted')
			->select('po.tgl_po_kpb')
			->select('so.id_sales_order')
			->select('so.tanggal_order as tanggal_so_kpb')
			->from('tr_po_kpb as po')
			->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
			->join('tr_h3_md_sales_order as so', '(so.id_po_kpb = po.id_po_kpb and so.kategori_po = "KPB" and so.status != "Canceled" and so.status != "Rejected")', 'left')
			->where('po.id_po_kpb', $this->input->get('id_po_kpb'))
			->get()->row_array();

		$this->template($data);
	}

	public function get_diskon_oli_kpb()
	{
		$parts = [];
		foreach ($this->input->post('parts') as $part) {
			$id_part = ($part['id_part_h3'] != null && $part['id_part_h3'] != '') ? $part['id_part_h3'] : $part['id_part'];
			$diskon = $this->diskon_oli_kpb->get_diskon_oli_kpb($id_part, $part['id_tipe_kendaraan']);

			if ($diskon != null) {
				$data = [];
				$data['id_part'] = $part['id_part'];
				$data['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];
				$data['tipe_diskon'] = $diskon['tipe_diskon'];
				$data['diskon_value'] = $diskon['diskon_value'];

				$parts[] = $data;
			}
		}

		send_json($parts);
	}

	public function proses()
	{
		$id_po_kpb = $this->input->get('id_po_kpb');

		$po_kpb = $this->db
			->from('tr_po_kpb')
			->where('id_po_kpb', $id_po_kpb)
			->limit(1)
			->get()->row_array();
		if ($po_kpb == null) throw new Exception('PO KPB tidak ditemukan');

		$po_kpb_parts = $this->db
			->select('pod.id_part_h3 as id_part')
			->select('pod.id_tipe_kendaraan')
			->select('SUM(pod.qty) as qty_order')
			->select('SUM(pod.qty) as qty_pemenuhan')
			->select('part_selected.harga_dealer_user as harga')
			->from('tr_po_kpb_detail as pod')
			->join('ms_part as part_selected', 'part_selected.id_part_int = pod.id_part_int')
			->where('pod.id_po_kpb', $id_po_kpb)
			->group_by('pod.id_part_h3')
			->group_by('pod.id_tipe_kendaraan')
			->get()->result_array();

		$po_kpb_parts = array_map(function($row){
			$diskon_oli_kpb = $this->diskon_oli_kpb->get_diskon_oli_kpb($row['id_part'], $row['id_tipe_kendaraan']);
            if ($diskon_oli_kpb != null) {
                $row['tipe_diskon'] = $diskon_oli_kpb['tipe_diskon'];
                $row['diskon_value'] = (double) $diskon_oli_kpb['diskon_value'];
            } else {
                $row['tipe_diskon'] = '';
                $row['diskon_value'] = 0;
            }

			$row['qty_order'] = (double) $row['qty_order'];
			$row['qty_pemenuhan'] = (double) $row['qty_pemenuhan'];
			$row['harga'] = (double) $row['harga'];

			$row['harga_setelah_diskon'] = harga_setelah_diskon($row['tipe_diskon'], $row['diskon_value'], $row['harga']);

			$row['sub_total'] = $row['qty_pemenuhan'] * $row['harga_setelah_diskon'];

			return $row;
		}, $po_kpb_parts);

		$total_amount = array_sum(array_map(function($row){
			return $row['sub_total'];
		}, $po_kpb_parts));

		$id_salesman = null;
		$dealer_kpb = $this->db
			->select('d.id_dealer')
			->from('ms_dealer as d')
			->where('d.tipe_plafon_h3', 'kpb')
			->get()->row_array();

		if ($dealer_kpb != null) {
			$this->load->model('H3_md_target_salesman_model', 'target_salesman');
			$this->target_salesman->get_target_sales_query(Mcarbon::now()->toDateString());
			$this->db->select('ts.id_salesman');
			$this->db->select('k.nama_lengkap as nama_salesman');
			$this->db->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman');
			$data = $this->db->get()->row_array();

			if ($data != null) {
				$id_salesman = $data['id_salesman'];
			}
		}

		$bulan_po = Mcarbon::parse($po_kpb['tgl_po_kpb'])->format('m');
		$sales_order = [
			'id_sales_order' => $this->sales_order->generateID('REG', $po_kpb['id_dealer']),
			'id_dealer' => $po_kpb['id_dealer'],
			'tipe_source' => 'Dealer',
			'jenis_pembayaran' => 'Credit',
			'bulan_kpb' => $bulan_po + 1,
			'id_po_kpb' => $po_kpb['id_po_kpb'],
			'po_type' => 'REG',
			'kategori_po' => 'KPB',
			'produk' => 'Oil',
			'tanggal_order' => Mcarbon::now()->toDateString(),
			'batas_waktu' => $this->tipe_po->get_batas_waktu($po_kpb['id_dealer'], 'REG'),
			'total_amount' => $total_amount,
			'type_ref' => 'purchase_order_dealer',
			'created_by_md' => 1,
			'id_salesman' => $id_salesman
		];

		$purchase_order = [
			'id_dealer' => $sales_order['id_dealer'],
			'kategori_po' => $sales_order['kategori_po'],
			'po_type' => $sales_order['po_type'],
			'batas_waktu' => $this->tipe_po->get_batas_waktu($po_kpb['id_dealer'], 'REG'),
			'total_amount' => $total_amount,
			'produk' => $sales_order['produk'],
			'po_id' => $this->purchase_order->generatePONumber('REG', $po_kpb['id_dealer']),
			'tanggal_order' => date('Y-m-d'),
			'status' => 'Processed by MD',
			'created_by_md' => $sales_order['created_by_md'],
			'id_salesman' => $id_salesman
		];

		$sales_order['id_ref'] = $purchase_order['po_id'];

		$parts = $this->getOnly([
			'id_part', 'id_tipe_kendaraan', 'qty_order', 'qty_on_hand',
			'qty_pemenuhan', 'harga', 'tipe_diskon',
			'diskon_value'
		], $po_kpb_parts, [
			'id_sales_order' => $sales_order['id_sales_order']
		]);

		$parts = array_map(function ($part) {
			$part['qty_on_hand'] = $this->stock->qty_on_hand($part['id_part']);
			return $part;
		}, $parts);

		$purchase_parts = array_map(function ($part) use ($purchase_order) {
			return [
				'po_id' => $purchase_order['po_id'],
				'id_part' => $part['id_part'],
				'id_tipe_kendaraan' => $part['id_tipe_kendaraan'],
				'harga_saat_dibeli' => $part['harga'],
				'kuantitas' => $part['qty_pemenuhan'],
				'tipe_diskon' => $part['tipe_diskon'],
				'diskon_value' => $part['diskon_value'],
				'tipe_diskon_campaign' => null,
				'diskon_value_campaign' => 0,
			];
		}, $parts);

		$this->db->trans_start();
		$this->purchase_order->insert($purchase_order);
		$this->purchase_order_parts->insert_batch($purchase_parts);

		// Create Order Parts Tracking
		foreach ($purchase_parts as $data) {
			$data = $this->get_in_array(['po_id', 'id_part', 'id_tipe_kendaraan'], $data);
			$this->order_parts_tracking->insert($data);
		}

		$this->sales_order->insert($sales_order);
		$this->sales_order_parts->insert_batch($parts);
		$this->db->trans_complete();

		$sales_order = (array) $this->sales_order->find($sales_order['id_sales_order'], 'id_sales_order');

		if ($this->db->trans_status() and $sales_order != null) {
			send_json([
				'payload' => $sales_order,
				'redirect_url' => base_url(sprintf('h3/h3_md_sales_order/detail?id=%s', $sales_order['id_sales_order']))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil proses pemenuhan KPB'
			], 422);
		}
	}

	public function reject()
	{
		$this->db->trans_start();
		$data = array(
			'rejected_at' => date('Y-m-d H:i:s', time()),
			'rejected_by' => $this->session->userdata('id_user'),
			'status' => 'Rejected by Admin H3',
			'alasan_reject' => $this->input->get('alasan_reject')
		);

		$this->db
			->where('id_po_kpb', $this->input->get('id_po_kpb'))
			->update('tr_po_kpb', $data);
		$this->db->trans_complete();

		if (!$this->db->trans_status()) {
			$this->output->set_status_header(500);
		}
	}

	public function set_id_part_h3()
	{
		$this->db->trans_start();

		$id_part_h3 = $this->input->get('id_part_h3');
		$part = $this->db
			->from('ms_part')
			->where('id_part', $id_part_h3)
			->limit(1)
			->get()->row_array();

		$this->db
			->set('id_part_h3', $part['id_part'])
			->set('id_part_h3_int', $part['id_part_int'])
			->where('id_detail', $this->input->get('id_detail'))
			->update('tr_po_kpb_detail');

		$this->db->trans_complete();
	}

	public function validation_process()
	{
		$id_po_kpb = $this->input->get('id_po_kpb');

		$data = $this->db
			->select('id_part')
			->select('id_part_int')
			->select('id_part_h3')
			->select('id_part_h3_int')
			->select('qty as qty_pemenuhan')
			->select('id_tipe_kendaraan')
			->from('tr_po_kpb_detail as pod')
			->where('pod.id_po_kpb', $id_po_kpb)
			->get()->result_array();

		$data = array_map(function ($row) {
			if ($row['id_part_h3_int'] != null and $row['id_part_h3_int'] != '') {
				$row['qty_avs'] = (int) $this->stock_int->qty_avs($row['id_part_h3_int']);
			} else {
				$row['qty_avs'] = (int) $this->stock_int->qty_avs($row['id_part_int']);
			}

			$row['qty_pemenuhan'] = intval($row['qty_pemenuhan']);

			return $row;
		}, $data);

		send_json($data);
	}
}
