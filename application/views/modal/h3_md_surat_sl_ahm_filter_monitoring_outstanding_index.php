<!-- Modal -->
<div id="h3_md_surat_sl_ahm_filter_monitoring_outstanding_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Surat SL AHM Filter</h4>
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
            <table id="h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Surat Jalan AHM</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            // $(document).ready(function() {
            //     h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable = $('#h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         order: [],
            //         ajax: {
            //             url: "<?= base_url('api/md/h3/surat_sl_ahm_filter_monitoring_outstanding') ?>",
            //             dataSrc: "data",
            //             type: "POST",
            //             data: function(d){
            //                 d.filters = surat_sl_ahm_filter.filters;
            //             }
            //         },
            //         columns: [
            //             { data: 'surat_jalan_ahm' },
            //             { data: 'action', orderable: false, className: 'text-center', width: '3%' }
            //         ],
            //     });
            // });
            var h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable;
            function drawing_surat_jalan_ahm(){
                    h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable = $('#h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    bDestroy: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/surat_sl_ahm_filter_monitoring_outstanding') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.filters = surat_sl_ahm_filter.filters;
                        }
                    },
                    columns: [
                        { data: 'surat_jalan_ahm' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            }
            $(document).ready(function() {
                $('#btn-reload_data').click(function(e){
                    e.preventDefault();
                    drawing_surat_jalan_ahm();
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>