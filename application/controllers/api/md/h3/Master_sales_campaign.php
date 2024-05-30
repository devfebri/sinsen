<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_sales_campaign extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_sales_campaign', [
                'id' => $row['id']
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
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
        ->select('sc.id')
        ->select('sc.kode_campaign')
        ->select("sc.nama")
        ->select('date_format(sc.start_date, "%d-%m-%Y") as start_date')
        ->select('date_format(sc.end_date, "%d-%m-%Y") as end_date')
        ->select("sc.status")
        ->from('ms_h3_md_sales_campaign as sc')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('sc.status', 'Closed');
        }else{
            $this->db->where('sc.status', 'Open');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->like('sc.kode_campaign', $search);
            $this->db->or_like('sc.nama', $search);
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sc.created_at', 'desc');
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
