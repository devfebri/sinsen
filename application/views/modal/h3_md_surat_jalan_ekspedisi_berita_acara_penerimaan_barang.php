<!-- Modal -->
<div id="h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Surat Jalan Ekspedisi</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Surat Jalan Ekspedisi</th>
                        <th>Nama Driver</th>
                        <th>Nomor Plat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable = $('#h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/surat_jalan_ekspedisi_berita_acara_penerimaan_barang') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(data){
                            data.id_vendor = form_.berita_acara.id_vendor;
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'no_surat_jalan_ekspedisi' },
                        { data: 'nama_driver' },
                        { data: 'no_plat' },
                        { data: 'action', orderable: false, widht: '3%', className: 'text-center' }
                    ],
                });

                h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable.on('draw.dt', function() {
                    var info = h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable.page.info();
                    h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable.column(0, {
                        search: 'applied',
                        order: 'applied',
                        page: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + info.start + ".";
                    });
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>