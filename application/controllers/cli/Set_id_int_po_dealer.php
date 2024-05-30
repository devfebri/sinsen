<?php

use GO\Scheduler;

class Set_id_int_po_dealer extends Honda_Controller {


    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->process();
        });

        $scheduler->run();
    }

    public function process(){
        $this->db->trans_start();
        $this->db
        ->select('pop.id')
        ->select('po.id as po_id_int')
        ->select('p.id_part_int')
        ->from('tr_h3_dealer_purchase_order_parts pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->join('ms_part as p', 'p.id_part = pop.id_part')
        ->group_start()
        ->where('pop.id_part_int IS NULL', null, false)
        ->or_where('pop.po_id_int IS NULL', null, false)
        ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->set('po_id_int', $row['po_id_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_dealer_purchase_order_parts');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}