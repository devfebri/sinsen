<?php
    $state = '';

    if(count($this->input->post('list_part_number')) > 0){
        if(in_array($id_part_int, $this->input->post('list_part_number'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" class='checkbox-part-number' data-part-number='<?= $id_part ?>' data-part-number-int='<?= $id_part_int ?>'>