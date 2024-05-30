<?php

class h3_md_scan_picking_list_parts_model extends Honda_Model
{
    protected $table = 'tr_h3_md_scan_picking_list_parts';

    public function set_int_relation($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();
        if ($data == null) throw new Exception(sprintf('Scan picking list tidak ditemukan [%s]', $id));

        $picking_list = $this->db
            ->select('id')
            ->from('tr_h3_md_picking_list')
            ->where('id_picking_list', $data['id_picking_list'])
            ->limit(1)
            ->get()->row_array();
        if ($picking_list == null) throw new Exception(sprintf('Picking list %s tidak ditemukan', $data['id_picking_list']));

        $part = $this->db
            ->select('id_part_int')
            ->from('ms_part')
            ->where('id_part', $data['id_part'])
            ->limit(1)
            ->get()->row_array();
        if ($part == null) throw new Exception(sprintf('Kode part %s tidak ditemukan', $data['id_part']));

        $updated = $this->db
            ->set('id_picking_list_int', $picking_list['id'])
            ->set('id_part_int', $part['id_part_int'])
            ->where('id', $id)
            ->update($this->table);

        log_message('info', sprintf('Set int relation scan picking list [%s]', $id));

        return $updated;
    }
}
