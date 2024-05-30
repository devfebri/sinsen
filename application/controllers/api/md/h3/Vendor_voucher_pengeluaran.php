<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendor_voucher_pengeluaran extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_vendor_voucher_pengeluaran', [
                'data' => json_encode($row),
            ], true);

            $row['index'] = $this->input->post('start') + $index;

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('v.id_vendor')
        ->select('v.vendor_name')
        ->select('v.alias')
        ->select('v.alamat')
        ->select('v.no_rekening as no_rekening_tujuan')
        ->select('v.nama_rekening as bank')
        ->select('v.atas_nama_bank as atas_nama')
        ->select('vt.vendor_type')
        ->from('ms_vendor as v')
        ->join('ms_vendor_type as vt', 'vt.id_vendor_type = v.id_vendor_type')
        ->where('v.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('v.vendor_name', $search);
            $this->db->or_like('v.alias', $search);
            $this->db->or_like('v.alamat', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('v.vendor_name', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data() {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
