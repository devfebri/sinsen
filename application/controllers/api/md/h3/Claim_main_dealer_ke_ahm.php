<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Claim_main_dealer_ke_ahm extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_index_claim_main_dealer_ke_ahm_datatable', [
                'id_claim' => $row['id_claim']
            ], true);
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
        ->select('date_format(cmd.created_at, "%d-%m-%Y") as created_at')
        ->select('cmd.id_claim')
        ->select('cmd.packing_sheet_number')
        ->select('fdo.invoice_number')
        ->select('cmd.status')
        ->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
		->join('tr_h3_md_fdo as fdo', 'fdo.id = cmd.invoice_number_int', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('cmd.id_claim', $search);
            $this->db->or_like('fdo.invoice_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('cmd.id', 'desc');
            $this->db->order_by('cmd.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
