<?php

class H3_md_ms_tipe_po_model extends Honda_Model{

	protected $table = 'master_tipe_po';

	public function get_batas_waktu($id_dealer, $tipe_po){
		$tipe_po_item = $this->db
		->select("
			case
				when '{$tipe_po}' = 'FIX' then DATE_FORMAT( DATE_ADD( NOW(), INTERVAL tpi.fix DAY), '%Y-%m-%d')
				when '{$tipe_po}' = 'REG' then DATE_FORMAT( DATE_ADD( NOW(), INTERVAL tpi.reg DAY), '%Y-%m-%d')
			end as batas_waktu
		", false)
		->from('master_tipe_po_item as tpi')
		->where('tpi.id_dealer', $id_dealer)
		->get()->row_array()
		;

		if($tipe_po_item != null){
			return $tipe_po_item['batas_waktu'];
		}

		$tipe_po = $this->db
		->select("
			case
				when '{$tipe_po}' = 'FIX' then DATE_FORMAT( DATE_ADD( NOW(), INTERVAL tp.fix DAY), '%Y-%m-%d')
				when '{$tipe_po}' = 'REG' then DATE_FORMAT( DATE_ADD( NOW(), INTERVAL tp.reg DAY), '%Y-%m-%d')
			end as batas_waktu
		", false)
		->from('master_tipe_po as tp')
		->get()->row_array()
		;

		if($tipe_po != null){
			return $tipe_po['batas_waktu'];
		}
		
		return null;
	}
	
}
