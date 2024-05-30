<?php

use GO\Scheduler;

class Set_int_relation_fdo extends Honda_Controller {

    public function index(){
		ini_set('max_execution_time', '0');

        $this->db->trans_start();
        $this->fdo_ps();
        $this->fdo_parts();
        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }

    private function fdo_parts(){
        $this->load->model('h3_md_fdo_parts_model', 'fdo_parts');        
        
        $this->db
        ->select('fdp.id')
        ->group_start()
        ->where('fdp.invoice_number_int IS NULL', null, false)
        ->or_where('fdp.id_part_int IS NULL', null, false)
        ->or_where('fdp.nomor_packing_sheet_int IS NULL', null, false)
        ->group_end()
        ->from('tr_h3_md_fdo_parts as fdp');

        foreach ($this->db->get()->result_array() as $row) {
            $this->fdo_parts->set_int_relation($row['id']);
        }
    }

    private function fdo_ps(){
        $this->load->model('H3_md_fdo_ps_model', 'fdo_ps');        
        
        $this->db
        ->select('fdo_ps.id')
        ->from('tr_h3_md_fdo_ps as fdo_ps')
        ->group_start()
        ->where('fdo_ps.invoice_number_int IS NULL', null, false)
        ->or_where('fdo_ps.packing_sheet_number_int IS NULL', null, false)
        ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->fdo_ps->set_int_relation($row['id']);
        }
    }
}