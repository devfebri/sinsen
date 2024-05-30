<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Po_umum extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/action_po_umum', [
                'id_purchase_order' => $row['id_purchase_order']
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
        ->select('pu.id_purchase_order')
        ->select('pu.created_at')
        ->select('v.vendor_name')
        ->select('pu.divisi')
        ->select('pu.grand_total')
        ->select('pu.keterangan')
        ->from('tr_h3_md_po_umum as pu')
        ->join('ms_vendor as v', 'v.id_vendor = pu.id_vendor');
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.id_purchase_order', $search);
            $this->db->or_like('v.vendor_name', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pu.created_at', 'desc');
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
