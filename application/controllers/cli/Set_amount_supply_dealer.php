<?php

use GO\Scheduler;

class Set_amount_supply_dealer extends Honda_Controller {

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->process();
        });

        $scheduler->run();
    }

    public function process(){
        $this->db->trans_start();
        $this->db->set('amount_penerimaan', 0)->update('tr_h3_dealer_purchase_order');

        $this->db
        ->select('po.po_id')
        ->select('pb.id_penerimaan_barang')
        ->select('pbi.id_part')
        ->select('pbi.qty_good')
        ->select('pop.harga_setelah_diskon')
        ->from('tr_h3_dealer_penerimaan_barang as pb')
        ->join('tr_h3_dealer_penerimaan_barang_items as pbi', 'pbi.id_penerimaan_barang = pb.id_penerimaan_barang')
        ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = pb.nomor_po')
        ->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.po_id = po.po_id AND pop.id_part = pbi.id_part)')
        ;

        foreach($this->db->get()->result_array() as $row){
            $amount_penerimaan = $row['qty_good'] + $row['harga_setelah_diskon'];

            $this->db
            ->set('amount_penerimaan', "(amount_penerimaan + {$amount_penerimaan})", false)
            ->where('po_id', $row['po_id'])
            ->update('tr_h3_dealer_purchase_order');
        }

        $this->db->trans_complete();
    }
}