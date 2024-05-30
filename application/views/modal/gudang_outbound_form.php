<!-- Modal -->
<div id="gudang_outbound_form" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="gudang_outbound_form_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Gudang</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        gudang_outbound_form_datatable = $('#gudang_outbound_form_datatable').DataTable({
                            initComplete: function() {
                                $('#gudang_outbound_form_datatable_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                                $('#gudang_outbound_form_datatable_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                                axios.get('html/filter_kategori_gudang_outbound_part_transfer')
                                    .then(function(res) {
                                        $('#gudang_outbound_form_datatable_filter').prepend(res.data);

                                        $('#filter_kategori_gudang').change(function() {
                                            gudang_outbound_form_datatable.draw();
                                        });
                                    });
                            },
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "<?=base_url('api/dealer/gudang_outbound_form') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.filter_kategori = $('#filter_kategori_gudang').val();
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: 'id_gudang' },
                                { data: 'deskripsi_gudang'},
                                { data: 'kategori'},
                                { data: 'action', width: '3%', className: 'text-center', orderable:false }
                            ],

                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>