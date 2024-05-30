<!-- Modal -->
<div id="h3_md_faktur_parts_retur_penjualan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_faktur_parts_retur_penjualan_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Qty Faktur</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_faktur_parts_retur_penjualan_datatable = $('#h3_md_faktur_parts_retur_penjualan_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/faktur_parts_retur_penjualan') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.no_faktur = app.retur_penjualan.no_faktur;
                                d.id_part = _.chain(app.parts)
                                .map(function(part){
                                    return part.id_part;
                                })
                                .value();
                            }
                        },
                        columns: [
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'qty_faktur' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>