<div id="h3_md_lokasi_rak_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width:80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Lokasi Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_lokasi_rak_penerimaan_barang_datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Lokasi Rak</th>
                            <th>Deskripsi Rak</th>
                            <th>Gudang</th>
                            <th>Kapasitas</th>
                            <th>Kapasitas Booking</th>
                            <th>Kapasitas Terpakai</th>
                            <th>Kapasitas Tersedia</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    // $(document).ready(function() {
                    //     digit_render = function(data){
                    //         return accounting.format(data, 0, ".", ",");
                    //     }
                    //     h3_md_lokasi_rak_penerimaan_barang_datatable = $('#h3_md_lokasi_rak_penerimaan_barang_datatable').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         order: [],
                    //         ajax: {
                    //             url: '',
                    //             dataSrc: 'data',
                    //             type: 'POST',
                    //             data: function(d){
                    //                 d.selected_id_part_int = _.get(form_.parts[form_.index_part], 'id_part_int', null);
                    //                 d.selected_id_part = _.get(form_.parts[form_.index_part], 'id_part', null);
                    //                 d.selected_packing_sheet_quantity = _.get(form_.parts[form_.index_part], 'packing_sheet_quantity', null);
                    //             }
                    //         },
                    //         columns: [
                    //             { data: 'index', orderable: false, width: '3%'},
                    //             { data: 'kode_lokasi_rak' },
                    //             { data: 'deskripsi' },
                    //             { data: 'kode_gudang' },
                    //             { 
                    //                 data: 'kapasitas',
                    //                 render: digit_render
                    //             },
                    //             { 
                    //                 data: 'kapasitas_booking',
                    //                 render: digit_render
                    //             },
                    //             { 
                    //                 data: 'kapasitas_terpakai',
                    //                 render: digit_render
                    //             },
                    //             { 
                    //                 data: 'kapasitas_tersedia',
                    //                 render: digit_render
                    //             },
                    //             { data: 'view_stock', orderable: false, width: '3%', className: 'text-center' },
                    //             { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    //         ],
                    //     });
                    // });
                    
                    var h3_md_lokasi_rak_penerimaan_barang_datatable;
                    function drawing_lokasi_rak_penerimaan_barang(){
                             digit_render = function(data){
                            return accounting.format(data, 0, ".", ",");
                        }
                        h3_md_lokasi_rak_penerimaan_barang_datatable = $('#h3_md_lokasi_rak_penerimaan_barang_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            searching: true, 
                            bDestroy: true,
                            order: [],
                            ajax: {
                                url: '<?= base_url('api/md/h3/lokasi_rak_penerimaan_barang') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d){
                                    d.selected_id_part_int = _.get(form_.parts[form_.index_part], 'id_part_int', null);
                                    d.selected_id_part = _.get(form_.parts[form_.index_part], 'id_part', null);
                                    d.selected_packing_sheet_quantity = _.get(form_.parts[form_.index_part], 'packing_sheet_quantity', null);
                                    d.search_lokasi_rak=$('#cari_lokasi_rak').val();
                                    d.search_gudang=$('#cari_gudang').val();
                                }
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%'},
                                { data: 'kode_lokasi_rak' },
                                { data: 'deskripsi' },
                                { data: 'kode_gudang' },
                                { 
                                    data: 'kapasitas',
                                    render: digit_render
                                },
                                { 
                                    data: 'kapasitas_booking',
                                    render: digit_render
                                },
                                { 
                                    data: 'kapasitas_terpakai',
                                    render: digit_render
                                },
                                { 
                                    data: 'kapasitas_tersedia',
                                    render: digit_render
                                },
                                { data: 'view_stock', orderable: false, width: '3%', className: 'text-center' },
                                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                            ],
                        });
                    }

                    function view_stock_lokasi(id){
                        $('#id_lokasi_rak_view_stock').val(id);
                        // h3_md_view_stock_lokasi_penerimaan_barang_datatable.draw();
                        drawing_view_stock_lokasi_penerimaan();
                        $('#h3_md_lokasi_rak_penerimaan_barang').modal('hide');   
                        $('#h3_md_view_stock_lokasi_penerimaan_barang').modal('show');   
                    }
                </script>
            </div>
        </div>
    </div>
</div>
