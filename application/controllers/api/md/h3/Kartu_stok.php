<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kartu_stok extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_kartu_stok_datatable', [
                'id' => $row['id_stok_part']
            ], true);
            $data[] = $row;
        }
        $output = array(
            'draw' => intval($_POST["draw"]), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->count_all(),
            'data' => $data,
        );
        send_json($output);
    }
    
    public function make_query() {
        $this->db
        ->select('sp.id_stok_part')
        ->select('sp.id_part')
        ->select('p.nama_part')
        ->select('g.kode_gudang')
        ->select('lr.kode_lokasi_rak')
        ->from('tr_stok_part as sp')
        ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak')
        ->join('ms_part as p', 'p.id_part_int = sp.id_part_int')
        ->join('ms_h3_md_gudang as g', 'g.id = lr.id_gudang')
        ->where('lr.active', 1)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('sp.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sp.id_part', 'ASC');
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

    public function count_all(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
