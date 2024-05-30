<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_check_part_stock extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('h3_dealer_stock_model', 'stock');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['stock'] = $this->stock->qty_on_hand($this->m_admin->cari_dealer(), $row['id_part'], null, null, false);
            $row['action'] = $this->load->view('additional/action_part_check_part_stock', [
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
        $id_part_berdasarkan_filter_kendaraan = $this->db
        ->select('pvtm.no_part')
        ->from('ms_tipe_kendaraan as tk')
        ->join('ms_ptm as ptm', 'ptm.tipe_marketing = tk.id_tipe_kendaraan')
        ->join('ms_pvtm as pvtm', 'pvtm.tipe_marketing = ptm.tipe_produksi')
        ->where('tk.id_tipe_kendaraan', $this->input->post('id_tipe_kendaraan'))
        ->get_compiled_select();

        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.nama_part_bahasa')
        ->select('p.harga_dealer_user')
        ->select('p.kelompok_vendor')
        ->select('p.kelompok_part')
        ->select('p.status')
        ->select('1 as kuantitas')
        ->from('ms_part as p');

        if($this->input->post('id_tipe_kendaraan') != null){
            $this->db->where("p.id_part IN ({$id_part_berdasarkan_filter_kendaraan})", null, false);
        }
            
        if($this->config->item('ahm_d_only')){
            $this->db->where("p.kelompok_part !='FED OIL'");
        }
        
        // $tanggal = date("Y-m-d");
        // if($tanggal >='2023-08-06' && $tanggal <='2023-08-12'){
        //     $this->db->where('p.kelompok_part !=','FED OIL');
        // }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->or_like('p.nama_part_bahasa', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
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
