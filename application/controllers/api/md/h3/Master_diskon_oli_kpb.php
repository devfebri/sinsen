<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_diskon_oli_kpb extends CI_Controller

{
    public function index()
    {
        $records = $this->make_datatables();
        $this->limit();
        $records = $this->db->get()->result();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_index_h3_md_master_diskon_oli_kpb', [
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
		->select('dok.id')
		->select('dok.id_part')
		->select('p.nama_part')
        ->select('p.harga_dealer_user')
        ->select('dok.tipe_diskon')
        ->select('dok.diskon_value')
        ->select('dok.harga_kpb')
		->select('dok.tipe_produksi')
		->select('dok.id_tipe_kendaraan')
        ->select('tk.tipe_ahm as nama_tipe_kendaraan')
		->select('date_format(tk.tgl_awal, "%d-%m-%Y") as tahun_kendaraan')
		->from('ms_h3_md_diskon_oli_kpb as dok')
        ->join('ms_part as p', 'p.id_part = dok.id_part')
		->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = dok.id_tipe_kendaraan', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search') ['value']);
        // if ($search != '') {
        //     $this->db->like('dok.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->or_like('dok.id_tipe_kendaraan', $search);
        // }

        if($this->input->post('filter_id_tipe_kendaraan_2_digit') != ''){
            $this->db->like('dok.tipe_produksi',$this->input->post('filter_id_tipe_kendaraan_2_digit'));
        }
        if($this->input->post('filter_id_tipe_kendaraan_3_digit') != ''){
            $this->db->like('dok.id_tipe_kendaraan',$this->input->post('filter_id_tipe_kendaraan_3_digit'));
        }
        if($this->input->post('filter_id_part') != ''){
            $this->db->like('dok.id_part',$this->input->post('filter_id_part'));
        }
        if($this->input->post('filter_nama_part') != ''){
            $this->db->like('p.nama_part',$this->input->post('filter_nama_part'));
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dok.id_part', 'asc');
        }

        // if ($this->input->post('length') != - 1) {
        //     $this->db->limit($_POST['length'], $_POST['start']);
        // }
        // return $this->db->get()->result();
    }

    public function limit()
    {
        if ($this->input->post('length') != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_datatables();
        return $this->db->count_all_results();
    }
}
