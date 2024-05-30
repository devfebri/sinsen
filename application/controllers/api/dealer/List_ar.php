<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class List_ar extends CI_Controller {

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
            $data[] = $sub_array;
        }
        return $data;
    }

    public function query() {
        $pembayaran = $this->db
        ->select('sum(rcm.nominal)')
        ->from('tr_h2_receipt_customer as rc')
        ->join('tr_h2_receipt_customer_metode as rcm', 'rcm.id_receipt = rc.id_receipt')
        ->where('rc.id_referensi = so.nomor_so')
        ->where('rc.referensi = "part_sales"')
        ->where('rc.id_dealer', $this->m_admin->cari_dealer())
        ->get_compiled_select();

        $this->db
        ->select('date_format(nsc.tgl_nsc, "%d-%m-%Y") as tgl_nsc')
        ->select('date_format(nsc.tgl_nsc, "%d-%m-%Y") as tgl_jatuh_tempo')
        ->select('nsc.no_nsc')
        ->select('so.nama_pembeli')
        ->select('concat("Rp ", format( (so.total_tanpa_ppn /1.1), 0, "ID_id")) as dpp')
        ->select('concat("Rp ", format( ( (so.total_tanpa_ppn /1.1) * (10/100) ), 0, "ID_id" )) as ppn')
        ->select('concat("Rp ", format( so.total_tanpa_ppn, 0, "ID_id" )) as total_piutang')
        ->select("concat('Rp ', format( ({$pembayaran}), 0, 'ID_id') ) as pembayaran")
        ->select("concat('Rp ', format( so.total_tanpa_ppn - ({$pembayaran}), 0, 'ID_id') ) as sisa_piutang")
        ->from('tr_h23_nsc as nsc')
        ->join('tr_h3_dealer_sales_order as so', 'nsc.nomor_so = so.nomor_so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->group_by('sop.nomor_so')
        ->where('nsc.referensi = "sales"')
        ;

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('nsc.no_nsc', $search);
            $this->db->group_end();
        }

        $this->db->order_by('nsc.tgl_nsc', 'ASC');
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