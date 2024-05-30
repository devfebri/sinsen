<?php

class Claim_main_dealer_ke_ahm extends Honda_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('H3_md_claim_main_dealer_ke_ahm_model', 'claim_main_dealer');
        $this->load->model('H3_md_claim_main_dealer_ke_ahm_item_model', 'claim_main_dealer_item');
    }

    public function index()
    {
        $this->claim();
        $this->items();
    }

    public function claim()
    {
        $this->db
            ->select('cmd.id')
            ->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
            ->group_start()
            ->where('cmd.packing_sheet_number_int IS NULL', null, false)
            ->or_where('cmd.invoice_number IS NULL', null, false)
            ->or_where('cmd.invoice_number_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->claim_main_dealer->set_int_relation($row['id']);
        }
    }

    public function items()
    {
        $this->db
            ->select('cmdi.id')
            ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdi')
            ->group_start()
            ->where('cmdi.id_claim_int IS NULL', null, false)
            ->or_where('cmdi.id_part IS NULL', null, false)
            ->or_where('cmdi.no_doos_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->claim_main_dealer_item->set_int_relation($row['id']);
        }
    }
}
