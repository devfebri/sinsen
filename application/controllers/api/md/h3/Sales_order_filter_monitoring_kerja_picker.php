<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order_filter_monitoring_kerja_picker extends CI_Controller {

    public function index() {
        $this->make_datatables(); $this->limit();
        $data = [];
        foreach ($this->db->get()->result_array() as $record) {
            $record['action'] = $this->load->view('additional/md/h3/action_so_filter_monitoring_kerja_picker_index', [
                'data' => json_encode($record),
                'id_sales_order' => $record['id_sales_order']
            ], true);
            $data[] = $record;
        }

        $output = [
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ];
        send_json($output);
    }
    
    public function make_query() {
        $this->db
        ->select('so.id_sales_order')
        ->select('d.kode_dealer_md')
        ->select('d.nama_dealer')
        ->from('tr_h3_md_picking_list as pl')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('so.id_sales_order', $search);
            $this->db->or_like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.created_at', 'asc');
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
        $this->make_query();
        return $this->db->count_all_results();
    }
}
