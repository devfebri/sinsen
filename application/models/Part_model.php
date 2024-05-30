<?php

class part_model extends Honda_Model {

    protected $table = 'ms_part';

    public function exist_by_id_part($id_part){
        $part = $this->find($id_part, 'id_part');

        if($part == null){
            $this->form_validation->set_message('exist_by_id_part_callable', 'Part tidak ditemukan');
        }

        return $part != null;
    }
    
}

?>