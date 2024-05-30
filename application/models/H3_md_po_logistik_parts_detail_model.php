<?php

class H3_md_po_logistik_parts_detail_model extends Honda_Model{

    protected $table = 'tr_h3_md_po_logistik_parts_detail';

    public function update_qty_book($id_po_logistik){
        $data = $this->db
        ->select('sop.id_part')
        ->select('SUM(sop.qty_order) as qty_order')
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_sales_order_parts as sop', 'sop.id_sales_order = so.id_sales_order')
        ->where('so.id_po_logistik', $id_po_logistik)
        ->group_by('sop.id_part')
        ->get()->result_array();

        foreach ($data as $row) {
            $this->db
            ->set('polpd.qty_book', 0)
            ->where('polpd.id_po_logistik', $id_po_logistik)
            ->where('polpd.id_part', $row['id_part'])
            ->update('tr_h3_md_po_logistik_parts_detail as polpd');

            $sisa_order = $row['qty_order'];

            $parts_nrfs = $this->db
            ->select('polpd.id_part')
            ->select('polpd.dokumen_nrfs_id')
            ->select('polpd.type_code')
            ->select('nrfs_part.qty_part')
            ->from('tr_h3_md_po_logistik_parts_detail as polpd')
            ->join('tr_dokumen_nrfs as nrfs', 'nrfs.dokumen_nrfs_id = polpd.dokumen_nrfs_id')
            ->join('tr_dokumen_nrfs_part as nrfs_part', '(nrfs_part.id_part = polpd.id_part and nrfs_part.dokumen_nrfs_id = polpd.dokumen_nrfs_id)')
            ->where('polpd.id_part', $row['id_part'])
            ->order_by('nrfs.created_at', 'asc')
            ->get()->result_array();

            foreach ($parts_nrfs as $part_nrfs) {
                while($sisa_order > 0){
                    if($sisa_order >= $part_nrfs['qty_part']){
                        $this->db
                        ->set('polpd.qty_book', $part_nrfs['qty_part'])
                        ->where('polpd.id_part', $part_nrfs['id_part'])
                        ->where('polpd.type_code', $part_nrfs['type_code'])
                        ->where('polpd.id_po_logistik', $id_po_logistik)
                        ->update('tr_h3_md_po_logistik_parts_detail as polpd');
                        $sisa_order -= $part_nrfs['qty_part'];
                    }else if($sisa_order < $part_nrfs['qty_part']){
                        $this->db
                        ->set('polpd.qty_book', $sisa_order)
                        ->where('polpd.id_part', $part_nrfs['id_part'])
                        ->where('polpd.type_code', $part_nrfs['type_code'])
                        ->where('polpd.id_po_logistik', $id_po_logistik)
                        ->update('tr_h3_md_po_logistik_parts_detail as polpd');
                        $sisa_order -= $sisa_order;
                    }
                }
            }
        }
    }

}
