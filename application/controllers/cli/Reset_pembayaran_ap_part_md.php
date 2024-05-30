<?php

class Reset_pembayaran_ap_part_md extends Honda_Controller {

    public function index(){
        $this->load->model('H3_md_voucher_pengeluaran_model', 'voucher_pengeluaran');
        $this->load->model('H3_md_voucher_pengeluaran_items_model', 'voucher_pengeluaran_items');
        $this->load->model('H3_md_entry_pengeluaran_bank_model', 'entry_pengeluaran_bank');
        $this->load->model('H3_md_log_pemotongan_hutang_ap_part_model', 'log_pemotongan_hutang_ap_part');

        $this->voucher_pengeluaran->truncate();
        $this->voucher_pengeluaran_items->truncate();
        $this->entry_pengeluaran_bank->truncate();
        $this->log_pemotongan_hutang_ap_part->truncate();

        $this->db
        ->set('ap.total_sudah_dibayar', 0)
        ->set('ap.lunas', 0)
        ->update('tr_h3_md_ap_part as ap');
    }
}