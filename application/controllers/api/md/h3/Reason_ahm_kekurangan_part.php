<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reason_ahm_kekurangan_part extends CI_Controller
{
    public function __construct(){
        ini_set('max_execution_time', '0');

        parent::__construct();
    }
    
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $this->db->get()->result_array(),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('kc.kode_claim')
        ->select('kc.nama_claim')
        ->select('pbr.checked')
        ->select('pbr.qty')
        ->select('pbr.keterangan')
        ->from('tr_h3_md_penerimaan_barang_reasons as pbr')
        ->join('ms_kategori_claim_c3 as kc', 'kc.id = pbr.id_claim')
        ->where('pbr.id_penerimaan_barang_item', $this->input->post('id_penerimaan_barang_item_for_reason_ahm'))
        ->where('kc.tipe_claim !=', 'Claim Ekspedisi')
        ;
    }

    public function make_datatables(){
        $this->make_query();
        
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by("kc.kode_claim", 'asc');
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
