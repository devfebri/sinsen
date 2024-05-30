<?php

class Set_id_int_penerimaan_barang_items extends Honda_Controller {

    public function __construct(){
        ini_set('max_execution_time', 0);

        parent::__construct();

        $this->load->model('H3_md_penerimaan_barang_items_model', 'penerimaan_barang_items');
    }

    public function index()
    {
        $this->db->trans_start();

        $this->penerimaan_barang_items();
        $this->set_qty_packing_sheet();

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Tidak berhasil';
        }   
    }

    public function penerimaan_barang_items(){
        $this->db
        ->select('pbi.id')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->group_start()
        ->where('pbi.id_part_int IS NULL', null, false)
        ->or_where('pbi.surat_jalan_ahm_int IS NULL', null, false)
        ->or_where('pbi.packing_sheet_number_int IS NULL', null, false)
        ->or_where('pbi.no_penerimaan_barang_int IS NULL', null, false)
        ->group_end();

        $this->db->limit(5000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->penerimaan_barang_items->set_int_relation($row['id']);
        }
    }

    public function set_qty_packing_sheet(){
        $this->db
        ->select('pbi.id')
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->where('pbi.qty_packing_sheet', 0);

        foreach ($this->db->get()->result_array() as $row) {
            $this->penerimaan_barang_items->set_qty_packing_sheet($row['id']);
        }
    }
}