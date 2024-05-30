<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Potong_stock extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
    }

    public function index()
    {
        $this->db->trans_start();
        if(count($this->input->post('parts')) > 0){
            foreach ($this->input->post('parts') as $part) {
                $transaksi_stock = [
                    'id_part' => $part['id_part'],
                    'id_gudang' => $part['id_gudang'],
                    'id_rak' => $part['id_rak'],
                    'tipe_transaksi' => '-',
                    'sumber_transaksi' => $this->input->post('sumber_transaksi'),
                    'referensi' => $this->input->post('referensi'),
                    'stok_value' => $part['qty'],
                ];

                $this->transaksi_stok->insert($transaksi_stock);
                $this->db->set('stock', "stock - {$part['qty']}", FALSE);
                $this->db->where('ds.id_part', $part['id_part']);
                $this->db->where('ds.id_gudang', $part['id_gudang']);
                $this->db->where('ds.id_rak', $part['id_rak']);
                $this->db->where('ds.id_dealer', $this->m_admin->cari_dealer());
                $this->db->update('ms_h3_dealer_stock as ds');
            }
        }
        $this->db->trans_complete();

        if($this->db->trans_status()){
            send_json([
               'success' => 1 
            ]);
        }else{
            $this->output->set_status_header(500);
            send_json([
                'success' => 0
             ]);
        }
    }
}
