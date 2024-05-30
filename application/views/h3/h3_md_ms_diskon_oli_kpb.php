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
  <h1><?=$title; ?></h1>
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
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Part</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_part') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='diskon_oli_kpb.id_part'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_part_diskon_oli_kpb'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_part')" class="form-text text-danger">{{ get_error('id_part') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_part_diskon_oli_kpb'); ?>
                    <script>
                      function pilih_part_diskon_oli_kpb(data) {
                        app.diskon_oli_kpb.id_part = data.id_part;
                        app.diskon_oli_kpb.nama_part = data.nama_part;
                        app.diskon_oli_kpb.harga_dealer_user = data.harga_dealer_user;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">HET</label>
                    <div class="col-sm-4">
                      <vue-numeric disabled v-model='diskon_oli_kpb.harga_dealer_user' class="form-control" separator='.' currency='Rp'></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Part</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='diskon_oli_kpb.nama_part'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Diskon Oli</label>
                    <div v-bind:class="{ 'has-error': error_exist('diskon_value') }" class="col-sm-4">
                      <vue-numeric class="form-control" separator='.' v-model='diskon_oli_kpb.diskon_value' :readonly='mode == "detail"' :currency='get_currency(diskon_oli_kpb.tipe_diskon)' :currency-symbol-position='get_currency_position(diskon_oli_kpb.tipe_diskon)'></vue-numeric>
                      <small v-if="error_exist('diskon_value')" class="form-text text-danger">{{ get_error('diskon_value') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Produksi</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='diskon_oli_kpb.tipe_produksi'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga KPB</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" separator='.' v-model='harga_kpb' disabled currency='Rp'></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_tipe_kendaraan') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='diskon_oli_kpb.id_tipe_kendaraan'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_kendaraan_diskon_oli_kpb'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_tipe_kendaraan')" class="form-text text-danger">{{ get_error('id_tipe_kendaraan') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_tipe_kendaraan_diskon_oli_kpb'); ?>
                    <script>
                      function pilih_tipe_kendaraan_diskon_oli_kpb(data) {
                        app.diskon_oli_kpb.tipe_produksi = data.tipe_produksi;
                        app.diskon_oli_kpb.id_tipe_kendaraan = data.id_tipe_kendaraan;
                        app.diskon_oli_kpb.nama_tipe_kendaraan = data.tipe_ahm;
                        app.diskon_oli_kpb.tahun_kendaraan = data.tgl_awal;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='diskon_oli_kpb.tahun_kendaraan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                    <div v-bind:class="{ 'has-error': error_exist('tipe_diskon') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control"  v-model='diskon_oli_kpb.tipe_diskon'>
                        <option value="">-Pilih-</option>
                        <option value="Rupiah">Rupiah</option>
                        <option value="Persen">Persen</option>
                      </select>
                      <small v-if="error_exist('tipe_diskon')" class="form-text text-danger">{{ get_error('tipe_diskon') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Tipe Kendaraan</label>
                      <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='diskon_oli_kpb.nama_tipe_kendaraan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div class="col-sm-4">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='diskon_oli_kpb.active'>
                    </div>
                    
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + diskon_oli_kpb.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        index_part: 0,
        loading: false,
        errors: {},
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        diskon_oli_kpb: <?= json_encode($diskon_oli_kpb) ?>,
        <?php else: ?>
        diskon_oli_kpb: {
          id_part: '',
          nama_part: '',
          harga_dealer_user: 0,
          id_dealer: '',
          nama_dealer: '',
          tipe_produksi: '',
          id_tipe_kendaraan: '',
          nama_tipe_kendaraan: '',
          tahun_kendaraan: '',
          tipe_diskon: '',
          diskon_value: '',
          active: 1
        }
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          keys = [
            'id_part', 'tipe_produksi', 'id_tipe_kendaraan', 'tipe_diskon', 'diskon_value', 'active'
          ];

          if(this.mode == 'edit'){
            keys.push('id');
          }
          post = _.pick(this.diskon_oli_kpb, keys);
          post.harga_kpb = this.harga_kpb;

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
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
        get_currency: function(type){
          if(type == 'Rupiah') return 'Rp';
          if(type == 'Persen') return '%';
        },
        get_currency_position: function(type){
          if(type == 'Rupiah') return 'prefix';
          if(type == 'Persen') return 'suffix';
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        'diskon_oli_kpb.id_part': function(n, o){
          h3_md_tipe_kendaraan_diskon_oli_kpb_datatable.draw();
        }
      },
      computed: {
        harga_kpb: function(){
          if(this.diskon_oli_kpb.tipe_diskon == 'Rupiah'){
            return this.diskon_oli_kpb.harga_dealer_user - this.diskon_oli_kpb.diskon_value;
          }

          if(this.diskon_oli_kpb.tipe_diskon == 'Persen'){
            potongan_harga = (this.diskon_oli_kpb.diskon_value/100) * this.diskon_oli_kpb.harga_dealer_user;
            return this.diskon_oli_kpb.harga_dealer_user - potongan_harga;
          }

          return this.diskon_oli_kpb.harga_dealer_user;
        }
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
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
        <div class="row"> 
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" name="filter_id_tipe_kendaraan_2_digit" id="filter_id_tipe_kendaraan_2_digit" placeholder="Cari 2 Digit Tipe Kendaraan">
                </div>
              </div> 
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" name="filter_id_tipe_kendaraan_3_digit" id="filter_id_tipe_kendaraan_3_digit" placeholder="Cari 3 Digit Tipe Kendaraan">
                </div>
              </div> 
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" name="filter_id_part" id="filter_id_part" placeholder="Cari Kode Part">
                </div>
              </div> 
              <div class="col-sm-2">
                <div class="form-group">
                  <input type="text" class="form-control" name="filter_nama_part" id="filter_nama_part" placeholder="Cari Nama Part">
                </div>
              </div> 
              <div class="col-sm-2">
                <div class="form-group">
                      <button type="button" class="btn btn-primary btn-sm" id="btn-cari_filter"><span class="fa fa-search"></span></button>
                </div>
              </div> 
          </div>
        <table id="master_diskon_oli_kpb" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Part</th>
              <th>Nama Part</th>
              <th>HET</th>
              <th>Diskon</th>
              <th>Harga KPB</th>
              <th>Tipe Produksi</th>
              <th>Kode Tipe</th>
              <th>Nama Tipe Kendaraan</th>
              <th>Tahun Produksi</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_diskon_oli_kpb = $('#master_diskon_oli_kpb').DataTable({
                processing: true,
                serverSide: true,
                searching:false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_diskon_oli_kpb') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(data) {
                              data.filter_id_tipe_kendaraan_3_digit = $('#filter_id_tipe_kendaraan_3_digit').val();
                              data.filter_id_tipe_kendaraan_2_digit = $('#filter_id_tipe_kendaraan_2_digit').val();
                              data.filter_id_part=$('#filter_id_part').val();
                              data.filter_nama_part=$('#filter_nama_part').val();
                          }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'id_part' },
                    { data: 'nama_part' },
                    { 
                      data: 'harga_dealer_user',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    { 
                      data: 'diskon_value',
                      render: function(data, type, row){
                        if(row.tipe_diskon == 'Rupiah'){
                          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                        }else if(row.tipe_diskon == 'Persen'){
                          return data + ' %';
                        }
                      }
                    },
                    { 
                      data: 'harga_kpb',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    { data: 'tipe_produksi' },
                    { data: 'id_tipe_kendaraan' },
                    { data: 'nama_tipe_kendaraan', name: 'ptm.deskripsi' },
                    { data: 'tahun_kendaraan' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_diskon_oli_kpb.on('draw.dt', function() {
              var info = master_diskon_oli_kpb.page.info();
              master_diskon_oli_kpb.column(0, {
                  search: 'applied',
                  order: 'applied',
                  page: 'applied'
              }).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1 + info.start + ".";
              });
            });
          });
          $('#btn-cari_filter').click(function(e){
            e.preventDefault();
            master_diskon_oli_kpb.draw();
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>