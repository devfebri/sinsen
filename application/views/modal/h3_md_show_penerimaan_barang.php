<!-- Modal -->
<div id="h3_md_show_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Barang Sudah di Cek</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid" style='margin-bottom: 15px;'>
                    <form>
                        <div class="row">
                            <div class="col-sm-3"> 
                                <div class="form-group" style='padding-right: 10px;'>
                                    <label class="control-label">Surat Jalan AHM</label>
                                    <input id='filter_surat_jalan_ahm_barang_sudah_dicek' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"> 
                                <div class="form-group" style='padding-right: 10px;'>
                                    <label class="control-label">Packing Sheet Number</label>
                                    <input id='filter_packing_sheet_number_barang_sudah_dicek' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"> 
                                <div class="form-group">
                                    <label class="control-label">Nomor Karton</label>
                                    <input id='filter_nomor_karton_barang_sudah_dicek' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"> 
                                <div class="form-group">
                                    <label class="control-label">Kode Part</label>
                                    <input id='filter_id_part_barang_sudah_dicek' type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button id='cari_filter_barang_sudah_dicek' class="btn btn-flat btn-sm btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <script>
                    $(document).ready(function(){
                        $('#cari_filter_barang_sudah_dicek').on('click', function(e){
                            e.preventDefault();
                            barang_sudah_dicek.draw();
                        });
                    });
                </script> -->
                <table id='barang_sudah_dicek' style='margin-top: 15px;' class="table table-condesned table-bordered">
                    <thead> 
                        <tr>
                            <th class='align-middle' width='3%'>No.</th>
                            <th class='align-middle'>Surat Jalan AHM</th>
                            <th class='align-middle'>Tanggal PS</th>
                            <th class='align-middle'>No. Packing Sheet</th>
                            <th class='align-middle'>No. Karton</th>
                            <th class='align-middle'>Part Number</th>
                            <th class='align-middle'>Nama Part</th>
                            <th class='align-middle'>Qty PS</th>
                            <th class='align-middle'>Qty Scan</th>
                            <th class='align-middle'>Qty Selisih</th>
                            <th class='align-middle'>Lokasi Rak</th>
                            <th class='align-middle'>Reason</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                // $(document).ready(function(){
                //     barang_sudah_dicek = $('#barang_sudah_dicek').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         searching: false,
                //         order: [],
                //         ajax: {
                //             url: "",
                //             dataSrc: 'data',
                //             type: "POST",
                //             data: function(d){
                //                 d.no_surat_jalan_ekspedisi = _.get(form_.penerimaan_barang, 'no_surat_jalan_ekspedisi');

                //                 d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm_barang_sudah_dicek').val();
                //                 d.filter_packing_sheet_number = $('#filter_packing_sheet_number_barang_sudah_dicek').val();
                //                 d.filter_nomor_karton = $('#filter_nomor_karton_barang_sudah_dicek').val();
                //                 d.filter_id_part = $('#filter_id_part_barang_sudah_dicek').val();
                //             }
                //         },
                //         columns: [
                //             { data: null, orderable: false, width: '3%' }, 
                //             { data: 'surat_jalan_ahm' }, 
                //             { data: 'packing_sheet_date' }, 
                //             { data: 'packing_sheet_number' }, 
                //             { data: 'nomor_karton' }, 
                //             { data: 'id_part' }, 
                //             { data: 'nama_part' }, 
                //             { data: 'packing_sheet_quantity' }, 
                //             { data: 'qty_diterima' }, 
                //             { data: 'qty_selisih' }, 
                //         ],
                //     });
                //     barang_sudah_dicek.on('draw.dt', function() {
                //         var info = barang_sudah_dicek.page.info();
                //         barang_sudah_dicek.column(0, {
                //             search: 'applied',
                //             order: 'applied',
                //             page: 'applied'
                //         }).nodes().each(function(cell, i) {
                //             cell.innerHTML = i + 1 + info.start + ".";
                //         });
                //     });
                // });
                // var barang_sudah_dicek;
                // function drawing_barang_sudah_dicek(){
                //     barang_sudah_dicek = $('#barang_sudah_dicek').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         searching: false,
                //         bDestroy: true,
                //         order: [],
                //         ajax: {
                //             url: "",
                //             dataSrc: 'data',
                //             type: "POST",
                //             data: function(d){
                //                 d.no_surat_jalan_ekspedisi = _.get(form_.penerimaan_barang, 'no_surat_jalan_ekspedisi');

                //                 d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm_barang_sudah_dicek').val();
                //                 d.filter_packing_sheet_number = $('#filter_packing_sheet_number_barang_sudah_dicek').val();
                //                 d.filter_nomor_karton = $('#filter_nomor_karton_barang_sudah_dicek').val();
                //                 d.filter_id_part = $('#filter_id_part_barang_sudah_dicek').val();
                //             }
                //         },
                //         columns: [
                //             { data: null, orderable: false, width: '3%' }, 
                //             { data: 'surat_jalan_ahm' }, 
                //             { data: 'packing_sheet_date' }, 
                //             { data: 'packing_sheet_number' }, 
                //             { data: 'nomor_karton' }, 
                //             { data: 'id_part' }, 
                //             { data: 'nama_part' }, 
                //             { data: 'packing_sheet_quantity' }, 
                //             { data: 'qty_diterima' }, 
                //             { data: 'qty_selisih' }, 
                //         ],
                //     });
                //     barang_sudah_dicek.on('draw.dt', function() {
                //         var info = barang_sudah_dicek.page.info();
                //         barang_sudah_dicek.column(0, {
                //             search: 'applied',
                //             order: 'applied',
                //             page: 'applied'
                //         }).nodes().each(function(cell, i) {
                //             cell.innerHTML = i + 1 + info.start + ".";
                //         });
                //     });  
                // }

                var barang_sudah_dicek;
                $(document).ready(function() {
                    $('#cari_filter_barang_sudah_dicek').click(function(e){
                        $('#barang_sudah_dicek').DataTable().clear().destroy();
                        e.preventDefault();
                        // drawing_barang_sudah_dicek();
                        barang_sudah_dicek = $('#barang_sudah_dicek').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                            bDestroy: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/barang_sudah_dicek') ?>",
                            dataSrc: 'data',
                            type: "POST",
                            data: function(d){
                                d.no_surat_jalan_ekspedisi = _.get(form_.penerimaan_barang, 'no_surat_jalan_ekspedisi');

                                d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm_barang_sudah_dicek').val();
                                d.filter_packing_sheet_number = $('#filter_packing_sheet_number_barang_sudah_dicek').val();
                                d.filter_nomor_karton = $('#filter_nomor_karton_barang_sudah_dicek').val();
                                d.filter_id_part = $('#filter_id_part_barang_sudah_dicek').val();
                            }
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%' }, 
                            { data: 'surat_jalan_ahm' }, 
                            { data: 'packing_sheet_date' }, 
                            { data: 'packing_sheet_number' }, 
                            { data: 'nomor_karton' }, 
                            { data: 'id_part' }, 
                            { data: 'nama_part' }, 
                            { data: 'packing_sheet_quantity' }, 
                            { data: 'qty_diterima' }, 
                            { data: 'qty_selisih' },  
                            { data: 'kode_lokasi_rak',orderable: false }, 
                            { data: 'reason',orderable: false}, 
                        ],
                    });
                    barang_sudah_dicek.on('draw.dt', function() {
                        var info = barang_sudah_dicek.page.info();
                        barang_sudah_dicek.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'applied'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1 + info.start + ".";
                        });
                    });  
                    });
                });
                </script>
                <input type="hidden" id="no_karton" />
                <input type="hidden" id="no_penerimaan_barang" />
                <input type="hidden" id="id_part" />
                <?php $this->load->view('modal/h3_md_penerimaan_barang_reason_modal'); ?>
                <script>
                    function open_view_reason(no_karton,no_penerimaan_barang,id_part){
                        $('#no_karton').val(no_karton);
                        $('#no_penerimaan_barang').val(no_penerimaan_barang);
                        $('#id_part').val(id_part);
                        $('#h3_md_penerimaan_barang_reason_modal').modal('show');
                        reason_penerimaan_barang_datatable.draw();
                    }
                </script>
            </div>
        </div>
    </div>
</div>