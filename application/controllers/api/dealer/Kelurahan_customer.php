<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kelurahan_customer extends CI_Controller
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
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_kelurahan_customer', [
                'data' => json_encode($row)
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
        ->select('kelurahan.id_kelurahan')
        ->select('kelurahan.kelurahan')
        ->select('kecamatan.id_kecamatan')
        ->select('kecamatan.kecamatan')
        ->select('kabupaten.id_kabupaten')
        ->select('kabupaten.kabupaten')
        ->select('provinsi.id_provinsi')
        ->select('provinsi.provinsi')
        ->from('ms_kelurahan as kelurahan')
        ->join('ms_kecamatan as kecamatan', 'kelurahan.id_kecamatan = kecamatan.id_kecamatan')
        ->join('ms_kabupaten as kabupaten', 'kecamatan.id_kabupaten = kabupaten.id_kabupaten')
        ->join('ms_provinsi as provinsi', 'kabupaten.id_provinsi = provinsi.id_provinsi')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kelurahan.kelurahan', $search);
            $this->db->or_like('kecamatan.kecamatan', $search);
            $this->db->or_like('kabupaten.kabupaten', $search);
            $this->db->or_like('provinsi.provinsi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kelurahan.kelurahan', 'asc');
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
