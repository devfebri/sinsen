<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_filter_laporan_stock_versi_all extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin');

        ini_set('max_input_vars','60000');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_part_filter_laporan_stock_versi_all', [
                'data' => json_encode($row),
                'id_part' => $row['id_part']
            ], true);
            
            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            ''
        ]);
    }
    
    public function make_query() {
        $id_part_ada_stock = $this->db
        ->select('ds.id_part')
        ->from('ms_h3_dealer_stock as ds')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        ->where('ds.stock > ', 0, false)
        ->get_compiled_select();

        $id_part_terdapat_penjualan = $this->db
        ->select('DISTINCT(sop.id_part) as id_part')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->get()->result_array();
        $id_part_terdapat_penjualan = array_map(function($row){
            return $row['id_part'];
        }, $id_part_terdapat_penjualan);

        $id_part_terdapat_penjualan = $this->db
        ->select('DISTINCT(sop.id_part) as id_part')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->get()->result_array();
        $id_part_terdapat_penjualan = array_map(function($row){
            return $row['id_part'];
        }, $id_part_terdapat_penjualan);

        $id_part_terdapat_pembelian = $this->db
        ->select('DISTINCT(pop.id_part) as id_part')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = po.po_id')
        ->where('po.id_dealer', $this->m_admin->cari_dealer())
        ->get()->result_array();
        $id_part_terdapat_pembelian = array_map(function($row){
            return $row['id_part'];
        }, $id_part_terdapat_pembelian);

        $this->db
        ->select('p.id_part')
        ->select('p.nama_part')
        ->from('ms_part as p')
        ->group_start()
        ->where("p.id_part in ({$id_part_ada_stock})", null, false)
        ;

        if(count($id_part_terdapat_penjualan) > 0){
            $this->db->or_where_in('p.id_part', $id_part_terdapat_penjualan);
        }

        if(count($id_part_terdapat_pembelian) > 0){
            $this->db->or_where_in('p.id_part', $id_part_terdapat_pembelian);
        }
        $this->db->group_end();
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('p.id_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('p.id_part', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function check_all(){
        $this->make_query();
        
        $parts_filter = $this->db->get()->result_array();
        $parts_filter = array_map(function($row){
            return $row['id_part'];
        }, $parts_filter);

        send_json($parts_filter);
    }
}
