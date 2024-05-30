<?php

class h3_dealer_event_h23_model extends Honda_Model{

    protected $table = 'ms_h3_dealer_event_h23';

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer_model', 'dealer');
    }

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        $data['status'] = 'Open';

        parent::insert($data);
    }

    public function generateID()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();

        $get_data  = $this->db
        ->from($this->table)
        ->limit(1)
        ->order_by('id', 'desc')
        ->get();

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_event = substr($row->id_event, -3);
            $new_kode   = $dealer->kode_dealer_md.'/EV-'.sprintf("%'.03d", $id_event+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/EV-'.'001';
        }
        return strtoupper($new_kode);
    }
}
