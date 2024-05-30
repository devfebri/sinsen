<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Good_receipt_shipping_list extends CI_Controller
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
            $row['action'] = $this->load->view('additional/action_index_good_receipt_shipping_list', [
                'id' => $row['id_good_receipt']
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
        ->select('gr.*')
        ->select('date_format(gr.tanggal_receipt, "%d-%m-%Y") as tanggal_receipt')
        ->select('pb.id_penerimaan_barang')
        ->select('date_format(pb.tanggal, "%d-%m-%Y") as tanggal_penerimaan')
        ->select('ps.id_packing_sheet')
        ->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tanggal_packing_sheet')
        ->select('ps.no_faktur')
        ->select('so.id_ref as nomor_po')
        ->from('tr_h3_dealer_good_receipt as gr')
        ->join('tr_h3_dealer_penerimaan_barang as pb', 'gr.id_reference = pb.id_packing_sheet')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
        ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'dso.id_sales_order = so.id_sales_order')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
        ->where('gr.id_dealer', $this->m_admin->cari_dealer())
        ->where('gr.ref_type', 'packing_sheet_shipping_list')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('filter_tipe_po') != null){
            $this->db->where('po.po_type', $this->input->post('filter_tipe_po'));
        }

        if($this->input->post('filter_good_receipt_date') != null){
            $this->db->group_start();
            $this->db->where("gr.tanggal_receipt >= '{$this->input->post('start_date')}'");
            $this->db->where("gr.tanggal_receipt <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('gr.id_good_receipt', $search);
            $this->db->or_like('pb.id_penerimaan_barang', $search);
            $this->db->or_like('ps.id_packing_sheet', $search);
            $this->db->or_like('ps.no_faktur', $search);
            $this->db->or_like('so.id_ref', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('gr.created_at', 'DESC');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered(){
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->count_all_results();
    }
}
