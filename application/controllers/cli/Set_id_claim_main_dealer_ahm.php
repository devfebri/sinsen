<?php

class Set_id_claim_main_dealer_ahm extends Honda_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $this->claim_main_dealer();
        $this->claim_main_dealer_item();
    }

    public function claim_main_dealer(){
        $this->db->trans_start();
        $this->db
        ->select('cmi.id')
        ->select('ps.id as packing_sheet_number_int')
        ->Select('fdo.id as invoice_number_int')
        ->from('tr_h3_md_claim_main_dealer_ke_ahm as cmi')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmi.packing_sheet_number')
		->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = cmi.invoice_number')
        ->group_start()
        ->or_where('cmi.packing_sheet_number_int IS NULL', null, false)
        ->or_where('cmi.invoice_number_int IS NULL', null, false)
        ->group_end()
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('packing_sheet_number_int', $row['packing_sheet_number_int'])
            ->set('invoice_number_int', $row['invoice_number_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_claim_main_dealer_ke_ahm');
        }
        $this->db->trans_complete();
    }

    public function claim_main_dealer_item(){
        $this->db->trans_start();
        $this->db
        ->select('cmii.id')
        ->select('p.id_part_int')
        ->select('cmi.id as id_claim_int')
        ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmii')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm as cmi', 'cmi.id_claim = cmii.id_claim')
        ->join('ms_part as p', 'p.id_part = cmii.id_part')
        ->group_start()
        ->where('cmii.id_claim_int IS NULL', null, false)
        ->or_where('cmii.id_part_int IS NULL', null, false)
        ->group_end()
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->set('id_claim_int', $row['id_claim_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_claim_main_dealer_ke_ahm_item');
        }
        $this->db->trans_complete();
    }
}