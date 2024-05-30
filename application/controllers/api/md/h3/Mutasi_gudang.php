<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_gudang extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_mutasi_gudang_datatable', [
                'id_mutasi_gudang' => $row['id_mutasi_gudang']
            ], true);

            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'data' => $data,
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
        ]);
    }

    public function make_query()
    {
        $this->db
        ->select('mg.id_mutasi_gudang')
        ->select('date_format(mg.tanggal, "%d/%m/%Y") as tanggal')
        ->select('gudang_awal.nama_gudang as gudang_awal')
        ->select('lokasi_awal.kode_lokasi_rak as lokasi_awal')
        ->select('gudang_tujuan.nama_gudang as gudang_tujuan')
        ->select('lokasi_tujuan.kode_lokasi_rak as lokasi_tujuan')
        ->select('mg.qty')
        ->from('tr_h3_md_mutasi_gudang as mg')
        ->join('ms_h3_md_gudang as gudang_awal', 'gudang_awal.id = mg.id_gudang_awal')
        ->join('ms_h3_md_lokasi_rak as lokasi_awal', 'lokasi_awal.id = mg.id_lokasi_awal')
        ->join('ms_h3_md_gudang as gudang_tujuan', 'gudang_tujuan.id = mg.id_gudang_tujuan')
        ->join('ms_h3_md_lokasi_rak as lokasi_tujuan', 'lokasi_tujuan.id = mg.id_lokasi_tujuan')
        ;
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('left(mg.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('left(mg.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('mg.id_mutasi_gudang', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('mg.created_at', 'desc');
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
