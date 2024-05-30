
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
<section class="content">
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" id="form_" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data Picking Slip</b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Picking Slip</label>
                  <div class="col-sm-4">
                      <input type="hidden" name="nomor_ps" :value="picking_slip.nomor_ps">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="picking_slip.nomor_ps" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="picking_slip.tanggal_ps" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" :value="picking_slip.nomor_so" disabled> 
                  </div>
                </div>
                <table class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td>Kuantitas Order</td>
                    <td>Satuan</td>
                    <td>Gudang</td>
                    <td>Rak</td>
                    <td>Return</td>
                    <td>Kuantitas Return</td>
                  </tr>
                  <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                    <td class="align-middle">{{ index + 1 }}.</td>
                    <td class="align-middle">
                      {{ part.id_part }}
                      <input type="hidden" name="id_part[]" :value="part.id_part">
                    </td>
                    <td class="align-middle">{{ part.nama_part }}</td>
                    <td>
                      <vue-numeric :read-only="true" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas" :empty-value="1"/>
                    </td>
                    <td class="align-middle">{{ part.satuan }}</td>
                    <td class="align-middle">{{ part.id_gudang }}</td>
                    <td class="align-middle">{{ part.id_rak }}</td>
                    <td class="align-middle">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='part.return'>
                    </td>
                    <td class="align-middle">
                      <vue-numeric v-if='part.return == 1' :max='part.kuantitas' :read-only="mode == 'detail'" class="form-control form-control-sm" thousand-separator="." v-model="part.kuantitas_return" :empty-value="1"/>
                    </td>
                  </tr>
                  <tr v-if="parts.length < 1">
                    <td colspan="8" class="text-center text-muted">Belum ada part</td>
                  </tr>
              </table>
              <hr>
              <table class="table" v-if='parts_ev.length > 0'>
                  <caption><b>SERIAL NUMBER</b></caption>
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td>Serial Number</td>
                    <td>Return</td>
                  </tr>
                  <tr v-if="parts_ev.length > 0" v-for="(part_ev, index2) of parts_ev">
                    <td class="align-middle">{{ index2 + 1 }}.</td>
                    <td hidden class="align-middle">{{ part_ev.id_sn_ev}}</td>
                    <td class="align-middle">
                      {{ part_ev.id_part }}
                      <input type="hidden" name="id_part[]" :value="part_ev.id_part">
                    </td>
                    <td class="align-middle">{{ part_ev.nama_part }}</td>
                    <td class="align-top" v-if='mode == "edit"'><input readonly @click.prevent="serialNumber(index2)" v-model='part_ev.serial_number' type="text" class="form-control" v-bind:class="{ 'has-error': error_exist('serial_number') }" > <small v-if="error_exist('serial_number')" class="form-text text-danger" :disabled='mode != "detail"' >{{ get_error('serial_number') }}</small> </td>
                    <td class="align-middle" v-else>{{ part_ev.serial_number }}</td>
                    <td class="align-middle">
                      <input :disabled='mode == "detail" || isReturnDisabled(part_ev.id_part)' type="checkbox" true-value='1' false-value='0' v-model='part_ev.is_return'>
                    </td>
                  </tr>
              </table>
              <?php $this->load->view('modal/serial_number_sales_order') ?>
              <script>
                function pilih_serial_number(serial_number, index2){
                  form_.parts_ev[index2].serial_number = serial_number.serial_number;
                  return false;
                }
              </script>
              <br>
              <div class="box-footer">
                <div class="row">
                  <div class="col-sm-6">
                    <a v-if='auth.can_print && mode == "detail"' :href="'dealer/h3_dealer_picking_slip/cetak?id=' + picking_slip.id"><button type="button" class="btn btn-flat btn-info btn-sm">Print</button></a>
                    <a v-if='auth.can_update && mode == "detail" && picking_slip.status == "Open"' :href="'dealer/h3_dealer_picking_slip/edit?id=' + picking_slip.id"><button type="button" class="btn btn-flat btn-warning btn-sm">Edit</button></a>
                    <button v-if='auth.can_update && mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='update'>Update</button>
                  </div>
                  <div class="col-sm-6 text-right">
                    <a v-if='auth.can_submit && picking_slip.status != "Process to NSC" && picking_slip.status != "Canceled" && picking_slip.status != "Closed" && picking_slip.wo_end == 1' :href="'dealer/h3_dealer_picking_slip/process_to_nsc?id=' + picking_slip.id"><button type="button" class="btn btn-flat btn-info btn-sm">Process to NSC</button></a>
                    <a v-if='picking_slip.status == "Process to NSC"' :href="'dealer/nsc/create_nsc'"><button type="button" class="btn btn-flat btn-info btn-sm">Create NSC</button></a>
                    <a v-if='auth.can_cancel && picking_slip.status != "Canceled" && picking_slip.status != "Closed" && picking_slip.wo_end == 1' :href="'dealer/h3_dealer_picking_slip/cancel?id=' + picking_slip.id"><button type="button" class="btn btn-flat btn-danger btn-sm">Cancel</button></a>
                    <a v-if='auth.can_close && picking_slip.status != "Closed" && picking_slip.status != "Canceled"' :href="'dealer/h3_dealer_picking_slip/close?id=' + picking_slip.id"><button type="button" class="btn btn-flat btn-danger btn-sm">Close</button></a>
                  </div>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        auth: <?= json_encode(get_user('h3_dealer_sales_order')) ?>, 
        mode : '<?= $mode ?>',
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        picking_slip: <?= json_encode($picking_slip) ?>,
        parts: <?= json_encode($picking_slip_parts) ?>,
        parts_ev: <?= json_encode($sales_order_ev) ?>,
        <?php else: ?>
        picking_slip: [],
        <?php endif; ?>
      },
      methods:{
        update: function(){
          post = {};
          post.id = this.picking_slip.id;
          post.nomor_ps = this.picking_slip.nomor_ps;
          post.nomor_so = this.picking_slip.nomor_so;
          post.parts = _.map(this.parts, function(p){
            return _.pick(p, ['id_part', 'id_gudang', 'id_rak', 'return', 'kuantitas_return','id_part_int']);
          });

          post.parts_ev = _.map(this.parts_ev, function(p){
            return _.pick(p, ['id_part', 'serial_number', 'is_return','id_part_int', 'id_sn_ev']);
          });

          axios.post('dealer/h3_dealer_picking_slip/update', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            toastr.error(err);
          });
        },
        ppnPerPart: function(part){
          return (10/100)* part.harga_md_dealer;
        },
        subTotal: function(part){
          return part.kuantitas * part.harga_md_dealer + this.ppnPerPart(part);
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        },
        isReturnDisabled(idPart) {
            const part = this.parts.find(part => part.id_part === idPart);
            if(part.return == 1){
              console.log("Ini return");
              return false;
            }else{
              console.log("Ini bukan return");
              return true;
            }
        },
        serialNumber: function(index2){
          this.indexPart = index2;
          $('#serial_number_sales_order').modal('show');
          datatable_serial_number_sales_order.draw();
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        totalInvoice: function(){
          totalInvoice = 0;
          for(part of this.parts){
            totalInvoice += this.subTotal(part);
          }
          return totalInvoice;
        }
      }
  });
