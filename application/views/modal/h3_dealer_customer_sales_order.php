<!-- Modal -->
<div id="modal-customer" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_cust" id="cari_cust" placeholder="Masukkan Nama Customer"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_nosin" id="cari_nosin" placeholder="Masukkan No.Mesin"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_norang" id="cari_norang" placeholder="Masukkan No.Rangka"/>
                            </div>
                            <div class="col-sm-3">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_customer"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-customer" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th>No HP</th>
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    // $(document).ready(function() {
                    //     datatable_customer = $('#datatable-customer').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         order: [],
                    //         ajax: {
                    //             url: "",
                    //             dataSrc: "data",
                    //             type: "POST",
                    //             data: function(d) {

                    //             },
                    //         },
                    //         columns: [
                    //             { data: 'index', orderable: false, width: '3%' }, 
                    //             { data: 'id_customer' }, 
                    //             { data: 'nama_customer' }, 
                    //             { data: 'no_hp' }, 
                    //             { data: 'no_mesin' }, 
                    //             { data: 'no_rangka' }, 
                    //             { data: 'aksi', orderable: false, width: '3%' }
                    //         ],
                    //     });
                    // });
                    function drawing_customer() {
                        $('#datatable-customer').DataTable({
                            processing: true,
                            serverSide: true,
                            searching: false, 
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/customer/fetch_all') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function(d) {
                                    d.search_cust=$('#cari_cust').val();
                                    d.search_nosin=$('#cari_nosin').val();
                                    d.search_norang=$('#cari_norang').val();
                                },
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_customer' }, 
                                { data: 'nama_customer' }, 
                                { data: 'no_hp' }, 
                                { data: 'no_mesin' }, 
                                { data: 'no_rangka' }, 
                                { data: 'aksi', orderable: false, width: '3%' }
                            ],
                        });
                    };

                    $(document).ready(function() {
                        $('#btn-cari_customer').click(function(e){
                            $('#datatable-customer').DataTable().clear().destroy();
                            e.preventDefault();
                           //alert($('#cari').val());
                            drawing_customer();
                         });
                    });
                </script>
            </div>
        </div>
    </div>
</div>