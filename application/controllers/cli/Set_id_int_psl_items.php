<?php

class Set_id_int_psl_items extends Honda_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->model('h3_md_psl_items_model', 'psl_items');
    }

    public function index()
    {
        $this->psl_items();
    }

    public function psl_items(){
        $this->db->trans_start();
        $this->db
        ->select('psli.id')
        ->from('tr_h3_md_psl_items as psli')
        ->group_start()
        ->where('psli.surat_jalan_ahm_int IS NULL', null, false)
        ->or_where('psli.packing_sheet_number_int IS NULL', null, false)
        ->group_end()
        ;

        $this->db->limit(5000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->psl_items->set_int_relation($row['id']);
        }
        
        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}