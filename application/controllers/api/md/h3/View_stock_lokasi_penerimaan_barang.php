<?php

defined('BASEPATH') or exit('No direct script access allowed');

class View_stock_lokasi_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        ini_set('max_execution_time', '0');

        $this->load->model('H3_md_stock_model', 'stock');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $row['qty_onhand'] = $this->stock->qty_on_hand($row['id_part'], $row['id_lokasi_rak']);
            $row['sisa'] = $row['qty_maks'] - $row['qty_onhand'];

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

    public function make_query()
    {
        $this->db
        ->select('lrp.id_lokasi_rak')
        ->select('lrp.id_part')
        ->select('lrp.id_part_int')
        ->select('p.nama_part')
        ->select('lrp.qty_maks')
        ->from('ms_h3_md_lokasi_rak_parts as lrp')
        ->join('ms_part as p', 'p.id_part_int = lrp.id_part_int')
        ->where('lrp.id_lokasi_rak', $this->input->post('id'));
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('lrp.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('lrp.id_part', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
