<?php

use GO\Scheduler;

class Notif_po_ready_for_taken extends Honda_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('notifikasi_model', 'notifikasi');
    }

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->process();
        })->daily();

        $scheduler->run();
    }

    public function process(){
        $dealers = $this->db
        ->select('id_dealer')
        ->from('ms_dealer')
        ->get()->result();

        foreach ($dealers as $dealer) {
            $fulfillments = $this->query_fulfillment($dealer->id_dealer);
            foreach ($fulfillments as $fulfillment) {
                $this->notify_front_desk($fulfillment);
            }
        }
    }

    private function query_fulfillment($id_dealer){
        $quantity_terpenuhi = $this->db
        ->select('sum(pbi.qty_good)')
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_do_sales_order as dso', 'dso.id_sales_order = so.id_sales_order', 'left')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = dso.id_do_sales_order', 'left')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
        ->join('tr_h3_md_surat_pengantar_items as spi', 'spi.id_packing_sheet = ps.id_packing_sheet', 'left')
        ->join('tr_h3_dealer_penerimaan_barang as pb', '(pb.id_packing_sheet = spi.id_packing_sheet and pb.id_surat_pengantar = spi.id_surat_pengantar)', 'left')
        ->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pb.id_penerimaan_barang = pbi.id_penerimaan_barang', 'left')
        ->where('so.id_ref = po.po_id')
        ->get_compiled_select();

        $qty_order = $this->db
        ->select('sum(kuantitas)')
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->where('pop.po_id = po.po_id')
        ->get_compiled_select();

        $this->db
        ->select('po.po_id')
        ->select('po.id_dealer')
        ->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_po')
        ->select("({$qty_order}) as qty_order")
        ->select("ifnull(({$quantity_terpenuhi}), 0) as qty_terpenuhi")
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('tr_h3_dealer_pemesan_request_hotline as prh', 'prh.id = rd.id_data_pemesan', 'left')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->group_start()
        ->where('po.po_type', 'hlo')
        ->where('po.id_booking !=', null)
        ->where('po.id_dealer', $id_dealer)
        ->group_end()
        ->where('po.penyerahan_customer', 0)
        ->having('qty_order = qty_terpenuhi')
        ;

        return $this->db->get()->result();
    }

    public function notify_front_desk($purchase){
        $pesan = "Purchase Order nomor {$purchase->po_id} telah terpenuhi. Harap hubungi customer, untuk pengambilan barang"; 

        $menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_rutin_order_fulfillment')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu_kategori->id_notif_kat,
            'judul' => $menu_kategori->nama_kategori,
            'pesan' => $pesan,
            'link' => "{$menu_kategori->link}/detail?id={$purchase->po_id}",
            'id_dealer' => $purchase->id_dealer,
            'show_popup' => $menu_kategori->popup,
        ]);
    }
}