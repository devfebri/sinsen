<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_outbound_form extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
    }
    
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            // // $row['qty_asal'] = $this->dealer_stock_int->qty_on_hand( $row['id_dealer'], $row['id_part_int'], $row['id_gudang'], $row['id_rak']);
            $row['qty_booking'] = $this->dealer_stock->qty_book($row['id_dealer'], $row['id_part'], $row['id_gudang'], $row['id_rak']);
            $row['qty_avs'] = $row['qty_asal']-$row['qty_booking'];
            $row['action'] = $this->load->view('additional/action_parts_outbound_form', [
                'data' => json_encode($row),
                'qty_asal' =>json_encode($row['qty_asal']),
                'qty_booking' =>json_encode($row['qty_booking']),
                'qty_avs' =>json_encode($row['qty_avs']),
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
        ->select('ds.*')
        ->select('p.nama_part')
        ->select('ds.stock as qty_asal')
        ->select('1 as kuantitas')
        ->select('g.tipe_gudang')
        ->select('g.kategori')
        ->select('"" as id_gudang_tujuan')
        ->select('"" as id_rak_tujuan')
        ->from('ms_h3_dealer_stock as ds')
        ->join('ms_part as p', 'p.id_part_int = ds.id_part_int')
        ->join('ms_gudang_h23 as g', 'g.id_gudang = ds.id_gudang')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        // ->where('ds.id_dealer', $this->input->post('id_dealer'))
        ->where('ds.id_gudang', $this->input->post('id_gudang'))
        // ->limit(5)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search')['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('ds.id_part', $search);
        //     $this->db->or_like('ds.id_gudang', $search);
        //     $this->db->or_like('ds.id_rak', $search);
        //     $this->db->group_end();
        // }

        $cari_kode_part = $this->input->post('cari_kode_part');
        $cari_nama_part = $this->input->post('cari_nama_part');
        $cari_id_rak = $this->input->post('cari_id_rak');

        if($cari_kode_part != ''){
            $this->db->like('ds.id_part', $cari_kode_part);
        }

        if($cari_nama_part != ''){
            $this->db->like('p.nama_part', $cari_nama_part);
        }

        
        if($cari_id_rak != ''){
            $this->db->like('ds.id_rak', $cari_id_rak);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ds.id_part', 'asc');
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
