<?php

class H3_md_ms_sim_part_item_model extends Honda_Model
{

    protected $table = 'ms_h3_md_sim_part_item';

    public function insert($data)
    {
        $part = $this->db
            ->from('ms_part')
            ->where('id_part', $data['id_part'])
            ->limit(1)
            ->get()->row_array();
        if ($part == null) throw new Exception('Part tidak ditemukan');

        $sim_part = $this->db
            ->from('ms_h3_md_sim_part')
            ->where('id_sim_part', $data['id_sim_part'])
            ->limit(1)
            ->get()->row_array();
        if ($sim_part == null) throw new Exception('SIM part tidak ditemukan');

        $data['id_part_int'] = $part['id_part_int'];
        $data['id_sim_part_int'] = $sim_part['id'];

        parent::insert($data);
    }
}
