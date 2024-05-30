<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nrfs_part extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        
        $this->db
        ->select('np.*')
        ->select("0 as diskon_value")
        ->select("'' as tipe_diskon")
        ->select("0 as diskon_value_campaign")
        ->select("'' as tipe_diskon_campaign")
        ->select('np.qty_part as kuantitas')
        ->select('p.nama_part')
        ->select('p.harga_md_dealer as harga_saat_dibeli')
        ->from('tr_dokumen_nrfs_part as np')
        ->join('ms_part as p', 'p.id_part = np.id_part')
        ;

        $data = $this->db->get()->result();

        send_json($data);
    }
}
