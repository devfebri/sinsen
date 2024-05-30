<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Debt_collector extends CI_Controller

{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_debt_collector', [
                'id' => $each->id
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
		->select('dc.*')
		->select('k.npk')
		->select('k.nama_lengkap')
		->select('k.no_telp')
		->select('k.alamat')
		->select('date_format(k.tgl_masuk, "%d-%m-%Y") as tgl_masuk')
		->from('ms_h3_md_debt_collector as dc')
		->join('ms_karyawan as k', 'k.id_karyawan = dc.id_karyawan')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('k.npk', $search);
            $this->db->or_like('k.nama_lengkap', $search);
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('k.npk', 'asc');
        }

        if ($this->input->post('length') != - 1) {
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
