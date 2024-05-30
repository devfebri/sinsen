<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_kabupaten, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-kabupaten='<?= $id_kabupaten ?>'>