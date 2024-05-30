<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parts_penerimaan_manual extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['id_lokasi_rak_suggest'] = null;
            $row['lokasi_suggest'] = null;
            $row['id_lokasi_rak'] = null;
            $row['lokasi'] = null;
            $row['action'] = $this->load->view('additional/md/h3/action_parts_penerimaan_manual', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
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
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.kelompok_part')
        ->select('
        concat(
            "Rp ",
            format(p.harga_md_dealer, 0, "ID_id")
        )
        as harga_formatted')
        ->select('p.harga_md_dealer as harga')
        ->select('1 as qty_terima')
        ->select('1 as tanpa_po_vendor')
        ->from('ms_part as p')
        // ->where('p.kelompok_vendor !=', 'AHM')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
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
