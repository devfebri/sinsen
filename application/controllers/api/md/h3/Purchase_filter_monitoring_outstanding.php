<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_filter_monitoring_outstanding extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_purchase_filter_monitoring_outstanding_index', [
                'data' => json_encode($row),
                'id_purchase_order' => $row['id_purchase_order']
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
       ->select('po.id_purchase_order')
       ->select('date_format(po.tanggal_po, "%d/%m/%Y") as tanggal_po')
       ->from('tr_h3_md_purchase_order as po')
       ->where('po.status','Approved')
       ->where('po.status !=','Closed')
       ->where('po.created_at >=','2023-01-01 00:00:00');
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('po.id_purchase_order', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.tanggal_po', 'desc');
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
