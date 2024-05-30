<?php
    $disabled = '';
    if ($this->input->post('selected_id_part') != null and count($this->input->post('selected_id_part')) > 0) {
        if(in_array($id_part, $this->input->post('selected_id_part'))){
            $disabled = 'disabled';
        }
    }
?>
<button <?= $disabled ?> onclick='return pilih_parts_purchase_reguler_and_fix(<?= $data ?>)' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></button>