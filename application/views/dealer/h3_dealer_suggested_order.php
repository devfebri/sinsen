<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>" type="text/javascript"></script>
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

  <section class="content" id="header1">
    <div class="box">
          <div class="box-header with-border">
              <div class="container-fluid">
                <div class="row">
                  <div class="form-group row">
                    <?php if ($cek_dealer_auto->autofulfillment_md== 1 ){ ?>
                      <label class="col-sm-3 col-form-label">Nonaktifkan fitur autofulfillment dari MD?</label>
                      <div class="col-sm-6">
                        <button id="non_active_autofulfillment" onclick="non_active_autofulfillment()" class="btn btn-danger btn-flat btn-flat no-margin" type="button">Nonactive</button>
                      </div>
                    <?php }else{?>
                      <label class="col-sm-3 col-form-label">Aktifkan fitur autofulfillment dari MD?</label>
                      <div class="col-sm-6">
                        <button id="active_autofulfillment" onclick="active_autofulfillment()" class="btn btn-primary btn-flat btn-flat no-margin" type="button">Active</button>
                      </div>
                    <?php }?>
                  </div>
                </div>
              </div>
          </div><!-- /.box-header -->
    </div><!-- /.box -->
  </section>

  <section class="content" id="header2" style="margin-top: -150px;">
      <div class="box">
        <div class="box-header with-border">
            <div class="container-fluid">
              <div class="row">
                <div class="pull-left margin">
                  <button id="perbarui_suggested_order" class="btn btn-success btn-flat btn-flat no-margin" type="button">Perbarui Suggested Order</button>
                  <script>
                    $(document).ready(function(){
                      $('#perbarui_suggested_order').click(function(e){
                        toastr.warning('Perhitungan suggested order sedang dilakukan mohon menunggu.');
                        $('#loading-overlay').show();
                        axios.get('dealer/h3_dealer_suggested_order/generate_suggested_order', {
                          params: {
                            tanggal_start_periode: $('#tanggal_start_periode').val(),
                          }
                        })
                        .then(function(res){
                          toastr.success('Perhitungan suggested order telah selesai dilakukan.');
                          $('#loading-overlay').hide();
                          suggested_order.draw();
                        })
                        .catch(function(err){
                          toastr.error(err);
                        });
                      });
                    });
                  </script>
                </div>
                <div class="pull-left margin">
                  <a href="dealer/h3_dealer_suggested_order/export">
                    <button class="btn btn-success btn-flat btn-flat no-margin">Export</button>
                  </a>
                </div>
                <div class="pull-left margin">
                  <input id="tanggal_start_periode" type="text" class="form-control datepicker" value="<?= date('Y-m-d', time()) ?>">
                </div>
                <script>
                  $(document).ready(function(){
                    $('#tanggal_start_periode').change(function(e){
                      start_point = $("#tanggal_start_periode").datepicker("getDate");
                      six_weeks_before = new Date(start_point.getTime() - (6*(60*60*24*7*1000)));
                      start_point_formatted = $.datepicker.formatDate("dd/mm/yy", start_point);
                      six_weeks_before_formatted = $.datepicker.formatDate("dd/mm/yy", six_weeks_before);
                      $html = '<span>Perhitungan analisis ranking dimulai di periode ' + start_point_formatted + ' - ' + six_weeks_before_formatted + '</span>';
                      $('#date_info').html($html);
                    });
                  });
                </script>
              </div>
              <div id="date_info" class="row text-center h4">
                  <span>Perhitungan analisis ranking dimulai di periode <?= date('d/m/Y', time()) ?> - <?= date('d/m/Y', time() - (6*604800)) ?></span>
              </div>
            </div>
        </div><!-- /.box-header -->
        <div id='loading-overlay' class="overlay" style="display: none;">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="suggested_order" class="table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Part Number</th>
                  <th>Part Deskripsi</th>
                  <th>Rata - rata 6 Minggu</th>
                  <th>Akumulasi Qty</th>
                  <th>Akumulasi %</th>
                  <th>Rank</th>
                  <th>W-1</th>
                  <th>W-2</th>
                  <th>W-3</th>
                  <th>W-4</th>
                  <th>W-5</th>
                  <th>W-6</th>
                  <th>Stock On Hand</th>
                  <th>SIM Part</th>
                  <th>Stock On Order</th>
                  <th>Stock In Transit</th>
                  <th>Stock Days</th>
                  <th>Suggested Order</th>
                  <th>Adjusted Order</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          suggested_order = $('#suggested_order').DataTable({
              initComplete: function() {
                  $('#suggested_order_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                  $('#suggested_order_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                  axios.get('html/filter_suggested_order')
                      .then(function(res) {
                          $('#suggested_order_filter').prepend(res.data);

                          $('#filter_prioritas_order_suggested_order').change(function() {
                              suggested_order.draw();
                          });
                      });
              },
              processing: true,
              serverSide: true,
              ordering: false,
              order: [],
              ajax: {
                  url: "<?= base_url('api/suggested_order_portal') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    kelompok_part = [];
                    $('.kelompok_part_checkbox:checkbox:checked').each(function(i){
                      kelompok_part[i] = $(this).val();
                    });
                    if(kelompok_part.length > 0){
                      d.kelompok_part = kelompok_part;
                    }

                    d.filter_order = $('#filter_prioritas_order_suggested_order').val();
                  }
              },
              createdRow: function (row, data, index) {
                $('td', row).addClass('align-middle');
              },
              columns: [
                  { data: 'index', width: '3%', orderable: false },
                  { 
                    data: 'id_part', 
                    width: '150px',
                    
                  },
                  { 
                    data: 'nama_part', 
                    width: '200px',
                  },
                  { 
                    data: 'avg_six_weeks',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'akumulasi_qty',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'akumulasi_persen',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'rank'
                  },
                  { 
                    data: 'w1',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w2',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w3',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w4',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w5',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'w6',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'stock',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'min_stok',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'on_order',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'in_transit',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'stock_days',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { 
                    data: 'suggested_order',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  },
                  { data: 'adjust_order' },
              ],
          }).on('preDraw', function () {
            $('#loading-overlay').show();
          })
          .on('draw.dt', function () {
            $('#loading-overlay').hide();
          });
        });
      </script>
  </section>

  <script>
    <?php if($cek_dealer_auto->autofulfillment_md=='1'){?>
      $('#header2').hide();
    <?php }else{?>
      $('#header2').show();
    <?php }?>

    const active_autofulfillment = () => {
      if(confirm('Yakin akan mengaktifkan fitur autofulfillment MD? Jika fitur diaktifkan, MD dapat melakukan suggest order dan otomatis membuat PO Reguler/Fix.')){
        $.ajax({
          type: "POST",
          url: "<?= base_url('dealer/h3_dealer_suggested_order/active_autofulfillment') ?>",
          dataType: "JSON",
          beforeSend: function(){ 
            $('#active_autofulfillment').attr('disabled', true);
            // $('#active_autofulfillment').html('Processing');
          },
          success: function(Result) {
              const {
                  status,
                  message,
                  data
              } = Result
              if (status) {
                  alert('Berhasil mengaktifkan fitur autofulfillment MD');
              } else {
                  alert('Gagal mengaktifkan fitur autofulfillment MD');
              }
            // $('#active_autofulfillment').attr('disabled', false);
            // $('#active_autofulfillment').html('Active');
            window.location.reload();
          },
          error: function(x, y, z) {
              alert('Gagal mengaktifkan fitur autofulfillment MD');
          },
        });
      }
    }

    const non_active_autofulfillment = () => {
      if(confirm('Yakin akan mengaktifkan fitur autofulfillment MD? Jika fitur diaktifkan, MD dapat melakukan suggest order dan otomatis membuat PO Reguler/Fix.')){
        $.ajax({
          type: "POST",
          url: "<?= base_url('dealer/h3_dealer_suggested_order/non_active_autofulfillment') ?>",
          dataType: "JSON",
          beforeSend: function(){ 
            $('#non_active_autofulfillment').attr('disabled', true);
            $('#non_active_autofulfillment').html('Processing');
          },
          success: function(Result) {
              const {
                  status,
                  message,
                  data
              } = Result
              if (status) {
                  alert('Berhasil menonaktifkan fitur autofulfillment MD');
              } else {
                  alert('Gagal menonaktifkan fitur autofulfillment MD');
              }
              
            // $('#non_active_autofulfillment').attr('disabled', false);
            // $('#non_active_autofulfillment').html('Non Active');
            window.location.reload();
          },
          error: function(x, y, z) {
              alert('Gagal menonaktifkan fitur autofulfillment MD');
          },
        });
      }
    }
  </script>
</div>