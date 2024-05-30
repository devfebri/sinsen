<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nota_retur_penjualan extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'input' => $this->input->post(),
        ]);
    }

    public function make_query()
    {

        $tanggal = date("Y-m-d");
        // $tanggal = '2023-07-13';
        // if($tanggal <='2023-07-10' || $tanggal >='2023-07-15'){
        // if(1){
        if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
            $where = '';
        }else{
            $where = "and p.kelompok_part !='FED OIL'";
        }
        
        $this->db
		->select('ps.nomor_ps')
		->select('ps.tanggal_ps')
		->select('so.nomor_so')
		->select('so.id_work_order')
		->select('wo.created_at as tgl_wo')
		->select('wo.no_nsc')
		->select('c.nama_customer')
		->select('c.no_polisi')
		->select('sop.id_part')
		->select('p.nama_part')
		->select('sop.id_rak')
		->select('sop.kuantitas')
		->select('sop.kuantitas_return')
		->select('(sop.kuantitas - sop.kuantitas_return) as kuantitas_terpakai', false)
		->from('tr_h3_dealer_sales_order as so')
		->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->join('tr_h3_dealer_picking_slip as ps', 'ps.nomor_so = so.nomor_so')
		->join('ms_part as p', 'p.id_part_int = sop.id_part_int '.$where)
		->join('tr_h2_wo_dealer as wo', 'wo.id_work_order = so.id_work_order', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer_int = so.id_customer_int', 'left')
		->where('sop.kuantitas_return > ', 0)
        ->where('ps.id_dealer', $this->m_admin->cari_dealer())
        ->where("ps.tanggal_ps between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}'", null, false);
		;
    }

    public function make_datatables()
    {
        $this->make_query();
      
        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('so.nomor_so', $search);
            $this->db->or_like('so.id_work_order', $search);
            $this->db->or_like('wo.no_nsc', $search);
            $this->db->or_like('c.no_polisi', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.created_at', 'DESC');
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

    public function get_qty(){
        $this->make_datatables();
        $this->db->select('SUM(grp.qty) as kuantitas');

        $data = $this->db->get()->row_array();

        send_json([
            'kuantitas' => $data['kuantitas']
        ]);
    }

    public function get_total_harga(){
        $this->make_datatables();
        $this->db->select('SUM(grp.qty * grp.harga_setelah_diskon) as total_harga');

        $data = $this->db->get()->row_array();

        send_json([
            'total_harga' => $data['total_harga']
        ]);
    }
}
