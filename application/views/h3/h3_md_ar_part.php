<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/jquery.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
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

      if ($mode == 'upload') {
        $form = 'inject';
      }

      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
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
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Rekap</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" v-model='rekap_invoice.id_rekap_invoice'>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Tgl Faktur</th>              
                          <th>No Faktur</th>              
                          <th>TOP DPP</th>              
                          <th>TOP PPN</th>              
                          <th>Total DPP</th>              
                          <th>Total PPN</th>              
                          <th>No Giro</th>              
                          <th>Amount Giro</th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-if='items.length > 0' v-for="(item, index) in items"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ item.invoice_date }}</td>                       
                          <td class="align-middle">{{ item.invoice_number }}</td>                       
                          <td class="align-middle">{{ item.dpp_due_date }}</td>                       
                          <td class="align-middle">{{ item.ppn_due_date }}</td>                       
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="item.total_dpp" />
                          </td>     
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="item.total_ppn" />
                          </td>        
                          <td class="align-middle">{{ item.no_giro }}</td> 
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="item.amount_giro" />
                          </td>             
                        </tr>
                        <tr v-if='items.length > 0'> 
                          <td class="align-middle text-right" colspan='5'>Total</td>            
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="total_dpp" />
                          </td>            
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="total_ppn" />
                          </td>            
                        </tr>
                        <tr v-if='items.length > 0'> 
                          <td class="align-middle text-right" colspan='5'>Grand Total</td>            
                          <td class="align-middle text-right" colspan='2'>
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="grand_total" />
                          </td>            
                        </tr>
                        <tr v-if="items.length < 1">
                          <td class="text-center" colspan="9">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
              </div><!-- /.box-body -->
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
            <?php if($mode == 'detail'): ?>
            rekap_invoice: <?= json_encode($rekap_invoice) ?>,
            items: <?= json_encode($items) ?>,
            <?php endif; ?>
          },
          computed: {
            total_dpp: function(){
              return _.sumBy(this.items, function(i){
                return i.total_dpp;
              });
            },
            total_ppn: function(){
              return _.sumBy(this.items, function(i){
                return i.total_ppn;
              });
            },
            grand_total: function(){
              return this.total_dpp + this.total_ppn;
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
          <a id='download-button' href="h3/<?= $isi ?>/download">
            <button class="btn btn-info btn-flat margin"><i class="fa fa-download"></i> Download</button>
          </a> 
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Batal Akhir Tanggal Jatuh Tempo</label>
                    <input id='tanggal_batas_akhir_referensi' type="text" class="form-control" readonly>
                    <input id='tanggal_batas_akhir_referensi_value' type="hidden">
                  </div>
                </div>    
              </div> 
              <script>
                $(document).ready(function(){
                  $('#tanggal_batas_akhir_referensi').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    clearBtn: true,
                  }).on('changeDate', function(e){
                    $('#tanggal_batas_akhir_referensi_value').val(e.format('yyyy-mm-dd'));
                    ar_part.draw();
                  });
                });
              </script>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Jenis transaksi</label>
                    <select id="jenis_transaksi_filter" class="form-control">
                      <option value="">-All-</option>
                      <option value="parts">Parts</option>
                      <option value="oil">Oil</option>
                      <option value="acc">Acc</option>
                    </select>
                  </div>                
                </div>
                <script>
                  $(document).ready(function(){
                    $('#jenis_transaksi_filter').on("change", function(){
                      ar_part.draw();
                    });
                  });
                </script>         
              </div>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12"> 
                  <div class="form-group">
                    <label class="control-label">No. Referensi</label>
                    <input id='no_referensi_filter' type="text" class="form-control">
                  </div>
                </div>
              </div>
              <script>
              $(document).ready(function(){
                $('#no_referensi_filter').on("keyup", _.debounce(function(){
                  ar_part.draw();
                }, 500));
              });
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Tanggal Jatuh Tempo</label>
                    <input id='tanggal_jatuh_tempo_filter' type="text" class="form-control" readonly>
                    <input id='tanggal_jatuh_tempo_filter_start' type="hidden" disabled>
                    <input id='tanggal_jatuh_tempo_filter_end' type="hidden" disabled>
                  </div>      
                </div>
              </div>          
            </div>
            <script>
              $('#tanggal_jatuh_tempo_filter').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              }, function(start, end, label) {
                $('#tanggal_jatuh_tempo_filter_start').val(start.format('YYYY-MM-DD'));
                $('#tanggal_jatuh_tempo_filter_end').val(end.format('YYYY-MM-DD'));
                ar_part.draw();
              }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
              }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#tanggal_jatuh_tempo_filter_start').val('');
                $('#tanggal_jatuh_tempo_filter_end').val('');
                ar_part.draw();
              });
            </script>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_ar_part_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>  
                </div>
              </div>              
            </div>
          </div>
          <?php $this->load->view('modal/h3_md_dealer_filter_ar_part_index'); ?>
          <script>
            function pilih_dealer_filter_ar_part_index(data, type) {
              if(type == 'add_filter'){
                $('#nama_customer_filter').val(data.nama_dealer);
                $('#id_customer_filter').val(data.id_dealer);
              }else if(type == 'reset_filter'){
                $('#nama_customer_filter').val('');
                $('#id_customer_filter').val('');
              }
              ar_part.draw();
              h3_md_dealer_filter_ar_part_index_datatable.draw();
            }
          </script>
        </div>
        <table id="ar_part" class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class='align-middle'>No.</th>              
              <th class='align-middle'>Jenis Transaksi</th>              
              <th class='align-middle'>Nama Customer</th>              
              <th class='align-middle'>No. Referensi</th>              
              <th class='align-middle'>Tipe Referensi</th>              
              <th class='align-middle'>Tanggal Jatuh Tempo</th>              
              <th class='align-middle'>Nominal</th>              
              <th class='align-middle'>Total Pembayaran</th>              
              <th class='align-middle'>Sisa Piutang</th>              
              <th class='align-middle'>Status Pembayaran</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          ar_part = $('#ar_part').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/ar_part') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(data){
                  filter = {};
                  filter.no_referensi_filter = $('#no_referensi_filter').val();
                  filter.jenis_transaksi_filter = $('#jenis_transaksi_filter').val();
                  filter.tanggal_jatuh_tempo_filter_start = $('#tanggal_jatuh_tempo_filter_start').val();
                  filter.tanggal_jatuh_tempo_filter_end = $('#tanggal_jatuh_tempo_filter_end').val();
                  filter.id_customer_filter = $('#id_customer_filter').val();
                  filter.tanggal_batas_akhir_referensi = $('#tanggal_batas_akhir_referensi_value').val();
                  filter.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  filter.filetype = 'excel';

                  data = _.merge(data, filter);

                  queryStringFilter = new URLSearchParams(filter).toString();
                  $('#download-button').attr('href', 'h3/<?= $isi ?>/download?' + queryStringFilter);
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { 
                  data: 'jenis_transaksi',
                  render: function(data){
                    if(data == null) return 'Tidak ada data';

                    return data;
                  }
                }, 
                { data: 'nama_customer' }, 
                { data: 'referensi' }, 
                { 
                  data: 'tipe_referensi',
                  render: function(data){
                    if(data == 'faktur_penjualan'){
                      return 'Faktur Penjualan';
                    }else if(data == 'retur_pembelian_claim'){
                      return 'Retur Pembelian';
                    }else if(data == 'jawaban_claim_dealer'){
                      return 'Jawaban Claim Dealer';
                    }else{
                      return data;
                    }
                  }
                }, 
                { data: 'tanggal_jatuh_tempo' }, 
                { 
                  data: 'total_amount', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'sudah_dibayar', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'sisa_piutang', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { data: 'open_status_pembayaran', className: 'text-center' }, 
            ],
          });
        });
        function open_status_pembayaran (referensi){
          $('#referensi_open_status_pembayaran').val(referensi);
          $('#h3_md_open_status_pembayaran_ar_part').modal('show');
          h3_md_open_status_pembayaran_ar_part_datatable.draw();
        }
        </script>
        <?php $this->load->view('modal/h3_md_open_status_pembayaran_ar_part'); ?>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>