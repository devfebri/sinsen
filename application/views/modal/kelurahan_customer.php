<div id="kelurahan_customer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Kelurahan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="kelurahan_customer_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten</th>
                            <th>Provinsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        kelurahan_customer_datatable = $('#kelurahan_customer_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/kelurahan_customer') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%'},
                                { data: 'kelurahan' }, 
                                { data: 'kecamatan' },
                                { data: 'kabupaten' },
                                { data: 'provinsi' },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false }
                            ],
                        });

                        kelurahan_customer_datatable.on('draw.dt', function() {
                            var info = kelurahan_customer_datatable.page.info();
                            kelurahan_customer_datatable.column(0, {
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