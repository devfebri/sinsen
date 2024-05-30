<?php
    $disabled = '';

    if($this->input->post('id_part') != null and count($this->input->post('id_part')) > 0){
        if(in_array($id_part, $this->input->post('id_part'))){
            $disabled = 'disabled';
        }
    }
?>
<button <?= $disabled ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_faktur_parts_retur_penjualan(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>