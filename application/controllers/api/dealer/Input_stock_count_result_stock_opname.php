<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Input_stock_count_result_stock_opname extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_index_input_stock_count_result', [
                'id' => $each->id_stock_opname
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
        $id_karyawan = $this->session->userdata('id_karyawan_dealer');

        // $this->db
        // ->select('so.*')
        // ->select('date_format(so.created_at, "%d-%m-%Y") as tanggal')
        // ->from('tr_h3_dealer_member_stock_opname as mso')
        // ->join('tr_h3_dealer_stock_opname as so', 'so.id_stock_opname = mso.id_stock_opname')
        // ->where('mso.id_member', $id_karyawan)
        // ;

        $this->db
        ->select('so.*')
        ->select('date_format(so.created_at, "%d-%m-%Y") as tanggal')
        ->from('tr_h3_dealer_stock_opname as so')
        ->join('tr_h3_dealer_stock_opname_parts as sop', 'so.id_stock_opname = sop.id_stock_opname')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->group_by('so.id_stock_opname')
        ;
        
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mso.id_stock_opname', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.created_at', 'ASC');
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
