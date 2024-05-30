<?php

class Set_id_int_packing_sheet_parts extends Honda_Controller {

    public function __construct(){
        parent::__construct();
        ini_set('max_execution_time', 0);

        $this->load->model('h3_md_ps_parts_model', 'ps_parts');
    }

    public function index()
    {
        $this->packing_sheet_parts();
    }

    public function packing_sheet_parts(){
        $this->db
        ->select('psp.id')
        ->from('tr_h3_md_ps_parts as psp')
        ->group_start()
        ->where('psp.packing_sheet_number_int IS NULL', null, false)
        ->or_where('psp.id_part_int IS NULL', null, false)
        ->group_end()
        ;

        $this->db->limit(5000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->ps_parts->set_int_relation($row['id']);
        }
    }
}