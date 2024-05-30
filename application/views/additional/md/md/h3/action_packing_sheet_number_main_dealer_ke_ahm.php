<?php
    $disabled = null;

    if($umur_packing_sheet > 6){
        $disabled = 'disabled';
    }
?>
<button <?= $disabled ?> onclick='return pilih_packing_sheet_main_dealer_ke_ahm(<?= $data ?>)' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></button>