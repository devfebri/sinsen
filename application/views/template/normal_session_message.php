<?php
    if(!isset($pesan_key)){
        $pesan_key = 'pesan';
    }

    if(!isset($tipe_key)){
        $tipe_key = 'tipe';
    }
?>

<?php if($this->session->userdata($pesan_key) != null): ?>               
<div class="alert alert-<?= $this->session->userdata($tipe_key) ?> alert-dismissable">
    <strong><?= $this->session->userdata($pesan_key) ?></strong>
    <button class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>  
    </button>
</div>
<?php 
    $this->session->unset_userdata($pesan_key);
    $this->session->unset_userdata($tipe_key);
?>
<?php endif; ?>