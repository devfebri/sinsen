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
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
<section class="content">
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
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" method="post">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div v-bind:class="{ 'has-error': errors.id_dealer != null }" class="col-sm-3">
                    <input readonly v-model='add_dealer_autofulfillment.kode_dealer_md' type="text" class="form-control">
                    <small v-if='errors.id_dealer != null' class="form-text text-danger">{{ errors.id_dealer }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div v-bind:class="{ 'has-error': errors.id_dealer != null }" class="col-sm-3">
                    <input readonly v-model='add_dealer_autofulfillment.nama_dealer' type="text" class="form-control">
                    <small v-if='errors.id_dealer != null' class="form-text text-danger">{{ errors.id_dealer }}</small>
                  </div>
                  <div class="col-sm-1 no-padding">
                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#dealer_autofulfillment'><i class="fa fa-search"></i></button>
                  </div>
                </div>
                <?php $this->load->view('modal/dealer_autofulfillment') ?>
                <script>
                  function pilih_dealer_autofulfillment(data){
                    form_.add_dealer_autofulfillment.id_dealer = data.id_dealer;
                    form_.add_dealer_autofulfillment.kode_dealer_md = data.kode_dealer_md;
                    form_.add_dealer_autofulfillment.nama_dealer = data.nama_dealer;
                  }
                </script>
                <div class="form-group">
                  <div class="col-sm-3 col-sm-offset-2">
                    <button class="btn btn-flat btn-primary" @click.prevent='tambah'>Tambah</button>
                  </div>
                </div>
                <table class="table-condensed table table-striped" style='margin-bottom: 10px;'>
                  <tr class='bg-blue-gradient'>
                    <td width='3%' class="align-middle">No.</td>
                    <td class="align-middle">Kode Customer</td>
                    <td class="align-middle">Nama Customer</td>
                    <td class="align-middle">Alamat</td>
                    <td class="align-middle">Kota / Kabupaten</td>
                    <td  width='8%' class="align-middle text-center"></td>
                  </tr>
                  <tr v-if='dealers.length > 0' v-for='(e, index) in dealers'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ e.kode_dealer_md }}</td>
                    <td>{{ e.nama_dealer }}</td>
                    <td>{{ e.alamat }}</td>
                    <td>{{ e.kabupaten }}</td>
                    <td>
                      <button class="btn btn-flat btn-sm btn-danger" type='button' @click.prevent='hapus_dealer(index)'><i class="fa fa-trash-o"></i></button>
                    </td>
                  </tr>
                  <tr v-if='dealers.length < 1'>
                    <td colspan='4' class='text-center align-middle'>Tidak ada data</td>
                  </tr>
                </table>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        loading: false,
        errors: {},
        add_dealer_autofulfillment: {
          id_dealer: '',
          kode_dealer_md: '',
        },
        dealers: <?= json_encode($dealers) ?>,
      },
      methods:{
        tambah: function(){
          this.errors = {};
          this.loading = true;

          post = _.pick(this.add_dealer_autofulfillment, [
            'id_dealer'
          ]);

          axios.post('h3/h3_md_ms_dealer_autofulfillment/tambah', Qs.stringify(post))
          .then(function(res){
            form_.dealers.push(res.data);
            form_.reset_add_dealer_autofulfillment();
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){
            form_.loading = false;
            dealer_autofulfillment_datatable.draw();
          });
        },
        hapus_dealer: function(index){
          this.loading = true;
          axios.get('h3/h3_md_ms_dealer_autofulfillment/hapus_dealer', {
            params: {
              id: this.dealers[index].id_dealer
            }
          })
          .then(function(res){
            form_.dealers.splice(index, 1);
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
            dealer_autofulfillment_datatable.draw();
          });
        },
        reset_add_dealer_autofulfillment: function(){
          this.add_dealer_autofulfillment = {
            id_dealer: '',
            kode_dealer_md: '',
          };
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        
      }
  });
</script>
  </section>
</div>