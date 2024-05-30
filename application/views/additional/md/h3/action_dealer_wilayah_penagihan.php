<?php 
    $selected_dealer = $this->db
    ->select('wpi.id_dealer')
    ->from('ms_h3_md_wilayah_penagihan_item as wpi')
    ->get()->result_array();

    $selected_dealer = array_map(function($data){
        return $data['id_dealer'];
    }, $selected_dealer);

    if (count($this->input->post('selected_id_dealer')) > 0) {
        $selected_dealer = array_merge($selected_dealer, $this->input->post('selected_id_dealer'));
    }
?>
<button <?= (count($selected_dealer) > 0 and in_array($id_dealer, $selected_dealer)) ? 'disabled' : '' ?> onclick='return pilih_dealer_wilayah_penagihan(<?= $data ?>)' class="btn btn-flat btn-xs btn-success" data-dismiss='modal' type='button'><i class="fa fa-check"></i></button>