<!-- Modal -->
<div id="tipe_kendaraan" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Tipe Kendaraan</h4>
            </div>
            <div class="modal-body">
                <table id="tipe_kendaraan_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe Kendaraan</th>
                            <th>Tipe AHM</th>
                            <th>Deskripsi AHM</th>
                            <th>Tipe Customer</th>
                            <th>Tipe Part</th>
                            <th>CC</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        tipe_kendaraan_datatable = $('#tipe_kendaraan_datatable').DataTable({
                            initComplete: function () {
                                axios.get('html/tipe_kendaraan_check_part_stock')
                                .then(function(res){
                                    $('#tipe_kendaraan_datatable_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                                    $('#tipe_kendaraan_datatable_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                                    $('#tipe_kendaraan_datatable_filter').prepend(res.data);
                                    $('#filter_tipe_kendaraan_check_part_stock').change(function(){
                                        tipe_kendaraan_datatable.draw();
                                    });
                                });
                            },
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "<?= base_url('api/dealer/tipe_kendaraan') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_kategori = $('#filter_tipe_kendaraan_check_part_stock').val();
                                    d.filter_tahun_kendaraan = $('#filter_tahun_kendaraan').val();
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: null, width: '1%', orderable: false },
                                { data: 'id_tipe_kendaraan' },
                                { data: 'tipe_ahm' },
                                { data: 'deskripsi_ahm' },
                                { data: 'tipe_customer' },
                                { data: 'tipe_part' },
                                { data: 'cc_motor' },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false },
                            ],
                        });

                        tipe_kendaraan_datatable.on('draw.dt', function() {
                            var info = tipe_kendaraan_datatable.page.info();
                            tipe_kendaraan_datatable.column(0, {
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