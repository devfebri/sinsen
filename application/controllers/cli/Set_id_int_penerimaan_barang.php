<?php

class Set_id_int_penerimaan_barang extends Honda_Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 0);

        parent::__construct();

        $this->load->model('H3_md_penerimaan_barang_items_model', 'penerimaan_barang_items');
    }

    public function index()
    {
        $this->penerimaan_barang_items();
        $this->set_qty_packing_sheet();
        $this->penerimaan_barang_surat_jalan();
    }

    public function penerimaan_barang_items()
    {
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

    public function set_nomor_karton_int()
    {
        $this->load->model('H3_md_nomor_karton_model', 'nomor_karton');

        $this->db
            ->select('pbi.id')
            ->select('pbi.nomor_karton')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->where('pbi.nomor_karton_int IS NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $nomor_karton = (array) $this->nomor_karton->find($row['nomor_karton'], 'nomor_karton');
            if ($nomor_karton != null) {
                $this->db
                    ->set('nomor_karton_int', $nomor_karton['id'])
                    ->where('id', $row['id'])
                    ->update('tr_h3_md_penerimaan_barang_items');
            }
        }
    }

    public function set_qty_packing_sheet()
    {
        $this->db
            ->select('pbi.id')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->where('pbi.qty_packing_sheet', 0);

        foreach ($this->db->get()->result_array() as $row) {
            $this->penerimaan_barang_items->set_qty_packing_sheet($row['id']);
        }
    }

    public function penerimaan_barang_surat_jalan()
    {
        $this->load->model('H3_md_penerimaan_barang_surat_jalan_ahm_model', 'penerimaan_barang_surat_jalan');

        $this->db
            ->select('pbsj.id')
            ->from('tr_h3_md_penerimaan_barang_surat_jalan_ahm as pbsj')
            ->group_start()
            ->where('pbsj.surat_jalan_ahm_int IS NULL', null, false)
            ->group_end();

        foreach ($this->db->get()->result_array() as $row) {
            $this->penerimaan_barang_surat_jalan->set_int_relation($row['id']);
        }
    }

    public function set_qty_claim()
    {
        $this->db
            ->select('pbi.id')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->where('pbi.sinkron', 0)
            ->limit(5000);

        foreach ($this->db->get()->result_array() as $row) {
            $this->penerimaan_barang_items->count_claim_ekspedisi($row['id']);
            $this->penerimaan_barang_items->count_selain_claim_ekspedisi($row['id']);

            $this->db
                ->set('sinkron', 1)
                ->where('id', $row['id'])
                ->update('tr_h3_md_penerimaan_barang_items');
        }
    }

    public function sinkron_int_penerimaan_barang_items_by_surat_jalan_ekspedisi()
    {
        $this->db
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->where('pbi.sinkron', 0);

        foreach ($this->db->get()->result_array() as $row) {
            $packing_sheet = $this->db
                ->select('ps.id')
                ->from('tr_h3_md_ps as ps')
                ->where('ps.packing_sheet_number', $row['packing_sheet_number'])
                ->limit(1)
                ->get()->row_array();

            if ($packing_sheet == null) throw new Exception('Packing sheet tidak ditemukan');

            $part = $this->db
                ->select('p.id_part_int')
                ->from('ms_part as p')
                ->where('p.id_part', $row['id_part'])
                ->limit(1)
                ->get()->row_array();

            if ($part == null) throw new Exception('Part tidak ditemukan');

            $nomor_karton = $this->db
                ->select('nk.id')
                ->from('tr_h3_md_nomor_karton as nk')
                ->where('nk.nomor_karton', $row['nomor_karton'])
                ->limit(1)
                ->get()->row_array();

            if ($nomor_karton == null) throw new Exception('Nomor karton tidak ditemukan');

            $this->db
                ->set('packing_sheet_number_int', $packing_sheet['id'])
                ->set('id_part_int', $part['id_part_int'])
                ->set('nomor_karton_int', $nomor_karton['id'])
                ->set('sinkron', 1)
                ->where('id', $row['id'])
                ->update('tr_h3_md_penerimaan_barang_items');
        }
    }
}
