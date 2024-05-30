<!-- Modal -->
<div id="h3_md_bapb_pelunasan_bapb" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Berita Acara Penerimaan Barang</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_bapb_pelunasan_bapb_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. BAPB</th>
                            <th>No. Surat Jalan Ekspedisi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_bapb_pelunasan_bapb_datatable = $('#h3_md_bapb_pelunasan_bapb_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/bapb_pelunasan_bapb') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%'},
                            { data: 'no_bapb' },
                            { data: 'no_surat_jalan_ekspedisi' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });

                    h3_md_bapb_pelunasan_bapb_datatable.on('draw.dt', function() {
                        var info = h3_md_bapb_pelunasan_bapb_datatable.page.info();
                        h3_md_bapb_pelunasan_bapb_datatable.column(0, {
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