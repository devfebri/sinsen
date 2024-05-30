<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Receive_part_request_nrfs extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['action'] = $this->load->view('additional/md/h3/action_index_receive_part_request_nrfs_datatable', [
                'request_id' => $row['request_id']
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
        ->select('prn.request_id')
        ->select('prn.dokumen_nrfs_id')
        ->select('dn.no_shiping_list')
        ->select('dn.no_mesin')
        ->select('dn.no_rangka')
        ->select('dn.type_code')
        ->select('dn.sumber_rfs_nrfs')
        ->select('prn.status_request')
        ->from('tr_part_request_nrfs as prn')
        ->join('tr_dokumen_nrfs as dn', 'dn.dokumen_nrfs_id = prn.dokumen_nrfs_id', 'left')
        ->where('dn.sumber_rfs_nrfs', 'MD')
        ->where('prn.status_request', 'Open')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('no_part_request_filter') != null) {
            $this->db->like('prn.request_id', trim($this->input->post('no_part_request_filter')));
        }

        if ($this->input->post('no_dokumen_nrfs_filter') != null) {
            $this->db->like('prn.dokumen_nrfs_id', trim($this->input->post('no_dokumen_nrfs_filter')));
        }

        if ($this->input->post('no_shipping_list_filter') != null) {
            $this->db->like('prn.no_shiping_list', trim($this->input->post('no_shipping_list_filter')));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('prn.created_at', 'desc');
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
