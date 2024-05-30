<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jawaban_claim_dealer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_jawaban_claim_dealer_datatable', [
                'id_jawaban_claim_dealer' => $row['id_jawaban_claim_dealer']
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
        ->select('date_format(jcd.created_at, "%d-%m-%Y") as created_at')
        ->select('jcd.id_jawaban_claim_dealer')
        ->select('jcd.id_claim_part_ahass')
        ->select('jcd.no_surat_jalan_ahm')
        ->from('tr_h3_md_jawaban_claim_dealer as jcd')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('jcd.status', 'Closed');
        }else{
            $this->db->where('jcd.status !=', 'Closed');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('jcd.id_jawaban_claim_dealer', $search);
            $this->db->or_like('jcd.id_claim_part_ahass', $search);
            $this->db->or_like('jcd.no_surat_jalan_ahm', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('jcd.created_at', 'desc');
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
