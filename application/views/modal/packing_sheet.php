<div id='packing_sheet'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Packing Sheet</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable_packing_sheet" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Packing Sheet</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        datatable_packing_sheet = $('#datatable_packing_sheet').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/packing_sheet') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_surat_pengantar = form_.shipping_list.id_surat_pengantar;
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_packing_sheet' }, 
                                { 
                                    data: 'tgl_packing_sheet',
                                    render: function(data){
                                        if(data != null){
                                            return moment(data).format('DD/MM/YYYY HH:mm:ss');
                                        }
                                        return data;
                                    }
                                }, 
                                { data: 'action', orderable: false, width: '3%', className: 'text-center' },
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>