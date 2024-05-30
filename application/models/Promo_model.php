<?php

class Promo_model extends Honda_Model
{
    protected $table = 'ms_h3_promo_dealer';

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer_model', 'dealer');
    }

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
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
        ->where("LEFT(created_at,7)='{$th_bln}'")
        ->limit(1)
        ->order_by('id', 'desc')
        ->get();

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $id_promo = substr($row->id_promo, -5);
            $new_kode   = 'PRM/'. date('m') . '/' . date('y') .'/' . sprintf("%'.05d", $id_promo+1);
        } else {
            $new_kode   = 'PRM/'. date('m') . '/' . date('y') .'/00001';
        }
        return strtoupper($new_kode);
    }
}
