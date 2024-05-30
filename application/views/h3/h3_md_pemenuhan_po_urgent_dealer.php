<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/sweet_alert.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<base href="<?php echo base_url(); ?>" />

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <?= $breadcrumb ?>
    </section>
    <section class="content">
      <?php if ($set == 'form') : ?>
        <?php
        $form     = '';
        $disabled = '';
        $readonly = '';
        if ($mode == 'insert') {
          $form = 'save';
        }
        if ($mode == 'detail') {
          $form = 'detail';
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
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal">
                  <div v-if='qty_avs_tidak_memenuhi.length > 0 && mode != "detail"' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> Terdapat pemenuhan yang melebihi kuantitas AVS.
                  </div>
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.po_id' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Tanggal PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.tanggal_order' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Kode Customer</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.kode_dealer_md' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Customer</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.nama_dealer' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Kategori PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.kategori_po' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Produk</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.produk' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <table id="table" class="table table-condensed table-responsive table-bordered">
                          <thead>
                            <tr class='bg-blue-gradient'>
                              <th width='3%'>No.</th>
                              <th>Part Number</th>
                              <th>Nama Part</th>
                              <th width="10%">Qty Order</th>
                              <th width="5%">Qty PO AHM</th>
                              <th width="5%">Qty Penerimaan</th>
                              <th width="5%">Qty SO</th>
                              <th width="5%">Qty Terpenuhi</th>
                              <th width="5%">Qty Supply</th>
                              <th width="5%">Qty Sisa</th>
                              <th width="10%">Qty AVS</th>
                              <th width="10%">Qty Pemenuhan</th>
                              <!-- <th width="10%">Qty Hotline</th> -->
                              <th width="10%">Qty Urgent</th>
                              <th width="10%" class='text-right'>Total Harga</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(part, index) in parts">
                              <td class="align-middle" width='3%'>{{ index + 1 }}.</td>
                              <td class="align-middle" @click='detail_logistik(index)'>{{ part.id_part }}</td>
                              <td class="align-middle">{{ part.nama_part }}</td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                              </td>
                              <td class="align-middle" @click.prevent='open_qty_po(index)'>
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_po"></vue-numeric>
                              </td>
                              <td class="align-middle" @click.prevent='open_qty_penerimaan(index)'>
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_penerimaan"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_so"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_fulfillment"></vue-numeric>
                              </td>
                              <td class="align-middle" @click.prevent='open_qty_supply(index)'>
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_supply"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_belum_terpenuhi"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_pemenuhan"></vue-numeric>
                              </td>
                              <!-- <td class="align-middle">
                            <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_hotline"></vue-numeric>
                          </td>  -->
                              <td class="align-middle">
                                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_urgent"></vue-numeric>
                              </td>
                              <td width="8%" class="align-middle text-right">
                                <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                              </td>
                            </tr>
                            <tr v-if="parts.length < 1">
                              <td class="text-center" colspan="16">Belum ada part</td>
                            </tr>
                          </tbody>
                        </table>
                        <?php $this->load->view('modal/h3_md_detail_urgent_po_logistik'); ?>
                        <?php $this->load->view('modal/h3_md_qty_po_pemenuhan_po_dealer'); ?>
                        <?php $this->load->view('modal/h3_md_qty_penerimaan_pemenuhan_po_dealer'); ?>
                        <?php $this->load->view('modal/h3_md_qty_supply_pemenuhan_po_dealer'); ?>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <a v-if='mode == "detail"' :href="'h3/h3_md_pemenuhan_po_urgent_dealer/edit?id=' + purchase.po_id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                      <button v-if='mode == "edit"' :disabled='qty_pemecahan_tidak_sinkron.length > 0 || qty_avs_tidak_memenuhi.length > 0' class="btn btn-flat btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                    </div>
                    <div class="col-sm-6 text-right no-padding">
                      <a v-if='mode == "detail"' :href="'h3/h3_md_sales_order/add?generateByPO=true&po_id=' + purchase.po_id"  :class='{ "disabled": tidak_boleh_proses_so }' class="btn btn-flat btn-sm btn-info" role='button'>
                        Create SO
                      </a>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          app = new Vue({
            el: '#app',
            data: {
              loading: false,
              mode: '<?= $mode ?>',
              index_part: 0,
              purchase: <?= json_encode($purchase) ?>,
              parts: <?= json_encode($parts) ?>,
            },
            methods: {
              <?= $form ?>: function() {
                this.loading = true;
                post = _.pick(this.purchase, ['po_id']);
                post.parts = _.chain(this.parts)
                  .map(function(data) {
                    return _.pick(data, ['id_part', 'qty_pemenuhan', 'qty_hotline', 'qty_urgent']);
                  })
                  .value();

                axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
                  .then(function(res) {
                    window.location = 'h3/h3_md_pemenuhan_po_urgent_dealer/detail?id=' + res.data.po_id;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              open_qty_po: function(index) {
                this.index_part = index;
                h3_md_qty_po_pemenuhan_po_dealer_datatable.draw();
                $('#h3_md_qty_po_pemenuhan_po_dealer').modal('show');
              },
              open_qty_penerimaan: function(index) {
                this.index_part = index;
                h3_md_qty_penerimaan_pemenuhan_po_dealer_datatable.draw();
                $('#h3_md_qty_penerimaan_pemenuhan_po_dealer').modal('show');
              },
              open_qty_supply: function(index) {
                this.index_part = index;
                h3_md_qty_supply_pemenuhan_po_dealer_datatable.draw();
                $('#h3_md_qty_supply_pemenuhan_po_dealer').modal('show');
              },
              detail_logistik: function(index) {
                if (this.purchase.po_logistik == 0) return;

                this.index_part = index;
                h3_md_detail_urgent_po_logistik_datatable.draw();
                $('#h3_md_detail_urgent_po_logistik').modal('show');
              },
              sub_total: function(part) {
                return (part.qty_order * part.harga);
              },
            },
            computed: {
              qty_pemecahan_tidak_sinkron: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    return part.qty_belum_terpenuhi != (parseInt(part.qty_pemenuhan) + parseInt(part.qty_hotline) + parseInt(part.qty_urgent))
                  })
                  .value();
              },
              terdapat_qty_pemenuhan: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    return parseInt(part.qty_pemenuhan) > 0;
                  })
                  .value();
              },
              qty_avs_tidak_memenuhi: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    return parseInt(part.qty_pemenuhan) > parseInt(part.qty_avs);
                  })
                  .value();
              },
              tidak_boleh_proses_so: function(){
                return this.terdapat_qty_pemenuhan.length < 1 || this.qty_pemecahan_tidak_sinkron.length > 0;
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($mode == "index") : ?>
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
            </h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="container-fluid no-padding">
              <div class="row">
                <div class="col-sm-3">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label class="control-label">Tanggal PO</label>
                        <input id='tanggal_po_filter' type="text" class="form-control" readonly>
                        <input id='tanggal_po_filter_start' type="hidden" disabled>
                        <input id='tanggal_po_filter_end' type="hidden" disabled>
                      </div>                
                      <script>
                        $('#tanggal_po_filter').daterangepicker({
                          opens: 'left',
                          autoUpdateInput: false,
                          locale: {
                            format: 'DD/MM/YYYY'
                          }
                        }).on('apply.daterangepicker', function(ev, picker) {
                          $('#tanggal_po_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                          $('#tanggal_po_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                          $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                          pemenuhan_po_dealer.draw();
                        }).on('cancel.daterangepicker', function(ev, picker) {
                          $(this).val('');
                          $('#tanggal_po_filter_start').val('');
                          $('#tanggal_po_filter_end').val('');
                          pemenuhan_po_dealer.draw();
                        });
                      </script>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="row">
                    <div id='dealer_filter' class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">Dealer</label>
                        <div class="input-group">
                          <input :value='filters.length + " dealer"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button type='button' class="btn btn-flat btn-primary" data-toggle='modal' data-target='#h3_md_dealer_filter_pemenuhan_po_dealer'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_filter_pemenuhan_po_dealer'); ?>
                    <script>
                        dealer_filter = new Vue({
                            el: '#dealer_filter',
                            data: {
                                filters: []
                            },
                            watch: {
                              filters: function(){
                                pemenuhan_po_dealer.draw();
                              }
                            }
                        });

                        $("#h3_md_dealer_filter_pemenuhan_po_dealer").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_dealer = target.attr('data-id-dealer');

                          if(target.is(':checked')){
                            dealer_filter.filters.push(id_dealer);
                          }else{
                            index_dealer = _.indexOf(dealer_filter.filters, id_dealer);
                            dealer_filter.filters.splice(index_dealer, 1);
                          }
                          h3_md_dealer_filter_pemenuhan_po_dealer_datatable.draw();
                        });
                    </script>
                  </div>
                </div>
              </div>
            </div>
            <table id="pemenuhan_po_dealer" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                  <th>No.</th>
                  <th>PO Number</th>
                  <th>Tanggal PO</th>
                  <th>Tanggal PO MD</th>
                  <th>Tanggal PO AHM</th>
                  <th>Dealer</th>
                  <th>Amount</th>
                  <th>Amount Supply</th>
                  <th>SR (%)</th>
                  <th>Status</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <script>
              $(document).ready(function() {
                pemenuhan_po_dealer = $('#pemenuhan_po_dealer').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/md/h3/pemenuhan_po_urgent_dealer') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                      d.tanggal_po_filter_start = $('#tanggal_po_filter_start').val();
                      d.tanggal_po_filter_end = $('#tanggal_po_filter_end').val();
                      d.dealer_filter = dealer_filter.filters;
                      d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    }
                  },
                  createdRow: function(row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [{
                      data: 'index',
                      orderable: false,
                      width: '3%'
                    },
                    {
                      data: 'po_id'
                    },
                    {
                      data: 'tanggal_order'
                    },
                    {
                      data: 'tanggal_po_md',
                      render: function(data) {
                        if(data != null){
                          return moment(data).format("DD/MM/YYYY");
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'tanggal_po_ahm',
                      render: function(data) {
                        if(data != null){
                          return moment(data).format("DD/MM/YYYY");
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'nama_dealer'
                    },
                    {
                      data: 'total_amount',
                      width: '10%',
                      className: 'text-right',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    {
                      data: 'amount_supply_md',
                      width: '10%',
                      className: 'text-right',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    {
                      data: 'service_rate',
                      width: '5%',
                      className: 'text-right',
                      render: function(data){
                        return accounting.format(data, 2, ',', '.') + '%';
                      }
                    },
                    {
                      data: 'status_md',
                      render: function(data){
                        if(data != null) return data;
                        return '-';
                      }
                    },
                    {
                      data: 'action',
                      width: '3%',
                      orderable: false,
                    },
                  ],
                });
              });
            </script>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php endif; ?>
    </section>
  </div>