<?php

class h3_dealer_purchase_order_model extends Honda_Model
{

	protected $table = 'tr_h3_dealer_purchase_order';

	public function __construct()
	{
		parent::__construct();

		$this->load->model('m_admin');
		$this->load->library('Mcarbon');

		$this->load->model('h3_dealer_purchase_order_parts_model', 'po_dealer_parts');
	}

	public function insert($data)
	{
		if (!isset($data['id_dealer'])) {
			$data['id_dealer'] = $this->m_admin->cari_dealer();
		}

		if (isset($data['created_by_md']) and $data['created_by_md'] == 1) {
			$data['created_at'] = date('Y-m-d H:i:s', time());
			$data['approve_at'] = date('Y-m-d H:i:s', time());
			$data['approve_by'] = $this->session->userdata('id_user');
			$data['submit_at'] = date('Y-m-d H:i:s', time());
			$data['submit_by'] = $this->session->userdata('id_user');
			$data['proses_at'] = date('Y-m-d H:i:s', time());
			$data['proses_by'] = $this->session->userdata('id_user');
		}

		if (isset($data['id_booking'])) {
			$request_document = $this->db
				->select('rd.id')
				->from('tr_h3_dealer_request_document as rd')
				->where('rd.id_booking', $data['id_booking'])
				->get()->row_array();

			if ($request_document != null) $data['id_booking_int'] = $request_document['id'];
		}

		parent::insert($data);
	}

	public function set_tanggal_po_md($po_id)
	{
		$this->db
			->set('po.tanggal_po_md', Mcarbon::now()->toDateTimeString())
			->where('po.po_id', $po_id)
			->where('po.tanggal_po_md IS NULL', null, false)
			->update("{$this->table} as po");
	}

	public function set_tanggal_po_ahm($po_id)
	{
		$this->db
			->set('po.tanggal_po_ahm', Mcarbon::now()->toDateTimeString())
			->where('po.po_id', $po_id)
			->update("{$this->table} as po");
	}

	public function add_amount_supply($po_id, $amount, $referensi = null)
	{
		$purchase_order_old = $this->db
			->from($this->table)
			->where('po_id', $po_id)
			->limit(1)
			->get()->row_array();

		$this->db
			->set('po.amount_supply_md', sprintf('(po.amount_supply_md + %d)', $amount), false)
			->where('po.po_id', $po_id)
			->update(sprintf('%s as po', $this->table));

		$purchase_order = $this->db
			->from($this->table)
			->where('po_id', $po_id)
			->limit(1)
			->get()->row_array();

		$this->record_tracking_amount_supply($po_id, $amount, $referensi);

		log_message('info', sprintf('Amount supply md PO %s ditambahkan sebesar %s; dari %s menjadi %s', $po_id, rupiah_format($amount, true), rupiah_format($purchase_order_old['amount_supply_md'], true), rupiah_format($purchase_order['amount_supply_md'], true)));
	}

	private function record_tracking_amount_supply($po_id, $amount, $referensi)
	{
		$this->db->insert('tr_h3_md_tracking_amount_supply_po_dealer', [
			'po_id' => $po_id,
			'amount' => $amount,
			'referensi' => $referensi,
			'created_at' => Mcarbon::now()->toDateTimeString(),
			'created_by' => $this->session->userdata('id_user')
		]);
	}

	public function set_proses_book($po_id)
	{
		$this->db
			->set('po.status_md', 'Proses Book')
			->set('po.proses_book_md', 1)
			->where('po.proses_book_md', 0)
			->where('po.proses_md', 0)
			->where('po.proses_ahm', 0)
			->where('po.po_id', $po_id)
			->update(sprintf('%s as po', $this->table));
	}

