<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ekspedisi_filter_report_penerimaan_ekspedisi extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_ekspedisi_filter_report_penerimaan_ekspedisi', [
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
        ->select('e.id')
        ->select('e.nama_ekspedisi')
        ->select('e.alamat')
        ->from('ms_h3_md_ekspedisi as e')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('e.nama_ekspedisi', $search);
            $this->db->or_like('e.alamat', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('e.nama_ekspedisi', 'asc');
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
