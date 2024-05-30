<?php

use GO\Scheduler;

class Dealer_suggested_order extends Honda_Controller
{
    public function set_int_relation()
    {
        $this->db->trans_start();
        $this->db
            ->select('ar.id')
            ->select('p.id_part_int')
            ->from('ms_h3_analisis_ranking as ar')
            ->join('ms_part as p', 'p.id_part = ar.id_part')
            ->where('ar.id_part_int', null)
            ->limit(5000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('id_part_int', $row['id_part_int'])
                ->where('id', $row['id'])
                ->update('ms_h3_analisis_ranking');
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            echo 'Berhasil';
        } else {
            echo 'Gagal';
        }
    }
}
