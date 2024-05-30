<?php

use GO\Scheduler;

class Set_harga_po_dealer extends Honda_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->helper('harga_setelah_diskon');
    }

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
        $this->db
        ->select('pop.po_id')
        ->select('pop.id_part')
        ->select('pop.kuantitas')
        ->select('pop.harga_saat_dibeli')
        ->select('pop.tipe_diskon')
        ->select('pop.diskon_value')
        ->select('pop.tipe_diskon_campaign')
        ->select('pop.diskon_value_campaign')
        ->select('
        case
            when sc.id is not null then (sc.jenis_diskon_campaign = "Additional")
            else 0
        end as additional
        ', false)
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('ms_h3_md_sales_campaign as sc', 'sc.id = pop.id_campaign_diskon', 'left')
        ;

        foreach($this->db->get()->result_array() as $part){
            $harga_setelah_diskon = harga_setelah_diskon($part['tipe_diskon'], $part['diskon_value'], $part['harga_saat_dibeli'], $part['additional'] == 1, $part['tipe_diskon_campaign'], $part['diskon_value_campaign']);
            $tot_harga_part = $harga_setelah_diskon * $part['kuantitas'];

            $this->db
            ->set('harga_setelah_diskon', $harga_setelah_diskon)
            ->set('tot_harga_part', $tot_harga_part)
            ->where('po_id', $part['po_id'])
            ->where('id_part', $part['id_part'])
            ->update('tr_h3_dealer_purchase_order_parts');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}