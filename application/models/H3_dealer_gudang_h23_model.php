<?php

class h3_dealer_gudang_h23_model extends Honda_Model
{
    protected $table = 'ms_gudang_h23';

    public function __construct()
    {
        $this->load->model('m_admin');
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'lokasi_rak_bin');
    }

    public function warehousePadaDealer(){
        return $this->db->where("id_dealer =", $this->dealer->getCurrentUserDealer()->id_dealer)
            ->like('id_gudang', $this->input->get('query'))
            ->get($this->table)
            ->result();
    }

    public function generateIdGudang()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        
        $get_data = $this->db
        ->from($this->table)
        ->where('id_dealer', $this->m_admin->cari_dealer())
        ->order_by('created_at', 'desc')
        ->limit(1)
        ->get();
            
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_gudang = substr($row->id_gudang, -3);
            $new_kode   = $dealer->kode_dealer_md.'/WHS-'.sprintf("%'.03d", $id_gudang+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/WHS-'.'001';
        }
        return strtoupper($new_kode);
    }
}
