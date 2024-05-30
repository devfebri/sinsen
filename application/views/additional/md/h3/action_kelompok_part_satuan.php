<?php
    $state = '';

    if(count($kelompok_part_yang_sudah_terpakai) > 0){
        if(in_array($id_kelompok_part, $kelompok_part_yang_sudah_terpakai)){
            $state = 'disabled';
        }
    }
?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_kelompok_part_satuan(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>