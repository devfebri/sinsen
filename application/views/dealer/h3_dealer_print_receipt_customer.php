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
      if ($mode == 'create_kwitansi') {
        $form = 'save_kwitansi';
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
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Sales Order</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Sales Order</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <tr class='bg-blue-gradient'>
                        <td>Metode Pembayaran</td>
                        <td>No. Rekening</td>
                        <td>Tanggal</td>
                        <td>Nominal Pembayaran</td>
                        <td width='3%' class='text-center'></td>
                      </tr>
                      <tr>
                        <td class='align-middle'>
                          <select class="form-control">
                            <option value="">-Choose-</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                          </select>
                        </td>
                        <td class='align-middle'>
                          <input type="text" class="form-control" readonly>
                        </td>
                        <td class='align-middle'>
                          <input class='form-control datepicker' readonly type="text">
                        </td>
                        <td class='align-middle'>
                          <vue-numeric class="form-control" currency='Rp ' separator='.'></vue-numeric>
                        </td>
                        <td v-if="mode != 'detail'" width='3%' class='text-center align-middle'>
                          <button class="btn btn-flat btn-primary" @click.prevent='add_item'><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </table>
                  </div>
              </form>
              <div class="box-footer">
                <div class="col-sm-6">
                  <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                  <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                  <a v-if='mode == "detail"' :href="'dealer/h3_dealer_penerimaan_kas/edit?id=' + penerimaan_kas.id_penerimaan_kas" class="btn btn-sm btn-flat btn-warning">Edit</a>
                </div>
              </div>
              <?php $this->load->view('modal/rekening_bank_print_receipt_customer') ?>
              <script>
                function pilih_rekening_bank_print_receipt_customer(data){
                  form_.item.kode_coa = data.kode_coa;
                  form_.item.coa = data.coa;
                  form_.item.tipe_transaksi = data.tipe_transaksi;
                }
              </script>
              <?php $this->load->view('modal/coa_penerimaan_kas') ?>
              <script>
                function pilih_coa_penerimaan_kas(data){
                  form_.penerimaan_kas.kode_coa = data.kode_coa;
                  form_.penerimaan_kas.coa = data.coa;
                  form_.penerimaan_kas.tipe_transaksi = data.tipe_transaksi;
                }
              </script>
              <?php $this->load->view('modal/referensi_penerimaan_kas') ?>
              <script>
                function pilih_referensi_penerimaan_kas(data){
                  form_.item.id_referensi = data.no_nsc;
                }
              </script>
            </div>
          </div>
        </div>
      </div>
      <script>
        form_ = new Vue({
          el: '#form_',
          data: {
            errors: [],
            loading: false,
            mode: '<?= $mode ?>',
            loading: false,
            <?php if($mode == "detail" or $mode == "edit"): ?>
            penerimaan_kas: <?= json_encode($penerimaan_kas) ?>,
            items: <?= json_encode($items) ?>,
            <?php else: ?>
            penerimaan_kas: {
              kode_coa: '',
              coa: '',
              tipe_transaksi: ''
            },
            items: [],
            <?php endif; ?>
            item: {
              kode_coa: '',
              coa: '',
              tipe_transaksi: '',
              id_referensi: '',
              nominal_dibayar: 0,
              keterangan: '',
            },
          },
          methods: {
            <?= $form ?>: function(){
              post = {};
              if(this.mode == 'edit'){
                post.id_penerimaan_kas = this.penerimaan_kas.id_penerimaan_kas;
              }
              post.kode_coa = this.penerimaan_kas.kode_coa;
              post.items = _.map(this.items, function(i){
                return _.pick(i, ['kode_coa', 'id_referensi', 'nominal_dibayar', 'keterangan']);
              });

              this.loading = true;
              this.errors = [];
              axios.post('dealer/h3_dealer_penerimaan_kas/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_penerimaan_kas/detail?id=' + res.data.payload.id_penerimaan_kas;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  form_.errors = data.payload;
                  toastr.error("Masih ada data yang kosong");
                }
              })
              .then(function(){ form_.loading = false; });
            },
            add_item: function(){
              this.items.push(this.item);
              this.reset_item();
            },
            reset_item: function(){
              this.item = {
                kode_coa: '',
                coa: '',
                tipe_transaksi: '',
                id_referensi: '',
                nominal_dibayar: 0,
                keterangan: '',
              };
            },
            hapus_item: function(index){
              this.items.splice(index, 1);
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
          <table id="print_receipt_customer" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Tanggal SO</th>
                <th>Nomor SO</th>
                <th>Nama Customer</th>
                <th>No. NSC</th>
                <th>Total Pembayaran</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              print_receipt_customer = $('#print_receipt_customer').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                      url: "<?= base_url('api/dealer/print_receipt_customer_h3') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(data){

                      }
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: 'tanggal_so' },
                      { data: 'nomor_so' },
                      { data: 'nama_pembeli' },
                      { data: 'no_nsc' },
                      { data: 'total_pembayaran' },
                      { data: 'action', orderable: false, width: '3%', className: 'text-center' },
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