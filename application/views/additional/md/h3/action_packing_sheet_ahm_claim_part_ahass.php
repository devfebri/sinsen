<?php
    $disabled = '';

    if($this->input->post('selected_packing_sheet_number') != null){
        if($this->input->post('selected_packing_sheet_number') == $packing_sheet_number){
            $disabled = 'disabled';
        }
    }
?>
<button <?= $disabled ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_packing_sheet_ahm_claim_part_ahass(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>