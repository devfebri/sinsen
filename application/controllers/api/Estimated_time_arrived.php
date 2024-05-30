<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimated_time_arrived extends CI_Controller
{
    public function index(){
        // TODO: Belum dengan claim eta.
        $this->db
        ->select('mp.id_part')
        ->select('2 as eta_tercepat')
        ->select("
            case 
                when (mp.import_lokal = 'N' AND mp.current = 'C') then 3 + 2
                when (mp.import_lokal = 'N' AND mp.current = 'N') then 3 + 4
                when (mp.import_lokal = 'Y' AND mp.current = 'C') then 3 + 22
                when (mp.import_lokal = 'Y' AND mp.current = 'N') then 3 + 44
            end as eta_terlama
        ")
        ->from('ms_part as mp')
        ->where_in('mp.id_part', $this->input->post('id_part'));

        send_json($this->db->get()->result());
    }
}
