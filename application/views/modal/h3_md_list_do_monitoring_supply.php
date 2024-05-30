<!-- Modal -->
<div id="h3_md_list_do_monitoring_supply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 90%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">List Nomor DO</h4>
            </div>
            <div class="modal-body">
              <table id="h3_md_list_do_monitoring_supply_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                  <thead>
                      <tr>
                          <th>No.</th>
                          <th>No. DO</th>
                          <th>Tanggal Proses</th>
                          <th>Tanggal Picking List</th>
                          <th>Tanggal Scan</th>
                          <th>Tanggal Faktur</th>
                          <th>Tanggal PS</th>
                          <th>Tanggal Shipping</th>
                          <th>Lead Time SO</th>
                          <th>Lead Time DO</th>
                          <th>Status DO</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <script>
              $(document).ready(function() {
                h3_md_list_do_monitoring_supply_datatable = $('#h3_md_list_do_monitoring_supply_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/list_do_monitoring_supply') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                        d.id_sales_order = $('#selected_id_sales_order').val();
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%' }, 
                        { data: 'id_do_sales_order' },
                        { data: 'tanggal' },
                        { 
                            data: 'tanggal_picking',
                            render: function(data){
                                if(data == null){
                                    return '-';
                                }
                                return data;
                            }
                        },
                        { 
                            data: 'tanggal_scan',
                            render: function(data){
                                if(data == null){
                                    return '-';
                                }
                                return data;
                            }
                        },
                        { 
                            data: 'tanggal_faktur',
                            render: function(data){
                                if(data == null){
                                    return '-';
                                }
                                return data;
                            }
                        },
                        { 
                            data: 'tanggal_packing',
                            render: function(data){
                                if(data == null){
                                    return '-';
                                }
                                return data;
                            }
                        },
                        { 
                            data: 'tanggal_shipping',
                            render: function(data){
                                if(data == null){
                                    return '-';
                                }
                                return data;
                            }
                        },
                        { 
                            data: 'lead_time_so',
                            render: function(data){
                                return humanizeDuration(data, { language: "id", round: true });
                            }
                        },
                        { 
                            data: 'lead_time_do',
                            render: function(data){
                                return humanizeDuration(data, { language: "id", round: true });
                            }
                        },
                        { 
                            data: 'status',
                            render: function(data){
                                if(data == null){
                                    return '-';
                                }
                                return data;
                            }
                        },
                    ],
                });
              });
              </script>
            </div>
        </div>
    </div>
</div>