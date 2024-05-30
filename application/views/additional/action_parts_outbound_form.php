<?php 
    if($qty_avs <= 0 ){
         $disabled = "disabled";
    }else{
         $disabled ='';
    }
?>

<button <?= $disabled ?> onclick='return parts_outbound_form(<?= $data ?>)' class="btn btn-flat btn-xs btn-success" type='button' data-dismiss='modal'><i class="fa fa-check"></i></button>