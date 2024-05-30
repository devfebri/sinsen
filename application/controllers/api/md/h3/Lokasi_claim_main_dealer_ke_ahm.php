<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_claim_main_dealer_ke_ahm extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('H3_md_stock_int_model', 'stock_int');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_lokasi_rak_claim_main_dealer_ke_ahm', [
                'data' => json_encode($row),
            ], true);

            $data[] = $row;
        }
        $output = array(
            'draw' => intval($_POST['draw']), 
            'recordsFiltered' => $this->get_filtered_data(), 
            'recordsTotal' => $this->get_total_data(), 
            'data' => $data
        );
        send_json($output);
    }
    
    public function make_query() {
        $qty_onhand = $this->stock->qty_on_hand('sp.id_part_int', 'sp.id_lokasi_rak', true);

        $this->db
        ->select('lr.id')
        ->select('lr.kode_lokasi_rak')
        ->select('lr.deskripsi')
        ->select('sp.qty as  qty_onhand')
        ->from('tr_stok_part as sp')
        ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = sp.id_lokasi_rak')
        ->where('sp.id_part_int', $this->input->post('id_part_int'))
        ->where('sp.qty >', 0);
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if (!empty($search)) {
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
        return $this->db->get()->num_rows();
    }
}
