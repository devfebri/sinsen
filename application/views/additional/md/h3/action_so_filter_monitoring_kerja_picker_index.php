<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_sales_order, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-sales-order='<?= $id_sales_order ?>'>