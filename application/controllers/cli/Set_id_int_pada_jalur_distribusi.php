<?php

use GO\Scheduler;

class Set_id_int_pada_jalur_distribusi extends Honda_Controller {

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->do_parts();
            $this->picking_list();
            $this->picking_list_parts();
            $this->packing_sheet();
        });

        $scheduler->run();
    }

    public function do_parts(){
        $this->db->trans_start();
        $this->db
        ->select('dop.id')
        ->select('p.id_part_int')
        ->select('do.id as id_do_sales_order_int')
        ->from('tr_h3_md_do_sales_order_parts as dop')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
        ->join('ms_part as p', 'p.id_part = dop.id_part')
        ->group_start()
        ->where('dop.id_part_int IS NULL', null, true)
        ->or_where('dop.id_do_sales_order_int IS NULL', null, true)
        ->group_end()
        ;

        foreach($this->db->get()->result_array() as $row){
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->set('id_do_sales_order_int', $row['id_do_sales_order_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_do_sales_order_parts');
        }
        $this->db->trans_complete();
    }

    public function picking_list(){
        $this->db->trans_start();
        $this->db
        ->select('pl.id')
        ->select('do.id as id_ref_int')
        ->from('tr_h3_md_picking_list as pl')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref', 'left')
        ->where('pl.id_ref_int IS NULL', null, false)
        ->order_by('pl.id', 'asc')
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_ref_int', $row['id_ref_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_picking_list');
        }

        $this->db->trans_complete();
    }

    public function picking_list_parts(){
        $this->db->trans_start();
        $this->db
        ->select('plp.id')
        ->select('pl.id as id_picking_list_int')
        ->select('p.id_part_int')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->join('ms_part as p', 'p.id_part = plp.id_part')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
        ->group_start()
        ->where('plp.id_picking_list_int IS NULL', null, false)
        ->where('plp.id_part_int IS NULL', null, false)
        ->group_end()
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_picking_list_int', $row['id_picking_list_int'])
            ->set('id_part_int', $row['id_part_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_picking_list_parts');
        }

        $this->db->trans_complete();
    }

    public function packing_sheet(){
        $this->db->trans_start();
        
        $this->db
        ->select('ps.id')
        ->select('pl.id as id_picking_list_int')
        ->from('tr_h3_md_packing_sheet as ps')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->where('ps.id_picking_list_int IS NULL', null, false)
        ;

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_picking_list_int', $row['id_picking_list_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_packing_sheet');
        }

        $this->db->trans_complete();
    }
}