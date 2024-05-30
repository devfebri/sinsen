<!-- Modal -->
<div id="modal-dealer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Dealer</h4>
        </div>
        <div class="modal-body">
            <table id="datatable-dealer" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                datatable_dealer = $('#datatable-dealer').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/dealer') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.type_of_query = 'exclude_selected_dealers';
                            d.id_dealers = _.map(vueForm.dealers, function(d){
                                return d.id_dealer;
                            });
                        }
                    },
                    columns: [
                        { data: 'nama_dealer' },
                        { data: 'aksi', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>