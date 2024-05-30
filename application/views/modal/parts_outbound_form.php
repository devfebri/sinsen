<!-- Modal -->
<div id="parts_outbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Parts</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_kode_part" id="cari_kode_part" placeholder="Masukkan Kode Sparepart"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_nama_part" id="cari_nama_part" placeholder="Masukkan Nama Part"/>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cari_id_rak" id="cari_id_rak" placeholder="Masukkan Kode Rak"/>
                            </div>
                            <div class="col-sm-3">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_part"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
            </div>
            <table id="parts_outbound_form_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th>Stock</th>
                    <th>Qty Booking</th>
                    <th>Tipe Gudang</th>
                    <th>Kategori Gudang</th>
                    <th>Kode Gudang</th>
                    <th>Kode Rak</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            // $(document).ready(function() {
            //     parts_outbound_form_datatable = $('#parts_outbound_form_datatable').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         language: {
            //             "infoFiltered": "",
            //             "searchPlaceholder": "Min. 5 digit untuk cari",
            //             "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
            //         },
            //         order: [],
            //         ajax: {
            //             url: "<?= base_url('api/dealer/parts_outbound_form') ?>",
            //             dataSrc: "data",
            //             type: "POST",
            //             data: function(d){
            //                 d.id_gudang = form_.gudang.id_gudang;
            //             },
            //         },
            //         columns: [
            //             { data: 'id_part' },
            //             { data: 'nama_part' },
            //             { data: 'qty_asal' },
            //             { data: 'qty_booking' },
            //             { data: 'tipe_gudang' },
            //             { data: 'kategori' },
            //             { data: 'id_gudang' },
            //             { data: 'id_rak' },
            //             { data: 'action', orderable: false, className: 'text-center', width: '3%' }
            //         ],
            //     });

            //     $(".dataTables_filter input")
            //         .unbind() // Unbind previous default bindings
            //         .bind("input", function(e) { // Bind our desired behavior
            //         // If the length is 3 or more characters, or the user pressed ENTER, search
            //         if(this.value.length >= 5 || e.keyCode == 13) {
            //             // Call the API search function
            //             parts_outbound_form_datatable.search(this.value).draw();
            //         }
            //         // Ensure we clear the search if they backspace far enough
            //         if(this.value == "") {
            //             parts_outbound_form_datatable.search("").draw();
            //         }
            //         return;
            //     });
            // });

            var parts_outbound_form_datatable ; 
            function drawing_part_outbound_form(){
                parts_outbound_form_datatable = $('#parts_outbound_form_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching:false,
                    bDestroy: true,
                    language: {
                        "infoFiltered": "",
                        "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                    },
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/parts_outbound_form') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_gudang = form_.gudang.id_gudang;
                            d.id_dealer = form_.gudang.id_dealer;
                            d.cari_kode_part = $('#cari_kode_part').val();
                            d.cari_nama_part=$('#cari_nama_part').val();
                            d.cari_id_rak=$('#cari_id_rak').val();
                        },
                    },
                    columns: [
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { data: 'qty_asal' },
                        { data: 'qty_booking' },
                        { data: 'tipe_gudang' },
                        { data: 'kategori' },
                        { data: 'id_gudang' },
                        { data: 'id_rak' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            }

            $(document).ready(function() {
                    $('#btn-cari_part').on('click', function(e){
                        drawing_part_outbound_form();
                    });
                });
            </script>
        </div>
        </div>
    </div>
</div>