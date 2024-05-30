<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Entry_pengeluaran_bank extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_entry_pengeluaran_bank', [
                'id_entry_pengeluaran_bank' => $row['id_entry_pengeluaran_bank']
            ], true);

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'post' => $_POST
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('epb.id_entry_pengeluaran_bank')
        ->select('ifnull(cg.kode_giro, "") as kode_giro')
		->select('vp.tanggal_giro')
		->select('vp.via_bayar')
		->select('vp.nama_penerima_dibayarkan_kepada')
		->select('vp.total_amount')
		->select('epb.status')
        ->from('tr_h3_md_entry_pengeluaran_bank as epb')
		->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id = epb.id_voucher_pengeluaran_int')
		->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left');

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('epb.status', 'Approved');
        }else{
            $this->db->where('epb.status !=', 'Approved');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('epb.id_entry_pengeluaran_bank', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('epb.created_at', 'desc');
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
        return $this->db->count_all_results();
    }
}
