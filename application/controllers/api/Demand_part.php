<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Demand_part extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_admin');
    }

    public function index()
    {
        $query = $this->db
        ->select('IFNULL( SUM(qty), 0 ) as frekuensi')
        ->select(" CONCAT('Rp ', FORMAT( IFNULL( SUM(d.harga_satuan * d.qty), 0) , 0, 'ID_id') ) as lost")
        ->from('tr_h3_dealer_record_reasons_and_parts_demand as d')
        ->join('ms_part as p', 'p.id_part = d.id_part')
        ->where('d.id_part', $this->input->get('id_part'))
        ->where('d.id_dealer', $this->m_admin->cari_dealer())
        // ->where('ds.freeze', 0)
        ;

        send_json($query->get()->row());
    }
}
