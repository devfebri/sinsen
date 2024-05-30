<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
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
<?php

  if ($set=="form") {
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='pengembalian') {
          $disabled = 'disabled';
          $form = 'pengembalian';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>

    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <?php 
                  if($mode == 'insert'):
                    $this->load->view('h3/h3_md_berita_acara_penyerahan_faktur_insert');
                  elseif($mode == 'pengembalian'):
                    $this->load->view('h3/h3_md_berita_acara_penyerahan_faktur_pengembalian');
                  endif; 
                 ?>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>' :disabled='items.length == 0'>Simpan</button>
                      <button v-if='mode == "pengembalian" && berita_acara_penyerahan_faktur.dikembalikan == 0' class="btn btn-flat btn-sm btn-primary" @click.prevent='simpan_pengembalian' :disabled='faktur_belum_lunas_tidak_dikembalikan.length > 0'>Simpan</button>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        errors: {},
        <?php if($mode == 'pengembalian' or $mode == 'edit'): ?>
        berita_acara_penyerahan_faktur: <?= json_encode($berita_acara_penyerahan_faktur) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        berita_acara_penyerahan_faktur: {
          id_wilayah_penagihan: '',
          nama_wilayah_penagihan: '',
          end_date: '',
          id_debt_collector: '',
          nama_debt_collector: '',
          id_diketahui: '',
          nama_diketahui: '',
          id_yang_menerima: '',
          nama_yang_menerima: '',
          id_yang_menyerahkan: <?= $logged_user->id_user ?>,
          nama_yang_menyerahkan: '<?= $logged_user->nama_lengkap ?>',
        },
        items: []
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.berita_acara_penyerahan_faktur, [
            'id', 'id_wilayah_penagihan', 'end_date',
            'id_debt_collector','id_diketahui','id_yang_menerima',
            'id_yang_menyerahkan',
          ]);
          
          post.total = this.total;
          post.items = _.chain(this.items)
          .filter(function(item){
            return item.checked == 1;
          })
          .map(function(item){
            return _.pick(item, ['no_faktur', 'keterangan'])
          }).value();

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>';
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
          .then(function(){ form_.loading = false; })
          ;
        },
        simpan_pengembalian: function(){
          post = _.pick(this.berita_acara_penyerahan_faktur, [
            'no_bap'
          ]);
          
          post.items = _.chain(this.items)
          .map(function(item){
            return _.pick(item, ['no_faktur', 'total', 'cash', 'transfer', 'amount_bg', 'no_bg', 'dikembalikan'])
          }).value();

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/simpan_pengembalian', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/pengembalian?no_bap=' + res.data.no_bap;
          })
          .catch(function(err){
            data = err.response.data;
            toastr.error(data.message);
          })
          .then(function(){ form_.loading = false; })
          ;
        },
        proses_faktur: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/proses_faktur', {
            params: {
              id_wilayah_penagihan: this.berita_acara_penyerahan_faktur.id_wilayah_penagihan,
              end_date: this.berita_acara_penyerahan_faktur.end_date,
            }
          })
          .then(function(res){
            form_.items = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          })
        },
        print_nama_customer: function(data){
          if(data.no_tanda_terima_faktur == null){
            return data.nama_dealer;
          }else{
            return data.nama_dealer + " " + data.no_tanda_terima_faktur +  " (" + data.jumlah_faktur + " Fak)";
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
        total: function(){
          return _.chain(this.items)
          .filter(function(item){
            return item.checked == 1;
          })
          .sumBy(function(item){
            return Number(item.total);
          }).value();
        },
        total_cash: function(){
          return _.sumBy(this.items, function(item){
            return item.cash;
          });
        },
        total_amount_bg: function(){
          return _.sumBy(this.items, function(item){
            return item.amount_bg;
          });
        },
        total_transfer: function(){
          return _.sumBy(this.items, function(item){
            return item.transfer;
          });
        },
        faktur_belum_lunas_tidak_dikembalikan: function(){
          return _.chain(this.items)
          .filter(function(item){
            return item.faktur_lunas == 0 && item.dikembalikan == 0;
          })
          .map(function(item){
            return item.no_faktur;
          })
          .value();
        }
      },
      watch: {
        'tanda_terima_faktur.id_wilayah_penagihan': function(){
          h3_md_dealer_tanda_terima_faktur_datatable.draw();
        }
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
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
        <div class="container-fluid">
        <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No BAP</label>
                  <div class="col-sm-8">
                    <input id='no_bap_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#no_bap_filter').on("keyup", _.debounce(function(){
                      berita_acara_penyerahan_faktur.draw();
                    }, 500));
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Tanggal Jatuh Tempo</label>
                  <div class="col-sm-8">
                    <input id='tanggal_jatuh_tempo_filter' type="text" class="form-control" readonly>
                    <input id='tanggal_jatuh_tempo_filter_start' type="hidden" disabled>
                    <input id='tanggal_jatuh_tempo_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#tanggal_jatuh_tempo_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $('#tanggal_jatuh_tempo_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#tanggal_jatuh_tempo_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    berita_acara_penyerahan_faktur.draw();
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#tanggal_jatuh_tempo_filter_start').val('');
                    $('#tanggal_jatuh_tempo_filter_end').val('');
                    berita_acara_penyerahan_faktur.draw();
                  });
                </script>
              </div>
            </div>
          </form>
        </div>
        <table id="berita_acara_penyerahan_faktur" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>No BAP</th>
              <th>Tgl Akhir Jatuh Tempo</th>
              <th>Debt Collector</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            berita_acara_penyerahan_faktur = $('#berita_acara_penyerahan_faktur').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/berita_acara_penyerahan_faktur') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.no_bap_filter = $('#no_bap_filter').val();
                    d.tanggal_jatuh_tempo_filter_start = $('#tanggal_jatuh_tempo_filter_start').val();
                    d.tanggal_jatuh_tempo_filter_end = $('#tanggal_jatuh_tempo_filter_end').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'no_bap' },
                    { data: 'end_date' },
                    { data: 'nama_lengkap' },
                    { data: 'total' },
                    { data: 'action', width: '3%', orderable: false },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>