<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
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
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;

                if(data.message != null){
                  toastr.error(data.message);
                } else{
                  toastr.error(err);
                }
                app.loading = false;
              });
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
        <table id="terima_claim_ahm" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Retur Pembelian</th>              
              <th>Tanggal</th>              
              <th>No. Claim MD</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          terima_claim_ahm = $('#terima_claim_ahm').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/retur_pembelian_claim') ?>",
                dataSrc: "data",
                type: "POST"
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
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
