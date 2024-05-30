<?php

class H3_md_ms_setting_kelompok_produk_model extends Honda_Model{

    protected $table = 'ms_h3_md_setting_kelompok_produk';

    public function insert($data){
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_id_kelompok_part_int($id);

        return $id;
    }

    public function set_id_kelompok_part_int($id){
        $kelompokProduk = $this->db
        ->select('kp.id as id_kelompok_part_int')
        ->select('kp.id_kelompok_part')
        ->from(sprintf('%s as skp', $this->table))
        ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = skp.id_kelompok_part')
        ->where('skp.id', $id)
        ->get()->row_array();

        if($kelompokProduk != null){
            $this->db
            ->set('skp.id_kelompok_part_int' ,$kelompokProduk['id_kelompok_part_int'])
            ->where('skp.id', $id)
            ->update(sprintf('%s as skp', $this->table));

            log_message('debug', sprintf('Set kelompok part int pada settingan kelompok produk pada kelompok part %s [%s]', $kelompokProduk['id_kelompok_part'], $id));
        }
    }

}
