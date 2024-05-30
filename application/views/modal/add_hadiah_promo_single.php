<!-- Modal -->
<div id="add_hadiah_promo_single" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Tambah Hadiah</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-sm-2 no-padding-top">AHHAS Part</label>
                    <div class="col-sm-4">
                        <input v-model='gift_single.part_ahass' true-value='1' false-value='0' type="checkbox">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Nama Hadiah</label>
                    <div class="col-sm-4">
                        <input v-model='gift_single.nama_hadiah' type="text" class="form-control" :readonly='gift_single.part_ahass == 1' data-toggle='modal' :data-target='gift_single.part_ahass == 1 ? "#part_ahass_untuk_promo_single" : ""'>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Qty Hadiah</label>
                    <div class="col-sm-4">
                        <input v-model='gift_single.qty_hadiah' type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-flat btn-sm btn-primary" @click.prevent='tambahkan_hadiah_single'>Tambahkan Hadiah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>