<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_setting_password extends CI_Controller
{
    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_setting_password', [
                'id' => $row['id']
            ], true);

            $row['index'] = $this->input->post('start') . $index . '.';
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
        ->select('mm.menu_name')
        ->select('(CASE WHEN mp.active =  1 THEN "Aktif" ELSE "Non Aktif" end) as active')
        ->select('mp.id')
        ->from('tr_h3_md_setting_menu_password mp')
        ->join('ms_menu mm', 'mm.id_menu=mp.id_menu');
    }

    public function make_datatables()
    {
        $this->make_query();


        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mm.menu_name', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mm.menu_name', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
        return $this->db->count_all_results();
    }
}
