<!-- Modal -->
<div id="h3_md_lokasi_awal_mutasi_gudang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Lokasi Awal</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_lokasi_awal_mutasi_gudang_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Lokasi Rak</th>
                            <th>Deskripsi</th>
                            <th>Kapasitas</th>
                            <th>Kapasitas Tersedia</th>
                            <th>Qty On Hand</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_lokasi_awal_mutasi_gudang_datatable = $('#h3_md_lokasi_awal_mutasi_gudang_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/lokasi_asal_mutasi_gudang') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_gudang_awal = app.mutasi.id_gudang_awal;
                                d.id_part = app.mutasi.id_part;
                            }
                        },
                        columns: [
                            { data: 'kode_lokasi_rak' },
                            { data: 'deskripsi' },
                            { data: 'kapasitas' },
                            { data: 'kapasitas_tersedia' },
                            { data: 'stock' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>