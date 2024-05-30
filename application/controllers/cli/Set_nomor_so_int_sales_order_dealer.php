<?php

use GO\Scheduler;

class Set_nomor_so_int_sales_order_dealer extends Honda_Controller {

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
        ->select('so.id as nomor_so_int')
        ->select('sop.id')
        ->from('tr_h3_dealer_sales_order_parts as sop')
        ->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = sop.nomor_so')
        ->where('sop.nomor_so_int IS NULL', null, false);

        foreach($this->db->get()->result_array() as $row){
            $this->db
            ->set('nomor_so_int', $row['nomor_so_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_dealer_sales_order_parts');
        }

        $this->db->trans_complete();
    }
}