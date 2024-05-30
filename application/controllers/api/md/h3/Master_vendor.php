<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_vendor extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_vendor', [
                'id_vendor' => $row['id_vendor']
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            $data[] = $row;
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
        ->select('v.id_vendor')
        ->select('v.vendor_name')
        ->select('v.no_telp')
        ->select('v.alamat')
        ->select('vt.vendor_type')
        ->select('v.ppn')
        ->select('v.no_rekening')
        ->select('v.nama_rekening as nama_bank')
        ->from('ms_vendor as v')
        ->join('ms_vendor_type as vt', 'vt.id_vendor_type = v.id_vendor_type', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('v.id_vendor', $search);
            $this->db->or_like('v.vendor_name', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('v.id_vendor', 'asc');
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
        return $this->db->count_all_results();
    }
}
