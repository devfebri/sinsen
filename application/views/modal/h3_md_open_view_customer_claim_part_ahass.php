<!-- Modal -->
<input type="hidden" id='id_claim_part_ahass_for_open_view_customer'>
<div id="h3_md_open_view_customer_claim_part_ahass" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Customer</h4>
            </div>
            <div class="modal-body">
              <table id="h3_md_open_view_customer_claim_part_ahass_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                  <thead>
                      <tr>
                          <th>No.</th>
                          <th>Kode Customer</th>
                          <th>Nama Customer</th>
                          <th>Alamat Customer</th>
                      </tr>
                  </thead>
                  <tbody></tbody>
              </table>
              <script>
              $(document).ready(function() {
                h3_md_open_view_customer_claim_part_ahass_datatable = $('#h3_md_open_view_customer_claim_part_ahass_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/open_view_customer_claim_part_ahass') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_claim_part_ahass = $('#id_claim_part_ahass_for_open_view_customer').val();
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%' }, 
                        { data: 'kode_dealer_md' },
                        { data: 'nama_dealer' },
                        { data: 'alamat' },
                    ],
                });
              });
              </script>
            </div>
        </div>
    </div>
</div>