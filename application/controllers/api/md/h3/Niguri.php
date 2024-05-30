<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Niguri extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_niguri', [
                'id' => $row['id'],
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
    private function make_query()
    {
        $this->db
        ->select('nh.id')
        ->select('DATE_FORMAT(nh.tanggal_generate, "%m/%Y") as tanggal_generate', false)
        ->select('nh.type_niguri')
        ->select('nh.status')
        ->select('DATE_FORMAT(nh.created_at, "%d/%m/%Y %H:%i:%s") as created_at', false)
        ->select('DATE_FORMAT(nh.updated_at, "%d/%m/%Y %H:%i:%s") as updated_at', false)
        ->from('tr_h3_md_niguri_header as nh')
        ;
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->or_where('left(nh.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('left(nh.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    private function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search')['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('n.id_part', $search);
        //     $this->db->group_end();
        // }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('nh.tanggal_generate', 'desc');
        }
    }

    private function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    private function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    private function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
