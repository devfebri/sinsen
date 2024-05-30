<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tipe_kendaraan_check_part_stock extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_tipe_kendaraan', [
                'data' => json_encode($row)
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
        $id_tipe_kendaraan_filter_id_part = $this->db
        ->select('ptm.tipe_marketing')
        ->from('ms_pvtm as pvtm')
        ->join('ms_ptm as ptm', 'ptm.tipe_produksi = pvtm.tipe_marketing')
        ->where('pvtm.no_part', $this->input->post('id_part'))
        ->get_compiled_select();
        
        $this->db
        ->select('tk.id_tipe_kendaraan')
        ->select('tk.tipe_ahm')
        ->select('tk.deskripsi_ahm')
        ->select('tk.tipe_customer')
        ->select('tk.tipe_part')
        ->select('tk.cc_motor')
        ->from('ms_tipe_kendaraan as tk')
        ;

        if($this->input->post('filter_tahun_kendaraan') != null){
            $this->db->where("left(tk.tgl_awal, 4) = {$this->input->post('filter_tahun_kendaraan')}");
        }

        if($this->input->post('id_kategori') != null){
            $this->db->where('tk.id_kategori', $this->input->post('id_kategori'));
        }

        if ($this->input->post('id_part') != null) {
            $this->db->where("tk.id_tipe_kendaraan IN ({$id_tipe_kendaraan_filter_id_part})", null, false);
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
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('tk.id_tipe_kendaraan', 'asc');
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
