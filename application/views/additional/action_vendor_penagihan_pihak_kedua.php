<?php

    $disabled = '';

    if(count($this->input->post('id_vendor')) > 0 AND in_array($id_vendor, $this->input->post('id_vendor'))){
        $disabled = 'disabled';
    }

?>
<button <?= $disabled ?> onclick='return pilih_vendor(<?= json_encode($row) ?>);' type='button' class="btn btn-flat btn-xs btn-success" data-dismiss='modal'><i class="fa fa-check" aria-hidden="true"></i></button>