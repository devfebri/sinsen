<!-- Modal -->
<div id="h3_md_lokasi_rak_temporary_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Lokasi Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_lokasi_rak_temporary_penerimaan_barang_datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Lokasi Rak</th>
                            <th>Deskripsi Rak</th>
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
                        h3_md_lokasi_rak_temporary_penerimaan_barang_datatable = $('#h3_md_lokasi_rak_temporary_penerimaan_barang_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: '<?= base_url('api/md/h3/lokasi_rak_temporary_penerimaan_barang') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d){
                                    packing_sheet_quantity = _.get(form_.parts, '['+ form_.index_part +'].packing_sheet_quantity', 0);
                                    kapasitas_tersedia = _.get(form_.parts, '['+ form_.index_part +'].kapasitas_tersedia', 0);

                                    d.kapasitas_yang_diperlukan = parseInt(packing_sheet_quantity) - parseInt(kapasitas_tersedia);
                                    d.selain_id_lokasi_rak = _.get(form_.parts, '['+ form_.index_part +'].id_lokasi_rak');
                                }
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%'},
                                { data: 'kode_lokasi_rak' },
                                { data: 'deskripsi' },
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

                        h3_md_lokasi_rak_temporary_penerimaan_barang_datatable.on('draw.dt', function() {
                            var info = h3_md_lokasi_rak_temporary_penerimaan_barang_datatable.page.info();
                            h3_md_lokasi_rak_temporary_penerimaan_barang_datatable.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                    });

                    function view_stock_lokasi_temporary(id){
                        $('#id_lokasi_rak_temporary_view_stock').val(id);
                        h3_md_view_stock_lokasi_temporary_penerimaan_barang_datatable.draw();
                        $('#h3_md_lokasi_rak_temporary_penerimaan_barang').modal('hide');   
                        $('#h3_md_view_stock_lokasi_temporary_penerimaan_barang').modal('show');   
                    }
                </script>
            </div>
        </div>
    </div>
</div>
