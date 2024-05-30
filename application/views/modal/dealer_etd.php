<!-- Modal -->
<div id="modal_dealer_etd" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Order to</h4>
            </div>
            <div class="modal-body">
                <table id="datatable_dealer_etd" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nama Dealer</th>
                            <th>Alamat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        datatable_dealer_etd = $('#datatable_dealer_etd').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "<?= base_url('api/md/h3/dealer_etd') ?>",
                                dataSrc: "data",
                                data: function(d) {

                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'nama_dealer' },
                                { data: 'alamat' }, 
                                { data: 'action' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>