<?php

class Berita_acara_penerimaan_barang extends Honda_Controller
{
    public function items()
    {
        $list_bapb = $this->db
            ->select('bai.no_bapb')
            ->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
            ->group_start()
            ->or_where('bai.nomor_karton_int is null', null, false)
            ->or_where('bai.packing_sheet_number_int is null', null, false)
            ->or_where('bai.surat_jalan_ahm_int is null', null, false)
            ->group_end()
            ->get()->result_array();

        foreach ($list_bapb as $row) {
            $bapb = $this->db
                ->select('no_bapb')
                ->select('no_surat_jalan_ekspedisi')
                ->from('tr_h3_md_berita_acara_penerimaan_barang as ba')
                ->where('ba.no_bapb', $row['no_bapb'])
                ->limit(1)
                ->get()->row_array();

            $items = $this->db
                ->from('tr_h3_md_berita_acara_penerimaan_barang_items as bai')
                ->where('bai.no_bapb', $bapb['no_bapb'])
                ->limit(1)
                ->get()->result_array();

            foreach ($items as $item) {
                $penerimaan_barang_item = $this->db
                    ->select('pbi.no_po_int')
                    ->select('pbi.nomor_karton_int')
                    ->select('pbi.packing_sheet_number_int')
                    ->select('pbi.surat_jalan_ahm_int')
                    ->from('tr_h3_md_penerimaan_barang_items as pbi')
                    ->where('pbi.no_surat_jalan_ekspedisi', $bapb['no_surat_jalan_ekspedisi'])
                    ->where('pbi.id_part', $item['id_part'])
                    ->where('pbi.no_po', $item['no_po'])
                    ->where('pbi.surat_jalan_ahm', $item['surat_jalan_ahm'])
                    ->where('pbi.nomor_karton', $item['nomor_karton'])
                    ->where('pbi.packing_sheet_number', $item['packing_sheet_number'])
                    ->limit(1)
                    ->get()->row_array();

                $this->db
                    ->set($penerimaan_barang_item)
                    ->where('id', $item['id'])
                    ->update('tr_h3_md_berita_acara_penerimaan_barang_items');
            }
        }
    }
}
