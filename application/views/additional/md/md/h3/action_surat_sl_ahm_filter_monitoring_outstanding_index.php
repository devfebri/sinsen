<?php
    $state = '';

    if(count($this->input->post('filters')) > 0){
        if(in_array($surat_jalan_ahm, $this->input->post('filters'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" data-surat-jalan-ahm='<?= $surat_jalan_ahm ?>'>