<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($kelompok_part, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" class='checkbox-item' data-kelompok-part='<?= $kelompok_part ?>'>