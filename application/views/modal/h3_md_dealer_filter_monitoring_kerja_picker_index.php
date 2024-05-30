<!-- Modal -->
<div id="h3_md_dealer_filter_monitoring_kerja_picker_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Customer</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_kode_dealer" id="cari_kode_dealer" placeholder="Cari Kode Dealer"/>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_nama_dealer" id="cari_nama_dealer" placeholder="Cari Nama Dealer"/>
                            </div>
                            <div class="col-sm-4">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_dealer"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <table id="h3_md_dealer_filter_monitoring_kerja_picker_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th>Alamat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                // $(document).ready(function() {
                //     h3_md_dealer_filter_monitoring_kerja_picker_index_datatable = $('#h3_md_dealer_filter_monitoring_kerja_picker_index_datatable').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         order: [],
                //         ajax: {
                //             url: "<?= base_url('api/md/h3/dealer_filter_monitoring_kerja_picker_index') ?>",
                //             dataSrc: "data",
                //             type: "POST",
                //             data: function(d){
                //                 d.filters = filter_customer.filters;
                //             }
                //         },
                //         columns: [
                //             { data: 'kode_dealer_md' },
                //             { data: 'nama_dealer' },
                //             { data: 'alamat' },
                //             { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                //         ],
                //     });
                // });
                function drawing_nama_dealer()
                {
                        $('#h3_md_dealer_filter_monitoring_kerja_picker_index_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        searching:false,
                        bDestroy:true,
                        ajax: {
                            url: "<?= base_url('api/md/h3/dealer_filter_monitoring_kerja_picker_index') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = filter_customer.filters;
                                    d.cari_kode_dealer=$('#cari_kode_dealer').val();
                                    d.cari_nama_dealer=$('#cari_nama_dealer').val();
                            }
                        },
                        columns: [
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'alamat' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                }
                $(document).ready(function() {
                    // drawing_pl_filter();
                    $('#btn-cari_dealer').click(function(e){
                            // $('#h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable').DataTable().clear().destroy();
                                e.preventDefault();
                                drawing_nama_dealer();
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>