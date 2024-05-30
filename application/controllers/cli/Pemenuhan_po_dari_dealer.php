<?php

class Pemenuhan_po_dari_dealer extends Honda_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po_dari_dealer');
    }

    public function index()
    {
        $this->db
            ->select('id')
            ->from('tr_h3_md_pemenuhan_po_dari_dealer')
            ->group_start()
            ->where('id_part_int IS NULL', null, false)
            ->or_where('po_id_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->pemenuhan_po_dari_dealer->set_int_relation($row['id']);
        }
    }

    public function set_minus_to_zero()
    {
        $this->db
            ->set('qty_so', 0)
            ->where('qty_so < ', 0)
            ->update('tr_h3_md_pemenuhan_po_dari_dealer');

        $this->db
            ->set('qty_do', 0)
            ->where('qty_do < ', 0)
            ->update('tr_h3_md_pemenuhan_po_dari_dealer');

        $this->db
            ->set('qty_supply', 0)
            ->where('qty_supply < ', 0)
            ->update('tr_h3_md_pemenuhan_po_dari_dealer');
    }
}
