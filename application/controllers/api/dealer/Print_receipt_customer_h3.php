<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Print_receipt_customer_h3 extends CI_Controller {

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
            $sub_array['action'] = $this->load->view('additional/action_index_print_receipt_customer_h3', [
                'id' => $each->no_nsc,
            ], true);

            $data[] = $sub_array;
        }
        return $data;
    }

    public function query() {
        $total_pembayaran = $this->db
        ->select("
            concat(
                'Rp ',
                format(
                    sum(
                        case 
                            when np.tipe_diskon = 'Percentage' then (np.qty * (np.harga_beli - ((np.diskon_value/100) * np.harga_beli)))
                            when np.tipe_diskon = 'FoC' then ((np.qty - np.diskon_value) * np.harga_beli)
                            when np.tipe_diskon = 'Value' then (np.qty * (np.harga_beli - np.diskon_value))
                            else (np.qty * np.harga_beli)
                        end
                    ),
                    0, 
                    'ID_id'
                ) 
            )
            as total_pembayaran
        ")
        ->from('tr_h23_nsc_parts as np')
        ->group_by('np.no_nsc')
        ->where('np.no_nsc = n.no_nsc')
        ->get_compiled_select()
        ;
        
        $this->db
        ->select('date_format(so.tanggal_so, "%d-%m-%Y") as tanggal_so')
        ->select('so.nomor_so')
        ->select('so.nama_pembeli')
        ->select('n.no_nsc')
        ->select("({$total_pembayaran}) as total_pembayaran")
        ->from('tr_h23_nsc as n')
        ->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = n.nomor_so')
        ->where('n.id_dealer', $this->m_admin->cari_dealer())
        ->where('n.referensi', 'sales');

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('so.nomor_so', $search);
            $this->db->or_like('n.no_nsc', $search);
            $this->db->group_end();
        }
    }

    public function get_result() {
        $this->query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.nomor_so', 'DESC');
        }

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