<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_part_setting_kelompok_produk extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_kelompok_part_kelompok_produk', [
                'data' => json_encode($row),
                'kelompok_part' => $row['id_kelompok_part'],
                'selected' => $row['selected'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
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
        ->select('kp.id_kelompok_part')
        ->select('
            case 
                when skp.id is null then 0
                else 1
            end as selected
        ', false)
        ->from('ms_kelompok_part as kp')
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = kp.id_kelompok_part', 'left')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        if($this->config->item('ahm_only')){
            $this->db->where('kp.kelompok_part !=','FED OIL');
        }

        $search = trim($this->input->post('search')['value']);
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
        return $this->db->get()->num_rows();
    }
}
