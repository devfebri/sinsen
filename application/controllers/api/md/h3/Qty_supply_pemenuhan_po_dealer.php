<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Qty_supply_pemenuhan_po_dealer extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['index'] = $this->input->post('start') + $index;
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
        $sales_order_normal = $this->db
        ->select('so.id_sales_order')
        ->select('so.tanggal_order as tgl_so')
        ->select('do.id_do_sales_order')
        ->select('do.tanggal as tgl_do')
        ->select('dop.id_part')
        ->select('dop.qty_supply as kuantitas')
        ->select('ps.no_faktur')
        ->select('ps.tgl_faktur')
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
        ->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
        ->where('dop.id_part', $this->input->post('id_part'))
        ->where('so.id_ref', $this->input->post('po_id'))
        ->get_compiled_select();

        $sales_order_rekap = $this->db
        ->select('so.id_sales_order')
        ->select('so.tanggal_order as tgl_so')
        ->select('do.id_do_sales_order')
        ->select('do.tanggal as tgl_do')
        ->select('dop.id_part')
        ->select('dop.qty_supply as kuantitas')
        ->select('ps.no_faktur')
        ->select('ps.tgl_faktur')
        ->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
        ->join('tr_h3_md_sales_order as so', 'so.id_rekap_purchase_order_dealer = rpodp.id_rekap')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
        ->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order AND dop.id_part = rpodp.id_part)')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
        ->where('rpodp.po_id', $this->input->post('po_id'))
        ->where('rpodp.id_part', $this->input->post('id_part'))
        ->get_compiled_select();

        $this->db
        ->from("
            (
                ({$sales_order_normal})
                UNION
                ({$sales_order_rekap})
            ) as table_pemenuhan_distribusi
        ");
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('table_pemenuhan_distribusi.id_do_sales_order', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('table_pemenuhan_distribusi.tgl_faktur', 'desc');
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
