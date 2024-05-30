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
            <!-- <div v-if='qty_jawaban_dengan_qty_claim_tidak_sinkron.length > 0' class="alert alert-warning" role="alert">
              <strong>Perhatian!</strong> Terdapat kuantitas yang tidak sinkron antara kuantitas part yang diclaim dengan kuantitas jawaban claim.
            </div> -->
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">No Claim Part AHASS</label>
                <div v-bind:class="{ 'has-error': error_exist('id_claim_part_ahass') }" class="col-sm-4">
                  <div class="input-group">
                    <input type="text" class="form-control" readonly v-model='jawaban_claim_dealer.id_claim_part_ahass'>  
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_claim_part_ahass_jawaban_claim_dealer'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
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
                  <input type="text" class="form-control" v-model='jawaban_claim_dealer.no_surat_jalan_ahm'>  
                  <small v-if="error_exist('no_surat_jalan_ahm')" class="form-text text-danger">{{ get_error('no_surat_jalan_ahm') }}</small>   
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Tgl Claim Part AHASS</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" readonly v-model='jawaban_claim_dealer.tgl_claim_part_ahass'>  
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <table id="table" class="table table-responsive">
                    <thead>
                      <tr>                                      
                        <th class="align-top" width='3%'>No.</th>
                        <th class='align-top'>Nama Dealer</th>              
                        <th class='align-top'>Kode Part</th>              
                        <th class='align-top'>Nama Part</th>              
                        <th class='align-top' width="5%">Qty Claim</th>
                        <th class='align-top' width="5%">Qty Terjawab</th>
                        <th class='align-top' width="5%">Qty Belum terjawab</th>
                        <th class='align-top text-center'>Barang</th>              
                        <th class='align-top' width="5%">Qty Barang</th>
                        <th class='align-top text-center'>Uang</th>              
                        <th class='align-top' width="5%">Qty Ganti Uang</th>
                        <th class='align-top' width="10%">Nominal Uang</th>
                        <th class='align-top' width="10%">Total Nominal Uang</th>
                        <th class='align-top text-center'>Tolak</th>              
                        <th class='align-top' width="5%">Qty Tolak</th>
                        <th class='align-top' width="10%">Alasan Ditolak</th>
                        <th class='align-top'>Pending</th>
                        <th class='align-top' width='5%'>Qty Pending</th>
                        <th class='align-top' width='10%'>Alasan Pending</th>
                      </tr>
                    </thead>
                    <tbody>            
                      <tr v-for="(claim_dealer_part, index) in claim_dealer_parts">
                        <td class="align-middle">{{ index + 1 }}.</td>
                        <td class="align-middle">{{ claim_dealer_part.nama_dealer }}</td>                       
                        <td class="align-middle">{{ claim_dealer_part.id_part }}</td>                       
                        <td class="align-middle">{{ claim_dealer_part.nama_part }}</td>            
                        <td class="align-middle">
                          <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="claim_dealer_part.qty_part_dikirim_ke_md"/>
                        </td>  
                        <td class="align-middle">
                          <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="claim_dealer_part.qty_sudah_terjawab"/>
                        </td>  
                        <td class="align-middle">
                          <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="claim_dealer_part.qty_belum_terjawab"/>
                        </td>
                        <td class="align-middle text-center">
                          <input :disabled='allow_ganti_barang(claim_dealer_part)' type="checkbox" true-value='1' false-value='0' v-model='claim_dealer_part.barang_checklist'>
                        </td>      
                        <td class="align-middle">
                          <vue-numeric v-if='claim_dealer_part.barang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" :max='claim_dealer_part.qty_belum_terjawab' v-model="claim_dealer_part.qty_barang"/>
                        </td>  
                        <td class="align-middle text-center">
                          <input :disabled='allow_ganti_uang(claim_dealer_part)' type="checkbox" true-value='1' false-value='0' v-model='claim_dealer_part.uang_checklist'>
                        </td>      
                        <td class="align-middle">
                          <vue-numeric v-if='claim_dealer_part.uang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" :max='claim_dealer_part.qty_belum_terjawab' v-model="claim_dealer_part.qty_uang"/>
                        </td> 
                        <td class="align-middle">
                          <vue-numeric v-if='claim_dealer_part.uang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" currency='Rp' v-model="claim_dealer_part.nominal_uang"/>
                        </td>
                        <td class="align-middle">
                          <vue-numeric v-if='claim_dealer_part.uang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" currency='Rp' :value="total_nominal_uang(claim_dealer_part)"/>
                        </td>
                        <td class="align-middle text-center">
                          <input :disabled='allow_tolak(claim_dealer_part)' type="checkbox" true-value='1' false-value='0' v-model='claim_dealer_part.tolak_checklist'>
                        </td>      
                        <td class="align-middle">
                          <vue-numeric v-if='claim_dealer_part.tolak_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" :max='claim_dealer_part.qty_belum_terjawab' v-model="claim_dealer_part.qty_tolak"/>
                        </td>
                        <td class="align-middle">
                          <input v-if='claim_dealer_part.tolak_checklist == 1' type="text" class="form-control" v-model='claim_dealer_part.alasan_ditolak'>
                        </td> 
                        <td class="align-middle">
                          <input :disabled='allow_pending(claim_dealer_part)' type="checkbox" true-value='1' false-value='0' v-model='claim_dealer_part.pending'>
                        </td>
                        <td class="align-middle">
                          <vue-numeric v-if='claim_dealer_part.pending == 1' read-only class="form-control" separator="." :value='get_qty_pending(claim_dealer_part)'/>
                        </td>
                        <td class="align-middle">
                          <input v-if='claim_dealer_part.pending == 1' type="text" class="form-control" v-model='claim_dealer_part.alasan_pending'>
                        </td>
                      </tr>
                      <tr v-if="claim_dealer_parts.length < 1">
                        <td class="text-center" colspan="17">Belum ada part</td>
                      </tr>
                    </tbody>                    
                  </table>
                </div>
              </div>                                                                                                                                
            </div><!-- /.box-body -->
            <div class="box-footer">
              <div class="col-sm-12 no-padding">
                <button v-if="mode == 'insert'" class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
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
          <?php if($mode == "detail"): ?>
          claim_part_ahass: <?= json_encode($claim_part_ahass) ?>,
          <?php else: ?>
          jawaban_claim_dealer: {
            id_claim_part_ahass: '',
            tgl_claim_part_ahass: '',
            no_surat_jalan_ahm: '',
          },
          claim_dealer_parts: [],
          <?php endif; ?>
        },
        methods: {
          <?= $form ?>: function(){
            post = _.pick(this.jawaban_claim_dealer, ['id_claim_part_ahass', 'no_surat_jalan_ahm']);

            get_qty_pending_fn = this.get_qty_pending;
            post.claim_dealer_parts = _.chain(this.claim_dealer_parts)
            .filter(function(part){
              return part.barang_checklist == 1 || part.uang_checklist == 1 || part.tolak_checklist == 1 || part.pending == 1;
            })
            .map(function(part){
              data = _.pick(part, [
                'id_part', 'id_claim_dealer', 'id_kategori_claim_c3', 'barang_checklist', 
                'uang_checklist', 'tolak_checklist', 'qty_barang', 
                'qty_uang', 'nominal_uang', 'qty_tolak', 'alasan_ditolak', 'pending'
              ]);

              data.qty_pending = get_qty_pending_fn(part);
              return data;
            }).value();

            this.loading = true;
            axios.post('h3/h3_md_jawaban_claim_dealer/<?= $form ?>', Qs.stringify(post))
            .then(function(res){
              data = res.data;
              if(data.redirect_url != null) window.location = data.redirect_url; 
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
            .then(function(){ app.loading = false; });
          },
          get_claim_dealer_parts: function(){
            this.loading = true;
            axios.get('h3/h3_md_jawaban_claim_dealer/get_claim_dealer_parts', {
              params: {
                id_claim_part_ahass: this.jawaban_claim_dealer.id_claim_part_ahass
              }
            })
            .then(function(res){
              app.claim_dealer_parts = res.data;
            })
            .catch(function(err){
              toastr.error(err);
            })
            .then(function(){ app.loading = false; })
          },
          get_qty_pending: function(part){
            qty_pending = 0;

            part_yang_terjawab = 0;
            if(part.barang_checklist == 1){
              part_yang_terjawab += parseInt(part.qty_barang);
            }

            if(part.uang_checklist == 1){
              part_yang_terjawab += parseInt(part.qty_uang);
            }
            
            if(part.tolak_checklist == 1){
              part_yang_terjawab += parseInt(part.qty_tolak);
            }

            qty_pending = parseInt(part.qty_belum_terjawab) - part_yang_terjawab;

            return (qty_pending > 0) ? qty_pending : 0;
          },
          allow_ganti_barang: function(part){
            if(part.uang_checklist == 0 && part.tolak_checklist == 0 && part.pending == 0){
              return false;
            }
            return true;
          },
          allow_ganti_uang: function(part){
            if(part.barang_checklist == 0 && part.tolak_checklist == 0 && part.pending == 0){
              return false;
            }
            return true;
          },
          allow_tolak: function(part){
            if(part.barang_checklist == 0 && part.uang_checklist == 0 && part.pending == 0){
              return false;
            }
            return true;
          },
          allow_pending: function(part){
            if(part.barang_checklist == 0 && part.uang_checklist == 0 && part.tolak_checklist == 0){
              return false;
            }
            return true;
          },
          total_nominal_uang: function(part){
            return parseFloat(part.qty_uang) * parseFloat(part.nominal_uang);
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        },
        watch: {
          'jawaban_claim_dealer.id_claim_part_ahass': function(){
            this.get_claim_dealer_parts();
          },
        },
        computed: {
          qty_jawaban_dengan_qty_claim_tidak_sinkron: function(){
            return _.chain(this.claim_dealer_parts)
            .map(function(part){
              part_yang_terjawab = 0;
              if(part.barang_checklist == 1){
                part_yang_terjawab += parseInt(part.qty_barang);
              }

              if(part.uang_checklist == 1){
                part_yang_terjawab += parseInt(part.qty_uang);
              }
              
              if(part.tolak_checklist == 1){
                part_yang_terjawab += parseInt(part.qty_tolak);
              }

              part.part_yang_terjawab = part_yang_terjawab;
              return part;
            })
            .filter(function(part){
              return parseInt(part.part_yang_terjawab) != parseInt(part.qty_part_dikirim_ke_md); 
            })
            .value();
          }
        }
      });
  </script>