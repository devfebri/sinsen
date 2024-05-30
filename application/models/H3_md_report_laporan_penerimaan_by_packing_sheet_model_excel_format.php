<?php



class H3_md_report_laporan_penerimaan_by_packing_sheet_model_excel_format extends Honda_Model {

    public function get_data($no_penerimaan_barang){
        $report = $this->db->query("SELECT pb.no_penerimaan_barang, DATE_FORMAT(pb.tanggal_penerimaan,'%d-%m-%Y') as tanggal_penerimaan,DATE_FORMAT(ps.packing_sheet_date,'%d-%m-%Y') as packing_sheet_date,
        pbi.packing_sheet_number, pbi.no_po, pbi.id_part , mp.nama_part, pbi.qty_diterima, pbi.nomor_karton, mp.kelompok_part, 
        lr.kode_lokasi_rak, pbi.serial_number
        from tr_h3_md_penerimaan_barang pb 
        join tr_h3_md_penerimaan_barang_items pbi on pb.id=pbi.no_penerimaan_barang_int
        join tr_h3_md_ps ps on ps.packing_sheet_number=pbi.packing_sheet_number 
        join ms_part mp on mp.id_part_int=pbi.id_part_int 
         join ms_h3_md_lokasi_rak lr on lr.id = pbi.id_lokasi_rak 
        left join ms_h3_md_lokasi_rak_parts lrp on lrp.id_part_int=pbi.id_part_int and lr.id=lrp.id_lokasi_rak 
        where pb.no_penerimaan_barang='$no_penerimaan_barang'");
        return $report;
    }
}
