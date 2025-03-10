<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wilayah_penagihan_tanda_terima_faktur extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();
        
        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_wilayah_penagihan_tanda_terima_faktur', [
                'data' => json_encode($row),
            ], true);

            $data[] = $row;
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
        ->select('wp.id')
        ->select('wp.kode_wilayah')
        ->select('wp.nama')
        ->from('ms_h3_md_wilayah_penagihan as wp')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('wp.kode_wilayah', $search);
            $this->db->or_like('wp.nama', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('wp.kode_wilayah', 'asc');
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
        return $this->db->count_all_results();
    }
}
