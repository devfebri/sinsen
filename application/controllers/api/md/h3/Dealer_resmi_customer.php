<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_resmi_customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_dealer_resmi_customer', [
                'data' => json_encode($row)
            ], true);
            $data[] = $sub_array;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->from('ms_dealer as d')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('d.nama_dealer', $search);
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'ASC');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        // $query = $this->db->get_compiled_select();
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
