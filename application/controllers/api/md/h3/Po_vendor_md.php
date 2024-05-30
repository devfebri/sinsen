<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Po_vendor_md extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            // $row['action'] = $this->load->view('additional/md/h3/action_index_po_vendor_datatable', [
            //     'id_po_vendor' => $row['id_po_vendor'],
            //     'status' => $row['status'],
            // ], true);

            $row['id_po_vendor'] = $this->load->view('additional/md/h3/action_view_po_vendor_datatable', [
                'id_po_vendor' => $row['id_po_vendor']
            ], true);
            
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
        ->select('date_format(pov.tanggal, "%d-%m-%Y") as tanggal')
        ->select('pov.id_po_vendor')
        ->select('v.vendor_name')
        ->select('
            concat(
                "Rp ",
                format(pov.total, 0, "ID_id")
            ) as total
        ', false)
        ->select('pov.status')
        ->from('tr_h3_md_po_vendor as pov')
        ->join('ms_vendor as v', 'v.id_vendor = pov.id_vendor')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('pov.status', 'Canceled');
            $this->db->or_where('pov.status', 'Closed');
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('left(pov.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->where('pov.status', 'Open');
            $this->db->or_where('pov.status', 'Processed');
            $this->db->group_end();
            $this->db->group_start();
            $this->db->where('left(pov.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pov.id_po_vendor', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pov.created_at', 'desc');
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
