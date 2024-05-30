<?php
class h3_md_psl_items_model extends Honda_Model
{

    protected $table = 'tr_h3_md_psl_items';

    public function insert($data)
    {
        parent::insert($data);
        $id = $this->db->insert_id();

        $this->set_int_relation($id);
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

        if ($data == null) {
            log_message('debug', sprintf('Gagal sinkron karena data tidak ditemukan [%s]', $id));
            return;
        }

        $this->db
            ->set('psli.surat_jalan_ahm_int', $data['surat_jalan_ahm_int'])
            ->set('psli.packing_sheet_number_int', $data['packing_sheet_number_int'])
            ->where('psli.id', $id)
            ->update(sprintf('%s as psli', $this->table));

        log_message('debug', sprintf('Set int relation packing sheet items [%s] [payload] %s', $id, print_r($data, true)));
    }
}
