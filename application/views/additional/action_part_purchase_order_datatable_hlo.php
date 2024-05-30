<?php
    $disabled = '';
    // $icon = '';

    $query= $this->db->query("SELECT hoo_flag from ms_part where id_part='$id_part'")->row_array();
    if($query['hoo_flag']=='N'){
        $disabled = 'disabled';
    }elseif($this->input->post('id_part') != null && in_array($id_part, $this->input->post('id_part'))){
        $disabled = 'disabled';
    }
?>

<button <?=   $disabled ?> onClick='return pilihPart(<?= $data ?>)' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></i></button>