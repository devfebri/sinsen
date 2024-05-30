<!-- Modal -->
<div id="h3_dealer_part_filter_laporan_stock_all" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filter Parts</h4>
            </div>
            <div class="modal-body">
                <table id="h3_dealer_part_filter_laporan_stock_all_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No Part</th>
                            <th>Deskripsi Part</th>
                            <th class='text-center'>
                                <input type="checkbox" id='checkbox-all'>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    $('#checkbox-all').on('change', function(e){
                        target = e;
                        
                        axios.get('<?= base_url('api/dealer/part_filter_laporan_stock_versi_all/check_all') ?>')
                        .then(function(res){
                            if(target.is(':checked')){
                                form_.filter_parts = res.data;
                            }else{
                                form_.filter_parts = [];
                            }
                        })
                        .catch(function(err){
                            toastr.error(err);
                        })
                    });
                    
                    h3_dealer_part_filter_laporan_stock_all_datatable = $('#h3_dealer_part_filter_laporan_stock_all_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/dealer/part_filter_laporan_stock_versi_all') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = form_.filter_parts;
                                d.start_date = form_.start_date;
                                d.end_date = form_.end_date;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });

                    $('#checkbox-all').on('change', function(e){
                        target = $(e.target);

                        if(target.is(':checked')){
                            data = h3_dealer_part_filter_laporan_stock_all_datatable.rows().data();
                            for_insert = [];
                            for (var index = 0; index < data.length; index++) {
                                var element = data[index];
                                found_index = _.findIndex(form_.filter_parts, function(id_part){
                                    return id_part == element.id_part;
                                });

                                if(found_index == -1){
                                    form_.filter_parts.push(element.id_part);
                                }
                            }
                        }else{
                            data = h3_dealer_part_filter_laporan_stock_all_datatable.rows().data();
                            for (var index = 0; index < data.length; index++) {
                                var element = data[index];
                                found_index = _.findIndex(form_.filter_parts, function(id_part){
                                    return id_part == element.id_part;
                                });

                                form_.filter_parts.splice(found_index, 1);
                            }
                        }
                        h3_dealer_part_filter_laporan_stock_all_datatable.draw(false);
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>