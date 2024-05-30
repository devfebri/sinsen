<?php

use GO\Scheduler;

class Set_int_key_dealer_stock extends Honda_Controller {

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
        ->select('ds.id')
        ->select('p.id_part_int')
        ->select('gudang.id as id_gudang_int')
        ->select('rak.id as id_rak_int')
        ->from('ms_h3_dealer_stock as ds')
        ->join('ms_part as p', 'p.id_part = ds.id_part')
        ->join('ms_gudang_h23 as gudang', '(gudang.id_gudang = ds.id_gudang AND gudang.id_dealer = ds.id_dealer)')
        ->join('ms_lokasi_rak_bin as rak', '(rak.id_rak = ds.id_rak AND rak.id_dealer = ds.id_dealer AND rak.id_gudang = gudang.id_gudang)')
        ->group_start()
        ->where('ds.id_part_int IS NULL', null, true)
        ->or_where('ds.id_gudang_int IS NULL', null, true)
        ->or_where('ds.id_rak_int IS NULL', null, true)
        ->group_end()
        ->limit(10000)
        ;

        foreach($this->db->get()->result_array() as $row){
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->set('id_gudang_int', $row['id_gudang_int'])
            ->set('id_rak_int', $row['id_rak_int'])
            ->where('id', $row['id'])
            ->update('ms_h3_dealer_stock');
        }

        $this->db->trans_complete();
    }
}