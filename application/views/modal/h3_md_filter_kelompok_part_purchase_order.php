<!-- Modal -->
<div id="h3_md_filter_kelompok_part_purchase_order" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Filter Kelompok Parts</h4>
            </div>
            <div class="container-fluid">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_filter_kelompok_part_purchase_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kelompok Part</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_filter_kelompok_part_purchase_order_datatable = $('#h3_md_filter_kelompok_part_purchase_order_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/filter_kelompok_part_purchase_order') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.available_kelompok_part = _.chain(app.parts)
                                .map(function(data){
                                    return data.kelompok_part;
                                })
                                .value();

                                d.filters = app.filter_kelompok_part;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'id_kelompok_part' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>