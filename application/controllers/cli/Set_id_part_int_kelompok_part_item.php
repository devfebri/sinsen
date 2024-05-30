<?php

class Set_id_part_int_kelompok_part_item extends Honda_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->db->trans_start();
        $this->db
        ->select('kpi.id')
        ->select('p.id_part_int')
        ->from('ms_kelompok_part_item as kpi')
        ->join('ms_part as p', 'p.id_part = kpi.id_part')
        ->where('kpi.id_part_int is null', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->where('id', $row['id'])
            ->update('ms_kelompok_part_item');
        }
        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}