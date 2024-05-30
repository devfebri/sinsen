<div id="dealer_autofulfillment" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">dealer</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="dealer_autofulfillment_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Customer</th>
                            <th>Nama Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        dealer_autofulfillment_datatable = $('#dealer_autofulfillment_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/md/h3/dealer_autofulfillment') ?>",
                                dataSrc: "data",
                                type: "POST",
                            },
                            columns: [
                                { data: 'kode_dealer_md' },
                                { data: 'nama_dealer' },
                                {  data: 'action', orderable: false, width: '3%', className: 'text-center' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>