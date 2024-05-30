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
          $form = 'detail';
      }
      if ($mode=='edit') {
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
      <div v-if='loading' class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php if ($mode=='insert'): ?>
                  <div class="alert alert-warning alert-dismissable">
                    <strong>Menu ini tidak tersedia untuk Penerimaan Parts EV, silahkan melakukan penerimaan melalui menu <a href='<?= base_url('dealer/h3_dealer_shipping_list') ?>'>Shipping List </a> </strong>
                    <button class="close" data-dismiss="alert">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                    </button>
                  </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data" @keydown.enter="$event.preventDefault()">
                <?php if ($mode=='edit'): ?>
                <input type="hidden" name="id_good_receipt" value="<?= $good_receipt->id_good_receipt ?>">
                <?php endif; ?>
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div v-if='mode == "detail"' class="form-group">
                  <label class="col-sm-2 control-label">Good Receipt</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.id_good_receipt' type="text" class="form-control" disabled>  
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tipe Referensi</label>
                  <div v-bind:class="{ 'has-error': error_exist('ref_type') }" class="col-sm-3">
                      <select :disabled="mode=='detail'" name="good_receipt" class="form-control" v-model="good_receipt.ref_type">
                        <option value="">-choose-</option>
                        <option value="return_exchange_so">Return/Exchange SO</option>
                        <option value="part_sales_work_order">Part Sales/Work Order</option>
                        <?php if($mode == 'detail'): ?>
                        <option value="shipping_list">Shipping List</option>
                        <?php endif; ?>
                      </select> 
                      <small v-if="error_exist('ref_type')" class="form-text text-danger">{{ get_error('ref_type') }}</small>
                  </div>
                </div>
                <div v-if='good_receipt.ref_type != ""' class="form-group">
                  <label class="col-sm-2 control-label">Nomor referensi</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_reference') }" class="col-sm-3">
                    <input v-model='good_receipt.id_reference' :readonly='good_receipt.pembelian_toko_umum == 0 || mode == "detail"' type="text" class="form-control col-sm-3">
                    <small v-if="error_exist('id_reference')" class="form-text text-danger">{{ get_error('id_reference') }}</small>
                  </div>
                  <div v-if='mode != "detail"' class="col-sm-2 no-padding">
                    <button v-if='good_receipt.pembelian_toko_umum == 0' type='button' data-toggle='modal' data-target='#referensi_good_receipt' class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                    <button type='button' @click.prevent='good_receipt.pembelian_toko_umum = !good_receipt.pembelian_toko_umum' class="btn btn-warning btn-flat"><i class="fa fa-pencil-square-o"></i></button>
                  </div>
                </div>
                <?php $this->load->view('modal/referensi_good_receipt') ?>
                <script>
                  function pilih_referensi_good_receipt(data){
                    form_.good_receipt.id_reference = data.id_referensi;
                    form_.good_receipt.nomor_so = data.nomor_so;
                    form_.good_receipt.nomor_po = data.nomor_po;
                    form_.good_receipt.nomor_sa_form = data.nomor_sa_form;
                    form_.good_receipt.nomor_wo = data.nomor_wo;
                    form_.get_referensi_parts();
                  }
                </script>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.nomor_so' type="text" class="form-control" readonly>  
                  </div>
                  <label class="col-sm-2 control-label">Nomor PO</label>
                  <div v-bind:class="{ 'has-error': error_exist('nomor_po') }" class="col-sm-3">
                      <input v-model='good_receipt.nomor_po' type="text" class="form-control" readonly>  
                      <small v-if="error_exist('nomor_po')" class="form-text text-danger">{{ get_error('nomor_po') }}</small>
                  </div>
                  <div v-if='good_receipt.pembelian_toko_umum == 1' class="col-sm-1 no-padding">
                    <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#purchase_order_create_submit_good_receipt'><i class="fa fa-search"></i></button>
                  </div>
                  <?php $this->load->view('modal/purchase_order_create_submit_good_receipt') ?>
                  <script>
                    function pilih_purchase_good_receipt(data){
                      form_.good_receipt.nomor_po = data.po_id;
                      form_.good_receipt.nomor_sa_form = data.id_sa_form;
                      form_.good_receipt.nomor_wo = data.id_work_order;
                      form_.get_referensi_parts_by_po();
                    }
                  </script>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nomor SA Form</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.nomor_sa_form' type="text" class="form-control" readonly>  
                  </div>
                  <label class="col-sm-2 control-label">Nomor WO</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.nomor_wo' type="text" class="form-control" readonly>  
                  </div>
                </div>
                </div>
                <div v-if='parts_yang_belum_dipilih_lokasi.length > 0' class="alert alert-warning" role="alert">
                  <strong>Perhatian!</strong> Terdapat part yang belum memilih tempat penyimpanan, antara lain: {{ parts_yang_belum_dipilih_lokasi.join(', ') }}
                </div>
                <table class="table table-striped">
                  <tr class='bg-blue-gradient'>
                    <td width="3%">No.</td>
                    <td width="10%">Nomor Parts</td>
                    <td width="10%">Deskripsi Parts</td>
                    <td width="5%">Qty PO</td>
                    <td width="5%">Qty Diterima</td>
                    <td width="8%">UoM</td>
                    <td width="12%">Gudang</td>
                    <td width="12%">Rak</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="good_receipt.ref_type != 'manual' || mode == 'detail'" class="form-control form-control-sm" thousand-separator="." v-model="part.qty_po"/>
                      </td>
                      <td class="align-middle">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control form-control-sm" thousand-separator="." v-model="part.qty" :max='part.qty_boleh_terima'/>
                      </td>
                      <td class="align-middle">
                        <span v-if='part.satuan != null'>{{ part.satuan }}</span>
                        <span v-if='part.satuan == null'>-</span>
                      </td>
                      <td class="align-middle">
                        <input v-model='part.id_gudang' @click.prevent='changeIndexPart(index)' type="text" class="form-control" readonly placeholder='Pilih Gudang'>
                      </td>
                      <td class="align-middle">
                        <input v-model='part.id_rak' @click.prevent='changeIndexPart(index)' type="text" class="form-control" readonly placeholder='Pilih Rak'>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                    </tr>
              </table>
              <div class="row">
                <div class="col-sm-12 text-right">
                    <button v-if='good_receipt.pembelian_toko_umum == 1 && mode != "detail"' class="btn btn-flat btn-primary margin btn-sm" type='button' data-toggle='modal' data-target='#parts_create_and_submit_good_receipt'><i class="fa fa-plus"></i></button>
                </div>
              </div>
              <?php $this->load->view('modal/rak_parts_good_receipt') ?>
              <script>
                function pilih_rak_parts(rak, index){
                  form_.parts[index].id_rak = rak.id_rak;
                  form_.parts[index].id_gudang = rak.id_gudang;
                }
              </script>
              <?php // $this->load->view('modal/parts_create_and_submit_good_receipt') ?>
              <script>
                function pilih_parts_good_receipt(data){
                  form_.parts.push(data);
                }
              </script>
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button @click.prevent='<?= $form ?>' v-if="mode=='insert'" :disabled='parts.length < 1 || parts_yang_belum_dipilih_lokasi.length > 0' type="submit" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <!-- <button v-if="mode=='edit'" type="submit" :disabled='parts_yang_belum_dipilih_lokasi.length > 0' class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button> -->
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
        auth: <?= json_encode(get_user('h3_dealer_create_submit_good_receipt')) ?>,
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        indexPart: 0,
        <?php if($mode=='detail'): ?>
        good_receipt: <?= json_encode($good_receipt) ?>,
        parts: <?= json_encode($good_receipt_parts) ?>,
        ref_type: '<?= $good_receipt->ref_type ?>',
        referensi: <?= $referensi != null ? json_encode($referensi) : '{}' ?>,
        <?php else: ?>
        good_receipt: {
          ref_type: '',
          pembelian_toko_umum: 0,
          id_reference: '',
          nomor_so: '',
          nomor_po: '',
          nomor_sa_form: '',
          nomor_wo: '',
        },
        parts: [],
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = {};
          post = this.good_receipt;
          if(this.good_receipt.pembelian_toko_umum == true){
            post.pembelian_toko_umum = 1;
          }else if(this.good_receipt.pembelian_toko_umum == false){
            post.pembelian_toko_umum = 0;
          }
          post.parts = _.map(this.parts, function(part){
            return _.pick(part, ['id_part_int', 'id_part', 'qty', 'id_gudang', 'id_rak', 'qty_po', 'harga', 'harga_setelah_diskon']);
          });
          this.errors = {};
          this.loading = true;
          // var confirmSubmit = confirm("Apakah Anda yakin ingin melanjutkan Penerimaan Barang?");
          // if (confirmSubmit) {
            axios.post('dealer/h3_dealer_create_submit_good_receipt/<?= $form ?>', Qs.stringify(post))
            .then(function(res){
              if(res.data.error_type=='validasi_qty_pemenuhan_part'){
                toastr.error(res.data.message);
              }else{
                window.location = 'dealer/h3_dealer_create_submit_good_receipt/detail?id=' + res.data.id_good_receipt;              
                console.log(res.data);
              }
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validasi_qty_pemenuhan_part' || data.error_type == 'validation_error'){
                form_.errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(err);
              }
            })
            .then(function(){
              form_.loading = false;
            });
          // }else{
          //   form_.loading = false;
          //   return false;
          // }
        },
        changeIndexPart: function(index){
          this.indexPart = index;
          $('#rak_parts_good_receipt').modal('show');
          datatable_rak_parts_good_receipt.draw();
        },
        get_referensi_parts_by_po: function(){
          this.loading = true;
          post = {};
          post.po_id = this.good_receipt.nomor_po
          axios.post('dealer/h3_dealer_create_submit_good_receipt/get_referensi_parts_by_po', Qs.stringify(post))
          .then(function(res){
            form_.parts = res.data;
          })
          .catch(function(e){
            toastr.error(e);
          })
          .then(function(){
            form_.loading = false;
          });
        },
        get_referensi_parts : function(){
          this.loading = true;
          post = {};
          post.id_referensi = this.good_receipt.id_reference
          axios.post('dealer/h3_dealer_create_submit_good_receipt/get_referensi_parts', Qs.stringify(post))
          .then(function(res){
            form_.parts = res.data;
          })
          .catch(function(e){
            toastr.error(e);
          })
          .then(function(){
            form_.loading = false;
          });
        },
        reset_form: function(){
          this.good_receipt.id_reference = '';
          this.good_receipt.nomor_so = '';
          this.good_receipt.nomor_po = '';
          this.good_receipt.nomor_sa_form = '';
          this.good_receipt.nomor_wo = '';
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        'good_receipt.ref_type': function(){
          this.reset_form();
          referensi_good_receipt_datatable.draw();
        },
        'good_receipt.pembelian_toko_umum': function(){
          this.reset_form();
        }
      },
      computed: {
        parts_yang_belum_dipilih_lokasi: function(){
          return _.chain(this.parts)
          .filter(function(part){
            return part.id_rak == null || part.id_rak == '';
          })
          .map(function(part){
            return part.id_part;
          })
          .value();
        },
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <?php if(can_access('h3_dealer_create_submit_good_receipt', 'can_insert')): ?>
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Tipe</label>
                <select id="filter_tipe_good_receipt" class="form-control">
                  <option value="">All</option>
                  <option value="part_sales">Part Sales</option>
                  <option value="work_order">Work Order</option>
                  <option value="b">Return Sales</option>
                </select>
                <script>
                  $(document).ready(function(){
                    $('#filter_tipe_good_receipt').on('change', function(){
                      good_receipt.draw();
                    });
                  })
                </script>
              </div>
            </div>
          </div>
        </div>
        <table id="good_receipt" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Good Receipt</th>
              <th>Tanggal Good Receipt</th>
              <th>Nomor PO</th>
              <th>Nama Supplier</th>
              <th>Tanggal PO</th>
              <th>Nomor SO</th>
              <th>Nomor Referensi</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            good_receipt = $('#good_receipt').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/good_receipt') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function (d) {
                      d.tipe = $('#filter_tipe_good_receipt').val();
                    }
                },
                columns: [
                    { data: null, width: '3%', orderable: false },
                    { data: 'id_good_receipt' },
                    { data: 'tanggal_receipt' },
                    { data: 'nomor_po' },
                    { data: 'nama_supplier' },
                    { data: 'tanggal_po' },
                    { data: 'nomor_so' },
                    { data: 'id_reference' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
            good_receipt.on('draw.dt', function() {
              var info = good_receipt.page.info();
              good_receipt.column(0, {
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