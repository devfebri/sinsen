<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_part_filter_part_sales_campaign_detail_poin extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = (array) $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_kelompok_part_filter_sales_campaign_detail_poin', [
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
        ->join('ms_h3_md_setting_kelompok_produk as skp', 'skp.id_kelompok_part = kp.id_kelompok_part')
        ->where('skp.produk', $this->input->post('kategori'))
        ->where('kp.active', 1)
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
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.id_kelompok_part', 'asc');
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
