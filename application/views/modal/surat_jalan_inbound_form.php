<!-- Modal -->
<div id="surat_jalan_inbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Surat Jalan</h4>
        </div>
        <div class="modal-body">
            <table id="surat_jalan_inbound_form_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Surat Jalan</th>
                    <th>Tanggal Surat Jalan</th>
                    <th>Outbound Form for Fulfillment</th>
                    <th>Tanggal Outbound Form for Fulfillment</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                surat_jalan_inbound_form_datatable = $('#surat_jalan_inbound_form_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/surat_jalan_inbound_form') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: null, width: '3%', orderable: false },
                        { data: 'id_surat_jalan' },
                        { data: 'tanggal_surat_jalan', name: 'sj.created_at' },
                        { data: 'id_outbound_form' },
                        { data: 'tanggal_outbound', name: 'f.created_at' },
                        { data: 'action', width: '1%', className: 'text-center', orderable: false }
                    ],
                });

                surat_jalan_inbound_form_datatable.on('draw.dt', function() {
                  var info = surat_jalan_inbound_form_datatable.page.info();
                  surat_jalan_inbound_form_datatable.column(0, {
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