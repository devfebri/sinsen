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
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled value='0'>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_monitor_plafon_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>  
                </div>
              </div>              
            </div>
            <div class="col-sm-1 col-sm-offset-6">
              <div class="form-group">
                <label for="" class="control-label invisible">Report</label>
                <a id='report_button' href="" class='hidden'>
                  <button class="btn btn-flat btn-primary">Report</button>
                </a>
              </div>
            </div>
            <div class="col-sm-1 no-padding">
              <div class="form-group text-right">
                <label for="" class="control-label invisible">Refresh</label>
                <button id='refresh_button' class="btn btn-flat btn-info"><i class="fa fa-refresh"></i> Refresh</button>
              </div>
            </div>
            <script>
              $(document).ready(function(){
                $('#refresh_button').click(function(){
                  monitor_plafon.draw();
                  get_plafon_dealer($('#id_customer_filter').val());
                  get_plafon_booking_dealer($('#id_customer_filter').val());
                });
              });
            </script>
          </div>
          <?php $this->load->view('modal/h3_md_dealer_filter_monitor_plafon_index'); ?>
          <script>
            function get_plafon_dealer(id_dealer){
              axios.get('h3/h3_md_monitor_plafon/get_plafon_dealer', {
                params: {
                  id_dealer: id_dealer
                }
              })
              .then(function(res){
                $('#plafon_dealer').text(
                  accounting.formatMoney(res.data.plafon, "Rp ", 0, ".", ",")
                );
              })
              .catch(function(err){
                $('#plafon_dealer').text(
                  accounting.formatMoney(0, "Rp ", 0, ".", ",")
                );
                toastr.error(err);
              });
            }

            function get_plafon_booking_dealer(id_dealer){
              axios.get('h3/h3_md_monitor_plafon/get_plafon_booking', {
                params: {
                  id_dealer: id_dealer
                }
              })
              .then(function(res){
                $('#plafon_booking').text(
                  accounting.formatMoney(res.data.plafon_booking, "Rp ", 0, ".", ",")
                );

                $('#sisa_plafon_dengan_booking').text(
                  accounting.formatMoney((res.data.plafon - res.data.plafon_booking), "Rp ", 0, ".", ",")
                );
              })
              .catch(function(err){
                $('#plafon_booking').text(
                  accounting.formatMoney(0, "Rp ", 0, ".", ",")
                );

                $('#sisa_plafon_dengan_booking').text(
                  accounting.formatMoney(0, "Rp ", 0, ".", ",")
                );
                toastr.error(err);
              });
            }

            function pilih_dealer_filter_monitor_plafon_index(data, type) {
              if(type == 'add_filter'){
                $('#nama_customer_filter').val(data.nama_dealer);
                $('#id_customer_filter').val(data.id_dealer);
                
                get_plafon_dealer(data.id_dealer);
                get_plafon_booking_dealer(data.id_dealer);

                $('#report_button').removeClass('hidden');
                $("#report_button").attr("href", '<?= base_url('h3/h3_md_monitor_plafon/report?id_dealer=') ?>' + data.id_dealer);
              }else if(type == 'reset_filter'){
                $('#nama_customer_filter').val('');
                $('#id_customer_filter').val(0);
                $('#report_button').addClass('hidden');
              }
              monitor_plafon.draw();
              h3_md_dealer_filter_monitor_plafon_index_datatable.draw();
            }
          </script>
        </div>
        <table id="monitor_plafon" class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class='align-middle'>No.</th>              
              <th class='align-middle'>No Faktur</th>              
              <th class='align-middle'>Jenis Pembelian</th>              
              <th class='align-middle'>Tanggal Faktur</th>              
              <th class='align-middle'>Tanggal Jatuh Tempo</th>              
              <th class='align-middle'>Amount</th>              
              <th class='align-middle'>Pembayaran</th>              
              <th class='align-middle'>Sisa Piutang</th>              
              <th class='align-middle'>Keterangan</th>              
              <th class='align-middle'>Sisa Plafon</th>              
            </tr>
          </thead>
          <thead>
            <tr>
              <td colspan='9'>Plafon</td>
              <td id='plafon_dealer' class='text-right'>Rp 0</td>
            </tr>
            <tr>
              <td colspan='8'>Total DO Pending</td>
              <td id='plafon_booking' class='text-right'>Rp 0</td>
              <td id='sisa_plafon_dengan_booking' class='text-right'>Rp 0</td>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan='5'>Total</td>
              <td class='text-right' id='total_amount'>Rp 0</td>
              <td class='text-right' id='total_pembayaran'>Rp 0</td>
              <td class='text-right' id='total_sisa_piutang'>Rp 0</td>
              <td colspan='2'></td>
            </tr>
          </tfoot>
        </table>
        <script>
        $(document).ready(function(){
          monitor_plafon = $('#monitor_plafon').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            // lengthChange: false,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/monitor_plafon') ?>",
                dataSrc: function(json){
                  total_amount = _.chain(json.data)
                  .sumBy(function(data){
                    return parseFloat(data.nominal);
                  })
                  .value();
                  $('#total_amount').text(
                    accounting.formatMoney(total_amount, "Rp ", 0, ".", ",")
                  );

                  total_pembayaran = _.chain(json.data)
                  .sumBy(function(data){
                    return parseFloat(data.nominal_pembayaran_faktur);
                  })
                  .value();
                  $('#total_pembayaran').text(
                    accounting.formatMoney(total_pembayaran, "Rp ", 0, ".", ",")
                  );

                  total_sisa_piutang = _.chain(json.data)
                  .sumBy(function(data){
                    return parseFloat(data.sisa_piutang);
                  })
                  .value();
                  $('#total_sisa_piutang').text(
                    accounting.formatMoney(total_sisa_piutang, "Rp ", 0, ".", ",")
                  );

                  return json.data;
                },
                type: "POST",
                data: function(d){
                  d.id_customer_filter = $('#id_customer_filter').val();
                }
            },
            columns: [
                { data: null, orderable: false, width: '3%' }, 
                { data: 'no_faktur', width: '180px' }, 
                { data: 'produk', width: '50px' }, 
                { data: 'tgl_faktur' }, 
                { data: 'tgl_jatuh_tempo'}, 
                { 
                  data: 'nominal', 
                  className: 'text-right', 
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'nominal_pembayaran_faktur', 
                  className: 'text-right',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'sisa_piutang',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  },
                  className: 'text-right'
                },
                { data: 'open_keterangan', width: '250px', className: 'text-center', orderable: false },
                { 
                  data: 'plafon',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  },
                  className: 'text-right'
                }, 
            ],
          });
          monitor_plafon.on('draw.dt', function() {
            var info = monitor_plafon.page.info();
            monitor_plafon.column(0, {
                search: 'applied',
                order: 'applied',
                page: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start + ".";
            });
          });
        });
        function open_keterangan_monitor_plafon(no_faktur){
          $('#no_faktur_keterangan_monitor_plafon').val(no_faktur);
          $('#h3_md_open_keterangan_monitor_plafon').modal('show');
          h3_md_open_keterangan_monitor_plafon_datatable.draw();
        }
        </script>
        <?php $this->load->view('modal/h3_md_open_keterangan_monitor_plafon'); ?>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>