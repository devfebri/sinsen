<!-- Modal -->
<div id="h3_salesman_target_salesman" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Salesman</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <form class='form-horizontal'>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4 align-middle">Departmen</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input id='nama_department_filter' type="text" class="form-control" disabled>
                                        <input id='id_department_filter' type="hidden" disabled>
                                        <div class="input-group-btn">
                                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_department_filter_salesman_target_salesman'>
                                            <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>       
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4 align-middle">Nama Karyawan</label>
                                <div class="col-sm-8">
                                    <input id='nama_karyawan_filter' type="text" class="form-control">
                                </div>
                            </div>                
                            <script>
                            $(document).ready(function(){
                                $('#nama_karyawan_filter').on("keyup", _.debounce(function(){
                                    h3_salesman_target_salesman_datatable.draw();
                                }, 500));
                            });
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4 align-middle">Jabatan</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input id='nama_jabatan_filter' type="text" class="form-control" disabled>
                                        <input id='id_jabatan_filter' type="hidden" disabled>
                                        <div class="input-group-btn">
                                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_jabatan_filter_salesman_target_salesman'>
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>       
                        </div>
                    </div>
                </form>
            </div>
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_salesman_target_salesman_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Salesman</th>
                        <th>Jabatan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_salesman_target_salesman_datatable = $('#h3_salesman_target_salesman_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    searching: false,
                    ajax: {
                        url: '<?= base_url('api/md/h3/salesman_target_salesman') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(d){
                            d.id_department_filter = $('#id_department_filter').val();
                            d.id_jabatan_filter = $('#id_jabatan_filter').val();
                            d.nama_karyawan_filter = $('#nama_karyawan_filter').val();
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'nama_lengkap' },
                        { data: 'jabatan' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });

                h3_salesman_target_salesman_datatable.on('draw.dt', function() {
                    var info = h3_salesman_target_salesman_datatable.page.info();
                    h3_salesman_target_salesman_datatable.column(0, {
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