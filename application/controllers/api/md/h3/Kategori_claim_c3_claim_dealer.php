<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_claim_c3_claim_dealer extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_kategori_claim_c3_claim_dealer', [
                'data' => json_encode($row)
            ], true);
            $data[] = $row;
        }

        send_json([
            'draw' => intval($_POST['draw']), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_total_data(), 
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('kc.id')
        ->select('kc.kode_claim')
        ->select('kc.nama_claim')
        ->from('ms_kategori_claim_c3 as kc')
        ->order_by('kc.kode_claim', 'asc')
        ->where('kc.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('kc.kode_claim', $search);
            $this->db->or_like('kc.nama_claim', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kc.kode_claim', 'asc');
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
