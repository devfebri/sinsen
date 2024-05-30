<?php

use GO\Scheduler;

class Otomatis_close_expired_so_md extends Honda_Controller {

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->process();
        })->daily();

        $scheduler->run();
    }

    public function process(){
        $this->db->trans_start();
        $this->db
        ->set('so.status', 'Closed')
        ->where('so.batas_waktu <=', date('Y-m-d', time()))
        ->where('so.status !=', 'Closed')
        ->where('so.status !=', 'Canceled')
        ->where('so.batas_waktu IS NOT NULL', null, false)
        ->update('tr_h3_md_sales_order as so');
        $this->db->trans_complete();
    }
}