<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_purchase_order, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-purchase-order='<?= $id_purchase_order ?>'>