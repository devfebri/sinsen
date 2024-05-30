<?php

use GO\Scheduler;

class Rekap_invoice_ahm extends Honda_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_rekap_invoice_ahm_model', 'rekap_invoice');
        $this->load->model('H3_md_rekap_invoice_ahm_items_model', 'rekap_invoice_items');
    }

    public function index()
    {
        // $scheduler = new Scheduler();

        // $scheduler->call(function () {
        //     $this->process();
        // })->daily();

        // $scheduler->run();
    }

    public function process(){
        $this->db->trans_start();
        
        $invoices = $this->get_invoices_group_by_dpp_due_date();

        $rekap = [];
        $rekap_items = [];
        foreach ($invoices as $date => $data) {
            $rekap['id_rekap_invoice'] = date('dmY', strtotime($date));
            $rekap['tgl_jatuh_tempo'] = $date;
            $rekap['total_dpp'] = $this->sumBy($data, 'total_dpp'); 
            $rekap['total_ppn'] = $this->sumBy($data, 'total_ppn');
            
            $rekap_items = [];
            foreach ($data as $invoice) {
                $sub_array = [];
                $sub_array['id_rekap_invoice'] = $rekap['id_rekap_invoice'];
                $sub_array['invoice_number'] = $invoice['invoice_number'];
                $rekap_items[] = $sub_array;
            }

            $this->rekap_invoice->insert($rekap);
            $this->rekap_invoice_items->insert_batch($rekap_items);
        }

        $this->db->trans_complete();
    }

    public function get_invoices_group_by_dpp_due_date(){
        $invoice_sudah_terekap = $this->db
        ->select('riai.invoice_number')
        ->from('tr_h3_rekap_invoice_ahm_items as riai')
        ->get_compiled_select();

        $invoice_belum_terekap = $this->db
        ->from('tr_h3_md_fdo as fdo')
        ->where("fdo.invoice_number not in ({$invoice_sudah_terekap})")
        ->get()->result_array();

        $result = [];
        foreach ($invoice_belum_terekap as $each) {
            $result[$each['dpp_due_date']][] = $each;
        }
        return $result;
    }

    public function sumBy($array, $key){
        $total = 0;
        foreach ($array as $data) {
            $total += $data[$key];
        }
        return $total;
    }
}