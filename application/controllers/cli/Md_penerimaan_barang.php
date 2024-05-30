<?php

class Md_penerimaan_barang extends Honda_Controller
{
    public function set_qty_packing_sheet_nol()
    {
        $this->db
            ->select('pbi.sinkron')
            ->select('pbi.id')
            ->select('pbi.no_penerimaan_barang')
            ->select('psp.packing_sheet_quantity')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->join('tr_h3_md_ps_parts as psp', '(psp.packing_sheet_number_int = pbi.packing_sheet_number_int and psp.id_part_int = pbi.id_part_int and psp.no_doos_int = pbi.nomor_karton_int and psp.no_po = pbi.no_po)', 'left')
            ->group_start()
            ->where('pbi.qty_packing_sheet', 0)
            ->or_where('pbi.qty_packing_sheet is null', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('qty_packing_sheet', $row['packing_sheet_quantity'])
                ->where('id', $row['id'])
                ->update('tr_h3_md_penerimaan_barang_items');
        }
    }
}
