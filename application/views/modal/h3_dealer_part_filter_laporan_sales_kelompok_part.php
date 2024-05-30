<!-- Modal -->
<div id="h3_dealer_part_filter_laporan_sales_kelompok_part" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filter Parts</h4>
            </div>
            <div class="modal-body">
                <table id="h3_dealer_part_filter_laporan_sales_kelompok_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kelompok Part</th>
                            <th class='text-center'>
                                <input type="checkbox" id='checkbox-all'>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_dealer_part_filter_laporan_sales_kelompok_part_datatable = $('#h3_dealer_part_filter_laporan_sales_kelompok_part_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        drawCallback: function(e){
                            api = this.api();
                            data = api.rows().data();

                            count_found = 0;
                            for (var index = 0; index < data.length; index++) {
                                var element = data[index];
                                found_index = _.findIndex(form_.filter_kelompok_part, function(kelompok_part){
                                    return kelompok_part == element.kelompok_part;
                                });

                                if(found_index != -1){
                                    count_found++;
                                }
                            }
                            if( (api.page.len() == count_found) ){
                                $('#checkbox-all').prop('checked', true);
                            }else{
                                $('#checkbox-all').prop('checked', false);
                            }
                        },  
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/dealer/part_filter_laporan_sales_kelompok_part') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = form_.filter_kelompok_part;
                                d.start_date = form_.start_date;
                                d.end_date = form_.end_date;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'kelompok_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });

                    $('#checkbox-all').on('change', function(e){
                        target = $(e.target);

                        if(target.is(':checked')){
                            data = h3_dealer_part_filter_laporan_sales_kelompok_part_datatable.rows().data();
                            for_insert = [];
                            for (var index = 0; index < data.length; index++) {
                                var element = data[index];
                                found_index = _.findIndex(form_.filter_kelompok_part, function(kelompok_part){
                                    return kelompok_part == element.kelompok_part;
                                });

                                if(found_index == -1){
                                    form_.filter_kelompok_part.push(element.kelompok_part);
                                }
                            }
                        }else{
                            data = h3_dealer_part_filter_laporan_sales_kelompok_part_datatable.rows().data();
                            for (var index = 0; index < data.length; index++) {
                                var element = data[index];
                                found_index = _.findIndex(form_.filter_kelompok_part, function(kelompok_part){
                                    return kelompok_part == element.kelompok_part;
                                });

                                form_.filter_kelompok_part.splice(found_index, 1);
                            }
                        }
                        h3_dealer_part_filter_laporan_sales_kelompok_part_datatable.draw(false);
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>