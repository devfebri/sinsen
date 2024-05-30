<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_ptm_ms_part extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();
        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $this->db->get()->result_array(),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('ptm.tipe_marketing')
		->select('ptm.deskripsi')
		->from('ms_pvtm as pvtm')
		->join('ms_ptm as ptm', 'ptm.tipe_produksi = pvtm.tipe_marketing')
        ->where('pvtm.no_part', $this->input->post('id_part'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ptm.tipe_marketing', $search);
            $this->db->or_like('ptm.deskripsi', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ptm.tipe_marketing', 'asc');
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

    public function get_record_total(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
