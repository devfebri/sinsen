<?php
defined('BASEPATH') or exit('No direct script access allowed');

class View_sales_order_monitoring_permintaan_part extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $this->db->get()->result_array(),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('so.id_sales_order')
        ->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_order')
        ->from('tr_h3_md_sales_order as so')
        ->where('so.id_ref', $this->input->post('po_id_monitoring_permintaan_part'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('so.id_sales_order', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.id_sales_order', 'asc');
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

    public function get_total_data(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
