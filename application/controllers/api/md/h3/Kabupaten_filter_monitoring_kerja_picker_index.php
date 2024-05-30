<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kabupaten_filter_monitoring_kerja_picker_index extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        $rows = $this->db->get()->result_array();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_kabupaten_filter_monitoring_kerja_picker_index', [
                'data' => json_encode($row),
                'id_kabupaten' => $row['id_kabupaten']
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
        ->select('kab.id_kabupaten')
        ->select('kab.kabupaten')
        ->from('ms_kabupaten as kab')
        ->where('kab.id_provinsi', '1500')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        // $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('kab.kabupaten', $search);
        //     $this->db->group_end();
        // }
        $cari_kabupaten = $this->input->post('cari_kabupaten');
        if($cari_kabupaten != ''){
            $this->db->like('kab.kabupaten', $cari_kabupaten);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kab.kabupaten', 'desc');
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
        return $this->db->from('ms_kabupaten')->count_all_results();
    }
}
