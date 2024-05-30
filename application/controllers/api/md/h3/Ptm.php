<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ptm extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();
        $records = $this->db->get()->result();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $data[] = $sub_arr;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('ptm.*')
        ->select('
        case
            when ptm.terakhir_efektif != "" then date_format(ptm.terakhir_efektif, "%d/%m/%Y")
            else "-"
        end as terakhir_efektif')
        ->from('ms_ptm as ptm')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('tipe_produksi_filter') != '') {
            $this->db->like('ptm.tipe_produksi', $this->input->post('tipe_produksi_filter'));
        }

        if ($this->input->post('tipe_marketing_filter') != '') {
            $this->db->like('ptm.tipe_marketing', $this->input->post('tipe_marketing_filter'));
        }

        if ($this->input->post('deskripsi_filter') != '') {
            $this->db->like('ptm.deskripsi', $this->input->post('deskripsi_filter'));
        }

        if ($this->input->post('tanggal_terakhir_efektif_filter') != '') {
            $this->db->where('ptm.terakhir_efektif', $this->input->post('tanggal_terakhir_efektif_filter'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ptm.terakhir_efektif', 'desc');
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
        return $this->db->from('ms_ptm')->count_all_results();
    }
}
