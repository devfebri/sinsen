<?php

use GO\Scheduler;

class Set_nomor_karton extends Honda_Controller
{

    public function index()
    {
        $this->db->trans_start();

        $nomor_karton_diterima = $this->db
            ->select('COUNT(pbi_sq.id) as count', false)
            ->from('tr_h3_md_penerimaan_barang_items as pbi_sq')
            ->where('pbi_sq.nomor_karton_int = nk.id', null, false)
            ->where('pbi_sq.tersimpan', 1)
            ->get_compiled_select();

        $this->db
            ->select('nk.id')
            ->select('nk.nomor_karton')
            ->select("IFNULL(({$nomor_karton_diterima}), 0) as nomor_karton_diterima", false)
            ->from('tr_h3_md_nomor_karton as nk')
            ->where('nk.check', 0)
            ->limit(10000);


        foreach ($this->db->get()->result_array() as $row) {
            $this->db
                ->set('jumlah_item_diterima', $row['nomor_karton_diterima'])
                ->set('check', 1)
                ->where('id', $row['id'])
                ->update('tr_h3_md_nomor_karton');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            echo 'Berhasil';
        } else {
            echo 'Gagal';
        }
    }

    public function set_jumlah_item()
    {
        $this->load->model('H3_md_nomor_karton_model', 'nomor_karton');

        $this->db
            ->select('nk.id')
            ->from('tr_h3_md_nomor_karton as nk')
            ->where('jumlah_item', 0)
            ->limit(1000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->nomor_karton->set_jumlah_item($row['id']);

            // $this->db
            // ->set('proses', 1)
            // ->where('id', $row['id'])
            // ->update('tr_h3_md_nomor_karton');
        }
    }
}
