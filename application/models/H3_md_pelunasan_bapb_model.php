<?php

class H3_md_pelunasan_bapb_model extends Honda_Model{
	
	protected $table = 'tr_h3_md_pelunasan_bapb';

	public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

	public function generateID(){
		$bulan_short = date('m');
		$tahun_short = date('y');
        $tahun_dan_bulan = date('Y-m');
        
        $data = $this->db
        ->from($this->table)
        ->where("LEFT(created_at,7) = '{$tahun_dan_bulan}'")
        ->order_by('no_pelunasan', 'desc')
        ->order_by('created_at', 'desc')
        ->limit(1)
        ->get();

        if ($data->num_rows()>0) {
            $row        = $data->row();
            $no_pelunasan = substr($row->no_pelunasan, -5);
			$new_kode   = "PLBAPB/{$bulan_short}/{$tahun_short}/" .sprintf("%'.05d", $no_pelunasan+1);
			
        } else {
            $new_kode   = "PLBAPB/{$bulan_short}/{$tahun_short}/00001";
        }
        return strtoupper($new_kode);
	}
	
}
