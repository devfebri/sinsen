<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_filter_laporan_sales_kelompok_part extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_part_filter_laporan_sales_kelompok_part', [
                'data' => json_encode($row),
                'kelompok_part' => $row['kelompok_part']
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
    
    public function make_query() {
        $kelompok_part_terdapat_penjualan = $this->db
        ->select('DISTINCT(p.kelompok_part) as kelompok_part')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->get()->result_array();
        $kelompok_part_terdapat_penjualan = array_map(function($row){
            return $row['kelompok_part'];
        }, $kelompok_part_terdapat_penjualan);

        $this->db
        ->select('kp.kelompok_part')
        ->from('ms_kelompok_part as kp')
        ;

        if(count($kelompok_part_terdapat_penjualan) > 0){
            $this->db->or_where_in('kp.kelompok_part', $kelompok_part_terdapat_penjualan);
        }else{
            $this->db->where('1=0', null, false);
        }
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('kp.kelompok_part', $search);
            $this->db->group_end();
        }
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('kp.kelompok_part', 'asc');
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
}
