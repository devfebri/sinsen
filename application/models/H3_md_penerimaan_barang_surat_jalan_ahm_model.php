<?php

class H3_md_penerimaan_barang_surat_jalan_ahm_model extends Honda_Model {
	
	protected $table = 'tr_h3_md_penerimaan_barang_surat_jalan_ahm';

	

	public function set_int_relation($id){
		$data = $this->db
		->select('psl.id as surat_jalan_ahm_int')
		->where('pbsj.id', $id)
		->from(sprintf('%s as pbsj', $this->table))
		->join('tr_h3_md_psl as psl', 'psl.surat_jalan_ahm = pbsj.surat_jalan_ahm')
		->get()->row_array();

		if($data != null){
			$this->db
			->set('pbsj.surat_jalan_ahm_int', $data['surat_jalan_ahm_int'])
			->where('pbsj.id', $id)
			->update(sprintf('%s as pbsj', $this->table));

			log_message('debug', sprintf('Set int relation penerimaan barang surat jalan AHM [%s] [payload] %s', $id, print_r($data, true)));
		}else{
			log_message('debug', sprintf('Tidak berhasil set int relation penerimaan barang surat jalan AHM [%s]', $id));
		}
	}

}