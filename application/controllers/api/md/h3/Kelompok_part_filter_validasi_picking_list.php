<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_part_filter_validasi_picking_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        $data = array();

        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_kelompok_part_filter_validasi_picking_list', [
                'data' => json_encode($row),
                'id_kelompok_part' => $row['id_kelompok_part']
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
        $id_part = $this->db
        ->select('plp.id_part')
        ->from('tr_h3_md_picking_list_parts as plp')
        ->where('plp.id_picking_list', $this->input->post('id_picking_list'))
        ->group_by('plp.id_part')
        ->get_compiled_select()
        ;

        $this->db
        ->select('kp.id_kelompok_part')
        ->from('ms_part as p')
        ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
        ->where("p.id_part in ({$id_part})")
        ->group_by('kp.id_kelompok_part')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kp.id_kelompok_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.id_kelompok_part', 'asc');
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
        return $this->db->count_all_results();
    }
}
