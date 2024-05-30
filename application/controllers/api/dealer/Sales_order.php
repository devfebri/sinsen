<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $output = array(
            "draw" => intval($this->input->post('draw')), 
            "recordsFiltered" => $this->get_filtered_data(), 
            'recordsTotal' => $this->all_records(),
            "data" => $this->process_data()
        );
        echo json_encode($output);
    }

    public function process_data(){
        $fetch_data = $this->get_result();
        $data = array();
        $akumulasi = 0;
        $index = 1;
        foreach ($fetch_data as $each) {
            $sub_array = (array) $each;
            $sub_array['action'] = $this->load->view('additional/action_sales_order', [
                'id' => $each->nomor_so,
            ], true);

            $data[] = $sub_array;
            $index++;
        }

        return $data;
    }

    public function query() {
        

        $this->db
        ->select('so.*')
        ->select('date_format(so.tanggal_so, "%d-%m-%Y") as tanggal_so')
        ->select("
            case
                when so.booking_id_reference is null or so.booking_id_reference = '' then '-'
                else so.booking_id_reference
            end as booking_reference
        ")
        ->from('tr_h3_dealer_sales_order as so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->order_by('so.created_at', 'desc');

        if($this->input->post('filter_status_sales_order') != null){
            $this->db->where('so.status', $this->input->post('filter_status_sales_order'));
        }

        if($this->input->post('filter_sales_date') != null){
            $this->db->group_start();
            $this->db->where("so.tanggal_so >= '{$this->input->post('start_date')}'");
            $this->db->where("so.tanggal_so <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('so.nomor_so', $search);
            $this->db->or_like('so.nama_pembeli', $search);
            $this->db->or_like('so.no_hp_pembeli', $search);
            $this->db->or_like('so.booking_id_reference', $search);
            $this->db->or_like('date_format(so.tanggal_so, "%d-%m-%Y")', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.created_at', 'ASC');
        }
    }

    public function get_result() {
        $this->query();
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function all_records(){
        $this->query();
        return $this->db->count_all_results();
    }
}