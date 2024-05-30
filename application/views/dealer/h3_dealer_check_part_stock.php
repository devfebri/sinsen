<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
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
    <div id='form_' class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-7">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <h4>
                  <b>Check Part Stock</b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label">Kode Part</label>
                  <div class="col-sm-6 no-padding">
                    <div class="input-group">
                      <input v-model='id_part' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_dealer_parts_check_part_stock'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label">Nama Part</label>
                  <div class="col-sm-6 no-padding">
                    <input v-model='nama_part' type="text" class="form-control" disabled>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_dealer_parts_check_part_stock') ?>
                <script>
                  function pilih_part_check_part_stock(data) {
                    form_.id_part = data.id_part;
                    form_.nama_part = data.nama_part;
                    form_.kelompok_part = data.kelompok_part;
                    form_.kuantitas = data.kuantitas;
                    form_.harga_dealer_user = data.harga_dealer_user;
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label">Kuantitas yang akan dibeli</label>
                  <div class="col-sm-6 no-padding">
                    <vue-numeric class="form-control" thousand-separator="." v-model="kuantitas"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-6 no-padding">
                    <div class="input-group">
                      <input v-model='deskripsi_ahm' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button type='button' data-toggle='modal' data-target='#h3_dealer_tipe_kendaraan_check_part_stock' class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_dealer_tipe_kendaraan_check_part_stock'); ?>
                <script>
                  function pilih_tipe_kendaraan(data){
                    form_.id_tipe_kendaraan = data.id_tipe_kendaraan;
                    form_.deskripsi_ahm = data.deskripsi_ahm;
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label no-padding-top">Re-numbering</label>
                  <div class="col-sm-6 no-padding">
                    <input type="checkbox" v-model='renumbering'>
                  </div>
                </div>
                <div v-if='renumbering' class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label no-padding-top">Claim</label>
                  <div class="col-sm-6 no-padding">
                    <input :disabled='non_claim' type="checkbox" v-model='claim'>
                  </div>
                </div>
                <div v-if='renumbering' class="form-group">
                  <label for="inputEmail3" class="col-sm-5 control-label no-padding-top">Non Claim</label>
                  <div class="col-sm-6 no-padding">
                    <input :disabled='claim' type="checkbox" v-model='non_claim'>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-2 col-sm-offset-5 no-padding">
                    <button @click.prevent='check' :disabled='!allowed_check' class="btn btn-sm btn-flat btn-primary" type='button'>Check</button>
                  </div>
                </div>
                <!-- Reason demand -->
                <button v-if='kuantitas != 0' type="button" class="margin btn btn-flat btn-danger btn-sm" data-toggle="modal" data-target="#modal-reason">Tidak bersedia pesan hotline</button>
                <a v-if='kuantitas != 0' href="dealer/h3_dealer_sales_order/add" class="btn btn-flat btn-success btn-sm margin">Create Sales Order</a>
                <a v-if='kuantitas != 0' href="dealer/h3_dealer_request_document/add" class="btn btn-flat btn-info btn-sm margin">Create Request Document</a>
                <div id="modal-reason" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Alasan pelanggan tidak bersedia hotline</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <textarea class="form-control" name="" cols="30" rows="10" v-model="note_field"></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-12">
                            <button @click.prevent="submit_reasons" type="submit" class="pull-right btn btn-flat btn-info btn-sm">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-5">
            <div v-if='!_.isEqual(demand_part, {})' class="box-body">
              <h4 class='text-bold'>History Demand</h4>
              <table class="table table-condensed table-striped">
                  <tr class='bg-blue-gradient'>
                    <td>Part Number</td>
                    <td>Part Deskripsi</td>
                    <td>Qty Parts (Pcs)</td>
                    <td>Lost of Sales Amount (IDR)</td>
                  </tr>
                  <tr>
                    <td>{{ id_part }}</td>
                    <td>{{ nama_part }}</td>
                    <td>{{ demand_part.frekuensi }}</td>
                    <td>{{ demand_part.lost }}</td>
                  </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
            <div v-if="promoProgram.length > 0">
                  <table class="table table-responsive table-bordered table-condensed">
                    <tr class='bg-aqua-active'>
                      <td colspan='10'>Program Promo</td>
                    </tr>
                    <tr class='bg-gray-active'>
                      <td>Part Number</td>
                      <td>Part Deskripsi</td>
                      <td>HET</td>
                      <td>ID Promo</td>
                      <td>Nama Promo</td>
                      <td>Mekanisme Promo</td>
                      <td>Start Date</td>
                      <td>End Date</td>
                      <td>Promo Value</td>
                      <td>Gimmick</td>
                    </tr>
                    <tr v-if='promoProgram.length > 0' v-for="each in promoProgram">
                      <td>{{ each.id_part }}</td>
                      <td>{{ each.nama_part }}</td>
                      <td>{{ each.het }}</td>
                      <td>{{ each.id_promo }}</td>
                      <td>{{ each.nama }}</td>
                      <td>{{ each.mekanisme_promo }}</td>
                      <td>{{ each.start_date }}</td>
                      <td>{{ each.end_date }}</td>
                      <td>{{ each.promo_value }}</td>
                      <td>{{ each.gifts }}</td>
                    </tr>
                  </table>
                </div>
                <div v-if="stockInDealer.length > 0">
                  <table class="table table-responsive table-bordered table-condensed">
                    <tr class='bg-aqua-active'>
                      <td colspan="6"><?= $this->dealer->getCurrentUserDealer()->nama_dealer ?></td>
                    </tr>
                    <tr class='bg-gray-active'>
                      <td width="10%">Kode Part</td>
                      <td>Nama Part</td>
                      <td width="15%">HET</td>
                      <td width="15%">Gudang</td>
                      <td width="15%">Rak</td>
                      <td width="15%">Stock Available</td>
                    </tr>
                    <tr v-for='e in stockInDealer'>
                      <td>{{ e.id_part }}</td>
                      <td>{{ e.nama_part }}</td>
                      <td><vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="e.harga_saat_dibeli" /></td>
                      <td>{{ e.id_gudang }}</td>
                      <td>{{ e.id_rak }}</td>
                      <td><vue-numeric :read-only="true" thousand-separator="." v-model="e.stock" /></td>
                    </tr>
                  </table>
                </div>
                <div v-if="stockDealerTerdekat.length > 0">
                  <table class="table table-responsive table-bordered table-condensed">
                    <tr>
                      <td class='bg-aqua-active' colspan='6'>Dealer Terdekat</td>
                    </tr>
                    <tr class='bg-gray-active'>
                      <td width="10%">Kode Part</td>
                      <td>Nama Part</td>
                      <td width="30%">Dealer</td>
                      <td width="15%">HET</td>
                      <td width="15%">Status</td>
                    </tr>
                    <tr v-for="each in stockDealerTerdekat">
                      <td>{{ each.id_part }}</td>
                      <td>{{ each.nama_part }}</td>
                      <td>{{ each.nama_dealer }}</td>
                      <td>{{ each.harga_saat_dibeli }}</td>
                      <td>{{ each.status }}</td>
                    </tr>
                  </table>
                </div>
                <div v-if="stockMD.id_part != null">
                  <table class="table table-responsive table-bordered table-condensed">
                    <tr class='bg-aqua-active'>
                      <td colspan="7">Main Dealer</td>
                    </tr>
                    <tr class='bg-gray-active'>
                      <td width="10%">Kode Part</td>
                      <td>Nama Part</td>
                      <td width="15%">HET</td>
                      <td width="15%">Stock Available</td>
                      <td width="15%">Status</td>
                      <td>ETA Tercepat</td>
                      <td>ETA Terlama</td>
                    </tr>
                    <tr>
                      <td>{{ stockMD.id_part }}</td>
                      <td>{{ stockMD.nama_part }}</td>
                      <td><vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="stockMD.harga_saat_dibeli" /></td>
                      <td>{{ stockMD.stock }}</td>
                      <td>{{ stockMD.status }}</td>
                      <td>{{ stockMD.eta_tercepat }} Hari</td>
                      <td>{{ stockMD.eta_terlama }} Hari</td>
                    </tr>
                  </table>
                </div>
            </div>
        </div>
      </div>
      <div v-if='fetching' class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
    </div>
    <script>
      var form_ = new Vue({
        el: '#form_',
        data: {
          kosong: '',
          id_part: '',
          nama_part: '',
          kelompok_part: '',
          kuantitas: 0,
          harga_dealer_user: 0,
          stock: 0,
          id_tipe_kendaraan: '',
          deskripsi_ahm: '',
          renumbering: false,
          claim: false,
          non_claim: false,

          search_query: '',
          note_field: '',
          demand_part: {},
          stockInDealer: [],
          stockDealerTerdekat: [],
          stockMD: {},
          promoProgram: [],
          
          fetching: false,
        },
        methods: {
          submit_reasons: function() {
            postData = {};
            postData.search_field = this.search_query;
            postData.search_result = this.nama_part + ", " + this.id_part;
            postData.sisa_stock = this.stock;
            postData.id_part = this.id_part;
            postData.harga_satuan = this.harga_dealer_user;
            postData.qty = this.kuantitas;
            postData.note_field = this.note_field;
            axios.post('api/record_reasons_and_parts_demand/insert', Qs.stringify(postData))
              .then(function(response) {
                data = response.data;
                $('#modal-reason').modal('hide');
                form_.id_part = '';
                form_.nama_part = '';
                form_.kelompok_part = '';
                form_.kuantitas = 0;
                form_.harga_dealer_user = 0;
                form_.stock = 0;
                form_.stockInDealer = {};
                form_.stockDealerTerdekat = [];
                form_.promoProgram =  [];
                form_.demand_part =  {};
                form_.stockMD = {};
                form_.search_query = '';
                form_.note_field = '';
              })
              .catch(function(error) {
                toastr.error(error);
              });
          },
          check: function(){
            this.get_demand_part();
            this.getStockInDealer();
            this.getPromoProgram();
            this.getStockDealerTerdekat();
            this.getStockMD();
          },
          get_demand_part: function(){
            form_.fetching = true;
            axios.get('api/demand_part', {
                params: {
                  id_part: this.id_part,
                }
              })
              .then(function(response) {
                data = response.data;
                form_.demand_part = data;
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.fetching = false; });
          },
          getStockInDealer: function(){
            form_.fetching = true;
            axios.get('api/stockInDealer', {
                params: {
                  id_part: this.id_part,
                  qty: this.kuantitas,
                }
              })
              .then(function(response) {
                data = response.data;
                form_.stockInDealer = data;
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.fetching = false; });
          },
          getPromoProgram: function(){
            form_.fetching = true;
            axios.get('api/promoProgram', {
                params: {
                  id_part: this.id_part,
                  kelompok_part: this.kelompok_part,
                  qty: this.kuantitas,
                }
              })
              .then(function(response) {
                data = response.data;
                if (data != null) {
                  form_.promoProgram = data;
                }
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.fetching = false; });
          },
          getStockDealerTerdekat: function(){
            form_.fetching = true;
            axios.get('api/stockDealerTerdekat', {
                params: {
                  id_part: this.id_part,
                  qty: this.kuantitas,
                }
              })
              .then(function(response) {
                form_.stockDealerTerdekat = response.data;
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.fetching = false; });
          },
          getStockMD: function(){
            form_.fetching = true;
            axios.get('api/stockMD', {
                params: {
                  id_part: this.id_part,
                  qty: this.kuantitas,
                  claim: this.renumbering,
                  tipe_claim: this.tipe_claim,
                }
              })
              .then(function(response) {
                form_.stockMD = response.data;
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.fetching = false; });
          }
        },
        computed: {
          tipe_claim: function(){
            if(this.claim){
              return 'renumbering_claim';
            }else{
              return 'renumbering_non_claim';
            }
          },
          allowed_check: function(){
            return this.kuantitas != 0;
          },
          total_demand: function(){
            total = 0;
            for (let index = 0; index < this.demand_part.length; index++) {
              const element = this.demand_part[index];
              total += Number(element.qty) * Number(element.harga_satuan);
            }
            return total;
          },
        },
        watch: {
          id_part: function(){
            h3_dealer_tipe_kendaraan_check_part_stock_datatable.draw();
          },
          id_tipe_kendaraan: function(){
            h3_dealer_parts_check_part_stock_datatable.draw();
          }
        }
      });
    </script>
  </section>
</div>