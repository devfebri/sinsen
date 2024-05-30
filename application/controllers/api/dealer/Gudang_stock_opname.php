<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gudang_stock_opname extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_gudang_stock_opname_datatable', [
                'data' => json_encode($each)
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
        ->from('ms_gudang_h23 as g')
        ->where('g.id_dealer', $this->m_admin->cari_dealer())
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('g.id_gudang', $search);
            $this->db->or_like('g.deskripsi_gudang', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('g.id_gudang', 'ASC');
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
