<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fdo extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_index_fdo_datatable', [
                'id' => $row['invoice_number']
            ], true);

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
        $jumlah_voucher = $this->db
        ->select('count(vpi.id) as jumlah')
        ->from('tr_h3_md_ap_part as ap')
        ->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_referensi = ap.id')
        ->where('ap.referensi = fdo.invoice_number', null, false)
        ->get_compiled_select();

        $jumlah_voucher_dari_rekap = $this->db
        ->select('count(vpi.id) as jumlah')
        ->from('tr_h3_rekap_invoice_ahm_items as riai')
        ->join('tr_h3_md_ap_part as ap', 'ap.referensi = riai.id_rekap_invoice')
        ->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_referensi = ap.id')
        ->where('riai.invoice_number = fdo.invoice_number', null, false)
        ->get_compiled_select();

        $this->db
        ->select('fdo.*')
        ->select("(IFNULL(({$jumlah_voucher}), 0) + IFNULL(({$jumlah_voucher_dari_rekap}), 0)) as jumlah_voucher", false)
        ->from('tr_h3_md_fdo as fdo');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('fdo.invoice_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('fdo.invoice_date', 'desc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
