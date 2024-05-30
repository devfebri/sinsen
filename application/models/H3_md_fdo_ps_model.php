<?php

class H3_md_fdo_ps_model extends Honda_Model{

    protected $table = 'tr_h3_md_fdo_ps';

    public function insert($data){
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);
    }

    public function set_int_relation($id){
        $data = $this->db
        ->select('fdo.id as invoice_number_int')
        ->select('ps.id as packing_sheet_number_int')
        ->where('fdo_ps.id', $id)
        ->from('tr_h3_md_fdo_ps as fdo_ps')
        ->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_ps.invoice_number', 'left')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = fdo_ps.packing_sheet_number', 'left')
        ->get()->row_array();

        if($data != null){
            $this->db
            ->set('invoice_number_int', $data['invoice_number_int'])
            ->set('packing_sheet_number_int', $data['packing_sheet_number_int'])
            ->where('id', $id)
            ->update('tr_h3_md_fdo_ps');

            log_message('debug', sprintf('Set int relation fdo ps [%s] [payload] %s', $id, print_r($data, true)));
        }
    }

}
