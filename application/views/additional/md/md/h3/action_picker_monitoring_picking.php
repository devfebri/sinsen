<?php
    $state = false;

    if($this->input->post('id_picker') != ''){
        $state = $id_karyawan == $this->input->post('id_picker');
    }
?>
<?php if($state): ?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-danger" type='button' onclick='return pilih_picker_monitoring_picking(<?= $data ?>, "reset")' data-dismiss='modal'><i class="fa fa-trash-o"></i></button>
<?php else: ?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_picker_monitoring_picking(<?= $data ?>, "select")' data-dismiss='modal'><i class="fa fa-check"></i></button>
<?php endif; ?>