<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/vue/custom/vb-rangedatepicker.js") ?>"></script> 
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
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
          <?php if($mode == 'detail' AND $sales_order->status != 'Closed' AND $sales_order->status != 'Canceled'): ?>
          <a href="dealer/<?= $isi ?>/update_harga?nomor_so=<?= $sales_order->nomor_so ?>">
            <button class="btn bg-primary btn-flat margin">Update Harga</button>
          </a>
          <?php endif; ?>
        </h3>
        <div class="box-tools pull-right">
          <div class="container-fluid">
            <div v-if='gift_promo.length > 0' v-for='(each) of gift_promo' class="row">
              <span class="label label-primary">{{ each }}</span>
            </div>
          </div>
        </div>
      </div><!-- /.box-header -->
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data Sales Order</b>
                </h4>
                <div v-if='mode == "detail"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sales Order</label>
                  <div class="col-sm-4">
                      <input v-model='sales_order.nomor_so' type="text" class="form-control" disabled> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Order</label>
                  <div class="col-sm-4">
                      <input v-model='sales_order.tanggal_so' type="text" class="form-control" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_customer') }" class="col-sm-4">
                    <input type="text" class="form-control pull-left" v-model="sales_order.id_customer" readonly data-toggle="modal" data-target="#modal-customer-detail">
                    <small v-if="error_exist('id_customer')" class="form-text text-danger">{{ get_error('id_customer') }}</small> 
                  </div>
                  <div class="col-sm-1 no-padding">
                    <button v-if="mode != 'detail' && !customer_empty && !pembelian_dari_dealer_lain && booking_empty" @click.prevent='reset_customer' class="btn btn-flat btn-danger" type="button" ><i class="fa fa-trash-o"></i></button>
                    <button v-if="mode != 'detail' && customer_empty && !pembelian_dari_dealer_lain" class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#modal-customer"><i class="fa fa-search"></i></button>
                  </div>
                  <?php $this->load->view('modal/customer_detail_modal') ?>
                  <?php $this->load->view('modal/h3_dealer_customer_sales_order') ?>
                  <script>
                    function pilihCustomer(customer){
                      form_.sales_order.id_customer = customer.id_customer;
                      form_.sales_order.id_customer_int = customer.id_customer_int;
                      form_.sales_order.nama_customer = customer.nama_customer;
                      form_.sales_order.alamat = customer.alamat;
                      form_.sales_order.no_hp = customer.no_hp;
                      form_.sales_order.no_mesin = customer.no_mesin;
                      form_.sales_order.no_rangka = customer.no_rangka;
                    }
                  </script>
                  <div v-if='mode == "detail"'>
                    <label for="inputEmail3" class="col-sm-1 control-label">Status</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="sales_order.status" readonly> 
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pembeli</label>
                  <div v-bind:class="{ 'has-error': error_exist('nama_pembeli') }" class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="sales_order.nama_pembeli">
                      <small v-if="error_exist('nama_pembeli')" class="form-text text-danger">{{ get_error('nama_pembeli') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP Pembeli</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_hp_pembeli') }" class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="sales_order.no_hp_pembeli">
                      <small v-if="error_exist('no_hp_pembeli')" class="form-text text-danger">{{ get_error('no_hp_pembeli') }}</small> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Uang Muka</label>
                  <div class="col-sm-4">
                      <vue-numeric :readonly="mode == 'detail'" type="text" class="form-control" separator="." currency='Rp ' v-model="sales_order.uang_muka"/> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div v-bind:class="{ 'has-error': error_exist('alamat_pembeli') }" class="col-sm-4">
                    <input :readonly='mode == "detail"' type="text" class="form-control" v-model='sales_order.alamat_pembeli'>
                    <small v-if="error_exist('alamat_pembeli')" class="form-text text-danger">{{ get_error('alamat_pembeli') }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Booking Reference</label>
                  <div v-bind:class="{ 'has-error': error_exist('booking_id_reference') }" class="col-sm-4">
                      <input v-model="sales_order.booking_id_reference" type="text" class="form-control" readonly>
                      <small v-if="error_exist('booking_id_reference')" class="form-text text-danger">{{ get_error('booking_id_reference') }}</small>
                  </div>
                  <div class="col-sm-2 no-padding">
                    <button v-if='!booking_empty && purchase_empty' @click.prevent='reset_booking' class="btn btn-flat btn-danger"><i class="fa fa-trash-o"></i></button>
                    <button v-if='mode != "detail" && booking_empty && !pembelian_dari_dealer_lain' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#booking_reference_sales_order'><i class="fa fa-search"></i></button>
                  </div>
                </div>
                <?php $this->load->view('modal/booking_reference_sales_order') ?>
                <script>
                  function pilih_booking_reference_sales_order(data){
                    form_.sales_order.booking_id_reference = data.id_booking;
                    form_.sales_order.id_customer_int = data.id_customer_int;
                    form_.sales_order.id_customer = data.id_customer;
                    form_.sales_order.nama_customer = data.nama_customer;
                    form_.sales_order.alamat = data.alamat;
                    form_.sales_order.no_hp = data.no_hp;
                    form_.sales_order.no_mesin = data.no_mesin;
                    form_.sales_order.no_rangka = data.no_rangka;
                    form_.sales_order.uang_muka = data.uang_muka;
                    form_.get_parts_by_booking();
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label no-padding">Pembelian dealer lain</label>
                  <div class="col-sm-4">
                      <input :disabled='mode != "insert" || pembelian_dari_dealer_lain_query' v-model='sales_order.pembelian_dari_dealer_lain' type="checkbox" true-value='1' false-value='0'>
                  </div>
                </div>
                <div v-show="pembelian_dari_dealer_lain">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Purchase Order</label>
                    <div class="col-sm-4">
                      <input v-model='sales_order.po_id' type="text" class="form-control" readonly>
                    </div>
                    <div class="col-sm-2 no-padding">
                      <button v-if='purchase_empty && !pembelian_dari_dealer_lain_query' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#modal_purchase_order_dealer_lain'><i class="fa fa-search"></i></button>
                      <button v-if='!purchase_empty && !pembelian_dari_dealer_lain_query && mode != "detail"' class="btn btn-flat btn-danger" type='button' @click.prevent='reset_purchase'><i class="fa fa-trash-o"></i></button>
                    </div>
                  </div>
                  <?php $this->load->view('modal/purchase_order_dealer_lain') ?>
                  <script>
                    function pilih_purchase_dealer_lain(data){
                      form_.sales_order.po_id = data.po_id;
                      form_.sales_order.nama_pembeli = data.dealer;
                      form_.sales_order.no_hp_pembeli = data.no_telp;
                      form_.sales_order.alamat_pembeli = data.alamat;
                      form_.sales_order.id_dealer_pembeli = data.id_dealer;
                      form_.get_purchase_parts();
                      form_.get_booking_reference();
                      form_.get_customer();
                    }
                  </script>
                </div>
                <div v-if='parts_yang_kuantitas_nol.length > 0' class="alert alert-warning" role="alert">
                  <strong>Perhatian!</strong> Terdapat part yang kuantitas 0, antara lain: {{ parts_yang_kuantitas_nol.join(', ') }}
                </div>
                <table class="table table-condensed table-hover">
                  <tr>
                    <td width="3%">No.</td>
                    <td width="10%">Part Number</td>
                    <td width="11%">Deskripsi Part</td>
                    <td width="10%">Qty</td>
                    <td width="5%">UoM</td>
                    <td width="15%">Rak</td>
                    <td width="15%">Tipe Disc</td>
                    <td width="10%">Promo</td>
                    <td width="10%" class="text-right">HET</td>
                    <td width="15%" class="text-right">Sub total</td>
                    <td width="1%" v-if="mode!='detail' && !pembelian_dari_dealer_lain_query"></td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts" class=''>
                      <td class="align-top">{{ index + 1 }}.</td>
                      <td class="align-top">{{ part.id_part }}</td>
                      <td class="align-top">{{ part.nama_part }}</td>
                      <td class="align-top">
                        <vue-numeric class="form-control" :read-only="mode=='detail' || !booking_empty" thousand-separator="." v-model="part.kuantitas" :empty-value="1" :max='get_max_kuantitas_order(part)'/>
                      </td>
                      <td class="align-top">
                        {{ part.satuan }}
                      </td>
                      <td class="align-top">
                        <input readonly @click.prevent="updateIndexPart(index)" v-model='part.id_rak' type="text" class="form-control">
                      </td>
                      <td class="align-top">
                        <select class='form-control' :disabled="mode == 'detail'" v-model="part.tipe_diskon">
                          <option :value='null'>-No Disc-</option>
                          <option value="Percentage">Percentage</option>
                          <option value="FoC">FoC</option>
                          <option value="Value">Value</option>
                        </select>
                        <vue-numeric v-if='part.tipe_diskon != "" && part.tipe_diskon != null' style='margin-top: 10px' :disabled="mode == 'detail'" class="form-control" thousand-separator="." v-model="part.diskon_value"/>
                      </td>
                      <td class="align-top">
                        <input v-model='part.selected_promo.nama' type="text" class="form-control" readonly @click.prevent='pilih_promo_modal(index)' placeholder='Pilih Promo'>
                        <button v-if='_.get(part, "selected_promo.hadiah_per_item") == 1' style='margin-top: 10px;' class="btn btn-flat btn-primary" @click.prevent='view_promo_modal(index)'>View</button>
                      </td>
                      <td class="align-top text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.harga_saat_dibeli"/>
                      </td>
                      <td class="align-top text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="subTotal(part)"/>
                      </td>
                      <td v-if="mode!='detail' && !pembelian_dari_dealer_lain_query" class="align-top text-right">
                        <button v-if='booking_empty' class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length > 0">
                      <td class="text-right" colspan="9">Total</td>
                      <td class="text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalTanpaPPN"/>
                      </td>
                    </tr>
                    <!-- <tr v-if="parts.length > 0">
                      <td class="text-right" colspan="9">PPN</td>
                      <td class="text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalPPN"/>
                      </td>
                    </tr> -->
                    <!-- <tr v-if="parts.length > 0">
                      <td class="text-right" colspan="9">Total</td>
                      <td class="text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalDenganPPN"/>
                      </td>
                    </tr> -->
                    <tr v-if="parts.length < 1">
                      <td colspan="11" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <div class="row">
                <div class="col-sm-12 text-right">
                  <!-- <button class="btn btn-flat btn-primary btn-sm margin" type='button' data-toggle='modal' data-target='#view_hadiah_master_promo'>Hadiah</button> -->
                  <button v-if="mode != 'detail' && !pembelian_dari_dealer_lain_query && booking_empty" type="button" class=" margin btn btn-flat btn-primary btn-sm" data-toggle="modal" data-target="#parts_sales_order">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                  </button>
                </div>
              </div>
              <?php $this->load->view('modal/parts_sales_order') ?>
              <script>
              function pilihPart(part) {
                part.kuantitas = 1;

                post = {};
                post.id_part_int = part.id_part_int;
                post.id_part = part.id_part;
                post.kelompok_part = part.kelompok_part;
                axios.post('dealer/h3_dealer_sales_order/get_part_promo', Qs.stringify(post))
                .then(function(res){
                  part.promo = res.data;
                  if(res.data.length == 1){
                    // part.selected_promo = res.data[0];
                    part.selected_promo = {};
                  }else{
                    part.selected_promo = {};
                  }

                  if(part.selected_promo.tipe_disc != '' && part.selected_promo.tipe_disc != null){
                    // part.tipe_diskon = part.selected_promo.tipe_disc;
                    // part.diskon_value = part.selected_promo.disc_value;
                    part.tipe_diskon = null;
                    part.diskon_value = null;
                  }else{
                    part.tipe_diskon = null;
                    part.diskon_value = null;
                  }

                  console.log(part);
                  form_.parts.push(part);
                })
                .catch(function(err){
                  toastr.error(err);
                })
              }
              </script>
              <?php $this->load->view('modal/rak_parts_sales_order') ?>
              <script>
                function pilih_rak_parts(rak, index){
                  form_.parts[index].id_gudang = rak.id_gudang;
                  form_.parts[index].id_rak = rak.id_rak;
                  return false;
                }
              </script>
              <?php $this->load->view('modal/promo_sales_order') ?>
              <?php $this->load->view('modal/view_promo_part') ?>
              <?php //$this->load->view('modal/view_hadiah_master_promo') ?>
              <div class="box-footer">
                <div class="col-sm-12 no-padding" v-if="mode=='insert'">
                  <button @click.prevent='<?= $form ?>' :disabled='parts_yang_kuantitas_nol.length > 0 || parts.length < 1' type="button" class="btn btn-info btn-sm btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
                <div class="col-sm-12 no-padding" v-if="mode=='edit'">
                  <button @click.prevent='<?= $form ?>' :disabled='parts_yang_kuantitas_nol.length > 0 || parts.length < 1' type="button" class="btn btn-warning btn-sm btn-flat"><i class="fa fa-save"></i> Update</button>
                </div>
                <div v-if="mode=='detail'">
                  <div class="col-sm-6 no-padding">
                    <a v-if='auth.can_update && mode == "detail" && (sales_order.status == "Open" || sales_order.status == "Canceled") && sales_order.wo_end == 0' :href="'dealer/h3_dealer_sales_order/edit?k=' + sales_order.nomor_so" class='btn btn-sm btn-warning btn-flat'>Edit</a>
                    <a v-if='auth.can_insert && mode == "detail" && sales_order.status == "Processing"' :href="'dealer/h3_dealer_picking_slip/detail?id=' + sales_order.picking_slip_id" class='btn btn-sm btn-warning btn-flat'>Picking Slip</a>
                  </div>
                  <div class="col-sm-6 no-padding text-right">
                    <a v-if='auth.can_submit && mode == "detail" && sales_order.status == "Open"' :href="'dealer/h3_dealer_sales_order/proses?id=' + sales_order.id" class='btn btn-sm btn-info btn-flat'>Proses</a>
                    <a v-if='auth.can_cancel && mode == "detail" && sales_order.status != "Canceled" && sales_order.status != "Closed"' onclick='return confirm("Apakah anda yakin ingin mengcancel sales order ini?")' :href="'dealer/h3_dealer_sales_order/cancel?k=' + sales_order.nomor_so" class='btn btn-sm btn-danger btn-flat'>Cancel</a>
                  </div>    
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
      data: {
        auth: <?= json_encode(get_user('h3_dealer_sales_order')) ?>, 
        indexPart: 0,
        selected_part_promo: [],
        selected_view_part_promo: {},
        selected_view_gift_part_promo: {},
        loading: false,
        errors: {},
        mode : '<?= $mode ?>',
        
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        sales_order: <?= json_encode($sales_order) ?>,
        parts: <?= json_encode($parts) ?>,
        <?php else: ?>
        sales_order: {
          id_customer_int: null,
          id_customer: null,
          nama_customer: null,
          no_hp: null,
          no_mesin: null,
          no_rangka: null,
          alamat: null,
          nama_pembeli: null,
          no_hp_pembeli: null,
          alamat_pembeli: null,
          booking_id_reference: null,
          uang_muka: 0,
          pembelian_dari_dealer_lain: 0,
          id_dealer_pembeli: null,
          po_id: null,
        },
        parts: [],
        <?php endif; ?>
      },
      mounted: function(){
        <?php if($this->input->get('generateByBooking')): ?>
        <?php
            $booking = $this->db
            ->select('rd.id_booking')
            ->select('rd.id_customer')
            ->select('c.id_customer_int')
            ->select('rd.uang_muka')
            ->select('c.nama_customer')
            ->select('c.alamat')
            ->select('c.no_hp')
            ->select('c.no_mesin')
            ->select('c.no_rangka')
            ->from('tr_h3_dealer_request_document as rd')
            ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
            ->where('rd.id_booking', $this->input->get('booking'))
            ->get()->row();
          ?>
        this.sales_order.booking_id_reference = '<?= $booking->id_booking ?>';
        this.sales_order.id_customer = '<?= $booking->id_customer ?>';
        this.sales_order.id_customer_int = '<?= $booking->id_customer_int ?>';
        this.sales_order.nama_customer = '<?= $booking->nama_customer ?>';
        this.sales_order.alamat = '<?= $booking->alamat ?>';
        this.sales_order.no_hp = '<?= $booking->no_hp ?>';
        this.sales_order.no_mesin = '<?= $booking->no_mesin ?>';
        this.sales_order.no_rangka = '<?= $booking->no_rangka ?>';
        this.sales_order.uang_muka = '<?= $booking->uang_muka ?>';
        this.get_parts_by_booking();
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = {};
          post = _.pick(this.sales_order, [
            'nomor_so', 'nomor_so_int',
            'id_customer', 'id_customer_int',
            'nama_pembeli','no_hp_pembeli','alamat_pembeli','booking_id_reference',
            'uang_muka','pembelian_dari_dealer_lain','id_dealer_pembeli'
          ]);
          post.total_tanpa_ppn = this.totalTanpaPPN;
          post.disc_so = this.totalTanpaPPNTanpaDiskon - this.totalTanpaPPN;

          subTotal_fn = this.subTotal;
          harga_setelah_diskon_fn = this.harga_setelah_diskon;
          post.parts = _.chain(this.parts)
          .map(function(part){
            new_part = _.pick(part, ['kuantitas', 'harga_saat_dibeli', 'id_part_int', 'id_part', 'id_gudang', 'id_rak', 'diskon_value', 'tipe_diskon']);
            new_part.id_promo = part.selected_promo.id_promo;
            new_part.ppn = <?php echo getPPN(0.1,false) ?> * subTotal_fn(part);
            new_part.harga_setelah_diskon = harga_setelah_diskon_fn(part);
            new_part.tot_harga_part = subTotal_fn(part) + new_part.ppn;
            return new_part;
          })
          .value();

          this.loading = true;
          this.errors = {};
          axios.post('dealer/h3_dealer_sales_order/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'dealer/h3_dealer_sales_order/detail?k=' + res.data.nomor_so;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              toastr.error(data.message);
              form_.errors = err.response.data.errors;
            }else{
              toastr.error(err);
            }
          })
          .then(function(){
            form_.loading = false;
          });
        },
        reset_customer: function(){
          this.sales_order.id_customer = null;
          this.sales_order.id_customer_int = null;
          this.sales_order.nama_customer = null;
          this.sales_order.no_hp = null;
          this.sales_order.no_mesin = null;
          this.sales_order.no_rangka = null;
          this.sales_order.alamat = null;
          this.sales_order.nama_pembeli = this.sales_order.nama_customer;
          this.sales_order.no_hp_pembeli = this.sales_order.no_hp;
          this.sales_order.alamat_pembeli = this.sales_order.alamat;
        },
        reset_booking: function(){
          this.sales_order.booking_id_reference = null;
          this.sales_order.uang_muka = 0;
          this.reset_customer();
          this.parts = [];
        },
        reset_purchase: function(){
          this.sales_order.po_id = null;
          this.reset_customer();
          this.reset_booking();
        },
        get_purchase_parts: function(){
          this.loading = true;
          axios.get('dealer/h3_dealer_sales_order/get_purchase_parts', {
            params: {
              id: this.sales_order.po_id
            }
          })
          .then(function(res){
            form_.parts = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_parts_by_booking: function(){
          this.loading = true;
          axios.get('dealer/h3_dealer_sales_order/get_parts_by_booking', {
            params: {
              id: this.sales_order.booking_id_reference
            }
          })
          .then(function(res){
            form_.parts = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_booking_reference: function(){
          this.loading = true;
          axios.get('dealer/h3_dealer_sales_order/get_booking_reference', {
            params: {
              id: this.sales_order.po_id
            }
          })
          .then(function(res){
            form_.sales_order.booking_id_reference = res.data.id_booking;
            if(form_.sales_order.pembelian_dari_dealer_lain == 0){
              form_.sales_order.id_customer = res.data.id_customer;
              form_.sales_order.id_customer_int = res.data.id_customer_int;
              form_.sales_order.nama_customer = res.data.nama_customer;
              form_.sales_order.alamat = res.data.alamat;
              form_.sales_order.no_hp = res.data.no_hp;
              form_.sales_order.no_mesin = res.data.no_mesin;
              form_.sales_order.no_rangka = res.data.no_rangka;
            }
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_customer: function(){
          form_.loading = true;
          axios.get('dealer/h3_dealer_sales_order/get_customer', {
            params: {
              id: form_.sales_order.po_id
            }
          })
          .then(function(res){
            // form_.sales_order.id_customer = res.data.id_customer;
            // form_.sales_order.id_customer_int = res.data.id_customer_int;
            form_.sales_order.nama_pembeli = res.data.nama_dealer;
            form_.sales_order.no_hp_pembeli = res.data.no_telp;
            form_.sales_order.alamat_pembeli = res.data.alamat;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        updateIndexPart: function(index){
          this.indexPart = index;
          $('#rak_parts_sales_order').modal('show');
          datatable_rak_parts_sales_order.draw();
        },
        pilih_promo_modal: function(index){
          if(this.mode == 'detail') return; 
          this.indexPart = index;
          this.selected_part_promo = this.parts[index].promo;
          $('#promo_sales_order').modal('show');
        },
        view_promo_modal: function(index){
          this.indexPart = index;
          this.selected_view_part_promo = this.parts[index].selected_promo;
          if(this.selected_view_part_promo.hadiah_per_item == 1){
            part = this.parts[this.indexPart];
            promo_items = _.find(this.parts[index].selected_promo.promo_items, ['id_part', part.id_part]);
            
            if(promo_items != null){
              this.selected_view_gift_part_promo = promo_items.gifts;
            }else{
              this.selected_view_gift_part_promo = [];
            }
          }
          $('#view_promo_part').modal('show');
        },
        pilih_promo: function(index){
          this.parts[this.indexPart].selected_promo = this.parts[this.indexPart].promo[index];
          this.parts[this.indexPart].tipe_diskon = this.parts[this.indexPart].promo[index].tipe_disc;
          this.parts[this.indexPart].diskon_value = this.parts[this.indexPart].promo[index].disc_value;
          $('#promo_sales_order').modal('hide');
        },
        hapus_promo: function(){
          this.parts[this.indexPart].selected_promo = {};
        },
        clear_purchase_order: function(){
          this.booking_reference = {};
          this.purchase_order = {};
          this.parts = [];
          this.customer = {};
        },
        harga_setelah_diskon: function(part){
          harga_setelah_diskon = Number(part.harga_saat_dibeli);
          if(part.tipe_diskon == 'Percentage'){
            potongan_harga = (part.diskon_value/100) * harga_setelah_diskon;
            harga_setelah_diskon -= potongan_harga;
          }

          if(part.tipe_diskon == 'Value'){
            harga_setelah_diskon -= Number(part.diskon_value);
          }
          return harga_setelah_diskon;
        },
        subTotal: function(part){
          harga_setelah_diskon = this.harga_setelah_diskon(part);

          kuantitas = Number(part.kuantitas);
          if(part.tipe_diskon == 'FoC'){
            kuantitas -= Number(part.diskon_value);
          }

          return (kuantitas * harga_setelah_diskon);
        },
        subTotalTanpaDiskon: function(part){
          return parseInt(part.harga_saat_dibeli) * parseInt(part.kuantitas);
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        },
        parse_promo: function (part_index) {
          part = this.parts[part_index];
          promo = part.selected_promo;
          if(_.get(promo, 'tipe_promo') == 'Bertingkat'){
            for (var index = 0; index < promo.promo_items.length; index++) {
              const element = promo.promo_items[index];
              if(part.id_part == element.id_part && Number(part.kuantitas) >= Number(element.qty)){
                this.parts[part_index].tipe_diskon = element.tipe_disc;
                this.parts[part_index].diskon_value = element.disc_value;
                return;
              }else{
                this.parts[part_index].tipe_diskon = '';
                this.parts[part_index].diskon_value = '';
              }
            }
          }
          else if(_.get(promo, 'tipe_promo') == 'Standar'){
            for (var index = 0; index < promo.promo_items.length; index++) {
              const element = promo.promo_items[index];
              if(part.id_part == element.id_part){
                this.parts[part_index].tipe_diskon = element.tipe_disc;
                this.parts[part_index].diskon_value = element.disc_value;
                return;
              }
            }
          }else if(_.get(promo, 'tipe_promo') == 'Paket'){
            if(this.totalTanpaPPN >= promo.minimal_pembelian){
              id_part = this.parts[part_index].id_part;
              part_promo_item = _.find(promo.promo_items, ['id_part', id_part]);
              this.parts[part_index].tipe_diskon = part_promo_item.tipe_disc;
              this.parts[part_index].diskon_value = part_promo_item.disc_value;
            }else{
              this.parts[part_index].tipe_diskon = '';
              this.parts[part_index].diskon_value = '';
            }
          }else if(_.get(promo, 'tipe_promo') == 'Bundling'){
            count = 0;
            for (let index_promo = 0; index_promo < promo.promo_items.length; index_promo++) {
              const item = promo.promo_items[index_promo];
              for (let index_part = 0; index_part < this.parts.length; index_part++) {
                const part = this.parts[index_part];
                
                if(item.id_part == part.id_part && part.kuantitas >= item.qty){
                  count += 1;
                }
              }
            }

            if(count == promo.promo_items.length){
              this.parts[part_index].tipe_diskon = promo.tipe_diskon_master;
              this.parts[part_index].diskon_value = promo.diskon_value_master;
            }else{
              this.parts[part_index].tipe_diskon = '';
              this.parts[part_index].diskon_value = '';
            }
          }
        },
        get_max_kuantitas_order: function(part){
          if(parseInt(part.stock) > parseInt(part.kuantitas_boleh_dibuatkan_so)){
            return parseInt(part.kuantitas_boleh_dibuatkan_so);
          }else{
            return parseInt(part.stock);
          }
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        gift_promo: function(){
          gift_promo = [];
          used_promo = [];
          for (var index_part = 0; index_part < this.parts.length; index_part++) {
            var part = this.parts[index_part];
            var part_promo = part.selected_promo;
            if(part_promo.hadiah_per_item == 0 && used_promo.indexOf(part_promo.id_promo) < 0){
              pesan = 'Anda mendapatkan promo ' + part_promo.nama + ' berhadiah ';
              hadiah = '';
              for (var index_gift = 0; index_gift < part_promo.gifts.length; index_gift++) {
                gift = part_promo.gifts[index_gift];
                hadiah += ', ' + gift.qty_hadiah + ' ' + gift.nama_hadiah;
              }
              pesan += hadiah.substring(2);
              gift_promo.push(pesan);
              used_promo.push(part_promo.id_promo);
            }
          }
          return gift_promo;
        },
        customer_empty: function(){
          return this.sales_order.id_customer == null || this.sales_order.id_customer == '';
        },
        booking_empty: function(){
          return this.sales_order.booking_id_reference == null || this.sales_order.booking_id_reference == '';
        },
        purchase_empty: function(){
          return this.sales_order.po_id == null || this.sales_order.po_id == '';
        },
        pembelian_dari_dealer_lain_query: function(){
          return <?= $this->input->get('po_dealer_lain') != null ? 'true' : 'false' ?>;
        },
        pembelian_dari_dealer_lain: function(){
          return this.sales_order.pembelian_dari_dealer_lain == 1;
        },
        parts_yang_kuantitas_nol: function(){
          return _.chain(this.parts)
          .filter(function(p){
            return p.kuantitas < 1;
          })
          .map(function(part){
            return part.id_part;
          })
          .value();
        },
        totalTanpaPPN: function(){
          total = 0;
          for(part of this.parts){
            total += this.subTotal(part);
          }
          return total;
        },
        totalTanpaPPNTanpaDiskon: function(){
          total = 0;
          for(part of this.parts){
            total += this.subTotalTanpaDiskon(part);
          }
          return total;
        },
        totalPPN: function(){
          return (10/100) * this.totalTanpaPPN;
        },
        totalDenganPPN: function (){
          return this.totalTanpaPPN + this.totalPPN;
        },
      },
      watch: {
        parts: {
          deep: true,
          handler: function(){
            // parts_sales_order_datatable.draw();
          }
        },
        'sales_order.id_customer': {
          deep: true, 
          handler: function(){
            if(!this.pembelian_dari_dealer_lain){
              this.sales_order.nama_pembeli = this.sales_order.nama_customer;
              this.sales_order.no_hp_pembeli = this.sales_order.no_hp;
              this.sales_order.alamat_pembeli = this.sales_order.alamat;
            }
          }
        },
      }
  });

  <?php if($this->input->get('po_dealer_lain') != null): ?>
  <?php
    $purchase_order = $this->db
    ->select('po.po_id')
    ->select('po.id_dealer')
    ->from('tr_h3_dealer_purchase_order as po')
    ->where('po.po_id', $this->input->get('po_id'))
    ->limit(1)
    ->get()->row();
  ?>
  $(document).ready(function(){
    form_.sales_order.po_id = '<?= $purchase_order->po_id ?>';
    form_.sales_order.pembelian_dari_dealer_lain = 1;
    form_.sales_order.id_dealer_pembeli = <?= $purchase_order->id_dealer ?>;
    form_.get_purchase_parts();
    form_.get_booking_reference();
    form_.get_customer();

    
  });
  <?php endif; ?>
  setInterval(function(){
    for(i = 0; i < form_.parts.length; i++){
        form_.parse_promo(i);
    }
  }, 500);
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <?php if(can_access('h3_dealer_sales_order', 'can_insert')): ?>
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="col-sm-1 col-sm-offset-9">
            <div class="form-group">
              <label for="" class="control-label">Status</label>
              <select id='filter_status' class='form-control'>
                <option value="">All</option>
                <option value="Open">Open</option>
                <option value="Processing">Processing</option>
                <option value="Closed">Closed</option>
                <option value="Canceled">Canceled</option>
              </select>
            </div>
          </div>
          <script>
            $(document).ready(function(){
              $('#filter_status').on('change', function(e){
                sales_order.draw();
              });
            });
          </script>
          <div class="col-sm-2">
            <div class="form-group">
              <label for="" class="control-label">Filter Tanggal</label>
              <input type="text" class="form-control pull-right" id="filter_sales_date" readonly>
              <input type="hidden" id="filter_sales_date_start">
              <input type="hidden" id="filter_sales_date_end">
            </div>
          </div>
          <script>
            $(document).ready(function(){
              $('#filter_sales_date').daterangepicker({
                autoUpdateInput: false,
              })
              .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                $('#filter_sales_date_start').val(picker.startDate.format('YYYY-MM-DD'));
                $('#filter_sales_date_end').val(picker.endDate.format('YYYY-MM-DD'));
                sales_order.draw();
              }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#filter_sales_date_start').val('');
                $('#filter_sales_date_end').val('');
                sales_order.draw();
              });
            });
          </script>
        </div>
        <table id="sales_order" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nomor SO</th>
              <th>Tanggal</th>
              <th>Customer</th>
              <th>No. HP Customer</th>
              <th>Booking Reference</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
        $(document).ready(function() {
              sales_order = $('#sales_order').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                      url: "<?= base_url('api/dealer/sales_order') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(data) {
                        data.filter_status_sales_order = $('#filter_status').val();

                        start_date = $('#filter_sales_date_start').val();
                        end_date = $('#filter_sales_date_end').val();
                        if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                            data.filter_sales_date = true;
                            data.start_date = start_date;
                            data.end_date = end_date;
                        }
                      }
                  },
                  createdRow: function(row, data, index) {
                      $('td', row).addClass('align-middle');
                  },
                  columns: [
                    { data: null, width: '2%', orderable: false },
                    { data: 'nomor_so' },
                    { data: 'tanggal_so' },
                    { data: 'nama_pembeli' },
                    { data: 'no_hp_pembeli' },
                    { data: 'booking_reference' },
                    { data: 'status' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                  ],
              });

              sales_order.on('draw.dt', function() {
                  var info = sales_order.page.info();
                  sales_order.column(0, {
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