</script>
    <?php } elseif ($set=="index") { ?>
    <div class="box">
      <div class="box-body">
        <table id="picking_slip" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width='3%'>No.</th>
              <th>Picking Slip</th>
              <th>Nomor SO</th>
              <th>Work Order</th>
              <th>Customer</th>
              <th>No. Plat</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th width='3%'>Action</th>
              <!-- <th width="10%">Action</th> -->
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
        $(document).ready(function() {
          picking_slip = $('#picking_slip').DataTable({
                initComplete: function() {
                  $('#picking_slip_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                  $('#picking_slip_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                  axios.get('html/filter_picking_slip')
                      .then(function(res) {
                          $('#picking_slip_filter').prepend(res.data);

                          $('#filter_status_picking_slip').change(function() {
                            picking_slip.draw();
                          });
                      });
                },
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/picking_slip') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(data){
                      data.filter_status_picking_slip = $('#filter_status_picking_slip').val();

                      start_date = $('#filter_picking_date_start').val();
                      end_date = $('#filter_picking_date_end').val();
                      if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                          data.filter_picking_date = true;
                          data.start_date = start_date;
                          data.end_date = end_date;
                      }
                    }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'nomor_ps' },
                    { data: 'nomor_so' },
                    { data: 'id_work_order' },
                    { data: 'nama_pembeli'},
                    { data: 'no_polisi'},
                    { data: 'tanggal_ps'},
                    { data: 'status', orderable: false, width: '3%' },
                    { data: 'action', width: '3%', orderable: false },
                ],
            });
          });
    </script>
    <?php
  }
    ?>
  </section>
</div>