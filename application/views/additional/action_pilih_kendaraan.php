<?php
    $pilihKendaraan = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($id_tipe_kendaraan, $this->input->post('filters'))){
            $pilihKendaraan = 'checked';
        }
    }
?>
<input <?= $pilihKendaraan ?> type="checkbox" data-id_tipe_kendaraan='<?= $id_tipe_kendaraan ?>'>