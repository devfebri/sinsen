<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <?= $breadcrumb ?>
  </section>
  <section class="content">
      <div class="box">
        <div class="box-body">
          <div class="container-fluid">
            <div class="row"> 
              <div class="col-sm-2">
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="" class="control-label">Total Lost</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <span id='total_lost_of_sales' class='bold'>Rp 0</span>
                    </div>
                  </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="" class="control-label">Filter Part</label>
                  <div class="input-group">
                    <input id='filter_part' type="text" class="form-control" readonly>
                    <input type="hidden" id='filter_id_part'>
                    <div class="input-group-btn">
                      <button id='btn-pilih-filter-part' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_dealer_filter_part_reason_demand'><i class="fa fa-search"></i></button>
                      <button id='btn-hapus-filter-part' class="btn btn-flat btn-danger hide" onclick='return filter_part_dihapus()'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_dealer_filter_part_reason_demand'); ?> 
              <script>
                function filter_part_dipilih(data){

                  $('#filter_part').val(data.id_part + ' - ' + data.nama_part);
                  $('#filter_id_part').val(data.id_part);

                  $('#btn-hapus-filter-part').removeClass('hide');
                  $('#btn-pilih-filter-part').addClass('hide');
                  reason_demand.draw();
                }

                function filter_part_dihapus(data){
                  $('#filter_part').val('');
                  $('#filter_id_part').val('');
                  reason_demand.draw();

                  $('#btn-hapus-filter-part').addClass('hide');
                  $('#btn-pilih-filter-part').removeClass('hide');

                  return false;
                }
              </script>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="" class="control-label">Periode</label>
                  <input id='filter_date_record_demand' type="text" class='form-control' readonly>
                  <input type="hidden" id="filter_date_record_demand_start">
                  <input type="hidden" id="filter_date_record_demand_end">
                </div>
                <script>
                  $('#filter_date_record_demand').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#filter_date_record_demand_start').val(start.format('YYYY-MM-DD'));
                    $('#filter_date_record_demand_end').val(end.format('YYYY-MM-DD'));
                    $('#report_button').removeClass('invisible');
                    reason_demand.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#filter_date_record_demand_start').val('');
                    $('#filter_date_record_demand_end').val('');
                    $('#report_button').addClass('invisible');
                    reason_demand.draw();
                  });
                </script>
              </div>
              <div class="col-sm-3">
                <div class="row">
                  <div class="col-sm-12">
                    <label for="" class="control-label invisible">Report</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <button id='report_button' onclick='return report()' class="btn btn-flat btn-success invisible">Report</button>
                  </div>
                </div>
                <script>
                  function report(){
                      start_date = $('#filter_date_record_demand_start').val();
                      end_date = $('#filter_date_record_demand_end').val();

                      window.open('dealer/h3_dealer_reason_demand/report?start_date=' + start_date + '&end_date=' + end_date, '_blank');
                  }
                </script>
              </div>
            </div>
          </div>
          <table id="reason_demand" class="table table-bordered table-hover table-condensed">
            <thead>
              <tr>
                <th>No.</th>
                <th>Tanggal Part Lost</th>
                <th>Part Number</th>
                <th>Part Deskripsi</th>
                <th>Qty</th>
                <th>Lost of Sales Amount (IDR)</th>
                <th>Alasan</th>
              </tr>
            </thead>
            <tbody>
            <script>
              $(document).ready(function() {
                reason_demand = $('#reason_demand').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/record_demand') ?>",
                        dataSrc: function(json){
                          $('#total_lost_of_sales').text(accounting.formatMoney(json.total_lost, "Rp ", 0, ".", ","));
                          return json.data;
                        },
                        type: "POST",
                        data: function(data) {
                            data.filter_part_record_demand = $('#filter_id_part').val();

                            start_date = $('#filter_date_record_demand_start').val();
                            end_date = $('#filter_date_record_demand_end').val();
                            if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                                data.filter_date_record_demand = true;
                                data.start_date = start_date;
                                data.end_date = end_date;
                            }
                        }
                    },
                    createdRow: function(row, data, index) {
                        $('td', row).addClass('align-middle');
                    },
                    columns: [
                      { data: 'index', width: '3%', orderable: false, },
                      { data: 'created_at'},
                      { data: 'id_part'},
                      { data: 'nama_part'},
                      { data: 'qty'},
                      { 
                        data: 'lost_of_sales',
                        render: function(data){
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        }
                      },
                      { data: 'note_field'},
                    ],
                });
              });
            </script>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
  </section>
</div>