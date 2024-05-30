<?php

$disabled = '';
if ($this->input->post('selected_parts') != null and count($this->input->post('selected_parts')) > 0) {
    foreach ($this->input->post('selected_parts') as $selected_part) {
        $partSelected = ($id_claim == $selected_part['id_claim']) and
            ($id_part == $selected_part['id_part']) and
            ($no_doos == $selected_part['no_doos']) and
            ($no_po == $selected_part['no_po']) and
            ($id_kode_claim == $selected_part['id_kode_claim']);
        if (
            $partSelected
        ) {
            $disabled = 'disabled';
            break;
        }
    }
}

if ($invoice_tidak_ditemukan) {
    $disabled = 'disabled';
}

?>
<button <?= $disabled ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_parts_terima_claim_ahm(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>