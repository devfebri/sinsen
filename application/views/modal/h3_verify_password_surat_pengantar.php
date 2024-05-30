<div id="passwordModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
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
                <button type="button" class="btn btn-primary" id="submitPassword" @click.prevent='verify_password'>Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- <script>
$(document).ready(function() {
    $('#submitPassword').unbind().click(function() {
        var password = $('#passwordInput').val();
        var id = $('#id').val();
        $.ajax({
            url: 'h3/h3_md_surat_pengantar/verifyPassword',
            type: 'POST',
            data: {
                password: password,
                id: id
            },
            success: function(response) {
                if (response === 'success') {
                    url='/h3/h3_md_surat_pengantar/cetak?id_surat_pengantar='+id;  
                    window.open(location.origin+url,'_blank');

                } else {
                    alert('Password salah!');
                }
            }
        });
    });
});
</script> -->