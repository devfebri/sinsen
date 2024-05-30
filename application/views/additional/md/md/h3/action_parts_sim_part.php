<?php
    $state = '';

    if(count($this->input->post('selected_id_part')) > 0){
        if(in_array($id_part, $this->input->post('selected_id_part'))){
            $state = 'disabled';
        }
    }
?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_parts_sim_part(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>