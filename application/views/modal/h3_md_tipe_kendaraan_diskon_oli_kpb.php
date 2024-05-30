<!-- Modal -->
<div id="h3_md_tipe_kendaraan_diskon_oli_kpb" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Tipe Kendaraan</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_tipe_kendaraan_diskon_oli_kpb_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tipe Produksi</th>
                        <th>Kode Tipe Kendaraan</th>
                        <th>Tipe Kendaraan</th>
                        <th>Tanggal Berlaku</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_tipe_kendaraan_diskon_oli_kpb_datatable = $('#h3_md_tipe_kendaraan_diskon_oli_kpb_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/tipe_kendaraan_diskon_oli_kpb') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_part = app.diskon_oli_kpb.id_part;
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%' },
                        { data: 'tipe_produksi' },
                        { data: 'id_tipe_kendaraan' },
                        { data: 'tipe_ahm' },
                        { data: 'tgl_awal' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>