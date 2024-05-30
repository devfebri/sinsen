<?php

class Set_id_int_packing_sheet extends Honda_Controller
{

    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 0);

        $this->load->model('h3_md_ps_parts_model', 'ps_parts');
        $this->load->model('h3_md_ps_model', 'ps');
    }

    public function index()
    {
        $this->packing_sheet_parts();
        $this->set_jumlah_karton();
    }

    public function set_jumlah_karton()
    {
        $this->db
            ->select('ps.id')
            ->from('tr_h3_md_ps as ps')
            ->where('ps.jumlah_karton', 0);

        foreach ($this->db->get()->result_array() as $row) {
            $this->ps->set_jumlah_karton($row['id']);
        }
    }

    public function packing_sheet_parts()
    {
        $this->db
            ->select('psp.id')
            ->from('tr_h3_md_ps_parts as psp')
            ->group_start()
            ->where('psp.packing_sheet_number_int IS NULL', null, false)
            ->or_where('psp.id_part_int IS NULL', null, false)
            ->or_where('psp.no_doos_int IS NULL', null, false)
            ->group_end();

        $this->db->limit(500);

        foreach ($this->db->get()->result_array() as $row) {
            $this->ps_parts->set_int_relation($row['id']);
        }
    }

    public function sinkron_data_nomor_karton(){
        $this->load->model('H3_md_nomor_karton_model', 'nomor_karton');

        $this->nomor_karton->sinkron();
    }

    public function sinkron_data_nomor_karton_untuk_penerimaan_barang(){
        $this->load->model('H3_md_nomor_karton_model', 'nomor_karton');

        $this->nomor_karton->sinkron_penerimaan_barang();
    }
}
