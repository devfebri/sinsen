<a href="h3/h3_md_validasi_picking_list/open?id_picking_list=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Open</a>
<a href="h3/h3_md_validasi_picking_list/open?id_picking_list=<?= $id ?>&status=start">
    <button <?= $status == 'Closed PL' ? 'disabled' : '' ?> class="btn btn-xs btn-flat btn-success">Start</button>
</a>
<!-- <a href="h3/h3_md_validasi_picking_list/cetak_picking_list?id_picking_list=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Cetak</a> -->

<?php if($cetakan_ke >= 1 ){?>
    <button  type="button" class="btn btn-xs btn-flat btn-info"  data-toggle="modal" data-target="#passwordModal">Cetak</button>
<?php }else{?>
    <a href="h3/h3_md_validasi_picking_list/cetak_picking_list?id_picking_list=<?= $id ?>" class="btn btn-xs btn-flat btn-info">Cetak</a>
<?php }?>

<div id="passwordModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" class="form-control" id="id" placeholder="id" value="<?= $id ?>">  
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-input">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="passwordInput" placeholder="Password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitPassword">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#submitPassword').unbind().click(function() {
        var password = $('#passwordInput').val();
        var id = $('#id').val();
        $.ajax({
            url: 'h3/h3_md_validasi_picking_list/verifyPassword',
            type: 'POST',
            data: {
                password: password,
                id: id
            },
            success: function(response) {
                if (response === 'success') {
                    url='/h3/h3_md_validasi_picking_list/cetak_picking_list?id_picking_list='+id;  
                    window.open(location.origin+url,'_blank');

                } else {
                    alert('Password salah!');
                }
            }
        });
    });
});
</script>