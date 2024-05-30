<?php

class h3_md_ps_parts_model extends Honda_Model
{
    protected $table = 'tr_h3_md_ps_parts';

    public function __construct(){
        parent::__construct();

        $this->load->model('H3_md_nomor_karton_model', 'nomor_karton');
    }

    public function insert($data)
    {
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->nomor_karton->add_nomor_karton($data['no_doos']);

        $this->set_int_relation($id);
    }

    public function set_int_relation($id){
        $data = $this->db
        ->select('ps.id as packing_sheet_number_int')
        ->select('p.id_part_int')
        ->select('po.id as no_po_int')
        ->select('nk.id as no_doos_int')
        ->from('tr_h3_md_ps_parts as psp')
        ->join('ms_part as p', 'p.id_part = psp.id_part')
        ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = psp.packing_sheet_number')
        ->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = psp.no_po', 'left')
        ->join('tr_h3_md_nomor_karton as nk', 'nk.nomor_karton = psp.no_doos', 'left')
        ->where('psp.id', $id)
        ->get()->row_array();


        if($data == null) return;
        
        $this->db
        ->set('psp.packing_sheet_number_int', $data['packing_sheet_number_int'])
        ->set('psp.id_part_int', $data['id_part_int'])
        ->set('psp.no_po_int', $data['no_po_int'])
        ->set('psp.no_doos_int', $data['no_doos_int'])
        ->where('psp.id', $id)
        ->update('tr_h3_md_ps_parts as psp');
        
        log_message('debug', sprintf('Set int relation packing sheet [%s] [payload] %s', $id, print_r($data, true)));
    }
}
