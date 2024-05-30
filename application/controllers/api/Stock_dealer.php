<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Stock_dealer extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function potong(){
        $part = $this->input->post();

        $this->db->trans_start();
        foreach ($part as $each) {
            $this->db->set('stock', "stock - {$each->kuantitas}", FALSE);
            $this->db->where('ds.id_part', $each->id_part);
            $this->db->where('ds.id_gudang', $each->id_gudang);
            $this->db->where('ds.id_rak', $each->id_rak);
            $this->db->update('ms_h3_dealer_stock as ds');
        }
        $this->db->trans_complete();

        if($this->db->trans_status()){
            send_json([
                'message' => 'Stok berhasil dipotong.'
            ]);
        }else{
            $this->output->set_status_header(500);
        }
        
    }
}
