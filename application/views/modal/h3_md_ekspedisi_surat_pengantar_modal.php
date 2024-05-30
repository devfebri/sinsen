<!-- Modal -->
<div id="h3_md_ekspedisi_surat_pengantar" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Ekspedisi</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_ekspedisi_surat_pengantar_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nama Ekspedisi</th>
                            <th>Nama Pemilik</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function () {
                        h3_md_ekspedisi_surat_pengantar_datatable = $("#h3_md_ekspedisi_surat_pengantar_datatable").DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/md/h3/ekspedisi_surat_pengantar') ?>",
                                dataSrc: "data",
                                type: "POST",
                            },
                            columns: [
                                { data: 'nama_ekspedisi' }, 
                                { data: 'nama_pemilik' }, 
                                { data: 'no_telp' }, 
                                { data: 'alamat' }, 
                                { data: 'action', orderable: false, className: 'text-center', width: '3%' }, 
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
