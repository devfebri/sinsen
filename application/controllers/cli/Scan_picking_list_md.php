<?php

class Scan_picking_list_md extends Honda_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_md_scan_picking_list_parts_model', 'scan_picking_list');
    }

    public function set_int_relation()
    {
        $this->db
            ->select('scp.id')
            ->from('tr_h3_md_scan_picking_list_parts as scp')
            ->group_start()
            ->where('scp.id_part_int IS NULL', null, false)
            ->or_where('scp.id_picking_list_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->scan_picking_list->set_int_relation($row['id']);
        }
    }
}
