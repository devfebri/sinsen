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
          $disabled = 'disabled';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
<style>
  .isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>

<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  Vue.filter('toCurrency', function (value) {
      return accounting.formatMoney(value, "", 0, ".", ",");
      return value;
  });

  $(document).ready(function(){
    <?php if (isset($row)) { ?>
      $('#id_antrian').val('<?= $row->id_antrian ?>').trigger('change');
      setPembawa();
    <?php } ?>
  })
</script>
    <div class="box box-default">
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
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" id="form_" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <?php if ($mode=='edit'): ?>
                <input type="hidden" name="id_gudang" value="<?= $gudang_h23->id_gudang ?>">
                <?php endif; ?>
                <div class="box-body">
                <h4>
                  <b>Masukkan data Gudang H23</b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                      <input type="hidden" name="id_dealer" :value="user_dealer.id_dealer">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="user_dealer.nama_dealer" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="user_dealer.alamat" disabled> 
                  </div>
                </div>
                <?php if ($mode== 'edit'): ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="user_dealer.kode_dealer_md" disabled> 
                  </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Gudang</label>
                  <div class="col-sm-4">
                      <select name="tipe_gudang" class="form-control" :disabled="mode=='detail'">
                        <?php if ($mode == 'detail' || $mode == 'edit'): ?>
                        <option value="">-choose-</option>
                        <option <?= $gudang_h23->tipe_gudang == 'Good' ? 'selected' : '' ?> value="Good">Good</option>
                        <option <?= $gudang_h23->tipe_gudang == 'Bad' ? 'selected' : '' ?> value="Bad">Bad</option>
                        <option <?= $gudang_h23->tipe_gudang == 'Variance' ? 'selected' : '' ?> value="Variance">Variance</option>
                        <?php else: ?>
                        <option value="">-choose-</option>
                        <option value="Good">Good</option>
                        <option value="Bad">Bad</option>
                        <option value="Variance">Variance</option>
                        <?php endif; ?>
                      </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Gudang</label>
                  <div class="col-sm-4">
                      <?php if ($mode == 'detail' || $mode == 'edit'): ?>
                        <input name="deskripsi_gudang" type="text" class="form-control" :disabled="mode=='detail'" value="<?= $gudang_h23->deskripsi_gudang ?>"> 
                      <?php else: ?>
                      <input name="deskripsi_gudang" type="text" class="form-control" :disabled="mode=='detail'"> 
                      <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Gudang</label>
                  <div class="col-sm-4">
                    <?php if ($mode == 'detail' || $mode == 'edit'): ?>
                    <input name="alamat" class="form-control" value="<?= $gudang_h23->alamat ?>" :disabled="mode=='detail'">
                    <?php else: ?>
                    <input name="alamat" class="form-control" :disabled="mode=='detail'">
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Luas Gudang</label>
                  <div class="col-sm-4">
                    <?php if ($mode == 'detail' || $mode == 'edit'): ?>
                    <input name="luas_gudang" type="text" class="form-control" :disabled="mode=='detail'" value="<?= $gudang_h23->luas_gudang ?>"> 
                    <?php else: ?>
                    <input name="luas_gudang" type="text" class="form-control" :disabled="mode=='detail'"> 
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                  <div class="col-sm-4">
                      <?php if ($mode == 'detail' || $mode == 'edit'): ?>
                      <select name="kategori" class="form-control" :disabled="mode=='detail'">
                        <option value="">-choose-</option>
                        <option <?= $gudang_h23->kategori == "Permanent" ? "selected" : "" ?> value="Permanent">Permanent</option>
                        <option <?= $gudang_h23->kategori == "Temporary" ? "selected" : "" ?> value="Temporary">Temporary</option>
                      </select>
                      <?php else: ?>
                      <select name="kategori" class="form-control" :disabled="mode=='detail'">
                        <option value="">-choose-</option>
                        <option value="Permanent">Permanent</option>
                        <option value="Temporary">Temporary</option>
                      </select>
                      <?php endif; ?>
                  </div>
                </div>
              <div class="box-footer">
                <div class="col-sm-12" v-if="mode=='insert'">
                  <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
                <div class="col-sm-12" v-if="mode=='edit'">
                  <button type="submit" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                </div>
                <div v-if="mode=='detail'">
                  <div class="col-sm-6">
                    <?php if ($mode == 'detail'): ?>
                    <a href="dealer/<?= $isi ?>/edit?k=<?= $gudang_h23->id_gudang ?>"><button type="button" class="btn btn-sm btn-primary btn-flat">Ubah</button></a>
                    <a href="dealer/<?= $isi ?>/delete?k=<?= $gudang_h23->id_gudang ?>"><button type="button" class="btn btn-sm btn-box-tool btn-flat">Hapus</button></a>
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
        kosong :'',
        mode : '<?= $mode ?>',
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        user_dealer: <?= json_encode($dealer) ?>,
        <?php else: ?>
        user_dealer: <?= json_encode($user_dealer) ?>,
        <?php endif; ?>
      },
      methods:{
        ppnPerPart: function(part){
          return (10/100)* part.harga_md_dealer;
        },
        subTotal: function(part){
          return part.kuantitas * part.harga_md_dealer + this.ppnPerPart(part);
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        }
      },
      computed: {
        totalInvoice: function(){
          totalInvoice = 0;
          for(part of this.parts){
            totalInvoice += this.subTotal(part);
          }
          return totalInvoice;
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
              <th>ID Gudang</th>
              <th>Tipe</th>
              <th>Luas</th>
              <th>Kategori</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($gudang_h23) > 0): ?>
            <?php foreach ($gudang_h23 as $e): ?>
              <tr>
                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id_gudang ?>"><?= $e->id_gudang ?></a></td>
                <td><?= $e->tipe_gudang ?></td>
                <td><?= $e->luas_gudang ?> m²</td>
                <td><?= $e->kategori ?></td>
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