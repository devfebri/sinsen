<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitor_file_transfer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
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
        ->select('d.nama_dealer')
        ->select('d.kode_dealer_md')
        ->select('d.alamat')
        ->select('
            case
                when ft.tanggal_upload_stok is null then "-"
                else date_format(ft.tanggal_upload_stok, "%d/%m/%Y %H:%i")
            end as tanggal_upload_stok
        ', false)
        ->select('
            case
                when ft.tanggal_upload_sales is null then "-"
                else date_format(ft.tanggal_upload_sales, "%d/%m/%Y %H:%i")
            end as tanggal_upload_sales
        ', false)
        ->from('ms_dealer as d')
        ->join('tr_h3_md_file_transfer as ft', 'ft.id_dealer = d.id_dealer', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->like('d.kode_dealer_md', $search);
            $this->db->or_like('d.nama_dealer', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('d.nama_dealer', 'asc');
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
