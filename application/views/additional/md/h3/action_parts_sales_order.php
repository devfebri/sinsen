<!-- <button <?=  ( $this->input->post('id_part') != null && in_array($id_part, $this->input->post('id_part')) ) ? 'disabled' : '' ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_parts_sales_order(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button> -->


<?php
    $disabled = '';
    
    $selected_parts = $this->input->post('selected_parts');
    if($selected_parts != null and count($selected_parts) > 0){
        foreach ($selected_parts as $part) {
            if(
                ($part['id_part'] == $id_part)
            ){
                $disabled = 'disabled';
                break;
            }
        }
    }
?>
<button <?= $disabled ?> data-dismiss='modal' onClick='return pilih_parts_sales_order(<?= $data ?>)' class="btn btn-flat btn-success btn-xs"><i class="fa fa-check"></i></button>