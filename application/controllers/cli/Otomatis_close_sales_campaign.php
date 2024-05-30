<?php

use GO\Scheduler;

class Otomatis_close_sales_campaign extends Honda_Controller {

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
        ->set('sc.status', 'Closed')
        ->where('sc.end_date <', date('Y-m-d', time()))
        ->where('sc.status', 'Open')
        ->update('ms_h3_md_sales_campaign as sc');
        $this->db->trans_complete();
    }
}