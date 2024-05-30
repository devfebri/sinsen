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
    <div id="app" class="box box-default">
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Produk</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-3">
                      <select class="form-control" v-model='setting_kelompok_produk.produk'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Acc</option>
                        <option value="Apparel">Apparel</option>
                        <option value="Tools">Tools</option>
                        <!-- <option value="Other">Other</option> -->
                      </select>
                      <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Part</label>
                    <div v-bind:class="{ 'has-error': error_exist('kelompok_part') }" class="col-sm-3">
                      <input :value='setting_kelompok_produk.kelompok_part.length + " Kelompok Part"' type="text" class="form-control" disabled>
                      <small v-if="error_exist('kelompok_part')" class="form-text text-danger">{{ get_error('kelompok_part') }}</small>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_setting_kelompok_produk'><i class="fa fa-search"></i></button>
                    </div>
                    <?php $this->load->view('modal/h3_md_kelompok_part_setting_kelompok_produk'); ?>
                    <script>
                      function pilih_kelompok_part_kelompok_produk(data) {
                        app.setting_kelompok_produk.id_kelompok_part = data.id_kelompok_part;
                      }
                    </script>
                    <script>
                      $(document).ready(function(){
                        $("#h3_md_kelompok_part_setting_kelompok_produk").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          kelompok_part = target.attr('data-kelompok-part');

                          if(target.is(':checked')){
                            app.setting_kelompok_produk.kelompok_part.push(kelompok_part);
                          }else{
                            index = _.indexOf(app.setting_kelompok_produk.kelompok_part, kelompok_part);
                            app.setting_kelompok_produk.kelompok_part.splice(index, 1);
                          }
                        });
                      });
                    </script>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-3">
                        <button class="btn btn-flat btn-primary" @click.prevent='simpan_setting_kelompok_produk'>Simpan</button>
                      </div>
                  </div>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-3">
                        <table class="table table-condensed">
                          <tr class='bg-blue-gradient'>
                            <td colspan='3' class='text-center'>Parts</td>
                          </tr>
                          <tr>
                            <td width='3%'>No.</td>
                            <td>Kelompok Part</td>
                            <td width='3%'></td>
                          </tr>
                          <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                            <td>{{ index + 1 }}.</td>
                            <td>{{ part.id_kelompok_part }}</td>
                            <td>
                              <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_kelompok_produk(index, "Parts")'><i class="fa fa-trash-o"></i></button>
                            </td>
                          </tr>
                          <tr v-if='parts.length< 1'>
                            <td class='text-center' colspan='3'>Tidak ada data</td>
                          </tr>
                        </table>
                      </div>
                      <div class="col-sm-3">
                        <table class="table table-condensed">
                          <tr class='bg-blue-gradient'>
                            <td colspan='3' class='text-center'>Oil</td>
                          </tr>
                          <tr>
                            <td width='3%'>No.</td>
                            <td>Kelompok Part</td>
                            <td width='3%'></td>
                          </tr>
                          <tr v-if='oil.length > 0' v-for='(each_oil, index) of oil'>
                            <td>{{ index + 1 }}.</td>
                            <td>{{ each_oil.id_kelompok_part }}</td>
                            <td>
                              <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_kelompok_produk(index, "Oil")'><i class="fa fa-trash-o"></i></button>
                            </td>
                          </tr>
                          <tr v-if='oil.length< 1'>
                            <td class='text-center' colspan='3'>Tidak ada data</td>
                          </tr>
                        </table>
                      </div>
                      <div class="col-sm-3">
                        <table class="table table-condensed">
                            <tr class='bg-blue-gradient'>
                              <td colspan='3' class='text-center'>Accesories</td>
                            </tr>
                            <tr>
                              <td width='3%'>No.</td>
                              <td>Kelompok Part</td>
                              <td width='3%'></td>
                            </tr>
                            <tr v-if='acc.length > 0' v-for='(each_acc, index) of acc'>
                              <td>{{ index + 1 }}.</td>
                              <td>{{ each_acc.id_kelompok_part }}</td>
                              <td>
                                <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_kelompok_produk(index, "Acc")'><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                            <tr v-if='acc.length< 1'>
                              <td class='text-center' colspan='3'>Tidak ada data</td>
                            </tr>
                          </table>
                      </div>
                      <div class="col-sm-3">
                        <table class="table table-condensed">
                            <tr class='bg-blue-gradient'>
                              <td colspan='3' class='text-center'>Apparel</td>
                            </tr>
                            <tr>
                              <td width='3%'>No.</td>
                              <td>Kelompok Part</td>
                              <td width='3%'></td>
                            </tr>
                            <tr v-if='apparel.length > 0' v-for='(each_apparel, index) of apparel'>
                              <td>{{ index + 1 }}.</td>
                              <td>{{ each_apparel.id_kelompok_part }}</td>
                              <td>
                                <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_kelompok_produk(index, "Apparel")'><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                            <tr v-if='apparel.length< 1'>
                              <td class='text-center' colspan='3'>Tidak ada data</td>
                            </tr>
                          </table>
                      </div>
                      <div class="col-sm-3">
                        <table class="table table-condensed">
                            <tr class='bg-blue-gradient'>
                              <td colspan='3' class='text-center'>Tools</td>
                            </tr>
                            <tr>
                              <td width='3%'>No.</td>
                              <td>Kelompok Part</td>
                              <td width='3%'></td>
                            </tr>
                            <tr v-if='tools.length > 0' v-for='(each_tools, index) of tools'>
                              <td>{{ index + 1 }}.</td>
                              <td>{{ each_tools.id_kelompok_part }}</td>
                              <td>
                                <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_kelompok_produk(index, "Tools")'><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                            <tr v-if='tools.length< 1'>
                              <td class='text-center' colspan='3'>Tidak ada data</td>
                            </tr>
                          </table>
                      </div>
                      <div class="col-sm-3">
                        <!-- <table class="table table-condensed">
                            <tr class='bg-blue-gradient'>
                              <td colspan='3' class='text-center'>Other</td>
                            </tr>
                            <tr>
                              <td width='3%'>No.</td>
                              <td>Kelompok Part</td>
                              <td width='3%'></td>
                            </tr>
                            <tr v-if='other.length > 0' v-for='(each_other, index) of other'>
                              <td>{{ index + 1 }}.</td>
                              <td>{{ each_other.id_kelompok_part }}</td>
                              <td>
                                <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_kelompok_produk(index, "Other")'><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                            <tr v-if='other.length< 1'>
                              <td class='text-center' colspan='3'>Tidak ada data</td>
                            </tr>
                        </table> -->
                      </div>
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
        loading: false,
        errors: {},
        setting_kelompok_produk: {
          produk: '',
          kelompok_part: []
        },
        parts: <?= json_encode($parts) ?>,
        oil: <?= json_encode($oil) ?>,
        acc: <?= json_encode($acc) ?>,
        other: <?= json_encode($other) ?>,
        apparel: <?= json_encode($apparel) ?>,
        tools: <?= json_encode($tools) ?>,
      },
      methods:{
        simpan_setting_kelompok_produk : function(){
          this.loading = true;
          this.errors = {};
          axios.post('h3/h3_md_ms_setting_kelompok_produk/simpan_setting_kelompok_produk', Qs.stringify(this.setting_kelompok_produk))
          .then(function(res){
            for (data of res.data) {
              if(data.produk == 'Parts'){
                app.parts.push(data);
              }else if(data.produk == 'Oil'){
                app.oil.push(data);
              }else if(data.produk == 'Acc'){
                app.acc.push(data);
              }else if(data.produk == 'Other'){
                app.other.push(data);
              }else if(data.produk == 'Apparel'){
                app.apparel.push(data);
              }else if(data.produk == 'Tools'){
                app.tools.push(data);
              }
            }
            
            app.reset_setting_kelompok_produk();
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
          .then(function(){ 
            app.loading = false;
            h3_md_kelompok_part_setting_kelompok_produk_datatable.draw();
          })
          ;
        },
        reset_setting_kelompok_produk: function(){
          this.setting_kelompok_produk = {
            produk: '',
            kelompok_part: []
          };
        },
        hapus_kelompok_produk : function(index, type){
          if(type == 'Parts'){
            setting_kelompok_produk = this.parts[index];
          }else if(type == 'Oil'){
            setting_kelompok_produk = this.oil[index];
          }else if(type == 'Acc'){
            setting_kelompok_produk = this.acc[index];
          }else if(type == 'Other'){
            setting_kelompok_produk = this.other[index];
          }else if(type == 'Apparel'){
            setting_kelompok_produk = this.apparel[index];
          }else if(type == 'Tools'){
            setting_kelompok_produk = this.tools[index];
          }
          

          this.loading = true;
          this.errors = {};
          axios.get('h3/h3_md_ms_setting_kelompok_produk/hapus_setting_kelompok_produk', {
            params: {
              id: setting_kelompok_produk.id
            }
          })
          .then(function(res){
            toastr.success(res.data.message);

            if(type == 'Parts'){
              app.parts.splice(index, 1);
            }else if(type == 'Oil'){
              app.oil.splice(index, 1);
            }else if(type == 'Acc'){
              app.acc.splice(index, 1);
            }else if(type == 'Other'){
              app.other.splice(index, 1);
            }else if(type == 'Apparel'){
              app.apparel.splice(index, 1);
            }else if(type == 'Tools'){
              app.tools.splice(index, 1);
            }
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
          .then(function(){ 
            app.loading = false;
            h3_md_kelompok_part_setting_kelompok_produk_datatable.draw();
          })
          ;
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
  </section>
</div>