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
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-10">
              <div class="form-inline">
                <div class="form-group" style='margin-right: 10px;'>
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="" class="control-label">Periode</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <input type="text" id='periode' class="form-control" readonly>
                      <input type="hidden" id='periode_start'>
                      <input type="hidden" id='periode_end'>
                    </div>
                  </div>
                </div>
                <script>
                  $('#periode').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $('#periode_start').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#periode_end').val(picker.endDate.format('YYYY-MM-DD'));
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_start').val('');
                    $('#periode_end').val('');
                    hide_iframe();
                  });
                </script>
                <div class="form-group" style='margin-right: 10px;'>
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="" class="control-label">Group by</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <select id="group" class="form-control">
                        <option value="">-</option>
                        <option value="tanggal">Tanggal</option>
                        <option value="customer">Customer</option>
                        <option value="salesman">Salesman</option>
                        <option value="kelompok_barang">Kelompok Barang</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group" style='margin-right: 10px;'>
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="" class="control-label invisible">Preview</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <button id='preview-btn' type='button' class="btn-flat btn btn-success">Preview</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <script>
            $(document).ready(function(){
              $('#preview-btn').on('click', function(e){
                e.preventDefault();
                periode_start = $('#periode_start').val();
                periode_end = $('#periode_end').val();
                group = $('#group').val();

                if(periode_start == '' || periode_end == ''){
                  toastr.warning('Pilih periode terlebih dahulu');
                  return;
                }

                query_string = new URLSearchParams({
                  periode_start : periode_start,
                  periode_end : periode_end,
                  group : group,
                }).toString();

                url = 'h3/<?= $isi ?>/pdf?' + query_string;

                show_iframe(url);
              });
            });

            function show_iframe(url){
              $('#iframe_view').show();
              $('#iframe_view').attr('src', url)
            }

            function hide_iframe(){
              $('#iframe_view').hide();
              $('#iframe_view').attr('src', '')
            }
          </script>
          <div class="container-fluid no-padding" style='margin-top: 10px;'>
            <iframe id='iframe_view' style='display: none;' width='100%' height='800px' frameborder='0'></iframe>
          </div>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>