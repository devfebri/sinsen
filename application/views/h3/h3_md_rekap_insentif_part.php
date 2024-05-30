<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
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
        <h3 class="box-title">
          <a style='display: none;' id='btn_download_excel' href="h3/<?= $isi ?>/download_excel">
            <button class="btn btn-success btn-flat">Download Excel</button>
          </a>
        </h3>
      </div>
      <script>
        function set_download_excel_url(){
          query_string = new URLSearchParams({
            periode_filter_start : $('#periode_filter_start').val(),
            periode_filter_end : $('#periode_filter_end').val(),
            id_collector : $('#id_collector').val(),
          }).toString();

          $('#btn_download_excel').attr('href', 'h3/<?= $isi ?>/download_excel?' + query_string);
        }
      </script>
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label for="">Periode</label>
                <input type="text" class="form-control" readonly id='periode_filter'>
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
                  rekap_insentif_part.draw();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_filter_start').val('');
                  $('#periode_filter_end').val('');
                  rekap_insentif_part.draw();
                });
              </script>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label for="">Collector</label>
                <div class="input-group">
                  <input id='collector' type="text" class="form-control" readonly>
                  <input id='id_collector' type="hidden" class="form-control" readonly>
                  <div class="input-group-btn">
                    <button id='search_modal' class="btn btn-flat btn-primary" data-toggle='modal' data-target='#h3_md_collector_rekap_insentif_part'><i class="fa fa-search" aria-hidden="true"></i></button>
                    <button style='display: none;' id='hapus_collector_filter' onclick='return hapus_collector_filter()' type='button' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php $this->load->view('modal/h3_md_collector_rekap_insentif_part') ?>
            <script>
              function pilih_collector(data){
                $('#collector').val(data.nama_lengkap);
                $('#id_collector').val(data.id_karyawan);

                $('#search_modal').hide();
                $('#hapus_collector_filter').show();
                rekap_insentif_part.draw();
              }

              function hapus_collector_filter(){
                $('#collector').val('');
                $('#id_collector').val('');

                $('#search_modal').show();
                $('#hapus_collector_filter').hide();
                rekap_insentif_part.draw();
              }
            </script>
          </div>
        </div>
        <table id="rekap_insentif_part" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl. BAP</th>              
              <th>Jumlah Faktur</th>              
              <th>Realisasi Faktur</th>              
              <th>Nominal Rupiah</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          rekap_insentif_part = $('#rekap_insentif_part').DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            searching: false,
            ordering: false,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/rekap_insentif_part') ?>",
                dataSrc: function(json){
                  if(json.data.length > 0){
                    $('#btn_download_excel').show();
                  }else{
                    $('#btn_download_excel').hide();
                  }

                  set_download_excel_url();

                  return json.data;
                },
                type: "POST",
                data: function(d){
                  d.periode_filter_start = $('#periode_filter_start').val();
                  d.periode_filter_end = $('#periode_filter_end').val();
                  d.id_collector = $('#id_collector').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { 
                  data: 'created_at',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                },
                { data: 'jumlah_faktur' },
                { data: 'jumlah_faktur_dikembalikan' },
                { 
                  data: 'nominal_dikembalikan',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, ',', '.');
                  },
                  className: 'text-right'
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