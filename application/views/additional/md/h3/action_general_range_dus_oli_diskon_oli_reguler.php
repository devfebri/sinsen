<?php
    $state = '';

    if(count($this->input->post('selected_range')) > 0){
        if(in_array($id, $this->input->post('selected_range'))){
            $state = 'disabled';
        }
    }
?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_general_range_dus_oli_diskon_oli_reguler(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>