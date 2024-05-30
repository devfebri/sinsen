<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class H3_surat_jalan_outbound_model extends Honda_Model {                
    protected $table = 'tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment';     

    public function generateID()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        
        $get_data  = $this->db->query("SELECT * FROM $this->table ORDER BY created_at DESC LIMIT 0,1");
            
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_surat_jalan = substr($row->id_surat_jalan, -3);
            $new_kode   = $dealer->kode_dealer_md.'/SJFF-'.sprintf("%'.03d", $id_surat_jalan+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/SJFF-'.'001';
        }
        return strtoupper($new_kode);
    }
}