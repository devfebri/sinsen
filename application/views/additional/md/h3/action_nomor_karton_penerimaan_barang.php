<?php
    $state = '';
    $disabled = '';

    // $list_nomor_karton_int = $this->input->post('list_nomor_karton_int');
    // if(count($list_nomor_karton_int) > 0){
    //     if(in_array($nomor_karton_int, $list_nomor_karton_int)){
    //         $state = 'checked';
    //     }
    // }

    $list_nomor_karton = $this->input->post('list_nomor_karton');
    if(count($list_nomor_karton) > 0){
        if(in_array($nomor_karton, $list_nomor_karton)){
            $state = 'checked';
        }
    }

    if($status == 1){
        $disabled = 'disabled';
    }
?>
<input <?= $disabled ?> <?= $state ?> type="checkbox" class='checkbox-nomor-karton' data-nomor-karton-int='<?= $nomor_karton_int ?>' data-nomor-karton='<?= $nomor_karton ?>' data-packing-sheet-number='<?= $packing_sheet_number ?>' data-packing-sheet-number-int='<?= $packing_sheet_number_int ?>' data-surat-jalan-ahm='<?= $surat_jalan_ahm ?>' data-surat-jalan-ahm-int='<?= $surat_jalan_ahm_int ?>' data-jenis-penerimaan='non_ev'>