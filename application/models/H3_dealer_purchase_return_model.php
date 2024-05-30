<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class H3_dealer_purchase_return_model extends Honda_Model {
    protected $table = 'tr_h3_dealer_purchase_return';        
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dealer_model', 'dealer');
    }

    public function generateIdPurchaseReturn()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        
        $get_data  = $this->db->query("SELECT * FROM $this->table
            ORDER BY created_at DESC LIMIT 0,1");
            
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_purchase_return = substr($row->id_purchase_return, -3);
            $new_kode   = $dealer->kode_dealer_md.'/PS-'.sprintf("%'.03d", $id_purchase_return+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/PS-'.'001';
        }
        return strtoupper($new_kode);
    }
}
