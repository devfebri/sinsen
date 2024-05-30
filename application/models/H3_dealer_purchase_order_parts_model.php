<?php

class h3_dealer_purchase_order_parts_model extends Honda_Model {
	protected $table = 'tr_h3_dealer_purchase_order_parts';

	public function __construct(){
		parent::__construct();

        $this->load->helper('harga_setelah_diskon');
		$this->load->model('h3_dealer_purchase_order_model', 'po_dealer');
	}

	public function insert($data){
		if(!isset($data['id_part_int'])){
			$data['id_part_int'] = $this->db->select('id_part_int')->from('ms_part')->where('id_part', $data['id_part'])->get()->row_array()['id_part_int'];
		}

		if(!isset($data['po_id_int'])){
			$data['po_id_int'] = $this->db->select('id')->from('tr_h3_dealer_purchase_order')->where('po_id', $data['po_id'])->get()->row_array()['id'];
		}

		$campaign = null;
		if(isset($data['id_campaign_diskon'])){
			$campaign = $this->db
			->select('sc.jenis_diskon_campaign')
			->from('ms_h3_md_sales_campaign as sc')
			->where('sc.id', $data['id_campaign_diskon'])
			->get()->row_array();
		}

		$data['harga_setelah_diskon'] = harga_setelah_diskon($data['tipe_diskon'], $data['diskon_value'], $data['harga_saat_dibeli'], ($campaign != null AND $campaign['jenis_diskon_campaign'] == 'Additional'), $data['tipe_diskon_campaign'], $data['diskon_value_campaign']);
		$data['tot_harga_part'] = $data['kuantitas'] * $data['harga_setelah_diskon'];

		parent::insert($data);
	}

    public function update_harga($id){
        $part = $this->db
		->select('pop.po_id_int')
		->select('pop.po_id')
		->select('pop.id_part_int')
		->select('pop.id_part')
		->select('pop.harga_saat_dibeli')
		->select('p.harga_dealer_user as harga_terakhir')
		->select('pop.tipe_diskon')
		->select('pop.diskon_value')
		->select('pop.tipe_diskon_campaign')
		->select('pop.diskon_value_campaign')
		->select('pop.id_campaign_diskon')
		->select('pop.harga_setelah_diskon')
		->select('pop.kuantitas')
		->select('pop.tot_harga_part')
        ->where('pop.id', $id)
        ->from(sprintf('%s as pop', $this->table))
		->join('ms_part as p', 'p.id_part_int = pop.id_part_int')
		->get()->row_array();

		if($part != null){

			$campaign = null;
			if(isset($part['id_campaign_diskon'])){
				$campaign = $this->db
				->select('sc.jenis_diskon_campaign')
				->from('ms_h3_md_sales_campaign as sc')
				->where('sc.id', $part['id_campaign_diskon'])
				->get()->row_array();
			}

			$part['harga_setelah_diskon_terakhir'] = harga_setelah_diskon($part['tipe_diskon'], $part['diskon_value'], $part['harga_terakhir'], ($campaign != null AND $campaign['jenis_diskon_campaign'] == 'Additional'), $part['tipe_diskon_campaign'], $part['diskon_value_campaign']);
			$part['tot_harga_part_terakhir'] = $part['harga_setelah_diskon_terakhir'] * $part['kuantitas'];

			$this->db
			->set('pop.harga_saat_dibeli', $part['harga_terakhir'])
			->set('pop.harga_setelah_diskon', $part['harga_setelah_diskon_terakhir'])
			->set('pop.tot_harga_part', $part['tot_harga_part_terakhir'])
			->where('pop.id', $id)
			->update(sprintf('%s as pop', $this->table));

			log_message('debug', sprintf('[%s] Update harga PO dealer %s untuk kode part %s[%s] [payload] %s', $id, $part['po_id'], $part['id_part'], $part['id_part_int'], print_r($part, true)));

			$this->po_dealer->update_total_amount($part['po_id_int']);
			return;
		}

		log_message('debug', sprintf('Part purchase order dengan id %s tidak ditemukan', $id));
    }
}
?>