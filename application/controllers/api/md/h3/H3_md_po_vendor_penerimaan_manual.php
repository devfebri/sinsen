<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_po_vendor_penerimaan_manual extends CI_Controller {

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_po_vendor_penerimaan_manual', [
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
        ->select('pv.id_po_vendor')
        ->select('date_format(pv.tanggal, "%d/%m/%Y") as tanggal')
        ->select('v.vendor_name')
        ->from('tr_h3_md_po_vendor as pv')
        ->join('ms_vendor as v', 'v.id_vendor = pv.id_vendor')
        ->where('pv.id_vendor', $this->input->post('id_vendor'))
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pv.id_po_vendor', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pv.created_at', 'asc');
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
