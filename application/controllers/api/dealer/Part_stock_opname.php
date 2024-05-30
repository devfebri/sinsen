<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Part_stock_opname extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/action_part_stock_opname_datatable', [
                'data' => json_encode($each),
                'id_part' => $sub_arr['id_part'],
            ], true);
            $data[] = $sub_arr;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('ds.*')
        ->select('p.nama_part')
        ->from('ms_h3_dealer_stock as ds')
        ->join('ms_part as p', 'p.id_part = ds.id_part')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        ->where('ds.id_gudang', $this->input->post('id_gudang'))
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ds.id_part', $search);
            $this->db->or_like('ds.id_rak', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ds.id_rak', 'ASC');
        }



        if ($_POST["length"] != - 1) {

            $this

                ->db

                ->limit($_POST['length'], $_POST['start']);

        }

        return $this->db->get()->result();

    }



    public function recordsFiltered()

    {

        $this->make_query();

        return $this->db->get()->num_rows();

    }



    public function recordsTotal(){

        $this->make_query();

        return $this->db->count_all_results();

    }

}
