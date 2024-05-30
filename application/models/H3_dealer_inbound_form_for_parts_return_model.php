<?php

class h3_dealer_inbound_form_for_parts_return_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_inbound_form_for_parts_return';

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer_model', 'dealer');
    }

    public function generateID($id_dealer = null)
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        if($id_dealer != null){
            $dealer = $this->db
            ->from('ms_dealer')
            ->where('id_dealer', $id_dealer)
            ->limit(1)
            ->get()->row();
        }else{
            $dealer    = $this->dealer->getCurrentUserDealer();
        }

        $get_data = $this->db
        ->from($this->table)
        ->where('id_dealer', $dealer->id_dealer)
        ->limit(1)
        ->order_by('id_inbound_form_for_parts_return', 'desc')
        ->order_by('created_at', 'desc')
        ->get();

        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_inbound_form_for_parts_return = substr($row->id_inbound_form_for_parts_return, -3);
            $new_kode   = $dealer->kode_dealer_md.'/IFPR-'.sprintf("%'.03d", $id_inbound_form_for_parts_return+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/IFPR-'.'001';
        }
        return strtoupper($new_kode);
    }
}
