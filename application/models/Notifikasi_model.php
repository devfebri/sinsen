<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Notifikasi_model extends Honda_Model {
    protected $table = 'tr_notifikasi';

    public function insert($data){
        $data = array_merge($data, [
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('id_user'),
            'status' => 'baru',
        ]);

        parent::insert($data);
    }
}
