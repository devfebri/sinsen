<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Parts_event extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('h3_dealer_stock_model', 'stock');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['stock_avs'] = $this->stock->qty_avs($row['id_dealer'], $row['id_part'], $row['id_gudang'], $row['id_rak']);
            $row['action'] = $this->load->view('additional/action_parts_event', [
                'data' => json_encode($row),
                'id_part' => $row['id_part'],
                'id_gudang' => $row['id_gudang'],
                'id_rak' => $row['id_rak'],
            ], true);
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
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
        ->select('1 as kuantitas')
        ->select('s.satuan')
        ->select('format(ds.stock, 0, "ID_id") as stock')
        ->from('ms_h3_dealer_stock as ds')
        ->join('ms_part as p', 'p.id_part = ds.id_part')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        ;
    }



    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ds.id_part', $search);
            $this->db->or_like('p.nama_part', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ds.id_part', 'DESC');
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
