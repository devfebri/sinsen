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
        <a href="h3/<?= $isi ?>/download_excel" id='btn_download_excel'>
          <button class="btn btn-flat btn-sm btn-success">Download Excel</button>
        </a>
      </div>
      <script>
      function set_download_excel_url(){
        query_string = new URLSearchParams({
          tanggal_faktur_start : $('#tanggal_faktur_start').val(),
          tanggal_faktur_end : $('#tanggal_faktur_end').val(),
          tanggal_jatuh_tempo_start : $('#tanggal_jatuh_tempo_start').val(),
          tanggal_jatuh_tempo_end : $('#tanggal_jatuh_tempo_end').val(),
        }).toString();

        $('#btn_download_excel').attr('href', 'h3/<?= $isi ?>/download_excel?' + query_string);
      }
      </script>
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Tanggal Faktur</label>
                <input type="text" id='tanggal_faktur' class="form-control" readonly>
                <input type="hidden" id='tanggal_faktur_start'>
                <input type="hidden" id='tanggal_faktur_end'>
              </div>
              <script>
                $('#tanggal_faktur').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#tanggal_faktur_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#tanggal_faktur_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  report_penerimaan_sparepart.draw();
                  set_download_excel_url();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#tanggal_faktur_start').val('');
                  $('#tanggal_faktur_end').val('');
                  report_penerimaan_sparepart.draw();
                  set_download_excel_url();
                });
              </script>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Tanggal Jatuh Tempo</label>
                <input type="text" id='tanggal_jatuh_tempo' class="form-control" readonly>
                <input type="hidden" id='tanggal_jatuh_tempo_start'>
                <input type="hidden" id='tanggal_jatuh_tempo_end'>
              </div>
              <script>
                $('#tanggal_jatuh_tempo').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#tanggal_jatuh_tempo_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#tanggal_jatuh_tempo_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  report_penerimaan_sparepart.draw();
                  set_download_excel_url();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#tanggal_jatuh_tempo_start').val('');
                  $('#tanggal_jatuh_tempo_end').val('');
                  report_penerimaan_sparepart.draw();
                  set_download_excel_url();
                });
              </script>
            </div>
          </div>
        </div>
        <table id="report_penerimaan_sparepart" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Faktur</th>              
              <th>Tgl Faktur</th>              
              <th>Tgl Jatuh Tempo</th>              
              <th>Kode Part</th>              
              <th>Deskripsi Part</th>              
              <th>Qty</th>              
              <th>Harga Satuan</th>              
              <th>Diskon</th>              
              <th>PPN</th>              
              <th>Total Harga</th>              
              <th>Qty Scan</th>              
              <th>No. Penerimaan</th>              
              <th>Tgl Penerimaan</th>              
              <th>No. Plat</th>              
              <th>Ekspedisi</th>              
              <th>No. Surat Jalan Ekspedisi</th>              
              <th>Tgl. Surat Jalan Ekspedisi</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        date_render = function(data){
          if(data != null) return moment(data).format('DD/MM/YYYY');
          return '-';
        }
        $(document).ready(function(){
          report_penerimaan_sparepart = $('#report_penerimaan_sparepart').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/report_penerimaan_sparepart') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.tanggal_faktur_start = $('#tanggal_faktur_start').val();
                  d.tanggal_faktur_end = $('#tanggal_faktur_end').val();

                  d.tanggal_jatuh_tempo_start = $('#tanggal_jatuh_tempo_start').val();
                  d.tanggal_jatuh_tempo_end = $('#tanggal_jatuh_tempo_end').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { data: 'invoice_number' }, 
                { 
                  data: 'invoice_date',
                  render: date_render
                }, 
                { 
                  data: 'tanggal_jatuh_tempo',
                  render: date_render
                },
                { data: 'id_part' }, 
                { data: 'nama_part', width: '200px' }, 
                { 
                  data: 'quantity',
                  render: function(data){
                    return accounting.format(data, 0, '.',',');
                  }
                }, 
                { 
                  data: 'price',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.',',');
                  },
                  width: '100px'
                }, 
                { 
                  data: 'diskon',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 2, '.',',');
                  },
                  width: '100px'
                }, 
                { 
                  data: 'ppn',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.',',');
                  },
                  width: '100px'
                }, 
                { 
                  data: 'total_harga',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.',',');
                  },
                  width: '100px'
                }, 
                { 
                  data: 'qty_scan',
                  render: function(data){
                    return accounting.format(data, 0, '.',',');
                  }
                }, 
                { 
                  data: 'no_penerimaan_barang',
                  render: function(data){
                    if(data != null) return data;
                    return '-';
                  }
                }, 
                { 
                  data: 'tanggal_penerimaan',
                  render: date_render
                }, 
                {
                  data: 'no_plat', 
                  width: '80px',
                  render: function(data){
                    if(data != null) return data;
                    return '-';
                  }
                }, 
                { 
                  data: 'nama_ekspedisi',
                  render: function(data){
                    if(data != null) return data;
                    return '-';
                  } 
                }, 
                { 
                  data: 'no_surat_jalan_ekspedisi',
                  render: function(data){
                    if(data != null) return data;
                    return '-';
                  }
                }, 
                { 
                  data: 'tgl_surat_jalan_ekspedisi',
                  render: date_render
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