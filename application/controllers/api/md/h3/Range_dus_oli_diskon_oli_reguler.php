<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Range_dus_oli_diskon_oli_reguler extends CI_Controller {

    public $table = "ms_h3_md_range_dus_oli";

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $fetch_data = $this->db->get()->result();
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_range_dus_oli_diskon_oli_reguler', [
                'data' => json_encode($row),
                'id' => $row->id
            ], true);
            $data[] = $sub_array;
        }
        $output = [
            'draw' => intval($_POST["draw"]),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ];
        send_json($output);
    }
    
    public function make_query() {
        $this->db
        ->select('rdo.*')
        ->select('rdo.id as id_range_dus_oli')
        ->select('"" as tipe_diskon')
        ->select('0 as diskon_value')
        ->from('ms_h3_md_range_dus_oli as rdo')
        ->where('rdo.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rdo.kode_range', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('rdo.kode_range', 'asc');
        }

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
        return $this->db->get()->num_rows();
    }
}
