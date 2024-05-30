<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
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
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Penerimaan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.referensi'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.tanggal_penerimaan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.no_surat_jalan_ekspedisi'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Surat Jalan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.tgl_surat_jalan_ekspedisi'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.nama_ekspedisi'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No Polisi Ekspedisi</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.no_plat'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Supir</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.nama_driver'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Satuan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.per_satuan_ongkos_angkut_part'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Ongkos Angkut</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" disabled v-model='invoice_ekspedisi.harga_ongkos_angkut_part' separator='.' currency='Rp'></vue-numeric>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Perhitungan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='invoice_ekspedisi.jenis_ongkos_angkut_part'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Total Qty</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" disabled v-model='total_qty' separator='.'></vue-numeric>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total Berat</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" :disabled='mode == "detail"' v-model='invoice_ekspedisi.berat_truk' precision='2'></vue-numeric>
                    </div>
                  </div>
                </div>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-condensed">
                        <tr>
                          <td width='3%'>No.</td>
                          <td>Part Number</td>
                          <td>Nama Part</td>
                          <td>Qty Order</td>
                          <td>Qty Received</td>
                        </tr>
                        <tr v-if='items.length > 0' v-for='(item, index) of items'>
                          <td>{{ index + 1 }}.</td>
                          <td>{{ item.id_part }}</td>
                          <td>{{ item.nama_part }}</td>
                          <td>
                            <vue-numeric read-only class="form-control" separator='.' v-model='item.qty_order'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only class="form-control" separator='.' v-model='item.qty_diterima'></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='items.length > 0'>
                          <td colspan='4' class='text-right'>Total</td>
                          <td class='text-right'>
                            <vue-numeric v-model='total' currency='Rp' separator='.' read-only></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='items.length > 0'>
                          <td colspan='4' class='text-right align-middle'>Diskon</td>
                          <td class='text-right'>
                            <vue-numeric :read-only='mode == "detail"' v-model='invoice_ekspedisi.diskon' currency='Rp' separator='.' class='form-control'></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='items.length > 0'>
                          <td colspan='4' class='text-right align-middle'>Potong Tagihan</td>
                          <td class='text-right'>
                            <vue-numeric :read-only='mode == "detail"' v-model='invoice_ekspedisi.potongan_tagihan' currency='Rp' separator='.' class='form-control'></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='items.length > 0'>
                          <td colspan='4' class='text-right align-middle'>PPN</td>
                          <td class='text-right'>
                            <vue-numeric v-model='ppn' currency='Rp' separator='.' read-only></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='items.length > 0'>
                          <td colspan='4' class='text-right align-middle'>Grand Total</td>
                          <td class='text-right'>
                            <vue-numeric v-model='grand_total' currency='Rp' separator='.' read-only></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='items.length < 1'>
                          <td colspan='5' class='text-center'>Tidak ada data.</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <a v-if='mode == "detail" && invoice_ekspedisi.status == "Open"' :href="'h3/h3_md_invoice_ekspedisi/edit?id=' + invoice_ekspedisi.id" class="btn btn-flat btn-warning btn-sm">Edit</a>
                      <button v-if='mode == "edit"' class="btn-flat btn btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                    </div>
                    <div class="col-sm-6 text-right">
                      <button v-if='mode == "detail" && invoice_ekspedisi.status == "Open"' class="btn-flat btn btn-success btn-sm" @click.prevent='proses'>Proses</button>
                      <button v-if='mode == "detail" && invoice_ekspedisi.status == "Open"' class="btn-flat btn btn-danger btn-sm" type='button' data-toggle='modal' data-target='#reject_modal'>Reject</button>
                    </div>
                    <div id="reject_modal" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" id="alasan_reject" rows='5'></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
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
        invoice_ekspedisi: <?= json_encode($invoice_ekspedisi) ?>,
        items: <?= json_encode($items) ?>,
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.invoice_ekspedisi, [
            'id', 'diskon', 'potongan_tagihan', 'berat_truk'
          ]);
          post.dpp = this.total;
          post.ppn = this.ppn;
          post.grand_total = this.grand_total;

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
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
        proses: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/proses', {
            params: {
              id: this.invoice_ekspedisi.id
            }
          })
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          });
        },
        reject: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/reject', {
            params: {
              id: this.invoice_ekspedisi.id,
              alasan_reject: $('#alasan_reject').val()
            }
          })
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          });
        },
        get_jumlah_dus: function(data){
          return data.qty_diterima/data.qty_dus;
        },
        get_berat_dus: function(data){
          return 1;
        },
        get_total_berat: function(data){
          return this.get_jumlah_dus(data) * this.get_berat_dus(data);
        },
        get_total_dpp: function(data){
          return this.get_jumlah_dus(data) * data.harga_ongkos_angkut_part;
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        total_qty: function(){
          return _.chain(this.items)
          .sumBy(function(item){
            return item.qty_diterima;
          })
          .value();
        },
        total: function(){
          return this.invoice_ekspedisi.harga_ongkos_angkut_part * (this.invoice_ekspedisi.berat_truk/this.invoice_ekspedisi.per_satuan_ongkos_angkut_part);
        },
        ppn: function(){
          return this.total * (this.invoice_ekspedisi.ppn_ekspedisi/100);
        },
        grand_total: function(){
          return (this.total + this.ppn) - this.invoice_ekspedisi.diskon - this.invoice_ekspedisi.potongan_tagihan;
        },
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
        <div class="container-fluid" style='margin-bottom: 20px;'>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">No. Surat Jalan</label>
                <input id='no_surat_jalan_filter' type="text" class="form-control">
              </div>
            </div>
            <script>
              $(document).ready(function(){
                $('#no_surat_jalan_filter').on('keyup', _.debounce(function(e){
                  invoice_ekspedisi.draw();
                }, 300))
              });
            </script>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Nama Ekspedisi</label>
                <div class="input-group">
                  <input id='id_ekspedisi_filter' type="hidden">
                  <input id='ekspedisi_filter' type="text" class="form-control" readonly>
                  <div class="input-group-btn">
                    <button id='choose_ekspedisi_filter' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_filter_invoice_ekspedisi'><i class="fa fa-search"></i></button>
                    <button id='reset_ekspedisi_filter' class="btn btn-flat btn-danger hide"><i class ="fa fa-trash-o"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php $this->load->view('modal/h3_md_ekspedisi_filter_invoice_ekspedisi'); ?>
            <script>
              function pilih_ekspedisi_filter_invoice_ekspedisi(data) {
                $('#id_ekspedisi_filter').val(data.id);
                $('#ekspedisi_filter').val(data.nama_ekspedisi);

                $('#choose_ekspedisi_filter').addClass('hide');
                $('#reset_ekspedisi_filter').removeClass('hide');

                invoice_ekspedisi.draw();
              }

              $(document).ready(function(){
                $('#reset_ekspedisi_filter').on('click', function(e){
                  e.preventDefault();

                  $('#id_ekspedisi_filter').val('');
                  $('#ekspedisi_filter').val('');

                  $('#choose_ekspedisi_filter').removeClass('hide');
                  $('#reset_ekspedisi_filter').addClass('hide');

                  invoice_ekspedisi.draw();
                });
              });
            </script>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Periode Invoice</label>
                <input id='periode_invoice_filter' type="text" class="form-control" readonly>
                <input id='periode_invoice_filter_start' type="hidden" disabled>
                <input id='periode_invoice_filter_end' type="hidden" disabled>
              </div>
            </div>
            <script>
              $('#periode_invoice_filter').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              }).on('apply.daterangepicker', function(ev, picker) {
                $('#periode_invoice_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                $('#periode_invoice_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));

                invoice_ekspedisi.draw();
              }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#periode_invoice_filter_start').val('');
                $('#periode_invoice_filter_end').val('');
                invoice_ekspedisi.draw();
              });
            </script>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">No. Penerimaan</label>
                <input id='no_penerimaan_filter' type="text" class="form-control">
              </div>
            </div>
            <script>
              $(document).ready(function(){
                $('#no_penerimaan_filter').on('keyup', _.debounce(function(e){
                  invoice_ekspedisi.draw();
                }, 300))
              });
            </script>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <label for="" class="control-label">Jenis Satuan Ongkos Angkut</label>
              <select id='filter_jenis_satuan_ongkos_angkut' class='form-control'>
                <option value="">All</option>
                <option value="Berat">Kg</option>
                <option value="Volume">M3</option>
                <option value="Truk">Truk</option>
              </select>
            </div>
            <script>
              $(document).ready(function(){
                $('#filter_jenis_satuan_ongkos_angkut').on('change', function(e){
                  invoice_ekspedisi.draw();
                })
              });
            </script>
          </div>
        </div>
        <table id="invoice_ekspedisi" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>No Invoice</th>
              <th>Tanggal Invoice</th>
              <th>No Penerimaan</th>
              <th>Tanggal Penerimaan</th>
              <th>Ekspedisi</th>
              <th>No Surat Jalan Ekspedisi</th>
              <th>Jumlah</th>
              <th>Total Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            invoice_ekspedisi = $('#invoice_ekspedisi').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/invoice_ekspedisi') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.no_surat_jalan_filter = $('#no_surat_jalan_filter').val();
                    d.id_ekspedisi_filter = $('#id_ekspedisi_filter').val();
                    d.periode_invoice_filter_start = $('#periode_invoice_filter_start').val();
                    d.periode_invoice_filter_end = $('#periode_invoice_filter_end').val();
                    d.no_penerimaan_filter = $('#no_penerimaan_filter').val();
                    d.filter_jenis_satuan_ongkos_angkut = $('#filter_jenis_satuan_ongkos_angkut').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'no_invoice_ekspedisi' },
                    { data: 'tanggal_invoice', name: 'ie.created_at' },
                    { data: 'referensi' },
                    { data: 'tanggal_penerimaan', name: 'pb.created_at' },
                    { data: 'nama_ekspedisi' },
                    { data: 'no_surat_jalan_ekspedisi' },
                    { data: 'berat_truk' },
                    { 
                      data: 'grand_total',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      }
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>