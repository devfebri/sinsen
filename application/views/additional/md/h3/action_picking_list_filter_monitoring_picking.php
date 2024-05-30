<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_picking_list, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-picking-list='<?= $id_picking_list ?>'>