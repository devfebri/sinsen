<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Picker_filter_monitoring_kerja_picker extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables(); $this->limit();
        $data = [];
        foreach ($this->db->get()->result_array() as $record) {
            $record['action'] = $this->load->view('additional/md/h3/action_picker_filter_monitoring_kerja_picker_index', [
                'data' => json_encode($record),
                'id_karyawan' => $record['id_karyawan']
            ], true);
            $data[] = $record;
        }

        $output = [
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ];
        send_json($output);
    }
    
    public function make_query() {
        $this->db
        ->select('k.id_karyawan')
        ->select('k.nama_lengkap')
        ->from('ms_karyawan as k')
        ->where('k.id_jabatan', 'JBT-054');
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('k.nama_lengkap', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.nama_lengkap', 'asc');
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
        return $this->db->count_all_results();
    }
}
