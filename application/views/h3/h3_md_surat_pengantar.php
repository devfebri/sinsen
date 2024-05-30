<base href="<?php echo base_url(); ?>" /> 
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<body>
<div class="content-wrapper">
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
      if ($mode == 'detail') {
        $form = 'create';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id='app' class="box box-default">
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
        <div v-if="surat_pengantar.cetak_sl_ke > 1" class="alert alert-warning" role="alert">
          Cetakan Surat Pengantar telah dicetak sebanyak {{ surat_pengantar.cetak_sl_ke }} kali
        </div>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                    <div class="input-group">
                      <input v-model="nama_dan_kode_customer" readonly type="text" class="form-control">
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail" || mode == "edit"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#h3_md_customer_surat_pengantar"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                  </div>     
                </div>
                <?php $this->load->view('modal/h3_md_customer_surat_pengantar_modal') ?>
                <script>
                  function pilih_customer_surat_pengantar(data){
                    app.surat_pengantar.id_dealer = data.id_dealer;
                    app.surat_pengantar.kode_dealer_md = data.kode_dealer_md;
                    app.surat_pengantar.nama_dealer = data.nama_dealer;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_ekspedisi') }" class="col-sm-4">
                    <div class="input-group">
                      <input v-model="surat_pengantar.nama_ekspedisi" readonly type="text" class="form-control">
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail" || mode == "edit"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#h3_md_ekspedisi_surat_pengantar"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_ekspedisi')" class="form-text text-danger">{{ get_error('id_ekspedisi') }}</small>
                  </div>     
                </div>
                <?php $this->load->view('modal/h3_md_ekspedisi_surat_pengantar_modal') ?>
                <script>
                  function pilih_ekspedisi_surat_pengantar(data){
                    app.surat_pengantar.id_ekspedisi = data.id;
                    app.surat_pengantar.nama_ekspedisi = data.nama_ekspedisi;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Plat</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_plat') }" class="col-sm-4">
                    <input :readonly="mode == 'detail'" v-model="surat_pengantar.no_plat" type="text" class="form-control">
                    <small v-if="error_exist('no_plat')" class="form-text text-danger">{{ get_error('no_plat') }}</small>
                  </div>     
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Tgl Packing Sheet</th>              
                          <th>No Packing Sheet</th>              
                          <th>No SO</th>              
                          <th>Tipe PO</th>              
                          <th>Jumlah Koli</th>              
                          <th width="3%"></th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(packing_sheet, index) in packing_sheets"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                             
                          <td class="align-middle">{{ packing_sheet.tgl_packing_sheet }}</td>                             
                          <td class="align-middle">{{ packing_sheet.id_packing_sheet }}</td>                             
                          <td class="align-middle">{{ packing_sheet.id_sales_order }}</td>                             
                          <td class="align-middle">{{ packing_sheet.po_type }}</td>                             
                          <td class="align-middle">{{ packing_sheet.jumlah_koli }}</td>                             
                          <td v-if="mode != 'detail'" class="align-middle">
                            <input type="checkbox" true-value='1' false-value='0' v-model='packing_sheet.checked'>
                          </td>                             
                        </tr>
                        <tr v-if="packing_sheets.length < 1">
                          <td class="text-center" colspan="6">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode == 'insert'" @click="<?= $form ?>" type="button" class="btn btn-sm btn-flat btn-primary">Simpan</button>
                  <!-- <a v-if='mode == "detail"' :href="'h3/h3_md_surat_pengantar/cetak?id_surat_pengantar=' + surat_pengantar.id_surat_pengantar" class='btn btn-flat btn-sm btn-info'>Cetak</a> -->
                  <a v-if='mode == "detail" && (surat_pengantar.cetak_sl_ke == 0 || surat_pengantar.cetak_sl_ke == null ) ' :href="'h3/h3_md_surat_pengantar/cetak?id_surat_pengantar=' + surat_pengantar.id_surat_pengantar" class='btn btn-flat btn-sm btn-info'>Cetak</a>
                  <button v-if='mode == "detail" && surat_pengantar.cetak_sl_ke >= 1 ' class="btn btn-flat btn-sm btn-danger" type='button' data-toggle='modal' data-target='#passwordModal'>Cetak</button>
                  <?php $this->load->view('modal/h3_verify_password_surat_pengantar') ?>
                </div>
                <div class="col-sm-6 text-right">
                  <a onclick='return confirm("Apakah anda yakin ingin menutup Surat Pengantar ini?")' v-if='mode == "detail"&& surat_pengantar.close_sl!=1' :href="'h3/h3_md_surat_pengantar/close?id_surat_pengantar=' + surat_pengantar.id_surat_pengantar" class="btn btn-sm btn-flat btn-warning">Close</a>
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
            loading: false,
            mode: '<?= $mode ?>',
            errors: {},
            <?php if($mode == 'detail' || $mode == 'edit'): ?>
            surat_pengantar: <?= json_encode($surat_pengantar) ?>,
            packing_sheets: <?= json_encode($packing_sheets) ?>,
            <?php else: ?>
            surat_pengantar: {
              id_dealer: '',
              kode_dealer_md: '',
              nama_dealer: '',
              id_ekspedisi: '',
              nama_ekspedisi: '',
              no_plat: ''
            },
            packing_sheets: []
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              this.loading = true;

              post = _.pick(this.surat_pengantar, ['id_surat_pengantar', 'id_dealer', 'id_ekspedisi', 'no_plat']);
              post.packing_sheets = _.chain(this.packing_sheets)
              .filter(function(data){
                return data.checked == 1;
              })
              .map(function(data){
                return _.pick(data, ['id_packing_sheet']);
              }).value();

              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_surat_pengantar/detail?id_surat_pengantar=' + res.data.id_surat_pengantar;
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
            get_packing_sheets: function(){
              this.loading = true;

              axios.get('h3/<?= $isi ?>/get_packing_sheets', {
                params: {
                  id_dealer: this.surat_pengantar.id_dealer
                }
              })
              .then(function(res){
                app.packing_sheets = res.data;
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
            },
            verify_password: function(){
              post = {};
              post.id_surat_pengantar = this.surat_pengantar.id_surat_pengantar;
              post.password = $('#passwordInput').val();

              this.loading = true;
              axios.post('h3/h3_md_surat_pengantar/verifyPassword', Qs.stringify(post))
              .then(function(res){
                if(res.data.pesan == 'Success'){
                  window.location = 'h3/h3_md_surat_pengantar/cetak?id_surat_pengantar=' + res.data.id_surat_pengantar;
                }else{
                  alert("Password Salah!");
                }
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ app.loading = false; })
            },
          },
          watch: {
            'surat_pengantar.id_dealer': function(){
              app.get_packing_sheets();
            }
          },
          computed: {
            nama_dan_kode_customer: function(){
              if(this.surat_pengantar.kode_dealer_md != '' && this.surat_pengantar.nama_dealer != ''){
                return this.surat_pengantar.kode_dealer_md + ' - ' + this.surat_pengantar.nama_dealer;
              }
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-primary btn-flat margin">Add New</button>
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
        <table id="surat_pengantar" class="table table-bordered table-hover">
          <thead>
              <tr>
                  <th>No.</th>
                  <th>No. Surat Pengantar</th>
                  <th>Tgl Surat Pengantar</th>
                  <th>Customer</th>
                  <th>Ekspedisi</th>
                  <th width="10%">Action</th>
              </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          surat_pengantar = $('#surat_pengantar').DataTable({
            processing: true,
            serverSide: true,
            "language": {           
                    "searchPlaceholder": "Min. 3 digit untuk cari",
                  }, 
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/surat_pengantar') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'id_surat_pengantar' }, 
                { data: 'tanggal' }, 
                { data: 'nama_dealer' }, 
                { data: 'nama_ekspedisi' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });

          $(".dataTables_filter input")
                .unbind() // Unbind previous default bindings
                .bind("input", function(e) { // Bind our desired behavior
                    // If the length is 3 or more characters, or the user pressed ENTER, search
                    if(this.value.length >= 3 || e.keyCode == 13) {
                        // Call the API search function
                        surat_pengantar.search(this.value).draw();
                    }
                    // Ensure we clear the search if they backspace far enough
                    if(this.value == "") {
                      surat_pengantar.search("").draw();
                    }
                    return;
                });
                
          surat_pengantar.on('draw.dt', function() {
            var info = surat_pengantar.page.info();
            surat_pengantar.column(0, {
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
    <?php endif; ?>
  </section>
</div>
