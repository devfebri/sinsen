<?php
    $pilihLastToj = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_type, $this->input->post('filters'))){
            $pilihLastToj = 'checked';
        }
    }
?>
<input <?= $pilihLastToj ?> type="checkbox" data-last_toj='<?= $id_type ?>'>