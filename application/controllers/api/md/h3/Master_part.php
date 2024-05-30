<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_part extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_master_part', [
                'id' => $row['id_part']
            ], true);
            $data[] = $row;
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
        ->select('p.nama_part_bahasa')
        ->select('p.kelompok_vendor')
        ->select('ifnull(p.min_stok, "-") as min_stok')
        ->select('ifnull(p.maks_stok, "-") as maks_stok')
        ->select('ifnull(p.safety_stok, "-") as safety_stok')
        ->select('p.kelompok_part')
        ->select('p.status')
        ->select('p.active')
        ->select('ifnull(s.kode_satuan, "-") as kode_satuan')
        ->from('ms_part as p')
        ->join('ms_kelompok_part as k', 'k.id_kelompok_part = p.kelompok_part', 'left')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->config->item('ahm_only')){
            $this->db->where('p.kelompok_part !=','FED OIL');
        }

        if($this->input->post('id_part_filter') != null){
            $this->db->like('p.id_part', trim($this->input->post('id_part_filter')));
        }

        if ($this->input->post('nama_part_filter') != null) {
            $this->db->like('p.nama_part', trim($this->input->post('nama_part_filter')));
        }

        if ($this->input->post('nama_part_bahasa_filter') != null) {
            $this->db->like('p.nama_part_bahasa', trim($this->input->post('nama_part_bahasa_filter')));
        }

        if (count($this->input->post('id_kelompok_part_filter')) > 0) {
            $this->db->where_in('p.kelompok_part', $this->input->post('id_kelompok_part_filter'));
        }

        if ($this->input->post('status_filter') != null) {
            $this->db->where('p.status', trim($this->input->post('status_filter')));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'ASC');
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
        return $this->db->count_all_results();
    }
}
