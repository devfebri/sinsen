<?php

class Set_kelompok_part_int_part extends Honda_Controller {

    public function index()
    {
        $this->db->trans_start();
        $this->db
        ->select('p.id_part_int')
        ->select('kp.id as kelompok_part_int')
        ->from('ms_part as p')
        ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
        ->where('p.kelompok_part_int IS NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('kelompok_part_int', $row['kelompok_part_int'])
            ->where('id_part_int', $row['id_part_int'])
            ->update('ms_part');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}