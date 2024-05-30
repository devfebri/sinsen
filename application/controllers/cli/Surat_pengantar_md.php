<?php

class Surat_pengantar_md extends Honda_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_md_surat_pengantar_items_model', 'surat_pengantar_item');
    }

    public function set_int_relation()
    {
        $this->db
            ->select('id')
            ->from('tr_h3_md_surat_pengantar_items')
            ->or_where('id_surat_pengantar_int', null)
            ->or_where('id_packing_sheet_int', null);

        foreach ($this->db->get()->result_array() as $row) {
            try {
                $this->surat_pengantar_item->set_int_relation($row['id']);
            } catch (Exception $e) {
                log_message('error', $e);
            }
        }
    }
}
