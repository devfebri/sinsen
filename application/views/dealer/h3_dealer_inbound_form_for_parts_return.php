<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $title; ?></h1>
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
          $form = 'detail';
      }

      if ($mode=='edit') {
          $form = 'update';
      } ?>
<script>
  Vue.use(VueNumeric.default);
  Vue.component('v-select', VueSelect.VueSelect);
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div v-if='mode == "detail" || mode == "edit"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Inbound Form for Part Transfer</label>
                  <div class="col-sm-4">
                    <input v-model='inbound_form.id_inbound_form_for_parts_return' type="text" class="form-control" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-3 control-label">Tanggal Inbound Form for Part Transfer</label>
                  <div class="col-sm-3">
                    <input v-model='inbound_form.tanggal_inbound' type="text" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Surat Jalan</label>
                  <div class="col-sm-4">
                    <input v-model='inbound_form.id_surat_jalan' type="text" class="form-control" readonly>
                  </div>
                  <div class="col-sm-1">
                    <button v-show='mode == "insert"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#surat_jalan_inbound_form'><i class="fa fa-search"></i></button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                  <div class="col-sm-3">
                    <input v-model='inbound_form.tanggal_surat_jalan' type="text" class="form-control" readonly>
                  </div>
                </div>
                <?php $this->load->view('modal/surat_jalan_inbound_form') ?>
                <script>
                  function pilih_surat_jalan_inbound_form(data){
                    form_.inbound_form.id_surat_jalan = data.id_surat_jalan;
                    form_.inbound_form.tanggal_surat_jalan = data.tanggal_surat_jalan;
                    form_.inbound_form.id_outbound_form = data.id_outbound_form;
                    form_.inbound_form.tanggal_outbound = data.tanggal_outbound;
                    form_.inbound_form.id_event = data.id_event;
                    form_.inbound_form.nama_event = data.nama_event;
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Outbound form for fulfillment</label>
                  <div class="col-sm-4">
                    <input v-model='inbound_form.id_outbound_form' type="text" class="form-control" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-3 control-label">Tanggal Outbound form for fulfillment</label>
                  <div class="col-sm-3">
                    <input v-model='inbound_form.tanggal_outbound' type="text" class="form-control" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Event</label>
                  <div class="col-sm-4">
                    <input v-model='inbound_form.id_event' type="text" class="form-control" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-3 control-label">Nama Event</label>
                  <div class="col-sm-3">
                    <input v-model='inbound_form.nama_event' type="text" class="form-control" readonly>
                  </div>
                </div>
                <div v-show='mode != "detail"' class="row" style='margin-bottom: 15px;'>
                  <div class="col-sm-12 text-right">
                    <button class="btn btn-flat btn-sm btn-info" type='button' data-toggle='modal' data-target='#transaksi_penjualan_inbound_form'>Transaksi Penjualan</button>
                  </div>
                </div>
                <table v-if='invoices.length > 0' class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Invoice</td>
                    <td>Tanggal Invoice</td>
                    <td width="10%"></td>
                    <td v-if='mode != "detail"' class='text-right' width='3%'></td>
                  </tr>
                  <tr v-if='invoices.length > 0' v-for='(invoice, index_invoices) of invoices'>
                    <td class='align-middle'>{{ index_invoices + 1 }}.</td>
                    <td class='align-middle'>{{ invoice.nomor_invoice }}</td>
                    <td class='align-middle'>{{ invoice.tanggal_invoice }}</td>
                    <td class='align-middle' width="10%">
                      <button @click.prevent='change_index_part_invoice(index_invoices)' class="btn btn-flat btn-xs btn-info" type='button'>Detail Parts</button>
                    </td>
                    <td v-if='mode != "detail"' class='align-middle'><button @click.prevent='hapus_invoices(index_invoices)' class="btn btn-flat btn-sm btn-danger" type='button'><i class="fa fa-trash-o"></i></button></td>
                  </tr>
                  <tr v-if='invoices.length < 1'>
                    <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                  </tr>
                </table>
                <hr>
                <table class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td width="10%">Qty Book</td>
                    <td width="10%">Qty Return</td>
                    <td>Gudang</td>
                    <td>Rak</td>
                    <td>Reason</td>
                  </tr>
                  <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                    <td class='align-middle'>{{ index + 1 }}.</td>
                    <td class='align-middle'>{{ part.id_part }}</td>
                    <td class='align-middle'>{{ part.nama_part }}</td>
                    <td class='align-middle' width="10%">
                      <vue-numeric read-only='true' v-model='part.qty_book' class='form-control' separator='.'></vue-numeric>
                    </td>
                    <td class='align-middle' width="10%">
                      <vue-numeric :read-only='mode == "detail"' v-model='part.qty_return' class='form-control' separator='.'></vue-numeric>
                    </td>
                    <td class='align-middle'>
                      <input v-model='part.id_gudang' @click.prevent='change_index(index, "rak")' type="text" class="form-control" readonly>
                    </td>
                    <td class='align-middle'>
                      <input v-model='part.id_rak' @click.prevent='change_index(index, "rak")' type="text" class="form-control" readonly>
                    </td>
                    <td class='align-middle'>
                      <button class="btn btn-flat btn-sm btn-primary" @click.prevent='change_index(index, "reason")'>Reason</button>
                    </td>
                  </tr>
                  <tr v-if='parts.length < 1'>
                    <td colspan="8" class="text-center text-muted">Belum ada part</td>
                  </tr>
                </table>
              <?php $this->load->view('modal/transaksi_penjualan_inbound_form') ?>
              <?php $this->load->view('modal/reason_inbound_form') ?>
              <script>
                function pilih_reason_inbound_form(reason){
                  form_.parts[form_.index_part].id_reason = reason.id_reasons;
                  form_.parts[form_.index_part].reason = reason.deskripsi;
                }
              </script>
              <?php $this->load->view('modal/rak_parts_inbound_form') ?>
              <script>
                function pilih_rak_parts(rak, index){
                  if(form_.tipe_rak == 'rak_kerusakan'){
                    form_.parts[form_.index_part].reason[1].id_rak = rak.id_rak;
                    form_.parts[form_.index_part].reason[1].id_gudang = rak.id_gudang;
                  }else{
                    form_.parts[form_.index_part].id_rak = rak.id_rak;
                    form_.parts[form_.index_part].id_gudang = rak.id_gudang;
                  }
                }
              </script>
              <?php $this->load->view('modal/parts_transaksi_penjualan_inbound_form') ?>
              <script>
                function parts_transaksi_inbound_form(part) {
                  form_.invoice_parts.push(part);
                }
              </script>
              <?php $this->load->view('modal/detail_part_invoice') ?>
              <div class="box-footer">
                <div class="col-sm-6">
                  <button v-if='mode == "insert"' @click.prevent='<?= $form ?>' class="btn btn-sm btn-flat btn-primary" type='button'>Simpan</button>
                  <button v-if='mode == "edit"' @click.prevent='<?= $form ?>' class="btn btn-sm btn-flat btn-warning" type='button'>Update</button>
                  <a v-if='auth.can_update && mode == "detail" && inbound_form.status != "Closed"' :href="'dealer/h3_dealer_inbound_form_for_parts_return/edit?id=' + inbound_form.id_inbound_form_for_parts_return" class="btn btn-sm btn-flat btn-warning">Edit</a>
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <button v-if='auth.can_transit && inbound_form.status != "Closed" && mode == "detail"' class="btn btn-flat btn-sm btn-primary" @click.prevent='parts_transfer'>Parts Transfer</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  form_ = new Vue({
      el: '#form_',
      mounted: function(){
        for (index = 0; index < this.invoices.length; index++) {
          element = this.invoices[index];
          axios.get('dealer/h3_dealer_inbound_form_for_parts_return/get_invoice_parts', {
            params: {
              id: element.id,
              index: index,
            }
          })
          .then(function(res){
            data = res.data;
            form_.invoices[data.index].parts = data.result;
          })
          .catch(function(err){
            toastr.error(err);
          })
        }
      },
      data: {
        auth: <?= json_encode(get_user('h3_dealer_inbound_form_for_parts_return')) ?>,
        kosong :'',
        mode : '<?= $mode ?>',
        loading: false,
        index_part: 0,
        tipe_rak: '',
        index_part_invoice: 0,
        parts_contain_transaction: false,
        reason_part: [],
        <?php if($mode == 'edit' OR $mode == 'detail'): ?>
        inbound_form: <?= json_encode($inbound_form) ?>,
        parts: <?= json_encode($parts) ?>,
        invoices: <?= json_encode($invoices) ?>,
        <?php else: ?>
        inbound_form: {
          id_surat_jalan: '',
          tanggal_surat_jalan: '',
          id_outbound_form: '',
          tanggal_outbound: '',
          id_event: '',
          nama_event: '',
        },
        parts: [],
        invoices: [],
        <?php endif; ?>
        invoice: {},
        invoice_parts: [],
        invoice_part_detail: [],
      },
      methods: {
        <?= $form ?>: function(){
          part_columns = ['id_part', 'id_reason', 'id_gudang', 'id_rak', 'qty_book', 'qty_return', 'reason'];
          post = _.pick(this.inbound_form, 'id_outbound_form');
          if(this.mode == 'edit'){
            post.id_inbound_form = this.inbound_form.id_inbound_form_for_parts_return;
            part_columns.push('id');
          }
          post.parts = _.map(this.parts, function(part){
            return _.pick(part, part_columns)
          });
          post.invoices = this.invoices;

          this.loading = true;
          axios.post('dealer/h3_dealer_inbound_form_for_parts_return/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'dealer/h3_dealer_inbound_form_for_parts_return/detail?id=' + res.data.id_inbound_form_for_parts_return;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        parts_transfer: function(){
          post = {};
          post.id_inbound_form = this.inbound_form.id_inbound_form_for_parts_return;
          post.id_outbound_form = this.inbound_form.id_outbound_form;

          this.loading = true;
          axios.post('dealer/h3_dealer_inbound_form_for_parts_return/parts_transfer', Qs.stringify(post))
          .then(function(res){
            // window.location = 'dealer/h3_dealer_inbound_form_for_parts_return/detail?id=' + res.data.id_inbound_form_for_parts_return;
          })
          .catch(function(e){
            toastr.error(e);
          })
          .then(function(){ form_.loading = false; })
        },
        hapus_part: function(index){
          this.parts.splice(index, 1);
        },
        hapus_invoice_part: function(index){
          this.invoice_parts.splice(index, 1);
        },
        hapus_invoices: function(index){
          this.invoices.splice(index, 1);
        },
        button_plus: function(index_reason){
          this.parts[this.index_part].reason[index_reason].plus = 1;
          this.parts[this.index_part].reason[index_reason].minus = 0;
          this.generate_qty_return_parts();
        },
        button_minus: function(index_reason){
          this.parts[this.index_part].reason[index_reason].minus = 1;
          this.parts[this.index_part].reason[index_reason].plus = 0;
          this.generate_qty_return_parts();
        },
        simpan_invoice: function(){
          invoice = {};
          invoice.nomor_invoice = this.invoice.nomor_invoice;
          invoice.tanggal_invoice = $('#tanggal_invoice').val();
          invoice.parts = this.invoice_parts;

          this.invoice = {};
          this.invoice_parts = [];
          $('#tanggal_invoice').val('');

          this.invoices.push(invoice);

          $('#transaksi_penjualan_inbound_form').modal('toggle');
        },
        change_index: function(index, tipe){
          this.index_part = index;
          if(tipe == 'rak' && this.mode != 'detail'){
            // parts_inbound_form_datatable.draw();
            $('#rak_parts_inbound_form').modal('show');
          }else if(tipe == 'reason'){
            // reason_inbound_form_datatable.draw();
            this.reason_part = this.parts[this.index_part].reason;
            $('#reason_inbound_form').modal('show');
          }else if(tipe == 'rak_kerusakan' && this.mode != 'detail'){
            this.tipe_rak = tipe;
            $('#rak_parts_inbound_form').modal('show');
          }
        },
        change_index_part_invoice: function(index){
          this.index_part_invoice = index;
          this.invoice_part_detail = this.invoices[index].parts;
          $('#detail_part_invoice').modal('show');
        },
        check_parts_contain_transaction: function(){
          if(this.parts.length < 1){
            this.parts_contain_transaction = false;
            return;
          }else{
            for(part of this.parts){
              if(part.reason == 'Transaksi penjualan'){
                this.parts_contain_transaction = true;
                return;
              }
            }
            this.parts_contain_transaction = false;
          }
        },
        generate_qty_return_parts: function(){
          for (var index_part = 0; index_part < this.parts.length; index_part++) {
            var part = this.parts[index_part];
            part.qty_return = part.qty_book;
            part.reason[0].qty = 0;
            for (var index_invoice = 0; index_invoice < this.invoices.length; index_invoice++) {
              var invoice = this.invoices[index_invoice];
              for (let index_invoice_item = 0; index_invoice_item < invoice.parts.length; index_invoice_item++) {
                var invoice_part = invoice.parts[index_invoice_item];
                if(part.id_part == invoice_part.id_part){
                  part.qty_return -= invoice_part.qty;
                  part.reason[0].qty += invoice_part.qty;
                }
              }
            }
            part.qty_return -= part.reason[2].qty;
            if(part.reason[3].plus == 1){
              part.qty_return += Number(part.reason[3].qty);
            }else if(part.reason[3].minus == 1){
              part.qty_return -= Number(part.reason[3].qty);
            }

            if(part.reason[4].plus == 1){
              part.qty_return += Number(part.reason[4].qty);
            }else if(part.reason[4].minus == 1){
              part.qty_return -= Number(part.reason[4].qty);
            }
            this.parts[index_part] = part;
          }
        },
        qty_reason_change: function(reason, event){
          if(reason != 'Kerusakan'){
            this.generate_qty_return_parts();
          }
        },
        get_fulfillment_parts: function(){
          this.parts = [];
          this.loading = true;
          axios.get('dealer/h3_dealer_inbound_form_for_parts_return/get_fulfillment_parts', {
            params: {
              outbound_fulfillment: this.inbound_form.id_outbound_form
            }
          })
          .then(function(res){
            form_.parts = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        }
      },
      computed: {
        surat_jalan_empty: function(){
          return this.inbound_form.id_surat_jalan == '';
        },
      },
      watch: {
        'inbound_form.id_surat_jalan': {
          deep: true,
          handler: function(){
            this.get_fulfillment_parts();
            parts_transaksi_penjualan_inbound_form_datatable.draw();
          }
        },
        parts: {
          deep: true,
          handler: function(){
            this.check_parts_contain_transaction();
            // parts_transaksi_penjualan_inbound_form_datatable.draw();
          }
        },
        invoices: {
          deep: true,
          handler: function(){
            this.generate_qty_return_parts();
          }
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
        <?php if(can_access('h3_dealer_inbound_form_for_parts_return', 'can_insert')): ?>
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        <?php endif; ?>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="inbound_form_parts_return" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>No. Inbound form</th>
              <th>Tanggal Inbound</th>
              <th>No. Outbound form</th>
              <th>Tanggal Outbound</th>
              <th>Surat Jalan</th>
              <th>No. Event</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            inbound_form_parts_return = $('#inbound_form_parts_return').DataTable({
                initComplete: function() {
                    $('#inbound_form_parts_return_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                    $('#inbound_form_parts_return_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                    axios.get('html/filter_inbound_form_parts_return')
                        .then(function(res) {
                            $('#inbound_form_parts_return_filter').prepend(res.data);

                            $('#filter_status_inbound_form_parts_return').change(function() {
                                inbound_form_parts_return.draw();
                            });
                        });
                },
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/inbound_form_parts_return') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                      d.filter_status = $('#filter_status_inbound_form_parts_return').val();
                    }
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'nomor_inbound', name: 'ifpr.id_inbound_form_for_parts_return' },
                    { data: 'tanggal_inbound', name: 'ifpr.created_at' },
                    { data: 'nomor_outbound', name: 'off.id_outbound_form_for_fulfillment' },
                    { data: 'tanggal_outbound', name: 'ifpr.created_at' },
                    { data: 'id_surat_jalan' },
                    { data: 'id_event' },
                    { data: 'status', orderable: false },
                    { data: 'action', width: '3%', orderable: false, className: 'text-left' },
                ],
            });
            inbound_form_parts_return.on('draw.dt', function() {
                var info = inbound_form_parts_return.page.info();
                inbound_form_parts_return.column(0, {
                    search: 'applied',
                    order: 'applied',
                    page: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + info.start + ".";
                });
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