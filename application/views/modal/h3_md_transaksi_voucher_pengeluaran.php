<!-- Modal -->
<div id="h3_md_transaksi_voucher_pengeluaran" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Transaksi</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_transaksi_voucher_pengeluaran_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Referensi</th>
                            <th>Jenis Transaksi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Nama Vendor</th>
                            <th>Sisa Hutang</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_transaksi_voucher_pengeluaran_datatable = $('#h3_md_transaksi_voucher_pengeluaran_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/transaksi_voucher_pengeluaran') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.items_selected = _.chain(form_.items)
                                .map(function(row){
                                    return _.pick(row, [
                                        'id_referensi', 'jenis_transaksi'
                                    ]);
                                })
                                .value();
                                
                                d.tipe_penerima = form_.voucher_pengeluaran.tipe_penerima;
                                d.id_dibayarkan_kepada = form_.voucher_pengeluaran.id_dibayarkan_kepada;
                            }
                        },
                        columns: [
                            { data: 'index', width: '3%', orderable: false },
                            { data: 'referensi' },
                            { 
                                data: 'jenis_transaksi',
                                render: function(data){
                                    return data.replaceAll('_', ' ').toUpperCase();
                                }
                            },
                            { data: 'tanggal_transaksi' },
                            { data: 'tanggal_jatuh_tempo' },
                            { data: 'nama_vendor' },
                            { 
                                data: 'jumlah_terutang', 
                                render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                                },
                                className: 'text-right',
                            },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>