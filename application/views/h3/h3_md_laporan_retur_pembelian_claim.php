<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'detail';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Retur Pembelian</label>
                  <div class="col-sm-4">                    
                    <input v-model="retur_pembelian_claim.no_retur" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Retur Pembelian</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" :value='moment(retur_pembelian_claim.tanggal).format("DD/MM/YYYY")'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Claim Main Dealer</label>
                  <div class="col-sm-4">                    
                    <input v-model="retur_pembelian_claim.id_claim" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Claim Main Dealer</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" :value='moment(retur_pembelian_claim.tanggal_claim).format("DD/MM/YYYY")'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Packing Sheet</label>
                  <div class="col-sm-4">                    
                    <input v-model="retur_pembelian_claim.packing_sheet_number" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Packing Sheet</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" :value='moment(retur_pembelian_claim.packing_sheet_date).format("DD/MM/YYYY")'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Faktur AHM</label>
                  <div class="col-sm-4">                    
                    <input v-model="retur_pembelian_claim.invoice_number" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Faktur AHM</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" :value='moment(retur_pembelian_claim.invoice_date).format("DD/MM/YYYY")'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input v-model="retur_pembelian_claim.status" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-middle' width='3%'>No.</th>              
                          <th class='align-middle' width='10%'>Kode Part</th>              
                          <th class='align-middle' width='15%'>Nama Part</th>              
                          <th class='align-middle' width="10%">Kuantitas</th>
                          <th class='align-middle' width="10%">Harga</th>
                          <th class='align-middle' width="10%">Total Harga</th>
                          <th class='align-middle'>Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty"/>
                          </td>
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." currency="Rp" :empty-value="1" v-model="part.price"/>
                          </td>
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." currency="Rp" :empty-value="1" v-model="part.nominal"/>
                          </td>
                          <td class="align-middle">{{ part.keterangan }}</td>
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="7">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding text-right">
                  <button :disabled='loading' v-if='mode == "detail" && retur_pembelian_claim.status == "Open"' class="btn btn-sm btn-flat btn-info" @click.prevent='proses'>Proses</button>
                  <a :href="'h3/h3_md_retur_pembelian_claim/cetak?no_retur=' + retur_pembelian_claim.no_retur" class="btn btn-info btn-flat btn-sm">Cetak</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            retur_pembelian_claim: <?= json_encode($retur_pembelian_claim) ?>,
            parts: <?= json_encode($parts) ?>,
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
              todayBtn: 'linked'
            },
          },
          methods: {
            proses: function(){
              post = _.pick(this.retur_pembelian_claim, ['no_retur']);
              
              this.loading = true;
              axios.post('h3/<?= $isi ?>/proses', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?no_retur=' + res.data.no_retur;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Periode Tanggal Penerimaan</label>
                <input id='periode_laporan_retur_pembelian_filter' type="text" class="form-control" readonly>
                <input id='periode_laporan_retur_pembelian_filter_start' type="hidden" disabled>
                <input id='periode_laporan_retur_pembelian_filter_end' type="hidden" disabled>
              </div>                
              <script>
                $('#periode_laporan_retur_pembelian_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#periode_laporan_retur_pembelian_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#periode_laporan_retur_pembelian_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));

                  href = 'h3/h3_md_laporan_retur_pembelian_claim/cetak?periode_start=' + picker.startDate.format('YYYY-MM-DD') + '&periode_end=' + picker.endDate.format('YYYY-MM-DD');
                  $('#tombol-cetak').removeClass('hide');
                  $('#tombol-cetak').attr("href", href);

                  laporan_retur_pembelian.draw();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_laporan_retur_pembelian_filter_start').val('');
                  $('#periode_laporan_retur_pembelian_filter_end').val('');

                  href = 'h3/h3_md_laporan_retur_pembelian_claim';
                  $('#tombol-cetak').addClass('hide');
                  $('#tombol-cetak').attr("href", href);

                  laporan_retur_pembelian.draw();
                });
              </script>
            </div>
          </div>
          <div class="row" style='margin-bottom: 10px;'>
            <div class="col-sm-12">
              <a id='tombol-cetak' href="h3/h3_md_laporan_retur_pembelian_claim" class="btn btn-flat btn-sm btn-info hide">Cetak</a>
            </div>
          </div>
        </div>
        <table id="laporan_retur_pembelian" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Retur Pembelian</th>              
              <th>Tanggal</th>              
              <th>No. Claim MD</th>              
              <th>Tanggal Claim MD</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Kuantitas</th>              
              <th>Harga</th>              
              <th>Nominal</th>              
              <th>Tgl. PS</th>              
              <th>No. PS</th>              
              <th>Tgl. Faktur</th>              
              <th>No. Faktur</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          laporan_retur_pembelian = $('#laporan_retur_pembelian').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/laporan_retur_pembelian_claim') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.periode_laporan_retur_pembelian_filter_start = $('#periode_laporan_retur_pembelian_filter_start').val();
                  d.periode_laporan_retur_pembelian_filter_end = $('#periode_laporan_retur_pembelian_filter_end').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'no_retur' }, 
                { 
                  data: 'tanggal',
                  render: function(data){
                    return moment(data).format("DD/MM/YYYY")
                  }
                }, 
                { data: 'id_claim' }, 
                { 
                  data: 'tanggal_claim',
                  render: function(data){
                    if(data != null) return moment(data).format("DD/MM/YYYY");
                    return '-';
                  }
                },
                { data: 'id_part' }, 
                { data: 'nama_part' }, 
                { data: 'qty' }, 
                { 
                  data: 'price',
                  render: function(data){
                    if(data != null) return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    return '-';
                  },
                  className: 'text-right',
                  width: '100px'
                },
                { 
                  data: 'nominal',
                  render: function(data){
                    if(data != null) return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    return '-';
                  },
                  className: 'text-right',
                  width: '100px'
                },
                { 
                  data: 'packing_sheet_date',
                  render: function(data){
                    if(data != null) return moment(data).format("DD/MM/YYYY");
                    return '-';
                  }
                },
                { data: 'packing_sheet_number' }, 
                { 
                  data: 'invoice_date',
                  render: function(data){
                    if(data != null) return moment(data).format("DD/MM/YYYY");
                    return '-';
                  },
                },
                { 
                  data: 'invoice_number',
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
