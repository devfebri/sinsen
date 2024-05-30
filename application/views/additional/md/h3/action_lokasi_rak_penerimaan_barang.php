<?php 
    $disabled = null;

    if($row['part_sudah_disetting_rak'] AND $row['kapasitas_tersedia_berdasarkan_setting_kode_part_pada_lokasi_rak'] < $packing_sheet_quantity){
        $disabled = 'disabled';
    }
?>
<button <?= $disabled ?> onclick='return pilih_lokasi_rak_penerimaan_barang(<?= $data ?>)' data-dismiss='modal' class="btn btn-xs btn-flat btn-success" type="button"><i class="fa fa-check"></button>