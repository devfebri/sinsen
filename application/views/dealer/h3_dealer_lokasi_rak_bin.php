<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">Sevice Management</li>
    <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
  </ol>
</section>
<section class="content">
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
          $form = 'save';
      }

      if ($mode=='detail') {
          $form = 'detail';
          $disabled = 'disabled';
      }

      if ($mode=='edit') {

          $form = 'update';

      } ?>

<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script> 

<script>
  Vue.use(VueNumeric.default);
</script>
    <div id="form_" class="box box-default">
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                <h4><b>Masukkan data Lokasi Rak Bin</b></h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Rak</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_rak') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode!='insert'" v-model='lokasi_rak_bin.id_rak'> 
                    <small v-if="error_exist('id_rak')" class="form-text text-danger">{{ get_error('id_rak') }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Rak</label>
                  <div v-bind:class="{ 'has-error': error_exist('deskripsi_rak') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model='lokasi_rak_bin.deskripsi_rak'> 
                    <small v-if="error_exist('deskripsi_rak')" class="form-text text-danger">{{ get_error('deskripsi_rak') }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Unit</label>
                  <div v-bind:class="{ 'has-error': error_exist('unit') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model='lokasi_rak_bin.unit'> 
                    <small v-if="error_exist('unit')" class="form-text text-danger">{{ get_error('unit') }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_gudang') }" class="col-sm-4">
                      <select v-model="lokasi_rak_bin.id_gudang" class="form-control" :disabled="mode=='detail'">
                        <option value="">-choose-</option>
                        <option v-for='row in gudang' :value="row.id_gudang">{{ row.id_gudang }}</option>
                      </select>
                    <small v-if="error_exist('id_gudang')" class="form-text text-danger">{{ get_error('id_gudang') }}</small>
                  </div>
                </div>
              <div class="box-footer">
                <div class="col-sm-12" v-if="mode=='insert'">
                  <button :disabled='loading' class="btn btn-info btn-flat" @click.prevent='<?= $form ?>'><i class="fa fa-save"></i> Save All</button>
                </div>
                <div class="col-sm-12" v-if="mode=='edit'">
                  <button :disabled='loading' type="submit" class="btn btn-warning btn-flat" @click.prevent='<?= $form ?>'><i class="fa fa-save"></i> Update</button>
                </div>
                <div v-if="mode=='detail'">
                  <div class="col-sm-6">
                    <?php if ($mode == 'detail'): ?>
                    <a href="dealer/<?= $isi ?>/edit?k=<?= $lokasi_rak_bin['id'] ?>"><button type="button" class="btn btn-sm btn-primary btn-flat">Ubah</button></a>
                    <a href="dealer/<?= $isi ?>/delete?k=<?= $lokasi_rak_bin['id'] ?>"><button type="button" class="btn btn-sm btn-box-tool btn-flat">Hapus</button></a>
                    <?php endif; ?>
                  </div>   
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>

  var form_ = new Vue({
      el: '#form_',
      data: {
        errors: [],
        loading: false,
        mode : '<?= $mode ?>',
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        lokasi_rak_bin: <?= json_encode($lokasi_rak_bin) ?>,
        <?php else: ?>
        lokasi_rak_bin: {
          id: '',
          id_rak: '',
          deksripsi_rak: '',
          unit: 0,
          id_gudang: '',
        },
        <?php endif; ?>
        gudang: <?= json_encode($gudang_h23) ?>
      },
      methods:{
        <?= $form ?>: function(){
          this.loading = true;

          post = _.pick(this.lokasi_rak_bin, [
            'id', 'id_rak', 'deskripsi_rak', 'unit', 'id_gudang'
          ]);
          axios.post('<?= base_url("dealer/{$isi}/{$form}") ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;

            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
            }
            
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
  });

</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">

          <a href="dealer/<?= $isi ?>/add">

            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>

          </a>

        </h3>

        <div class="box-tools pull-right">

          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

        </div>

      </div><!-- /.box-header -->

      <div class="box-body">

        <?php

        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>                  

        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

            <strong><?php echo $_SESSION['pesan'] ?></strong>

            <button class="close" data-dismiss="alert">

                <span aria-hidden="true">&times;</span>

                <span class="sr-only">Close</span>  

            </button>

        </div>

        <?php

        }

      $_SESSION['pesan'] = ''; ?>

        <table id="example1" class="table table-bordered table-hover">

          <thead>

            <tr>

              <th>ID Rak</th>

              <th>Deskripsi Rak</th>

              <th>Unit</th>


              <th>Gudang</th>

            </tr>

          </thead>

          <tbody>

          <?php if (count($lokasi_rak_bin) > 0): ?>

            <?php foreach ($lokasi_rak_bin as $e): ?>

              <tr>

                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id ?>"><?= $e->id_rak ?></a></td>

                <td><?= $e->deskripsi_rak ?></td>

                <td><?= $e->unit ?></td>


                <td><?= $e->id_gudang ?></td>

              </tr>

            <?php endforeach ?>

          <?php endif; ?>

          </tbody>

        </table>

      </div><!-- /.box-body -->

    </div><!-- /.box -->

    <?php

  }

    ?>

  </section>

</div>