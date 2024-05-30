<?php

class Sim_part extends Honda_Controller
{
    public function items()
    {
        $this->db
            ->from('ms_h3_md_sim_part_item')
            ->group_start()
            ->or_where('id_part_int is null', null, false)
            ->or_where('id_sim_part_int is null', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $part = $this->db
                ->from('ms_part')
                ->where('id_part', $row['id_part'])
                ->limit(1)
                ->get()->row_array();
            if ($part == null) throw new Exception('Part tidak ditemukan');

            $sim_part = $this->db
                ->from('ms_h3_md_sim_part')
                ->where('id_sim_part', $row['id_sim_part'])
                ->limit(1)
                ->get()->row_array();
            if ($sim_part == null) throw new Exception('SIM part tidak ditemukan');

            $this->db
                ->set('id_part_int', $part['id_part_int'])
                ->set('id_sim_part_int', $sim_part['id'])
                ->where('id', $row['id'])
                ->update('ms_h3_md_sim_part_item');
        }
    }
}
