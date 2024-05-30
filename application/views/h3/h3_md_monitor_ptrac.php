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
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/upload">
            <button class="btn bg-blue btn-flat margin">Upload</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Bulan PO</label>
                <select id='filter_bulan_po' class="form-control">
                  <option value="">-</option>
                  <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>"><?= lang('month_' . $i) ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function(){
                $('#filter_bulan_po').on('change', function(){
                  h3_md_filter_kode_part_monitoring_ptrac_datatable.draw();
                  monitoring_ptrac.draw();
                });
              })
            </script>
            <div id='filter_kode_part' class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Kode Part</label>
                <div class="input-group">
                  <input type="text" class="form-control" readonly :value='filters.length + " Part"'>
                  <div class="input-group-btn">
                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_filter_kode_part_monitoring_ptrac'><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php $this->load->view('modal/h3_md_filter_kode_part_monitoring_ptrac'); ?>
            <script>
              filter_kode_part = new Vue({
                el: '#filter_kode_part',
                data: {
                  filters: []
                },
                watch: {
                  filters: {
                    deep: true,
                    handler: function(){
                      h3_md_filter_kode_part_monitoring_ptrac_datatable.draw();
                      monitoring_ptrac.draw();
                    }
                  }
                }
              });

              $("#h3_md_filter_kode_part_monitoring_ptrac").on('change',"input[type='checkbox']",function(e){
                target = $(e.target);
                id_part = target.attr('data-id-part');

                if(target.is(':checked')){
                  filter_kode_part.filters.push(id_part);
                }else{
                  index_kode_part = _.indexOf(filter_kode_part.filters, id_part);
                  filter_kode_part.filters.splice(index_kode_part, 1);
                }
                h3_md_filter_kode_part_monitoring_ptrac_datatable.draw();
              });
            </script>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Tipe PO</label>
                <select id='filter_tipe_po' class="form-control">
                  <option value="">All</option>
                  <option value="FIX">Fix</option>
                  <option value="REG">Reguler</option>
                  <option value="URG">Urgent</option>
                  <option value="HTL">Hotline</option>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function(){
                $('#filter_tipe_po').on('change', function(){
                  h3_md_filter_kode_part_monitoring_ptrac_datatable.draw();
                  monitoring_ptrac.draw();
                });
              })
            </script>
            <div id='filter_purchase_order' class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">No. PO</label>
                <div class="input-group">
                  <input type="text" class="form-control" readonly :value='filters.length + " PO"'>
                  <div class="input-group-btn">
                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_filter_po_monitoring_ptrac'><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php $this->load->view('modal/h3_md_filter_po_monitoring_ptrac'); ?>
            <script>
              filter_purchase_order = new Vue({
                el: '#filter_purchase_order',
                data: {
                  filters: []
                },
                watch: {
                  filters: {
                    deep: true,
                    handler: function(){
                      h3_md_filter_po_monitoring_ptrac_datatable.draw();
                      monitoring_ptrac.draw();
                    }
                  }
                }
              });

              $("#h3_md_filter_po_monitoring_ptrac").on('change',"input[type='checkbox']",function(e){
                target = $(e.target);
                id_purchase_order = target.attr('data-id-purchase-order');

                if(target.is(':checked')){
                  filter_purchase_order.filters.push(id_purchase_order);
                }else{
                  index_purchase_order = _.indexOf(filter_purchase_order.filters, id_purchase_order);
                  filter_purchase_order.filters.splice(index_purchase_order, 1);
                }
                h3_md_filter_po_monitoring_ptrac_datatable.draw();
              });
            </script>
          </div>
          <div class="row">
            <div id='filter_kelompok_part' class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Kelompok Part</label>
                <div class="input-group">
                  <input type="text" class="form-control" readonly :value='filters.length + " Kelompok Part"'>
                  <div class="input-group-btn">
                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_filter_kelompok_part_monitoring_ptrac'><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php $this->load->view('modal/h3_md_filter_kelompok_part_monitoring_ptrac'); ?>
            <script>
              filter_kelompok_part = new Vue({
                el: '#filter_kelompok_part',
                data: {
                  filters: []
                },
                watch: {
                  filters: {
                    deep: true,
                    handler: function(){
                      h3_md_filter_kelompok_part_monitoring_ptrac_datatable.draw();
                      monitoring_ptrac.draw();
                    }
                  }
                }
              });

              $("#h3_md_filter_kelompok_part_monitoring_ptrac").on('change',"input[type='checkbox']",function(e){
                target = $(e.target);
                id_kelompok_part = target.attr('data-id-kelompok-part');

                if(target.is(':checked')){
                  filter_kelompok_part.filters.push(id_kelompok_part);
                }else{
                  index_kode_part = _.indexOf(filter_kelompok_part.filters, id_kelompok_part);
                  filter_kelompok_part.filters.splice(index_kode_part, 1);
                }
                h3_md_filter_kelompok_part_monitoring_ptrac_datatable.draw();
              });
            </script>
          </div>
        </div>
        <table id="monitoring_ptrac" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. PO AHM</th>              
              <th>No. PO MD PTRAC</th>              
              <th>No. PO MD Sistem</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Qty PO</th>              
              <th>Qty Book</th>              
              <th>Qty Pick</th>              
              <th>Qty Pack</th>              
              <th>Qty Bill</th>              
              <th>Qty Ship</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function(){
            monitoring_ptrac = $('#monitoring_ptrac').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/master_ptrac') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.filter_bulan_po = $('#filter_bulan_po').val();
                    d.filter_tipe_po = $('#filter_tipe_po').val();
                    d.filter_kode_part = filter_kode_part.filters;
                    d.filter_kelompok_part = filter_kelompok_part.filters;
                    d.filter_purchase_order = filter_purchase_order.filters;
                  }
              },
              columns: [
                  { data: 'index', orderable: false, width: '3%' },
                  { data: 'no_po_ahm' }, 
                  { data: 'no_po_md' }, 
                  { 
                    data: 'id_purchase_order',
                    render: function(data){
                      if(data == null) return '-';
                      return data;
                    }
                  }, 
                  { data: 'id_part' }, 
                  { data: 'nama_part' }, 
                  { data: 'qty_po' }, 
                  { data: 'qty_book' }, 
                  { data: 'qty_picking' }, 
                  { data: 'qty_packing' }, 
                  { data: 'qty_invoice' }, 
                  { data: 'qty_ship' }, 
              ],
            });
          });
        </script>
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
            <div v-if='part_not_found.length > 0' class="alert alert-warning alert-dismissible">
              <button type="button" class="close" @click.prevent='part_not_found = []' aria-hidden="true">×</button>
              <h4>
                <i class="icon fa fa-warning"></i> 
                Alert!
              </h4>
              <div class="row">
                <div class="col-sm-12">
                  <span>Terdapat Kode Part yang tidak ada di sistem. Mohon dilakukan pengecekan kembali, antara lain:</span>
                  <ul>
                    <li v-for='(id_part, index) of part_not_found'>{{ id_part }}</li>
                  </ul> 
                </div>
              </div>
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File PTRAC</label>
                  <div class="col-sm-4">                    
                    <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".ptrac,.PTRAC">
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
            part_not_found: [],
            file: null
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);

              this.errors = {};
              this.part_not_found = [];
              this.loading = true;
              axios.post('h3/<?= $isi ?>/inject', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>';
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else if(data.error_type == 'part_not_found'){
                  app.part_not_found = data.payload.terdapat_part_yang_tidak_terdaftar;
                }else{
                  toastr.error(err);
                }

              })
              .then(function(){ 
                app.reset_file();
                app.loading = false; 
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