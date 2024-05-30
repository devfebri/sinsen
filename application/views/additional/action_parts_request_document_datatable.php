<?php
    $disabled = '';

    // if($this->input->post('id_part') != null){
    //     if (in_array($id_part, $this->input->post('id_part'))) {
    //         $disabled = 'disabled';
    //     }
    // }
    $query= $this->db->query("SELECT hoo_flag from ms_part where id_part='$id_part'")->row_array();
    if($query['hoo_flag']=='N' && ($this->input->post('order_to') == '' || $this->input->post('order_to') == 0)){
        $disabled = 'disabled';
    }elseif($this->input->post('id_part') != null){
        if (in_array($id_part, $this->input->post('id_part'))) {
            $disabled = 'disabled';
        }
    }
?>
<button <?=  $disabled ?> onclick='return pilih_parts_request_document(<?= $data ?>)' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></button>