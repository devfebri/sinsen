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
        <a href="h3/<?= $isi ?>/download_excel" id="btn_download_excel">
          <button class="btn btn-flat btn-sm btn-success">Download</button>
        </a>
      </div>
      <div class="box-body">
        <table id="monitoring_program_cashback_insentif" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tanggal Transaksi</th>              
              <th>Nama Program</th>              
              <th>Periode</th>              
              <th>Nama Toko</th>              
              <th>Nominal Program</th>              
              <th>No. Faktur</th>              
              <th>Nominal potongan di faktur</th>              
              <th>No. Giro</th>              
              <th>Tgl Giro</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          monitoring_program_cashback_insentif = $('#monitoring_program_cashback_insentif').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/monitoring_program_cashback_insentif') ?>",
                dataSrc: "data",
                type: "POST",
            },
            columns: [
              { data: 'index', orderable: false, width: '3%' }, 
              { 
                data: 'tanggal_transaksi', 
                name: 'do.tanggal',
                render: function(data){
                  return moment(data).format('DD/MM/YYYY');
                }
              }, 
              { data: 'nama_program', name: 'sc.nama' }, 
              { 
                data: 'start_date', 
                render: function(data, type, row){
                  if(data == null){
                    return '-';
                  }

                  return moment(row.start_date).format('DD/MM/YYYY') + ' s.d ' + moment(row.end_date).format('DD/MM/YYYY')
                }
              }, 
              { data: 'nama_dealer', name: 'd.nama_dealer' }, 
              { 
                data: 'total_bayar', 
                name: 'ap.total_bayar',
                render: function(data){
                  return accounting.formatMoney(data, 'Rp', 0, '.', ',');
                }
              }, 
              { 
                data: 'no_faktur', 
                name: 'ps.no_faktur',
                render: function(data){
                  if(data != null){
                    return data;
                  }
                  return '-';
                }
              }, 
              { 
                data: 'nilai_claim', 
                name: 'ciscp.nilai_claim',
                render: function(data){
                  return accounting.formatMoney(data, 'Rp', 0, '.', ',');
                }
              }, 
              { 
                data: 'kode_giro', 
                name: 'cg.kode_giro',
                render: function(data){
                  if(data != null){
                    return data;
                  }
                  return '-';
                }
              }, 
              { 
                data: 'tanggal_giro', 
                name: 'vp.tanggal_giro',
                render: function(data){
                  if(data != null){
                    return moment(data).format('DD/MM/YYYY');
                  }
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