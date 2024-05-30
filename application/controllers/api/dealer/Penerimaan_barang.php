<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = [];
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_shipping_list', [
                'id' => $row['nomor_penerimaan']
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
        ->select('pb.id_penerimaan_barang as nomor_penerimaan')
        ->select('date_format(pb.tanggal, "%d-%m-%Y") as tanggal_penerimaan')
        ->select('sp.id_surat_pengantar as nomor_shipping_list')
        ->select('date_format(sp.tanggal, "%d-%m-%Y") as tanggal_shipping_list')
        ->select('ps.id_packing_sheet as nomor_packing_sheet')
        ->select('po.po_id as nomor_po')
        ->select('ps.no_faktur')
        ->from('tr_h3_dealer_penerimaan_barang as pb')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = pb.id_packing_sheet')
        ->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet')
        ->join('tr_h3_md_surat_pengantar as sp', 'sp.id_surat_pengantar = spi.id_surat_pengantar')
        ->join('tr_h3_md_picking_list as pl', 'ps.id_picking_list = pl.id_picking_list')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
        ->where('pb.id_dealer', $this->m_admin->cari_dealer());
    }

    public function make_datatables()
    {
        $this->make_query();

        if($this->input->post('tipe_po') != null){
            $this->db->where('po.po_type', $this->input->post('tipe_po'));
        }

        if($this->input->post('filter_shipping_date') != null){
            $this->db->group_start();
            $this->db->where("pb.tanggal >= '{$this->input->post('start_date')}'");
            $this->db->where("pb.tanggal <= '{$this->input->post('end_date')}'");
            $this->db->group_end();
        }

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->like('pb.id_surat_pengantar', $search);
            $this->db->or_like('po.po_id', $search);
            $this->db->or_like('ps.no_faktur', $search);
            $this->db->or_like('ps.id_packing_sheet', $search);
            $this->db->or_like('pb.id_penerimaan_barang', $search);
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pb.created_at', 'desc');
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