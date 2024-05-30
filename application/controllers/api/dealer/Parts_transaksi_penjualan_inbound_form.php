<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_transaksi_penjualan_inbound_form extends CI_Controller
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
            $sub_arr['action'] = $this->load->view('additional/action_parts_transaksi_penjualan_inbound_form', [
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
        ->select('p.id_part')
        ->select('p.nama_part')
        ->select('p.harga_dealer_user as harga')
        ->select('1 as qty')
        ->select('fp.id_gudang')
        ->select('fp.id_rak')
        ->select('fp.kuantitas as qty_pinjam')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as fp')
        ->join('ms_part as p', 'p.id_part = fp.id_part')
        ->where('fp.id_outbound_form_for_fulfillment', $this->input->post('id_outbound_form'))
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('fp.id_part', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('fp.id_outbound_form_for_fulfillment_parts', 'ASC');
        }

        if ($_POST["length"] != - 1) {
            $this
                ->db
                ->limit($_POST['length'], $_POST['start']);
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
