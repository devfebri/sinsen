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
                <input type="hidden" name="id_invoice" value="<?= $invoice->id_booking ?>">
                <?php endif; ?>
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-4">
                      <input type="hidden" name="nomor_so" :value="sales_order.nomor_so">
                      <input class="form-control" type="text" name="nomor_so" :value="sales_order.nomor_so" disabled>
                  </div>
                  <div class="col-sm-2">
                    <button data-toggle="modal" data-target="#modal-sales-order" v-if="mode!='detail'" type="button" class=" btn btn-flat btn-info">Cari SO</button>
                  </div>
                </div>
                <!-- Modal -->
                <div id="modal-sales-order" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Sales Order</h4>
                      </div>
                      <div class="modal-body">
                        <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-sales-order" style="width: 100%">
                                  <thead>
                                  <tr>
                                      <th>Nomor SO</th>
                                      <th>Customer</th>
                                      <th>Action</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                              <script>
                                $(document).ready(function(){
                                    $('#datatable-sales-order').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        "language": {                
                                                "infoFiltered": ""
                                            },
                                        order: [],
                                        ajax: {
                                            url: "<?= base_url('api/sales_order_untuk_sales_return') ?>",
                                            dataSrc: "data",
                                            data: function ( d ) {
                                                  return d;
                                              },
                                            type: "POST"
                                        },
                                        columns: [
                                          { data: 'nomor_so' },
                                          { data: 'id_customer' },
                                          { data: 'aksi' }
                                        ],
                                    });
                                });
                                function pilihSo(sales_order)
                                {
                                  form_.sales_order = sales_order;
                                  axios.get('dealer/h3_dealer_sales_order_return/ambil_sales_order_data', {
                                    params: {
                                      nomor_so: sales_order.nomor_so
                                    }
                                  })
                                  .then(function (response) {
                                    form_.parts = response.data;
                                  })
                                  .catch(function (error) {
                                    console.log(error);
                                  });
                                }
                            </script>
                      </div>
                    </div>
                  </div>
                </div>
                <table class="table">
                  <tr>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td width="20%">Kuantitas Order</td>
                    <td width="20%">Kuantitas Return</td>
                    <td v-if="mode!='detail'" width="10%">Aksi</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">
                        {{ part.id_part }}
                        <input type="hidden" name="id_part[]" :value="part.id_part">
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <input type="hidden" name="kuantitas[]" :value="part.kuantitas">
                        <vue-numeric :read-only="true" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas" :empty-value="1"/>
                      <td class="align-middle">
                        <input type="hidden" name="kuantitas_return[]" v-model="part.kuantitas_return">
                        <vue-numeric :read-only="mode=='detail'" class="form-control" thousand-separator="." v-model="part.kuantitas_return" :max="part.kuantitas"/>
                      </td>
                      <td v-if="mode!='detail'" class="align-middle">
                        <button class="btn btn-sm" v-on:click.prevent="hapusPart(index)">Hapus</button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="4" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
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
                    <!-- <a href="dealer/<?= $isi ?>/edit?k=<?= $invoice->id_invoice ?>"><button type="button" class="btn btn-primary btn-flat">Edit Order</button></a> -->
                    <?php endif; ?>
                  </div>
                  <div class="col-sm-6">
                    <?php if ($mode == 'detail'): ?>
                      <?php //if($request_document['status'] == 'Draft' OR $request_document['status'] == 'Submitted'):?>
                        <?php //if(($request_document['status'] == 'Submitted' AND $user_group['code'] == 'pic_dealer') OR $request_document['status'] == 'Draft'):?>
                          <!-- <a href="dealer/<?= $isi ?>/status?k=<?= $request_document->id_booking ?>&status=Canceled"><button type="button" class="btn btn-box-tool btn-flat pull-right">Cancel Order</button></a> -->
                        <?php //endif;?>
                      <?php //endif;?>
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
        parts: <?= json_encode($parts) ?>,
        sales_order: <?= json_encode($sales_order) ?>,
        <?php else: ?>
        parts: [],
        sales_order: {},
        <?php endif; ?>
      },
      methods:{
        hapusPart: function(index){
          this.parts.splice(index, 1);
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
              <th>Sales Return</th>
              <th>Sales Order</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($sales_order_return) > 0): ?>
            <?php foreach ($sales_order_return as $e): ?>
              <tr>
                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id_sales_order_return ?>"><?= $e->id_sales_order_return ?></a></td>
                <td><?= $e->nomor_so ?></td>
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