<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Picking_list_filter_monitoring_picking extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();
        $rows = $this->db->get()->result_array();
        $data = array();
        foreach ($rows as $row) {
            $sub_array = $row;
            $sub_array['action'] = $this->load->view('additional/md/h3/action_picking_list_filter_monitoring_picking', [
                'data' => json_encode($row),
                'id_picking_list' => $row['id_picking_list']
            ], true);
            $data[] = $sub_array;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $this->db
        ->select('pl.id_picking_list')
        ->select('do.id_do_sales_order')
        ->select('so.id_sales_order')
        ->select('d.nama_dealer')
        ->from('tr_h3_md_picking_list as pl')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->where('pl.id_picker', null)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pl.id_picking_list', $search);
            $this->db->or_like('do.id_do_sales_order', $search);
            $this->db->or_like('so.id_sales_order', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pl.created_at', 'desc');
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

    public function get_record_total(){
        return $this->db->from('tr_h3_md_picking_list')->count_all_results();
    }
}
