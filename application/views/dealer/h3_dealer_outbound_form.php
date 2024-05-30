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

<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>

<script>
  Vue.use(VueNumeric.default);
  Vue.component('v-select', VueSelect.VueSelect);
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
                <div class="box-body">
                <h4>
                  <b>Masukkan data Sales Order</b>
                </h4>
                <?php if($mode != 'insert'): ?>
                <input type="hidden" name="id_outbound_form" value="<?= $outbound_form->id_outbound_form ?>">
                <?php endif; ?>
                <input type="hidden" name="id_warehouse_asal" v-model="selectedWarehousePadaDealer.id_gudang">
                <input type="hidden" name="id_warehouse_tujuan" v-model="selectedWarehouseTujuan.id_gudang">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kategori Lokasi</label>
                  <div class="col-sm-4">
                    <select name="kategori_lokasi" class="form-control">
                      <option value="">-choose-</option>
                      <option value="Warehouse">Warehouse</option>
                      <option value="Event">Event</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Event</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" disabled>
                  </div>
                  <div class="col-sm-4 no-padding">
                    <button v-if="mode!='detail'" type="button" class="btn btn-flat btn-info" data-toggle="modal" data-target="#modal-event">Cari Event</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <?php if($mode == 'detail' OR $mode == 'edit'): ?>
                    <select :disabled="mode=='detail'" name="tipe" class="form-control">
                      <option value="">-choose-</option>
                      <option <?= $outbound_form->tipe == "Between Warehouse" ? "selected" : "" ?> value="Between Warehouse">Between Warehouse</option>
                      <option <?= $outbound_form->tipe == "POS" ? "selected" : "" ?> value="POS">POS</option>
                    </select>
                    <?php else: ?>
                    <select name="tipe" class="form-control">
                      <option value="">-choose-</option>
                      <option value="Between Warehouse">Between Warehouse</option>
                      <option value="POS">POS</option>
                    </select>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warehouse asal</label>
                  <div class="col-sm-4">
                    <v-select :disabled="mode=='detail'" v-model="selectedWarehousePadaDealer" @search="getWarehousePadaDealer" :filterable="false" :options="warehousePadaDealer" label="id_gudang"></v-select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warehouse tujuan</label>
                  <div class="col-sm-4">
                    <v-select :disabled="mode=='detail'" v-model="selectedWarehouseTujuan" @search="getWarehouseTujuan" :filterable="false" :options="warehouseTujuan" label="id_gudang"></v-select>
                  </div>
                </div>
                <table class="table">
                  <tr>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td>Kuantitas Order</td>
                    <td>Rak</td>
                    <td v-if="mode!='detail'">Aksi</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">
                        {{ part.id_part }}
                        <input type="hidden" name="id_part[]" :value="part.id_part">
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td>
                        <input type="hidden" name="kuantitas[]" :value="part.kuantitas">
                        <vue-numeric :read-only="mode=='detail'" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas" :empty-value="1"/>
                      </td>
                      <td class="align-middle">
                        <input type="hidden" name="id_rak[]" v-model="part.selectedRak.id_rak">
                        <span v-if="mode=='detail'">{{ part.selectedRak.id_rak }}</span>
                        <select v-if="mode!='detail'" class="form-control" v-model="part.selectedRak">
                          <option v-for="e in rakLokasiBin" v-bind:value="e">{{ e.id_rak }}</option>
                        </select>
                      </td>
                      <td v-if="mode!='detail'" class="align-middle">
                        <button class="btn btn-sm" v-on:click.prevent="hapusPart(index)">Hapus</button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <button v-if="mode!='detail'" style="margin-top:15px" type="button" class="mt-3 pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-part">Tambah Part</button>
              <!-- Modal -->
              <div id="modal-part" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Part</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-part" style="width: 100%">
                            <thead>
                            <tr>
                                <th>ID Part</th>
                                <th>Part</th>
                                <th>Gudang</th>
                                <th>Rak</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <script>
                          $(document).ready(function(){
                              datatable_part = $('#datatable-part').DataTable({
                                  processing: true,
                                  serverSide: true,
                                  ordering: false,
                                  "language": {                
                                          "infoFiltered": ""
                                      },
                                  order: [],
                                  ajax: {
                                      url: "<?=base_url('api/availableStock') ?>",
                                      dataSrc: "data",
                                      data: function ( d ) {
                                            d.warehouse_asal = form_.selectedWarehousePadaDealer.id_gudang;
                                            return d;
                                        },
                                      type: "POST"
                                  },
                                  columns: [
                                      { data: 'id_part' },
                                      { data: 'nama_part' },
                                      { data: 'id_gudang' },
                                      { data: 'id_rak' },
                                      { data: 'stock' },
                                      { data: 'aksi' }
                                  ],
                              });
                          });
                          function pilihPart(part)
                          {
                            part.kuantitas = 1;
                            part.selectedRak = {};
                            form_.parts.push(part);
                          }
                      </script>
                </div>
              </div>
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
                  <div class="col-sm-6 no-padding">
                    <?php if ($mode == 'detail'): ?>
                    <a href="dealer/<?= $isi ?>/edit?k=<?= $outbound_form->id_outbound_form ?>"><button type="button" class="btn btn-primary btn-flat">Edit Order</button></a>
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
        selectedWarehousePadaDealer: <?= json_encode($this->gudang->find($outbound_form->id_warehouse_asal)) ?>,
        selectedWarehouseTujuan: <?= json_encode($this->gudang->find($outbound_form->id_warehouse_tujuan)) ?>,
        parts: <?= json_encode($parts) ?>,
        rakLokasiBin: <?= json_encode($this->rak->get(['id_gudang' => $outbound_form->id_warehouse_tujuan])) ?>,
        <?php else: ?>
        parts: [],
        selectedWarehousePadaDealer: {},
        selectedWarehouseTujuan: {},
        rakLokasiBin: [],
        <?php endif; ?>
        warehousePadaDealer: [],
        warehouseTujuan: [],
      },
      methods:{
        getWarehousePadaDealer : function(search, loading){
          loading(true);
          axios.get('api/warehouse/warehouse_pada_dealer', {
            params: {
              query: search
            }
          }).then(function(res){
            loading(false);
            form_.warehousePadaDealer = res.data;
          }).catch(function(err){
            loading(false);
            console.log(err);
          });
        },
        getWarehouseTujuan : function(search, loading){
          loading(true);
          axios.get('api/warehouse/warehouse_tujuan', {
            params: {
              warehouse_asal: form_.selectedWarehousePadaDealer.id_gudang,
              query: search,
            }
          }).then(function(res){
            loading(false);
            form_.warehouseTujuan = res.data;
          }).catch(function(err){
            loading(false);
            console.log(err);
          });
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        }
      },
      watch: {
        selectedWarehousePadaDealer: function(object){
          this.parts = [];
          this.selectedWarehouseTujuan = {};
          this.warehouseTujuan = [];
          datatable_part.draw();
        },
        selectedWarehouseTujuan: function(object){
          axios.get('api/h3_dealer_gudang_h23/rak_pada_gudang', {
            params: {
              k: object.id_gudang
            }
          }).then(function(res){
            form_.rakLokasiBin = res.data;
          }).catch(function(err){
            console.log(err);
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
              <th>Warehouse Asal</th>
              <th>Warehouse Tujuan</th>
              <th>Tipe</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($outbound_form) > 0): ?>
            <?php foreach ($outbound_form as $e): ?>
              <tr>
                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id_outbound_form ?>"><?= $e->id_warehouse_asal ?></a></td>
                <td><?= $e->id_warehouse_tujuan ?></td>
                <td><?= $e->tipe ?></td>
                <td><?= $e->status ?></td>
                <td><?= date('d-m-Y', strtotime($e->created_at)) ?></td>
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