<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ap_part extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index;
            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('ap.referensi')
        ->select('ap.jenis_transaksi')
        ->select('date_format(ap.tanggal_transaksi, "%d/%m/%Y") as tanggal_transaksi')
        ->select('date_format(ap.tanggal_jatuh_tempo, "%d/%m/%Y") as tanggal_jatuh_tempo')
        ->select('ap.nama_vendor')
        ->select('ap.total_bayar')
        ->select('ap.total_sudah_dibayar')
        ->select('(ap.total_bayar - ap.total_sudah_dibayar) as sisa_pembayaran')
        ->from('tr_h3_md_ap_part as ap')
        ->where('ap.jenis_transaksi != ', 'rekap_invoice_ahm')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            $this->db->where('ap.lunas', 1);
        }else{
            $this->db->where('ap.lunas', 0);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('filter_no_transaksi') != null) {
            $this->db->where('ap.referensi', trim($this->input->post('filter_no_transaksi')));
        }

        if ($this->input->post('filter_nama_partner') != null) {
            $this->db->like('ap.nama_vendor', trim($this->input->post('filter_nama_partner')));
        }

        if($this->input->post('tgl_transaksi_filter_start') != null and $this->input->post('tgl_transaksi_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('ap.tanggal_transaksi >=', $this->input->post('tgl_transaksi_filter_start'));
            $this->db->where('ap.tanggal_transaksi <=', $this->input->post('tgl_transaksi_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ap.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
