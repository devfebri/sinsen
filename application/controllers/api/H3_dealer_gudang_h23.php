<?php
defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_gudang_h23 extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
    }
    public function rak_pada_gudang(){
        $id_gudang = $this->input->get('k');

        $result = $this->lokasi_rak_bin->get([
            'id_gudang' => $id_gudang
        ]);

        send_json($result);
    }
}
