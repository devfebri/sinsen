<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher_pengeluaran_fdo extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach($this->db->get()->result_array() as $row){
            $row['index']  = $this->input->post('start') + $index . '.';

            $index++;
            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
        ]);
    }
    
    public function make_query() {
        $jumlah_voucher = $this->db
        ->select('vp.*')
        ->select('vpi.nominal')
        ->from('tr_h3_md_ap_part as ap')
        ->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_referensi = ap.id')
        ->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran')
        ->where('ap.referensi', $this->input->post('invoice_number'))
        ->get_compiled_select();

        $jumlah_voucher_dari_rekap = $this->db
        ->select('vp.*')
        ->select('vpi.nominal')
        ->from('tr_h3_rekap_invoice_ahm_items as riai')
        ->join('tr_h3_md_ap_part as ap', 'ap.referensi = riai.id_rekap_invoice')
        ->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_referensi = ap.id')
        ->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran')
        ->where('riai.invoice_number', $this->input->post('invoice_number'))
        ->get_compiled_select();

        $this->db
        ->select('voucher.id_voucher_pengeluaran')
        ->select('voucher.tanggal_transaksi')
        ->select('voucher.via_bayar')
        ->select('voucher.nominal')
        ->from("
            (
                ({$jumlah_voucher})
                UNION
                ({$jumlah_voucher_dari_rekap})
            ) as voucher
        ");
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('voucher.id_voucher_pengeluaran', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('voucher.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
