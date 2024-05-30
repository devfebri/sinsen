<?php
    $disabled = '';
    if(count($this->input->post('selected_id_part')) > 0){
        if($sisa_boleh_diclaim == 0){
            $disabled = 'disabled';
        }

        if(in_array($id_part, $this->input->post('selected_id_part'))){
            $disabled = 'disabled';
        }
    }
?>
<button <?= $disabled ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_parts_claim_dealer(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>