<!-- Modal -->
<div id="h3_md_pop_up_alasan_barang_kurang" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Barang Kurang - Close</h4>
            </div>
            <div class="modal-body">
                <h4 class='text-center'>Kekurangan barang karena:</h4>

                <div class="row">
                    <div class="col-sm-6">
                        <button class="btn btn-flat btn-primary" @click.prevent='update_alasan_barang_kurang("AHM Belum Kirim")'>AHM Belum Kirim</button>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button class="btn btn-flat btn-success" type='button' data-toggle='modal' data-target='#h3_md_pop_up_kekurangan_oleh_ekspedisi'>Kekurangan Oleh Ekspedisi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>