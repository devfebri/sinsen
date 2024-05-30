<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_create_do_sales_order extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_create_do_sales_order";
	protected $title  = "Create DO Sales Order";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('get_diskon_part');
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
		$this->load->model('H3_md_create_do_sales_order_parts_model', 'create_do_parts');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('H3_md_do_sales_order_cashback_model', 'do_sales_order_cashback');
		$this->load->model('H3_md_do_sales_order_gimmick_model', 'do_sales_order_gimmick');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('notifikasi_model', 'notifikasi');
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->model('H3_md_pencatatan_poin_sales_campaign_model', 'pencatatan_poin_sales_campaign');
		$this->load->model('H3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po_dari_dealer');
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

		$data['sales_order'] = $this->sales_order->get_sales_order($this->input->get('id'));
		$data['parts'] = $this->create_do_parts->get_sales_order_parts($this->input->get('id'));

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";

		$data['sales_order'] = $this->sales_order->get_sales_order($this->input->get('id'));
		$data['parts'] = $this->create_do_parts->get_sales_order_parts($this->input->get('id'));

		$this->template($data);
	}

	public function create_do()
	{
		$parts = $this->input->post('parts');

		if (count($parts) == 0) {
			send_json([
				'error_type' => 'parts_for_supply_not_available',
				'message' => 'Tidak ada part yang akan disupply.'
			], 422);
		}

		$this->db->trans_start();
		$sales_order = $this->sales_order->find($this->input->post('id_sales_order'), 'id_sales_order');

		try {
			$kuantitas_create_do = array_sum(
				array_map(function ($row) {
					return $row['qty_supply'];
				}, $parts)
			);

			$this->sales_order->check_kuantitas_do($sales_order->id, $kuantitas_create_do);
		} catch (Exception $e) {
			send_json([
				'message' => $e->getMessage()
			], $e->getCode());
		}

		$sales_order_update_data = $this->input->post(['back_order']);
		$sales_order_update_data['status'] = $this->input->post('back_order') == 1 ? 'Back Order' : 'On Process';
		// TODO: Bug karena mysql transaction timeout update sales order dan sales order parts
		$this->sales_order->update($sales_order_update_data, $this->input->post(['id_sales_order']));
		$this->sales_order_parts->update([
			'qty_suggest' => 0
		], $this->input->post(['id_sales_order']));


		$data = array_merge($this->input->post(['id_sales_order', 'diskon_additional', 'total', 'sub_total', 'gimmick']), [
			'id_do_sales_order' => $this->do_sales_order->generateID($sales_order->po_type, $sales_order->id_dealer, $sales_order->gimmick),
			'tanggal' => date('Y-m-d', time())
		]);
		$data['top'] = $this->get_top_delivery_order($sales_order);
		$data['id_sales_order_int'] = $sales_order->id;

		if ($sales_order->kategori_po != 'KPB') {
			if ($sales_order->po_type != 'HLO') {
				$parts = $this->getOnly([
					'id_part', 'qty_supply'
				], $this->input->post('parts'));
				$parts = array_map(function ($part) {
					$part['qty_order'] = $part['qty_supply'];
					unset($part['qty_supply']);
					return $part;
				}, $parts);

				$sales_campaign_poin = $this->sales_campaign->get_campaign_poin($parts);
				foreach ($sales_campaign_poin as $row) {
					$this->pencatatan_poin_sales_campaign->insert([
						'id_dealer' => $sales_order->id_dealer,
						'id_transaksi' => $data['id_do_sales_order'],
						'id_campaign' => $row['id'],
						'poin' => $row['poin_yang_didapat'],
						'nilai_insentif' => $row['nilai_insentif'],
					]);
				}

				$gimmick = $this->sales_campaign->get_gimmick_campaign($parts, $sales_order->id_dealer);
				$gimmick = array_map(function ($item) use ($data) {
					$item['id_do_sales_order'] = $data['id_do_sales_order'];
					return $item;
				}, $gimmick);

				$campaign = $this->sales_campaign->get_cashback_campaign($parts);
				$campaign_langsung = array_filter($campaign, function ($item) {
					if ($item['reward_cashback'] == 'Langsung') {
						return $item;
					}
				}, ARRAY_FILTER_USE_BOTH);

				$campaign = array_map(function ($item) use ($data) {
					$item['id_do_sales_order'] = $data['id_do_sales_order'];
					unset($item['reward_cashback']);
					return $item;
				}, $campaign);

				if (count($campaign_langsung) > 0) {
					$sum_campaign_langsung = array_sum(
						array_map(function ($data) {
							return floatval($data['cashback']);
						}, $campaign_langsung)
					);
					$data['diskon_cashback_otomatis'] = $sum_campaign_langsung;
				}

				if (count($campaign) > 0 && $this->input->post('gimmick') == 0) {
					foreach ($campaign as $row) {
						$this->do_sales_order_cashback->insert($row);
					}
				}
				if (count($gimmick) > 0 && $this->input->post('gimmick') == 0) {
					foreach ($gimmick as $row) {
						$this->do_sales_order_gimmick->insert($row);
					}
				}
			}
		}

		$do_sales_order = $this->do_sales_order->insert($data);
		$id_do_sales_order_int = $do_sales_order['id'];

		$part_keys = [
			'id_part_int', 'id_part', 'harga_jual', 'qty_supply',
			'tipe_diskon_satuan_dealer', 'diskon_satuan_dealer',
			'tipe_diskon_campaign', 'diskon_campaign', 'id_campaign_diskon'
		];
		if ($sales_order->kategori_po == 'KPB') $part_keys[] = 'id_tipe_kendaraan';

		$items = $this->getOnly($part_keys, $this->input->post('parts'), [
			'id_do_sales_order' => $data['id_do_sales_order'],
			'id_do_sales_order_int' => $id_do_sales_order_int
		]);

		$items = array_map(function ($item) {
			$item['id_diskon_campaign'] = $item['id_campaign_diskon'];
			unset($item['id_campaign_diskon']);
			return $item;
		}, $items);

		// $items = $this->get_parts_for_delivery_order($items, $sales_order);
		$items = $this->get_parts_for_delivery_order_v2($items, $sales_order);
		$this->do_sales_order_parts->insert_batch($items);

		// Update Order Parts Tracking
		foreach ($items as $item) {
			if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
				$this->order_parts_tracking->tambah_qty_book($sales_order->id_ref, $item['id_part'], $item['qty_supply'], $item['id_tipe_kendaraan']);
			} else {
				$this->order_parts_tracking->tambah_qty_book($sales_order->id_ref, $item['id_part'], $item['qty_supply']);
			}
			if ($sales_order->id_rekap_purchase_order_dealer != null) {
				$jumlah_item = $this->db
					->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
					->from('tr_h3_dealer_purchase_order_parts as pop')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
					->where('pop.po_id = po.po_id', null, false)
					->get_compiled_select();

				$purchase_urgents = $this->db
					->select('po.po_id')
					->select('pop.id_part')
					->select('(pop.kuantitas - opt.qty_book) as selisih')
					->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
					->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
					->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
					->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
					->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
					->join('tr_h3_md_rekap_purchase_order_dealer_parts as rip', '(rip.id_part = pop.id_part and rip.po_id = pop.po_id and rip.id_rekap = ri.id_rekap)')
					->where('ri.id_rekap', $sales_order->id_rekap_purchase_order_dealer)
					->order_by('jumlah_item', 'asc')
					->order_by('po.created_at', 'desc')
					->get()->result_array();

				$supply_untuk_dipecah = $item['qty_supply'];
				foreach ($purchase_urgents as $purchase_urgent) {
					if ($purchase_urgent['selisih'] <= $supply_untuk_dipecah) {
						if ($this->input->post('kategori_po') == 'KPB') {
							$this->order_parts_tracking->tambah_qty_book($purchase_urgent['po_id'], $purchase_urgent['id_part'], $purchase_urgent['selisih'], $item['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->tambah_qty_book($purchase_urgent['po_id'], $purchase_urgent['id_part'], $purchase_urgent['selisih']);
						}
						$supply_untuk_dipecah -= $purchase_urgent['selisih'];
					} else if ($purchase_urgent['selisih'] >= $supply_untuk_dipecah) {
						if ($this->input->post('kategori_po') == 'KPB') {
							$this->order_parts_tracking->tambah_qty_book($purchase_urgent['po_id'], $purchase_urgent['id_part'], $supply_untuk_dipecah, $item['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->tambah_qty_book($purchase_urgent['po_id'], $purchase_urgent['id_part'], $supply_untuk_dipecah);
						}
						break;
					}

					if ($supply_untuk_dipecah == 0) break;
				}
			}
		}
		$this->create_notif_delivery_order_created($data['id_sales_order'], $data['id_do_sales_order']);

		// $this->update_qty_do_pemenuhan_hotline($data['id_do_sales_order']);
		$this->update_qty_do_pemenuhan_hotline_v2($data['id_do_sales_order']);

		$check_po_dealer = $this->db->select('dpo.created_by_md')
									->from('tr_h3_md_sales_order as so')
									->join('tr_h3_dealer_purchase_order as dpo','dpo.po_id=so.id_ref')
									->where('so.id',$sales_order->id)->get()->row_array();
		
		if($check_po_dealer['created_by_md'] == 1 && ($this->input->post('kategori_po') == 'KPB' || $sales_order->id_rekap_purchase_order_dealer == null)){
			foreach($items as $item){
			//Update/Insert data qty booking ke stok part summary
				$check_id_part = $this->db->select('id_part_int')
										->from('tr_stok_part_summary')
										->where('id_part_int',$item['id_part_int'])->get()->row_array();
				if($check_id_part['id_part_int']!=NULL){
					$this->db->set('qty_book', "qty_book + {$item['qty_supply']}", false);
					$this->db->where('id_part_int', $item['id_part_int']);
					$this->db->update('tr_stok_part_summary');
				}else{
					$data = array(
					'id_part' => $item['id_part'],
					'id_part_int' => $item['id_part_int'],
					'qty_book' => $item['qty_supply']
					);
					$this->db->insert('tr_stok_part_summary', $data);
				}
			}
		}

		$this->db->trans_complete();

		$do_sales_order = (array) $this->do_sales_order->find($data['id_do_sales_order'], 'id_do_sales_order');
		if ($this->db->trans_status() and $do_sales_order != null) {
			send_json([
				'message' => 'Berhasil membuat DO',
				'redirect_url' => base_url(sprintf('h3/h3_md_do_sales_order_h3/detail?id=%s', $do_sales_order['id_do_sales_order']))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil membuat DO'
			], 422);
		}
	}

	public function update_qty_do_pemenuhan_hotline($id_do_sales_order)
	{
		$delivery_order_parts = $this->db
			->select('so.id_sales_order')
			->select('so.po_type')
			->select('so.id_ref')
			->select('so.id_rekap_purchase_order_dealer')
			->select('dop.id_part')
			->select('dop.qty_supply')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('dop.id_do_sales_order', $id_do_sales_order)
			->get()->result_array();

		log_message('debug', sprintf('Delivery order parts %s', print_r($delivery_order_parts, true)));

		foreach ($delivery_order_parts as $delivery_order_part) {
			if ($delivery_order_part['id_rekap_purchase_order_dealer'] != null && $delivery_order_part['id_rekap_purchase_order_dealer'] != '') {
				$list_part_rekap = $this->db
					->select('rpodp.po_id')
					->select('rpodp.id_part')
					->select('(pop.kuantitas - pemenuhan_po.qty_supply) as kuantitas_boleh_disupply', false)
					->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
					->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.po_id = rpodp.po_id AND pop.id_part = rpodp.id_part)')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as pemenuhan_po', '(pemenuhan_po.po_id = pop.po_id and pemenuhan_po.id_part = pop.id_part)')
					->where('rpodp.id_rekap', $delivery_order_part['id_rekap_purchase_order_dealer'])
					->where('rpodp.id_part', $delivery_order_part['id_part'])
					->order_by('kuantitas_boleh_disupply', 'asc')
					->having('kuantitas_boleh_disupply >', 0)
					->get()->result_array();

				log_message('debug', sprintf('List Part rekap %s', print_r($list_part_rekap, true)));

				if (count($list_part_rekap) > 0) {
					$supply_untuk_dibagi = $delivery_order_part['qty_supply'];

					foreach ($list_part_rekap as $row) {
						if ($supply_untuk_dibagi >= $row['kuantitas_boleh_disupply']) {
							$this->pemenuhan_po_dari_dealer->tambah_qty_do($row['po_id'], $row['id_part'], $row['kuantitas_boleh_disupply']);
							$this->pemenuhan_po_dari_dealer->kurangi_qty_so($row['po_id'], $row['id_part'], $row['kuantitas_boleh_disupply']);

							$supply_untuk_dibagi -= $row['kuantitas_boleh_disupply'];
						} else if ($supply_untuk_dibagi < $row['kuantitas_boleh_disupply']) {
							$this->pemenuhan_po_dari_dealer->tambah_qty_do($row['po_id'], $row['id_part'], $supply_untuk_dibagi);
							$this->pemenuhan_po_dari_dealer->kurangi_qty_so($row['po_id'], $row['id_part'], $supply_untuk_dibagi);

							break;
						}

						if ($supply_untuk_dibagi == 0) break;
					}
				}
			} elseif (($delivery_order_part['po_type'] == 'HLO' || $delivery_order_part['po_type'] == 'URG') and $delivery_order_part['id_ref'] != null and $delivery_order_part['id_ref'] != '') {
				$this->db
					->set('ppd.qty_so', "ppd.qty_so - {$delivery_order_part['qty_supply']}", false)
					->set('ppd.qty_do', "ppd.qty_do + {$delivery_order_part['qty_supply']}", false)
					->where('ppd.id_part', $delivery_order_part['id_part'])
					->where('ppd.po_id', $delivery_order_part['id_ref'])
					->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');

				log_message('debug', "[Create DO] Mengurangi {$delivery_order_part['qty_supply']} qty SO untuk kode part {$delivery_order_part['id_part']} pada pemenuhan PO dealer {$delivery_order_part['id_ref']}");
				log_message('debug', "[Create DO] Menambah {$delivery_order_part['qty_supply']} qty DO untuk kode part {$delivery_order_part['id_part']} pada pemenuhan PO dealer {$delivery_order_part['id_ref']}");
			}
		}
	}

	public function update_qty_do_pemenuhan_hotline_v2($id_do_sales_order)
	{
		$delivery_order_parts = $this->db
			->select('so.id_sales_order')
			->select('so.po_type')
			->select('so.id_ref')
			->select('so.id_rekap_purchase_order_dealer')
			->select('dop.id_part')
			->select('dop.qty_supply')
			->select('dop.id_part_int')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('dop.id_do_sales_order', $id_do_sales_order)
			->get()->result_array();

		log_message('debug', sprintf('Delivery order parts %s', print_r($delivery_order_parts, true)));

		foreach ($delivery_order_parts as $delivery_order_part) {
			if ($delivery_order_part['id_rekap_purchase_order_dealer'] != null && $delivery_order_part['id_rekap_purchase_order_dealer'] != '') {
				$list_part_rekap = $this->db
					->select('rpodp.po_id')
					->select('rpodp.id_part')
					->select('(pop.kuantitas - pemenuhan_po.qty_supply) as kuantitas_boleh_disupply', false)
					->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
					->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.po_id = rpodp.po_id AND pop.id_part = rpodp.id_part)')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as pemenuhan_po', '(pemenuhan_po.po_id = pop.po_id and pemenuhan_po.id_part = pop.id_part)')
					->where('rpodp.id_rekap', $delivery_order_part['id_rekap_purchase_order_dealer'])
					->where('rpodp.id_part', $delivery_order_part['id_part'])
					->order_by('kuantitas_boleh_disupply', 'asc')
					->having('kuantitas_boleh_disupply >', 0)
					->get()->result_array();

				log_message('debug', sprintf('List Part rekap %s', print_r($list_part_rekap, true)));

				if (count($list_part_rekap) > 0) {
					$supply_untuk_dibagi = $delivery_order_part['qty_supply'];

					foreach ($list_part_rekap as $row) {
						if ($supply_untuk_dibagi >= $row['kuantitas_boleh_disupply']) {
							$this->pemenuhan_po_dari_dealer->tambah_qty_do($row['po_id'], $row['id_part'], $row['kuantitas_boleh_disupply']);
							$this->pemenuhan_po_dari_dealer->kurangi_qty_so($row['po_id'], $row['id_part'], $row['kuantitas_boleh_disupply']);

							$supply_untuk_dibagi -= $row['kuantitas_boleh_disupply'];
						} else if ($supply_untuk_dibagi < $row['kuantitas_boleh_disupply']) {
							$this->pemenuhan_po_dari_dealer->tambah_qty_do($row['po_id'], $row['id_part'], $supply_untuk_dibagi);
							$this->pemenuhan_po_dari_dealer->kurangi_qty_so($row['po_id'], $row['id_part'], $supply_untuk_dibagi);

							break;
						}

						if ($supply_untuk_dibagi == 0) break;
					}
				}
			} elseif (($delivery_order_part['po_type'] == 'HLO' || $delivery_order_part['po_type'] == 'URG') and $delivery_order_part['id_ref'] != null and $delivery_order_part['id_ref'] != '') {
				$this->db
					->set('ppd.qty_so', "ppd.qty_so - {$delivery_order_part['qty_supply']}", false)
					->set('ppd.qty_do', "ppd.qty_do + {$delivery_order_part['qty_supply']}", false)
					// ->where('ppd.id_part', $delivery_order_part['id_part'])
					->where('ppd.id_part_int',$delivery_order_part['id_part_int'])
					->where('ppd.po_id', $delivery_order_part['id_ref'])
					->update('tr_h3_md_pemenuhan_po_dari_dealer as ppd');

				log_message('debug', "[Create DO] Mengurangi {$delivery_order_part['qty_supply']} qty SO untuk kode part {$delivery_order_part['id_part']} pada pemenuhan PO dealer {$delivery_order_part['id_ref']}");
				log_message('debug', "[Create DO] Menambah {$delivery_order_part['qty_supply']} qty DO untuk kode part {$delivery_order_part['id_part']} pada pemenuhan PO dealer {$delivery_order_part['id_ref']}");
			}
		}
	}

	private function get_parts_for_delivery_order($items, $sales_order)
	{
		$items = array_map(function ($item) {
			$item['harga_beli'] = $this->get_harga_beli_part($item['id_part']);
			$item['kuantitas'] = $item['qty_supply'];
			return $item;
		}, $items);

		$items = get_diskon_part($sales_order->id_dealer, $sales_order->po_type, $sales_order->produk, $sales_order->kategori_po, $items);

		$items = array_map(function ($item) {
			$item['tipe_diskon_satuan_dealer'] = $item['tipe_diskon_satuan_dealer'];
			$item['diskon_satuan_dealer'] = $item['diskon_satuan_dealer'];

			$item['tipe_diskon_campaign'] = $item['tipe_diskon_campaign'];
			$item['diskon_campaign'] = $item['diskon_campaign'];
			$item['id_diskon_campaign'] = $item['id_diskon_campaign'];

			unset($item['tipe_diskon']);
			unset($item['diskon_value']);
			unset($item['id_campaign_diskon']);
			unset($item['diskon_value_campaign']);
			unset($item['kuantitas']);
			return $item;
		}, $items);

		return $items;
	}

	private function get_parts_for_delivery_order_v2($items, $sales_order)
	{
		$items = array_map(function ($item) {
			$item['harga_beli'] = $this->get_harga_beli_part_v2($item['id_part_int']);
			$item['kuantitas'] = $item['qty_supply'];
			return $item;
		}, $items);

		$items = get_diskon_part($sales_order->id_dealer, $sales_order->po_type, $sales_order->produk, $sales_order->kategori_po, $items);

		$items = array_map(function ($item) {
			$item['tipe_diskon_satuan_dealer'] = $item['tipe_diskon_satuan_dealer'];
			$item['diskon_satuan_dealer'] = $item['diskon_satuan_dealer'];

			$item['tipe_diskon_campaign'] = $item['tipe_diskon_campaign'];
			$item['diskon_campaign'] = $item['diskon_campaign'];
			$item['id_diskon_campaign'] = $item['id_diskon_campaign'];

			unset($item['tipe_diskon']);
			unset($item['diskon_value']);
			unset($item['id_campaign_diskon']);
			unset($item['diskon_value_campaign']);
			unset($item['kuantitas']);
			return $item;
		}, $items);

		return $items;
	}

	private function get_harga_beli_part($id_part)
	{
		$part = $this->db
			->select('p.harga_md_dealer')
			->from('ms_part as p')
			->where('p.id_part', $id_part)
			->get()->row_array();

		if ($part != null) {
			return $part['harga_md_dealer'];
		}
		return 0;
	}

	private function get_harga_beli_part_v2($id_part)
	{
		$part = $this->db
			->select('p.harga_md_dealer')
			->from('ms_part as p')
			->where('p.id_part_int', $id_part)
			->get()->row_array();

		if ($part != null) {
			return $part['harga_md_dealer'];
		}
		return 0;
	}

	private function create_notif_delivery_order_created($id_sales_order, $id_do_sales_order)
	{
		$dealer = $this->db
			->select('d.nama_dealer')
			->from('tr_h3_md_sales_order as so')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('so.id_sales_order', $id_sales_order)
			->get()->row_array();

		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_do_created_for_finance')->get()->row_array();
		if ($menu_kategori != null) {
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. DO {$id_do_sales_order} a.n {$dealer['nama_dealer']} telah di create. Segera Approve/Reject.",
				'link' => "{$menu_kategori['link']}/detail?id={$id_do_sales_order}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}
	}

	private function get_top_delivery_order($sales_order)
	{
		$this->load->library('Mcarbon');
		$this->benchmark->mark('get_top_delivery_order_start');
		if ($sales_order->jenis_pembayaran == 'Tunai') {
			return date('Y-m-d', time());
		} else if ($sales_order->jenis_pembayaran == 'Credit') {
			if ($sales_order->gimmick == 1) {
				$settingan_top = $this->db
					->select('d.top_oli')
					->select('d.top_part')
					->from('ms_dealer as d')
					->where('d.tipe_plafon_h3', 'gimmick')
					->limit(1)
					->get()->row_array();
			} else {
				$settingan_top = $this->db
					->select('d.top_oli')
					->select('d.top_part')
					->from('ms_dealer as d')
					->where('d.id_dealer', $sales_order->id_dealer)
					->get()->row_array();
			}

			if ($sales_order->produk == 'Oil') {
				return Mcarbon::now()->addDays($settingan_top['top_oli'])->format('Y-m-d');
			} else {
				return Mcarbon::now()->addDays($settingan_top['top_part'])->format('Y-m-d');
			}
		}
		$this->benchmark->mark('get_top_delivery_order_end');

		log_message('debug', sprintf('[Create DO] waktu eksekusi function %s adalah %s', __FUNCTION__, $this->benchmark->elapsed_time('get_top_delivery_order_start', 'get_top_delivery_order_end')));
	}

	public function get_info_order()
	{
		send_json([
			'jumlah_amount_sales_order' => $this->get_amount_so($this->input->get('id_sales_order')),
			'jumlah_item_sales_order' => $this->get_item_so($this->input->get('id_sales_order')),
			'jumlah_pcs_sales_order' => $this->get_pcs_so($this->input->get('id_sales_order')),
			'jumlah_amount_delivery_order' => $this->get_amount_do($this->input->get('id_sales_order')),
			'jumlah_item_delivery_order' => $this->get_item_do($this->input->get('id_sales_order')),
			'jumlah_pcs_delivery_order' => $this->get_pcs_do($this->input->get('id_sales_order')),
		]);
	}

	private function get_amount_do($id_sales_order)
	{
		$data = $this->db
			->select('dop.id_part')
			->select('dop.qty_supply')
			->select('sop.harga')
			->select('ifnull(sop.tipe_diskon, "") as tipe_diskon_satuan_dealer')
			->select('ifnull(sop.diskon_value, 0) as diskon_satuan_dealer')
			->select('ifnull(sop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
			->select('ifnull(sop.diskon_value_campaign, 0) as diskon_campaign')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order and sop.id_part = dop.id_part)')
			->where('do.id_sales_order', $id_sales_order)
			->group_start()
			->where('do.status !=', 'Rejected')
			->where('do.status !=', 'Canceled')
			->group_end()
			->get()->result_array();

		if ($data == null) {
			return 0;
		}

		$data = array_map(function ($part) {
			$part['harga_setelah_diskon'] = $this->harga_setelah_diskon($part);
			$part['sub_total'] = $part['harga_setelah_diskon'] * $part['qty_supply'];
			return $part['sub_total'];
		}, $data);

		return array_sum($data);
	}

	private function get_amount_so($id_sales_order)
	{
		$data = $this->db
			->select('sop.id_part')
			->select('sop.qty_pemenuhan')
			->select('sop.harga')
			->select('ifnull(sop.tipe_diskon, "") as tipe_diskon_satuan_dealer')
			->select('ifnull(sop.diskon_value, 0) as diskon_satuan_dealer')
			->select('ifnull(sop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
			->select('ifnull(sop.diskon_value_campaign, 0) as diskon_campaign')
			->from('tr_h3_md_sales_order as so')
			->join('tr_h3_md_sales_order_parts as sop', 'sop.id_sales_order = so.id_sales_order')
			->where('so.id_sales_order', $id_sales_order)
			->get()->result_array();

		if ($data == null) {
			return 0;
		}

		$data = array_map(function ($part) {
			$part['harga_setelah_diskon'] = $this->harga_setelah_diskon($part);
			$part['sub_total'] = $part['harga_setelah_diskon'] * $part['qty_pemenuhan'];
			return $part['sub_total'];
		}, $data);

		return array_sum($data);
	}

	private function harga_setelah_diskon($part)
	{
		$harga_setelah_diskon = $part['harga'];
		if ($part['tipe_diskon_satuan_dealer'] == 'Rupiah') {
			$harga_setelah_diskon -= $part['diskon_satuan_dealer'];
		} else if ($part['tipe_diskon_satuan_dealer'] == 'Persen') {
			$diskon_harga = 0;
			$diskon_harga = ($part['diskon_satuan_dealer'] / 100) * $part['harga'];
			$harga_setelah_diskon -= $diskon_harga;
		}

		if ($part['tipe_diskon_campaign'] == 'Rupiah') {
			$harga_setelah_diskon -= $part['diskon_campaign'];
		} else if ($part['tipe_diskon_campaign'] == 'Persen') {
			$diskon_harga = 0;
			$diskon_harga = ($part['diskon_campaign'] / 100) * $harga_setelah_diskon;
			$harga_setelah_diskon -= $diskon_harga;
		}
		return $harga_setelah_diskon;
	}

	private function get_item_do($id_sales_order)
	{
		$data = $this->db
			->distinct()
			->select('dop.id_part')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->where('do.id_sales_order', $id_sales_order)
			->group_start()
			->where('do.status !=', 'Rejected')
			->where('do.status !=', 'Canceled')
			->group_end()
			->get()->result_array();

		if ($data == null) {
			return 0;
		}

		$data = array_map(function ($part) {
			return $part['id_part'];
		}, $data);

		return count($data);
	}

	private function get_item_so($id_sales_order)
	{
		$data = $this->db
			->distinct()
			->select('sop.id_part')
			->from('tr_h3_md_sales_order_parts as sop')
			->where('sop.id_sales_order', $id_sales_order)
			->get()->result_array();

		if ($data == null) {
			return 0;
		}

		$data = array_map(function ($part) {
			return $part['id_part'];
		}, $data);

		return count($data);
	}

	private function get_pcs_do($id_sales_order)
	{
		$data = $this->db
			->select('dop.qty_supply')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->where('do.id_sales_order', $id_sales_order)
			->group_start()
			->where('do.status !=', 'Rejected')
			->where('do.status !=', 'Canceled')
			->group_end()
			->get()->result_array();

		if ($data == null) {
			return 0;
		}

		$data = array_map(function ($part) {
			return $part['qty_supply'];
		}, $data);

		return array_sum($data);
	}

	private function get_pcs_so($id_sales_order)
	{
		$data = $this->db
			->select('sop.qty_pemenuhan')
			->from('tr_h3_md_sales_order as so')
			->join('tr_h3_md_sales_order_parts as sop', 'sop.id_sales_order = so.id_sales_order')
			->where('so.id_sales_order', $id_sales_order)
			->get()->result_array();

		if ($data == null) {
			return 0;
		}

		$data = array_map(function ($part) {
			return $part['qty_pemenuhan'];
		}, $data);

		return array_sum($data);
	}

	public function delete_from_create_do_sales_order()
	{
		$this->db->trans_start();
		$this->sales_order->update([
			'delete_at_create_do_sales_order' => 1,
			'status' => 'New SO'
		], [
			'id_sales_order' => $this->input->get('id')
		]);

		$do_sales_orders = $this->db
			->select('do.id_do_sales_order')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_sales_order', $this->input->get('id'))
			->where('do.sudah_create_faktur', 0)
			->get()->result_array();

		foreach ($do_sales_orders as $do_sales_order) {
			$this->db
				->set('do.status', 'Canceled')
				->where('do.id_do_sales_order', $do_sales_order['id_do_sales_order'])
				->update('tr_h3_md_do_sales_order as do');
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Sales Order berhasil di hapus dari Create DO Sales Order');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/'>";
		} else {
			$this->session->set_flashdata('pesan', 'Sales Order tidak berhasil di hapus dari Create DO Sales Order');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page'>";
		}
	}

	public function get_list_delivery_orders()
	{
		$result = $this->db
			->select('do.id_do_sales_order')
			->select('do.status')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_sales_order', $this->input->get('id_sales_order'))
			->get()->result_array();

		send_json($result);
	}

	public function get_paket_bundling()
	{
		$data = $this->db
			->select('pbd.id_part')
			->select('pbd.qty_part')
			->from('ms_paket_bundling_detail as pbd')
			->where('pbd.id_paket_bundling', $this->input->get('id_paket_bundling'))
			->get()->result_array();

		send_json($data);
	}

	public function check_qty_booking()
	{
		$part = $this->input->get('part');
		$idPart = $part['id_part'];
		$idPartInt = $part['id_part_int'];
		$cek_rekap_po = $this->db->select('id_rekap_purchase_order_dealer')
								->select('id_ref')
								->from('tr_h3_md_sales_order')
								->where('id_sales_order',$this->input->get('id_sales_order'))
								->get()->row_array();
		if($cek_rekap_po['id_rekap_purchase_order_dealer'] == null ||$cek_rekap_po['id_rekap_purchase_order_dealer'] == 0 || $cek_rekap_po['id_rekap_purchase_order_dealer'] == ''){
			$po_id = $cek_rekap_po['id_ref'];
		}else{
			$no_po = $this->db->select('rpdp.po_id as id_referensi')
								->from('tr_h3_md_rekap_purchase_order_dealer_parts rpdp')
								->where('rpdp.id_rekap',$cek_rekap_po['id_rekap_purchase_order_dealer'])
								->where('rpdp.id_part',$idPart)
								->get()->row_array();
			$po_id = $no_po['id_referensi'];
		}
		

		$data = $this->db
		->select('(ppdd.qty_so + ppdd.qty_pemenuhan) as kuantitas')
		->from('tr_h3_md_pemenuhan_po_dari_dealer as ppdd')
		->join('tr_h3_dealer_purchase_order as po','po.po_id = ppdd.po_id')
		->where('ppdd.id_part', $idPartInt)
		->where('ppdd.po_id', $po_id)
		->get()->row_array();

		send_json($data);
	}

	public function get_create_do_user()
	{
		$user = $this->db
			->select('so.id_salesman')
			->from('tr_h3_md_sales_order as so')
			->where('so.id_sales_order', $this->input->get('id_sales_order'))
			->get()->row_array();

		$data = $this->db
			->select('count(do.id) as do')
			->from('tr_h3_md_sales_order as so')
			->join('tr_h3_md_do_sales_order as do','do.id_sales_order = so.id_sales_order')
			->where('so.id_salesman', $user['id_salesman'])
			->where_not_in('do.status', array('Create Faktur','Rejected','Packing Sheet','Canceled','Shipping List'))
			->get()->row_array();


		send_json($data);
	}

	public function get_create_do_waktu()
	{
		$user = $this->db
			->select('so.id_salesman')
			->from('tr_h3_md_sales_order as so')
			->where('so.id_sales_order', $this->input->get('id_sales_order'))
			->get()->row_array();

		$data = $this->db
			->select('count(do.id) as do')
			->from('tr_h3_md_sales_order as so')
			->join('tr_h3_md_do_sales_order as do','do.id_sales_order = so.id_sales_order')
			->where('so.id_salesman', $user['id_salesman'])
			->where('do.tanggal <= date_add(curdate(), interval -2 day)', '', false)
			->where_not_in('do.status', array('Create Faktur','Rejected','Packing Sheet','Canceled','Shipping List'))
			->get()->row_array();


		send_json($data);
	}
}
