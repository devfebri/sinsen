<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_kelompok_part, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-kelompok-part='<?= $id_kelompok_part ?>'>