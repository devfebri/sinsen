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

    <div id="app" class="box box-default">
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
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="" class="control-label col-sm-2">Kode SIM Part</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='sim_part.id_sim_part'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Tanggal Mulai Berlaku</label> 
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_mulai_berlaku') }" class="col-sm-4">
                      <input id='tanggal_mulai_berlaku' type="text" class="form-control" readonly>
                      <small v-if="error_exist('tanggal_mulai_berlaku')" class="form-text text-danger">{{ get_error('tanggal_mulai_berlaku') }}</small>
                    </div>
                    <!-- <label for="inputEmail3" class="col-sm-2 control-label">Kategori SIM Part berdasarkan Pit</label>
                    <div v-bind:class="{ 'has-error': error_exist('batas_bawah_jumlah_pit') || error_exist('batas_atas_jumlah_pit') }"class="col-sm-4">
                      <div class="input-group">
                        <vue-numeric class="form-control" :disabled='mode == "detail"' currency-symbol-position='suffix' currency='Pit' v-model='sim_part.batas_bawah_jumlah_pit'></vue-numeric>
                        <span class="input-group-addon">s/d</span>
                        <vue-numeric class="form-control" :disabled='mode == "detail"' currency-symbol-position='suffix' currency='Pit' v-model='sim_part.batas_atas_jumlah_pit'></vue-numeric>
                      </div>
                      <small v-if="error_exist('batas_bawah_jumlah_pit') || error_exist('batas_atas_jumlah_pit')" class="form-text text-danger">{{ get_error('batas_bawah_jumlah_pit') || get_error('batas_atas_jumlah_pit') }}</small>
                    </div> -->
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Pilih Kategori SIM Part</label>
                    <div v-bind:class="{ 'has-error': error_exist('kategori_sim_part') }" class="col-sm-2">
                      <select :disabled='mode != "insert"' class="form-control" v-model="sim_part.kategori_sim_part">
                        <option value="">-Pilih-</option>
                        <option value="ue">Unit Entry</option>
                        <option disabled value="pit">Pit</option>
                      </select>
                      <small v-if="error_exist('kategori_sim_part')" class="form-text text-danger">{{ get_error('kategori_sim_part') }}</small>
                    </div> 
                  </div>
                  
                  <div v-if='sim_part.kategori_sim_part=="ue"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori SIM Part berdasarkan UE</label>
                    <div v-bind:class="{ 'has-error': error_exist('batas_bawah_jumlah_ue') || error_exist('batas_atas_jumlah_ue') }"class="col-sm-4">
                      <div class="input-group">
                        <vue-numeric class="form-control" :disabled='mode == "detail"' currency-symbol-position='suffix' currency='UE' v-model='sim_part.batas_bawah_jumlah_ue'></vue-numeric>
                        <span class="input-group-addon">s/d</span>
                        <vue-numeric class="form-control" :disabled='mode == "detail"' currency-symbol-position='suffix' currency='UE' v-model='sim_part.batas_atas_jumlah_ue'></vue-numeric>
                      </div>
                      <small v-if="error_exist('batas_atas_jumlah_ue') || error_exist('batas_bawah_jumlah_ue')" class="form-text text-danger">{{ get_error('batas_atas_jumlah_ue') || get_error('batas_bawah_jumlah_ue') }}</small>
                    </div>
                  </div>

                  <div v-if='sim_part.kategori_sim_part=="pit"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori SIM Part berdasarkan PIT</label>
                      <div v-bind:class="{ 'has-error': error_exist('batas_bawah_jumlah_pit') || error_exist('batas_atas_jumlah_pit') }"class="col-sm-4">
                        <div class="input-group">
                          <vue-numeric class="form-control" :disabled='mode == "detail"' currency-symbol-position='suffix' currency='Pit' v-model='sim_part.batas_bawah_jumlah_pit'></vue-numeric>
                          <span class="input-group-addon">s/d</span>
                          <vue-numeric class="form-control" :disabled='mode == "detail"' currency-symbol-position='suffix' currency='Pit' v-model='sim_part.batas_atas_jumlah_pit'></vue-numeric>
                        </div>
                        <small v-if="error_exist('batas_bawah_jumlah_pit') || error_exist('batas_atas_jumlah_pit')" class="form-text text-danger">{{ get_error('batas_bawah_jumlah_pit') || get_error('batas_atas_jumlah_pit') }}</small>
                      </div>
                  </div>
                  <div class="form-group">
                    <div v-if='mode != "insert"'>
                      <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                      <div class="col-sm-4">
                        <input type="checkbox" v-model='sim_part.active' true-value='1' false-value='0' :disabled='mode == "detail"'>
                      </div>
                    </div>
                    <div  v-bind:class="{ 
                      'col-sm-offset-8': mode == 'insert',
                      'col-sm-offset-2': mode != 'insert',
                    }" class="col-sm-4">
                      <!-- <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary" @click.prevent='get_dealers'>Generate Customer</button> -->
                    </div>
                  </div>
                  <div class="container-fluid no-padding">
                    <div class="row">
                      <div class="col-sm-offset-8 col-sm-2">
                        <div class="form-group">
                          <label class="control-label">Kabupaten</label>
                          <div class="input-group">
                            <input v-model='filter_kabupaten' type="text" class="form-control" disabled>
                            <div class="input-group-btn">
                              <button v-if='filter_id_kabupaten == ""' class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kabupaten_filter_sim_part'>
                                <i class="fa fa-search"></i>
                              </button>
                              <button v-if='filter_id_kabupaten != ""' class="btn btn-flat btn-danger" @click.prevent='reset_filter_kabupaten'><i class="fa fa-trash-o"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php $this->load->view('modal/h3_md_kabupaten_filter_sim_part'); ?>
                      <script>
                        function pilih_kabupaten_filter_sim_part(data){
                          app.filter_kabupaten = data.kabupaten;
                          app.filter_id_kabupaten = data.id_kabupaten;
                        }
                      </script>
                      <div v-if='sim_part.kategori_sim_part=="pit"' class="col-sm-2">
                        <label class="control-label">Jumlah PIT</label>
                        <select v-model='filter_jumlah_pit' class="form-control">
                          <option value="">All</option>
                          <option v-for='jumlah_pit in list_jumlah_pit' :value="jumlah_pit">{{ jumlah_pit }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="container-fluid bg-primary" style='padding: 5px 0;'>
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <span class='text-bold'>Customer</span>
                      </div>
                    </div>
                  </div>
                  <table class="table table-condensed">
                    <tr>
                      <td width='3%'>No.</td>
                      <td>Kode Customer</td>
                      <td>Nama Customer</td>
                      <td>Alamat</td>
                      <td>Kota / Kabupaten</td>
                      <td v-if='sim_part.kategori_sim_part=="pit"'>Jumlah PIT</td>
                      <td v-if='sim_part.kategori_sim_part=="ue"'>Aktual UE</td>
                      <td v-if='sim_part.kategori_sim_part=="ue"'>Target UE</td>
                      <td v-if='sim_part.kategori_sim_part=="ue"'></td>
                    </tr>
                    <tr v-if='filtered_dealers.length > 0' v-for='(dealer, index) of filtered_dealers'>
                      <td width='3%'>{{ index + 1 }}. <input type="hidden" value="@{{ dealer.id_dealer }}" v-model="dealer.id_dealer"/></td>
                      <td>{{ dealer.kode_dealer_md }}</td>
                      <td>{{ dealer.nama_dealer }}</td>
                      <td>{{ dealer.alamat }}</td>
                      <td>{{ dealer.kabupaten }}</td>
                      <td v-if='sim_part.kategori_sim_part=="pit"' class='text-center'>
                        <vue-numeric read-only v-model='dealer.jumlah_pit'></vue-numeric>
                      </td>
                      <td v-if='mode != "detail"'>
                        <vue-numeric  v-model='dealer.jumlah_ue' class='form-control' separator='.'></vue-numeric>
                      </td>  
                      <td v-else>
                        <vue-numeric readonly v-model='dealer.jumlah_ue' class='form-control' separator='.'></vue-numeric>
                      </td> 
                      <td v-if='mode != "detail"'>
                        <vue-numeric  v-model='dealer.target_ue' class='form-control' separator='.'></vue-numeric>
                      </td>  
                      <td v-else>
                        <vue-numeric readonly v-model='dealer.target_ue' class='form-control' separator='.'></vue-numeric>
                      </td>
                      <td v-if='mode != "detail" && sim_part.kategori_sim_part=="ue"'>
                        <button class="btn btn-flat btn-danger btn-sm " @click.prevent='hapus_dealer(index)'><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <!-- <tr v-if='filtered_dealers.length < 1'>
                      <td colspan='6' class='text-center'>Tidak ada data.</td>
                    </tr> -->
                    
                    <tr v-if='dealers.length < 1 && sim_part.kategori_sim_part=="pit"'>
                      <td colspan='6' class='text-center'>Tidak ada data.</td>
                    </tr>
                    <tr v-if='dealers.length < 1 && sim_part.kategori_sim_part=="ue"'>
                      <td colspan='5' class='text-center'>Tidak ada data.</td>
                    </tr>
                  </table>
                  <div class="container-fluid">
                      <div class="row">
                        <div v-if='sim_part.kategori_sim_part=="ue"' class="col-sm-12 text-right">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary btn-sm" type='button' data-toggle='modal' data-target='#h3_md_dealer_sim_part'><i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                    <?php $this->load->view('modal/h3_md_dealer_sim_part') ?>
                    <script>
                      function pilih_dealer_sim_part(data){
                        app.dealers.push(data);
                        h3_md_dealer_sim_part_datatable.draw();
                      }
                    </script>
                </div>
                <br>

                  <div class="container-fluid bg-primary" style='padding: 5px 0;'>
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <span class='text-bold'>SIM PART</span>
                      </div>
                    </div>
                  </div>
                  <table class="table table-condensed">
                    <tr>
                      <td width='3%'>No.</td>
                      <td>Part Number</td>
                      <td>Part Description</td>
                      <td>Kelompok Part</td>
                      <td>HET</td>
                      <td width='8%'>Status</td>
                      <td width='8%'>Qty SIM Part</td>
                      <td v-if='mode != "detail"' width='3%'></td>
                    </tr>
                    <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                      <td width='3%'>{{ index + 1 }}.</td>
                      <td>{{ part.id_part }}</td>
                      <td>{{ part.nama_part }}</td>
                      <td>{{ part.kelompok_part }}</td>
                      <td>{{ part.het }}</td>
                      <td>{{ part.status }}</td>
                      <td>
                        <vue-numeric :read-only='mode == "detail"' v-model='part.qty_sim_part' class='form-control' separator='.'></vue-numeric>
                      </td>
                      <td v-if='mode != "detail"'>
                        <button class="btn btn-flat btn-danger btn-sm" @click.prevent='hapus_part(index)'><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if='parts.length < 1'>
                      <td colspan='6' class='text-center'>Tidak ada data.</td>
                    </tr>
                  </table>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary btn-sm" type='button' data-toggle='modal' data-target='#h3_md_parts_sim_part'><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_parts_sim_part'); ?>
                  <script>
                    function pilih_parts_sim_part(data) {
                      app.parts.push(data);
                      h3_md_parts_sim_part_datatable.draw();
                    }
                  </script>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id_sim_part=' + sim_part.id_sim_part" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var app = new Vue({
      el: '#app',
      data: {
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        filter_jumlah_pit: '',
        filter_kabupaten: '',
        filter_id_kabupaten: '',
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        sim_part: <?= json_encode($sim_part) ?>,
        // dealers: [],
        dealers: <?= json_encode($dealers) ?>,
        parts: <?= json_encode($parts) ?>,
        <?php else: ?>
        sim_part: {
          tanggal_mulai_berlaku: '',
          kategori_sim_part: '',
          batas_bawah_jumlah_pit: 0,
          batas_atas_jumlah_pit: 0,
          batas_bawah_jumlah_ue: 0,
          batas_atas_jumlah_ue: 0,
          active: 1,
        },
        dealers: [],
        parts: [],
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.sim_part, [
            'id_sim_part', 'tanggal_mulai_berlaku', 'batas_bawah_jumlah_pit', 'batas_atas_jumlah_pit','batas_atas_jumlah_ue', 'batas_bawah_jumlah_ue', 'active','kategori_sim_part', 'id_dealer','jumlah_ue'
          ]);
          post.parts = _.map(this.parts, function(part){
            return _.pick(part, ['id_part', 'qty_sim_part']);
          });

          post.dealers = _.map(this.dealers, function(d) {
            return _.pick(d, [
              'id_dealer','jumlah_ue','target_ue'
            ]);
          });
          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            window.location = 'h3/<?= $isi ?>/detail?id_sim_part=' + res.data.id_sim_part;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              app.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){ app.loading = false; })
          ;
        },
        reset_filter_kabupaten: function(){
          this.filter_id_kabupaten = '';
          this.filter_kabupaten = '';
        },
        hapus_part: function (index) {
          this.parts.splice(index, 1);
          h3_md_parts_sim_part_datatable.draw();
        },
        hapus_dealer: function (index) {
          this.dealers.splice(index, 1);
          h3_md_dealer_sim_part_datatable.draw();
        },
        // get_dealers: function(){
        //   this.loading = true;
        //   axios.get('h3/<?= $isi ?>/get_dealers', {
        //     params: {
        //       batas_bawah_jumlah_pit: this.sim_part.batas_bawah_jumlah_pit,
        //       batas_atas_jumlah_pit: this.sim_part.batas_atas_jumlah_pit,
        //     }
        //   })
        //   .then(function(res){
        //     app.dealers = res.data;
        //   })
        //   .catch(function(err){
        //     toastr.error(err);
        //   })
        //   .then(function(){
        //     app.loading = false;
        //   });
        // },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        filtered_dealers: function(){
          filter_jumlah_pit = this.filter_jumlah_pit;
          filter_id_kabupaten = this.filter_id_kabupaten;
          return _.chain(this.dealers)
          .filter(function(data){
            if(filter_jumlah_pit != ''){
              return data.jumlah_pit == filter_jumlah_pit
            }
            return true;
          })
          .filter(function(data){
            if(filter_id_kabupaten != ''){
              return data.id_kabupaten == filter_id_kabupaten;
            }
            return true;
          })
          .value();
        },
        list_jumlah_pit: function(){
          return _.chain(this.dealers)
          .uniqBy(function(data){
            return data.jumlah_pit;
          })
          .map(function(data){
            return data.jumlah_pit;
          })
          .value();
        }
      },
      mounted: function(){
        // if(this.mode != 'insert'){
        //   this.get_dealers();
        // }

        $(document).ready(function(){
          $('#tanggal_mulai_berlaku').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
          })
          .on('changeDate', function(e){
            app.sim_part.tanggal_mulai_berlaku = e.format('yyyy-mm-dd');
          });
        });

        if(this.mode == "detail"){
          date = new Date(this.sim_part.tanggal_mulai_berlaku);
          $(document).ready(function(){
            $("#tanggal_mulai_berlaku").datepicker("setDate", date);
            $('#tanggal_mulai_berlaku').datepicker('update');
          });
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
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="master_sim_part" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nomor SIM Part</th>
              <th>Kategori SIM Part</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_sim_part = $('#master_sim_part').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_sim_part') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'id_sim_part' },
                    { data: 'kategori_sim_part', orderable: false, width: '15%' },
                    {
                      data: 'active',
                      render: function(data){
                        if(data == 1){
                          return '<i class="glyphicon glyphicon-ok"></i>'
                        }
                        return '<i class="glyphicon glyphicon-remove"></i>'
                      },
                      width: '5%',
                      className: 'text-center',
                      orderable: false,
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_sim_part.on('draw.dt', function() {
              var info = master_sim_part.page.info();
              master_sim_part.column(0, {
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
    <?php } ?>
  </section>
</div>