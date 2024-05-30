<input type="hidden" id='id_lokasi_rak_view_stock'>
<!-- Modal -->
<div id="h3_md_view_stock_lokasi_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Detail Lokasi Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_view_stock_lokasi_penerimaan_barang_datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Book</th>
                            <th>Real</th>
                            <th>Sisa</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    var h3_md_view_stock_lokasi_penerimaan_barang_datatable;
                    number_render = function(data){
                        return accounting.format(data, 0, ".", ",");
                    }
                    
                    // $(document).ready(function() {
                    //     h3_md_view_stock_lokasi_penerimaan_barang_datatable = $('#h3_md_view_stock_lokasi_penerimaan_barang_datatable').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         order: [],
                    //         ajax: {
                    //             url: '<?= base_url('api/md/h3/view_stock_lokasi_penerimaan_barang') ?>',
                    //             dataSrc: 'data',
                    //             type: 'POST',
                    //             data: function(d){
                    //                 d.id = $('#id_lokasi_rak_view_stock').val();
                    //             }
                    //         },
                    //         columns: [
                    //             { data: 'index', orderable: false, width: '3%'},
                    //             { data: 'id_part' },
                    //             { data: 'nama_part' },
                    //             { 
                    //                 data: 'qty_maks',
                    //                 render: number_render
                    //             },
                    //             { 
                    //                 data: 'qty_onhand',
                    //                 render: number_render,
                    //                 orderable: false,
                    //             },
                    //             { 
                    //                 data: 'sisa',
                    //                 render: number_render,
                    //                 orderable: false,
                    //             },
                    //         ],
                    //     });
                    // });
                    
                    

                    function drawing_view_stock_lokasi_penerimaan(){
                        h3_md_view_stock_lokasi_penerimaan_barang_datatable = $('#h3_md_view_stock_lokasi_penerimaan_barang_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            bDestroy: true, 
                            ajax: {
                                url: '<?= base_url('api/md/h3/view_stock_lokasi_penerimaan_barang') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d){
                                    d.id = $('#id_lokasi_rak_view_stock').val();
                                }
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%'},
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { 
                                    data: 'qty_maks',
                                    render: number_render
                                },
                                { 
                                    data: 'qty_onhand',
                                    render: number_render,
                                    orderable: false,
                                },
                                { 
                                    data: 'sisa',
                                    render: number_render,
                                    orderable: false,
                                },
                            ],
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_view_stock_lokasi_penerimaan_barang').on('hidden.bs.modal', function () {
            $('#h3_md_lokasi_rak_penerimaan_barang').modal('show');   
        });
    });
</script>
