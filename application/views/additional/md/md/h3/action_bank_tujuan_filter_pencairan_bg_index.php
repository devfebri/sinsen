<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_rek_md, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-rek-md='<?= $id_rek_md ?>'>