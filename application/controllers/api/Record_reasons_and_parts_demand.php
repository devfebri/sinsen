<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Record_reasons_and_parts_demand extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('h3_dealer_record_reasons_and_parts_demand_model', 'reason');
    }

    public function insert(){
        $this->db->trans_start();
        
        $data = $this->input->post();
        $this->reason->insert($data);

        $this->db->trans_complete();
        
        if($this->db->trans_status()){
            $this->output->set_status_header(200);
        }else{
            $this->output->set_status_header(500);
        }
    }

}
