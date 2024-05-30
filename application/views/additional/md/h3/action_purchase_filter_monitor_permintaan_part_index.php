<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($po_id, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-po-id='<?= $po_id ?>'>