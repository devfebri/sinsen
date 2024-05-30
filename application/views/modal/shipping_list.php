<div id='shipping_list'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Surat Pengantar</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable_shipping_list" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Shipping List</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        datatable_shipping_list = $('#datatable_shipping_list').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/shipping_list') ?>",
                                dataSrc: "data",
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_surat_pengantar' }, 
                                { 
                                    data: 'tanggal',
                                    render: function(data){
                                        if(data != null){
                                            return moment(data).format('DD/MM/YYYY');
                                        }
                                        return data;
                                    }
                                }, 
                                { data: 'action', width: '3%', orderable: false, className: 'text-center' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>