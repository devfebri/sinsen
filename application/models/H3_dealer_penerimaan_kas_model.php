<?php

class h3_dealer_penerimaan_kas_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_penerimaan_kas';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function generateID()
    {
        $bulan = date('m');
        $tahun = date('y');

        $get_data  = $this->db
        ->from($this->table)
        ->order_by('id_penerimaan_kas', 'desc')
        ->order_by('created_at', 'desc')
        ->limit(1)
        ->get();

        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_penerimaan_kas = substr($row->id_penerimaan_kas, -5);
            $new_kode   = "MK/{$tahun}/{$bulan}/". sprintf("%'.05d", $id_penerimaan_kas+1);
        } else {
            $new_kode = "MK/{$tahun}/{$bulan}/00001";
        }
        return strtoupper($new_kode);
    }
}
