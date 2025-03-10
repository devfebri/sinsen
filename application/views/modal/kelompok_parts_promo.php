<!-- Modal -->
<div id="kelompok_parts_promo" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="kelompok_parts_promo_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kelompok Part</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        $('#kelompok_parts_promo_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/kelompok_parts_promo') ?>",
                                dataSrc: "data",
                                data: function(d) {

                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'kelompok_part' }, 
                                { data: 'action', width: '3%', className: 'text-center', orderable: false }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>