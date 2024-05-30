<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
        <div v-if='kuantitas_part_tidak_sinkron.length > 0' class="alert alert-warning" role="alert">
          <strong>Perhatian!</strong> Terdapat kuantitas part yang tidak sinkron.
        </div>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div v-if='parts_tanpa_pelunasan_uang.length > 0' class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
                  <h4>
                    <i class="icon fa fa-warning"></i> 
                    Perhatian!
                  </h4>
                  <p>Terdapat part dengan pelunasan ganti uang 0 sehingga tidak bisa melanjutkan ke proses buat SO, antara: {{ _.map(parts_tanpa_pelunasan_uang, function(part){
                    return part.id_part;
                  }).join(', ') }}</p>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. BAPB</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_bapb') }" class="col-sm-3">                    
                    <input disabled v-model='berita_acara.no_bapb' type="text" class="form-control">
                    <small v-if="error_exist('no_bapb')" class="form-text text-danger">{{ get_error('no_bapb') }}</small>
                  </div>  
                  <label class="col-sm-offset-1 col-sm-2 control-label">Tanggal Serah Terima</label>
                  <div v-bind:class="{ 'has-error': error_exist('tanggal_serah_terima') }" class="col-sm-4">                    
                    <date-picker :disabled='mode == "detail"' @update-date='tanggal_serah_terima_datepicker_change' class='form-control' readonly :config='config' v-model='berita_acara.tanggal_serah_terima'></date-picker>
                    <small v-if="error_exist('tanggal_serah_terima')" class="form-text text-danger">{{ get_error('tanggal_serah_terima') }}</small>
                  </div>  
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-3">                    
                    <input readonly type="text" class="form-control" v-model='berita_acara.vendor_name'>
                      <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>
                  </div> 
                  <div class="col-sm-1 no-padding">
                    <button v-if='mode == "insert"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_berita_acara_penerimaan_barang'><i class="fa fa-search"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_ekspedisi_berita_acara_penerimaan_barang') ?>
                  <script>
                    function pilih_ekspedisi_berita_acara_penerimaan_barang(data){
                      form_.berita_acara.id_vendor = data.id;
                      form_.berita_acara.vendor_name = data.nama_ekspedisi;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Nama Driver</label>
                  <div v-bind:class="{ 'has-error': error_exist('nama_driver') }" class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='berita_acara.nama_driver'>
                      <small v-if="error_exist('nama_driver')" class="form-text text-danger">{{ get_error('nama_driver') }}</small>
                  </div>
                                              
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor Surat Jalan Ekspedisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_surat_jalan_ekspedisi') }" class="col-sm-3">                    
                    <input readonly type="text" class="form-control" v-model='berita_acara.no_surat_jalan_ekspedisi'>
                      <small v-if="error_exist('no_surat_jalan_ekspedisi')" class="form-text text-danger">{{ get_error('no_surat_jalan_ekspedisi') }}</small>
                  </div> 
                  <div class="col-sm-1 no-padding">
                    <button v-if='mode == "insert"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang'><i class="fa fa-search"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang') ?>
                  <script>
                    function pilih_surat_jalan_ekspedisi_berita_acara_penerimaan_barang(data){
                      form_.berita_acara.no_surat_jalan_ekspedisi = data.no_surat_jalan_ekspedisi;
                      form_.berita_acara.nama_driver = data.nama_driver;
                      form_.berita_acara.no_plat = data.no_plat;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Nomor Plat</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_plat') }" class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='berita_acara.no_plat'>
                      <small v-if="error_exist('no_plat')" class="form-text text-danger">{{ get_error('no_plat') }}</small>
                  </div>   
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>SJ AHM</th>              
                          <th>No PS</th>              
                          <th>No Karton</th>              
                          <th>No PO</th>              
                          <th>Kode Part</th>
                          <th>Nama Part</th>
                          <th width='5%'>Qty PS</th>
                          <th width='5%'>Qty Terima</th>              
                          <th width='5%'>Qty Kurang/Rusak</th>
                          <th>Keterangan</th>              
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
                          <td class="align-middle text-right">{{ part.packing_sheet_quantity }}</td>                       
                          <td class="align-middle text-right">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" v-model='part.qty_diterima' :max='part.packing_sheet_quantity' separator='.'></vue-numeric>
                          </td>    
                          <td class="align-middle text-right">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" v-model='part.qty_rusak' :max='part.packing_sheet_quantity' separator='.'></vue-numeric>
                          </td>    
                          <td class="align-middle">
                            <input :readonly='mode == "detail"' type="text" class="form-control" v-model='part.keterangan_bapb'>
                          </td>                                 
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="10">Belum ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <a v-if='mode == "detail" && berita_acara.id_sales_order == null' :href="'h3/h3_md_berita_acara_penerimaan_barang/edit?no_bapb=' + berita_acara.no_bapb" class="btn btn-sm btn-flat btn-warning">Edit</a>
                  <button v-if="mode == 'insert'" :disabled='kuantitas_part_tidak_sinkron.length > 0' class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" :disabled='kuantitas_part_tidak_sinkron.length > 0' class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <a v-if='berita_acara.id_sales_order == null && berita_acara.id_dealer != null' :disabled='parts_tanpa_pelunasan_uang.length != 0' :href="'h3/h3_md_sales_order/add?generateSalesOrderEkspedisi=true&no_bapb='+ berita_acara.no_bapb" class="btn btn-sm btn-flat btn-success">Create SO</a>
                  <button v-if="mode == 'detail' && berita_acara.id_sales_order == null && berita_acara.status != 'Canceled'" class="btn btn-sm btn-flat btn-danger" @click.prevent='cancel'>Cancel</button>
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
            mode: '<?= $mode ?>',
            loading: false,
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            berita_acara: <?= json_encode($berita_acara) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            berita_acara: {
              no_surat_jalan_ekspedisi: '',
              nama_driver: '',
              no_plat: '',
              id_vendor: '',
              vendor_name: '',
              tanggal_serah_terima: ''
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
              this.errors = {};
              this.loading = true;
              post = this.berita_acara;
              post.parts = _.map(this.parts, function(p){
                return _.pick(p, ['nomor_karton', 'id_part', 'qty_diterima', 'qty_rusak', 'id_lokasi_rak', 'keterangan_bapb', 'packing_sheet_number', 'no_po', 'surat_jalan_ahm'])
              })
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post)).then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?no_bapb=' + res.data.no_bapb;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  form_.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
              })
              .then(function(){
                form_.loading = false;
              });
            },
            cancel: function(){
              params = {
                no_bapb: this.berita_acara.no_bapb,
              }
              this.loading = true;
              axios.get('h3/<?= $isi ?>/cancel', {
                params: params
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?no_bapb=' + res.data.no_bapb;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ form_.loading = false; });
            },
            parts_laporan_penerimaan_barang: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/parts_laporan_penerimaan_barang', {
                params: {
                  no_surat_jalan_ekspedisi: this.berita_acara.no_surat_jalan_ekspedisi
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
            tanggal_serah_terima_datepicker_change: function(date){
              this.berita_acara.tanggal_serah_terima = date.format('yyyy-mm-dd');
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
          },
          watch: {
            'berita_acara.no_surat_jalan_ekspedisi': function(n, o){
              this.parts_laporan_penerimaan_barang();
            },
            'berita_acara.id_vendor': function(n, o){
              h3_md_surat_jalan_ekspedisi_berita_acara_penerimaan_barang_datatable.draw();
            },
          },
          computed: {
            kuantitas_part_tidak_sinkron: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return parseInt(part.packing_sheet_quantity) != ( parseInt(part.qty_claim_ahm) + parseInt(part.qty_rusak) );
              })
              .value();
            },
            parts_tanpa_pelunasan_uang: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return parseInt(part.qty_pelunasan_uang) == 0;
              })
              .value();
            },
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header">
        <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
        <?php endif; ?>
      </div>
      <div class="box-body">
        <table id="berita_acara" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>NO BAPB</th>              
              <th>No. SJ Ekspedisi</th>              
              <th>SJ AHM</th>              
              <th>Packing Sheet</th>              
              <th>Qty Rusak</th>              
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <?php $this->load->view('modal/h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang'); ?>
        <?php $this->load->view('modal/h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang'); ?>
        <script>
          $(document).ready(function() {
            berita_acara = $('#berita_acara').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/berita_acara') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'no_bapb' },
                    { data: 'no_surat_jalan_ekspedisi' },
                    // { data: 'open_view_surat_jalan_ahm', orderable: false, width: '15%', className: 'text-center' },
                    { 
                      data: 'jumlah_surat_jalan', 
                      orderable: false, 
                      width: '15%', 
                      className: 'text-center text-bold',
                      render: function(data){
                        return data + ' SJ AHM';
                      }
                    },
                    { 
                      data: 'jumlah_packing_sheet', 
                      orderable: false, 
                      width: '15%', 
                      className: 'text-center text-bold',
                      render: function(data){
                        return data + ' Packing Sheet';
                      }
                    },
                    { 
                      data: 'qty_rusak',
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",");
                      }
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
        <script>
        $(document).ready(function(){
          $('#berita_acara tbody').on( 'click', 'td', function () {
              column = berita_acara.cell( this ).index().column;
              index_row = berita_acara.cell( this ).index().row;
              data = berita_acara.row(index_row).data();

              console.log(column, data);

              if(column == 4){
                $('#no_bapb_for_open_view_packing_sheet_number').val(data.no_bapb);
                h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang_datatable.draw();
                $('#h3_md_open_view_packing_sheet_berita_acara_penerimaan_barang').modal('show');
              }

              if(column == 3){
                $('#no_bapb_for_open_view_surat_jalan_ahm').val(data.no_bapb);
                h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang_datatable.draw();
                $('#h3_md_open_view_surat_jalan_ahm_berita_acara_penerimaan_barang').modal('show');
              }
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>