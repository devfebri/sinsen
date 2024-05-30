<?php 
    $disabled = '';

    if($this->input->post('items_selected') != null AND count($this->input->post('items_selected')) > 0){
        foreach($this->input->post('items_selected') as $item){
            if($item['id_referensi'] == $data['id_referensi'] AND $item['jenis_transaksi'] == $data['jenis_transaksi']){
                $disabled = 'disabled';
                break;
            }
        }
    }
?>
<button <?= $disabled ?> onclick='return pilih_transaksi_voucher_pengeluaran(<?= $json ?>)' class="btn btn-flat btn-xs btn-success" data-dismiss='modal' type='button'><i class="fa fa-check"></i></button>