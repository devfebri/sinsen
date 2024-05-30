<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_filter_create_do_sales_order extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_parts_filter_create_do_sales_order', [
                'data' => json_encode($each),
                'selected' => $each->selected
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
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select("p.id_part = '{$this->input->post('filter_part_number')}' as selected")
        ->from('tr_h3_md_sales_order_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->where('sop.id_sales_order', $this->input->post('id_sales_order'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
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