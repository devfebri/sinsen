<?php

class h3_dealer_sales_order_return_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_sales_order_return';

    public function __construct(){
        parent::__construct();

        $this->load->model('dealer_model', 'dealer');
    }

    public function generateID()
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
            $id_sales_order_return = substr($row->id_sales_order_return, -3);
            $new_kode   = $dealer->kode_dealer_md.'/SOR-'.sprintf("%'.03d", $id_sales_order_return+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/SOR-'.'001';
        }
        return strtoupper($new_kode);
    }
}
