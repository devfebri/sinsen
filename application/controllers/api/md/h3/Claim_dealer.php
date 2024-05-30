<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Claim_dealer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_claim_dealer_datatable', [
                'id_claim_dealer' => $row['id_claim_dealer']
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
        ->select('date_format(cd.tanggal, "%d/%m/%Y") as tanggal')
        ->select('cd.id_claim_dealer')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('date_format(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur')
        ->select('ps.no_faktur')
        ->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
        ->select('ps.id_packing_sheet as id_packing_sheet')
        ->select('cd.status')
        ->from('tr_h3_md_claim_dealer as cd')
        ->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        // $search = trim($this->input->post('search') ['value']);
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('cd.id_claim_dealer', $search);
        //     $this->db->or_like('cd.id_packing_sheet', $search);
        //     $this->db->group_end();
        // }
        
        if (count($this->input->post('filter_customer')) > 0) {
            $this->db->where_in('cd.id_dealer', $this->input->post('filter_customer'));
        }

        if (count($this->input->post('filter_status')) > 0) {
            $this->db->where_in('cd.status', $this->input->post('filter_status'));
        }

        if($this->input->post('periode_claim_filter_start') != null and $this->input->post('periode_claim_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('cd.tanggal >=', $this->input->post('periode_claim_filter_start'));
            $this->db->where('cd.tanggal <=', $this->input->post('periode_claim_filter_end'));
            $this->db->group_end();
        }

        if ($this->input->post('no_claim_customer_filter') != null) {
            $this->db->like('cd.id_claim_dealer', trim($this->input->post('no_claim_customer_filter')));
        }

        if ($this->input->post('no_faktur_filter') != null) {
            $this->db->like('ps.no_faktur', trim($this->input->post('no_faktur_filter')));
        }

        if ($this->input->post('no_surat_jalan_filter') != null) {
            $this->db->like('cd.no_surat_jalan_ahm', trim($this->input->post('no_surat_jalan_filter')));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('cd.created_at', 'desc');
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
