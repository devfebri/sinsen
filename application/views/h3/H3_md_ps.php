<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body onload="auto()">
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

      if ($mode == 'upload') {
        $form = 'inject';
      }

      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <?php if($ps['invoice_number_int'] == null): ?>
        <div class="alert alert-warning">
            <strong>Packing sheet tidak memiliki data invoice (FDO), mohon lengkapi data terlebih dahulu. Hal ini dapat menggangu proses penerimaan barang</strong>
        </div>
        <?php endif; ?>
        <?php if($jumlah_karton_ps['no_doos'] != $jumlah_item_karton['jumlah_item']): ?>
        <div class="alert alert-danger">
            <strong>Terdapat jumlah no.karton yang tidak sesuai antara PS dan Jumlah Item, yaitu :</strong>
            <br>
            <?php  $notFoundString = implode(', ', $notFound); // Menggabungkan elemen array dengan koma sebagai pemisah 
            echo $notFoundString ;?>
        </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-12">
            <form id="vueForm" class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <?php if($mode == 'upload'): ?>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File PS</label>
                  <div class="col-sm-4">                    
                    <input name="file_ps" type="file" class="form-control" accept=".ps,.PS">
                  </div>  
                </div>
                <?php else: ?>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Packing Sheet</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" value="<?= $ps['packing_sheet_number'] ?>">
                  </div>  
                  <label class="col-sm-2 control-label">Tgl Packing Sheet</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" value="<?= date('d-m-Y', strtotime($ps['packing_sheet_date'])) ?>">
                  </div>  
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Invoice (FDO)</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" value="<?= $ps['invoice_number'] != null ? $ps['invoice_number'] : '-' ?>">
                  </div>  
                  <label class="col-sm-2 control-label">Tgl Packing Sheet</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" value="<?= $ps['invoice_date'] != null ? Mcarbon::parse($ps['invoice_date'])->format('d/m/Y') : '-' ?>">
                  </div>  
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th>No. Karton</th>              
                          <th>No. PO</th>              
                          <th>Jenis PO</th>              
                          <th>Tanggal PO</th>              
                          <th>Qty Packing Sheet</th>              
                          <th>Qty Order</th>
                          <th>Qty Back Order</th>
                          <th>No. Penerimaan Barang</th>
                          <th>Status Penerimaan Barang</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle">{{ part.no_doos }}</td>                       
                          <td class="align-middle">{{ part.no_po }}</td>                       
                          <td class="align-middle">{{ part.jenis_po }}</td>                       
                          <td class="align-middle">{{ part.tanggal_po }}</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="true" thousand-separator="." v-model="part.packing_sheet_quantity" />
                          </td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="true" thousand-separator="." v-model="part.qty_order" />
                          </td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="true" thousand-separator="." v-model="part.qty_back_order" />
                          </td>     
                          <td class="align-middle">{{ part.no_penerimaan_barang }}</td>                       
                          <td class="align-middle">{{ part.status_penerimaan_barang }}</td>                       
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
                  <?php if($mode == 'upload'): ?>
                  <button class="btn btn-flat btn-sm btn-primary" type="submit">Upload</button>
                  <?php endif; ?>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var vueForm = new Vue({
          el: '#vueForm',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail'): ?>
            parts: <?= json_encode($parts) ?>,
            <?php endif; ?>
          },
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
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message'); ?>
        <table id="ps" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl Packing Sheet</th>              
              <th>No Packing Sheet</th>              
              <th>No. Invoice</th>              
              <th>Tgl. Invoice</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function(){
            ps = $('#ps').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/ps') ?>",
                  dataSrc: "data",
                  type: "POST"
              },
              columns: [
                  { data: 'index', orderable: false, width: '3%' }, 
                  { 
                    data: 'packing_sheet_date',
                    render: function(data){
                      if(data != null){
                        return moment(data).format('DD/MM/YYYY');
                      }
                      return '-';
                    }
                  }, 
                  { data: 'packing_sheet_number' }, 
                  { 
                    data: 'invoice_number',
                    render: function(data){
                      if(data != null){
                        return data;
                      }
                      return '-';
                    }
                  }, 
                  { 
                    data: 'invoice_date',
                    render: function(data){
                      if(data != null){
                        return moment(data).format('DD/MM/YYYY');
                      }
                      return '-';
                    }
                  }, 
                  { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
              ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
    <?php if($set == 'upload_ps'): ?>
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
        <div v-if='errors_payload.length > 0' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Perhatian!
          </h4>
          <p>Terdapat Packing Sheet dengan part yang tidak terdaftar di sistem, antara lain:</p>
          <ol class="">
            <li v-for='(each, index) of errors_payload'>
              {{ each.packing_sheet_number }}
              <ul>
                <li v-for='(part, index) of each.parts_tidak_terdaftar'>{{ part }}</li>
              </ul>
            </li>
          </ol>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File PS</label>
                  <div class="col-sm-4">                    
                    <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".ps,.PS">
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
            error_type: null,
            errors_payload: {},
            success: {},
            file: null
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);

              this.errors_payload = {};
              this.loading = true;
              axios.post('h3/<?= $isi ?>/inject', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              }).catch(function(err){
                data = err.response.data;
                if(data.error_type == 'part_tidak_terdaftar'){
                  app.error_type = data.error_type;
                  app.errors_payload = data.errors_payload;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }

                app.loading = false;
              });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          }
        });
    </script>
    <?php endif; ?>
  </section>
</div>