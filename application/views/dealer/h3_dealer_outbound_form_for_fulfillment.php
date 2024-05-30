<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?php echo $breadcrumb ?>
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
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= base_url("dealer/{$isi}") ?>">
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
              <form  class="form-horizontal">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <label class="col-sm-2 control-label">ID Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.id_event" data-toggle="modal" data-target="#event_outbound_fulfillment" placeholder="Klik untuk memilih event ...">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                  <label class="col-sm-2 control-label">Nama Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.nama" data-toggle="modal" data-target="#modal-event">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                </div>
                <?php $this->load->view('modal/event_outbound_fulfillment') ?>
                <script>
                  function pilih_event_outbound_fulfillment(event){
                    form_.event = event;
                  }
                </script>
                <div class="form-group">
                  <label class="col-sm-2 control-label">PIC Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.nama_pic">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.start_date" data-toggle="modal" data-target="#modal-event">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Lokasi Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.lokasi_event" data-toggle="modal" data-target="#modal-event">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.end_date" data-toggle="modal" data-target="#modal-event">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Event</label>
                  <div v-bind:class="{ 'has-error': errors.id_event != null }" class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="event.deskripsi" data-toggle="modal" data-target="#modal-event">
                    <small v-if='errors.id_event != null' class="form-text text-danger">{{ errors.id_event }}</small>
                  </div>
                  <div v-if='mode == "detail" || mode == "edit"'>
                    <label class="col-sm-2 control-label">Tanggal Request</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model="outbound_fulfillment.tanggal_request" data-toggle="modal" data-target="#modal-event">
                    </div>
                  </div>
                </div>
                <div v-if='mode == "detail" || mode == "edit"' class="form-group">
                  <label class="col-sm-2 control-label">ID Outbound Form for Fulfillment</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="outbound_fulfillment.id_outbound_form_for_fulfillment">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Transit</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="outbound_fulfillment.tanggal_transit" data-toggle="modal" data-target="#modal-event">
                  </div>
                </div>
                <div v-if='mode == "detail" || mode == "edit"' class="form-group">
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="outbound_fulfillment.status">
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Closed</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model="outbound_fulfillment.tanggal_closed" data-toggle="modal" data-target="#modal-event">
                  </div>
                </div>
                <table class="table table-striped table-condensed">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td width="10%">Stock AVS</td>
                    <td>Gudang Asal</td>
                    <td>Rak Asal</td>
                    <td width="10%">Qty Asal</td>
                    <td width="10%">Qty Transfer</td>
                    <td width="10%">Satuan</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="true" class="form-control form-control-sm" thousand-separator="." v-model="part.stock_avs" :empty-value="1"/>
                      </td>
                      <td class="align-middle">
                        {{ part.id_gudang }}
                      </td>
                      <td class="align-middle">
                        {{ part.id_rak }}
                      </td>
                      <td class="align-middle">
                        {{ part.stock }}
                      </td>
                      <td class="align-middle">
                        {{ part.kuantitas }}
                      </td>
                      <td class="align-middle">
                        {{ part.satuan }}
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="6" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Simpan</button>
                  <button v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                </div>
                <div class="col-sm-6 text-right no-padding">
                  <a v-if='auth.can_print && mode == "detail" && outbound_fulfillment.status != "Open"' :href="'dealer/h3_dealer_outbound_form_for_fulfillment/cetak?k=' + outbound_fulfillment.id_outbound_form_for_fulfillment" class="btn btn-flat btn-primary btn-sm">Cetak Surat Jalan</a>
                  <button v-if='auth.can_transit && mode == "detail" && outbound_fulfillment.status == "Open"' @click.prevent='transit' class="btn btn-flat btn-sm btn-info" type='button'>Transit</button>
                  <button v-if='auth.can_close && mode == "detail" && outbound_fulfillment.status == "In Transit"' @click.prevent='close' class="btn btn-flat btn-sm btn-primary" type='button'>Close</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
form_ = new Vue({
    el: '#form_',
    data: {
      auth: <?= json_encode(get_user('h3_dealer_outbound_form_for_fulfillment')) ?>, 
      mode : '<?= $mode ?>',
      loading: false,
      errors: {},
      <?php if ($mode == 'detail' or $mode == 'edit'): ?>
      outbound_fulfillment: <?= json_encode($outbound_form_for_fulfillment) ?>,
      event: <?= json_encode($event) ?>,
      parts: <?= json_encode($parts) ?>,
      <?php else: ?>
      event: {},
      parts: [],
      <?php endif; ?>
    },
    methods: {
      <?= $form ?>: function(){
        post = {};
        post.id_event = this.event.id_event;
        post.parts = _.map(this.parts, function(p){
          return _.pick(p, ['id_part', 'id_gudang', 'id_rak', 'kuantitas'])
        });

        this.loading = true;
        axios.post('dealer/h3_dealer_outbound_form_for_fulfillment/<?= $form ?>', Qs.stringify(post))
        .then(function(res){
          window.location = 'dealer/h3_dealer_outbound_form_for_fulfillment/detail?k=' + res.data.id_outbound_form_for_fulfillment;
        })
        .catch(function(err){
          form_.errors = err.response.data;
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; })
      },
      transit: function(){
        this.loading = true;
        axios.get('dealer/h3_dealer_outbound_form_for_fulfillment/transit', {
          params: {
            k: this.outbound_fulfillment.id_outbound_form_for_fulfillment
          }
        })
        .then(function(res){
          window.location = 'dealer/h3_dealer_outbound_form_for_fulfillment/detail?k=' + form_.outbound_fulfillment.id_outbound_form_for_fulfillment;
        })
        .catch(function(err){
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; })
      },
      close: function(){
        this.loading = true;
        axios.get('dealer/h3_dealer_outbound_form_for_fulfillment/close', {
          params: {
            k: this.outbound_fulfillment.id_outbound_form_for_fulfillment
          }
        })
        .then(function(res){
          window.location = 'dealer/h3_dealer_outbound_form_for_fulfillment/detail?k=' + form_.outbound_fulfillment.id_outbound_form_for_fulfillment;
        })
        .catch(function(err){
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; })
      }
    },
    watch: {
      event: {
        deep: true,
        handler: function(){
          this.loading = true;
          axios.get('api/eventPart', {
              params: {
                  id_event: this.event.id_event
              }
          }).then(function(res) {
              form_.parts = res.data;
          }).catch(function(err) {
              toastr.error(err);
          })
          .then(function(){ form_.loading = false; });

          this.errors.id_event = null;
        }
      }
    }
});
</script>
<?php } elseif ($set=="index") { ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <?php if(can_access('h3_dealer_outbound_form_for_fulfillment', 'can_insert')): ?>
          <a href='<?= base_url("dealer/{$isi}/add") ?>'>
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="outbound_form_fulfillment" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nomor Outbound</th>
              <th>Tanggal Outbound</th>
              <th>Nomor Event</th>
              <th>Nama Event</th>
              <th>Surat Jalan</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            outbound_form_fulfillment = $('#outbound_form_fulfillment').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/outbound_form_for_fulfillment') ?>",
                    dataSrc: "data",
                    type: "POST"
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'id_outbound_form_for_fulfillment' },
                    { data: 'created_at' },
                    { data: 'id_event' },
                    { data: 'nama_event' },
                    { data: 'id_surat_jalan' },
                    { data: 'status' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
            outbound_form_fulfillment.on('draw.dt', function() {
                var info = outbound_form_fulfillment.page.info();
                outbound_form_fulfillment.column(0, {
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
    <?php } ?>
  </section>
</div>