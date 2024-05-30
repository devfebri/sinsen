<?php

use GO\Scheduler;

class Set_id_int_po_parts_md extends Honda_Controller {

    public function index(){
        $this->db->trans_start();
        $this->db
        ->select('pop.id')
        ->select('po.id as id_purchase_order_int')
        ->select('p.id_part_int')
        ->from('tr_h3_md_purchase_order_parts as pop')
        ->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = pop.id_purchase_order')
        ->join('ms_part as p', 'p.id_part = pop.id_part')
        ->group_start()
        ->where('pop.id_purchase_order_int IS NULL', null, false)
        ->or_where('pop.id_part_int IS NULL', null, false)
        ->group_end()
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->set('id_purchase_order_int', $row['id_purchase_order_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_purchase_order_parts');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}