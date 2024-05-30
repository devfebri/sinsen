<?php
    $state = '';

    if(count($this->input->post('referensi_terpakai')) > 0){
        if(in_array($referensi, $this->input->post('referensi_terpakai'))){
            $state = 'disabled';
        }
    }
?>
<button <?= $state ?> onclick='return pilih_referensi_po_hotline(<?= $data ?>)' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></button>