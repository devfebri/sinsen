<?php if($this->session->flashdata('pesan') != null): ?>               
<div class="alert alert-<?= $this->session->flashdata('tipe') ?> alert-dismissable">
    <strong><?= $this->session->flashdata('pesan') ?></strong>
    <button class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>  
    </button>
</div>
<?php endif; ?>