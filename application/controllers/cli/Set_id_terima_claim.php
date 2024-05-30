<?php

class Set_id_terima_claim extends Honda_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->terima_claim_item();
    }

    public function terima_claim_item(){
        $this->db->trans_start();
        $this->db
        ->select('tcai.id')
        ->select('p.id_part_int')
        ->select('cm.id as id_claim_int')
        ->from('tr_h3_md_terima_claim_ahm_item as tcai')
        ->join('ms_part as p', 'p.id_part = tcai.id_part')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm as cm', 'cm.id_claim = tcai.id_claim')
        ->group_start()
        ->where('tcai.id_claim_int is null', null, false)
        ->or_where('tcai.id_part_int is null', null, false)
        ->group_end()
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->set('id_claim_int', $row['id_claim_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_terima_claim_ahm_item');
        }
        $this->db->trans_complete();
    }
}