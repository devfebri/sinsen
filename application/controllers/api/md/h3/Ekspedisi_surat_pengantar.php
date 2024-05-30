<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ekspedisi_surat_pengantar extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_ekspedisi_surat_pengantar', [
                'data' => json_encode($row),
            ], true);
            $data[] = $row;
        }
        $output = array(
            'draw' => intval($this->input->post('draw')), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        );
        send_json($output);
    }
    
    public function make_query() {
        $this->db
        ->select('e.id')
        ->select('e.nama_ekspedisi')
        ->select('e.nama_pemilik')
        ->select('e.no_telp')
        ->select('e.alamat')
        ->from('ms_h3_md_ekspedisi as e');
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('e.nama_ekspedisi', $search);
            $this->db->or_like('e.nama_pemilik', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
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

    public function get_record_total() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
