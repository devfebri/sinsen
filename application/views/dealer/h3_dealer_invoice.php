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
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Booking</label>
                  <div class="col-sm-4">
                      <input type="hidden" name="ref" :value="invoice.id_booking">
                      <input v-if="mode != 'detail'" type="text" class="form-control" :disabled="mode=='detail'" :value="invoice.id_booking" disabled> 
                      <input v-if="mode == 'detail'" type="text" class="form-control" :disabled="mode=='detail'" :value="invoice.ref" disabled> 
                  </div>
                  <div class="col-sm-2">
                    <button data-toggle="modal" data-target="#modal-customer" v-if="mode!='detail'" type="button" class=" btn btn-flat btn-info">Cari Customer</button>
                  </div>
                </div>
                <!-- Modal -->
                <div id="modal-customer" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Customer</h4>
                      </div>
                      <div class="modal-body">
                        <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-customer" style="width: 100%">
                                  <thead>
                                  <tr>
                                      <th>ID Booking</th>
                                      <th>Nama Customer</th>
                                      <th>Action</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                              <script>
                                $(document).ready(function(){
                                    $('#datatable-customer').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        "language": {                
                                                "infoFiltered": ""
                                            },
                                        order: [],
                                        ajax: {
                                            url: "<?= base_url('api/booking') ?>",
                                            dataSrc: "data",
                                            data: function ( d ) {
                                                  return d;
                                              },
                                            type: "POST"
                                        },
                                        columns: [
                                          { data: 'id_booking' },
                                          { data: 'id_customer' },
                                          { data: 'aksi' }
                                        ],
                                    });
                                });
                                function pilihBooking(booking)
                                {
                                  form_.invoice = booking;
                                  axios.get('dealer/h3_dealer_request_document/getRequestDocumentParts', {
                                    params: {
                                      id_booking: booking.id_booking
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
                    <td>Kuantitas Order</td>
                    <td>Harga Beli Parts</td>
                    <td>PPN</td>
                    <td>Sub total</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">
                        {{ part.id_part }}
                        <input type="hidden" name="id_part[]" :value="part.id_part">
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td>
                        <input type="hidden" name="kuantitas[]" :value="part.kuantitas">
                        <vue-numeric :read-only="true" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas" :empty-value="1"/>
                      <td class="align-middle text-right">
                        <input type="hidden" name="harga_saat_dibeli[]" v-model="part.harga_saat_dibeli">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.harga_saat_dibeli"/>
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="ppnPerPart(part)"/>
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="subTotal(part)"/>
                      </td>
                    </tr>
                    <tr v-if="parts.length > 0">
                      <td class="text-right" colspan="5">Total</td>
                      <td class="text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalInvoice"/>
                      </td>
                      <td></td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
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
        invoice: <?= json_encode($invoice) ?>,
        <?php else: ?>
        parts: [],
        invoice: {},
        <?php endif; ?>
      },
      methods:{
        ppnPerPart: function(part){
          return (10/100)* Number(part.harga_saat_dibeli);
        },
        subTotal: function(part){
          return part.kuantitas * part.harga_saat_dibeli + this.ppnPerPart(part);
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
              <th>Invoice</th>
              <th>Ref</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($invoice) > 0): ?>
            <?php foreach ($invoice as $e): ?>
              <tr>
                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id_invoice ?>"><?= $e->id_invoice ?></a></td>
                <td><?= $e->ref ?></td>
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