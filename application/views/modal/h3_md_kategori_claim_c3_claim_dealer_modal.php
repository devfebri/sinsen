<!-- Modal -->
<div id="h3_md_kategori_claim_c3_claim_dealer_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Kategori Claim C3</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_kategori_claim_c3_claim_dealer_modal_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Claim</th>
                            <th>Nama Claim</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_kategori_claim_c3_claim_dealer_modal_datatable = $('#h3_md_kategori_claim_c3_claim_dealer_modal_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/kategori_claim_c3_claim_dealer') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'kode_claim' },
                            { data: 'nama_claim' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>