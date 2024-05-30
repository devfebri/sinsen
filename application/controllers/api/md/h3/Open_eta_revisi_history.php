<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Open_eta_revisi_history extends CI_Controller
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
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('date_format(hewh.eta_revisi, "%d/%m/%Y") as eta_revisi')
        ->select('hewh.source')
        ->select('date_format(hewh.created_at, "%d/%m/%Y") as created_at')
        ->from('tr_h3_md_history_estimasi_waktu_hotline as hewh')
        ->where('hewh.id_part', $this->input->post('id_part'))
        ->where('hewh.id_purchase_order', $this->input->post('id_purchase_order'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('hewh.created_at', 'asc');
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