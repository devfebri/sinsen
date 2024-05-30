<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EventPart extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {

        $book_by_sales = $this->db
        ->select('ifnull( sum(sop.kuantitas), 0) as qty_sales_order')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->where('so.status', 'Open')
        ->where('so.id_inbound_form_for_parts_return', null)
        ->group_start()
        ->where("sop.id_part = ei.id_part")
        ->where("sop.id_gudang = ei.id_gudang")
        ->where("sop.id_rak = ei.id_rak")
        ->group_end()
        ->get_compiled_select();

        $book_by_outbound_fulfillment = $this->db
        ->select('ifnull( sum(offp.kuantitas), 0) as qty_outbound_fulfillment')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as off')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp', 'off.id_outbound_form_for_fulfillment = offp.id_outbound_form_for_fulfillment')
        ->where('off.id_dealer', $this->m_admin->cari_dealer())
        ->where('off.status', 'Open')
        ->group_start()
        ->where("offp.id_part = ei.id_part")
        ->where("offp.id_gudang = ei.id_gudang")
        ->where("offp.id_rak = ei.id_rak")
        ->group_end()
        ->get_compiled_select();

        $parts = $this->db
        ->select('ei.*')
        ->select('p.nama_part')
        ->select('ds.stock')
        ->select("( ds.stock - (({$book_by_sales}) + ({$book_by_outbound_fulfillment})) ) as stock_avs")
        ->select('s.satuan')
        ->from('ms_h3_dealer_event_h23_items as ei')
        ->join('ms_h3_dealer_stock as ds', '(ds.id_part = ei.id_part and ds.id_rak = ei.id_rak and ds.id_gudang = ei.id_gudang)')
        ->join('ms_part as p', 'p.id_part = ei.id_part')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ->where('ei.id_event', $this->input->get('id_event'))
        ->get()->result();

        send_json($parts);
    }
}
