<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_laporan_ev_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

        public function report($start_date,$end_date)
        {
            $report = $this->db->query("SELECT id_part,nama_part, kelompok_part,  (CASE WHEN et.type_accesories='B'then 'Battery' WHEN et.type_accesories='C' then 'Charger' end) as type_acc, serial_number, (CASE WHEN et.accStatus=2 THEN 'Penerimaan MD' WHEN et.accStatus=3 THEN 
            'Pengeluaran MD' WHEN et.accStatus=4 THEN 'Penerimaan Dealer' WHEN et.accStatus=9 THEN 'Penjualan/Billing Process' End) as accStatus, 
            et.no_shipping_list, DATE_FORMAT(et.created_at_shipping_list, '%d-%m-%Y') as tgl_sl, et.no_penerimaan_barang_md, DATE_FORMAT(et.created_at_penerimaan_md , '%d-%m-%Y') as tgl_penerimaan_md, 
            et.kode_lokasi_rak_md, et.fifo, et.id_do_sales_order, et.no_faktur, DATE_FORMAT(et.created_at_faktur , '%d-%m-%Y') as tgl_faktur, 
            et.id_packing_sheet, DATE_FORMAT(et.created_at_packing_sheet , '%d-%m-%Y') as tgl_packing_sheet, id_surat_pengantar_md, 
            DATE_FORMAT(et.created_at_surat_pengantar , '%d-%m-%Y') as tgl_surat_pengantar, md.nama_dealer, et.id_penerimaan_dealer, 
            DATE_FORMAT(et.created_at_penerimaan_dealer , '%d-%m-%Y') as tgl_penerimaan_dealer, et.id_lokasi_rak_dealer, et.id_gudang_dealer, et.no_nsc, 
            DATE_FORMAT(et.created_at_nsc , '%d-%m-%Y') as tgl_nsc, et.nama_customer, et.no_mesin, et.no_rangka, et.no_hp 
            from tr_h3_serial_ev_tracking et 
            left join ms_dealer md on et.id_dealer=md.id_dealer 
            WHERE et.created_at_shipping_list >= '$start_date 00:00:00' and et.created_at_shipping_list <= '$end_date 23:59:59'");
            return $report;
        }

	}	
?>