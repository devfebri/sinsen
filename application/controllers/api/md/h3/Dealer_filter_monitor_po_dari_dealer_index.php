<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealer_filter_monitor_po_dari_dealer_index extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_dealer_filter_do_sales_order_index', [
                'data' => json_encode($row),
                'id_dealer' => $row['id_dealer']
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
        ->select('d.id_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->from('ms_dealer as d');
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
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.kode_dealer_md', 'asc');
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
        return $query = $this->db->count_all_results();
    }
}
