<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wilayah_penagihan_debt_collector_filter extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();
        $rows = $this->db->get()->result_array();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_wilayah_penagihan_debt_collector_filter', [
                'id' => $row['id']
            ], true);
            $data[] = $sub_array;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
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

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('wp.kode_wilayah', $search);
            $this->db->or_like('wp.nama', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('wp.kode_wilayah', 'ASC');
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
