<?php

class H3_md_po_logistik_parts_model extends Honda_Model{

    protected $table = 'tr_h3_md_po_logistik_parts';

    public function update_batch($data, $condition){
		$this->load->model('H3_md_po_logistik_parts_detail_model', 'po_logistik_parts_detail');

        if(count($data) > 0){
            foreach ($data as $row) {
                $this->db
                ->set('polpd.qty_supply', 0)
                ->set('polpd.qty_po_ahm', 0)
                ->where('polpd.id_po_logistik', $row['id_po_logistik'])
                ->where('polpd.id_part', $row['id_part'])
                ->update('tr_h3_md_po_logistik_parts_detail as polpd');

                $sisa_supply = $row['qty_supply'];
                $sisa_po_ahm = $row['qty_po_ahm'];

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
                    while($sisa_supply > 0){
                        if($sisa_supply >= $part_nrfs['qty_part']){
                            $this->db
                            ->set('polpd.qty_supply', $part_nrfs['qty_part'])
                            ->where('polpd.id_part', $part_nrfs['id_part'])
                            ->where('polpd.type_code', $part_nrfs['type_code'])
                            ->where('polpd.id_po_logistik', $row['id_po_logistik'])
                            ->update('tr_h3_md_po_logistik_parts_detail as polpd');
                            $sisa_supply -= $part_nrfs['qty_part'];
                        }else if($sisa_supply < $part_nrfs['qty_part']){
                            $this->db
                            ->set('polpd.qty_supply', $sisa_supply)
                            ->where('polpd.id_part', $part_nrfs['id_part'])
                            ->where('polpd.type_code', $part_nrfs['type_code'])
                            ->where('polpd.id_po_logistik', $row['id_po_logistik'])
                            ->update('tr_h3_md_po_logistik_parts_detail as polpd');
                            $sisa_supply -= $sisa_supply;
                        }
                    }

                    while($sisa_po_ahm > 0){
                        if($sisa_po_ahm >= $part_nrfs['qty_part']){
                            $this->db
                            ->set('polpd.qty_po_ahm', $part_nrfs['qty_part'])
                            ->where('polpd.id_part', $part_nrfs['id_part'])
                            ->where('polpd.type_code', $part_nrfs['type_code'])
                            ->where('polpd.id_po_logistik', $row['id_po_logistik'])
                            ->update('tr_h3_md_po_logistik_parts_detail as polpd');
                            $sisa_po_ahm -= $part_nrfs['qty_part'];
                        }else if($sisa_po_ahm < $part_nrfs['qty_part']){
                            $this->db
                            ->set('polpd.qty_po_ahm', $sisa_po_ahm)
                            ->where('polpd.id_part', $part_nrfs['id_part'])
                            ->where('polpd.type_code', $part_nrfs['type_code'])
                            ->where('polpd.id_po_logistik', $row['id_po_logistik'])
                            ->update('tr_h3_md_po_logistik_parts_detail as polpd');
                            $sisa_po_ahm -= $sisa_po_ahm;
                        }
                    }
                }
            }
        }

        parent::update_batch($data, $condition);
    }

    public function name(){
        
    }

}
