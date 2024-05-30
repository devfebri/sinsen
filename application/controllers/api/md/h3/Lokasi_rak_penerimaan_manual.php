<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_rak_penerimaan_manual extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_rak_penerimaan_manual', [
                'data' => json_encode($row),
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
        ->select('lr.id')
        ->select('lr.kode_lokasi_rak')
        ->select('lr.deskripsi')
        ->from('ms_h3_md_lokasi_rak as lr')
        ->where('lr.id_gudang', $this->input->post('id_gudang'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lr.kode_lokasi_rak', $search);
            $this->db->or_like('lr.deskripsi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('lr.kode_lokasi_rak', 'asc');
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
