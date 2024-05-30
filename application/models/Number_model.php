<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Number_model extends CI_Model {

    public function generateKodeJasa()
    {   
        $get_data = $this->db
        ->from('ms_h2_jasa')
        ->limit(1)
        ->order_by('id_jasa_int', 'desc')
        ->get();
            
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_jasa= substr($row->id_jasa, -5);
            $new_kode = "JS" . sprintf("%'.05d", $id_jasa+1);
        } else {
            $new_kode   = "JS00001";
        }
        return strtoupper($new_kode);
    }

}

/* End of file Number.php */

?>