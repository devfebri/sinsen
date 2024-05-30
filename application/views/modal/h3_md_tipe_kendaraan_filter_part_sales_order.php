<!-- Modal -->
<div id="h3_md_tipe_kendaraan_filter_part_sales_order" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Tipe Kendaraan</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-6">Tahun Produksi Motor</label>
                        <div class="col-sm-8">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="filter_tahun_2">
                                <input type="hidden" id="filter_tahun_kendaraan_2">
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('#filter_tahun_2').datepicker({
                                format: "yyyy",
                                viewMode: "years", 
                                minViewMode: "years"
                            }).on('changeDate', function(e){
                                $('#filter_tahun_kendaraan_2').val(e.target.value);
                                h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw();
                            });
                            $('#filter_tahun_2').on('change', function(){
                                var value = $(this).val();
                                if(value === '') {
                                    $('#filter_tahun_kendaraan_2').val(''); 
                                    h3_md_tipe_kendaraan_filter_part_sales_order_datatable.draw(); 
                                }
                            });
                        });
                    </script>
                </div>
            </div>
            <table id="h3_md_tipe_kendaraan_filter_part_sales_order_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode Tipe Kendaraan</th>
                        <th>Tipe AHM</th>
                        <th>Deskripsi AHM</th>
                        <th>Tipe Customer</th>
                        <th>Tanggal Produksi</th>
                        <th>CC Motor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_tipe_kendaraan_filter_part_sales_order_datatable = $('#h3_md_tipe_kendaraan_filter_part_sales_order_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/tipe_kendaraan_filter_part_sales_order') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_tipe_kendaraan_filter = $('#id_tipe_kendaraan_filter').val();
                            d.kategori_filter = $('#kategori_filter').val();
                            d.filter_tahun_kendaraan = $('#filter_tahun_kendaraan').val();
                        }
                    },
                    columns: [
                        { data: 'id_tipe_kendaraan' },
                        { data: 'tipe_ahm' },
                        { data: 'deskripsi_ahm' },
                        { data: 'tipe_customer' },
                        { data: 'tgl_awal' },
                        { data: 'cc_motor' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>