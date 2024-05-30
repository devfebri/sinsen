<input type="hidden" id='no_faktur_keterangan_monitor_plafon'>
<!-- Modal -->
<div id="h3_md_open_keterangan_monitor_plafon" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Giro</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_open_keterangan_monitor_plafon_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor BG</th>
                            <th>Nama Bank</th>
                            <th>Tanggal Jatuh Tempo BG</th>
                            <th>Jumlah BG</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_open_keterangan_monitor_plafon_datatable = $('#h3_md_open_keterangan_monitor_plafon_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        searching: false,
                        ordering: false,
                        ajax: {
                            url: "<?= base_url('api/md/h3/open_keterangan_monitor_plafon') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.no_faktur = $('#no_faktur_keterangan_monitor_plafon').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'nomor_bg' },
                            { data: 'nama_bank_bg' },
                            { 
                                data: 'tanggal_jatuh_tempo_bg',
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { 
                                data: 'jumlah_pembayaran',
                                render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', ',', '.');
                                }
                            },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>