<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_tujuan_mutasi_gudang extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_tujuan_mutasi_gudang', [
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
        $kapasitas_terpakai = $this->db
        ->select('sum(sp_sq.qty)')
        ->from('tr_stok_part as sp_sq')
        ->where('sp_sq.id_lokasi_rak = lr.id')
        ->get_compiled_select();

        $stock = $this->db
        ->select('sum(sp_sq.qty)')
        ->from('tr_stok_part as sp_sq')
        ->where('sp_sq.id_lokasi_rak = lr.id')
        ->where('sp_sq.id_part', $this->input->post('id_part'))
        ->get_compiled_select();

        $this->db
        ->select('lr.id')
        ->select('lr.kode_lokasi_rak')
        ->select('lr.deskripsi')
        ->select('lr.kapasitas')
        ->select("lr.kapasitas - ({$kapasitas_terpakai}) as kapasitas_tersedia", false)
        ->select("ifnull(({$stock}), 0) as stock", false)
        ->from('ms_h3_md_lokasi_rak as lr')
        ->where('lr.id_gudang', $this->input->post('id_gudang_tujuan'));
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lr.kode_lokasi_rak', $search);
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
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function get_filtered_data() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
