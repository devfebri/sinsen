<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_do_sales_order, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-do-sales-order='<?= $id_do_sales_order ?>'>