	public function set_processed_by_md($po_id)
	{
		$this->db
			->set('po.status_md', "
			case
				when (po.proses_ahm = 1) then 'Processed by MD & AHM'
				else 'Processed by MD'
			end 
		", false)
			->set('po.proses_md', 1)
			->where('po.proses_book_md', 1)
			->where('po.proses_md', 0)
			->where('po.po_id', $po_id)
			->update(sprintf('%s as po', $this->table));
	}

	public function set_processed_by_ahm($po_id)
	{
		$this->db
			->set('po.status_md', "
			case
				when (po.proses_md = 1) then 'Processed by MD & AHM'
				else 'Processed by AHM'
			end 
		", false)
			->set('po.proses_ahm', 1)
			->where('po.proses_book_md', 1)
			->where('po.proses_ahm', 0)
			->where('po.po_id', $po_id)
			->update(sprintf('%s as po', $this->table));
	}

	public function generatePONumber($po_type = null, $id_dealer = null, $time = null)
	{
		$po_type = $po_type == null ? $this->input->post('po_type') : $po_type;
		if ($time == null) {
			$th_bln = date('Y-m');
			$th = date('Y');
			$bln = date('m');
			$thbln = date('ym');
		} else {
			$th_bln = date('Y-m', strtotime($time));
			$th = date('Y', strtotime($time));
			$bln = date('m', strtotime($time));
			$thbln = date('ym', strtotime($time));
		}

		if ($id_dealer == null) {
			if ($this->input->post('id_dealer') != null) {
				$id_dealer = $this->input->post('id_dealer');
			} else {
				$id_dealer = $this->m_admin->cari_dealer();
			}
		}
		$dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

		$get_data = $this->db
			->from($this->table)
			->where("left(tanggal_order, 4) = '{$th}'")
			->where('po_type', $po_type)
			->where('id_dealer', $id_dealer)
			->where('created_at >', '2021-06-10 21:36:07')
			->limit(1)
			->order_by('po_id', 'desc')
			->order_by('created_at', 'desc')
			->get();

		if ($get_data->num_rows() > 0) {
			$row = $get_data->row();
			$po_id = substr($row->po_id, -5);
			$new_kode = 'PO/' . strtoupper($po_type) . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.05d", $po_id + 1);
		} else {
			$new_kode = 'PO/' . strtoupper($po_type) . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/00001';
		}

		return strtoupper($new_kode);
	}

	public function update_harga($id_part_int)
	{
		$parts = $this->db
			->select('pop.id')
			->select('(pop.harga_saat_dibeli != p.harga_dealer_user) as harga_tidak_sama', false)
			->where('pop.id_part_int', $id_part_int)
			->group_start()
			->where('po.status !=', 'Rejected')
			->where('po.status !=', 'Closed')
			->group_end()
			->from(sprintf('%s as po', $this->table))
			->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id_int = po.id')
			->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
			->get()->result_array();

		if (count($parts) > 0) {
			foreach ($parts as $part) {
				if ($part['harga_tidak_sama'] == 1) $this->po_dealer_parts->update_harga($part['id']);
			}
		}
	}

	public function update_total_amount($id)
	{
		$this->db
			->select('pop.tot_harga_part')
			->where('pop.po_id_int', $id)
			->from('tr_h3_dealer_purchase_order_parts as pop');

		$total_amount = array_sum(
			array_column($this->db->get()->result_array(), 'tot_harga_part')
		);

		$updated = $this->db
			->set('po.total_amount', $total_amount)
			->where('po.id', $id)
			->update(sprintf('%s as po', $this->table));

		if ($updated) log_message('debug', sprintf('Update total amount PO dealer [%s] menjadi %s', $id, $total_amount));
	}

	public function update_diskon()
	{
		$this->load->helper('get_diskon_part');

		$this->db
			->select('po.id')
			->select('po.po_id')
			->select('po.id_dealer')
			->select('po.po_type')
			->select('po.produk')
			->select('po.kategori_po')
			->select('po.status')
			->from(sprintf('%s as po', $this->table))
			->group_start()
			->where('po.status !=', 'Rejected')
			->where('po.status !=', 'Closed')
			->group_end();

		foreach ($this->db->get()->result_array() as $row) {
			log_message('debug', sprintf('Memperbarui diskon untuk PO dealer nomor %s dengan status %s', $row['po_id'], $row['status']));

			$parts = $this->db
				->select('pop.id')
				->select('pop.id_part')
				->select('pop.id_tipe_kendaraan')
				->select('pop.tipe_diskon')
				->select('pop.diskon_value')
				->select('pop.kuantitas')
				->select('pop.tipe_diskon_campaign')
				->select('pop.diskon_value_campaign')
				->select('pop.id_campaign_diskon')
				->select('pop.jenis_diskon_campaign')
				->from('tr_h3_dealer_purchase_order_parts as pop')
				->where('pop.po_id_int', $row['id'])
				->get()->result_array();

			$parts = get_diskon_part($row['id_dealer'], $row['po_type'], $row['produk'], $row['kategori_po'], $parts);

			foreach ($parts as $part) {
				$this->db
					->set('pop.tipe_diskon', $part['tipe_diskon'])
					->set('pop.diskon_value', $part['diskon_value'])
					->set('pop.tipe_diskon_campaign', $part['tipe_diskon_campaign'])
					->set('pop.diskon_value_campaign', $part['diskon_value_campaign'])
					->set('pop.id_campaign_diskon', $part['id_campaign_diskon'])
					->set('pop.jenis_diskon_campaign', $part['jenis_diskon_campaign'])
					->where('pop.id', $part['id'])
					->update('tr_h3_dealer_purchase_order_parts as pop');
				$this->po_dealer_parts->update_harga($part['id']);
			}
		}
	}
}
