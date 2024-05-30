<?php

class H3_md_lokasi_rak_parts_model extends Honda_Model{
	
	protected $table = 'ms_h3_md_lokasi_rak_parts';

	public function set_int_relation($id){
		$data = $this->db
		->select('p.id_part_int')
		->from('ms_h3_md_lokasi_rak_parts as lrp')
		->join('ms_part as p', 'p.id_part = lrp.id_part', 'left')
		->where('lrp.id', $id)
		->get()->row_array();

		if($data != null){
			$this->db
			->set('lrp.id_part_int', $data['id_part_int'])
			->where('lrp.id', $id)
			->update(sprintf('%s as lrp', $this->table));

			log_message('debug', sprintf('Set int relation lokasi rak parts [%s] [payload] %s', $id, print_r($data, true)));
		}else{
			log_message('debug', sprintf('Tidak berhasil set int relation lokasi rak parts [%s]', $id));
		}
	}

}
