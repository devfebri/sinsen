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
    <?php if($set == 'form'): ?>
      <?php 
        $form     = '';
        $disabled = '';
        $readonly = '';
        if ($mode == 'insert') {
          $form = 'save';
        }elseif($mode == 'detail') {
          $disabled = 'disabled';
          $form= 'detail';
        }elseif($mode == 'edit') {
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
              <div v-if='supply_parts_melebihi_qty_avs.length > 0 && mode != "detail"' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Terdapat supply part yang melebihi kuantitas AVS.
              </div>
              <div v-if='parts_dengan_jumlah_pemenuhan_tidak_sinkron.length > 0 && mode != "detail"' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Terdapat pemenuhan part yang tidak sinkron dengan kuantitas NRFS.
              </div>
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. PO Logistik</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_po_logistik') }" class="col-sm-4">
                    <input v-model="po_logistik.id_po_logistik" type="text" class="form-control" readonly>
                    <small v-if="error_exist('id_po_logistik')" class="form-text text-danger">{{ get_error('id_po_logistik') }}</small>  
                  </div>
                  <label class="col-sm-2 control-label">Tgl. PO Logistik</label>
                  <div v-bind:class="{ 'has-error': error_exist('tanggal') }" class="col-sm-4">
                    <input v-model="po_logistik.tanggal" type="text" class="form-control" readonly>
                    <small v-if="error_exist('tanggal')" class="form-text text-danger">{{ get_error('tanggal') }}</small>  
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. PO AHM</label>
                  <div  class="col-sm-4">
                    <input v-if='po_logistik.id_purchase_order != null' v-model="po_logistik.id_purchase_order" type="text" class="form-control" readonly>
                    <input v-if='po_logistik.id_purchase_order == null' value='-' type="text" class="form-control" readonly>
                  </div>
                </div>
                <div class="container-fluid">
                  <table class="table table-condensed">
                    <tr>
                      <td width='3%'>No.</td>
                      <td>Kode Part</td>
                      <td>Part Deskripsi</td>
                      <td class='text-center'>HET</td>
                      <td width='8%'>Qty NRFS</td>
                      <td width='8%'>Qty On Hand</td>
                      <td width='8%'>Qty AVS</td>
                      <td width='8%'>Qty Supply</td>
                      <td width='8%'>Qty Book</td>
                      <td width='8%'>Qty PO AHM</td>
                    </tr>
                    <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                      <td class='align-middle'>{{ index + 1 }}.</td>
                      <td class='align-middle' @click.prevent='view_kode_part(index)'>{{ part.id_part }}</td>
                      <td class='align-middle'>{{ part.nama_part }}</td>
                      <td class='align-middle text-right'>
                        <vue-numeric read-only v-model='part.harga' separator='.' currency='Rp'></vue-numeric>
                      </td>
                      <td class='align-middle'>
                        <vue-numeric read-only v-model='part.qty_part' separator='.'></vue-numeric>
                      </td>
                      <td class='align-middle'>
                        <vue-numeric read-only v-model='part.qty_onhand' separator='.'></vue-numeric>
                      </td>
                      <td class='align-middle'>
                        <vue-numeric read-only v-model='part.qty_avs' separator='.'></vue-numeric>
                      </td>
                      <td class='align-middle'>
                        <vue-numeric class='form-control' :read-only='mode != "edit"' v-model='part.qty_supply' separator='.'></vue-numeric>
                      </td>
                      <td class='align-middle'>
                        <vue-numeric read-only v-model='part.qty_book' separator='.'></vue-numeric>
                      </td>
                      <td class='align-middle'>
                        <vue-numeric class='form-control' :read-only='mode != "edit"' v-model='part.qty_po_ahm' separator='.'></vue-numeric>
                      </td>
                    </tr>
                    <tr v-if='parts.length < 1'>
                      <td colspan='10'>Tidak ada data.</td>
                    </tr>
                  </table>
                </div>
                <?php $this->load->view('modal/h3_md_view_kode_part_po_logistik'); ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4 no-padding">
                  <a v-if='mode == "detail"' :href="'h3/h3_md_po_logistik/edit?id_po_logistik=' + po_logistik.id_po_logistik" class="btn btn-flat btn-sm btn-warning">Edit</a>
                  <button v-if='mode == "edit"' :disabled='supply_parts_melebihi_qty_avs.length > 0 || parts_dengan_jumlah_pemenuhan_tidak_sinkron.length > 0' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                </div>
                <div class="col-sm-4 no-padding text-center"></div>
                <div class="col-sm-4 no-padding text-right">
                   <a v-if='mode == "detail"' :href="'h3/h3_md_sales_order/add?id_po_logistik=' + po_logistik.id_po_logistik + '&generatePOLogistik=true'" class="btn btn-flat btn-sm btn-info">Generate SO</a>
                   <a v-if='mode == "detail" && po_logistik.id_purchase_order == null' :href="'h3/h3_md_purchase_order/add?id_po_logistik=' + po_logistik.id_po_logistik + '&generatePOLogistik=true'" class="btn btn-flat btn-sm btn-info">Generate PO Urgent</a>
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
            index_part: 0,
            mode: '<?= $mode ?>',
            po_logistik: <?= json_encode($po_logistik) ?>,
            parts: <?= json_encode($parts) ?>,
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.po_logistik, [
                'id_po_logistik'
              ]);
              post.parts = _.chain(this.parts)
              .map(function(part){
                return _.pick(part, [
                  'id_part', 'qty_part', 'harga', 'qty_supply', 'qty_po_ahm'
                ])
              })
              .value();

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function (res) {
                window.location = 'h3/<?= $isi ?>/detail?id_po_logistik=' + res.data.id_po_logistik;
              })
              .catch(function (err) {
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
            view_kode_part: function(index){
              this.index_part = index;
              h3_md_view_kode_part_po_logistik_datatable.draw();
              $('#h3_md_view_kode_part_po_logistik').modal('show');
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            supply_parts_melebihi_qty_avs: function(){
              // return [];
              return _.chain(this.parts)
              .filter(function(part){
                return parseInt(part.qty_supply) > parseInt(part.qty_avs);
              })
              .value();
            },
            parts_dengan_jumlah_pemenuhan_tidak_sinkron: function(){
              // return [];
              return _.chain(this.parts)
              .filter(function(part){
                return parseInt(part.qty_part) != ( parseInt(part.qty_supply) + parseInt(part.qty_book) + parseInt(part.qty_po_ahm) );
              })
              .value();
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($set=="index"): ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid no-padding" style='margin-top: 20px;'>
          <table id="po_logistik" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>              
                <th>PO Number Logistik</th>              
                <th>Tanggal PO</th>              
                <th>Jumlah Item</th>
                <th>Jumlah Pcs</th>
                <th>Total Amount</th>
                <th>Nilai DO</th>
                <th>Nilai Urgent</th>
                <th>S/R</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>    
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              po_logistik = $('#po_logistik').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  scrollX: true,
                  ajax: {
                    url: "<?= base_url('api/md/h3/po_logistik') ?>",
                    dataSrc: "data",
                    type: "POST",
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: 'index', orderable: false, width: '3%' },
                      { data: 'id_po_logistik', },
                      { data: 'tanggal' },
                      { data: 'jumlah_item', },
                      { data: 'jumlah_pcs' },
                      { 
                        data: 'total_amount',
                        render: function(data){
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        }
                      },
                      { 
                        data: 'nilai_do',
                        render: function(data){
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        }
                      },
                      { 
                        data: 'nilai_urgent',
                        render: function(data){
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        }
                      },
                      { 
                        data: 'service_rate',
                        render: function(data){
                          return accounting.formatMoney(data, "", 2, ".", ",") + '%';
                        }
                      },
                      { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                  ],
              });
            });
          </script>
          <?php $this->load->view('modal/h3_md_view_modal_po_logistik'); ?>
          <script>
          function view_modal_po_logistik(dokumen_nrfs_id) {
            url = 'iframe/md/h3/h3_md_po_logistik?dokumen_nrfs_id=' + dokumen_nrfs_id;
            $('#view_iframe_po_logistik').attr('src', url);
            $('#h3_md_view_modal_po_logistik').modal('show');
          }
          </script>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
