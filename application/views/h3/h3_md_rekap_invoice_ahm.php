<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }

      if ($mode == 'upload') {
        $form = 'inject';
      }

      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
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
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label">No Rekap</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='rekap_invoice.id_rekap_invoice'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tgl Jatuh Tempo</label>
                  <div v-bind:class="{ 'has-error': error_exist('tgl_jatuh_tempo') }" class="col-sm-4">                    
                    <input :disabled='mode == "detail"' id='tgl_jatuh_tempo' readonly type="text" class="form-control">
                    <small v-if="error_exist('tgl_jatuh_tempo')" class="form-text text-danger">{{ get_error('tgl_jatuh_tempo') }}</small>
                  </div>
                  <div v-if='mode != "detail"' class="col-sm-1 no-padding">
                    <button class="btn btn-flat btn-success" @click.prevent='proses_faktur'>Proses</button>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Tgl Faktur</th>              
                          <th>No Faktur</th>              
                          <th>TOP DPP</th>              
                          <th>TOP PPN</th>              
                          <th class='text-right'>Total DPP</th>              
                          <th class='text-right'>Total PPN</th>              
                          <th>No Giro</th>              
                          <th>Amount Giro</th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-if='items.length > 0' v-for="(item, index) in items"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ item.invoice_date }}</td>                       
                          <td class="align-middle">{{ item.invoice_number }}</td>                       
                          <td class="align-middle">{{ item.dpp_due_date_formatted }}</td>                       
                          <td class="align-middle">{{ item.ppn_due_date_formatted }}</td>                       
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="item.total_dpp" />
                          </td>     
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="item.total_ppn" />
                          </td>        
                          <td class="align-middle">{{ item.no_giro }}</td> 
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="item.amount_giro" />
                          </td>             
                        </tr>
                        <tr v-if='items.length > 0'> 
                          <td class="align-middle text-right" colspan='5'>Total</td>            
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="total_dpp" />
                          </td>            
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="total_ppn" />
                          </td>            
                        </tr>
                        <tr v-if='items.length > 0'> 
                          <td class="align-middle text-right" colspan='5'>Grand Total</td>            
                          <td class="align-middle text-right" colspan='2'>
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="grand_total" />
                          </td>            
                        </tr>
                        <tr v-if="items.length < 1">
                          <td class="text-center" colspan="9">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
                <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12 no-padding">
                        <button v-if='mode == "insert"' class="btn btn-flat btn-primary btn-sm" @click.prevent='<?= $form ?>'>Simpan</button>
                      </div>
                    </div>
                </div>
              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
   <script>
      var app = new Vue({
          el: '#app',
          data: {
            loading: false, 
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail'): ?>
            rekap_invoice: <?= json_encode($rekap_invoice) ?>,
            items: <?= json_encode($items) ?>,
            <?php else: ?>
            rekap_invoice: {
              tgl_jatuh_tempo: ''
            },
            items: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              this.loading = true;
              post = _.pick(this.rekap_invoice, ['tgl_jatuh_tempo']);
              post.total_dpp = this.total_dpp;
              post.total_ppn = this.total_ppn;
              post.items = _.map(this.items, function(item){
                return _.pick(item, [
                  'invoice_number'
                ]);
              });
              console.log(post);

              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_rekap_invoice=' + res.data.id_rekap_invoice;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  form_.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
              })
              .then(function(){ app.loading = false; });
            },
            proses_faktur: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/proses_faktur', {
                params: {
                  tgl_jatuh_tempo: this.rekap_invoice.tgl_jatuh_tempo
                }
              })
              .then(function(res){
                app.items = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            total_dpp: function(){
              tgl_jatuh_tempo = this.rekap_invoice.tgl_jatuh_tempo;
              return _.chain(this.items)
              .filter(function(data){
                return data.dpp_due_date == tgl_jatuh_tempo;
              })
              .sumBy(function(i){
                return parseFloat(i.total_dpp);
              }).value();
            },
            total_ppn: function(){
              tgl_jatuh_tempo = this.rekap_invoice.tgl_jatuh_tempo;
              return _.chain(this.items)
              .filter(function(data){
                return data.ppn_due_date == tgl_jatuh_tempo;
              })
              .sumBy(function(i){
                return parseFloat(i.total_ppn);
              }).value();
            },
            grand_total: function(){
              return parseFloat(this.total_dpp) + parseFloat(this.total_ppn);
            }
          },
          mounted: function(){
            config = {
              autoclose: true,
              format: 'dd/mm/yyyy'
            };
            $(document).ready(function(){
              $('#tgl_jatuh_tempo').datepicker(config)
              .on('changeDate', function(e){
                app.rekap_invoice.tgl_jatuh_tempo = e.format('yyyy-mm-dd');
              });
            });
            if(this.mode == "detail"){
              date = new Date(this.rekap_invoice.tgl_jatuh_tempo);
              $(document).ready(function(){
                $("#tgl_jatuh_tempo").datepicker("setDate", date);
                $('#tgl_jatuh_tempo').datepicker('update');
              });
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <table id="rekap_invoice" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No Rekap</th>              
              <th>Tgl Jatuh Tempo</th>              
              <th>Total DPP</th>              
              <th>Total PPN</th>              
              <th>Amount</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          rekap_invoice = $('#rekap_invoice').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/rekap_invoice_ahm') ?>",
                dataSrc: "data",
                type: "POST"
            },
            columns: [
                { data: null, orderable: false, width: '3%' }, 
                { data: 'id_rekap_invoice' }, 
                { data: 'tgl_jatuh_tempo' }, 
                { data: 'total_dpp', className: 'text-right' }, 
                { data: 'total_ppn', className: 'text-right' }, 
                { data: 'amount', className: 'text-right' }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
            ],
          });
          rekap_invoice.on('draw.dt', function() {
            var info = rekap_invoice.page.info();
            rekap_invoice.column(0, {
                search: 'applied',
                order: 'applied',
                page: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start + ".";
            });
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>