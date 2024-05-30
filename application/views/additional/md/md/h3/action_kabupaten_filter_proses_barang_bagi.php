<?php
    $state = '';

    if(count($this->input->post('filter_kabupaten')) > 0){
        if(in_array($id_kabupaten, $this->input->post('filter_kabupaten'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-kabupaten='<?= $id_kabupaten ?>'>