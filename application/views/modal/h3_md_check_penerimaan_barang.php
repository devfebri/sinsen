<!-- Modal -->
<div id="h3_md_check_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Barang Belum di Cek</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid" style='margin-bottom: 15px;'>
                    <form>
                        <div class="row">
                            <div class="col-sm-3"> 
                                <div class="form-group" style='padding-right: 10px;'>
                                    <label class="control-label">Surat Jalan AHM</label>
                                    <input id='filter_surat_jalan_ahm_barang_belum_dicek' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"> 
                                <div class="form-group" style='padding-right: 10px;'>
                                    <label class="control-label">Packing Sheet Number</label>
                                    <input id='filter_packing_sheet_number_barang_belum_dicek' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"> 
                                <div class="form-group">
                                    <label class="control-label">Nomor Karton</label>
                                    <input id='filter_nomor_karton_barang_belum_dicek' type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-3"> 
                                <div class="form-group">
                                    <label class="control-label">Kode Part</label>
                                    <input id='filter_id_part_barang_belum_dicek' type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button id='cari_filter_barang_belum_dicek' class="btn btn-flat btn-sm btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <script>
                    $(document).ready(function(){
                        $('#cari_filter_barang_belum_dicek').on('click', function(e){
                            e.preventDefault();
                            // barang_belum_dicek.draw();
                            drawing_barang_belum_dicek();
                        });
                    });
                </script> -->
                <table id='barang_belum_dicek' style='margin-top: 15px;' class="table table-condesned table-bordered">
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
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                // $(document).ready(function(){
                //     var barang_belum_dicek;
                //     function drawing_barang_belum_dicek(){
                //     barang_belum_dicek = $('#barang_belum_dicek').DataTable({
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

                //                 d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm_barang_belum_dicek').val();
                //                 d.filter_packing_sheet_number = $('#filter_packing_sheet_number_barang_belum_dicek').val();
                //                 d.filter_nomor_karton = $('#filter_nomor_karton_barang_belum_dicek').val();
                //                 d.filter_id_part = $('#filter_id_part_barang_belum_dicek').val();
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
                //         ],
                //     });
                //     barang_belum_dicek.on('draw.dt', function() {
                //         var info = barang_belum_dicek.page.info();
                //         barang_belum_dicek.column(0, {
                //             search: 'applied',
                //             order: 'applied',
                //             page: 'applied'
                //         }).nodes().each(function(cell, i) {
                //             cell.innerHTML = i + 1 + info.start + ".";
                //         });
                //     });
                // }
                // });

                var barang_belum_dicek;
                $(document).ready(function(){
                        $('#cari_filter_barang_belum_dicek').on('click', function(e){
                            e.preventDefault();
                            // drawing_barang_belum_dicek();
                            barang_belum_dicek = $('#barang_belum_dicek').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        bDestroy: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/barang_belum_dicek') ?>",
                            dataSrc: 'data',
                            type: "POST",
                            data: function(d){
                                d.no_surat_jalan_ekspedisi = _.get(form_.penerimaan_barang, 'no_surat_jalan_ekspedisi');

                                d.filter_surat_jalan_ahm = $('#filter_surat_jalan_ahm_barang_belum_dicek').val();
                                d.filter_packing_sheet_number = $('#filter_packing_sheet_number_barang_belum_dicek').val();
                                d.filter_nomor_karton = $('#filter_nomor_karton_barang_belum_dicek').val();
                                d.filter_id_part = $('#filter_id_part_barang_belum_dicek').val();
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
                        ],
                    });
                    barang_belum_dicek.on('draw.dt', function() {
                        var info = barang_belum_dicek.page.info();
                        barang_belum_dicek.column(0, {
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
            </div>
        </div>
    </div>
</div>