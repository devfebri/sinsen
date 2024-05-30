<?php 
 $checked = '';

 if(count($plafon_id) > 0 AND in_array($id, $plafon_id)){
     $checked = 'checked';
 }
?>

<input <?= $checked ?> type="checkbox" data-id='<?= $id ?>' class='plafon-checkbox'>