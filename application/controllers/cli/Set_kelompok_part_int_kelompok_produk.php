<?php

class Set_kelompok_part_int_kelompok_produk extends Honda_Controller {

    public function index()
    {
        $this->load->model('H3_md_ms_setting_kelompok_produk_model', 'setting_kelompok_produk');

        $this->db->trans_start();
        $this->db
        ->select('skp.id')
        ->from('ms_h3_md_setting_kelompok_produk as skp')
        ->where('skp.id_kelompok_part_int IS NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->setting_kelompok_produk->set_id_kelompok_part_int($row['id']);
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}