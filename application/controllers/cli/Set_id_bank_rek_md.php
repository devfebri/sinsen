<?php

class Set_id_bank_rek_md extends Honda_Controller {

    public function index(){
        $this->db
        ->select('rek.id_rek_md')
        ->select('b.id_bank')
        ->from('ms_rek_md as rek')
        ->join('ms_bank as b', 'b.bank = rek.bank')
        ;

        foreach($this->db->get()->result_array() as $data){
            $this->db
            ->set('id_bank', $data['id_bank'])
            ->where('id_rek_md', $data['id_rek_md'])
            ->update('ms_rek_md');
        }
    }
}