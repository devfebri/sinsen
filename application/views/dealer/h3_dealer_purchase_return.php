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

                  <label for="inputEmail3" class="col-sm-2 control-label">Purchase Return</label>

                  <div class="col-sm-4">

                      <input type="hidden" name="id_purchase_return" :value="purchase_return.id_purchase_return">

                      <input class="form-control" type="text" :value="purchase_return.id_purchase_return" disabled>

                  </div>

                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Purchase Order</label>

                  <div class="col-sm-4">

                      <input class="form-control" type="text" :value="purchase_return.po_id" disabled>

                  </div>

                </div>

                <table class="table">

                  <tr>

                    <td>Nomor Parts</td>

                    <td>Deskripsi Parts</td>

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

                        <input type="hidden" name="kuantitas[]" v-model="part.kuantitas">

                        <vue-numeric :read-only="mode=='detail'" class="form-control" thousand-separator="." v-model="part.kuantitas" :max="part.kuantitas"/>

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

                  <div class="col-sm-6 no-padding">

                    <?php if ($mode == 'detail' AND $purchase_return->id_ref === null): ?>

                    <a href="dealer/<?= $isi ?>/submitShippingList?k=<?= $purchase_return->id_purchase_return ?>"><button type="button" class="btn btn-primary btn-flat btn-sm">Submit Shipping List</button></a>

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

        purchase_return: <?= json_encode($purchase_return) ?>,

        <?php else: ?>

        parts: [],

        purchase_return: {},

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

              <th>Purchase Return</th>

              <th>Reference</th>

            </tr>

          </thead>

          <tbody>

          <?php if (count($purchase_return) > 0): ?>

            <?php foreach ($purchase_return as $e): ?>

              <?php $dealer = $this->dealer->find($e->id_dealer, 'id_dealer'); ?>

              <tr>

                <td><a href="dealer/<?= $isi ?>/detail?k=<?= $e->id_purchase_return ?>"><?= $e->id_purchase_return ?></a></td>

                <td><?= $dealer->nama_dealer ?></td>

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