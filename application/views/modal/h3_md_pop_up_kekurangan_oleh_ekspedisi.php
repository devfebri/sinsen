<!-- Modal -->
<div id="h3_md_pop_up_kekurangan_oleh_ekspedisi" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Barang Kurang - Kekurangan oleh Ekspedisi</h4>
            </div>
            <div class="modal-body">
                <h4 class='text-center'>Kekurangan Ekspedisi karena:</h4>

                <div class="row">
                    <div class="col-sm-6">
                        <button class="btn btn-flat btn-primary" @click.prevent='update_alasan_barang_kurang("Masih di Ekspedisi")'>Masih di Ekspedisi</button>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button class="btn btn-flat btn-success" @click.prevent='update_alasan_barang_kurang("Kehilangan oleh Ekspedisi")'>Kehilangan oleh Ekspedisi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>