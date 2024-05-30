<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Open_view_packing_sheet_number_pelunasan_bapb extends CI_Controller
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
        $packing_sheet_number = $this->db
        ->select('DISTINCT(pli.packing_sheet_number)')
        ->from('tr_h3_md_pelunasan_bapb_items as pli')
        ->where('pli.no_pelunasan', $this->input->post('no_pelunasan'))
        ->get_compiled_select();

        $this->db
        ->select('ps.packing_sheet_number')
        ->select('ps.packing_sheet_date')
        ->from('tr_h3_md_ps as ps')
        ->where("ps.packing_sheet_number in ({$packing_sheet_number})", null, false)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.packing_sheet_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'desc');
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