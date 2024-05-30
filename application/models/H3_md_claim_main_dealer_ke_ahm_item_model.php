<?php

class H3_md_claim_main_dealer_ke_ahm_item_model extends Honda_Model
{

    protected $table = 'tr_h3_md_claim_main_dealer_ke_ahm_item';

    public function insert($data)
    {
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);

        return $id;
    }

    public function set_int_relation($id)
    {
        $data = $this->db
            ->select('cmd.id as id_claim_int')
            ->select('p.id_part_int')
            ->select('psp.no_doos_int')
            ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdi')
            ->join('tr_h3_md_claim_main_dealer_ke_ahm as cmd', 'cmd.id_claim = cmdi.id_claim')
            ->join('ms_part as p', 'p.id_part = cmdi.id_part')
            ->join('tr_h3_md_ps_parts as psp', '(psp.packing_sheet_number = cmd.packing_sheet_number and psp.id_part = cmdi.id_part AND psp.no_doos = cmdi.no_doos AND psp.no_po = cmdi.no_po)')
            ->where('cmdi.id', $id)
            ->get()->row_array();

        if ($data == null) throw new Exception(sprintf('Claim main dealer item tidak ditemukan [%s]', $id));

        $this->db
            ->set('id_claim_int', $data['id_claim_int'])
            ->set('id_part_int', $data['id_part_int'])
            ->set('no_doos_int', $data['no_doos_int'])
            ->where('id', $id)
            ->update('tr_h3_md_claim_main_dealer_ke_ahm_item');

        log_message('debug', sprintf('Setting int relation claim main dealer ke ahm item[%s]', $id));
    }
}
