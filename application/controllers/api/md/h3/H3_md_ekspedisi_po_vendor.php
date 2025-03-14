<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ekspedisi_po_vendor extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_ekspedisi_penerimaan_manual', [
                'data' => json_encode($row)
            ], true);

            $data[] = $row;
        }
       
        send_json(
            array(
                'draw' => intval($this->input->post('draw')), 
                'recordsFiltered' => $this->get_filtered_data(), 
                'recordsTotal' => $this->get_total_data(), 
                'data' => $data
            )
        );
    }
    
    public function make_query() {
        $this->db
        ->select('e.id')
        ->select('e.nama_ekspedisi')
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
            $this->db->or_like('e.no_telp', $search);
            $this->db->or_like('e.alamat', $search);
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

    public function get_total_data() {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
