<?php

class h3_md_purchase_order_model extends Honda_Model
{

	protected $table = 'tr_h3_md_purchase_order';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_admin');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('h3_md_purchase_order_parts_model', 'po_parts');
	}

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Waiting Approval';

		parent::insert($data);
	}

	public function generateID($jenis_po, $produk = null, $bulan_po = null)
	{
		$tahun = date('Y');
		// $bulan = date('m');
		if($jenis_po == 'REG'){
			$bulan_po = substr($bulan_po,5,2);
			$bulan = $bulan_po;
		}else{
			$bulan = date('m');
		}
		$tahun_bulan = date('Y');

		$data = $this->db
			->select('id_purchase_order')
			->from($this->table)
			->where('jenis_po', $jenis_po)
			->where("LEFT(tanggal_po,4)='{$tahun}'", null, false)
			->order_by('id_purchase_order', 'desc')
			->order_by('created_at', 'desc')
			->where('created_at >', '2021-05-06 12:03:00')
			->get();

		if ($data->num_rows() > 0) {
			$row = $data->row();
			$id_purchase_order = substr($row->id_purchase_order, 0, 5);
			$new_kode = sprintf("%'.05d", $id_purchase_order + 1) . '/' . $jenis_po . '-' . 'E20' . '/' . $bulan . '/' . $tahun;
		} else {
			$new_kode = '00001/' . $jenis_po . '-' . 'E20' . '/' . $bulan . '/' . $tahun;
		}

		if ($jenis_po == 'REG') {
			if ($produk == 'Parts') {
				$new_kode .= '/HGP';
			} else if ($produk == 'Oil') {
				$new_kode .= '/OIL';
			} else if ($produk == 'Acc') {
				$new_kode .= '/HGA';
			}else if ($produk == 'Apparel') {
				$new_kode .= '/APP';
			}
		}

		return strtoupper($new_kode);
	}

	public function update_harga($id_part_int){
		$this->db
		->select('pop.id')
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('tr_h3_md_purchase_order as po', 'po.id = pop.id_purchase_order_int')
		->where('pop.id_part_int', $id_part_int)
		->where('po.status != ', 'Approved');

		foreach($this->db->get()->result_array() as $part){
			$this->po_parts->update_harga($part['id']);
		}
	}

	public function update_total_amount($id){
		$parts = $this->db
		->select('(pop.qty_order * pop.harga) as amount', false)
		->from('tr_h3_md_purchase_order_parts as pop')
		->where('pop.id_purchase_order_int', $id)
		->get()->result_array();

		$total_amount = array_sum(
			array_column($parts, 'amount')
		);

		$this->db
		->set('po.total_amount', $total_amount)
		->where('po.id', $id)
		->update(sprintf('%s as po', $this->table));

		log_message('debug', sprintf('Update total amount PO MD menjadi %s [%s]', $total_amount, $id));
	}
}
