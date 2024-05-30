<!-- Modal -->
<div id="parts_sales_order" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <!-- <div class="container-fluid no-padding">
                    <div class="row">
                        <div class="col-sm-offset-8 col-sm-4 no-padding">
                            <label for="" class="control-label col-sm-4">Search</label>
                            <div class="col-sm-8">
                                <input id='search_filter' type="text" class="form-control" placeholder='Search'>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_kode" id="cari_kode" placeholder="Masukkan Kode Part"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_nama_part" id="cari_nama_part" placeholder="Masukkan Nama Part"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_nama_part_bahasa" id="cari_nama_part_bahasa" placeholder="Masukkan Nama Part Bahasa Indonesia"/>
                            </div>
                            <div class="col-sm-2">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari3"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="parts_sales_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Nama Part Bahasa</th>
                            <th>HET</th>
                            <th>Gudang</th>
                            <th>Rak</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    // $(document).ready(function() {
                    //     parts_sales_order_datatable = $('#parts_sales_order_datatable').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         order: [],
                    //         ordering: false,
                    //         searching: false,
                    //         ajax: {
                    //             url: "<?=base_url('api/actualStock') ?>",
                    //             dataSrc: "data",
                    //             data: function(d){
                    //                 d.search.value = $('#search_filter').val();
                    //                 d.selected_parts = _.chain(form_.parts)
                    //                 .map(function(part){
                    //                     return _.pick(part, ['id_part', 'id_rak']);
                    //                 })
                    //                 .value();
                    //             },
                    //             type: "POST"
                    //         },
                    //         columns: [
                    //             { data: 'index', width: '3%', orderable: false },
                    //             { data: 'id_part' },
                    //             { data: 'nama_part' },
                    //             { data: 'het', name: 'harga_dealer_user' },
                    //             { data: 'id_gudang' },
                    //             { data: 'id_rak' },
                    //             { data: 'stock' },
                    //             { data: 'aksi', width: '3%', className: 'text-center', orderable: false },
                    //         ],
                    //     });

                    //     $('#search_filter').on('keyup', _.debounce(function(e){
                    //         parts_sales_order_datatable.draw();
                    //     }, 500));

                    //     // $('#search_filter').on('keypress', function(e){
                    //     //     if(e.which == 13){
                    //     //         e.preventDefault();
                    //     //         parts_sales_order_datatable.draw();
                    //     //     }
                    //     // });
                    // });
                    function drawing2()
                    {
                        // var parts_sales_order_datatable;
                        $('#parts_sales_order_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ordering: false,
                            searching: false,
                            ajax: {
                                url: "<?=base_url('api/actualStock') ?>",
                                dataSrc: "data",
                                data: function(d){
                                    d.search_kode=$('#cari_kode').val();
                                    d.search_nama_part=$('#cari_nama_part').val();
                                    d.search_nama_part_bahasa=$('#cari_nama_part_bahasa').val();
                                    d.selected_parts = _.chain(form_.parts)
                                    .map(function(part){
                                        return _.pick(part, ['id_part', 'id_rak']);
                                    })
                                    .value();
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', width: '3%', orderable: false },
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { data: 'nama_part_bahasa' },
                                { data: 'het', name: 'harga_dealer_user' },
                                { data: 'id_gudang' },
                                { data: 'id_rak' },
                                { data: 'stock' },
                                { data: 'aksi', width: '3%', className: 'text-center', orderable: false },
                            ],
                        });
                    }
                    
                    $(document).ready(function() {
                        // var parts_table;
                        // var parts_table = drawing2();
                        $('#btn-cari3').click(function(e){
                            $('#parts_sales_order_datatable').DataTable().clear().destroy();
                            e.preventDefault();
                        //    alert($('#cari').val("TEST"));
                            // parts_sales_order_datatable.draw();
                            drawing2();
                            // alert("TEST");
                         });
                    });
                </script>
            </div>
        </div>
    </div>
</div>