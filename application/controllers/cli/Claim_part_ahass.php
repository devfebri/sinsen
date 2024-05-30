<?php

class Claim_part_ahass extends Honda_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_md_claim_part_ahass_model', 'claim_part_ahass');
    }

    public function index()
    {
        $this->set_id_claim_part_ahass();
    }

    private function set_id_claim_part_ahass()
    {
        $this->db
            ->select('cpa.id')
            ->from('tr_h3_md_claim_part_ahass as cpa')
            ->group_start()
            ->where('cpa.packing_sheet_number_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->claim_part_ahass->set_id_int($row['id']);
        }
    }

    public function items()
    {
        $this->db
            ->select('cpai.id')
            ->from('tr_h3_md_claim_part_ahass_parts as cpai')
            ->group_start()
            ->where('cpai.id_claim_part_ahass_int is null', null, false)
            ->or_where('cpai.id_claim_dealer_int is null', null, false)
            ->or_where('cpai.id_part_int is null', null, false)
            ->group_end();

        $this->load->model('h3_md_claim_part_ahass_parts_model', 'claim_part_ahass_parts');

        foreach ($this->db->get()->result_array() as $row) {
            $this->claim_part_ahass_parts->set_int_relation($row['id']);
        }
    }
}
