<?php
    $state = '';

    if(count($this->input->post('filters_lokasi')) > 0){
        if(in_array($id, $this->input->post('filters_lokasi'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id='<?= $id ?>'>