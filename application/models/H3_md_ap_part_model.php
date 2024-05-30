<?php

class H3_md_ap_part_model extends Honda_Model{

    protected $table = 'tr_h3_md_ap_part';

    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');
    }

    public function insert($data){
        $data['created_at'] = Mcarbon::now()->toDateTimeString();
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function insentif_poin($id_dealer = null, $sql = false){
        $this->db
        ->select('IFNULL(
            SUM( (ap.total_bayar - ap.total_sudah_dibayar) ),
            0
        ) as insentif', false)
        ->from('tr_h3_md_ap_part as ap')
        ->where('ap.jenis_transaksi', 'perolehan_insentif_sales_campaign')
        ;

        if($id_dealer != null) $this->db->where('ap.id_dealer', $id_dealer);
        
        if($sql){
            return $this->db->get_compiled_select();
        }else{
            $data = $this->db->get()->row_array();

            return $data['insentif'];
        }
    }

    public function bayar($id, $nominal, $id_entry_pengeluaran_bank = null, $id_rekap_invoice = null){
        $ap_part = $this->db->from($this->table)->where('id', $id)->limit(1)->get()->row_array();
        $entry_pengeluaran = $this->db
        ->select('epb.id as id_entry_pengeluaran_bank_int')
        ->select('epb.id_entry_pengeluaran_bank')
        ->select('epb.id_voucher_pengeluaran_int')
        ->select('vp.id_voucher_pengeluaran')
        ->from('tr_h3_md_entry_pengeluaran_bank as epb')
        ->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id = epb.id_voucher_pengeluaran_int')
        ->where('epb.id', $id_entry_pengeluaran_bank)
        ->get()->row_array();

        if($ap_part == null OR $entry_pengeluaran == null) return;

        $log_pemotongan_hutang_ap_part = [
            'id_ap_part' => $ap_part['id'],
            'referensi' => $ap_part['referensi'],
            'id_entry_pengeluaran_bank_int' => $entry_pengeluaran['id_entry_pengeluaran_bank_int'],
            'id_entry_pengeluaran_bank' => $entry_pengeluaran['id_entry_pengeluaran_bank'],
            'id_voucher_pengeluaran_int' => $entry_pengeluaran['id_voucher_pengeluaran_int'],
            'id_voucher_pengeluaran' => $entry_pengeluaran['id_voucher_pengeluaran'],
            'nominal_piutang' => floatval($ap_part['total_bayar']) - floatval($ap_part['total_sudah_dibayar']),
            'nominal_pembayaran' => floatval($nominal),
            'id_rekap_invoice_ahm' => $id_rekap_invoice
        ];
        $log_pemotongan_hutang_ap_part['sisa_piutang'] = $log_pemotongan_hutang_ap_part['nominal_piutang'] - $log_pemotongan_hutang_ap_part['nominal_pembayaran'];

        $this->load->model('H3_md_log_pemotongan_hutang_ap_part_model', 'log_pemotongan_hutang_ap_part');
        $this->log_pemotongan_hutang_ap_part->insert($log_pemotongan_hutang_ap_part);

        $this->db
        ->set('ap.total_sudah_dibayar', "ap.total_sudah_dibayar + {$nominal}", false)
        ->where('ap.id', $id)
        ->update("{$this->table} as ap");

        log_message('info', sprintf('AP PART [%s] dibayar dengan nominal %s', $ap_part['referensi'], $nominal));
        
        $this->set_lunas($id);
    }

    public function set_lunas($id){
        $ap_part = $this->db->from($this->table)->where('id', $id)->limit(1)->get()->row_array();

        if($ap_part == null) return;

        if(floatval($ap_part['total_bayar']) == floatval($ap_part['total_sudah_dibayar'])){
            $this->db
            ->set('ap.lunas', 1)
            ->where('ap.id', $id)
            ->update("{$this->table} as ap");

            log_message('info', sprintf('AP PART [%s] sudah lunas', $ap_part['referensi']));
        }
    }

}
