<?php 
    $id_checker_selected = $this->session->userdata('id_checker_selected');

    $checkbox = '';
    if(count($id_checker_selected) > 0){
        if(in_array($id_checker, $id_checker_selected)){
            $checkbox = 'checked';
        }
    }
?>
<input type='checkbox' <?= $checkbox ?> data-id='<?= $id_checker ?>'>