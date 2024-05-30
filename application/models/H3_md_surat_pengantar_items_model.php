<?php
class h3_md_surat_pengantar_items_model extends Honda_Model
{
    protected $table = 'tr_h3_md_surat_pengantar_items';

    public function set_int_relation($id)
    {
        $data = $this->db
            ->from(sprintf('%s as spi', $this->table))
            ->where('id', $id)
            ->limit(1)
            ->get()->row_array();

        $surat_pengantar = $this->db
            ->select('sp.id')
            ->from('tr_h3_md_surat_pengantar as sp')
            ->where('sp.id_surat_pengantar', $data['id_surat_pengantar'])
            ->limit(1)
            ->get()->row_array();
        if (!$surat_pengantar) throw new Exception(sprintf('Surat pengantar %s tidak ditemukan', $data['id_surat_pengantar']));

        $packing_sheet = $this->db
            ->select('ps.id')
            ->from('tr_h3_md_packing_sheet as ps')
            ->where('ps.id_packing_sheet', $data['id_packing_sheet'])
            ->limit(1)
            ->get()->row_array();
        if (!$packing_sheet) throw new Exception(sprintf('Packing sheet %s tidak ditemukan', $data['id_packing_sheet']));

        $updated = $this->db
            ->set('id_surat_pengantar_int', $surat_pengantar['id'])
            ->set('id_packing_sheet_int', $packing_sheet['id'])
            ->where('id', $id)
            ->update($this->table);

        log_message('info', sprintf('Update int relation surat pengantar items MD [%s]', $id));

        return $updated;
    }
}
