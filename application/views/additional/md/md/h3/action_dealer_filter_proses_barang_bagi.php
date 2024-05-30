<?php
    $state = '';

    if(count($this->input->post('filter_dealers')) > 0){
        if(in_array($id_dealer, $this->input->post('filter_dealers'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-dealer='<?= $id_dealer ?>'>