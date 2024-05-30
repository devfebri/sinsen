<?php

class h3_md_do_sales_order_parts_model extends Honda_Model{

	protected $table = 'tr_h3_md_do_sales_order_parts';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');

		$this->load->model('H3_md_do_sales_order_model', 'delivery_order');
	}

	public function insert($data){
		if(!isset($data['id_part_int']) AND isset($data['id_part'])){
			$part = $this->db
			->select('p.id_part_int')
			->from('ms_part as p')
			->where('p.id_part', $data['id_part'])
			->get()->row_array();

			if($part != null) $data['id_part_int'] = $part['id_part_int'];
		}

		parent::insert($data);
	}

    public function get_do_sales_order_parts($id_do_sales_order){
		$do_sales_order = $this->db
		->select('so.kategori_po')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->where('do.id_do_sales_order', $id_do_sales_order)
		->get()->row_array();

        $this->db
		->select('dsop.id_do_sales_order')
		->select('dsop.id_part')
		->select('p.id_part_int')
		->select('p.nama_part')
		->select('dsop.id_tipe_kendaraan')
		->select('p.qty_dus')
		->select('dsop.qty_supply')
		->select('sop.qty_order')
		->select('dsop.tipe_diskon_satuan_dealer')
		->select('dsop.diskon_satuan_dealer')
		->select('dsop.tipe_diskon_campaign')
		->select('dsop.diskon_campaign')
		->select('dsop.harga_jual')
		->select('dsop.harga_beli')
		->select('so.po_type')
		->select('so.produk')
		->select('so.id_dealer')
		->select('so.gimmick')
		->select('sop.id_campaign_diskon')
		->select('sc.jenis_diskon_campaign')
		->from('tr_h3_md_do_sales_order_parts as dsop')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dsop.id_do_sales_order')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_part as p', 'p.id_part = dsop.id_part')
		->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
		->where('dsop.id_do_sales_order', $id_do_sales_order)
		->where('dsop.qty_supply >', 0)
		->order_by('dsop.id_part', 'asc');

		if($do_sales_order['kategori_po'] == 'KPB'){
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part and sop.id_tipe_kendaraan = dsop.id_tipe_kendaraan)');
		}else{
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part)');
		}

		$this->db->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left');

		$parts = $this->db->get()->result_array();

		$jumlah_dus = $this->get_jumlah_dus($parts);
		$parts = array_map(function($data) use ($jumlah_dus, $do_sales_order){
			$data['harga_setelah_diskon'] = $this->harga_setelah_diskon($data);
			$data['amount'] = $this->amount($data);
			unset($data['qty_dus']);

			return $data;
		}, $parts);
        
        return $parts;
	}
	
	public function get_jumlah_dus($parts, $key = 'qty_supply'){
		$total_dus = 0;
		foreach ($parts as $part) {
			$total_dus += $part[$key] / $part['qty_dus'];
		}

		return floor($total_dus);
	}

	public function harga_setelah_diskon($part){
		$harga_setelah_diskon = $part['harga_jual'];
		$harga_setelah_diskon = $harga_setelah_diskon - $this->calculate_discount($part['diskon_satuan_dealer'], $part['tipe_diskon_satuan_dealer'], $part['harga_jual']);

		if(isset($part['jenis_diskon_campaign']) AND $part['jenis_diskon_campaign'] == 'Non Additional'){
			$harga_setelah_diskon = $harga_setelah_diskon - $this->calculate_discount($part['diskon_campaign'], $part['tipe_diskon_campaign'], $part['harga_jual']);
		}else{
			$harga_setelah_diskon = $harga_setelah_diskon - $this->calculate_discount($part['diskon_campaign'], $part['tipe_diskon_campaign'], $harga_setelah_diskon);
		}

		return $harga_setelah_diskon;
	}

	public function calculate_discount($discount, $tipe_diskon, $price){
		if($tipe_diskon == 'Persen'){
			if($discount == 0) return 0; 

			return $discount = ($discount/100) * $price;
		  }else if($tipe_diskon == 'Rupiah'){
			return $discount;
		  }
		  return 0;
	}

	public function amount($part, $key = 'qty_supply'){
		return $this->harga_setelah_diskon($part) * $part[$key];
	}

	public function update_harga($id){
		$data = $this->db
		->select('dop.id')
		->select('dop.id_do_sales_order')
		->select('dop.id_part_int')
		->select('dop.id_part')
		->select('dop.qty_supply')
		->select('dop.harga_jual')
		->select('p.harga_dealer_user as harga_jual_terakhir')
		->select('dop.harga_beli')
		->select('p.harga_md_dealer as harga_beli_terakhir')
		->select('dop.tipe_diskon_satuan_dealer')
		->select('dop.diskon_satuan_dealer')
		->select('dop.tipe_diskon_campaign')
		->select('dop.diskon_campaign')
		->select('dop.harga_setelah_diskon')
		->select('(sc.jenis_diskon_campaign = "Additional") as additional_discount', false)
		->from(sprintf('%s as dop', $this->table))
		->join('ms_h3_md_sales_campaign as sc', '(sc.id = dop.id_diskon_campaign AND sc.jenis_reward_diskon = 1)', 'left')
		->join('ms_part as p', 'p.id_part_int = dop.id_part_int')
		->where('dop.id', $id)
		->get()->row_array();

		if($data == null) return;

		$data['harga_setelah_diskon_terakhir'] = harga_setelah_diskon($data['tipe_diskon_satuan_dealer'], $data['diskon_satuan_dealer'], $data['harga_jual_terakhir'], ($data['additional_discount'] == 1), $data['tipe_diskon_campaign'], $data['diskon_campaign']);

		$this->db
		->set('dop.harga_jual', $data['harga_jual_terakhir'])
		->set('dop.harga_beli', $data['harga_beli_terakhir'])
		->set('dop.harga_setelah_diskon', $data['harga_setelah_diskon_terakhir'])
		->where('dop.id', $id)
		->update(sprintf('%s as dop', $this->table));

		log_message('debug', sprintf('[%s] Update harga DO MD %s untuk kode part %s[%s] [payload] %s', $id, $data['id_do_sales_order'], $data['id_part'], $data['id_part_int'], print_r($data, true)));

		$this->delivery_order->update_total_do($data['id_do_sales_order']);
	}
}
