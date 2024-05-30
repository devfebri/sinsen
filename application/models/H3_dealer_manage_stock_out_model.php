<?php

class h3_dealer_manage_stock_out_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_manage_stock_out';

    public function generateIdManageStockOut()
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
            $id_manage_stock_out = substr($row->id_manage_stock_out, -3);
            $new_kode   = $dealer->kode_dealer_md.'/MSO-'.sprintf("%'.03d", $id_manage_stock_out+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/MSO-'.'001';
        }
        return strtoupper($new_kode);
    }
}
