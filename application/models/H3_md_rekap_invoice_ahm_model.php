<?php

class H3_md_rekap_invoice_ahm_model extends Honda_Model{

    protected $table = 'tr_h3_rekap_invoice_ahm';

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_ap_part_model', 'ap_part');
    }

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['status'] = 'Unpaid';
		
        parent::insert($data);
    }

    public function create_ap($id_rekap_invoice){
        $invoice = $this->db
        ->select('"2.03.21012.02" as nomor_account')
        ->select('"AHM" as id_referensi_table')
        ->select('"ms_vendor" as referensi_table')
        ->select('ria.id_rekap_invoice as referensi')
        ->select('"rekap_invoice_ahm" as jenis_transaksi')
        ->select('DATE_FORMAT(ria.created_at, "%Y-%m-%d") as tanggal_transaksi')
        ->select('ria.tgl_jatuh_tempo as tanggal_jatuh_tempo')
        ->select('"AHM" as nama_vendor')
        ->select('(ria.total_dpp + ria.total_ppn) as total_bayar', false)
        ->from('tr_h3_rekap_invoice_ahm as ria')
        ->where('ria.id_rekap_invoice', $id_rekap_invoice)
        ->limit(1)
        ->get()->row_array();

        if($invoice != null){
            $ap_part = $this->ap_part->get([
                'referensi' => $invoice['referensi'],
                'tanggal_jatuh_tempo' => $invoice['tanggal_jatuh_tempo'],
            ], true);
            if($ap_part == null){
                $this->ap_part->insert($invoice);
            }
        }

        $this->set_id_rekap_ke_invoice_ahm($id_rekap_invoice);
    }

    public function set_id_rekap_ke_invoice_ahm($id_rekap_invoice){
        $this->db
        ->select('ria.id_rekap_invoice')
        ->select('riai.invoice_number')
        ->select('ria.tgl_jatuh_tempo')
        ->from('tr_h3_rekap_invoice_ahm_items as riai')
        ->join('tr_h3_rekap_invoice_ahm as ria', 'ria.id_rekap_invoice = riai.id_rekap_invoice')
        ->where('riai.id_rekap_invoice', $id_rekap_invoice);

        foreach($this->db->get()->result_array() as $row){
            $this->db
            ->set('ap.id_rekap_invoice_ahm', $row['id_rekap_invoice'])
            ->where('ap.referensi', $row['invoice_number'])
            ->where('ap.tanggal_jatuh_tempo', $row['tgl_jatuh_tempo'])
            ->update('tr_h3_md_ap_part as ap');
        }
    }

    public function distribusi_pembayaran_ke_faktur($id_rekap_invoice, $nominal, $id_entry_pengeluaran_bank = null){
        $this->db
        ->select('ap.id')
        ->select('ap.referensi')
        ->select('ap.tanggal_jatuh_tempo')
        ->select('(ap.total_bayar - ap.total_sudah_dibayar) as sisa_piutang')
        ->from('tr_h3_rekap_invoice_ahm as ria')
        ->join('tr_h3_rekap_invoice_ahm_items as riai', 'riai.id_rekap_invoice = ria.id_rekap_invoice')
        ->join('tr_h3_md_ap_part as ap', '(ap.referensi = riai.invoice_number AND ap.tanggal_jatuh_tempo = ria.tgl_jatuh_tempo)')
        ->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = ap.referensi')
        ->where('ria.id_rekap_invoice', $id_rekap_invoice)
        ->order_by('fdo.invoice_date', 'asc')
		->order_by('fdo.invoice_number', 'asc');

        foreach ($this->db->get()->result_array() as $row) {
            $sisa_piutang = floatval($row['sisa_piutang']);
            if($sisa_piutang >= $nominal){
                $this->ap_part->bayar($row['id'], $nominal, $id_entry_pengeluaran_bank, $id_rekap_invoice);
                break;
            }else{
                $this->ap_part->bayar($row['id'], $sisa_piutang, $id_entry_pengeluaran_bank, $id_rekap_invoice);
                $nominal -= $sisa_piutang;
            }
        }
    }

    
}
