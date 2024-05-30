<input type="hidden" id='h3_md_open_view_packing_sheet_number_pelunasan_bapb_value'>
<div id="h3_md_open_view_packing_sheet_number_pelunasan_bapb" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Packing Sheet Number</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_open_view_packing_sheet_number_pelunasan_bapb_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. PS</th>
                        <th>Tgl. PS</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_open_view_packing_sheet_number_pelunasan_bapb_datatable = $('#h3_md_open_view_packing_sheet_number_pelunasan_bapb_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/open_view_packing_sheet_number_pelunasan_bapb') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.no_pelunasan = $('#h3_md_open_view_packing_sheet_number_pelunasan_bapb_value').val();
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%'},
                        { data: 'packing_sheet_number' },
                        { 
                            data: 'packing_sheet_date',
                            render: function(data){
                                return moment(data).format('DD/MM/YYYY');
                            }
                        },
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>