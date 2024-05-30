<!-- Modal -->
<div id="h3_gudang_lokasi_rak" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Part</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_gudang_lokasi_rak_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Gudang</th>
                        <th>Nama Gudang</th>
                        <th>Jenis Gudang</th>
                        <th>Luas Gudang</th>
                        <th>Jumlah Rak</th>
                        <th>Jumlah Binbox</th>
                        <th>Jumlah Pallet</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_gudang_lokasi_rak_datatable = $('#h3_gudang_lokasi_rak_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/gudang_lokasi_rak') ?>',
                        dataSrc: 'data',
                        type: 'POST'
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'kode_gudang' },
                        { data: 'nama_gudang' },
                        { data: 'jenis_gudang' },
                        { data: 'luas_gudang' },
                        { data: 'jumlah_rak' },
                        { data: 'jumlah_binbox' },
                        { data: 'jumlah_pallet' },
                        { data: 'action', orderable: false, widht: '3%', className: 'text-center' }
                    ],
                });

                h3_gudang_lokasi_rak_datatable.on('draw.dt', function() {
                    var info = h3_gudang_lokasi_rak_datatable.page.info();
                    h3_gudang_lokasi_rak_datatable.column(0, {
                        search: 'applied',
                        order: 'applied',
                        page: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + info.start + ".";
                    });
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>