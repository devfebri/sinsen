<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rak_parts_shipping_list extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_rak_parts_sales_order_datatable', [
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
        ->select('r.*')
        ->select('r.id as id_rak_int')
        ->select('g.deskripsi_gudang')
        ->select('g.kategori')
        ->select('g.id as id_gudang_int')
        ->from('ms_lokasi_rak_bin as r')
        ->join('ms_gudang_h23 as g', 'g.id_gudang = r.id_gudang and g.id_dealer = r.id_dealer')
        ->where('r.id_dealer', $this->m_admin->cari_dealer())
        ;

        if($this->input->post('tipe_rak') == 'good'){
            $this->db->where('g.tipe_gudang', 'Good');
        }else if($this->input->post('tipe_rak') == 'bad'){
            $this->db->where('g.tipe_gudang', 'Bad');
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->like('r.id_rak', $search);
            $this->db->or_like('r.deskripsi_rak', $search);
            $this->db->or_like('g.deskripsi_gudang', $search);
            $this->db->or_like('g.kategori', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('r.id_gudang', 'ASC');
            $this->db->order_by('r.id_rak', 'ASC');
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