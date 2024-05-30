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
    <div id="vueForm" class="box box-default">
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
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode Part</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.id_part != null }">
                    <input v-model="diskon_part_tertentu.id_part" type="text" class="form-control form-control-sm" readonly>
                    <small v-if="error_exist('id_part')" class="form-text text-danger">{{ get_error('id_part') }}</small>                  
                  </div>
                  <div class="col-sm-1 no-padding">
                    <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#h3_md_parts_diskon_part_tertentu"><i class="fa fa-search"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_parts_diskon_part_tertentu'); ?>
                  <script>
                    function pilih_parts_diskon_part_tertentu(data){
                      vueForm.diskon_part_tertentu.id_part_int = data.id_part_int;
                      vueForm.diskon_part_tertentu.id_part = data.id_part;
                      vueForm.diskon_part_tertentu.nama_part = data.nama_part;
                      vueForm.diskon_part_tertentu.harga_dealer_user = data.harga_dealer_user;
                      vueForm.diskon_part_tertentu.kelompok_part = data.kelompok_part;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Deksripsi Part</label>
                  <div class="col-sm-3">                      
                    <input v-model="diskon_part_tertentu.nama_part" type="text" class="form-control form-control-sm" readonly>
                  </div>                                 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">HET</label>
                  <div class="col-sm-3">
                    <vue-numeric currency="Rp " thousand-separator='.' v-model="diskon_part_tertentu.harga_dealer_user" class="form-control form-control-sm" disabled></vue-numeric>
                  </div>                         
                  <label class="col-sm-offset-1 col-sm-2 control-label">Kelompok Part</label>
                  <div class="col-sm-3">
                    <input disabled v-model='diskon_part_tertentu.kelompok_part' type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe Diskon</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.tipe_diskon != null }">                      
                    <select :disabled="mode == 'detail'" class="form-control form-control-sm" v-model="diskon_part_tertentu.tipe_diskon">
                      <option value="">-Pilih-</option>
                      <option value="Rupiah">Rupiah</option>
                      <option value="Persen">Persen</option>
                    </select>
                    <small v-if="error_exist('tipe_diskon')" class="form-text text-danger">{{ get_error('tipe_diskon') }}</small>                  
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Diskon Fixed Order</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.diskon_fixed != null }">                      
                    <vue-numeric :readonly="mode == 'detail'" v-model="diskon_part_tertentu.diskon_fixed" precision='1' thousand-separator='.' class="form-control"  :currency='get_currency_symbol(diskon_part_tertentu)' :currency-symbol-position='get_currency_position(diskon_part_tertentu)'/>
                  </div>  
                  <label class="col-sm-offset-1 col-sm-2 control-label">Diskon Reguler</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.diskon_reguler != null }">                      
                    <vue-numeric :readonly="mode == 'detail'" v-model="diskon_part_tertentu.diskon_reguler" precision='1' thousand-separator='.' class="form-control"  :currency='get_currency_symbol(diskon_part_tertentu)' :currency-symbol-position='get_currency_position(diskon_part_tertentu)'/>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Diskon Hotline</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.diskon_hotline != null }">                      
                    <vue-numeric :readonly="mode == 'detail'" v-model="diskon_part_tertentu.diskon_hotline" precision='1' thousand-separator='.' class="form-control"  :currency='get_currency_symbol(diskon_part_tertentu)' :currency-symbol-position='get_currency_position(diskon_part_tertentu)'/>
                  </div>  
                  <label class="col-sm-offset-1 col-sm-2 control-label">Diskon Urgent</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.diskon_urgent != null }">                      
                    <vue-numeric :readonly="mode == 'detail'" v-model="diskon_part_tertentu.diskon_urgent" precision='1' thousand-separator='.' class="form-control"  :currency='get_currency_symbol(diskon_part_tertentu)' :currency-symbol-position='get_currency_position(diskon_part_tertentu)'/>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Diskon Other</label>
                  <div class="col-sm-3" v-bind:class="{ 'has-error' : errors.diskon_other != null }">                      
                    <vue-numeric :readonly="mode == 'detail'" v-model="diskon_part_tertentu.diskon_other" precision='1' thousand-separator='.' class="form-control"  :currency='get_currency_symbol(diskon_part_tertentu)' :currency-symbol-position='get_currency_position(diskon_part_tertentu)'/>
                  </div>  
                </div>
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label no-padding-top">Active</label>
                  <div class="col-sm-6">                    
                    <input :disabled='mode == "detail"' v-model="diskon_part_tertentu.active" type="checkbox" true-value="1" false-value="0">
                  </div>                                
                </div>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-offset-8 col-sm-2">
                      <label class="control-label">Nama Customer</label>
                      <input type="text" class="form-control" v-model='filter_nama_customer'>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <label class="control-label">Kabupaten</label>
                        <div class="input-group">
                          <input v-model='filter_kabupaten' type="text" class="form-control" disabled>
                          <div class="input-group-btn">
                            <button v-if='filter_id_kabupaten == ""' class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kabupaten_filter_diskon_part_tertentu'>
                              <i class="fa fa-search"></i>
                            </button>
                            <button v-if='filter_id_kabupaten != ""' class="btn btn-flat btn-danger" @click.prevent='reset_filter_kabupaten'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_kabupaten_filter_diskon_part_tertentu'); ?>
                    <script>
                      function pilih_kabupaten_filter_diskon_part_tertentu(data){
                        vueForm.filter_kabupaten = data.kabupaten;
                        vueForm.filter_id_kabupaten = data.id_kabupaten;
                      }
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table class="table table-condensed table-hover">
                      <tr>
                        <td width='3%'>No.</td>
                        <td width='10%'>Kode Customer</td>
                        <td>Nama Customer</td>
                        <td>Alamat</td>
                        <td>Kabupaten</td>
                        <td width="10%">Tipe</td>
                        <td width="10%">Diskon Fixed Order</td>
                        <td width="10%">Diskon Reguler</td>
                        <td width="10%">Diskon Hotline</td>
                        <td width="10%">Diskon Urgent</td>
                        <td width="10%">Diskon Other</td>
                        <td v-if="mode != 'detail'" width="3%"></td>
                      </tr>
                      <tr v-for="(each, index) in filtered_dealers">
                        <td class="align-middle">{{ index + 1 }}.</td>
                        <td class="align-middle">{{ each.kode_dealer_md }}</td>
                        <td class="align-middle">{{ each.nama_dealer }}</td>
                        <td class="align-middle">{{ each.alamat }}</td>
                        <td class="align-middle">{{ each.kabupaten }}</td>
                        <td class="align-middle">
                          <select :disabled="mode == 'detail'" class="input-compact" v-model='each.tipe_diskon'>
                            <option value="">-Pilih</option>
                            <option value="Rupiah">Rupiah</option>
                            <option value="Persen">Persen</option>
                          </select>
                        </td>
                        <td class="align-middle">
                          <vue-numeric :read-only="mode == 'detail'" v-bind:precision="2" v-model='each.diskon_fixed' :minus='false' :min='0' thousand-separator='.' class='input-compact' :currency='get_currency_symbol(each)' :currency-symbol-position='get_currency_position(each)'></vue-numeric>
                        </td>
                        <td class="align-middle">
                          <vue-numeric :read-only="mode == 'detail'" v-bind:precision="2" v-model='each.diskon_reguler' thousand-separator='.' class='input-compact' :currency='get_currency_symbol(each)' :currency-symbol-position='get_currency_position(each)'></vue-numeric>
                        </td>
                        <td class="align-middle">
                          <vue-numeric :read-only="mode == 'detail'" v-bind:precision="2" v-model='each.diskon_hotline' thousand-separator='.' class='input-compact' :currency='get_currency_symbol(each)' :currency-symbol-position='get_currency_position(each)'></vue-numeric>
                        </td>
                        <td class="align-middle">
                          <vue-numeric :read-only="mode == 'detail'" v-bind:precision="2" v-model='each.diskon_urgent' thousand-separator='.' class='input-compact' :currency='get_currency_symbol(each)' :currency-symbol-position='get_currency_position(each)'></vue-numeric>
                        </td>
                        <td class="align-middle">
                          <vue-numeric :read-only="mode == 'detail'" v-bind:precision="2" v-model='each.diskon_other' thousand-separator='.' class='input-compact' :currency='get_currency_symbol(each)' :currency-symbol-position='get_currency_position(each)'></vue-numeric>
                        </td>
                        <td v-if="mode != 'detail'" @click='remove_range(index)' class="text-right no-padding align-middle"><button class="btn btn-flat btn-sm btn-danger" type='button'><i class="fa fa-trash-o"></i></button></td>
                      </tr>
                      <tr v-if="filtered_dealers.length < 1">
                        <td class="text-center" colspan="10">Tidak ada data</td>
                      </tr>
                    </table> 
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <button v-if="mode != 'detail'" data-toggle="modal" data-target="#h3_md_dealer_diskon_part_tertentu" class="btn btn-flat btn-sm btn-primary pull-right" type="button"><i class="fa fa-plus"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_diskon_part_tertentu') ?>
                  <script>
                    function pilih_dealer_diskon_part_tertentu(data){
                      vueForm.dealers.push(data);
                      h3_md_dealer_diskon_part_tertentu_datatable.draw();
                    }
                  </script>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" @click.prevent='<?= $form ?>' class="btn btn-flat btn-primary btn-sm" type="button">Submit</button>
                  <button v-if="mode == 'edit'" @click.prevent='<?= $form ?>' class="btn btn-flat btn-warning btn-sm" type="button">Update</button>
                  <a v-if="mode == 'detail'" :href="'h3/h3_md_diskon_part_tertentu/edit?id=' + diskon_part_tertentu.id" class="btn btn-sm btn-flat btn-warning">Edit</a>
                  <a v-if="mode == 'detail'" :href="'h3/h3_md_diskon_part_tertentu/delete?id=' + diskon_part_tertentu.id" class="btn btn-sm btn-flat btn-danger" onclick='return confirm("Apakah anda yakin ingin menghapus data ini?")'>Hapus</a>
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
            loading: false,
            errors: {},
            mode: '<?= $mode ?>',
            filter_nama_customer: '',
            filter_kabupaten: '',
            filter_id_kabupaten: '',
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            diskon_part_tertentu: <?= json_encode($diskon_part_tertentu) ?>,
            dealers: <?= json_encode($items) ?>,
            <?php else: ?>
            diskon_part_tertentu: {
              id_part_int: 0,
              id_part: '',
              nama_part: '',
              harga_dealer_user: '',
              tipe_diskon: '',
              diskon_fixed: 0,
              diskon_reguler: 0,
              diskon_hotline: 0,
              diskon_urgent: 0,
              diskon_other: 0,
              active: 1,
            },
            dealers: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.diskon_part_tertentu, [
                'id_part_int', 'id_part', 'tipe_diskon', 'diskon_fixed', 'diskon_reguler',
                'diskon_hotline', 'diskon_urgent', 'diskon_other', 'active'
              ]);
              if(this.mode == 'edit'){
                post.id = this.diskon_part_tertentu.id;
              }
              post.items = _.map(this.dealers, function(d) {
                return _.pick(d, [
                  'id_dealer', 'tipe_diskon', 'diskon_fixed', 'diskon_reguler',
                  'diskon_hotline', 'diskon_urgent', 'diskon_other',
                ]);
              });

              this.loading = true;
              this.errors = {};
              axios.post('h3/h3_md_diskon_part_tertentu/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_diskon_part_tertentu/detail?id=' + res.data.id;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  vueForm.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
              })
              .then(function(){ vueForm.loading = false; });
            },
            remove_range: function(index){
              this.dealers.splice(index, 1)
            },
            get_currency_position: function(data){
              if(data.tipe_diskon == 'Rupiah'){
                return 'prefix';
              }
              return 'suffix';
            },
            get_currency_symbol: function(data){
              if(data.tipe_diskon == 'Rupiah'){
                return 'Rp';
              }
              return '%';
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
            reset_filter_kabupaten: function(){
              this.filter_kabupaten = '';
              this.filter_id_kabupaten = '';
            }
          },
          computed: {
            filtered_dealers: function(){
              filter_id_kabupaten = this.filter_id_kabupaten;
              filter_nama_customer = this.filter_nama_customer;
              return _.chain(this.dealers)
              .filter(function(data){
                if(filter_id_kabupaten != ''){
                  return data.id_kabupaten == filter_id_kabupaten;
                }
                return true;
              })
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
        <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='id_kelompok_part_filter' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_diskon_part_tertentu_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_diskon_part_tertentu_index'); ?>         
                <script>
                function pilih_kelompok_part_filter_diskon_part_tertentu_index (data, type) {
                  if(type == 'add_filter'){
                    $('#id_kelompok_part_filter').val(data.id_kelompok_part);
                  }else if(type == 'reset_filter'){
                    $('#id_kelompok_part_filter').val('');
                  }
                  diskon_part_tertentu.draw();
                  h3_md_kelompok_part_filter_diskon_part_tertentu_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Part Number/Deksripsi</label>
                  <div class="col-sm-8">
                    <input id='part_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#part_filter').on("keyup", _.debounce(function(){
                      diskon_part_tertentu.draw();
                    }, 500));
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Status</label>
                  <div class="col-sm-8">
                    <select id="active_filter" class="form-control">
                      <option value="">All</option>
                      <option value="1">Active</option>
                      <option value="0">Not Active</option>
                    </select>
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#active_filter').on("change", function(){
                      diskon_part_tertentu.draw();
                    });
                  });
                </script>
              </div>
            </div>
          </form>
        </div>
        <table id="diskon_part_tertentu" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Part</th>              
              <th>Nama</th>              
              <th>Kelompok Part</th>              
              <th>HET</th>              
              <th>Diskon Fixed</th>              
              <th>Diskon Reguler</th>              
              <th>Diskon Urgent</th>              
              <th>Diskon Hotline</th>              
              <th>Diskon Other</th>              
              <th>Status</th>              
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
          diskon_part_tertentu = $('#diskon_part_tertentu').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/diskon_part_tertentu') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.part_filter = $('#part_filter').val();
                    d.active_filter = $('#active_filter').val();
                    d.id_kelompok_part_filter = $('#id_kelompok_part_filter').val();
                  }
              },
              columns: [
                  { data: null, orderable: false, width: '3%' },
                  { data: 'id_part' },
                  { data: 'nama_part' },
                  { data: 'kelompok_part' },
                  { data: 'het', name: 'harga_dealer_user', width: '10%' },
                  { 
                    data: 'diskon_fixed',
                    render: function(data, type, row){
                      data = parseFloat(data);
                      if(row.tipe_diskon == 'Rupiah'){
                        return 'Rp ' + accounting.formatNumber(data, 0, '.');
                      }else if(row.tipe_diskon == 'Persen'){
                        decimal_number = data % 1 == 0 ? 0 : 2;
                        return accounting.formatNumber(data, decimal_number, '.') + '%';
                      }
                      return data;
                    }
                  },
                  { 
                    data: 'diskon_reguler',
                    render: function(data, type, row){
                      data = parseFloat(data);
                      if(row.tipe_diskon == 'Rupiah'){
                        return 'Rp ' + accounting.formatNumber(data, 0, '.');
                      }else if(row.tipe_diskon == 'Persen'){
                        decimal_number = data % 1 == 0 ? 0 : 2;
                        return accounting.formatNumber(data, decimal_number, '.') + '%';
                      }
                      return data;
                    }
                  },
                  { 
                    data: 'diskon_urgent',
                    render: function(data, type, row){
                      data = parseFloat(data);
                      if(row.tipe_diskon == 'Rupiah'){
                        return 'Rp ' + accounting.formatNumber(data, 0, '.');
                      }else if(row.tipe_diskon == 'Persen'){
                        decimal_number = data % 1 == 0 ? 0 : 2;
                        return accounting.formatNumber(data, decimal_number, '.') + '%';
                      }
                      return data;
                    }
                  },
                  { 
                    data: 'diskon_hotline',
                    render: function(data, type, row){
                      data = parseFloat(data);
                      if(row.tipe_diskon == 'Rupiah'){
                        return 'Rp ' + accounting.formatNumber(data, 0, '.');
                      }else if(row.tipe_diskon == 'Persen'){
                        decimal_number = data % 1 == 0 ? 0 : 2;
                        return accounting.formatNumber(data, decimal_number, '.') + '%';
                      }
                      return data;
                    }
                  },
                  { 
                    data: 'diskon_other',
                    render: function(data, type, row){
                      data = parseFloat(data);
                      if(row.tipe_diskon == 'Rupiah'){
                        return 'Rp ' + accounting.formatNumber(data, 0, '.');
                      }else if(row.tipe_diskon == 'Persen'){
                        decimal_number = data % 1 == 0 ? 0 : 2;
                        return accounting.formatNumber(data, decimal_number, '.') + '%';
                      }
                      return data;
                    }
                  },
                  { 
                    data: 'active',
                    render: function(data){
                      if(data == 1){
                        return '<i class="glyphicon glyphicon-ok"></i>';
                      }
                      return '<i class="glyphicon glyphicon-remove"></i>';
                    },
                    orderable: false,
                    width: '5%'
                  },
                  { data: 'action', orderable: false, width: '3%' }
              ],
          });

          diskon_part_tertentu.on('draw.dt', function() {
            var info = diskon_part_tertentu.page.info();
            diskon_part_tertentu.column(0, {
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