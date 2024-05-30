<input type="hidden" id='no_bapb_for_open_view_surat_jalan_ahm'>
<div id="h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Surat Jalan AHM</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Surat Jalan AHM</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang_datatable = $('#h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/open_view_surat_jalan_ahm_berita_acara_penerimaan_barang') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.no_bapb = $('#no_bapb_for_open_view_surat_jalan_ahm').val();
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%'},
                        { data: 'surat_jalan_ahm' },
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>