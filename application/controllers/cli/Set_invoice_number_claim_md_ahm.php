<?php

class Set_invoice_number_claim_md_ahm extends Honda_Controller {

    public function index()
    {
        $this->db
        ->select('cm.id')
        ->select('cm.packing_sheet_number_int')
        ->from('tr_h3_md_claim_main_dealer_ke_ahm as cm')
        ->where('cm.invoice_number_int IS NULL', null, false);

        foreach($this->db->get()->result_array() as $row){
            $packing_sheet = $this->db
            ->select('ps.invoice_number_int')
            ->select('ps.invoice_number')
            ->from('tr_h3_md_ps as ps')
            ->where('ps.id', $row['packing_sheet_number_int'])
            ->get()->row_array();

            $this->db
            ->set('invoice_number_int', $packing_sheet['invoice_number_int'])
            ->set('invoice_number', $packing_sheet['invoice_number'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_claim_main_dealer_ke_ahm');
        }
    }

}