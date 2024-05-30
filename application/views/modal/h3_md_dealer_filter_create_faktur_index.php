<!-- Modal -->
<div id="h3_md_dealer_filter_create_faktur_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Dealer</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-4">
                           <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-reload_data"><span class="fa fa-search"></span>Tampilkan Data</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <table id="h3_md_dealer_filter_create_faktur_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode Dealer</th>
                        <th>Nama Dealer</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            // $(document).ready(function() {
            //     h3_md_dealer_filter_create_faktur_index_datatable = $('#h3_md_dealer_filter_create_faktur_index_datatable').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         order: [],
            //         ajax: {
            //             url: "<?= base_url('api/md/h3/dealer_filter_create_faktur_index') ?>",
            //             dataSrc: "data",
            //             type: "POST",
            //             data: function(d){
            //                 d.id_customer_filter = $('#id_customer_filter').val();
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
            var h3_md_dealer_filter_create_faktur_index_datatable;
            function drawing_dealer_filter_faktur(){
                    h3_md_dealer_filter_create_faktur_index_datatable = $('#h3_md_dealer_filter_create_faktur_index_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy : true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/dealer_filter_create_faktur_index') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_customer_filter = $('#id_customer_filter').val();
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
                    $('#btn-reload_data').click(function(e){
                                e.preventDefault();
                                drawing_dealer_filter_faktur();
                    });
            });
            </script>
        </div>
        </div>
    </div>
</div>