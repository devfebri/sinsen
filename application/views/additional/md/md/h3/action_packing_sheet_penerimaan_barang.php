<?php
    $state = '';

    if(count($this->input->post('list_packing_sheet_number')) > 0){
        if(in_array($packing_sheet_number_int, $this->input->post('list_packing_sheet_number'))){
            $state = 'checked';
        }
    }
?>
<input <?= $state ?> type="checkbox" class='checkbox-packing-sheet-number' data-packing-sheet-number-int='<?= $packing_sheet_number_int ?>' data-packing-sheet-number='<?= $packing_sheet_number ?>' data-surat-jalan-ahm='<?= $surat_jalan_ahm ?>'  data-surat-jalan-ahm-int='<?= $surat_jalan_ahm_int ?>'>