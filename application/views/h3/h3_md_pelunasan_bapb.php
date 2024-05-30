<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
    <?php if($set == 'form'): 
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
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div class="box box-default" id='form_'>
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
                  <label class="col-sm-2 control-label">No. BAPB</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_bapb') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input readonly type="text" class="form-control" v-model='pelunasan_bapb.no_bapb'>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' v-if='no_bapb_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_bapb_pelunasan_bapb'><i class="fa fa-search"></i></button>
                        <button v-if='!no_bapb_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_no_bapb'><i class="fa fa-trash-o"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('no_bapb')" class="form-text text-danger">{{ get_error('no_bapb') }}</small>
                  </div>
                  <?php $this->load->view('modal/h3_md_bapb_pelunasan_bapb') ?>
                  <script>
                    function pilih_bapb_pelunasan_bapb(data){
                      form_.pelunasan_bapb.no_bapb = data.no_bapb;
                      form_.pelunasan_bapb.no_surat_jalan_ekspedisi = data.no_surat_jalan_ekspedisi;
                    }
                  </script>
                  <div v-if='mode != "insert"'>
                    <label class="col-sm-2 control-label">No. Pelunasan</label>
                    <div class="col-sm-4">                    
                      <input readonly type="text" class="form-control" v-model='pelunasan_bapb.no_pelunasan'>
                    </div> 
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Pelunasan</label>
                  <div v-bind:class="{ 'has-error': error_exist('tanggal_pelunasan') }" class="col-sm-4">                    
                    <date-picker :disabled='mode == "detail"' @update-date='tanggal_pelunasan_datepicker_change' class='form-control' readonly :config='config' v-model='pelunasan_bapb.tanggal_pelunasan'></date-picker>
                    <small v-if="error_exist('tanggal_pelunasan')" class="form-text text-danger">{{ get_error('tanggal_pelunasan') }}</small>
                  </div>  
                  <label class="col-sm-2 control-label">No Surat Jalan Ekspedisi</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='pelunasan_bapb.no_surat_jalan_ekspedisi'>
                  </div>  
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div v-bind:class="{ 'has-error': error_exist('keterangan') }" class="col-sm-4">                    
                    <input :readonly='mode == "detail"' type="text" class="form-control" v-model='pelunasan_bapb.keterangan'>
                    <small v-if="error_exist('keterangan')" class="form-text text-danger">{{ get_error('keterangan') }}</small>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Nomor SJ AHM</th>              
                          <th>No PS</th> 
                          <th>No Karton</th>
                          <th>No PO</th>              
                          <th>Kode Part</th>
                          <th>Nama Part</th>
                          <th width='10%'>Qty Kurang/Rusak</th>
                          <th>Ganti Uang/Barang</th>
                          <th>Proses Pembayaran</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.surat_jalan_ahm }}</td>                       
                          <td class="align-middle">{{ part.packing_sheet_number }}</td>                       
                          <td class="align-middle">{{ part.nomor_karton }}</td>                       
                          <td class="align-middle">{{ part.no_po }}</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>
                          <td class="align-middle">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" v-model='part.qty_rusak' separator='.'></vue-numeric>
                          </td>    
                          <td class="align-middle">
                            <select v-if='mode != "detail"' class="form-control" v-model='part.tipe_ganti'>
                              <option value="">-Choose-</option>
                              <option value="Uang">Uang</option>
                              <option value="Barang">Barang</option>
                            </select>
                            <span v-if='mode == "detail"'>{{ part.tipe_ganti }}</span>
                          </td>
                          <td class="align-middle">
                            <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='part.proses_pembayaran'>
                          </td>                
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="11">Belum ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" :disabled='loading' class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      form_ = new Vue({
          el: '#form_',
          data: {
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            pelunasan_bapb: <?= json_encode($pelunasan_bapb) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            pelunasan_bapb: {
              no_bapb: '',
              no_surat_jalan_ekspedisi: '',
              tanggal_pelunasan: '',
            },
            parts: [],
            <?php endif; ?>
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
            },
          },
          methods: {
            <?= $form ?>: function(){
              
              post = this.pelunasan_bapb;
              post.parts = _.map(this.parts, function(part){
                return _.pick(part, [
                  'nomor_karton', 'id_part', 'qty_rusak', 
                  'id_lokasi_rak', 'proses_pembayaran', 'tipe_ganti', 
                  'packing_sheet_number', 'surat_jalan_ahm', 'no_po'
                ]);
              });

              post.parts_ganti_uang = this.parts_ganti_uang;
              post.parts_ganti_barang = this.parts_ganti_barang;

              this.errors = {};
              this.loading = true;
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                data = res.data;
                if(data.redirect_url) window.location = data.redirect_url;
              }).catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  form_.errors = data.errors;
                  toastr.error(data.message);
                  form_.loading = false;
                }else{
                  toastr.error(data.message);
                }
              })
            },
            parts_bapb: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/parts_bapb', {
                params: {
                  no_bapb: this.pelunasan_bapb.no_bapb
                }
              })
              .then(function(res){
                form_.parts = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ form_.loading = false; })
            },
            reset_no_bapb: function(){
              this.pelunasan_bapb.no_bapb = '';
              this.pelunasan_bapb.no_surat_jalan_ekspedisi = '';
            },
            tanggal_pelunasan_datepicker_change: function(date){
              this.pelunasan_bapb.tanggal_pelunasan = date.format('yyyy-mm-dd');
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          watch: {
            'pelunasan_bapb.no_bapb': function(n, o){
              this.parts_bapb();
            },
          },
          computed: {
            parts_ganti_uang: function(){
              return _.chain(this.parts).filter(function(p){
                return p.tipe_ganti == 'Uang';
              })
              .map(function(p){
                return _.pick(p, form_.part_keys);
              }).value();
            },
            parts_ganti_barang: function(){
              return _.chain(this.parts).filter(function(p){
                return p.tipe_ganti == 'Barang';
              })
              .map(function(p){
                return _.pick(p, form_.part_keys);
              }).value();
            },
            no_bapb_empty: function(){
              return this.pelunasan_bapb.no_bapb == '' || this.pelunasan_bapb.no_bapb == null;
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
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="pelunasan_bapb" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Pelunasan</th>              
              <th>No. BAPB</th>              
              <th>No. Surat Jalan Ekspedisi</th>              
              <th>Jumlah Surat Jalan AHM</th>              
              <th>Jumlah No. PS</th>              
              <th>Qty Rusak</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            pelunasan_bapb = $('#pelunasan_bapb').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/pelunasan_bapb') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'no_pelunasan' },
                    { data: 'no_bapb' },
                    { data: 'no_surat_jalan_ekspedisi' },
                    { data: 'count_surat_jalan_ahm' },
                    { data: 'count_packing_sheet_number' },
                    { data: 'qty_rusak' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
        <?php $this->load->view('modal/h3_md_open_view_surat_jalan_ahm_pelunasan_bapb'); ?>
        <?php $this->load->view('modal/h3_md_open_view_packing_sheet_number_pelunasan_bapb'); ?>
        <script>
        $(document).ready(function(){
          $('#pelunasan_bapb tbody').on( 'click', 'td', function () {
              column = pelunasan_bapb.cell( this ).index().column;
              index_row = pelunasan_bapb.cell( this ).index().row;
              data = pelunasan_bapb.row(index_row).data();

              if(column == 4){
                $('#h3_md_open_view_surat_jalan_ahm_pelunasan_bapb_value').val(data.no_pelunasan);
                h3_md_open_view_surat_jalan_ahm_pelunasan_bapb_datatable.draw();
                $('#h3_md_open_view_surat_jalan_ahm_pelunasan_bapb').modal('show');
              }

              if(column == 5){
                $('#h3_md_open_view_packing_sheet_number_pelunasan_bapb_value').val(data.no_pelunasan);
                h3_md_open_view_packing_sheet_number_pelunasan_bapb_datatable.draw();
                $('#h3_md_open_view_packing_sheet_number_pelunasan_bapb').modal('show');
              }
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>