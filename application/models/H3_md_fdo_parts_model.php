<?php

class h3_md_fdo_parts_model extends Honda_Model{

    protected $table = 'tr_h3_md_fdo_parts';

    public function set_int_relation($id){
        $data = $this->db
        ->select('p.id_part_int')
        ->select('ps.id as nomor_packing_sheet_int')
        ->select('fd.id as invoice_number_int')
        ->from(sprintf('%s as fdp', $this->table))
        ->join('ms_part as p', 'p.id_part = fdp.id_part', 'left')
        ->join('tr_h3_md_fdo as fd', 'fd.invoice_number = fdp.invoice_number', 'left')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = fdp.nomor_packing_sheet', 'left')
        ->where('fdp.id', $id)
        ->get()->row_array();

        if($data != null){
            $this->db
            ->set('fdp.id_part_int', $data['id_part_int'])
            ->set('fdp.nomor_packing_sheet_int', $data['nomor_packing_sheet_int'])
            ->set('fdp.invoice_number_int', $data['invoice_number_int'])
            ->where('fdp.id', $id)
            ->update(sprintf('%s as fdp', $this->table));

            log_message('debug', sprintf('Set int relation untuk fdo parts [%s] [payload] %s', $id, print_r($data, true)));
        }
    }

}
