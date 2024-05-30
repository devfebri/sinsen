<?php

class Reset_md extends Honda_Controller
{
    public function index()
    {
        $this->surat_pengantar_md();
        $this->packing_sheet_md();
        $this->picking_list_md();
        $this->delivery_order_md();
        $this->do_revisi_md();
        $this->purchase_order_dealer();
        $this->proses_barang_bagi();
        $this->sales_order_md();
        // $this->stock_md();
        // $this->lokasi();
        die;

        $this->surat_jalan_ahm();
        $this->ar_ap_part();
        $this->penerimaan_barang_md();
        $this->packing_sheet_ahm();
        $this->faktur_ahm();
        $this->claim_dealer_md();
        $this->po_vendor();
        $this->mutasi_gudang();
        $this->monitor_file_transfer();
        $this->monitor_ptrac();
        $this->rekap_purchase_order_dealer();
        $this->proses_barang_bagi();
        $this->finance();
        $this->claim_ke_ahm();
        $this->purchase_order_md();
        $this->plafon_md();
    }

    public function purchase_order_dealer()
    {
        $sales_order_md = $this->db
            ->select('so.id')
            ->select('so.id_sales_order')
            ->select('so.id_ref as po_id')
            ->select('so.status')
            ->from('tr_h3_md_sales_order as so')
            ->where('so.created_by_md', 1)
            ->get()->result_array();

        foreach ($sales_order_md as $sales_order) {
            $this->db
                ->where('po_id', $sales_order['po_id'])
                ->delete('tr_h3_dealer_purchase_order');

            log_message('debug', sprintf('Menghapus purchase order dealer %s', $sales_order['po_id']));

            $this->db
                ->where('po_id', $sales_order['po_id'])
                ->delete('tr_h3_dealer_purchase_order_parts');

            $this->db
                ->where('po_id', $sales_order['po_id'])
                ->delete('tr_h3_dealer_order_parts_tracking');

            $this->db
                ->where('po_id', $sales_order['po_id'])
                ->delete('tr_h3_md_pemenuhan_po_dari_dealer');

            log_message('debug', sprintf('Menghapus purchase order dealer %s', $sales_order['po_id']));

            $this->db
                ->where('id_sales_order', $sales_order['id_sales_order'])
                ->delete('tr_h3_md_sales_order_parts');


            $this->db
                ->select('id')
                ->select('id_do_sales_order')
                ->from('tr_h3_md_do_sales_order')
                ->where('id_sales_order', $sales_order['id_sales_order']);
            foreach ($this->db->get()->result_array() as $delivery_order) {

                $this->db
                    ->select('id')
                    ->select('id_picking_list')
                    ->from('tr_h3_md_picking_list')
                    ->where('id_ref_int', $delivery_order['id']);
                foreach ($this->db->get()->result_array() as $picking_list) {

                    $this->db
                        ->select('id')
                        ->select('id_packing_sheet')
                        ->from('tr_h3_md_packing_sheet')
                        ->where('id_picking_list', $picking_list['id_picking_list']);
                    foreach ($this->db->get()->result_array() as $packing_sheet) {

                        $this->db
                            ->from('tr_h3_md_surat_pengantar_items')
                            ->where('id_packing_sheet', $packing_sheet['id_packing_sheet']);
                        foreach ($this->db->get()->result_array() as $surat_pengantar_item) {

                            $this->db
                                ->where('id', $surat_pengantar_item['id'])
                                ->delete('tr_h3_md_surat_pengantar_items');

                            $this->db
                                ->where('id_surat_pengantar', $surat_pengantar_item['id_surat_pengantar'])
                                ->delete('tr_h3_md_surat_pengantar');
                        }

                        $this->db
                            ->where('id', $packing_sheet['id'])
                            ->delete('tr_h3_md_packing_sheet');
                    }

                    $this->db
                        ->where('id_picking_list', $picking_list['id_picking_list'])
                        ->delete('tr_h3_md_picking_list_parts');

                    $this->db
                        ->where('id_picking_list', $picking_list['id_picking_list'])
                        ->delete('tr_h3_md_scan_picking_list_parts');

                    $this->db
                        ->where('id', $picking_list['id'])
                        ->delete('tr_h3_md_picking_list');
                }

                $this->db
                    ->where('id', $delivery_order['id'])
                    ->delete('tr_h3_md_do_sales_order');
            }

            $this->db
                ->where('id', $sales_order['id'])
                ->delete('tr_h3_md_sales_order');

            echo sprintf('Menghapus data SO %s', $sales_order['id_sales_order']);
            echo '<br>';
        }
    }

    public function sales_order_md()
    {
        $this->db->empty_table('tr_h3_md_sales_order');
        $this->db->empty_table('tr_h3_md_sales_order_parts');

        log_message('debug', 'Sales order MD direset');
    }

