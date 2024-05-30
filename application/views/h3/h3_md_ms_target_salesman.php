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
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Salesman</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_salesman') }" class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='target_salesman.nama_salesman'>
                      <small v-if="error_exist('id_salesman')" class="form-text text-danger">{{ get_error('id_salesman') }}</small>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_salesman_target_salesman'><i class="fa fa-search"></i></button>
                    </div>
                    <?php $this->load->view('modal/h3_salesman_target_salesman') ?>
                    <script>
                      function pilih_salesman_target_salesman(data){
                        app.target_salesman.id_salesman = data.id_karyawan;
                        app.target_salesman.nama_salesman = data.nama_lengkap;
                        app.target_salesman.nik_salesman = data.nik;
                      }
                    </script>
                    <?php $this->load->view('modal/h3_md_department_filter_salesman_target_salesman'); ?>         
                    <script>
                    function pilih_department_filter_salesman_target_salesman(data, type) {
                        if(type == 'add_filter'){
                            $('#nama_department_filter').val(data.department);
                            $('#id_department_filter').val(data.id_department);
                        }else if(type == 'reset_filter'){
                            $('#nama_department_filter').val('');
                            $('#id_department_filter').val('');
                        }
                        h3_salesman_target_salesman_datatable.draw();
                        h3_md_department_filter_salesman_target_salesman_datatable.draw();
                    }
                    </script>
                    <?php $this->load->view('modal/h3_md_jabatan_filter_salesman_target_salesman'); ?>         
                    <script>
                    function pilih_jabatan_filter_salesman_target_salesman(data, type) {
                        if(type == 'add_filter'){
                            $('#nama_jabatan_filter').val(data.jabatan);
                            $('#id_jabatan_filter').val(data.id_jabatan);
                        }else if(type == 'reset_filter'){
                            $('#nama_jabatan_filter').val('');
                            $('#id_jabatan_filter').val('');
                        }
                        h3_salesman_target_salesman_datatable.draw();
                        h3_md_jabatan_filter_salesman_target_salesman_datatable.draw();
                    }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">NIK Salesman</label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" readonly v-model='target_salesman.nik_salesman'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date') || error_exist('end_date') }" class="col-sm-4">
                      <input type="text" class="form-control pull-right" id="periode_target_salesman" readonly>
                      <small v-if="error_exist('start_date') || error_exist('end_date')" class="form-text text-danger">{{ get_error('start_date') || get_error('end_date') }}</small>
                    </div>
                    <label class="control-label col-sm-2 col-sm-offset-1">Jenis Target Salesman</label>
                    <div class="col-sm-2">
                      <select :disabled='mode != "insert"' class="form-control" v-model='target_salesman.jenis_target_salesman'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Accesories</option>
                        <option value="Apparel">Apparel</option>
                        <!-- <option value="Tools">Tools</option>
                        <option value="Other">Other</option> -->
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-2">Target Salesman Global</label>
                      <div class="col-sm-4">
                        <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='target_salesman.target_salesman_global' separator='.' currency='Rp'></vue-numeric>
                      </div>
                      <div v-if='target_salesman.jenis_target_salesman == "Oil"'>
                        <label class="control-label col-sm-2">Target Salesman Dus Global</label>
                        <div class="col-sm-4">
                          <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='target_salesman.target_salesman_dus_global' separator='.'></vue-numeric>
                        </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-2">Target Salesman Per Channel</label>
                      <div class="col-sm-4">
                        <vue-numeric disabled class="form-control" v-model='target_salesman_channel' separator='.' currency='Rp'></vue-numeric>
                      </div>
                      <div v-if='target_salesman.jenis_target_salesman == "Oil"'>
                        <label class="control-label col-sm-2">Target Salesman Dus Per Channel</label>
                        <div class="col-sm-4">
                          <vue-numeric disabled class="form-control" v-model='target_salesman_dus_channel' separator='.'></vue-numeric>
                        </div>
                      </div>
                  </div>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-offset-6 col-sm-2">
                        <div class="form-group">
                          <label class="control-label">Jenis Dealer</label>
                          <div class="row">
                            <div class="col-sm-12">
                              <div class="input-group">
                                <input :value='filter_jenis_dealer.length + " Jenis Dealer"' type="text" class="form-control" readonly>
                                <div class="input-group-btn">
                                  <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_jenis_dealer_filter_target_salesman'><i class="fa fa-search"></i></button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php $this->load->view('modal/h3_md_jenis_dealer_filter_target_salesman'); ?>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <label class="control-label">Search Nama Customer</label>
                            <input v-model='filter_nama_customer' type="text" class="form-control">
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <div class="col-sm-12">
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
                      </div>
                      <?php $this->load->view('modal/h3_md_kabupaten_filter_sim_part'); ?>
                      <script>
                        function pilih_kabupaten_filter_sim_part(data){
                          app.filter_kabupaten = data.kabupaten;
                          app.filter_id_kabupaten = data.id_kabupaten;
                        }
                      </script>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_target_salesman_parts') ?>
                  <?php $this->load->view('modal/h3_md_target_salesman_parts_items') ?>
                  <?php $this->load->view('modal/h3_md_target_salesman_oil') ?>
                  <?php $this->load->view('modal/h3_md_target_salesman_acc') ?>
                  <?php $this->load->view('modal/h3_md_target_salesman_apparel') ?>
                  <?php $this->load->view('modal/h3_dealer_target_salesman') ?>
                  <script>
                    function pilih_dealer_target_salesman(data){
                      data = _.pick(data, ['id_dealer', 'kode_dealer_md', 'nama_dealer', 'alamat', 'kabupaten']);
                      if(app.target_salesman.jenis_target_salesman == 'Parts'){
                        data.items = [];
                        data.global = 1;
                        data.target_part = 0;
                        app.target_salesman_parts.push(data);
                      }

                      if(app.target_salesman.jenis_target_salesman == 'Oil'){
                        data.amount_engine_oil = 0;
                        data.botol_engine_oil = 0;
                        data.amount_gear_oil = 0;
                        data.botol_gear_oil = 0;
                        app.target_salesman_oils.push(data);
                      }

                      if(app.target_salesman.jenis_target_salesman == 'Acc'){
                        data.target_acc = 0;
                        app.target_salesman_acc.push(data);
                      }

                      if(app.target_salesman.jenis_target_salesman == 'Apparel'){
                        data.target_apparel = 0;
                        app.target_salesman_apparel.push(data);
                      }

                      h3_dealer_target_salesman_datatable.draw();
                    }
                  </script>
                  <?php $this->load->view('modal/h3_md_kelompok_part_target_salesman_parts'); ?>
                  <script>
                    function pilih_kelompok_part_target_salesman_parts(data){
                      item = {};
                      item.id_kelompok_part = data.id_kelompok_part;
                      item.target_part_items = 0;
                      app.items.push(item);
                    }
                  </script>
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Perbarui</button>
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + target_salesman.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  app = new Vue({
      el: '#app',
      data: {
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        errors: {},
        filter_kabupaten: '',
        filter_id_kabupaten: '',
        filter_nama_customer: '',
        filter_jenis_dealer: [],
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        target_salesman: <?= json_encode($target_salesman) ?>,
        target_salesman_parts: <?= json_encode($target_salesman_parts) ?>,
        target_salesman_oils: <?= json_encode($target_salesman_oils) ?>,
        target_salesman_acc: <?= json_encode($target_salesman_acc) ?>,
        target_salesman_apparel: <?= json_encode($target_salesman_apparel) ?>,
        <?php else: ?>
        target_salesman: {
          id_salesman: '',
          nama_salesman: '',
          nik_salesman: '',
          start_date: '',
          end_date: '',
          jenis_target_salesman: '',
          target_salesman_global: '',
          target_salesman_dus_global: '',
        },
        target_salesman_parts: [],
        target_salesman_oils: [],
        target_salesman_acc: [],
        target_salesman_apparel: [],
        <?php endif; ?>
        dealer_modal_type: '',
        index_target_salesman_parts: 0,
        items: [],
      },
      methods:{
        <?= $form ?>: function(){
          this.errors = {};
          this.loading = true;

          post = _.pick(this.target_salesman, [
            'id','id_salesman', 'start_date', 'end_date', 'jenis_target_salesman', 'target_salesman_global', 'target_salesman_dus_global'
          ]);

          post.target_salesman_channel = this.target_salesman_channel;
          post.target_salesman_dus_channel = this.target_salesman_dus_channel;

          post.target_salesman_parts = _.map(this.target_salesman_parts, function(i){
            if(i.global == 0){
              i.target_part = app.total_target_parts_per_dealer(i);
            }
            return i;
          });

          post.target_salesman_oils = _.map(this.target_salesman_oils, function(i){
            i.total_amount = app.hitung_amount_target_salesman_oil(i);
            i.total_botol = app.hitung_botol_target_salesman_oil(i);
            return i;
          });

          post.target_salesman_acc = this.target_salesman_acc;
          post.target_salesman_apparel = this.target_salesman_apparel;


          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              app.errors = data.errors;
              toastr.error(data.message);
            }

            app.loading = false;
          });
        },
        open_dealer_target_salesman: function(dealer_modal_type){
          this.dealer_modal_type = dealer_modal_type;
          $('#h3_dealer_target_salesman').modal('show');
        },
        open_target_salesman_parts_items: function(index){
          this.index_target_salesman_parts = index;
          this.items = this.target_salesman_parts[index].items;
          $('#h3_target_salesman_parts_items').modal('show');
        },
        hapus_target_salesman_parts: function(index){
          this.target_salesman_parts.splice(index, 1);
          h3_dealer_target_salesman_datatable.draw();
        },
        total_target_parts_per_dealer: function(data){
          return _.sumBy(data.items, function(item){
            return Number(item.target_part_items);
          });
        },
        hitung_amount_target_salesman_oil: function(data){
          return data.amount_engine_oil + data.amount_gear_oil;
        },
        hitung_botol_target_salesman_oil: function(data){
          return data.botol_engine_oil + data.botol_gear_oil;
        },
        hapus_target_salesman_oils: function(index){
          this.target_salesman_oils.splice(index, 1);
          h3_dealer_target_salesman_datatable.draw();
        },
        hapus_target_salesman_acc: function(index){
          this.target_salesman_acc.splice(index, 1);
          h3_dealer_target_salesman_datatable.draw();
        },
        hapus_target_salesman_apparel: function(index){
          this.target_salesman_apparel.splice(index, 1);
          h3_dealer_target_salesman_datatable.draw();
        },
        reset_filter_kabupaten: function(){
          this.filter_kabupaten = '';
          this.filter_id_kabupaten = '';
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        'target_salesman.jenis_target_salesman': function(n, o){
          h3_dealer_target_salesman_datatable.draw();
        }
      },
      computed: {
        target_salesman_channel: function(){
          if(this.target_salesman.jenis_target_salesman == 'Parts'){
            return _.sumBy(this.target_salesman_parts, function(i){
              if(i.global == 1){
                return parseInt(i.target_part);
              }
              return _.sumBy(i.items, function(item){
                return parseInt(item.target_part_items);
              });
            });
          }

          if(this.target_salesman.jenis_target_salesman == 'Oil'){
            return _.sumBy(this.target_salesman_oils, function(i){
              return  parseInt(i.amount_engine_oil) + parseInt(i.amount_gear_oil);
            });
          }

          if(this.target_salesman.jenis_target_salesman == 'Acc'){
            return _.sumBy(this.target_salesman_acc, function(i){
              return parseInt(i.target_acc);
            });
          }

          if(this.target_salesman.jenis_target_salesman == 'Apparel'){
            return _.sumBy(this.target_salesman_apparel, function(i){
              return parseInt(i.target_apparel);
            });
          }
          return 0;
        },
        target_salesman_dus_channel: function(){
          if(this.target_salesman.jenis_target_salesman == 'Oil'){
            return _.sumBy(this.target_salesman_oils, function(i){
              return (i.botol_engine_oil/24) + (i.botol_gear_oil/48);
            });
          }
          return 0;
        },
        total_botol: function(){
          if(this.target_salesman.jenis_target_salesman == 'Oil'){
            return _.sumBy(this.target_salesman_oils, function(i){
              return i.botol_engine_oil + i.botol_gear_oil;
            });
          }
          return 0;
        },
        filtered_target_salesman_parts: function(){
          filter_id_kabupaten = this.filter_id_kabupaten;
          filter_nama_customer = this.filter_nama_customer;
          filter_jenis_dealer = this.filter_jenis_dealer;
          
          return _.chain(this.target_salesman_parts)
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
          .filter(function(data){
            if(filter_jenis_dealer.length > 0){
              for (filter of filter_jenis_dealer) {
                if(filter == 'H123'){
                  return data.h1 == 1 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H23'){
                  return data.h1 == 0 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H3'){
                  return data.h1 == 0 && data.h2 == 0 && data.h3 == 1;
                }
              }
            }
            return true;
          })
          .value();
        },
        filtered_target_salesman_oils: function(){
          filter_id_kabupaten = this.filter_id_kabupaten;
          filter_nama_customer = this.filter_nama_customer;
          filter_jenis_dealer = this.filter_jenis_dealer;
          
          return _.chain(this.target_salesman_oils)
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
          .filter(function(data){
            if(filter_jenis_dealer.length > 0){
              for (filter of filter_jenis_dealer) {
                if(filter == 'H123'){
                  return data.h1 == 1 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H23'){
                  return data.h1 == 0 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H3'){
                  return data.h1 == 0 && data.h2 == 0 && data.h3 == 1;
                }
              }
            }
            return true;
          })
          .value();
        },
        filtered_target_salesman_acc: function(){
          filter_id_kabupaten = this.filter_id_kabupaten;
          filter_nama_customer = this.filter_nama_customer;
          filter_jenis_dealer = this.filter_jenis_dealer;

          return _.chain(this.target_salesman_acc)
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
          .filter(function(data){
            if(filter_jenis_dealer.length > 0){
              for (filter of filter_jenis_dealer) {
                if(filter == 'H123'){
                  return data.h1 == 1 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H23'){
                  return data.h1 == 0 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H3'){
                  return data.h1 == 0 && data.h2 == 0 && data.h3 == 1;
                }
              }
            }
            return true;
          })
          .value();
        },
        filtered_target_salesman_apparel: function(){
          filter_id_kabupaten = this.filter_id_kabupaten;
          filter_nama_customer = this.filter_nama_customer;
          filter_jenis_dealer = this.filter_jenis_dealer;

          return _.chain(this.target_salesman_apparel)
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
          .filter(function(data){
            if(filter_jenis_dealer.length > 0){
              for (filter of filter_jenis_dealer) {
                if(filter == 'H123'){
                  return data.h1 == 1 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H23'){
                  return data.h1 == 0 && data.h2 == 1 && data.h3 == 1;
                }else if(filter == 'H3'){
                  return data.h1 == 0 && data.h2 == 0 && data.h3 == 1;
                }
              }
            }
            return true;
          })
          .value();
        },
      },
      mounted: function(){
        config = {
          opens: 'left',
          autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
          locale: {
            format: 'DD/MM/YYYY'
          }
        };

        if(this.mode == 'detail' || this.mode == 'edit'){
          config.startDate = new Date(this.target_salesman.start_date);
          config.endDate = new Date(this.target_salesman.end_date);
        }

        periode_promo = $('#periode_target_salesman').daterangepicker(config).on('apply.daterangepicker', function(ev, picker) {
          app.target_salesman.start_date = picker.startDate.format('YYYY-MM-DD');
          app.target_salesman.end_date = picker.endDate.format('YYYY-MM-DD');
          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
        });
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
        <div class="container-fluid no-padding">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="" class="control-label">Produk</label>
                  <select id='filter_produk' class="form-control">
                    <option value="">-</option>
                    <option value="Parts">Parts</option>
                    <option value="Oil">Oil</option>
                    <option value="Acc">Accesories</option>
                  </select>
                </div>
              </div>
              <div id='filter-salesman' class="col-md-3">
                <div class="form-group">
                  <label for="" class="control-label">Salesman</label>
                  <div class="input-group">
                    <input type="text" :value='salesmans.length + " salesman"' class="form-control" readonly="readonly">
                    <div class="input-group-btn">
                      <button type='button' data-toggle='modal' data-target='#h3_md_salesman_filter_target_salesman' class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <?php $this->load->view('modal/h3_md_salesman_filter_target_salesman'); ?>
        <script>
          $(document).ready(function(){
            $("#h3_md_salesman_filter_target_salesman").on('change',"input[type='checkbox']",function(e){
              target = $(e.target);
              id_karyawan = target.attr('data-id-karyawan');

              if(target.is(':checked')){
                salesmanFilter.salesmans.push(id_karyawan);
              }else{
                index_id_karyawan = _.indexOf(salesmanFilter.salesmans, id_karyawan);
                salesmanFilter.salesmans.splice(index_id_karyawan, 1);
              }
              h3_md_salesman_filter_target_salesman_datatable.draw();
            });
          });
        </script>
        <script>
          $(document).ready(function(){
            $('#filter_produk').on('change', function(e){
              master_target_salesman.draw();
            });
          });

          salesmanFilter = new Vue({
            el: '#filter-salesman',
            data: {
              salesmans: []
            },
            watch: {
              salesmans: {
                deep: true,
                handler: function(){
                  master_target_salesman.draw();
                }
              }
            }
          })
        </script>
        <table id="master_target_salesman" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Salesman</th>
              <th>Jenis Target Salesman</th>
              <th>Periode awal</th>
              <th>Periode akhir</th>
              <th>Target Salesman Global</th>
              <th>Target Salesman Per Channel</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          date_render = function(data){
            return moment(data).format('DD/MM/YYYY');
          }

          rupiah_render = function(data){
            if(data != null) return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
          }

          $(document).ready(function() {
            master_target_salesman = $('#master_target_salesman').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_target_salesman') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.filter_salesman = salesmanFilter.salesmans;
                    d.filter_produk = $('#filter_produk').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'nama_lengkap' },
                    { data: 'jenis_target_salesman', orderable: false },
                    { 
                      data: 'start_date',
                      render: date_render
                    },
                    { 
                      data: 'end_date',
                      render: date_render
                    },
                    { 
                      data: 'target_salesman_global',
                      render: rupiah_render
                    },
                    { 
                      data: 'target_salesman_channel',
                      render: rupiah_render
                    },
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