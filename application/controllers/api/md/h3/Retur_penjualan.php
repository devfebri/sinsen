<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Retur_penjualan extends CI_Controller
{
    public function index()
    {
        $records = $this->make_datatables();
        $data = array();
        foreach ($records as $each) {
            $sub_arr = (array) $each;
            $sub_arr['action'] = $this->load->view('additional/md/h3/action_index_retur_penjualan_datatable', [
                'id_retur_penjualan' => $each->id_retur_penjualan
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
        ->select('date_format(rp.created_at, "%d-%m-%Y") as created_at')
        ->select('rp.id_retur_penjualan')
        ->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
        ->select('rp.no_faktur')
        ->select('d.nama_dealer')
        ->select('d.alamat')
        ->select('do.total as total_nilai_faktur')
        ->select('rp.total_nilai_retur')
        ->select('rp.status')
        ->from('tr_h3_md_retur_penjualan as rp')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = rp.no_faktur')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
        ->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
        ;

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('rp.status', 'Processed');
            $this->db->or_where('rp.status', 'Canceled');
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->where('rp.status !=', 'Processed');
            $this->db->where('rp.status !=', 'Canceled');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('rp.id_retur_penjualan', $search);
            $this->db->or_like('rp.no_faktur', $search);
            $this->db->or_like('d.nama_dealer', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'desc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
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
