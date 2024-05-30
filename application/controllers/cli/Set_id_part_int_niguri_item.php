<?php

use GO\Scheduler;

class Set_id_part_int_niguri_item extends Honda_Controller {

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
        ->select('n.id')
        ->select('p.id_part_int')
        ->from('tr_h3_md_niguri as n')
        ->join('ms_part as p', 'p.id_part = n.id_part')
        ->where('n.id_part_int IS NULL', null, false);

        foreach($this->db->get()->result_array() as $row){
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_niguri');
        }

        $this->db->trans_complete();
    }
}