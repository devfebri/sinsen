<!-- Modal -->
<div id="h3_md_parts_sales_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 90vh">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Kategori Motor</label>
                            <div class="col-sm-8">
                                <select id="kategori_filter" class="form-control">
                                    <option value="">All</option>
                                    <?php 
                                        $kategori = $this->db->from('ms_kategori')->get()->result();
                                        foreach($kategori as $each):
                                    ?>
                                    <option value="<?= $each->id_kategori ?>"><?= $each->kategori ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <script>
                                    $(document).ready(function(){
                                        $('#kategori_filter').on('change', function(){
                                            h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw();
                                        })
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Tahun Produksi Motor</label>
                            <div class="col-sm-8">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="filter_tahun">
                                    <input type="hidden" id="filter_tahun_kendaraan">
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#filter_tahun').datepicker({
                                    format: "yyyy",
                                    viewMode: "years", 
                                    minViewMode: "years"
                                })
                                .on('changeDate', function(e){
                                    $('#filter_tahun_kendaraan').val(e.target.value);
                                    h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw();
                                });
                            });
                        </script>
                    </div> -->
                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Tahun Produksi Motor</label>
                            <div class="col-sm-8">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="filter_tahun">
                                    <input type="hidden" id="filter_tahun_kendaraan">
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#filter_tahun').datepicker({
                                    format: "yyyy",
                                    viewMode: "years", 
                                    minViewMode: "years"
                                })
                                .on('changeDate', function(e){
                                    $('#filter_tahun_kendaraan').val(e.target.value);
                                    h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw();
                                });

                                $('#filter_tahun').on('change', function(){
                                    var value = $(this).val();
                                    // Cek apakah inputan kosong
                                    if(value === '') {
                                        $('#filter_tahun_kendaraan').val(''); // Pastikan nilai filter di-clear
                                        h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw(); // Gambar ulang DataTable
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label col-sm-4 align-middle">Tipe Kendaraan</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <input id='nama_tipe_kendaraan_filter' type="text" class="form-control" disabled>
                                <input id='id_tipe_kendaraan_filter' type="hidden" disabled>
                                <div class="input-group-btn">
                                    <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_tipe_kendaraan_filter_part_sales_order'>
                                    <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>       
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Kode Part</label>
                            <div class="col-sm-8">
                            <input id='filter_kp' placeholder="Cari Kode Part" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Nama Part</label>
                            <div class="col-sm-8">
                            <input id='filter_np' placeholder="Cari Nama Part" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Nama Part Bahasa</label>
                            <div class="col-sm-8">
                            <input id='filter_npb' placeholder="Cari Nama Part Bahasa" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <!-- <label for="" class="control-label col-sm-4">&nbsp;</label> -->
                            <div class="col-sm-8">
                            <a id='cari_filter_p' type="button" class="btn btn-warning">Cari</a>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_sales_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Nama Part Bahasa</th>
                            <th>Kelompok Part</th>
                            <th>HET</th>
                            <th>Qty Onhand</th>
                            <th>Qty In Transit</th>
                            <th>Qty Booking Dinamis</th>
                            <th>Qty Booking DB</th>
                            <th>Qty AVS</th>
                            <th>Status</th>
                            <th>Tipe Motor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                // $(document).ready(function() {
                //     h3_md_parts_sales_order_datatable = $('#h3_md_parts_sales_order_datatable').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         order: [],
                //         ajax: {
                //             url: "<?= base_url('api/md/h3/parts_sales_order') ?>",
                //             dataSrc: "data",
                //             type: "POST",
                //             data: function(d){
                //                 d.kategori_po = app.sales_order.kategori_po;
                //                 d.id_dealer = app.sales_order.id_dealer;
                //                 d.produk = app.sales_order.produk;
                //                 d.id_part = _.map(app.parts, function(p){
                //                     return p.id_part;
                //                 });

                //                 d.id_tipe_kendaraan_filter = $('#id_tipe_kendaraan_filter').val();
                //             }
                //         },
                //         columns: [
                //             { data: null, orderable: false, width: '3%' }, 
                //             { data: 'id_part' },
                //             { data: 'nama_part' },
                //             { data: 'kelompok_part' },
                //             { data: 'harga_dealer_user', className: 'text-right', width: '70px' },
                //             { data: 'qty_on_hand', orderable: false, width: '30px' },
                //             { data: 'qty_intransit', orderable: false, width: '30px' },
                //             { data: 'qty_booking', orderable: false, width: '30px' },
                //             { data: 'qty_avs', orderable: false, width: '30px' },
                //             { data: 'status' },
                //             { data: 'view_tipe_motor', orderable: false, width: '3%', className: 'text-center' },
                //             { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                //         ],
                //     });

                //     h3_md_parts_sales_order_datatable.on('draw.dt', function() {
                //         info = h3_md_parts_sales_order_datatable.page.info();
                //         h3_md_parts_sales_order_datatable.column(0, {
                //             search: 'applied',
                //             order: 'applied',
                //             page: 'applied'
                //         }).nodes().each(function(cell, i) {
                //             cell.innerHTML = i + 1 + info.start + ".";
                //         });
                //     });
                // });

                var h3_md_parts_sales_order_datatable;
                function drawing_so_part(){
                    h3_md_parts_sales_order_datatable= $('#h3_md_parts_sales_order_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            searching: false,
                            bDestroy:true,
                            "iDisplayLength": 5, 
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/md/h3/parts_sales_order') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function(d){
                                    d.kategori_po = app.sales_order.kategori_po;
                                    d.id_dealer = app.sales_order.id_dealer;
                                    d.produk = app.sales_order.produk;
                                    d.is_ev = app.sales_order.is_ev;
                                    d.filter_kp = $('#filter_kp').val();
                                    d.filter_np = $('#filter_np').val();
                                    d.filter_npb = $('#filter_npb').val();
                                    // d.id_part = _.map(app.parts, function(p){
                                    //     return p.id_part;
                                    // });
                                    d.selected_parts = _.chain(app.parts)
                                    .map(function(part){
                                        return _.pick(part, ['id_part']);
                                    })
                                    .value();
                                    d.id_tipe_kendaraan_filter = $('#id_tipe_kendaraan_filter').val();
                                }
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%' }, 
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { data: 'nama_part_bahasa' },
                                { data: 'kelompok_part' },
                                { data: 'harga_dealer_user', className: 'text-right', width: '70px' },
                                { data: 'qty_on_hand', orderable: false, width: '30px' },
                                { data: 'qty_intransit', orderable: false, width: '30px' },
                                { data: 'qty_booking', orderable: false, width: '30px' },
                                { data: 'qty_booking_db', orderable: false, width: '30px' },
                                { data: 'qty_avs', orderable: false, width: '30px' },
                                { data: 'status' },
                                { data: 'view_tipe_motor', orderable: false, width: '3%', className: 'text-center' },
                                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                            ],
                        });
                        
                        h3_md_parts_sales_order_datatable.on('draw.dt', function() {
                            info = h3_md_parts_sales_order_datatable.page.info();
                            h3_md_parts_sales_order_datatable.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                }
                $(document).ready(function() {
                    $('#cari_filter_p').on('click', function(e){
                        drawing_so_part();
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('modal/h3_md_tipe_kendaraan_filter_part_sales_order'); ?>         
<script>
function pilih_tipe_kendaraan_filter_part_sales_order(data, type) {
    if(type == 'add_filter'){
        $('#nama_tipe_kendaraan_filter').val(data.tipe_ahm);
        $('#id_tipe_kendaraan_filter').val(data.id_tipe_kendaraan);
    }else if(type == 'reset_filter'){
        $('#nama_tipe_kendaraan_filter').val('');
        $('#id_tipe_kendaraan_filter').val('');
    }
    // h3_md_parts_sales_order_datatable.draw();
    drawing_so_part();  
    h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw();
}
</script>