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
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
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
                <div class="form-group">                  
                  <label class="col-sm-2 control-label no-padding-top">Part Number</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_part') }" class="col-sm-6">                    
                    <input disabled v-model="diskon_oli_reguler.id_part" type="input" class='form-control'>
                    <small v-if="error_exist('id_part')" class="form-text text-danger">{{ get_error('id_part') }}</small>
                  </div>  
                  <div class="col-sm-1 no-padding">
                    <button v-if='mode == "insert"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_parts_diskon_oli_reguler'><i class="fa fa-search"></i></button>
                  </div>                              
                  <?php $this->load->view('modal/h3_md_parts_diskon_oli_reguler'); ?>
                  <script>
                    function pilih_parts_diskon_oli_reguler (data) {
                        app.diskon_oli_reguler.id_part = data.id_part;
                        app.diskon_oli_reguler.harga_dealer_user = data.harga_dealer_user;
                        app.diskon_oli_reguler.nama_part = data.nama_part;
                        app.diskon_oli_reguler.kelompok_part = data.kelompok_part;
                        app.diskon_oli_reguler.status = data.status;
                    }
                  </script>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label no-padding-top">Part Deskripsi</label>
                  <div class="col-sm-6">                    
                    <input disabled v-model="diskon_oli_reguler.nama_part" type="input" class='form-control'>
                  </div>  
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label no-padding-top">Kelompok Part</label>
                  <div class="col-sm-6">                    
                    <input disabled v-model="diskon_oli_reguler.kelompok_part" type="input" class='form-control'>
                  </div>  
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label no-padding-top">HET</label>
                  <div class="col-sm-6">                    
                    <vue-numeric disabled class="form-control" separator='.' currency='Rp' v-model='diskon_oli_reguler.harga_dealer_user'></vue-numeric>
                  </div>  
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label no-padding-top">Status</label>
                  <div class="col-sm-6">                    
                    <input disabled v-model="diskon_oli_reguler.status" type="input" class='form-control'>
                  </div>  
                </div>
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label no-padding-top">Active</label>
                  <div class="col-sm-6">                    
                    <input :disabled='mode == "detail"' v-model="diskon_oli_reguler.active" type="checkbox" true-value="1" false-value="0">
                  </div>                                
                </div>
                <?php $this->load->view('modal/h3_md_general_ranges_diskon_oli_reguler'); ?>
                <?php $this->load->view('modal/h3_md_dealers_diskon_oli_reguler'); ?>
                <?php $this->load->view('modal/h3_md_range_dus_oli_diskon_oli_reguler'); ?>
                <script>
                    function pilih_range_dus_oli_diskon_oli_reguler(data) {
                        app.ranges.push(data);
                        h3_md_range_dus_oli_diskon_oli_reguler_datatable.draw();
                    }
                </script>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" @click.prevent='<?= $form ?>' class="btn btn-flat btn-primary btn-sm" type="button">Submit</button>
                  <button v-if="mode == 'edit'" @click.prevent='<?= $form ?>' class="btn btn-flat btn-warning btn-sm" type="button">Update</button>
                  <a v-if="mode == 'detail'" :href="'h3/h3_md_diskon_oli_reguler/edit?id=' + diskon_oli_reguler.id" class="btn btn-sm btn-flat btn-warning">Edit</a>
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
            mode: '<?= $mode ?>',
            filter_nama_customer: '',
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            diskon_oli_reguler: <?= json_encode($diskon_oli_reguler) ?>,
            dealers: <?= json_encode($items) ?>,
            general_ranges: <?= json_encode($general_ranges) ?>,
            <?php else: ?>
            diskon_oli_reguler: {
              id_part: '',
              harga_dealer_user: '',
              nama_part: '',
              kelompok_part: '',
              status: '',
              active: 1,
            },
            dealers: [],
            general_ranges: [],
            <?php endif; ?>
            ranges: [],
            index_dealer: 0,
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.diskon_oli_reguler, ['id', 'active', 'id_part']);
              post.dealers = this.dealers;
              post.general_ranges = this.general_ranges;

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
                  app.errors = data.errors;
                }
                
                if(data.message != null){
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }

                app.loading = false;
              });
            },
            open_range_model: function(index){
              this.index_dealer = index;
              this.ranges = this.dealers[this.index_dealer].ranges;
              $('#h3_md_ranges_diskon_oli_reguler').modal('show');
              h3_md_range_dus_oli_diskon_oli_reguler_datatable.draw();
            },
            hapus_part: function(index){
              this.parts.splice(index, 1);
            },
            hapus_dealer: function(index){
              this.dealers.splice(index, 1);
            },
            hapus_range: function(index){
              return this.ranges.splice(index, 1);
            },
            hapus_general_range: function(index){
              return this.general_ranges.splice(index, 1);
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
            get_currency: function(type){
              if(type == 'Rupiah') return 'Rp';
              if(type == 'Persen') return '%'
            },
            get_currency_position: function(type){
              if(type == 'Rupiah') return 'prefix';
              if(type == 'Persen') return 'suffix'
            },
          },
          computed: {
            filtered_dealers: function(){
              filter_nama_customer = this.filter_nama_customer;
              return _.chain(this.dealers)
              .filter(function(data){
                if(filter_nama_customer != ''){
                  return data.nama_dealer.toUpperCase().includes(filter_nama_customer.toUpperCase());
                }
                return true;
              })
              .value();
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-md-6">
              <a href="h3/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
            </div>
            <div class="col-md-6 text-right">
              <a href="h3/h3_md_update_diskon">
                <button class="btn bg-blue btn-flat margin">Update diskon</button>
              </a>
            </div>
          </div>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/session_message'); ?>
        <?php $this->load->view('template/normal_session_message'); ?>
        <table id="diskon_oli_reguler" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Part Number</th>
              <th>Part Deskripsi</th>
              <th>Kelompok Part</th>
              <th>HET</th>
              <th>Status</th>
              <th>Range 1</th>
              <th>Range 2</th>
              <th>Range 3</th>
              <th>Tanggal dibuat</th>
              <th>Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>    
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function() {
          diskon_oli_reguler = $('#diskon_oli_reguler').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              initComplete: function () {
                $('#diskon_oli_reguler_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                $('#diskon_oli_reguler_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');

                axios.get('html/filter_diskon_oli_reguler')
                .then(function(res){
                  $('#diskon_oli_reguler_filter').prepend(res.data);

                  $('#filter_active').change(function(){
                    diskon_oli_reguler.draw();
                  });
                });
              },
              ajax: {
                  url: "<?= base_url('api/md/h3/diskon_oli_reguler') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function (data){
                    data.active = $('#filter_active').val();
                  }
              },
              columns: [
                  { data: null, orderable: false, width: '3%' },
                  { data: 'id_part' },
                  { data: 'nama_part' },
                  { data: 'kelompok_part' },
                  { data: 'het' },
                  { data: 'status' },
                  { data: 'range_1', orderable: false },
                  { data: 'range_2', orderable: false },
                  { data: 'range_3', orderable: false },
                  { data: 'created_at' },
                  { 
                    data: 'active',
                    render: function(data){
                      if(data == 1){
                        return '<i class="glyphicon glyphicon-ok"></i>';
                      }
                      return '<i class="glyphicon glyphicon-remove"></i>';
                    },
                    width: '3%', 
                    className: 'text-center',
                    orderable: false,
                  },
                  { data: 'action', orderable: false, width: '3%', className: 'text-center' }
              ],
          });

          diskon_oli_reguler.on('draw.dt', function() {
            var info = diskon_oli_reguler.page.info();
            diskon_oli_reguler.column(0, {
                search: 'applied',
                order: 'applied',
                page: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start + ".";
            });
          });
      });
    </script>
    <?php endif; ?>
  </section>
</div>