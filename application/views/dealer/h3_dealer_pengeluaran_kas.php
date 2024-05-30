<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
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
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      } ?>

<script>
  Vue.use(VueNumeric.default);
</script>
      <div id="form_" class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div class="overlay" v-if='loading'>
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Voucher</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Entry Date</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Account Cash</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Account Name</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Dibayar Kepada</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                    <div class="col-sm-4">
                        <textarea cols="30" rows="3" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tr class='bg-blue-gradient'>
                        <td>No.</td>
                        <td>Account</td>
                        <td>Jenis Transaksi</td>
                        <td>Referensi</td>
                        <td>Sisa Hutang</td>
                        <td>Jumlah Dibayar</td>
                        <td width='20%'>Keterangan</td>
                        <td width='3%' class='text-center'></td>
                      </tr>
                      <tr>
                        <td class='align-middle'>1.</td>
                        <td class='align-middle'>COA</td>
                        <td class='align-middle'>Auto</td>
                        <td class='align-middle'>Referensi</td>
                        <td class='align-middle'>Rp 10.000.000</td>
                        <td class='align-middle'>Rp 5.000.000</td>
                        <td class='align-middle'>
                          <input type="text" class="form-control">
                        </td>
                        <td width='3%' class='text-center align-middle'>
                          <button class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                    </table>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            loading: false,
          },
          methods: {

          },
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">
          <table id="order_fulfillment" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Tanggal PO</th>
                <th>PO ID</th>
                <th>Nama Customer</th>
                <th>Kontak Customer</th>
                <th>Qty Order</th>
                <th>Qty Terpenuhi</th>
                <th>Qty Belum Terpenuhi</th>
                <th>Fulfillment Rate</th>
                <th>ETA Terlama</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              order_fulfillment = $('#order_fulfillment').DataTable({
                  initComplete: function() {
                      $('#order_fulfillment_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                      $('#order_fulfillment_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                      axios.get('html/filter_order_fulfillment')
                          .then(function(res) {
                              $('#order_fulfillment_filter').prepend(res.data);
                          });
                  },
                  processing: true,
                  serverSide: true,
                  ordering: false,
                  order: [],
                  ajax: {
                      url: "<?= base_url('api/dealer/order_fulfillment') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(data){
                        start_date = $('#filter_order_fulfillment_date_start').val();
                        end_date = $('#filter_order_fulfillment_date_end').val();
                        if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                            data.filter_order_fulfillment_date = true;
                            data.start_date = start_date;
                            data.end_date = end_date;
                        }
                      }
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: 'tanggal_po' },
                      { data: 'po_id' },
                      { data: 'nama_customer' },
                      { data: 'kontak_customer' },
                      { data: 'qty_order' },
                      { data: 'qty_terpenuhi' },
                      { data: 'qty_belum_terpenuhi' },
                      { data: 'fulfillment_rate' },
                      { data: 'eta_terlama' },
                      { data: 'action' },
                  ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>