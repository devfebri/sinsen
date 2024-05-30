<?php 
    $state = '';

    if($this->input->post('items') != null and in_array($no_po_aksesoris, $this->input->post('items'))){
        $state = 'disabled';
    }
?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_purchase_order_bundling_rekap_po_bundling(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>