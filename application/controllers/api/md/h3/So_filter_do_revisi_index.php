<?php
defined('BASEPATH') or exit('No direct script access allowed');

class So_filter_do_revisi_index extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_so_filter_do_revisi_index', [
                'data' => json_encode($row),
                'id_sales_order' => $row['id_sales_order']
            ], true);
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_record_total(),
            'data' => $data
        ]);
    }
    
    public function make_query() {
        $so_yang_direvisi = $this->db
        ->select('DISTINCT(do.id_sales_order) as id_sales_order')
        ->from('tr_h3_md_do_revisi as dr')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
        ->get_compiled_select();

        $this->db
        ->select('so.id_sales_order')
        ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_order')
        ->from('tr_h3_md_sales_order as so')
        ->where('so.status != ', 'Canceled')
        ->where('so.status != ', 'Closed')
        ->where("so.id_sales_order IN ({$so_yang_direvisi})", null, false)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.tanggal_order', 'desc');
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
