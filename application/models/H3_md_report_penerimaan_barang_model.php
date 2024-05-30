<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_report_penerimaan_barang_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

        public function penerimaan_status_closed($start_date,$end_date)
        {
            $status_closed = $this->db->query("SELECT pb.no_penerimaan_barang, DATE_FORMAT(pb.tanggal_penerimaan,'%d-%m-%Y') as tanggal_penerimaan, pbi.packing_sheet_number, pb.no_surat_jalan_ekspedisi as no_resi, pbi.no_po, pbi.id_part , mp.nama_part, 
            pbi.qty_packing_sheet, pbi.qty_diterima, pbi.nomor_karton, mp.kelompok_part 
            from tr_h3_md_penerimaan_barang pb 
            join tr_h3_md_penerimaan_barang_items pbi on pb.id=pbi.no_penerimaan_barang_int 
            join ms_part mp on mp.id_part_int=pbi.id_part_int 
            where pb.status='Closed'
            and pb.tanggal_penerimaan >= '$start_date' and pb.tanggal_penerimaan <= '$end_date'");
            return $status_closed;
        }

	}	
?>