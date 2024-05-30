<?php
    $disabled = '';

    if($selected == 1){
        $disabled = 'disabled';
    }
?>
<button <?= $disabled ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_dealer_ekspedisi(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>