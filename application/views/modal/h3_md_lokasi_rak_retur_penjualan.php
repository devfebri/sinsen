<!-- Modal -->
<div id="h3_md_lokasi_rak_retur_penjualan" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Lokasi Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_lokasi_rak_retur_penjualan_datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Lokasi Rak</th>
                            <th>Gudang</th>
                            <th>Kapasitas</th>
                            <th>Kapasitas Terpakai</th>
                            <th>Kapasitas Tersedia</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        h3_md_lokasi_rak_retur_penjualan_datatable = $('#h3_md_lokasi_rak_retur_penjualan_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: '<?= base_url('api/md/h3/lokasi_rak_retur_penjualan') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d){
                                    d.qty_faktur = _.get(app.parts, '[' + app.index_part + '].qty_faktur');
                                    d.id_part = _.get(app.parts, '[' + app.index_part + '].id_part');
                                }
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%'},
                                { data: 'kode_lokasi_rak' },
                                { data: 'kode_gudang' },
                                { 
                                    data: 'kapasitas',
                                    render: function(data){
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    }
                                },
                                { 
                                    data: 'kapasitas_terpakai',
                                    render: function(data){
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    }
                                },
                                { 
                                    data: 'kapasitas_tersedia',
                                    render: function(data){
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    }
                                },
                                { data: 'view_stock', orderable: false, width: '3%', className: 'text-center' },
                                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                            ],
                        });

                        h3_md_lokasi_rak_retur_penjualan_datatable.on('draw.dt', function() {
                            var info = h3_md_lokasi_rak_retur_penjualan_datatable.page.info();
                            h3_md_lokasi_rak_retur_penjualan_datatable.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                    });

                    function view_stock_lokasi(id){
                        $('#id_lokasi_rak_view_stock').val(id);
                        h3_md_view_stock_lokasi_retur_penjualan_datatable.draw();
                        $('#h3_md_lokasi_rak_retur_penjualan').modal('hide');   
                        $('#h3_md_view_stock_lokasi_retur_penjualan').modal('show');   
                    }
                </script>
            </div>
        </div>
    </div>
</div>
