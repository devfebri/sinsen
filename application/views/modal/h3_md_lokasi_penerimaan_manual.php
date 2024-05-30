<!-- Modal -->
<div id="h3_md_lokasi_penerimaan_manual" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Lokasi Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_lokasi_penerimaan_manual_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Lokasi Rak</th>
                            <th>Deskripsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_lokasi_penerimaan_manual_datatable = $('#h3_md_lokasi_penerimaan_manual_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/lokasi_rak_penerimaan_manual') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_gudang = app.penerimaan_manual.id_gudang;
                            }
                        },
                        columns: [
                            { data: 'kode_lokasi_rak' },
                            { data: 'deskripsi' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>