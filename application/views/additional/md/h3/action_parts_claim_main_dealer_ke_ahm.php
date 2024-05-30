<?php 
    $state = '';

    // if(count($this->input->post('selected_id_parts')) > 0){
    //     foreach($this->input->post('selected_id_parts') as $selected_part){
    //         if(
    //             ($id_part == $selected_part['id_part']) &&
    //             ($no_doos == $selected_part['no_doos'])
    //         ){
    //             $state = 'disabled';
    //             break;
    //         }
    //     }
    // }
?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_parts_claim_main_dealer_ke_ahm(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>