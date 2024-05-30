<?php 
    $disabled = '';
    if(count($this->input->post('selected_parts')) > 0){
        foreach ($this->input->post('selected_parts') as $selected_part) {
            if(
                $selected_part['id_part'] == $id_part and
                $selected_part['id_gudang'] == $id_gudang and
                $selected_part['id_rak'] == $id_rak
            ){
                $disabled = 'disabled';
                break;
            }
        }
    }
?>

<button <?= $disabled ?> onclick='return pilih_parts_event(<?= $data ?>)' class="btn btn-flat btn-xs btn-success" data-dismiss='modal' type='button'><i class="fa fa-check"></i></button>