<div id="parts_jumlah_pit" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="parts_jumlah_pit_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Part</th>
                            <th>Nama Part</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        parts_jumlah_pit_datatable = $('#parts_jumlah_pit_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/md/h3/parts_jumlah_pit') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: 'id_part' }, 
                                { data: 'nama_part' },
                                {  data: 'action', }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>