<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qty_hotline_online_stock_dealer extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $rows = $this->make_datatables();
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'data' => $rows
        ]);
    }
    
    public function make_query() {
        $qty_order_fulfillment = $this->db
		->select('SUM(of.qty_fulfillment) as qty_fulfillment', false)
		->from('tr_h3_dealer_order_fulfillment as of')
		->where('of.po_id = po.po_id', null, false)
		->where('of.id_part = pop.id_part', null, false)
		->get_compiled_select();

        $kuantitas_sales_order_closed = $this->db
        ->select('SUM(sop.kuantitas) as kuantitas', false)
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'sop.nomor_so = so.nomor_so')
        ->where('so.id_dealer', $this->input->post('id_customer_filter'))
        ->where('sop.id_part = pop.id_part', null, false)
        ->where('so.booking_id_reference = po.id_booking', null, false)
        ->where('so.status', 'Closed')
        ->get_compiled_select();

        $this->db
        ->select('po.po_id')
        ->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
        ->select("IFNULL(({$qty_order_fulfillment}), 0) as kuantitas", false)
        ->select('c.nama_customer')
        ->select('po.penyerahan_customer')
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pop.po_id')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->where('po.id_dealer', $this->input->post('id_customer_filter'))
        ->where('po.po_type', 'HLO')
        ->where('po.penyerahan_customer', 0)
        ->where('pop.id_part', $this->input->post('id_part_for_view_qty_hotline'))
        ->where("(IFNULL(({$qty_order_fulfillment}), 0) - IFNULL(({$kuantitas_sales_order_closed}), 0)) != 0", null, false)
        ;
    }

    public function make_datatables() {
        $this->make_query();

        $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('p.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_part[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.created_at', 'desc');
        }

        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}