    public function delivery_order_md()
    {
        $this->db->empty_table('tr_h3_md_do_sales_order');
        $this->db->empty_table('tr_h3_md_do_sales_order_cashback');
        $this->db->empty_table('tr_h3_md_do_sales_order_gimmick');
        $this->db->empty_table('tr_h3_md_do_sales_order_parts');

        log_message('debug', 'Delivery order MD direset');
    }

    public function picking_list_md()
    {
        $this->db->empty_table('tr_h3_md_scan_picking_list_parts');
        $this->db->empty_table('tr_h3_md_picking_list_parts');
        $this->db->empty_table('tr_h3_md_picking_list');


        log_message('debug', 'Picking list MD direset');
    }

    public function do_revisi_md()
    {
        $this->db->empty_table('tr_h3_md_do_revisi_cashback');
        $this->db->empty_table('tr_h3_md_do_revisi_detail_item');
        $this->db->empty_table('tr_h3_md_do_revisi_item');
        $this->db->empty_table('tr_h3_md_do_revisi');

        log_message('debug', 'DO revisi MD direset');
    }

    public function packing_sheet_md()
    {
        $this->db->empty_table('tr_h3_md_packing_sheet');

        log_message('debug', 'Packing sheet MD direset');
    }

    public function surat_pengantar_md()
    {
        $this->db->empty_table('tr_h3_md_surat_pengantar');
        $this->db->empty_table('tr_h3_md_surat_pengantar_items');

        log_message('debug', 'Surat pengantar MD direset');
    }

    public function ar_ap_part()
    {
        $this->db->empty_table('tr_h3_md_ar_part');
        $this->db->empty_table('tr_h3_md_ap_part');
        $this->db->empty_table('tr_h3_md_log_pemotongan_hutang_ap_part');

        log_message('debug', 'AR dan AP part di reset');
    }

    public function stock_md()
    {
        $this->db->empty_table('tr_stok_part');
        log_message('debug', 'Stock MD di reset');

        $this->db->empty_table('tr_h3_md_kartu_stock');
        log_message('debug', 'Kartu stock MD di reset');
    }

    public function lokasi()
    {
        $this->db->empty_table('ms_h3_md_lokasi_rak_parts');
        $this->db->empty_table('ms_h3_md_lokasi_rak');
        log_message('debug', 'Lokasi MD di reset');
    }

    public function penerimaan_barang_md()
    {
        $this->db->empty_table('tr_h3_md_berita_acara_penerimaan_barang');
        $this->db->empty_table('tr_h3_md_berita_acara_penerimaan_barang_items');
        $this->db->empty_table('tr_h3_md_penerimaan_barang');
        $this->db->empty_table('tr_h3_md_penerimaan_barang_items');
        $this->db->empty_table('tr_h3_md_penerimaan_barang_jumlah_koli');
        $this->db->empty_table('tr_h3_md_penerimaan_barang_reasons');
        $this->db->empty_table('tr_h3_md_penerimaan_barang_surat_jalan_ahm');
        $this->db->empty_table('tr_h3_md_pelunasan_bapb');
        $this->db->empty_table('tr_h3_md_pelunasan_bapb_items');

        log_message('debug', 'Penerimaan barang MD di reset');
    }

    public function packing_sheet_ahm()
    {
        $this->db->empty_table('tr_h3_md_ps');
        $this->db->empty_table('tr_h3_md_ps_parts');
        $this->db->empty_table('tr_h3_md_nomor_karton');

        log_message('debug', 'Packing sheet AHM di reset');
    }

    public function surat_jalan_ahm()
    {
        $this->db->empty_table('tr_h3_md_psl');
        $this->db->empty_table('tr_h3_md_psl_items');

        log_message('debug', 'Surat jalan AHM di reset');
    }

    public function faktur_ahm()
    {
        $this->db->empty_table('tr_h3_md_fdo');
        $this->db->empty_table('tr_h3_md_fdo_parts');
        $this->db->empty_table('tr_h3_md_fdo_ps');

        log_message('debug', 'Faktur AHM di reset');
    }

    public function claim_dealer_md()
    {
        $this->db->empty_table('tr_h3_md_claim_dealer');
        $this->db->empty_table('tr_h3_md_claim_dealer_parts');
        $this->db->empty_table('tr_h3_md_jawaban_claim_dealer');
        $this->db->empty_table('tr_h3_md_jawaban_claim_dealer_parts');
        $this->db->empty_table('tr_h3_md_claim_part_ahass');
        $this->db->empty_table('tr_h3_md_claim_part_ahass_parts');
        $this->db->empty_table('tr_h3_md_surat_pengantar_claim_c3_dealer');
        $this->db->empty_table('tr_h3_md_surat_pengantar_claim_c3_dealer_item');

        log_message('debug', 'Claim dealer di reset');
    }

    public function po_vendor()
    {
        $this->db->empty_table('tr_h3_md_penerimaan_po_vendor');
        $this->db->empty_table('tr_h3_md_penerimaan_po_vendor_parts');
        $this->db->empty_table('tr_h3_md_po_vendor');
        $this->db->empty_table('tr_h3_md_po_vendor_parts');
        $this->db->empty_table('tr_h3_md_penerimaan_manual');
        $this->db->empty_table('tr_h3_md_penerimaan_manual_parts');

        log_message('debug', 'PO Vendor di reset');
    }

