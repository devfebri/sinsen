<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script> 
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/vue/custom/vb-rangedatepicker.js") ?>"></script> 
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
      </div>
      <!-- /.box-header -->
      <div v-if="loading" class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
          <div class="row">
              <div class="col-md-12">
                  <form class="form-horizontal">
                      <div class="box-body">
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Kode Part</label>
                              <div class="col-sm-4">
                                  <input type="text" readonly class="form-control" v-model="kartu_stok.id_part" />
                              </div>
                              <label class="col-sm-2 control-label">Gudang</label>
                              <div class="col-sm-4">
                                  <input type="text" readonly class="form-control" v-model="kartu_stok.kode_gudang" />
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Nama Part</label>
                              <div class="col-sm-4">
                                  <input type="text" readonly class="form-control" v-model="kartu_stok.nama_part" />
                              </div>
                              <label class="col-sm-2 control-label">Lokasi</label>
                              <div class="col-sm-4">
                                  <input type="text" readonly class="form-control" v-model="kartu_stok.kode_lokasi_rak" />
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Periode</label>
                              <div class="col-sm-4">
                                <range-date-picker :config='config' class='form-control' @apply-date='applyDatePeriode' @cancel-date='cancelDatePeriode' readonly></range-date-picker>
                              </div>
                          </div>
                          <div class="container-fluid bg-blue" style='padding: 5px 0; margin-bottom: 15px;'>
                            <div class="col-sm-12 text-center">
                              <span class="text-bold">DETAIL STOK</span>
                            </div>
                          </div>
                          <table id='detail_stock' class="table table-condensed">
                            <thead>
                              <tr>
                                <td width='3%'>No.</td>
                                <td>Tanggal</td>
                                <td>Keterangan</td>
                                <td class='text-center'>Qty Masuk</td>
                                <td class='text-center'>Qty Keluar</td>
                                <td class='text-center'>Stok</td>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th colspan="5" style="text-align:right">Total Stock:</th>
                                <th>0</th>
                              </tr>
                            </tfoot>
                          </table>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer"></div>
                      <!-- /.box-footer -->
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
          kartu_stok: <?= json_encode($kartu_stok) ?>,
          detail_stock: [],
          periode_start: '',
          periode_end: '',
          config: {
            opens: 'left',
            autoUpdateInput: false,
            locale: {
              format: 'DD/MM/YYYY'
            }
          }
        },
        methods: {
          applyDatePeriode: function(picker){
            this.periode_start = picker.startDate.format('YYYY-MM-DD');
            this.periode_end = picker.endDate.format('YYYY-MM-DD');
            detail_stock.draw();
          },
          cancelDatePeriode: function(picker){
            this.periode_start = '';
            this.periode_end = '';
            detail_stock.draw();
          },
        },
        mounted: function(){
          $(document).ready(function(){
            detail_stock = $('#detail_stock').DataTable({
              processing: true,
              serverSide: true,
              searching: false,
              ordering: false,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/detail_stock_kartu_stok') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.id_part = app.kartu_stok.id_part;
                    d.id_lokasi_rak = app.kartu_stok.id_lokasi_rak;
                    d.periode_start = app.periode_start;
                    d.periode_end = app.periode_end;
                  }
              },
              footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;

                axios.post('<?= base_url('api/md/h3/detail_stock_kartu_stok/get_total_stock') ?>', Qs.stringify({
                  id_part : app.kartu_stok.id_part,
                  id_lokasi_rak : app.kartu_stok.id_lokasi_rak,
                }))
                .then(function(res){
                  $( api.column( 5 ).footer() ).html(accounting.formatMoney(res.data, '', 0, ".", ","));
                });
              },
              columns: [
                  { data: 'index', orderable: false, width: '3%' }, 
                  { 
                    data: 'created_at',
                    render: function(data){
                      return moment(data).format("DD/MM/YYYY HH:mm:ss")
                    }
                  },
                  { data: 'keterangan',},
                  { 
                    data: 'qty_masuk',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    className: 'text-right'
                  },
                  { 
                    data: 'qty_keluar',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    className: 'text-right'
                  },
                  { 
                    data: 'stok',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    className: 'text-right'
                  },
              ],
            });
          });
        }
      });
    </script>
    <?php $this->load->view('modal/h3_md_view_modal_packing_sheet_ahm_on_kartu_stock'); ?>
    <script>
    function open_view_packing_sheet_ahm(keterangan, nomor_karton) {
      query_string_nomor_karton = $.param({
        nomor_karton: nomor_karton
      });
      url = 'iframe/md/h3/h3_md_packing_sheet_ahm?packing_sheet_number=' + keterangan;

      if(nomor_karton.length > 0){
        url += '&' + query_string_nomor_karton;
      }

      $('#view_iframe_packing_sheet_ahm').attr('src', url);
      $('#h3_md_view_modal_packing_sheet_ahm_on_kartu_stock').modal('show');
    }
    </script>
    <?php $this->load->view('modal/h3_md_rincian_faktur_kartu_stock'); ?>
    <script>
    function open_view_create_faktur(keterangan) {
      url = 'iframe/md/h3/h3_md_faktur?id_do_sales_order=' + keterangan;
      $('#view_iframe_faktur').attr('src', url);
      $('#h3_md_rincian_faktur_kartu_stock').modal('show');
    }
    </script>
    <?php $this->load->view('modal/h3_md_penerimaan_po_vendor_kartu_stock'); ?>
    <script>
    function open_view_penerimaan_po_vendor(keterangan) {
      url = 'iframe/md/h3/h3_md_penerimaan_po_vendor?id_penerimaan_po_vendor=' + keterangan;
      $('#view_iframe_penerimaan_po_vendor').attr('src', url);
      $('#h3_md_penerimaan_po_vendor_kartu_stock').modal('show');
    }
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <table id="kartu_stok" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Gudang</th>              
              <th>Lokasi</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>    
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            kartu_stok = $('#kartu_stok').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/md/h3/kartu_stok') ?>",
                    dataSrc: "data",
                    type: "POST"
                },
                columns: [
                    { data: null, orderable: false, width: '3%' }, 
                    { data: 'id_part' },
                    { data: 'nama_part' },
                    { data: 'kode_gudang' },
                    { data: 'kode_lokasi_rak' },
                    { data: 'action', orderable: false, width: '3%', className: 'text-center' },
                ],
            });

            kartu_stok.on('draw.dt', function() {
              var info = kartu_stok.page.info();
              kartu_stok.column(0, {
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