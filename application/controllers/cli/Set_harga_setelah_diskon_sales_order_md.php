<?php

use GO\Scheduler;

class Set_harga_setelah_diskon_sales_order_md extends Honda_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->helper('harga_setelah_diskon');
    }

    public function index()
    {
        $this->process();
    }

    public function test(){
        $this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');

        $this->sales_order_parts->set_harga_setelah_diskon(1);
    }

    public function process(){
        $this->db->trans_start();
        $this->db
        ->select('sop.id')
        ->select('sop.id_part')
        ->select('sop.harga')
        ->select('sop.tipe_diskon')
        ->select('sop.diskon_value')
        ->select('sop.tipe_diskon_campaign')
        ->select('sop.diskon_value_campaign')
        ->select('
        case
            when sc.id is not null then (sc.jenis_diskon_campaign = "Additional")
            else 0
        end as additional
        ', false)
        ->from('tr_h3_md_sales_order_parts as sop')
        ->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left')
        ;

        foreach($this->db->get()->result_array() as $part){
            $harga_setelah_diskon = harga_setelah_diskon($part['tipe_diskon'], $part['diskon_value'], $part['harga'], $part['additional'] == 1, $part['tipe_diskon_campaign'], $part['diskon_value_campaign']);

            $this->db
            ->set('harga_setelah_diskon', $harga_setelah_diskon)
            ->where('id', $part['id'])
            ->update('tr_h3_md_sales_order_parts');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}