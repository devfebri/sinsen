<?php
    $state = '';

    if(count($this->input->post('filters_part')) > 0){
        if(in_array($id_part, $this->input->post('filters_part'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-part='<?= $id_part ?>'>