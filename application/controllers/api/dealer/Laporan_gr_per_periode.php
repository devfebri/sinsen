<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_gr_per_periode extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->select_datatable();
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

    public function select_datatable(){
        $this->db
        ->select('gr.id_good_receipt')
        ->select('gr.tanggal_receipt')
        ->select('
            case
                when gr.ref_type = "part_sales_work_order" then gr.id_reference
                when gr.ref_type = "return_exchange_so" then gr.id_reference
                when gr.ref_type = "packing_sheet_shipping_list" then ps.no_faktur
            end as id_reference
        ', false)
        ->select('gr.ref_type')
        ->select('grp.id_part')
        ->select('p.nama_part')
        ->select('grp.qty')
        ->select('grp.harga_setelah_diskon as harga_beli')
        ->select('(grp.qty * grp.harga_setelah_diskon) as total_harga');
    }

    public function make_query()
    {
        $this->db
        ->from('tr_h3_dealer_good_receipt as gr')
        ->join('tr_h3_dealer_good_receipt_parts as grp', 'grp.id_good_receipt = gr.id_good_receipt')
        ->join('ms_part as p', 'p.id_part_int = grp.id_part_int')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = gr.id_reference', 'left')
        ->where('gr.id_dealer', $this->m_admin->cari_dealer());
    }

    public function make_datatables()
    {
        $this->make_query();
        
        if($this->input->post('periode_filter_start') != null AND $this->input->post('periode_filter_end') != null){
            $this->db->where("gr.tanggal_receipt between '{$this->input->post('periode_filter_start')}' AND '{$this->input->post('periode_filter_end')}'", null, false);
        }

        if($this->input->post('tipe_referensi_filter') != null){
            $this->db->where('gr.ref_type', $this->input->post('tipe_referensi_filter'));
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('gr.id_good_receipt', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('gr.created_at', 'DESC');
            $this->db->order_by('gr.id_good_receipt', 'DESC');
            $this->db->order_by('grp.id_part', 'DESC');
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
