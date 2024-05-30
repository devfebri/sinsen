<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Part_oli extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $this->proses_data(),
        ]);
    }

    public function proses_data(){
        $record = $this->make_datatables();
        $data = [];
        foreach ($record as $each) {
            $sub_array = (array) $each;
            $sub_array['aksi'] = $this->load->view('additional/action_part_oli', [
                'data' => json_encode($each)
            ], true);
            $data[] = $sub_array;
        }
        return $data;
    }
    
    public function make_query() {
        $this->db
        ->from('ms_part as mp')
        ->where('mp.part_oli !=', null);
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mp.id_part', $search);
            $this->db->or_like('mp.nama_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mp.nama_part', 'ASC');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function recordsFiltered() {
        $this->make_query();

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function recordsTotal() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
