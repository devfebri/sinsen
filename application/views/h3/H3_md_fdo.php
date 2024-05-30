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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <?php if($mode == 'upload'): ?>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File FDO</label>
                  <div class="col-sm-4">                    
                    <input name="file_fdo" type="file" class="form-control" accept=".fdo,.FDO">
                  </div>  
                </div>
                <?php else: ?>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor Invoice AHM</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='fdo.invoice_number'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Jatuh Tempo DPP</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='fdo.dpp_due_date'>
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Jatuh Tempo PPN</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='fdo.ppn_due_date'>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th>Packing Sheet</th>              
                          <th>Qty</th>
                          <th>Harga</th>
                          <th>Disc Campaign</th>
                          <th>Disc Insentif</th>              
                          <th>DPP</th>
                          <th>PPN</th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-if='parts.length > 0' v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle">{{ part.nomor_packing_sheet }}</td>                       
                          <td class="align-middle">{{ part.quantity }}</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.price" />
                          </td>     
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.disc_campaign" />
                          </td>        
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.disc_insentif" />
                          </td> 
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.dpp" />
                          </td>             
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.ppn" />
                          </td>   
                        </tr>
                        <tr v-if='parts.length > 0'>                     
                          <td class="align-middle text-right" colspan='8'>Total</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="total_dpp" />
                          </td>             
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="total_ppn" />
                          </td>   
                        </tr>
                        <tr v-if='parts.length > 0'>                     
                          <td class="align-middle text-right" colspan='8'>Grand Total</td>                       
                          <td class="align-middle" colspan='2'>
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="grand_total" />
                          </td>
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="15">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
                <?php endif; ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if='mode == "upload"' class="btn btn-sm btn-flat btn-primary" type="submit">Upload</button>
                  <button v-if='mode == "detail" && fdo.status != "Approved"' class="btn btn-sm btn-flat btn-success" @click.prevent='approve'>Approve</button>
                </div>
              </div><!-- /.box-footer -->
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
            fdo: <?= json_encode($fdo) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php endif; ?>
          },
          methods: {
            approve: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/approve', {
                params: {
                  invoice_number: this.fdo.invoice_number
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?invoice_number=' + res.data.invoice_number;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              })
            }
          },
          computed: {
            total_dpp: function(){
              return _.sumBy(this.parts, function(p){
                return p.dpp;
              });
            },
            total_ppn: function(){
              return _.sumBy(this.parts, function(p){
                return p.ppn;
              });
            },
            grand_total: function(){
              return this.total_dpp + this.total_ppn;
            },
          }
        });
    </script>
    <?php endif; ?>
    <?php if($set=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/upload">
            <button class="btn bg-blue btn-flat margin">Upload</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message'); ?>
        <table id="fdo" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tanggal Invoice</th>              
              <th>Nomor Invoice</th>              
              <th>Total DPP</th>              
              <th>DPP Duedate</th>              
              <th>Total PPN</th>              
              <th>PPN Duedate</th>              
              <th>Tanggal Upload</th>              
              <th>Status</th>              
              <th>Jumlah Voucher</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          fdo = $('#fdo').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/fdo') ?>",
                dataSrc: "data",
                type: "POST"
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
                  data: 'total_dpp',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                  },
                  className: 'text-right'
                }, 
                { 
                  data: 'dpp_due_date',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { 
                  data: 'total_ppn',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                  },
                  className: 'text-right'
                }, 
                { 
                  data: 'ppn_due_date',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { 
                  data: 'created_at',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { data: 'status' }, 
                { data: 'jumlah_voucher' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
            ],
          });

          $('#fdo').on('click', 'td', function(e){
            rowIndex = fdo.cell( this ).index().row;
            columnIndex = fdo.cell( this ).index().columnVisible;
            data = fdo.row(rowIndex).data();
            
            if(columnIndex == 9){
              $('#invoice_number_voucher_pengeluaran_pop_up').val(data.invoice_number);
              $('#h3_md_voucher_pengeluaran_fdo').modal('show');
              h3_md_voucher_pengeluaran_fdo_datatable.draw();
            }
          });
        });
        </script>
        <?php $this->load->view('modal/h3_md_voucher_pengeluaran_fdo'); ?>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
    <?php if($set == 'upload'): ?>
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
        <div class="row">
          <div class="col-md-12">
            <div v-if='packing_sheet_not_found.length > 0' class="alert alert-warning alert-dismissible">
              <button type="button" class="close" @click.prevent='packing_sheet_not_found = []' aria-hidden="true">×</button>
              <h4>
                <i class="icon fa fa-warning"></i> 
                Alert!
              </h4>
              <div v-for='(each, index) of packing_sheet_not_found.slice(0, 10)' class="row">
                <div class="col-sm-12">
                  <span>{{ index + 1 }}. Invoice {{ each.invoice.invoice_number }} tidak memenuhi standar untuk dilakukan penguploadan</span>
                  <div v-if='each.packing_sheet_not_found.length > 0' class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <span>Terdapat Packing Sheet yang tidak terdaftar di sistem, antara lain:</span>
                        <ul>
                          <li v-for='(error, index) of each.packing_sheet_not_found'>{{ error }}</li>
                        </ul> 
                      </div>
                    </div>
                  </div>
                  <div v-if='each.part_not_found.length > 0' class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <span>Terdapat Part yang tidak terdaftar di sistem, antara lain:</span>
                        <ul>
                          <li v-for='(error, index) of each.part_not_found'>{{ error }}</li>
                        </ul> 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File FDO</label>
                  <div class="col-sm-4">                    
                    <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".fdo,.FDO">
                  </div>  
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button :disabled='file == null' class="btn btn-flat btn-sm btn-primary" type="submit" @click.prevent='upload'>Upload</button>
                </div>
              </div><!-- /.box-footer -->
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
            errors: {},
            packing_sheet_not_found: [],
            file: null
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);

              this.errors = {};
              this.packing_sheet_not_found = [];
              this.loading = true;
              axios.post('h3/<?= $isi ?>/inject', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else if(data.error_type == 'packing_sheet_not_complete'){
                  app.packing_sheet_not_found = data.payload;
                }else{
                  toastr.error(data.message);
                }
                app.loading = false; 
              })
              .then(function(){ 
                app.reset_file();
              });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            reset_file: function(){
              const input = this.$refs.file;
              input.type = 'text';
              input.type = 'file';
              this.file = null;
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
        });
    </script>
    <?php endif; ?>
  </section>
</div>