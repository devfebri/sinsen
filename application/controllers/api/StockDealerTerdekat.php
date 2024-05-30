<?php

defined('BASEPATH') or exit('No direct script access allowed');

class StockDealerTerdekat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_admin');
        $this->load->model('dealer_terdekat_model', 'dealer_terdekat');
    }

    public function index()
    {
        if($this->input->get('id_part') != null){
            $kuantitas = 0;
            if($this->input->get('qty') != null){
                $kuantitas = $this->input->get('qty');
            }

            // $query = $this->db
            // ->select('p.id_part')
            // ->select('p.nama_part')
            // ->select('concat("Rp ", format(p.harga_dealer_user, 0, "id_ID")) as harga_saat_dibeli')
            // ->select('d.nama_dealer')
            // ->select("
            //     case
            //         when ds.stock < ifnull(dmp.min_stok, 0) then 'Tidak Ada'
            //         when (ds.stock - {$kuantitas}) >= ifnull(dmp.min_stok, 0) then 'Ada'
            //         when (ds.stock - {$kuantitas}) < ifnull(dmp.min_stok, 0) then 'Ada - Tidak Cukup'
            //         when sum(ds.stock) = 0 then 'Tidak Ada'
            //     end as status
            // ")
            // ->select('dmp.min_stok')
            // ->from('ms_h3_dealer_terdekat as dt')
            // ->join('ms_dealer as d', 'd.id_dealer = dt.id_dealer_terdekat')
            // ->join('ms_h3_dealer_stock as ds', "(ds.id_dealer = dt.id_dealer_terdekat and ds.id_part = '{$this->input->get('id_part')}')")
            // ->join('ms_part as p', 'p.id_part = ds.id_part')
            // ->join('ms_h3_dealer_master_part as dmp', '(dmp.id_part=ds.id_part and dmp.id_dealer = dt.id_dealer_terdekat)', 'left')
            // ->where('dt.id_dealer', $this->m_admin->cari_dealer())
            // ->order_by('dt.id_dealer_terdekat', 'DESC')
            // // ->having('sum(ds.stock) >= dmp.min_stok')
            // ;

            $stock = $this->db
            ->select('sum(ds.stock)')
            ->from('ms_h3_dealer_stock as ds')
            ->group_start()
            ->where('ds.id_dealer = dt.id_dealer_terdekat')
            ->where('ds.id_part = p.id_part')
            ->group_end()
            ->get_compiled_select();

            $min_stock = $this->db
            ->select('dmp.min_stok')
            ->from('ms_h3_dealer_master_part as dmp')
            ->group_start()
            ->where('dmp.id_dealer = dt.id_dealer_terdekat')
            ->where('dmp.id_part = p.id_part')
            ->group_end()
            ->get_compiled_select();

            $query = $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('concat("Rp ", format(p.harga_dealer_user, 0, "id_ID")) as harga_saat_dibeli')
            ->select('d.nama_dealer')
            ->select("ifnull(({$stock}), 0) as stock")
            ->select("
                case
                    when ifnull(({$stock}), 0) = ifnull(({$min_stock}), 0) then 'Tidak Ada'
                    when ifnull(({$stock}), 0) < ifnull(({$min_stock}), 0) then 'Tidak Ada'
                    when (ifnull(({$stock}), 0) - {$kuantitas}) >= ifnull(({$min_stock}), 0) then 'Ada'
                    when (ifnull(({$stock}), 0) - {$kuantitas}) < ifnull(({$min_stock}), 0) then 'Ada - Tidak Cukup'
                end as status
            ")
            ->from('ms_h3_dealer_terdekat as dt')
            ->join('ms_dealer as d', 'd.id_dealer = dt.id_dealer_terdekat')
            ->join('ms_part as p', "p.id_part = '{$this->input->get('id_part')}'")
            ->where('dt.id_dealer', $this->m_admin->cari_dealer())
            ->order_by('dt.id_dealer_terdekat', 'DESC')
            // ->having('sum(ds.stock) >= dmp.min_stok')
            ;
            send_json($query->get()->result());
        }else{
            send_json([]);
        }
    }
}