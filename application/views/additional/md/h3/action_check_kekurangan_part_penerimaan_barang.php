<?php
    $state = '';

    if(count($this->input->post('checked')) > 0){
        if(in_array($id, $this->input->post('checked'))){
            $state = 'checked';
        }
    }
?>
<?php if($tersimpan == 1): ?>
<input <?= $state ?> type="checkbox" data-id='<?= $id ?>'>
<?php endif; ?>