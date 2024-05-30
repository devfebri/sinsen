<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $output = array(
            "draw" => intval($this->input->post('draw')), 
            "recordsFiltered" => $this->recordsFiltered(), 
            'recordsTotal' => $this->recordsTotal(),
            "data" => $this->process_data()
        );
        echo json_encode($output);
    }

    public function process_data(){
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_promo', [
                'id' => $row['id_promo'],
            ], true);

            $gimmick = $this->db
            ->from('ms_h3_promo_dealer_hadiah as h')
            ->where('h.id_promo', $row['id_promo'])
            ->get()->result();

            $row['gimmick'] = count($gimmick) > 0 ? 1 : 0;

            $diskon = $this->db
            ->from('ms_h3_promo_dealer_items as d')
            ->where('d.id_promo', $row['id_promo'])
            ->where('d.tipe_disc !=', '')
            ->get()->result();

            $row['diskon'] = ($row['diskon_value_master'] != '' OR count($diskon)) > 0 ? 1 : 0;

            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        return $data;
    }

    public function make_query() {
        $this->db
        ->select('pd.*')
        ->select('date_format(pd.start_date, "%d-%m-%Y") as start_date')
        ->select('date_format(pd.end_date, "%d-%m-%Y") as end_date')
        ->from('ms_h3_promo_dealer as pd')
        ;
    }

    public function make_datatables(){
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pd.id_promo', $search);
            $this->db->or_like('pd.tipe_promo', $search);
            $this->db->or_like('pd.nama', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pd.id_promo', 'DESC');
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