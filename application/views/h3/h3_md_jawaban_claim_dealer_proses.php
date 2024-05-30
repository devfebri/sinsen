<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<div id="app" class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">
        <a href="h3/<?= $isi ?>">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
        </a>  
      </h3>
    </div><!-- /.box-header -->
    <div v-if="loading" class="overlay">
      <i class="fa fa-refresh fa-spin text-light-blue"></i>
    </div>
    <div class="box-body">
      <?php $this->load->view('template/session_message.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">Nama Customer</label>
                <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                  <div class="input-group">
                    <input type="text" class="form-control" readonly v-model='nama_dealer'>
                    <div class="input-group-btn">
                      <button v-if='customer_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_proses_jawaban_claim_dealer'><i class="fa fa-search"></i></button>
                      <button v-if='!customer_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_customer'><i class="fa fa-trash-o"></i></button>
                    </div>
                  </div>
                  <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small> 
                </div>
                <?php $this->load->view('modal/h3_md_dealer_proses_jawaban_claim_dealer') ?>
                <script>
                  function pilih_dealer_proses_jawaban_claim_dealer(data){
                    app.id_dealer = data.id_dealer;
                    app.nama_dealer = data.nama_dealer;
                  }
                </script>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Jenis Penggantian</label>
                <div v-bind:class="{ 'has-error': error_exist('jenis_penggantian') }" class="col-sm-4">
                  <select class="form-control" v-model='jenis_penggantian'>
                    <option value="">-Pilih-</option>
                    <option value="Ganti Barang">Ganti Barang</option>
                    <option value="Ganti Uang">Ganti Uang</option>
                    <option value="Tolak">Tolak</option>
                  </select>
                  <small v-if="error_exist('jenis_penggantian')" class="form-text text-danger">{{ get_error('jenis_penggantian') }}</small>   
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <table v-if='jenis_penggantian == "Ganti Barang"' id="table" class="table table-responsive">
                    <thead>
                      <tr>                                      
                        <th class="align-top" width='3%'>No.</th>
                        <th class='align-top'>No. Claim</th>              
                        <th class='align-top'>Tgl. Claim</th>              
                        <th class='align-top'>Kode Part</th>              
                        <th class='align-top'>Nama Part</th>              
                        <th class='align-top text-right' width='10%'>Qty Pergantian ke AHASS</th>              
                        <th class='align-top text-right'>HET</th>              
                        <th class='align-top text-right'>Amount</th>              
                      </tr>
                    </thead>
                    <tbody>            
                      <tr v-if="parts.length > 0" v-for="(part, index) in parts">
                        <td class="align-middle">{{ index + 1 }}</td>
                        <td class="align-middle">{{ part.id_claim_dealer }}</td>                       
                        <td class="align-middle">{{ part.tgl_claim_dealer }}</td>                       
                        <td class="align-middle">{{ part.id_part }}</td>                       
                        <td class="align-middle">{{ part.nama_part }}</td>            
                        <td class="align-middle text-right">{{ part.qty }}</td>            
                        <td class="align-middle text-right">
                          <vue-numeric read-only v-model='part.het' currency='Rp' separator='.'></vue-numeric>
                        </td>            
                        <td class="align-middle text-right">
                          <vue-numeric read-only :value='amount(part.qty, part.het)' currency='Rp' separator='.'></vue-numeric>
                        </td>            
                      </tr>
                      <tr v-if="parts.length > 0">
                        <td class="text-center" colspan="6"></td>
                        <td class="text-right">
                          <vue-numeric read-only v-model='total_het' currency='Rp' separator='.'></vue-numeric>
                        </td>
                        <td class="text-right">
                          <vue-numeric read-only v-model='total_amount' currency='Rp' separator='.'></vue-numeric>
                        </td>
                      </tr>
                      <tr v-if="parts.length < 1">
                        <td class="text-center" colspan="9">Tidak ada data</td>
                      </tr>
                    </tbody>                    
                  </table>
                  <table v-if='jenis_penggantian == "Ganti Uang"' id="table" class="table table-responsive">
                    <thead>
                      <tr>                                      
                        <th class="align-top" width='3%'>No.</th>
                        <th class='align-top'>No. Claim</th>              
                        <th class='align-top'>Tgl. Claim</th>              
                        <th class='align-top'>Kode Part</th>              
                        <th class='align-top'>Nama Part</th>              
                        <th class='align-top text-right' width='10%'>Qty Pergantian ke AHASS</th>              
                        <th class='align-top text-right'>Amount</th>              
                      </tr>
                    </thead>
                    <tbody>            
                      <tr v-if="parts.length > 0" v-for="(part, index) in parts">
                        <td class="align-middle">{{ index + 1 }}</td>
                        <td class="align-middle">{{ part.id_claim_dealer }}</td>                       
                        <td class="align-middle">{{ part.tgl_claim_dealer }}</td>                       
                        <td class="align-middle">{{ part.id_part }}</td>                       
                        <td class="align-middle">{{ part.nama_part }}</td>            
                        <td class="align-middle text-right">{{ part.qty }}</td>            
                        <td class="align-middle text-right">
                          <vue-numeric read-only :value='amount(part.qty, part.amount)' currency='Rp' separator='.'></vue-numeric>
                        </td>            
                      </tr>
                      <tr v-if="parts.length > 0">
                        <td class="text-center" colspan="6"></td>
                        <td class="text-right">
                          <vue-numeric read-only v-model='total_amount' currency='Rp' separator='.'></vue-numeric>
                        </td>
                      </tr>
                      <tr v-if="parts.length < 1">
                        <td class="text-center" colspan="8">Tidak ada data</td>
                      </tr>
                    </tbody>                    
                  </table>
                  <table v-if='jenis_penggantian == "Tolak"' id="table" class="table table-responsive">
                    <thead>
                      <tr>                                      
                        <th class="align-top" width='3%'>No.</th>
                        <th class='align-top'>No. Claim</th>              
                        <th class='align-top'>Tgl. Claim</th>              
                        <th class='align-top'>Kode Part</th>              
                        <th class='align-top'>Nama Part</th>              
                        <th class='align-top text-right' width='10%'>Qty yang ditolak</th>              
                      </tr>
                    </thead>
                    <tbody>            
                      <tr v-if="parts.length > 0" v-for="(part, index) in parts">
                        <td class="align-middle">{{ index + 1 }}</td>
                        <td class="align-middle">{{ part.id_claim_dealer }}</td>                       
                        <td class="align-middle">{{ part.tgl_claim_dealer }}</td>                       
                        <td class="align-middle">{{ part.id_part }}</td>                       
                        <td class="align-middle">{{ part.nama_part }}</td>            
                        <td class="align-middle text-right">{{ part.qty }}</td>         
                      </tr>
                      <tr v-if="parts.length < 1">
                        <td class="text-center" colspan="8">Tidak ada data</td>
                      </tr>
                    </tbody>                    
                  </table>
                </div>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
              <div class="col-sm-12 no-padding">
                <button :disabled='loading' class="btn btn-sm btn-flat btn-primary" @click.prevent="proses">Proses</button>
              </div>
            </div><!-- /.box-footer -->
          </form>
        </div>
      </div>
    </div>
  </div><!-- /.box -->
  <script>
    var app = new Vue({
        el: '#app',
        data: {
          errors: {},
          loading: false,
          mode: '<?= $mode ?>',
          jawaban_claim_dealer: <?= json_encode($jawaban_claim_dealer) ?>,
          id_dealer: '',
          nama_dealer: '',
          jenis_penggantian: '',
          parts: [],
        },
        methods: {
          get_parts: function(){
            this.parts = [];
            this.loading = true;

            params = {};
            params.id_jawaban_claim_dealer = this.jawaban_claim_dealer.id_jawaban_claim_dealer;
            params.id_dealer = this.id_dealer;
            params.jenis_penggantian = this.jenis_penggantian;

            axios.get('h3/h3_md_jawaban_claim_dealer/get_parts', {
              params: params
            })
            .then(function(res){
              app.parts = res.data;
            })
            .catch(function(err){
              toast.error(err);
            })
            .then(function(){
              app.loading = false;
            });
          },
          proses: function(){
            if(this.parts.length < 1) return;

            post = {};
            post.id_jawaban_claim_dealer = this.jawaban_claim_dealer.id_jawaban_claim_dealer;
            post.id_dealer = this.id_dealer;
            post.jenis_penggantian = this.jenis_penggantian;
            post.total_amount = this.total_amount;
            post.parts = _.chain(this.parts)
            .map(function(part){
              return _.pick(part, [
                'id_claim_dealer', 'id_part', 'id_kategori_claim_c3', 'no_faktur', 'qty'
              ]);
            })
            .value();

            this.loading = true;
            axios.post('h3/h3_md_jawaban_claim_dealer/save_proses', Qs.stringify(post))
            .then(function(res){
              toastr.success('Proses Berhasil');
              app.parts = [];
              app.reset_customer();
              app.jenis_penggantian = '';
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                app.errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(err);
              }
            })
            .then(function(){
              app.loading = false;
              h3_md_dealer_proses_jawaban_claim_dealer_datatable.draw();
            });
          },
          amount: function(kuantitas, amount){
            return parseFloat(kuantitas) * parseFloat(amount);
          },
          reset_customer: function(){
            this.id_dealer = '';
            this.nama_dealer = '';
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        },
        watch: {
          id_dealer: function(){
            this.get_parts();
          },
          jenis_penggantian: function(){
            this.get_parts();
          },
        },
        computed: {
          customer_empty: function(){
            return this.id_dealer == '' || this.id_dealer == null;
          },
          total_het: function(){
            return _.chain(this.parts)
            .sumBy(function(data){
              return parseFloat(data.het);
            })
            .value();
          },
          total_amount: function(){
            amount_fn = this.amount;
            return _.chain(this.parts)
            .sumBy(function(data){
              if(app.jenis_penggantian == 'Ganti Barang'){
                return amount_fn(data.qty, data.het);
              }

              if(app.jenis_penggantian == 'Ganti Uang'){
                return amount_fn(data.qty, data.amount);
              }
            })
            .value();
          }
        }
      });
  </script>