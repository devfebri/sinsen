<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
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
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <?= $breadcrumb ?>
</section>
<section class="content">
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>

    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Serah Terima SJ</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='serah_terima_sj.id_serah_terima_sj'>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='serah_terima_sj.created_at'>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Proses</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='serah_terima_sj.proses_at'>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='serah_terima_sj.status'>
                    </div>
                  </div>
                  <table class="table">
                    <tr>
                      <th width='3%'>No.</th>
                      <th>Tgl Surat Jalan</th>
                      <th>No. Surat Jalan</th>
                      <th>No. Delivery Order</th>
                      <th>Tgl. Faktur</th>
                      <th>No. Faktur</th>
                      <th>Nama Customer</th>
                      <th>Checklist (By H3)</th>
                      <th v-if='mode != "insert"'>Checklist (By Finance)</th>
                      <th>Keterangan</th>
                    </tr>
                    <tr v-if='items.length > 0' v-for='(item, index) of items'>
                      <td>{{ index + 1 }}.</td>
                      <td>{{ item.tgl_packing_sheet }}</td>
                      <td>{{ item.id_packing_sheet }}</td>
                      <td>{{ item.id_do_sales_order }}</td>
                      <td>{{ item.tgl_faktur }}</td>
                      <td>{{ item.no_faktur }}</td>
                      <td>{{ item.nama_dealer }}</td>
                      <td>
                        <input :disabled='mode == "detail"' type="checkbox" v-model='item.checklist_h3' true-value='1' false-value='0'>
                      </td>
                      <td v-if='mode != "insert"'>
                        <input :disabled='serah_terima_sudah_proses' type="checkbox" v-model='item.checklist_finance' true-value='1' false-value='0'>
                      </td>
                      <td>
                        <span v-if='mode == "insert" && item.keterangan != null'>{{ item.keterangan }}</span>
                        <span v-if='mode == "insert" && item.keterangan == null'>---</span>

                        <input v-if='mode != "insert"' :disabled='serah_terima_sudah_proses' type="text" class="form-control" v-model='item.keterangan'>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-12 text-center">
                      <button v-if='mode == "insert"' :disabled='item_checklist_h3 == 0' class="btn btn-flat btn-primary btn-sm" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "detail" && serah_terima_sj.status == "Open"' @click.prevent='proses' :disabled='item_checklist_finance < 1' class="btn btn-flat btn-sm btn-primary">Proses</button>
                      <button v-if='mode == "detail" && serah_terima_sj.status == "Open"' @click.prevent='reject' class="btn btn-flat btn-sm btn-danger">Reject</button>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        <?php if($mode == "detail" || $mode == "edit"): ?>
        serah_terima_sj: <?= json_encode($serah_terima_sj) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        serah_terima_sj: {
          status: null,
        },
        items: [],
        <?php endif; ?>
      },
      mounted: function(){
        if(this.mode == 'insert'){
          this.get_items();
        }
      },
      methods:{
        <?= $form ?>: function(){
          post = {};

          post.items = _.chain(this.items)
          .filter(function(item){
            return parseInt(item.checklist_h3) == 1;
          })
          .map(function(item){
            return _.pick(item, ['id_packing_sheet', 'id_packing_sheet_int', 'checklist_h3', 'checklist_finance']);
          })
          .value();

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url; 
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(data.message);
            }
            form_.loading = false;
          });
        },
        get_items: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/get_items')
          .then(function(res){
            form_.items = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        proses: function(){
          confirmed = confirm("Apakah anda yakin ingin memproses Surat Terima SJ ini?");
          if(!confirmed) return;

          post = {};
          post.id = this.serah_terima_sj.id;
          post.items = _.chain(this.items)
          .filter(function(item){
            return parseInt(item.checklist_h3) == 1;
          })
          .map(function(item){
            return _.pick(item, ['id', 'checklist_finance', 'keterangan']);
          })
          .value();

          this.loading = true;
          axios.post('h3/<?= $isi ?>/proses', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            toastr.error(data.message);
            form_.loading = false;
          });
        },
        reject: function(){
          confirmed = confirm("Apakah anda yakin ingin mereject Surat Terima SJ ini?");
          if(!confirmed) return;

          post = {};
          post.id = this.serah_terima_sj.id;
          post.items = _.chain(this.items)
          .filter(function(item){
            return parseInt(item.checklist_h3) == 1;
          })
          .map(function(item){
            return _.pick(item, ['id', 'checklist_finance', 'keterangan']);
          })
          .value();

          this.loading = true;
          axios.post('h3/<?= $isi ?>/reject', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            toastr.error(data.message);
            form_.loading = false;
          });
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        item_checklist_h3: function(){
          return _.chain(this.items)
          .filter(function(item){
            return parseInt(item.checklist_h3) == 1;
          })
          .value().length;
        },
        item_checklist_finance: function(){
          return _.chain(this.items)
          .filter(function(item){
            return parseInt(item.checklist_finance) == 1;
          })
          .value().length;
        },
        serah_terima_sudah_proses: function(){
          return this.serah_terima_sj.status != 'Open';
        }
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
          <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Serah Terima SJ</label>
                    <input type="text" class="form-control" id="id_serah_terima_sj_filter">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#id_serah_terima_sj_filter').on('keyup', _.debounce(function(){
                    serah_terima_sj.draw();
                  }, 500));
                })
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Surat Jalan</label>
                    <input type="text" class="form-control" id="id_packing_sheet_filter">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#id_packing_sheet_filter').on('keyup', _.debounce(function(){
                    serah_terima_sj.draw();
                  }, 500));
                })
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Faktur</label>
                    <input type="text" class="form-control" id="no_faktur_filter">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_faktur_filter').on('keyup', _.debounce(function(){
                    serah_terima_sj.draw();
                  }, 500));
                })
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Customer</label>
                    <input type="text" class="form-control" id="nama_customer_filter">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#nama_customer_filter').on('keyup', _.debounce(function(){
                    serah_terima_sj.draw();
                  }, 500));
                })
              </script>
            </div>
          </div>
        </div>
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <table id="serah_terima_sj" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>No. Serah Terima SJ</th>
              <th>Tgl Entry</th>
              <th>Tgl Proses</th>
              <th>Tgl Reject</th>
              <th>Jumlah SJ</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          date_renderer = function(data){
            if(data != null) return moment(data).format('DD/MM/YYYY');
            return '-';
          }

          $(document).ready(function() {
            serah_terima_sj  = $('#serah_terima_sj').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                searching: false,
                ajax: {
                  url: "<?= base_url('api/md/h3/serah_terima_sj') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.id_serah_terima_sj_filter = $('#id_serah_terima_sj_filter').val();
                    d.id_packing_sheet_filter = $('#id_packing_sheet_filter').val();
                    d.no_faktur_filter = $('#no_faktur_filter').val();
                    d.nama_customer_filter = $('#nama_customer_filter').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'id_serah_terima_sj' },
                    { 
                      data: 'created_at',
                      render: date_renderer 
                    },
                    { 
                      data: 'proses_at',
                      render: date_renderer 
                    },
                    { 
                      data: 'rejected_at',
                      render: date_renderer 
                    },
                    { data: 'jumlah_sj' },
                    { data: 'status' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>