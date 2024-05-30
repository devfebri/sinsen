<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_ptrac extends CI_Controller {

    public function index(){
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $index++;
            $data[] = $row;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query(){
        $this->db
        ->select('ptrac.*')
        ->select('IFNULL(ptrac.qty_book, 0) as qty_book')
        ->select('IFNULL(ptrac.qty_picking, 0) as qty_picking')
        ->select('IFNULL(ptrac.qty_packing, 0) as qty_packing')
        ->select('IFNULL(ptrac.qty_invoice, 0) as qty_invoice')
        ->select('IFNULL(ptrac.qty_ship, 0) as qty_ship')
        ->select('p.nama_part')
        ->select('po.id_purchase_order')
        ->from('ms_ptrac as ptrac')
        ->join('ms_part as p', 'p.id_part = ptrac.id_part')
        ->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = ptrac.no_po_md', 'left')
        ;
    }

    public function make_datatables(){
        $this->make_query();

        $filter_kode_part = $this->input->post('filter_kode_part');
        if (count($filter_kode_part) > 0) {
            $this->db->where_in('ptrac.id_part', $filter_kode_part);
        }

        $filter_kelompok_part = $this->input->post('filter_kelompok_part');
        if (count($filter_kelompok_part) > 0) {
            $this->db->where_in('p.kelompok_part', $filter_kelompok_part);
        }

        $filter_purchase_order = $this->input->post('filter_purchase_order');
        if(count($filter_purchase_order) > 0){
            $this->db->where_in('ptrac.no_po_md', $filter_purchase_order);
        }

        if ($this->input->post('filter_bulan_po') != null) {
            $this->db->where('po.bulan', $this->input->post('filter_bulan_po'));
        }

        if ($this->input->post('filter_tipe_po') != null) {
            $this->db->where('po.jenis_po', $this->input->post('filter_tipe_po'));
        }

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ptrac.id_part', $search);
            $this->db->or_like('ptrac.no_po_ahm', $search);
            $this->db->or_like('ptrac.no_po_md', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ptrac.id_part', 'asc');
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
