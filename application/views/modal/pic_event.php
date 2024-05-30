<div id="pic_event" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">PIC Event</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="pic_event_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        pic_event_datatable = $('#pic_event_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/pic_event') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: null, width: '3%', orderable: false },
                                { data: 'nik' },
                                { data: 'nama_lengkap' }, 
                                { data: 'jabatan' },
                                {  data: 'action', width: '3%', orderable: false, className: 'text-center' }
                            ],
                        });

                        pic_event_datatable.on('draw.dt', function() {
                            var info = pic_event_datatable.page.info();
                            pic_event_datatable.column(0, {
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