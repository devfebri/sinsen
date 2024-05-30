<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_request_document extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['eta_terlama'] = $row['eta_tercepat'] = $row['eta_revisi'] = null;
            $row['action'] = $this->load->view('additional/action_parts_request_document_datatable', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'hoo_max' => $row['hoo_max'],
                'import_lokal' => $row['import_lokal'],
                'current' => $row['current']
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
        ->select('mp.id_part')
        ->select('mp.nama_part')
        ->select('mp.nama_part_bahasa')
        ->select('mp.kelompok_part')
        ->select('mp.status')
        ->select('mp.harga_dealer_user as harga_saat_dibeli')
        ->select('1 as kuantitas')
        ->select('mp.import_lokal')
        ->select('mp.current')
        ->select('mp.hoo_flag')
        ->select('mp.hoo_max')
        ->from('ms_part as mp')
        ->where('mp.kelompok_vendor', 'AHM')
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
            $this->db->or_like('mp.nama_part_bahasa', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mp.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
