<?php

use GO\Scheduler;

class Po_vendor_auto_close extends Honda_Controller {

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->process();
        })->daily('23:59');

        $scheduler->run();
    }

    public function process(){
        $this->db->trans_start();
        
        $now = date('Y-m-d', time());

        $this->db
        ->set('pv.status', 'Closed')
        ->set('pv.closed_at', date('Y-m-d H:i:s', time()))
        ->where("DATEDIFF('{$now}', pv.tanggal) >= 90")
        ->update('tr_h3_md_po_vendor as pv')
        ;

        $this->db->trans_complete();
    }
}