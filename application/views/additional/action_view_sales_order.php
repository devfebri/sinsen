<?php 
if($row['tipe_referensi'] == 'Sales Order'){ ?>
    <a href="<?= base_url("dealer/h3_dealer_sales_order/detail?k={$row['referensi']}") ?>" target="_blank" class="btn btn-xs btn-flat btn-info">View</a>
<?php }elseif($row['tipe_referensi'] == 'Purchase Order Hotline'){ ?>
     <a href="<?= base_url("dealer/h3_dealer_purchase_order/detail?id={$row['referensi']}") ?>" target="_blank" class="btn btn-xs btn-flat btn-info">View</a>
<?php }elseif($row['tipe_referensi'] == 'Outbound Fulfillment'){ ?>
     <a href="<?= base_url("dealer/h3_dealer_outbound_form_for_fulfillment/detail?k={$row['referensi']}") ?>" target="_blank" class="btn btn-xs btn-flat btn-info">View</a>
 <?php } ?>