    public function mutasi_gudang()
    {
        $this->db->empty_table('tr_h3_md_mutasi_gudang');

        log_message('debug', 'Mutasi gudang di reset');
    }

    public function monitor_file_transfer()
    {
        $this->db->empty_table('tr_h3_md_file_transfer');

        log_message('debug', 'Monitor file transfer di reset');
    }

    public function monitor_ptrac()
    {
        $this->db->empty_table('ms_ptrac');
        log_message('debug', 'Monitor ptrac di reset');
    }

    public function rekap_purchase_order_dealer()
    {
        $this->db->empty_table('tr_h3_md_rekap_purchase_order_dealer');
        $this->db->empty_table('tr_h3_md_rekap_purchase_order_dealer_item');
        $this->db->empty_table('tr_h3_md_rekap_purchase_order_dealer_parts');

        log_message('debug', 'Rekap purchase order dealer di reset');
    }

    public function proses_barang_bagi()
    {
        $this->db->empty_table('tr_h3_proses_barang_bagi');
        $this->db->empty_table('tr_h3_proses_barang_bagi_items');
        $this->db->empty_table('tr_h3_proses_barang_bagi_kelompok_parts');

        log_message('debug', 'Proses barang bagi di reset');
    }

    public function finance()
    {
        $this->db->empty_table('tr_h3_rekap_invoice_ahm');
        $this->db->empty_table('tr_h3_rekap_invoice_ahm_items');
        log_message('debug', 'Rekap invoice AHM di reset');

        $this->db->empty_table('tr_h3_md_tanda_terima_faktur');
        $this->db->empty_table('tr_h3_md_tanda_terima_faktur_item');
        log_message('debug', 'Tanda terima faktur di reset');

        $this->db->empty_table('tr_h3_md_berita_acara_penyerahan_faktur');
        $this->db->empty_table('tr_h3_md_berita_acara_penyerahan_faktur_item');
        log_message('debug', 'Berita acara penyerahan faktur di reset');

        $this->db->empty_table('tr_h3_md_voucher_pengeluaran');
        $this->db->empty_table('tr_h3_md_voucher_pengeluaran_items');
        log_message('debug', 'Voucher pengeluaran di reset');

        $this->db->empty_table('tr_h3_md_penerimaan_pembayaran');
        $this->db->empty_table('tr_h3_md_penerimaan_pembayaran_item');
        log_message('debug', 'Penerimaan pembayaran di reset');

        $this->db->empty_table('tr_h3_md_invoice_ekspedisi');
        $this->db->empty_table('tr_h3_md_invoice_ekspedisi_item');
        log_message('debug', 'Invoice ekspedisi di reset');

        $this->db->empty_table('tr_h3_md_serah_terima_sj');
        $this->db->empty_table('tr_h3_md_serah_terima_sj_item');
        log_message('debug', 'Serah terima SJ di reset');

        $this->db->empty_table('tr_h3_md_po_umum');
        $this->db->empty_table('tr_h3_md_po_umum_parts');
        $this->db->empty_table('tr_h3_md_po_umum_penanggung');
        log_message('debug', 'PO Umum di reset');

        $this->db->empty_table('tr_h3_md_entry_pengeluaran_bank');
        log_message('debug', 'Entry pengeluaran bank di reset');

        $this->db->empty_table('tr_h3_md_penagihan_pihak_kedua');
        log_message('debug', 'Penagihan pihak kedua di reset');
    }

    public function claim_ke_ahm()
    {
        $this->db->empty_table('tr_h3_md_claim_main_dealer_ke_ahm');
        $this->db->empty_table('tr_h3_md_claim_main_dealer_ke_ahm_item');
        $this->db->empty_table('tr_h3_md_terima_claim_ahm');
        $this->db->empty_table('tr_h3_md_terima_claim_ahm_item');
        $this->db->empty_table('tr_h3_md_retur_pembelian_claim');
        $this->db->empty_table('tr_h3_md_retur_pembelian_claim_items');

        log_message('debug', 'Terima claim direset');
    }

    public function purchase_order_md()
    {
        $this->db->empty_table('tr_h3_md_back_order_ahm_item_upload');
        $this->db->empty_table('tr_h3_md_back_order_ahm_upload');
        $this->db->empty_table('tr_h3_md_purchase_order');
        $this->db->empty_table('tr_h3_md_purchase_order_parts');
        $this->db->empty_table('tr_h3_md_niguri');
        $this->db->empty_table('tr_h3_md_niguri_header');

        log_message('debug', 'Purchase order MD direset');
    }

    public function plafon_md()
    {
        $this->db->empty_table('ms_h3_md_plafon_sales_orders');
        $this->db->empty_table('ms_h3_md_plafon');
        log_message('debug', 'Plafon MD direset');
    }
}
