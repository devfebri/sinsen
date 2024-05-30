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
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" id="form_" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Dealer Part</b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Part</label>
                  <div class="col-sm-4">
                    <input name="id_part" type="text" class="form-control" :readonly="true" :value="part.id_part"> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Part</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" :value="part.nama_part" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Rank</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" :value="part.rank" disabled> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" :value="part.status" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Stock</label>
                  <div class="col-sm-4">
                    <input name="min_stok" type="text" class="form-control" :disabled="mode=='detail'" :value="part.min_stok"> 
                    <?php if($mode == 'edit'): ?>
                    <input type="checkbox" name='setting_part_group'> Setting Min Stock untuk kelompok part serupa.
                    <?php endif; ?>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Stock</label>
                  <div class="col-sm-4">
                    <input name="maks_stok" type="text" class="form-control" :disabled="mode=='detail'" :value="part.maks_stok"> 
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
                    <a href="dealer/<?= $isi ?>/edit?id_part=<?= $master_part->id_part ?>"><button type="button" class="btn btn-sm btn-primary btn-flat">Ubah</button></a>
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
        <?php if($mode == 'edit' || $mode == 'detail'): ?>
        part: <?= json_encode($master_part) ?>,
        <?php else: ?>
        part: {},
        <?php endif; ?>
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-body">
        <table id="dealer_master_part" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Part</th>
              <th>Nama Part</th>
              <th>Status</th>
              <th>Rank</th>
              <th>Stock On Hand</th>
              <th>Min Stok</th>
              <th>Max Stok</th>
              <th>Stock In Transit</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function() {
          dealer_master_part = $('#dealer_master_part').DataTable({
              processing: true,
              serverSide: true,
              ordering: false,
              order: [],
              ajax: {
                  url: "<?= base_url('api/part_dealer') ?>",
                  dataSrc: "data",
                  type: "POST"
              },
              createdRow: function (row, data, index) {
                $('td', row).addClass('align-middle');
              },
              columns: [
                  { data: 'index', orderable: false, width: '3%' }, 
                  { data: 'id_part' },
                  { data: 'nama_part' },
                  { 
                    data: 'status',
                    render: function(data){
                      if(data == 'A'){
                        return 'Active';
                      }
                      if(data == 'D'){
                        return 'Discontinue';
                      }
                      return data;
                    }
                  },
                  { 
                    data: 'rank',
                    render: function(data){
                      if(data != null){
                        return data;
                      }
                      return '-';
                    } 
                  },
                  { data: 'stock' },
                  { data: 'min_stok' },
                  { data: 'maks_stok' },
                  { data: 'order_md' },
                  { data: 'action', orderable: false, width: '3%', className: 'text-center' },
              ],
          });
        });
    </script>
    <?php
  }
    ?>
  </section>
</div>