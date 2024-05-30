<?php
    $disabled = '';
    
    $selected_parts = $this->input->post('selected_parts');
    if($selected_parts != null and count($selected_parts) > 0){
        foreach ($selected_parts as $part) {
            if(
                ($part['id_part'] == $id_part) &&
                ($part['id_rak'] == $id_rak)
            ){
                $disabled = 'disabled';
                break;
            }
        }
    }

    if($stock < 1){
        $disabled = 'disabled';
    }
?>
<button <?= $disabled ?> data-dismiss='modal' onClick='return pilihPart(<?= $data ?>)' class="btn btn-flat btn-success btn-xs"><i class="fa fa-check"></i></button>