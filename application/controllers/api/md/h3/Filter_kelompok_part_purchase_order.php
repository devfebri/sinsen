<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Filter_kelompok_part_purchase_order extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_order_parts');
        $this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
        $this->load->model('H3_md_niguri_header_model', 'niguri_header');

        $this->load->library('Mcarbon');
    }

    public function index() 
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_filter_kelompok_part_purchase_order', [
                'data' => json_encode($row),
                'id_kelompok_part' => $row['id_kelompok_part']
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
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
        ->select('kp.id_kelompok_part')
        ->from('ms_kelompok_part as kp')
        ;

        if (count($this->input->post('available_kelompok_part'))) {
            $this->db->where_in('kp.id_kelompok_part', $this->input->post('available_kelompok_part'));
        }else{
            $this->db->where('1 = 0', null, false);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kp.id_kelompok_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.id_kelompok_part', 'ASC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}