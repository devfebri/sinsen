<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_promo extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_parts_promo_datatable', [
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
        ->select('mp.id_part')
        ->select('mp.nama_part')
        ->from('ms_part as mp')
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mp.id_part', $search);
            $this->db->or_like('mp.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mp.id_part', 'ASC');
        }



        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
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
