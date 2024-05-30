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
        
      <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-sm btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-sm btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
      </div>
      <script>
      function set_download_excel_url(){
        query_string = new URLSearchParams({
          periode_filter_start : $('#periode_filter_start').val(),
          periode_filter_end : $('#periode_filter_end').val(),
        }).toString();

        $('#btn_download_excel').attr('href', 'h3/<?= $isi ?>/download_excel?' + query_string);
      }
      </script>
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label for="" class="control-label">Periode</label>
                <input type="text" id='periode_filter' class="form-control" readonly>
                <input type="hidden" id='periode_filter_start'>
                <input type="hidden" id='periode_filter_end'>
              </div>
              <script>
                $('#periode_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#periode_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#periode_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  monitoring_jatuh_tempo_ahm.draw();
                  set_download_excel_url();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_filter_start').val('');
                  $('#periode_filter_end').val('');
                  monitoring_jatuh_tempo_ahm.draw();
                  set_download_excel_url();
                });
              </script>
            </div>
          </div>
        </div>
        <table id="monitoring_jatuh_tempo_ahm" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tanggal Faktur</th>              
              <th>No Faktur</th>              
              <th>TOP DPP</th>              
              <th>TOP PPN</th>              
              <th>Total DPP</th>              
              <th>Total PPN</th>              
              <th>No. Giro</th>              
              <th>Tanggal Giro</th>              
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan='5' class='text-center'>Total</td>
              <td id='total_dpp' class='text-right'>Rp 0</td>
              <td id='total_ppn' class='text-right'>Rp 0</td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td colspan='5' class='text-center'>Grand Total</td>
              <td id='grand_total' class='text-right' colspan='2'>Rp 0</td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
        <script>
        $(document).ready(function(){
          monitoring_jatuh_tempo_ahm = $('#monitoring_jatuh_tempo_ahm').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/monitoring_jatuh_tempo_ahm') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.periode_filter_start = $('#periode_filter_start').val();
                  d.periode_filter_end = $('#periode_filter_end').val();
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;

                  // d.periode_filter_start = '2020-10-01';
                  // d.periode_filter_end = '2020-10-31';

                  axios.post('api/md/h3/monitoring_jatuh_tempo_ahm/get_total', Qs.stringify(d))
                  .then(function(res){
                    data = res.data;
                    $('#total_dpp').text(
                      accounting.formatMoney(data.total_dpp, 'Rp ', 0, '.', ',')
                    );

                    $('#total_ppn').text(
                      accounting.formatMoney(data.total_ppn, 'Rp ', 0, '.', ',')
                    );

                    $('#grand_total').text(
                      accounting.formatMoney(data.grand_total, 'Rp ', 0, '.', ',')
                    );
                  });
                }
            },
            rowCallback: function(row, data, index){
              if(data.top_dpp_filtered == 1){
                $('td', row).eq(3).css('background-color', 'yellow');
                $('td', row).eq(5).css('background-color', 'yellow');
              }
              if(data.top_ppn_filtered == 1){
                $('td', row).eq(4).css('background-color', 'yellow');
                $('td', row).eq(6).css('background-color', 'yellow');
              }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { 
                  data: 'invoice_date',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { data: 'invoice_number' }, 
                { 
                  data: 'dpp_due_date',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { 
                  data: 'ppn_due_date',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { 
                  data: 'total_dpp',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                  },
                  className: 'text-right'
                }, 
                { 
                  data: 'total_ppn',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                  },
                  className: 'text-right'
                }, 
                { 
                  data: 'kode_giro',
                  render: function(data){
                    if(data != null){
                      return data;
                    }
                    return '-';
                  },
                }, 
                { 
                  data: 'total_amount',
                  render: function(data){
                    if(data != null){
                      return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    }
                    return '-';
                  },
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