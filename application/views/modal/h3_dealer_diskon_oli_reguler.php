<!-- Modal -->
<div id="h3_dealer_diskon_oli_reguler" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Dealer</h4>
        </div>
        <div class="modal-body">
            <table id="h3_dealer_diskon_oli_reguler_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode Dealer</th>
                        <th>Nama Dealer</th>
                        <th>Alamat</th>
                        <th>Kota / Kabupaten</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_dealer_diskon_oli_reguler_datatable = $('#h3_dealer_diskon_oli_reguler_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/dealer_diskon_oli_reguler') ?>",
                        dataSrc: "data",
                        type: "POST",
                    },
                    columns: [
                        { data: 'kode_dealer_md', width: '15%' },
                        { data: 'nama_dealer' },
                        { data: 'alamat' },
                        { data: 'kabupaten', width: '20%' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>