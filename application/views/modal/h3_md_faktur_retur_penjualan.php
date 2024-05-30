<!-- Modal -->
<div id="h3_md_faktur_retur_penjualan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Faktur</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_faktur_retur_penjualan_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No Faktur</th>
                            <th>Tgl Faktur</th>
                            <th>No Packing Sheet</th>
                            <th>Tgl Packing Sheet</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_faktur_retur_penjualan_datatable = $('#h3_md_faktur_retur_penjualan_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/faktur_retur_penjualan') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_dealer = app.retur_penjualan.id_dealer;
                            }
                        },
                        columns: [
                            { data: 'no_faktur' },
                            { data: 'tgl_faktur' },
                            { data: 'id_packing_sheet' },
                            { data: 'tgl_packing_sheet' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>