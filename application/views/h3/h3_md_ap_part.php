<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/jquery.min.js") ?>"></script>
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
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">No. Transaksi</label>
                    <input id='filter_no_transaksi' type="text" class="form-control">
                  </div>
                  <script>
                    $(document).ready(function(){
                      $('#filter_no_transaksi').on('keyup', _.debounce(function(e){
                        ap_part.draw();
                      }, 300));
                    });
                  </script>
                </div>    
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Nama Divisi</label>
                    <input id='filter_nama_divisi'type="text" class="form-control">
                  </div>                
                  <script>
                    $(document).ready(function(){
                      $('#filter_nama_divisi').on('keyup', _.debounce(function(e){
                        ap_part.draw();
                      }, 300));
                    });
                  </script>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Nama Partner</label>
                    <input id='filter_nama_partner'type="text" class="form-control">
                  </div>
                  <script>
                    $(document).ready(function(){
                      $('#filter_nama_partner').on('keyup', _.debounce(function(e){
                        ap_part.draw();
                      }, 300));
                    });
                  </script>  
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Tgl Transaksi</label>
                    <input id='tgl_transaksi_filter' type="text" class="form-control" readonly>
                    <input id='tgl_transaksi_filter_start' type="hidden" disabled>
                    <input id='tgl_transaksi_filter_end' type="hidden" disabled>
                  </div>      
                </div>
              </div>          
            </div>
            <script>
              $('#tgl_transaksi_filter').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              }, function(start, end, label) {
                $('#tgl_transaksi_filter_start').val(start.format('YYYY-MM-DD'));
                $('#tgl_transaksi_filter_end').val(end.format('YYYY-MM-DD'));
                ap_part.draw();
              }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
              }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#tgl_transaksi_filter_start').val('');
                $('#tgl_transaksi_filter_end').val('');
                ap_part.draw();
              });
            </script>
          </div>
        </div>
        <table id="ap_part" class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class='align-middle'>No.</th>              
              <th class='align-middle'>Referensi</th>              
              <th class='align-middle'>Jenis Transaksi</th>              
              <th class='align-middle'>Tanggal Transaksi</th>              
              <th class='align-middle'>Tanggal Jatuh Tempo</th>              
              <th class='align-middle'>Nama Vendor</th>              
              <th class='align-middle'>Total Bayar</th>              
              <th class='align-middle'>Total Sudah Di Bayar</th>              
              <th class='align-middle'>Sisa Pembayaran</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          ap_part = $('#ap_part').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/ap_part') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.filter_no_transaksi = $('#filter_no_transaksi').val();
                  d.filter_nama_divisi = $('#filter_nama_divisi').val();
                  d.filter_nama_partner = $('#filter_nama_partner').val();
                  d.tgl_transaksi_filter_start = $('#tgl_transaksi_filter_start').val();
                  d.tgl_transaksi_filter_end = $('#tgl_transaksi_filter_end').val();
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { data: 'referensi' }, 
                { 
                  data: 'jenis_transaksi',
                  render: function(data){
                    return data.split('_')
                    .map(function(word){
                      return word.charAt(0).toUpperCase() + word.slice(1);
                    })
                    .join(' ');
                  }
                }, 
                { data: 'tanggal_transaksi' }, 
                { data: 'tanggal_jatuh_tempo' }, 
                { data: 'nama_vendor' }, 
                { 
                  data: 'total_bayar', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'total_sudah_dibayar', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'sisa_pembayaran', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
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