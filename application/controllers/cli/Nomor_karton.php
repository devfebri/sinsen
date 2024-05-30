<?php

class Nomor_karton extends Honda_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('H3_md_nomor_karton_model', 'nomor_karton');
    }

    public function set_jumlah_item()
    {
        $this->db
            ->select('nk.id')
            ->from('tr_h3_md_nomor_karton as nk')
            // ->where('nk.jumlah_item', 0)
            ->where('nk.proses', 0)
            ->limit(5000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->nomor_karton->set_jumlah_item($row['id']);

            $this->db
                ->set('proses', 1)
                ->where('id', $row['id'])
                ->update('tr_h3_md_nomor_karton');
        }
    }
}
