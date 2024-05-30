<!-- Modal -->
<div id="h3_md_vendor_voucher_pengeluaran" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Vendor</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_vendor_voucher_pengeluaran_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Vendor</th>
                            <th>Alias</th>
                            <th>Tipe Vendor</th>
                            <th>No. Rekening</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_vendor_voucher_pengeluaran_datatable = $('#h3_md_vendor_voucher_pengeluaran_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/vendor_voucher_pengeluaran') ?>",
                            dataSrc: "data",
                            type: "POST",
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'vendor_name' },
                            { 
                                data: 'alias',
                                render: function(data){
                                    if(data != null){
                                        return data;
                                    }
                                    return '-';
                                }
                            },
                            { data: 'vendor_type' },
                            { 
                                data: 'no_rekening_tujuan', 
                                name: 'v.no_rekening',
                                render: function(data){
                                    if(data != null && data != ''){
                                        return data;
                                    }
                                    return '-';
                                }
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