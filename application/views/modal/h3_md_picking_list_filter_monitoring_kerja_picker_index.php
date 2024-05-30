<!-- Modal -->
<div id="h3_md_picking_list_filter_monitoring_kerja_picker_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Picking List</h4>
            </div>
            <div class="modal-body">
            <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_kode" id="cari_no_pl" placeholder="Cari No.PL"/>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_nama_part" id="cari_no_do" placeholder="Cari No.DO"/>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_nama_part" id="cari_no_so" placeholder="Cari No.SO"/>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_pl"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <table id="h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No. Picking List</th>
                            <th>No. Delivery Order</th>
                            <th>No. Sales Order</th>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                // $(document).ready(function() {
                //     h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable = $('#h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable').DataTable({
                //         processing: true,
                //         serverSide: true,
                //         order: [],
                //         ajax: {
                //             url: "<?= base_url('api/md/h3/picking_list_filter_monitoring_kerja_picker') ?>",
                //             dataSrc: "data",
                //             type: "POST",
                //             data: function(d){
                //                 d.filters = filter_delivery_order.filters;
                //             }
                //         },
                //         columns: [
                //             { data: 'id_picking_list' },
                //             { data: 'id_do_sales_order' },
                //             { data: 'id_sales_order' },
                //             { data: 'kode_dealer_md' },
                //             { data: 'nama_dealer' },
                //             { data: 'action', className: 'text-center', width: '3%', orderable: false }
                //         ],
                //     });
                // });
                function drawing_pl_filter()
                {
                    $('#h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        searching: false,
                        bDestroy: true,
                        ajax: {
                            url: "<?= base_url('api/md/h3/picking_list_filter_monitoring_kerja_picker') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                // d.filters = filter_delivery_order.filters;
                                    d.cari_no_pl=$('#cari_no_pl').val();
                                    d.cari_no_do=$('#cari_no_do').val();
                                    d.cari_no_so=$('#cari_no_so').val();
                            }
                        },
                        columns: [
                            { data: 'id_picking_list' },
                            { data: 'id_do_sales_order' },
                            { data: 'id_sales_order' },
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'action', className: 'text-center', width: '3%', orderable: false }
                        ],
                    });
                }

                $(document).ready(function() {
                    // drawing_pl_filter();
                    $('#btn-cari_pl').click(function(e){
                            // $('#h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable').DataTable().clear().destroy();
                                e.preventDefault();
                                drawing_pl_filter();
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>