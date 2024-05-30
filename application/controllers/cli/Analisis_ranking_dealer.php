<?php

class Analisis_ranking_dealer extends Honda_Controller
{
    public function items()
    {
        $this->db
            ->from('ms_h3_analisis_ranking')
            ->group_start()
            ->or_where('id_part_int is null', null, false)
            ->group_end()
            ->limit(30000);

        foreach ($this->db->get()->result_array() as $row) {
            $part = $this->db
                ->from('ms_part')
                ->where('id_part', $row['id_part'])
                ->limit(1)
                ->get()->row_array();
            if ($part == null) throw new Exception('Part tidak ditemukan');

            $this->db
                ->set('id_part_int', $part['id_part_int'])
                ->where('id', $row['id'])
                ->update('ms_h3_analisis_ranking');
        }
    }
}
