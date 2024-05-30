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
                    <label for="inputEmail3" class="col-sm-2 control-label">Masa Berlaku PO Fix</label>
                    <div v-bind:class="{ 'has-error': error_exist('fix') }" class="col-sm-3">
                      <vue-numeric class="form-control" separator="." v-model='tipe_po.fix' currency='Hari' currency-symbol-position='suffix'></vue-numeric>
                      <small v-if="error_exist('fix')" class="form-text text-danger">{{ get_error('fix') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Masa Berlaku PO Reguler</label>
                    <div v-bind:class="{ 'has-error': error_exist('reg') }" class="col-sm-3">
                      <vue-numeric class="form-control" separator="." v-model='tipe_po.reg' currency='Hari' currency-symbol-position='suffix'></vue-numeric>
                      <small v-if="error_exist('reg')" class="form-text text-danger">{{ get_error('reg') }}</small>
                    </div>
                  </div>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-condensed">
                          <tr>
                            <td width='3%'>No.</td>
                            <td>Kode Customer</td>
                            <td>Nama Customer</td>
                            <td>Alamat</td>
                            <td>Masa Berlaku PO Fix</td>
                            <td>Masa Berlaku PO Reguler</td>
                            <td width='3%'></td>
                          </tr>
                          <tr v-if='items.length > 0' v-for='(item, index) of items'>
                            <td>{{ index + 1 }}.</td>
                            <td>{{ item.kode_dealer_md }}</td>
                            <td>{{ item.nama_dealer }}</td>
                            <td>{{ item.alamat }}</td>
                            <td>
                              <vue-numeric class="form-control" separator='.' v-model='item.fix' currency='Hari' currency-symbol-position='suffix'></vue-numeric>
                            </td>
                            <td>
                              <vue-numeric class="form-control" separator='.' v-model='item.reg' currency='Hari' currency-symbol-position='suffix'></vue-numeric>
                            </td>
                            <td>
                              <button class="btn btn-sm btn-flat btn-danger" @click.prevent='hapus_tipe_po_item(index)'><i class="fa fa-trash-o"></i></button>
                            </td>
                          </tr>
                          <tr v-if='items.length < 1'>
                            <td class='text-center' colspan='7'>Tidak ada data</td>
                          </tr>
                        </table>
                        <div class="row">
                          <div class="col-sm-12 no-padding text-right">
                            <button class="btn btn-flat btn-sm btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_tipe_po'><i class="fa fa-plus"></i></button>
                          </div>
                        </div>
                        <?php $this->load->view('modal/h3_md_dealer_tipe_po'); ?>
                        <script>
                          function pilih_dealer_tipe_po(data) {
                            app.items.push(data);
                            h3_md_dealer_tipe_po_datatable.draw();
                          }
                        </script>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-3">
                        <button class="btn btn-flat btn-primary" @click.prevent='simpan'>Simpan</button>
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
        tipe_po: <?= json_encode($tipe_po) ?>,
        items: <?= json_encode($items) ?>,
      },
      methods:{
        simpan : function(){
          post = this.tipe_po;
          post.items = _.map(this.items, function(data){
            return _.pick(data, ['id_dealer', 'fix', 'reg']);
          });

          this.loading = true;
          this.errors = {};
          axios.post('h3/h3_md_ms_tipe_po/simpan', Qs.stringify(post))
          .then(function(res){
            toastr.success('Data berhasil disimpan.');
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
        hapus_tipe_po_item : function(index){
          this.items.splice(index, 1);
          h3_md_dealer_tipe_po_datatable.draw();
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