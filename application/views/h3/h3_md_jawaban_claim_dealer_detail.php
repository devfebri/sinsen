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
                <label class="col-sm-2 control-label">No Claim Part AHASS</label>
                <div v-bind:class="{ 'has-error': error_exist('id_claim_part_ahass') }" class="col-sm-4">
                  <input type="text" class="form-control" readonly data-toggle='modal' data-target='#h3_md_claim_part_ahass_jawaban_claim_dealer' v-model='jawaban_claim_dealer.id_claim_part_ahass'>  
                  <small v-if="error_exist('id_claim_part_ahass')" class="form-text text-danger">{{ get_error('id_claim_part_ahass') }}</small> 
                </div>
                <?php $this->load->view('modal/h3_md_claim_part_ahass_jawaban_claim_dealer') ?>
                <script>
                  function pilih_claim_part_ahass(data){
                    app.jawaban_claim_dealer.id_claim_part_ahass = data.id_claim_part_ahass;
                    app.jawaban_claim_dealer.tgl_claim_part_ahass = data.created_at;
                  }
                </script>
                <label class="col-sm-2 control-label">No Surat Jawaban AHM</label>
                <div v-bind:class="{ 'has-error': error_exist('no_surat_jalan_ahm') }" class="col-sm-4">
                  <input readonly type="text" class="form-control" v-model='jawaban_claim_dealer.no_surat_jalan_ahm'>  
                  <small v-if="error_exist('no_surat_jalan_ahm')" class="form-text text-danger">{{ get_error('no_surat_jalan_ahm') }}</small>   
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Tgl Claim Part AHASS</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" readonly v-model='jawaban_claim_dealer.created_at'>  
                </div>
                <div v-if='mode != "insert"'>
                  <label class="col-sm-2 control-label">No Jawaban claim</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_jawaban_claim_dealer') }" class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model='jawaban_claim_dealer.id_jawaban_claim_dealer'>  
                    <small v-if="error_exist('id_jawaban_claim_dealer')" class="form-text text-danger">{{ get_error('id_jawaban_claim_dealer') }}</small>   
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <table id="table" class="table table-responsive">
                    <thead>
                      <tr>                                      
                        <th class="align-top" width='3%'>No.</th>
                        <th class='align-top'>Tanggal Terima Part</th>              
                        <th class='align-top'>Nama Customer</th>              
                        <th class='align-top text-center'>Kode Part</th>              
                        <th class='align-top' width="10%">Nama Part</th>
                        <th class='align-top text-center'>Qty Claim Dealer</th>              
                        <th class='align-top' width="10%">Qty Pergantian ke AHASS</th>
                        <th class='align-top text-center'>Jenis Pergantian</th>              
                        <th class='align-top' width="10%">Status</th>
                      </tr>
                    </thead>
                    <tbody>            
                      <tr v-for="(claim_dealer_part, index) in claim_dealer_parts">
                        <td class="align-middle">{{ index + 1 }}.</td>
                        <td class="align-middle">{{ claim_dealer_part.tanggal_terima_part }}</td>                       
                        <td class="align-middle">{{ claim_dealer_part.nama_dealer }}</td>            
                        <td class="align-middle">{{ claim_dealer_part.id_part }}</td>            
                        <td class="align-middle">{{ claim_dealer_part.nama_part }}</td>            
                        <td class="align-middle">
                          <vue-numeric :read-only='true' class="form-control" v-model='claim_dealer_part.qty_part_dikirim_ke_md' separator='.'></vue-numeric>
                        </td>      
                        <td class="align-middle">
                          <vue-numeric :read-only='true' class="form-control" v-model='claim_dealer_part.qty_pergantian' separator='.'></vue-numeric>
                        </td>   
                        <td class="align-middle">{{ claim_dealer_part.tipe_pergantian }}</td>  
                        <td class="align-middle">{{ claim_dealer_part.status }}</td>  
                      </tr>
                      <tr v-if="claim_dealer_parts.length < 1">
                        <td class="text-center" colspan="9">Belum ada part</td>
                      </tr>
                    </tbody>                    
                  </table>
                </div>
              </div>                                                                                                                                
            </div><!-- /.box-body -->
            <div class="box-footer">
              <div class="col-sm-12 no-padding">
                <a v-if="mode == 'detail' && jawaban_claim_dealer.status != 'Closed' && parts_belum_proses.length > 0" :href="'h3/h3_md_jawaban_claim_dealer/proses?id_jawaban_claim_dealer=' + jawaban_claim_dealer.id_jawaban_claim_dealer" class="btn btn-flat btn-sm btn-primary">Proses</a>
                <button v-if="mode == 'detail' && jawaban_claim_dealer.status == 'Processed'" class="btn btn-sm btn-flat btn-success" @click.prevent="close">Close</button>
              </div>
            </div><!-- /.box-footer -->
          </form>
        </div>
      </div>
    </div>
  </div><!-- /.box -->
  <script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
  <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
  <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
  <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
  <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
  <script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
  <script>
    Vue.use(VueNumeric.default);
    var app = new Vue({
        el: '#app',
        data: {
          errors: {},
          loading: false,
          mode: '<?= $mode ?>',
          jawaban_claim_dealer: <?= json_encode($jawaban_claim_dealer) ?>,
          claim_dealer_parts: <?= json_encode($claim_dealer_parts) ?>,
        },
        methods: {
          close: function(){
            this.loading = true;
            axios.get('h3/h3_md_jawaban_claim_dealer/close', {
              params: {
                id_jawaban_claim_dealer: this.jawaban_claim_dealer.id_jawaban_claim_dealer
              }
            })
            .then(function(res){
              window.location = 'h3/h3_md_jawaban_claim_dealer/detail?id_jawaban_claim_dealer=' + res.data.id_jawaban_claim_dealer;
            })
            .catch(function(err){
              toastr.error(err);
            })
            .then(function(){ app.loading = false; });
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        },
        computed: {
          parts_belum_proses: function(){
            return _.chain(this.claim_dealer_parts)
            .filter(function(part){
              return part.sudah_proses == 0;
            })
            .value();
          }
        }
      });
  </script>