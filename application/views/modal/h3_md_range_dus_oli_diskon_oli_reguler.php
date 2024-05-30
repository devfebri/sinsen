<!-- Modal -->
<div id="h3_md_range_dus_oli_diskon_oli_reguler" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Range Dus Oli</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_range_dus_oli_diskon_oli_reguler_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Range</th>
                        <th>Awal Range</th>
                        <th>Akhir Range</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_range_dus_oli_diskon_oli_reguler_datatable = $('#h3_md_range_dus_oli_diskon_oli_reguler_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/range_dus_oli_diskon_oli_reguler') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.selected_range = _.map(app.ranges, function(data){
                                return data.id;
                            });
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%', className: 'text-center' },
                        { data: 'kode_range' },
                        { data: 'range_start' },
                        { data: 'range_end' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
                h3_md_range_dus_oli_diskon_oli_reguler_datatable.on('draw.dt', function() {
                    var info = h3_md_range_dus_oli_diskon_oli_reguler_datatable.page.info();
                    h3_md_range_dus_oli_diskon_oli_reguler_datatable.column(0, {
                        search: 'applied',
                        order: 'applied',
                        page: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + info.start + ".";
                    });
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>