<?php

class h3_dealer_outbound_form_for_fulfillment_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_outbound_form_for_fulfillment';

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer_model', 'dealer');
    }

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s');
        parent::insert($data);
    }

    public function generateID()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        $id_dealer = $dealer->id_dealer;

        $get_data = $this->db
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as of')
        ->where('of.id_dealer', $id_dealer)
        ->order_by('id', 'desc')
        ->limit(1)
        ->get();

        // ->order_by('created_at', 'desc') 
        // ->order_by('id_outbound_form_for_fulfillment', 'desc')

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_outbound_form_for_fulfillment = substr($row->id_outbound_form_for_fulfillment, -3);
            $new_kode   = $dealer->kode_dealer_md.'/OFFF-'.sprintf("%'.03d", $id_outbound_form_for_fulfillment+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/OFFF-'.'001';
        }
        return strtoupper($new_kode);
    }

    public function generateSuratJalan()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();

        $get_data  = $this->db->query("SELECT * FROM $this->table ORDER BY created_at DESC LIMIT 0,1");

        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $surat_jalan = substr($row->surat_jalan, -3);
            $new_kode   = $dealer->kode_dealer_md.'/SJFF-'.sprintf("%'.03d", $surat_jalan+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/SJFF-'.'001';
        }
        return strtoupper($new_kode);
    }
}
