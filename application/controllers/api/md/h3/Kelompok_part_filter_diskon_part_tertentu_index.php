<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_part_filter_diskon_part_tertentu_index extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_kelompok_part_filter_diskon_part_tertentu_index', [
                'data' => json_encode($row),
                'id_kelompok_part' => $row->id_kelompok_part
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
        ->select('kp.id_kelompok_part')
        ->from('ms_kelompok_part as kp')
        // ->where('kp.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();
        
        if($this->config->item('ahm_only')){
            $this->db->where('kp.kelompok_part !=','FED OIL');
        }

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kp.id_kelompok_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.id_kelompok_part', 'ASC');
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
