<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tipe_kendaraan extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_tipe_kendaraan', [
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
        ->select('tk.*')
        ->select('concat(tk.cc_motor, " cc") as cc_motor')
        ->from('ms_tipe_kendaraan as tk')
        ;

        if($this->input->post('filter_tahun_kendaraan') != null){
            $this->db->where("left(tk.tgl_awal, 4) = {$this->input->post('filter_tahun_kendaraan')}");
        }

        if($this->input->post('id_kategori') != null){
            $this->db->where('tk.id_series', $this->input->post('id_kategori'));
        }
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('tk.id_tipe_kendaraan', $search);
            $this->db->or_like('tk.tipe_ahm', $search);
            $this->db->or_like('tk.deskripsi_ahm', $search);
            $this->db->or_like('tk.tipe_customer', $search);
            $this->db->or_like('tk.tipe_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tk.id_tipe_kendaraan', 'asc');
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
