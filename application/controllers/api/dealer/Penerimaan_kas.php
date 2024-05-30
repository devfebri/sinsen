<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_kas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $output = array(
            "draw" => intval($this->input->post('draw')), 
            "recordsFiltered" => $this->get_filtered_data(), 
            'recordsTotal' => $this->all_records(),
            "data" => $this->process_data()
        );
        echo json_encode($output);
    }

    public function process_data(){
        $fetch_data = $this->get_result();
        $data = array();
        $akumulasi = 0;
        $index = 1;
        foreach ($fetch_data as $each) {
            $sub_array = (array) $each;
            $sub_array['action'] = $this->load->view('additional/action_index_penerimaan_kas', [
                'id' => $each->id_penerimaan_kas,
            ], true);
            $data[] = $sub_array;
        }
        return $data;
    }

    public function query() {
        $this->db
        ->select('pk.*')
        ->select('date_format(pk.created_at, "%d-%m-%Y %H:%i") as created_at')
        ->from('tr_h3_dealer_penerimaan_kas as pk');

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pk.id_penerimaan_kas', $search);
            $this->db->group_end();
        }

        
    }

    public function get_result() {
        $this->query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pk.id_penerimaan_kas', 'DESC');
        }

        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function all_records(){
        $this->query();
        return $this->db->count_all_results();
    }
}