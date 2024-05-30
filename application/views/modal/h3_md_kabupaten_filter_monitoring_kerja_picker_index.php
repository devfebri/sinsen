<!-- Modal -->
<div id="h3_md_kabupaten_filter_monitoring_kerja_picker_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <input type="text" class="form-control" name="cari_kabupaten" id="cari_kabupaten" placeholder="Cari Nama Kabupaten"/>
                            </div>
                            <div class="col-sm-4">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_kabupaten"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <table id="h3_md_kabupaten_filter_monitoring_kerja_picker_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kabupaten</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                // $(document).ready(function() {
                //     h3_md_kabupaten_filter_monitoring_kerja_picker_index_datatable = $('#h3_md_kabupaten_filter_monitoring_kerja_picker_index_datatable').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         order: [],
                //         ajax: {
                //             url: "<?= base_url('api/md/h3/kabupaten_filter_monitoring_kerja_picker_index') ?>",
                //             dataSrc: "data",
                //             type: "POST",
                //             data: function(d){
                //                 d.filters = filter_kabupaten.filters;
                //             }
                //         },
                //         columns: [
                //             { data: 'kabupaten' },
                //             { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                //         ],
                //     });
                // });
                function drawing_kabupaten(){
                    $('#h3_md_kabupaten_filter_monitoring_kerja_picker_index_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        bDestroy:true,
                        searching: false,
                        ajax: {
                            url: "<?= base_url('api/md/h3/kabupaten_filter_monitoring_kerja_picker_index') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = filter_kabupaten.filters;
                                    d.cari_kabupaten=$('#cari_kabupaten').val();
                            }
                        },
                        columns: [
                            { data: 'kabupaten' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                };
                $(document).ready(function() {
                    drawing_pl_filter();
                    $('#btn-cari_kabupaten').click(function(e){
                            // $('#h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable').DataTable().clear().destroy();
                                e.preventDefault();
                                drawing_kabupaten();
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>