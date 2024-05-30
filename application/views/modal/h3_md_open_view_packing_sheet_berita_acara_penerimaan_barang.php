<input type="hidden" id='no_bapb_for_open_view_packing_sheet_number'>
<div id="h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang_datatable" style="width: 100%">
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
                h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang_datatable = $('#h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/open_view_packing_sheet_number_berita_acara_penerimaan_barang') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.no_bapb = $('#no_bapb_for_open_view_packing_sheet_number').val();
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%'},
                        { data: 'packing_sheet_number' },
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>