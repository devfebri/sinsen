<?php

class H3_md_lokasi_rak_model extends Honda_Model
{

	protected $table = 'ms_h3_md_lokasi_rak';

	public function __construct()
	{
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
	}

	public function suggest_lokasi_rak_berdasarkan_settingan_master($id_part, $kuantitas, $lokasi_retur = false, $id_lokasi_rak = null, $getListLokasi = false)
	{
		$qty_onhand = $this->stock->qty_on_hand('lrp.id_part', 'lr.id', true);

		$this->db
			->select('lrp.id_lokasi_rak')
			->select('lr.kode_lokasi_rak')
			->select("(lrp.qty_maks - IFNULL(({$qty_onhand}), 0)) as kapasitas_tersedia")
			->select("IFNULL(({$qty_onhand}), 0) as qty_onhand")
			->select('1 as setting_per_part')
			->from('ms_h3_md_lokasi_rak_parts as lrp')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = lrp.id_lokasi_rak')
			->where('lrp.id_part', $id_part)
			->where("(lrp.qty_maks - IFNULL(({$qty_onhand}), 0)) >= {$kuantitas}")
			->where('(lr.kapasitas - lr.kapasitas_terpakai) >', $kuantitas)
			// ->order_by('kapasitas_tersedia', 'asc')
			->where('lr.lokasi_retur', $lokasi_retur)
			->where('lr.active', 1);

		if ($id_lokasi_rak != null and $getListLokasi == false) {
			$this->db->where('lr.id', $id_lokasi_rak);
		}

		if ($getListLokasi) {
			return array_map(function ($row) {
				return $row['id_lokasi_rak'];
			}, $this->db->get()->result_array());
		} else {
			$this->db->limit(1);
			return $this->db->get()->row_array();
		}
	}

	public function suggest_lokasi_rak_berdasarkan_kapasitas_tersedia($id_part, $kuantitas, $lokasi_retur = false, $id_lokasi_rak = null, $getListLokasi = false)
	{
		$this->db
			->select('lrp.id_lokasi_rak')
			->from('ms_h3_md_lokasi_rak_parts as lrp')
			->where('lrp.id_part', $id_part);

		$lokasi_rak_selain_yang_disetting = array_column($this->db->get()->result_array(), 'id_lokasi_rak');

		$this->db
			->select('lr.id as id_lokasi_rak')
			->select('lr.kode_lokasi_rak')
			->select('(lr.kapasitas - lr.kapasitas_terpakai) as kapasitas_tersedia', false)
			->select('0 as setting_per_part')
			->from('ms_h3_md_lokasi_rak as lr')
			->where('(lr.kapasitas - lr.kapasitas_terpakai) >=', $kuantitas, false)
			->where('lr.lokasi_retur', $lokasi_retur)
			->where('lr.active', 1)
			->order_by('kapasitas_tersedia', 'asc')
			;

		if (count($lokasi_rak_selain_yang_disetting) > 0) $this->db->where_not_in('lr.id', $lokasi_rak_selain_yang_disetting);

		if ($id_lokasi_rak != null AND $getListLokasi == false) $this->db->where('lr.id', $id_lokasi_rak);


		if($getListLokasi){
			return array_map(function ($row) {
				return $row['id_lokasi_rak'];
			}, $this->db->get()->result_array());
		}else{
			$this->db->limit(1);
			return $this->db->get()->row_array();
		}
	}

	public function suggest_lokasi($id_part = '', $kuantitas = 0, $lokasi_retur = false, $id_lokasi_rak = null, $getListLokasi = false)
	{
		$suggest_berdasarkan_lokasi_part = $this->suggest_lokasi_rak_berdasarkan_settingan_master($id_part, $kuantitas, $lokasi_retur, $id_lokasi_rak, $getListLokasi);
		if ($suggest_berdasarkan_lokasi_part != null) {
			log_message('debug', sprintf('Suggest lokasi rak berdasarkan settingan part untuk kode part %s', $id_part));
			log_message('debug', print_r($suggest_berdasarkan_lokasi_part, true));
			return $suggest_berdasarkan_lokasi_part;
		}

		$suggest_berdasarkan_lokasi = $this->suggest_lokasi_rak_berdasarkan_kapasitas_tersedia($id_part, $kuantitas, $lokasi_retur, $id_lokasi_rak, $getListLokasi);
		if ($suggest_berdasarkan_lokasi != null) {
			log_message('debug', sprintf('Suggest lokasi rak secara general untuk kode part %s', $id_part));
			log_message('debug', print_r($suggest_berdasarkan_lokasi, true));
			return $suggest_berdasarkan_lokasi;
		}
	}

	public function suggest_lokasi2($id_part = '', $kuantitas = 0, $lokasi_retur = false, $id_lokasi_rak = null, $getListLokasi = false)
	{
		$suggest_berdasarkan_lokasi_part = $this->suggest_lokasi_rak_berdasarkan_settingan_master($id_part, $kuantitas, $lokasi_retur, $id_lokasi_rak, $getListLokasi);
		if ($suggest_berdasarkan_lokasi_part != null) {
			log_message('debug', sprintf('Suggest lokasi rak berdasarkan settingan part untuk kode part %s', $id_part));
			log_message('debug', print_r($suggest_berdasarkan_lokasi_part, true));
			return $suggest_berdasarkan_lokasi_part;
		}

		// $suggest_berdasarkan_lokasi = $this->suggest_lokasi_rak_berdasarkan_kapasitas_tersedia($id_part, $kuantitas, $lokasi_retur, $id_lokasi_rak, $getListLokasi);
		// if ($suggest_berdasarkan_lokasi != null) {
		// 	log_message('debug', sprintf('Suggest lokasi rak secara general untuk kode part %s', $id_part));
		// 	log_message('debug', print_r($suggest_berdasarkan_lokasi, true));
		// 	return $suggest_berdasarkan_lokasi;
		// }
	}
}
