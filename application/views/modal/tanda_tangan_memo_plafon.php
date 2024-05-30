<!-- Modal -->
<div id="tanda_tangan_memo_plafon" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Tanda Tangan Memo</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id='filetype_memo'>
                <div class="form-group">
                    <label for="" class="control-label">Admin</label>
                    <input type="text" class="form-control" id='admin'>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Marketing</label>
                    <input type="text" class="form-control" id='marketing'>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Part Manager</label>
                    <input type="text" class="form-control" id='part_manager'>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Finance Head</label>
                    <input type="text" class="form-control" id='finance_head'>
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Pimpinan</label>
                    <input type="text" class="form-control" id='pimpinan'>
                </div>

                <a id='tombol_cetak' target='_blank' class="btn btn-sm btn-flat btn-success">Cetak</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#tanda_tangan_memo_plafon').on('keyup', 'input', function(e){
            query_string = new URLSearchParams({
                filetype : $('#filetype_memo').val(),
                admin : $('#admin').val(),
                marketing : $('#marketing').val(),
                part_manager : $('#part_manager').val(),
                finance_head : $('#finance_head').val(),
                pimpinan : $('#pimpinan').val(),
            }).toString();

            console.log(query_string);

            $('#tombol_cetak').attr('href', 'h3/<?= $isi ?>/memo?' + query_string);
        });
    });
</script>