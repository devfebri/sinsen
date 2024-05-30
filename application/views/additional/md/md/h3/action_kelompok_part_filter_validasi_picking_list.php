<?php
    $state = '';

    if(count($this->input->post('filters_kelompok_part')) > 0){
        if(in_array($id_kelompok_part, $this->input->post('filters_kelompok_part'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-kelompok-part='<?= $id_kelompok_part ?>'>