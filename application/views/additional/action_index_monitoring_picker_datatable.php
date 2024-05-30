<?php if($status == 'Open' || $status == ''): ?>
<a style='margin-bottom: 10px;' onclick='return confirm("Apakah anda yakin untuk unchecklist picker pada picking list ini?")' href="h3/h3_md_monitoring_picker/lepas_picker?id_picking_list=<?= $id ?>" class="btn btn-xs btn-flat btn-warning">Ganti Picker</a>
<?php endif; ?>

<?php 
    $state_proses_scan = '';

    if($status != 'Closed PL' || $ready_for_scan == 1){
        $state_proses_scan = 'disabled';
    }
?>
 <a href="h3/h3_md_monitoring_picker/ready_for_scan?id_picking_list=<?= $id ?>">
    <button <?= $state_proses_scan ?> onclick='return confirm("Apakah anda yakin untuk memulai scan picking list?")' class="btn btn-flat btn-xs btn-success">Proses Scan</button>
 </a>