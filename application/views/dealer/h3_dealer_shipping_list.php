<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>" type="text/javascript"></script>
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
        <i class="fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data Shipping List</b>
                </h4>
                <input type="hidden" name="generatePurchaseReturn" class="form-control" v-model="generatePurchaseReturn">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Shipping List</label>
                  <div class="col-sm-4">
                      <div class="input-group">
                        <input v-model='shipping_list.id_surat_pengantar' readonly type="text" class="form-control"> 
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' v-if='mode == "detail" || !surat_pengantar_terisi' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#shipping_list'><i class="fa fa-search"></i></button>
                          <button v-if='mode != "detail" && surat_pengantar_terisi' class="btn btn-flat btn-danger" @click.prevent='reset_surat_pengantar'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Shipping List</label>
                  <div class="col-sm-4">
                      <input v-if='shipping_list.tanggal_surat_pengantar != ""' :value='moment(shipping_list.tanggal_surat_pengantar).format("DD/MM/YYYY")' readonly type="text" class="form-control"> 
                      <input v-if='shipping_list.tanggal_surat_pengantar == ""' value='-' readonly type="text" class="form-control"> 
                  </div>
                </div>
                <?php $this->load->view('modal/shipping_list') ?>
                <script>
                  function pilih_shipping_list(data){
                    form_.shipping_list.id_surat_pengantar = data.id_surat_pengantar;
                    form_.shipping_list.tanggal_surat_pengantar = data.tanggal;
                    datatable_packing_sheet.draw();
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Packing Sheet</label>
                  <div class="col-sm-4">
                      <div class="input-group">
                        <input v-model='shipping_list.id_packing_sheet' readonly type="text" class="form-control"> 
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' v-if='mode == "detail" || !packing_sheet_terisi' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#packing_sheet'><i class="fa fa-search"></i></button>
                          <button v-if='mode != "detail" && packing_sheet_terisi' class="btn btn-flat btn-danger" @click.prevent='reset_packing_sheet'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Packing Sheet</label>
                  <div class="col-sm-4">
                      <input v-if='shipping_list.tanggal_packing_sheet != ""' :value='moment(shipping_list.tanggal_packing_sheet).format("DD/MM/YYYY")' readonly type="text" class="form-control"> 
                      <input v-if='shipping_list.tanggal_packing_sheet == ""' value='-' readonly type="text" class="form-control"> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor PO</label>
                  <div class="col-sm-4">
                      <input v-model='shipping_list.nomor_po' readonly type="text" class="form-control"> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">
                    <input v-if='shipping_list.tanggal_po != ""' :value='moment(shipping_list.tanggal_po).format("DD/MM/YYYY")' readonly type="text" class="form-control"> 
                    <input v-if='shipping_list.tanggal_po == ""' value='-' readonly type="text" class="form-control"> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Faktur</label>
                  <div class="col-sm-4">
                      <input v-model='shipping_list.nomor_faktur' readonly type="text" class="form-control"> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Faktur</label>
                  <div class="col-sm-4">
                    <input v-if='shipping_list.tanggal_faktur != ""' :value='moment(shipping_list.tanggal_faktur).format("DD/MM/YYYY")' readonly type="text" class="form-control"> 
                    <input v-if='shipping_list.tanggal_faktur == ""' value='-' readonly type="text" class="form-control"> 
                  </div>
                </div>
                <div v-if='mode == "detail"'>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Penerimaan</label>
                    <div class="col-sm-4">
                        <input v-model='shipping_list.id_penerimaan_barang' readonly type="text" class="form-control"> 
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                    <div class="col-sm-4">
                      <input v-if='shipping_list.tanggal != ""' :value='moment(shipping_list.tanggal).format("DD/MM/YYYY")' readonly type="text" class="form-control"> 
                      <input v-if='shipping_list.tanggal == ""' value='-' readonly type="text" class="form-control"> 
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/packing_sheet') ?>
                <script>
                  function pilih_packing_sheet(data){
                    form_.shipping_list.id_surat_pengantar = data.id_surat_pengantar;
                    form_.shipping_list.tanggal_surat_pengantar = data.tanggal_surat_pengantar;
                    form_.shipping_list.id_packing_sheet = data.id_packing_sheet;
                    form_.shipping_list.tanggal_packing_sheet = data.tanggal_packing_sheet;
                    form_.shipping_list.nomor_po = data.nomor_po;
                    form_.shipping_list.tanggal_po = data.tanggal_po;
                    form_.shipping_list.nomor_faktur = data.nomor_faktur;
                    form_.shipping_list.tanggal_faktur = data.tanggal_faktur;
                  }
                </script>
                <div v-if='parts_yang_belum_pilih_lokasi.length > 0' class="alert alert-warning" role="alert">
                  <strong>Perhatian!</strong> Terdapat part yang belum dipilihkan lokasi rak, antara lain:
                  <ul>
                    <li v-for='row of parts_yang_belum_pilih_lokasi'>Kode part {{ row.id_part }} pada nomor karton {{ row.no_dus }}</li>
                  </ul>
                </div>
                <div v-if='parts_harus_isi_alasan.length > 0' class="alert alert-warning" role="alert">
                  <strong>Perhatian!</strong> Terdapat part yang harus diisikan alasan, antara lain:
                  <ul>
                    <li v-for='row of parts_harus_isi_alasan'>{{ row }}</li>
                  </ul>
                </div>
                <div class='table-responsive'>
                <table class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td width='10%'>Nomor Parts</td>
                    <td width='10%'>Deskripsi Parts</td>
                    <td width='10%'>Serial Number</td>
                    <td width='10%'>No. Karton</td>
                    <td width="5%">Qty Ship</td>
                    <td width='5%'>Qty Good</td>
                    <td width="15%">Penyimpanan Good</td>
                    <td width="5%">Qty Bad</td>
                    <td width="15%">Penyimpanan Bad</td>
                    <td width="10%">Alasan Bad</td>
                    <td width="10%">Qty Tidak Terima</td>
                    <td width="10%">Alasan Tidak Terima</td>
                  </tr>
                  <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                    <td class="align-middle">{{ index + 1 }}.</td>
                    <td class="align-middle">{{ part.id_part }}</td>
                    <td class="align-middle">{{ part.nama_part }}</td>
                    <td class="align-middle"><b>{{ part.serial_number }}</b></td>
                    <td class="align-middle">{{ part.no_dus }}</td>
                    <td class='align-middle'>
                      <vue-numeric :read-only="true" class="form-control" thousand-separator="." v-model="part.qty_ship" :empty-value="1"/>
                    </td>
                    <td class='align-middle'>
                      <vue-numeric :max='part.qty_ship' :read-only="mode == 'detail'" class="form-control" thousand-separator="." v-model="part.qty_good" :empty-value="1"/>
                    </td>
                    <td class="align-middle">
                      <input v-if='mode != "detail"' v-model='part.id_gudang_good + " - " + part.id_rak_good' @click.prevent="changeIndexPart(index, 'good')" type="text" class="form-control" readonly>
                      <span v-if='mode == "detail"'>{{ part.id_gudang_good + " - " + part.id_rak_good }}</span>
                    </td>
                    <td class='align-middle'>
                      <vue-numeric v-if='part.qty_good != part.qty_ship' :read-only="mode == 'detail'" class="form-control" thousand-separator="." v-model="part.qty_bad" :empty-value="1"/>
                    </td>
                    <td class="align-middle">
                      <input v-if='mode != "detail" && part.qty_bad > 0' v-model='part.id_gudang_bad + " - " + part.id_rak_bad' @click.prevent="changeIndexPart(index, 'bad')" type="text" class="form-control" readonly>
                      <span v-if='mode == "detail"'>{{ menampilkan_lokasi_penyimpanan(part.id_gudang_bad, part.id_rak_bad) }}</span>
                    </td>
                    <td class="align-middle">
                      <input readonly v-if='mode != "detail" && part.qty_bad > 0' v-model='part.nama_claim_bad' type="text" class="form-control" @click.prevent='claim_c3(index, "bad")'>
                      <span v-if='mode == "detail"'>{{ part.nama_claim_bad }}</span>
                    </td>
                    <td class="align-middle">
                      <input readonly v-if='mode != "detail" && get_qty_tidak_terima(part) > 0' v-model='get_qty_tidak_terima(part)' type="text" class="form-control">
                      <span v-if='mode == "detail"'>{{ part.qty_tidak_terima }}</span>
                    </td>
                    <td class="align-middle">
                      <input readonly v-if='mode != "detail" && get_qty_tidak_terima(part) > 0' v-model='part.nama_claim_tidak_terima' type="text" class="form-control" @click.prevent='claim_c3(index, "tidak_terima")'>
                      <span v-if='mode == "detail"'>{{ part.nama_claim_tidak_terima }}</span>
                    </td>
                  </tr>
                  <tr v-if="parts.length < 1">
                    <td colspan="10" class="text-center text-muted">Belum ada part</td>
                  </tr>
                </table>
              </div>
              <div class="row" style="margin-bottom: 10px;">
                <div class="col-sm-6">
                  <button @click.prevent='<?= $form ?>' :disabled='parts_yang_belum_pilih_lokasi.length > 0 || parts_harus_isi_alasan.length > 0' v-if="mode=='insert'" type="button" class="btn btn-sm btn-primary btn-flat">Simpan</button>
                </div>
              </div>
              <?php $this->load->view('modal/rak_parts_shipping_list') ?>
              <script>
                function pilih_rak_parts(rak, index){
                  if(form_.tipe_rak == 'good'){
                    form_.parts[index].id_rak_int = rak.id_rak_int;
                    form_.parts[index].id_rak_good = rak.id_rak;
                    form_.parts[index].id_gudang_good = rak.id_gudang;
                    form_.parts[index].id_gudang_int = rak.id_gudang_int;
                  } else{
                    form_.parts[index].id_rak_int = rak.id_rak_int;
                    form_.parts[index].id_rak_bad = rak.id_rak;
                    form_.parts[index].id_gudang_bad = rak.id_gudang;
                    form_.parts[index].id_gudang_int = rak.id_gudang_int;
                  }
                }
              </script>
              <?php $this->load->view('modal/claim_c3_shipping_list') ?>
              <script>
                function pilih_claim_c3 (data) {
                  if(form_.tipe_alasan == 'bad'){
                    form_.parts[form_.indexPart].id_claim_bad = data.id;
                    form_.parts[form_.indexPart].kode_claim_bad = data.kode_claim;
                    form_.parts[form_.indexPart].nama_claim_bad = data.nama_claim;
                  }else{
                    form_.parts[form_.indexPart].id_claim_tidak_terima = data.id;
                    form_.parts[form_.indexPart].kode_claim_tidak_terima = data.kode_claim;
                    form_.parts[form_.indexPart].nama_claim_tidak_terima = data.nama_claim;
                  }
                }
              </script>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        auth: <?= json_encode(get_user('h3_dealer_shipping_list')) ?>, 
        mode : '<?= $mode ?>',
        loading: false,
        indexPart: 0,
        tipe_rak: '',
        tipe_alasan: '',
        selisih_penerimaan: 0,
        <?php if($mode == 'detail' OR $mode == 'edit'): ?>
        shipping_list: <?= json_encode($shipping_list) ?>,
        parts: <?= json_encode($penerimaan_barang_items) ?>,
        <?php else: ?>
        shipping_list: {
          id_surat_pengantar: '',
          tanggal_surat_pengantar: '',
          id_packing_sheet: '',
          tanggal_packing_sheet: '',
          nomor_po: '',
          tanggal_po: '',
          nomor_faktur: '',
          tanggal_faktur: '',
        },
        parts: [],
        <?php endif; ?>
        generatePurchaseReturn: false,
      },
      methods: {
        <?= $form ?>: function(){
          get_qty_tidak_terima_fn = this.get_qty_tidak_terima;

          post = {};
          post.id_surat_pengantar = this.shipping_list.id_surat_pengantar;
          post.id_packing_sheet = this.shipping_list.id_packing_sheet;
          post.nomor_po = this.shipping_list.nomor_po;
          post.generatePurchaseReturn = this.generatePurchaseReturn;
          post.selisih_penerimaan = this.selisih_penerimaan;
          post.parts = _.chain(this.parts)
          .map(function(part){
            data = _.pick(part, [
              'id_claim_bad', 'id_claim_tidak_terima', 'id_gudang_good', 'id_gudang_bad',
              'id_part_int', 'id_part', 'id_rak_bad', 'id_rak_good', 'no_dus', 'qty_bad', 'qty_good', 'qty_ship',
              'harga', 'harga_setelah_diskon', 'id_rak_int', 'id_gudang_int','serial_number'
            ]);
            data.qty_tidak_terima = get_qty_tidak_terima_fn(part);
            return data;
          })
          .value();
          
          this.loading = true;
          axios.post('dealer/h3_dealer_shipping_list/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;

            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              app.errors = data.errors;
              toastr.error(data.message);
            }else if(data.error_type == 'email_error'){
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){
            form_.loading = false;
          });
        },
        changeIndexPart: function(indexPart, tipe){
          this.tipe_rak = tipe;
          this.indexPart = indexPart;
          datatable_rak_parts_shipping_list.draw();
          $('#rak_parts_shipping_list').modal('show');
        },
        claim_c3: function(index, tipe){
          this.indexPart = index;
          this.tipe_alasan = tipe;
          $('#claim_c3_shipping_list').modal('show');
        },
        menampilkan_lokasi_penyimpanan: function(id_gudang, id_rak){
          if(id_gudang != null && id_rak){
            return id_gudang + " - " + id_rak;
          }
          return "-";
        },
        get_qty_tidak_terima: function(part){
          return parseInt(part.qty_ship) - ( parseInt(part.qty_good) + parseInt(part.qty_bad) );
        },
        hapus_part: function(index){
          this.parts.splice(index, 1);
        },
        get_parts: function(){
          if(this.shipping_list.id_packing_sheet == '') return;

          this.loading = true;
          this.parts = [];
          axios.get('dealer/<?= $isi ?>/get_parts_by_packing_sheet', {
            params: {
              id_packing_sheet: this.shipping_list.id_packing_sheet
            }
          })
          .then(function(res){
            form_.parts = res.data;
          })
          .catch(function(e){
            toastr.error(e);
          })
          .then(function(){ form_.loading = false; });
        },
        reset_surat_pengantar: function(){
          this.shipping_list.id_surat_pengantar = '';
          this.shipping_list.tanggal_surat_pengantar = '';
          this.reset_packing_sheet();
        },
        reset_packing_sheet: function(){
          this.shipping_list.id_surat_pengantar = '';
          this.shipping_list.tanggal_surat_pengantar = '';
          this.shipping_list.id_packing_sheet = '';
          this.shipping_list.tanggal_packing_sheet = '';
          this.shipping_list.nomor_po = '';
          this.shipping_list.tanggal_po = '';
          this.shipping_list.nomor_faktur = '';
          this.shipping_list.tanggal_faktur = '';
        }
      },
      watch: {
        'shipping_list.id_packing_sheet': function(){
          this.get_parts();
        },
        parts: {
          deep: true,
          handler: function(){
            for (let index = 0; index < this.parts.length; index++) {
              element = this.parts[index];
              if(!_.isEqual(element.qty_ship, element.qty_good)){
                this.selisih_penerimaan = 1;
                return;
              }
            }
          }
        }
      },
      computed: {
        parts_yang_belum_pilih_lokasi: function(){
          return _.chain(this.parts)
          .filter(function(part){
            if( parseInt(part.qty_good) > 0){
              if(part.id_gudang_good == '' || part.id_gudang_good == null){
                return true;
              }
            }

            if( parseInt(part.qty_bad) > 0){
              if(part.id_gudang_bad == '' || part.id_gudang_bad == null){
                return true;
              }
            }

            return false;
          })
          .value();
        },
        parts_harus_isi_alasan: function(){
          get_qty_tidak_terima_fn = this.get_qty_tidak_terima;
          return _.chain(this.parts)
          .filter(function(part){
            penerimaan_bad = parseInt(part.qty_bad) > 0 && (part.id_claim_bad == '' || part.id_claim_bad == null);
            penerimaan_tidak_terima = get_qty_tidak_terima_fn(part) > 0 && (part.id_claim_tidak_terima == '' || part.id_claim_tidak_terima == null);
            return penerimaan_bad || penerimaan_tidak_terima;
          })
          .map(function(part){
            penerimaan_bad = parseInt(part.qty_bad) > 0 && (part.id_claim_bad == '' || part.id_claim_bad == null);
            penerimaan_tidak_terima = get_qty_tidak_terima_fn(part) > 0 && (part.id_claim_tidak_terima == '' || part.id_claim_tidak_terima == null);
            if(penerimaan_bad && penerimaan_tidak_terima){
              return 'Kode part ' + part.id_part + ' pada nomor karton ' + part.no_dus + 'harus mengisi alasan penerimaan bad dan tidak terima.';
            }else if(penerimaan_tidak_terima){
              return 'Kode part ' + part.id_part + ' pada nomor karton ' + part.no_dus + 'harus mengisi alasan penerimaan tidak terima.';
            }else if(penerimaan_bad){
              return 'Kode part ' + part.id_part + ' pada nomor karton ' + part.no_dus + 'harus mengisi alasan penerimaan bad.';
            }
          })
          .value();
        },
        surat_pengantar_terisi: function(){
          return this.shipping_list.id_surat_pengantar != '' && this.shipping_list.id_surat_pengantar != null;
        },
        packing_sheet_terisi: function(){
          return this.shipping_list.id_packing_sheet != '' && this.shipping_list.id_packing_sheet != null;
        }
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
      <?php if(can_access('h3_dealer_shipping_list', 'can_insert')): ?>
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      <?php endif; ?>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="penerimaan_barang" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>No. Penerimaan Barang</th>
              <th>Tanggal Penerimaan</th>
              <th>No. Shipping List</th>
              <th>Tanggal Shipping List</th>
              <th>No Packing Sheet</th>
              <th>No PO</th>
              <th>No Faktur</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            penerimaan_barang = $('#penerimaan_barang').DataTable({
                initComplete: function () {
                  axios.get('html/filter_tipe_po_penerimaan')
                  .then(function(res){
                    $('#penerimaan_barang_filter').prepend(res.data);

                    $('#filter_penerimaan_barang').change(function(){
                      penerimaan_barang.draw();
                    });
                  });
                },
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/penerimaan_barang') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.tipe_po = $('#filter_penerimaan_barang').val();

                      start_date = $('#filter_shipping_date_start').val();
                      end_date = $('#filter_shipping_date_end').val();
                      if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                          d.filter_shipping_date = true;
                          d.start_date = start_date;
                          d.end_date = end_date;
                      }
                    },
                    type: "POST"
                },
                columns: [
                    { data: 'index', orderable:false, width: '3%' },
                    { data: 'nomor_penerimaan' },
                    { data: 'tanggal_penerimaan', name: 'pb.created_at' },
                    { data: 'nomor_shipping_list' },
                    { data: 'tanggal_shipping_list' },
                    { data: 'nomor_packing_sheet' },
                    { data: 'nomor_po' },
                    { data: 'no_faktur' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' }
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
</div>navi