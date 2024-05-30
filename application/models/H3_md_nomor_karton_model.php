<?php
class H3_md_nomor_karton_model extends Honda_Model
{

    protected $table = 'tr_h3_md_nomor_karton';

    public function add_nomor_karton($nomor_karton)
    {
        $data_karton = $this->db
            ->select('id')
            ->select('nomor_karton')
            ->from(sprintf('%s as nk', $this->table))
            ->where('nk.nomor_karton', $nomor_karton)
            ->get()->row_array();

        if ($data_karton == null) {
            $this->insert([
                'nomor_karton' => $nomor_karton
            ]);
            $id = $this->db->insert_id();

            log_message('info', sprintf('Menambahkan nomor karton %s', $nomor_karton));
        } else {
            $id = $data_karton['id'];
            log_message('info', sprintf('Nomor karton %s sudah pernah dibuat sebelumnya', $nomor_karton));
        }

        $this->set_jumlah_item($id);
    }

    public function set_jumlah_item($id)
    {
        $data_karton = $this->db
            ->select('id')
            ->select('nomor_karton')
            ->from($this->table)
            ->where('id', $id)
            ->get()->row_array();

        if ($data_karton == null) {
            throw new Exception(sprintf('Nomor karton tidak ditemukan [%s]', $id));
        }

        $nomor_karton_items = $this->db
            ->select('psp.id')
            ->from('tr_h3_md_ps_parts as psp')
            ->where('psp.no_doos', $data_karton['nomor_karton'])
            ->get()->result_array();

        $jumlah_item = count($nomor_karton_items);

        if ($jumlah_item > 0) {
            $this->db
                ->set('jumlah_item', $jumlah_item)
                ->where('id', $id)
                ->update($this->table);

            log_message('info', sprintf('Set jumlah item menjadi %s untuk nomor karton %s [%s]', $jumlah_item, $data_karton['nomor_karton'], $id));
        }
    }

    public function sinkron()
    {
        $list_nomor_karton = $this->db
            ->select('nk.nomor_karton')
            ->from('tr_h3_md_nomor_karton as nk')
            ->get_compiled_select();

        $this->db
            ->select('DISTINCT(psp.no_doos) as no_doos')
            ->from('tr_h3_md_ps_parts as psp')
            ->where("psp.no_doos NOT IN ({$list_nomor_karton})", null, false)
            ->limit(500);

        foreach ($this->db->get()->result_array() as $row) {
            $this->add_nomor_karton($row['no_doos']);
        }
    }

    public function sinkron_penerimaan_barang()
    {
        $this->db
            ->select('DISTINCT(pbi.nomor_karton) as nomor_karton')
            ->from('tr_h3_md_penerimaan_barang_items as pbi')
            ->where('pbi.nomor_karton_int IS NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->add_nomor_karton($row['nomor_karton']);
        }
    }

    public function set_int_relation($id)
    {
        $data = $this->db
            ->select('ps.id as packing_sheet_number_int')
            ->select('psl.id as surat_jalan_ahm_int')
            ->from('tr_h3_md_psl_items as psli')
            ->join('tr_h3_md_psl as psl', 'psl.surat_jalan_ahm = psli.surat_jalan_ahm')
            ->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = psli.packing_sheet_number')
            ->where('psli.id', $id)
            ->get()->row_array();

        if ($data == null) return;

        $this->db
            ->set('psli.surat_jalan_ahm_int', $data['surat_jalan_ahm_int'])
            ->set('psli.packing_sheet_number_int', $data['packing_sheet_number_int'])
            ->where('psli.id', $id)
            ->update(sprintf('%s as psli', $this->table));
    }

    public function set_jumlah_item_diterima($id)
    {
        $nomor_karton_diterima = $this->db
            ->select('COUNT(pbi_sq.id) as count', false)
            ->from('tr_h3_md_penerimaan_barang_items as pbi_sq')
            ->where('pbi_sq.nomor_karton_int = nk.id', null, false)
            ->where('pbi_sq.tersimpan', 1)
            ->get_compiled_select();

        $data_karton = $this->db
            ->select("IFNULL(({$nomor_karton_diterima}), 0) as nomor_karton_diterima", false)
            ->from(sprintf('%s as nk', $this->table))
            ->where('nk.id', $id)
            // ->where('nk.nomor_karton', $id)
            ->get()->row_array();

        if ($data_karton == null) {
            throw new Exception(sprintf('Nomor karton tidak ditemukan [%s]', $id));
        }

        $this->db
            ->set('jumlah_item_diterima', $data_karton['nomor_karton_diterima'])
            ->where('id', $id)
            ->update($this->table);
    }
}
