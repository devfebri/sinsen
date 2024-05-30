<?php
    $state = '';

    if(count($this->input->post('list_surat_jalan_ahm')) > 0){
        if(in_array($surat_jalan_ahm_int, $this->input->post('list_surat_jalan_ahm'))){
            $state = 'checked';
        }
    }

    $disabled = '';
    if($this->input->post('mode') == 'detail' || $check_state == 1){
        $disabled = 'disabled';
    }
?>
<input <?= $state ?> <?= $disabled ?> type="checkbox" class='checkbox-surat-jalan-ahm' data-surat-jalan-ahm='<?= $surat_jalan_ahm ?>' data-surat-jalan-ahm-int='<?= $surat_jalan_ahm_int ?>'>