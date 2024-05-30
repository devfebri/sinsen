<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
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
<body>
<div class="content-wrapper">
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header">
        <div class="btn-group">
          <button type="button" class="btn btn-flat btn-success">Download</button>
          <button type="button" class="btn btn-flat btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Download</span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="h3/<?= $isi ?>/download_excel">Excel</a></li>
          </ul>
        </div>
      </div>
      <script>
      function set_download_excel_url(){
        query_string = new URLSearchParams({
          tanggal_entry_start : $('#tanggal_entry_start').val(),
          tanggal_entry_end : $('#tanggal_entry_end').val(),
          tanggal_transaksi_start : $('#tanggal_transaksi_start').val(),
          tanggal_transaksi_end : $('#tanggal_transaksi_end').val(),
          tanggal_pembayaran_start : $('#tanggal_pembayaran_start').val(),
          tanggal_pembayaran_end : $('#tanggal_pembayaran_end').val(),
        }).toString();

        $('#btn_download_excel').attr('href', 'h3/<?= $isi ?>/download_excel?' + query_string);
      }
      </script>
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Tanggal Entry</label>
                <input type="text" id='tanggal_entry' class="form-control" readonly>
                <input type="hidden" id='tanggal_entry_start'>
                <input type="hidden" id='tanggal_entry_end'>
              </div>
              <script>
                $('#tanggal_entry').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#tanggal_entry_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#tanggal_entry_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  report_pembayaran_ke_vendor.draw();
                  set_download_excel_url();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#tanggal_entry_start').val('');
                  $('#tanggal_entry_end').val('');
                  report_pembayaran_ke_vendor.draw();
                  set_download_excel_url();
                });
              </script>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Tanggal Transaksi</label>
                <input type="text" id='tanggal_transaksi' class="form-control" readonly>
                <input type="hidden" id='tanggal_transaksi_start'>
                <input type="hidden" id='tanggal_transaksi_end'>
              </div>
              <script>
                $('#tanggal_transaksi').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#tanggal_transaksi_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#tanggal_transaksi_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  report_pembayaran_ke_vendor.draw();
                  set_download_excel_url();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#tanggal_transaksi_start').val('');
                  $('#tanggal_transaksi_end').val('');
                  report_pembayaran_ke_vendor.draw();
                  set_download_excel_url();
                });
              </script>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Tanggal Pembayaran</label>
                <input type="text" id='tanggal_pembayaran' class="form-control" readonly>
                <input type="hidden" id='tanggal_pembayaran_start'>
                <input type="hidden" id='tanggal_pembayaran_end'>
              </div>
              <script>
                $('#tanggal_pembayaran').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#tanggal_pembayaran_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#tanggal_pembayaran_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  report_pembayaran_ke_vendor.draw();
                  set_download_excel_url();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#tanggal_pembayaran_start').val('');
                  $('#tanggal_pembayaran_end').val('');
                  report_pembayaran_ke_vendor.draw();
                  set_download_excel_url();
                });
              </script>
            </div>
          </div>
        </div>
        <table id="report_pembayaran_ke_vendor" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Voucher</th>              
              <th>Tanggal Cair</th>              
              <th>Nama Vendor</th>              
              <th>Tanggal Pembayaran</th>              
              <th>Nominal Pembayaran</th>              
              <th>Nominal KU/CEK/BG</th>              
              <th>Keterangan</th>              
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan='5' class='text-center'>Total</td>
              <td id='total_jumlah_terutang' class='text-right'>Rp 0</td>
              <td id='total_nominal' class='text-right'>Rp 0</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
        <script>
        date_render = function(data){
          if(data != null) return moment(data).format('DD/MM/YYYY');
          return '-';
        }

        rupiah_render = function(data){
          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
        }

        $(document).ready(function(){
          report_pembayaran_ke_vendor = $('#report_pembayaran_ke_vendor').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/report_pembayaran_ke_vendor') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.tanggal_entry_start = $('#tanggal_entry_start').val();
                  d.tanggal_entry_end = $('#tanggal_entry_end').val();

                  d.tanggal_transaksi_start = $('#tanggal_transaksi_start').val();
                  d.tanggal_transaksi_end = $('#tanggal_transaksi_end').val();

                  d.tanggal_pembayaran_start = $('#tanggal_pembayaran_start').val();
                  d.tanggal_pembayaran_end = $('#tanggal_pembayaran_end').val();

                  axios.post('api/md/h3/report_pembayaran_ke_vendor/get_total', Qs.stringify(d))
                  .then(function(res){
                    data = res.data;
                    $('#total_jumlah_terutang').text(
                      accounting.formatMoney(data.total_jumlah_terutang, 'Rp ', 0, '.', ',')
                    );

                    $('#total_nominal').text(
                      accounting.formatMoney(data.total_nominal, 'Rp ', 0, '.', ',')
                    );
                  });
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { data: 'id_voucher_pengeluaran' }, 
                { 
                  data: 'tgl_cair',
                  render: date_render
                }, 
                { 
                  data: 'nama_penerima_dibayarkan_kepada',
                  width: '200px'
                }, 
                { 
                  data: 'tanggal_transaksi', 
                  render: date_render
                }, 
                { 
                  data: 'nominal',
                  render: rupiah_render,
                  className: 'text-right'
                }, 
                { 
                  data: 'total_amount',
                  render: rupiah_render,
                  className: 'text-right'
                },
                { 
                  data: 'deskripsi',
                  render: function(data){
                    if(data != null) return data;
                    return '-';
                  }
                }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>