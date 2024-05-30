<?php

class h3_md_ptm_model extends Honda_Model{

    protected $table = 'ms_ptm';

    public function validate_terakhir_efektif($terakhir_efektif){
        if($terakhir_efektif == '') return true;

        if (!preg_match("/^[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/", $terakhir_efektif)) {
            $this->form_validation->set_message('validate_terakhir_efektif_callable', 'Terakhir efektif tidak sesuai format.');
            return false;
        }
        return true;
    }

}
