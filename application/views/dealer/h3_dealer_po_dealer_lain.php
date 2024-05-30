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
                  <h4>
                    <b>Masukkan data Purchase Order</b>
                  </h4>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer Pembeli</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.kode_dealer' type="text" class="form-control" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer Pembeli</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.nama_dealer' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nomor Purchase Order</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.po_id' type="text" class="form-control" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Purchase Order</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.tanggal_order' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Booking Reference</label>
                    <div class="col-sm-4">
                        <input v-model='purchase_order.id_booking' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <table class="table">
                    <tr>
                      <td class="align-middle" width='3%'>No.</td>
                      <td class="align-middle">Part Number</td>
                      <td class="align-middle">Part Deskripsi</td>
                      <td class="align-middle">Qty</td>
                      <td class="align-middle text-right" width='15%'>Harga</td>
                      <td class="align-middle text-right">Sub Total</td>
                    </tr>
                    <tr v-if='parts.length > 0' v-for='(part, index) in parts'>
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">{{ part.kuantitas }}</td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only='true' v-model='part.harga_saat_dibeli' currency='Rp ' separator='.' class='form-control'></vue-numeric>
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only='true' v-model='subTotal(part)' currency='Rp ' separator='.' class='form-control'></vue-numeric>
                      </td>
                    </tr>
                  </table>
                  <div class="box-footer">
                    <div class="row">
                      <div class="col-sm-6"></div>
                      <div class="col-sm-6 text-right">
                        <a v-if='auth.can_submit && purchase_order.status != "Rejected"' :href="'dealer/h3_dealer_sales_order/add?po_dealer_lain=1&po_id=' + purchase_order.po_id" class="btn btn-flat btn-sm btn-info">Proses</a>
                        <button v-if='auth.can_reject && purchase_order.status != "Rejected"' class="btn btn-flat btn-sm btn-danger" type='button' data-toggle='modal' data-target='#reject_modal'>Reject</button>
                        <!-- Modal -->
                        <div id="reject_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                          <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                                    </div>
                                    <div class="modal-body">
                                      <div class="form-group">
                                        <div class="col-sm-12">
                                          <textarea class="form-control" id="alasan_reject"></textarea>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="col-sm-12">
                                          <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
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
            auth: <?= json_encode(get_user('h3_dealer_po_dealer_lain')) ?>, 
            mode: '<?= $mode ?>',
            loading: false,
            purchase_order: <?= json_encode($purchase_order) ?>,
            parts: <?= json_encode($parts) ?>
          },
          methods: {
            subTotal: function(part) {
              harga_setelah_diskon = part.harga_saat_dibeli;

              if(part.tipe_diskon == 'Rupiah'){
                harga_setelah_diskon = part.harga_saat_dibeli - part.diskon_value;
              }else if(part.tipe_diskon == 'Persen'){
                diskon = (part.diskon_value/100) * part.harga_saat_dibeli;
                harga_setelah_diskon = part.harga_saat_dibeli - diskon;
              }

              return part.kuantitas * harga_setelah_diskon;
            },
            reject: function(){
              post = {};
              post.po_id = this.purchase_order.po_id;
              post.keterangan = $('#alasan_reject').val();

              form_.loading = true;
              axios.post('dealer/h3_dealer_po_dealer_lain/reject', Qs.stringify(post))
              .then(function(res){
                console.log(res.data);
                if(_.isObject(res.data)){
                  window.location = 'dealer/h3_dealer_po_dealer_lain/detail?id=' + res.data.po_id;
                }
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){
                form_.loading = false;
              });
              
            },
          }
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-body">
          <link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">
          <table id="purchase_order" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>PO ID</th>
                <th>Periode</th>
                <th>PO Type</th>
                <th>Dealer</th>
                <th>Tanggal Order</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              purchase_order = $('#purchase_order').DataTable({
                  initComplete: function () {
                    axios.get('html/filter_status')
                    .then(function(res){
                      $('#purchase_order_filter').prepend(res.data);
                      $('#filter_status').change(function(){
                        purchase_order.draw();
                      });
                    });
                  },
                  processing: true,
                  serverSide: true,
                  ordering: false,
                  order: [],
                  ajax: {
                      url: "<?= base_url('api/dealer/purchase_order_dealer_lain') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(data){
                        data.filter_status = $('#filter_status').val();
                      }
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: 'aksi' },
                      { data: 'periode' },
                      { data: 'po_type' },
                      { data: 'dealer' },
                      { data: 'tanggal_order' },
                      { data: 'tanggal_selesai' },
                      { data: 'status' },
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