<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher_pengeluaran_entry_pengeluaran_bank extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_voucher_pengeluaran_entry_pengeluaran_bank', [
                'row' => $row
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
    
    public function make_query() {
        $this->db
        ->select('vp.id')
        ->select('vp.id_voucher_pengeluaran')
        ->select('vp.tanggal_transaksi')
        ->select('vp.tipe_penerima')
        ->select('vp.via_bayar')
        ->select('vp.deskripsi')
        ->select('vp.total_amount')
        ->from('tr_h3_md_voucher_pengeluaran as vp')
        ->where('vp.status', 'Open');
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('vp.id_voucher_pengeluaran', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('vp.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsTotal() {
        $this->make_query();

        return $this->db->get()->num_rows();
    }

    public function recordsFiltered() {
        $this->make_datatables();

        return $this->db->get()->num_rows();
    }
}
