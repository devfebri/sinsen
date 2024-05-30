<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'detail';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
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
            <form class="form-horizontal">
              <div class="box-body">
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label">No. Terima Claim</label>
                  <div class="col-sm-4">                    
                    <input v-model="terima_claim.id_terima_claim_ahm" readonly type="text" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Terima Claim</label>
                  <div class="col-sm-4">                    
                    <input v-model="terima_claim.created_at" readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Surat Jawaban AHM</label>
                  <div v-bind:class="{ 'has-error': error_exist('tanggal_surat_jawaban') }" class="col-sm-4">                    
                    <date-picker :disabled='mode == "detail"' @update-date='tanggal_surat_jawaban_datepicker_change' class='form-control' readonly :config='config' v-model='terima_claim.tanggal_surat_jawaban'></date-picker>
                    <small v-if="error_exist('tanggal_surat_jawaban')" class="form-text text-danger">{{ get_error('tanggal_surat_jawaban') }}</small>                                
                  </div>
                  <div v-if='mode != "insert"'>
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-4">                    
                      <input type="text" class="form-control" readonly v-model='terima_claim.status'>
                    </div>
                  </div>
                </div>
                <div class="container-fluid bg-primary" style='padding: 8px; margin-bottom: 10px;'>
                  <div class="row">
                    <div class="col-sm-12 text-center">
                      <span class='text-bold'>Claim</span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-top' width='3%'>No.</th>              
                          <th class='align-top'>No. Claim</th>              
                          <th class='align-top' width='10%'>Part Number</th>              
                          <th class='align-top' width='15%'>Part Deskripsi</th>              
                          <th class='align-top'>Kode Claim</th>              
                          <th class='align-top' width="10%">Qty Claim</th>
                          <th class='align-top' width="5%">Barang</th>
                          <th class='align-top' width="10%">Ganti Barang</th>
                          <th class='align-top' width="5%">Uang</th>
                          <th class='align-top' width="10%">Ganti Uang</th>
                          <th class='align-top' width="10%">Nominal Uang</th>
                          <th class='align-top' width="10%">Total Nominal Uang</th>
                          <th class='align-top' width="5%">Tolak</th>
                          <th class='align-top'>Qty Ditolak</th>
                          <th class='align-top' v-if="mode != 'detail'" width="3%"></th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_claim }}</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">{{ part.kode_claim }} - {{ part.nama_claim }}</td>            
                          <td class="align-middle">
                            <vue-numeric class="form-control" :read-only='true' separator="." :empty-value="1" v-model="part.qty_part_diclaim"/>
                          </td>
                          <td class='align-middle text-center'>
                            <input type="checkbox" :disabled='allow_ganti_barang(part) || mode == "detail"' true-value='1' false-value='0' v-model='part.barang_checklist'>
                          </td>
                          <td class="align-middle">
                            <vue-numeric v-if='part.barang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.ganti_barang" :max='part.sisa_boleh_terima_claim'>
                          </td>
                          <td class='align-middle text-center'>
                            <input :disabled='allow_ganti_uang(part) || mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='part.uang_checklist'>
                          </td>
                          <td class="align-middle">
                            <vue-numeric v-if='part.uang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.ganti_uang" :max='part.sisa_boleh_terima_claim'/>
                          </td> 
                          <td class="align-middle">
                            <vue-numeric v-if='part.uang_checklist == 1' :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.nominal_uang" currency='Rp'/>
                          </td>  
                          <td class="align-middle">
                            <vue-numeric v-if='part.uang_checklist == 1' read-only class="form-control" separator="." :empty-value="1" :value="total_nominal_uang(part)" currency='Rp'/>
                          </td>  
                          <td class='align-middle text-center'>
                            <input :disabled='allow_tolak(part) || mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='part.ditolak_checklist'>
                          </td>
                          <td class="align-middle">
                            <vue-numeric v-if='part.ditolak_checklist == 1' :read-only='mode == "detail"'  class="form-control" separator="." :empty-value="1" v-model="part.ditolak" :max='part.sisa_boleh_terima_claim'/>
                          </td>
                          <td v-if="mode != 'detail'" class="align-middle">
                            <button class="btn btn-flat btn-sm btn-danger" v-on:click.prevent="hapus_part(index)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                          </td>
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="14">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                  <div v-if="mode != 'detail'" class="col-sm-12 text-right">
                    <button type="button" class="btn-sm btn btn-flat btn-primary" data-toggle="modal" data-target="#h3_md_parts_terima_claim_ahm"><i class="fa fa-plus" aria-hidden="true"></i></button>
                  </div>
                </div>                                                                                                                                
                <?php $this->load->view('modal/h3_md_parts_terima_claim_ahm'); ?>
                <script>
                  function pilih_parts_terima_claim_ahm(data){
                    app.parts.push(data);
                    h3_md_parts_terima_claim_ahm_datatable.draw();
                  }
                </script>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && terima_claim.status == "Open"' class="btn btn-sm btn-flat btn-warning" :href="'h3/<?= $isi ?>/edit?id_terima_claim_ahm=' + terima_claim.id_terima_claim_ahm">Edit</a>
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <button v-if='mode == "detail" && terima_claim.status == "Open"' class="btn btn-sm btn-flat btn-info" @click.prevent='proses'>Proses</button>
                  <button v-if='mode == "detail" && terima_claim.status == "Open"' class="btn btn-sm btn-flat btn-danger" @click.prevent='cancel'>Cancel</button>
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
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            terima_claim: <?= json_encode($terima_claim) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            terima_claim: {
              tanggal_surat_jawaban: '',
            },
            parts: [],
            <?php endif; ?>
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
              todayBtn: 'linked'
            },
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.terima_claim, [
                'id_terima_claim_ahm', 'tanggal_surat_jawaban',
              ]);

              post.parts = _.map(this.parts, function(part){
                return _.pick(part, [
                  'id_claim', 'id_claim_int', 'id_part', 'id_part_int', 'no_doos', 'no_po', 'no_po_int', 'id_kode_claim', 'barang_checklist', 'ganti_barang', 'uang_checklist', 'ganti_uang', 'nominal_uang', 'ditolak_checklist', 'ditolak'
                ]);
              });

              this.loading = true;
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                app.errors = err.response.data;
                toastr.error(data.message);
                app.loading = false;
              });
            },
            proses: function(){
              post = _.pick(this.terima_claim, ['id_terima_claim_ahm']);
              
              this.loading = true;
              axios.post('h3/<?= $isi ?>/proses', Qs.stringify(post))
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            cancel: function(){
              post = _.pick(this.terima_claim, ['id_terima_claim_ahm']);
              
              this.loading = true;
              axios.post('h3/<?= $isi ?>/cancel', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_terima_claim_ahm=' + res.data.id_terima_claim_ahm;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            hapus_part: function(index) {
              this.parts.splice(index, 1);
              h3_md_parts_terima_claim_ahm_datatable.draw();
            },
            open_kode_claim_datatable: function(index){
              if(this.mode == 'detail') return;
              this.index_part = index;
              $('#h3_md_kode_claim_claim_main_dealer_ke_ahm').modal('show');
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
            tanggal_surat_jawaban_datepicker_change: function(date){
              this.terima_claim.tanggal_surat_jawaban = date.format('yyyy-mm-dd');

            },
            allow_ganti_barang: function(part){
              if(part.uang_checklist == 0 && part.ditolak_checklist == 0){
                return false;
              }
              return true;
            },
            allow_ganti_uang: function(part){
              if(part.barang_checklist == 0 && part.ditolak_checklist == 0){
                return false;
              }
              return true;
            },
            allow_tolak: function(part){
              if(part.barang_checklist == 0 && part.uang_checklist == 0){
                return false;
              }
              return true;
            },
            total_nominal_uang: function(part){
              return parseFloat(part.ganti_uang) * parseFloat(part.nominal_uang);
            }
          },
          watch:{
            'claim_main_dealer.packing_sheet_number': function(){
              h3_md_parts_claim_main_dealer_ke_ahm_datatable.draw();
            },
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="terima_claim_ahm" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Terima Claim AHM</th>              
              <th>Tanggal Surat Jawaban AHM</th>              
              <th>Tanggal Dibuat</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          terima_claim_ahm = $('#terima_claim_ahm').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/terima_claim_ahm') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'id_terima_claim_ahm' }, 
                { data: 'tanggal_surat_jawaban' }, 
                { data: 'created_at' }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
