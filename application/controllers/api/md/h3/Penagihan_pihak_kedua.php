<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penagihan_pihak_kedua extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_penagihan_pihak_kedua', [
                'id' => $row['id']
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
        ->select('p.id')
        ->select('p.no_surat')
        ->select('p.created_at')
        ->select('p.tgl_surat')
        ->select('"-" as nama_tujuan_penagihan')
        ->select('p.referensi')
        ->select('p.nominal')
        ->select('p.status')
        ->from('tr_h3_md_penagihan_pihak_kedua as p');

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('p.status', 'Approved');
        }else{
            $this->db->where('p.status !=', 'Approved');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.no_surat', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.created_at', 'desc');
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
