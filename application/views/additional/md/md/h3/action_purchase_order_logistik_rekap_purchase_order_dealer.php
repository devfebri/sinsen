<?php 
    $state = '';

    if($this->input->post('items') != null and in_array($po_id, $this->input->post('items'))){
        $state = 'disabled';
    }
?>
<button <?= $state ?> class="btn btn-xs btn-flat btn-success" type='button' onclick='return pilih_purchase_order_logistik_rekap_purchase_order_dealer(<?= $data ?>)' data-dismiss='modal'><i class="fa fa-check"></i></button>