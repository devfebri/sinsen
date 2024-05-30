<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Voucher_pengeluaran extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_voucher_pengeluaran', [
                'id_voucher_pengeluaran' => $row['id_voucher_pengeluaran']
            ], true);

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('vp.id_voucher_pengeluaran')
        ->select('vp.tanggal_transaksi')
        ->select('vp.nama_penerima_dibayarkan_kepada')
        ->select('vp.total_amount')
        ->select('rek.bank')
        ->select('cg.kode_giro')
        ->select('vp.nominal_giro')
        ->select('vp.status')
        ->from('tr_h3_md_voucher_pengeluaran as vp')
        ->join('ms_rek_md as rek', 'rek.id_rek_md = vp.id_account')
        ->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('vp.status !=', 'Open');
        }else{
            $this->db->where('vp.status', 'Open');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('vp.id_voucher_pengeluaran', $search);
            $this->db->or_like('vp.nama_penerima_dibayarkan_kepada', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('vp.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
