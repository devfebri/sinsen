<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dealer_terdekat extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        $output = array(
            'draw' => intval($this->input->post('draw')), 
            'recordsFiltered' => $this->recordsFiltered(), 
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        );
        echo json_encode($output);
    }

    public function make_query() {
        $this->db
        ->select('dtd.id')
        ->select('dt.nama_dealer')
        ->select('dt.kode_dealer_md')
        ->from('ms_h3_dealer_terdekat as dtd')
        ->join('ms_dealer as dt', 'dt.id_dealer = dtd.id_dealer_terdekat')
        ->where('dtd.id_dealer', $this->m_admin->cari_dealer())
        ;
    }

    public function make_datatables(){
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('dt.nama_dealer', $search);
            $this->db->or_like('dt.kode_dealer_md', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dt.nama_dealer', 'asc');
        }
    }

    public function limit() {
        if ($this->input->post('length') != - 1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}