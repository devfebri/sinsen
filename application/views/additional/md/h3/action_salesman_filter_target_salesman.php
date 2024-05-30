<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_karyawan, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-id-karyawan='<?= $id_karyawan ?>'>