<?php

class Set_int_lokasi_rak_md extends Honda_Controller {

    public function index(){
        $this->db->trans_start();
        $this->set_int_relation_lokasi_rak_parts();
        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }

    public function set_int_relation_lokasi_rak_parts(){
        $this->load->model('H3_md_lokasi_rak_parts_model', 'lokasi_rak_parts');

        $this->db
        ->select('lrp.id')
        ->from('ms_h3_md_lokasi_rak_parts as lrp')
        ->where('lrp.id_part_int IS NULL', null, false);

        foreach($this->db->get()->result_array() as $row){
            $this->lokasi_rak_parts->set_int_relation($row['id']);
        }
    }
}